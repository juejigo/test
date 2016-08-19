<?php

class Cart_CartController extends Core2_Controller_Action_Fr   
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
			->from(array('c' => 'cart'),array('id','item_id','num'))
			->joinLeft(array('i' => 'product_item'),'i.id = c.item_id',array('product_id','area','item_name','image','price','spec_desc','specval_1','specval_2','i.stock'))
			->where('c.member_id = ?',$this->_user->id)
			->query()
			->fetchAll();
		
		// 统计价格
		$cartList = array('amount' => 0,'products' => array());
		foreach ($carts as $cart)
		{
			$cart['image'] = thumbpath($cart['image'],220);
			$cart['stock'] = $cart['stock'];
			$cart['subtotal'] = $cart['price'] * $cart['num'];
			$cartList['amount'] = empty($cartList['amount']) ? $cart['subtotal'] : $cartList['amount'] + $cart['subtotal'];
			$cartList['products'][] = $cart;
		}
		$cartList['cartCount'] = count($cartList['products']);

		$this->view->cartList = $cartList;
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

		$this->rows['cart'] = $this->models['cart']->fetchRow(
			$this->models['cart']->select()
				->where('item_id = ?',$this->input->item_id)
				->where('member_id = ?',$this->_user->id)
		);
		if (empty($this->rows['cart'])) 
		{
			$this->rows['cart'] = $this->models['cart']->createRow(array(
				'item_id' => $this->input->item_id,
				'member_id' => $this->_user->id,
			));
		}
		$this->rows['cart']->num += $this->input->num;
		$this->rows['cart']->save();
		
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
		
		$this->rows['cart'] = $this->models['cart']->fetchRow(
			$this->models['cart']->select()
				->where('id = ?',$this->input->id)
				->where('member_id = ?',$this->_user->id)
		);
		$this->rows['cart']->num = $this->input->num;
		$this->rows['cart']->save();
		
		$this->json['errno'] = '0';
		$this->json['num'] = $this->input->num;
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
	
	/**
	 *  全部删除
	 */
	public function deleteallAction()
	{
		$this->_db->delete('cart',array('member_id = ?' => $this->_user->id));
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>