<?php

class Cartapi_CartController extends Core2_Controller_Action_Api   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['cart'] = new Model_Cart();
	}
	
	/**
	 *  购物车
	 */
	public function listAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取全部购物车产品 */
		
		$carts = $this->_db->select()
			->from(array('c' => 'cart'),array('id','item_id','num','selected'))
			->joinLeft(array('i' => 'product_item'),'i.id = c.item_id',array('price','product_id','spec_desc'))
			->joinLeft(array('p' => 'product'),'p.id = i.product_id',array('product_name','image','prices'))
			->where('c.member_id = ?',$this->_user->id)
			->query()
			->fetchAll();
		
		// 统计价格
		$cartList = array('amount' => 0,'products' => array());
		foreach ($carts as $cart)
		{
			/* 生成进货单数组 */
			
			if (!array_key_exists($cart['product_id'],$cartList['products'])) 
			{
				$product = array();
				$product['product_name'] = $cart['product_name'];
				$product['image'] = thumbpath($cart['image'],220);
				$product['prices'] = Zend_Serializer::unserialize($cart['prices']);
				$product['item_num'] = 0;
				
				/* 选中的计入购物车价格 */
				if ($cart['selected'] == 1) 
				{
					$product['item_num'] = $cart['num'];
				}
				
				$product['items'] = array();
				$item = array();
				$item['id'] = $cart['item_id'];
				$item['price'] = $cart['price'];
				$item['cart_id'] = $cart['id'];
				$item['spec_desc'] = $cart['spec_desc'];
				$item['num'] = $cart['num'];
				$item['selected'] = $cart['selected'];
				$product['items'][] = $item;
				
				$cartList['products'][$cart['product_id']] = $product;
			}
			else 
			{
				/* 选中的计入购物车价格 */
				if ($cart['selected'] == 1) 
				{
					$cartList['products'][$cart['product_id']]['item_num'] += $cart['num'];
				}
				
				$item = array();
				$item['id'] = $cart['item_id'];
				$item['price'] = $cart['price'];
				$item['cart_id'] = $cart['id'];
				$item['spec_desc'] = $cart['spec_desc'];
				$item['num'] = $cart['num'];
				$item['selected'] = $cart['selected'];
				$cartList['products'][$cart['product_id']]['items'][] = $item;
			}
		}
		
		$products = array();
		foreach ($cartList['products'] as $i => $product) 
		{
			/* 价格 */
			if (!empty($product['prices'])) 
			{
				// 历史订购数
				$product['history_num'] = 0;
				$result = $this->_db->select()
					->from(array('h' => 'order_history'),array('num'))
					->where('h.member_id = ?',$this->_user->id)
					->where('h.product_id = ?',$i)
					->query()
					->fetchColumn();
				if (!empty($result)) 
				{
					$product['history_num'] += $result;
				}
				$product['history_num'] += $product['item_num'];
				
				/* 是否符合第三批次 */
				if (!empty($product['prices'][2]['num']) && $product['history_num'] >= $product['prices'][2]['num']) 
				{
					$product['price'] = $product['prices'][2]['price'];
				}
				/* 是否符合第二批次 */
				else if (!empty($product['prices'][1]['num']) && $product['history_num'] >= $product['prices'][1]['num']) 
				{
					$product['price'] = $product['prices'][1]['price'];
				}
				else
				{
					$product['price'] = $product['prices'][0]['price'];
				}
			}
			
			$product['subtotal'] = $product['item_num'] * $product['price'];
			$cartList['amount'] = empty($cartList['amount']) ? $product['subtotal'] : $cartList['amount'] + $product['subtotal'];
			
			$product['id'] = $i;
			$products[] = $product;
		}
		
		/* 去键值 方便 app 处理 json */
		unset($cartList['products']);
		$cartList['products'] = $products;
		
		$this->json['cart_list'] = $cartList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  添加购物车
	 */
	public function addAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		foreach ($this->data['items'] as $item) 
		{
			$this->rows['cart'] = $this->models['cart']->fetchRow(
				$this->models['cart']->select()
					->where('item_id = ?',$item['item_id'])
					->where('member_id = ?',$this->_user->id)
			);
			if (empty($this->rows['cart'])) 
			{
				$this->rows['cart'] = $this->models['cart']->createRow(array(
					'item_id' => $item['item_id'],
					'member_id' => $this->_user->id,
				));
			}
			$this->rows['cart']->num += $item['num'];
			$this->rows['cart']->save();
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  选中
	 */
	public function selectAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		foreach ($this->input->id as $id) 
		{
			$this->rows['cart'] = $this->models['cart']->find($id)->current();
			$this->rows['cart']->selected = ($this->rows['cart']->selected == 1) ? 0 : 1;
			$this->rows['cart']->save();
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  改变数量
	 */
	public function numAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		foreach ($this->data['carts'] as $cart) 
		{
			$this->rows['cart'] = $this->models['cart']->find($cart['cart_id'])->current();
			$this->rows['cart']->num = $cart['num'];
			$this->rows['cart']->save();
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  删除购物车
	 */
	public function deleteAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['cart'] = $this->models['cart']->find($this->input->id)->current();
		$this->rows['cart']->delete();
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>