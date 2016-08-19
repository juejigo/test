<?php

class Productcp_ImageController extends Core2_Controller_Action_Cp  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['product'] = new Model_Product();
		$this->models['product_image'] = new Model_ProductImage();
	}
	
	public function indexAction()
	{
	    $this->_redirect('/productcp/cate/add');
	}
	
	public function addAction()
	{
	    if (!params($this))
	    {
	        $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
	            array(
	                'href' => '/admincp',
	                'text' => '返回')
	        ));
	    }
	    $product_id = $this->paramInput->id;
	    
	    $this->view->product_id = $product_id;
	    
	    $images = $this->_db->select()
    	    ->from(array('o' => 'product_image'),array('image','id'))
    	    ->where('o.product_id = ?',$product_id)
    	    ->where('o.status = ?',1)
    	    ->order('o.order asc')
    	    ->query()
    	    ->fetchAll();
	    
	    //查询默认图片
	    $default = $this->_db->select()
	       ->from(array('i' => 'product'),array('image','id','product_name'))
	       ->where('i.id = ?',$product_id)
	       ->query()
	       ->fetch();
	    
	    $this->view->defaultimg = $default;

	    $this->view->images = $images;
	}
	
	/**
	 * 行程上传图片
	 */
	public function tripimguploadAction()
	{
	    /* 取消视图 */
	    $this->_helper->viewRenderer->setNoRender();
	    
	    /* 初始化变量 */
	    $this->_vars['json'] = array();
	    $this->_vars['image'] = array();

	    $image = new Core2_Image('prodId');
	    if (!$this->_vars['image'] = $image->upload('file'))
	    {
	        $this->_vars['json']['errno'] = '0';
	        $this->_vars['json']['msg'] = '图片格式错误或图片过大';
	        $this->_helper->json($this->_vars['json']);
	    }
	
	    $this->_vars['json']['errno'] = '1';
	    $this->_vars['json']['url'] = $this->_vars['image']['url'];
	    $this->_helper->json($this->_vars['json']);
	}
	
	
	/**
	 *  商品图片上传
	 */
	public function imguploadAction()
	{
	    if (!form($this))
	    {
	        $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
	            array(
	                'href' => '/admincp',
	                'text' => '返回')
	        ));
	    }
	    /* 取消视图 */
	    $this->_helper->viewRenderer->setNoRender();
	
	    /* 初始化变量 */
	    $this->_vars['json'] = array();
	    $this->_vars['image'] = array();
	    
	    $product_id = $this->input->product_id;
	    
	    $image = new Core2_Image('product');

	    if (!$this->_vars['image'] = $image->upload('file'))
	    {
	        $this->_vars['json']['errno'] = '0';
	        $this->_vars['json']['msg'] = '图片格式错误或图片过大';
	        $this->_helper->json($this->_vars['json']);
	    }
	    $data = array(
	        'product_id' =>  $product_id,
	        'image' => $this->_vars['image']['url'],
	        'order' =>0,
	        'status' => 1,
	    );

	    
	    /*添加图片*/
	    $this->rows['product_image'] = $this->models['product_image']->createRow(array(
	        'product_id' =>  $product_id,
	        'image' => $this->_vars['image']['url'],
	        'order' =>0,
	        'status' => 1,
	    ));

	    $count = $this->_db->select()
    	    ->from(array('i' => 'product_image'),array(new Zend_Db_Expr('COUNT(*)')))
    	    ->where('i.product_id = ?',$product_id)
    	    ->where('i.main = ?',1)
    	    ->query()
    	    ->fetchColumn();
	    if ($count == 0)
	    {
	        $this->rows['product_image']->main = 1;
	    }
	   $this->rows['product_image']->save();

	    
	    $this->_vars['json']['errno'] = '1';
	    $this->_vars['json']['imgid'] = $this->rows['product_image']->id;
	    $this->_vars['json']['url'] = $this->_vars['image']['url'];
	    $this->_helper->json($this->_vars['json']);
	}
	
	/**
	 * 设置商品主图
	 */
    public function mainimgAction()
    {
        $product_id = $_POST['product_id'];
        $id = $_POST['id'];
        
        $image = $this->_db->select()
                ->from(array('o' => 'product_image'),array('image'))
                ->where('o.product_id = ?',$product_id)
                ->where('o.id = ?',$id)
                ->query()
                ->fetch();
        
        $this->rows['product_image'] = $this->models['product_image']->find($id)->current();
        $this->rows['product_image']->main =  1;
        $this->rows['product_image']->save();
        
        
        //修改商品主图
    	$this->rows['product'] = $this->models['product']->find($product_id)->current();
    	$this->rows['product']->image =  $image['image'];
    	$this->rows['product']->save();
        
    	$json['errno'] = 1;
    	$json['errmsg'] = "操作成功";
    	$this->_helper->json($json);
        
    }
	
    /**
     * 删除图片
     */
    public function deleteimgAction()
    {
        $id = $_POST['id'];
        
        $this->rows['product_image'] = $this->models['product_image']->find($id)->current();
        $this->rows['product_image']->status =  -1;
        $image = $this->rows['product_image']->save();
        
        if($image)
        {
            $json['errno'] = 1;
            $json['errmsg'] = "操作成功";
            $this->_helper->json($json);
        }
        else 
        {
            $json['errno'] = 0;
            $json['errmsg'] = "操作失败";
            $this->_helper->json($json);
        }
       
    }
    
    /**
     * 修改图片位置
     */
	public function sortimgAction()
	{
	    $order = $_POST['order'];

	    for($i=0;$i<count($order);$i++)
	    {
	        $this->rows['product_image'] = $this->models['product_image']->find($order[$i])->current();
	        $this->rows['product_image']->order =  $i;
	        $image = $this->rows['product_image']->save();
	    }
	    
	    $json['errno'] = 1;
	    $json['errmsg'] = "操作成功";
	    $this->_helper->json($json);
	    
	    
	}
	
	/**
	 *  ajax
	 */
	public function ajaxAction()
	{
		if (!$this->_request->isXmlHttpRequest()) 
		{
			exit ;
		}
		
		$op = $this->_request->getQuery('op','');
		$json = array();
		$this->_helper->viewRenderer->setNoRender();
		
		if (!ajax($this)) 
		{
			$json['flag'] = 'error';
			$json['msg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		switch ($op)
		{
			case 'insert' :
				$this->rows['product_image'] = $this->models['product_image']->createRow(array(
					'member_id' => $this->_user->id,
					'product_id' => $this->input->product_id,
					'image' => $this->input->image,
				));
				
				/* 默认主图 */
				
				if ($this->input->product_id == 0) 
				{
					$count = $this->_db->select()
						->from(array('i' => 'product_image'),array(new Zend_Db_Expr('COUNT(*)')))
						->where('i.member_id = ?',$this->_user->id)
						->where('i.product_id = ?',0)
						->where('i.main = ?',1)
						->query()
						->fetchColumn();
				}
				else 
				{
					$count = $this->_db->select()
						->from(array('i' => 'product_image'),array(new Zend_Db_Expr('COUNT(*)')))
						->where('i.product_id = ?',$this->input->product_id)
						->where('i.main = ?',1)
						->query()
						->fetchColumn();
				}
				if ($count == 0) 
				{
					$this->rows['product_image']->main = 1;
				}
				
				$this->rows['product_image']->save();
				$this->view->image = $this->rows['product_image']->toArray();
				$json['flag'] = 'success';
				$json['html'] = $this->view->render('productcp/image/ajax_add.tpl');
				break ;
			
			case 'setdefault':
				$this->rows['product_image'] = $this->models['product_image']->find($this->input->image_id)->current();
				$this->rows['product_image']->main = 1;
				$this->rows['product_image']->save();
				 $json['errno'] = 1;
                 $json['errmsg'] = "操作成功";
				break;
				
			case 'setorder':
				$this->rows['product_image'] = $this->models['product_image']->find($this->input->image_id)->current();
				$this->rows['product_image']->order = $this->input->order;
				$this->rows['product_image']->save();
				$json['flag'] = 'success';
				break;
			
			case 'delete' :
			    
				$this->rows['product_image'] = $this->models['product_image']->find($this->input->image_id)->current();
				$this->rows['product'] = $this->models['product']->find($this->rows['product_image']->product_id)->current();

				/* 如果删除的是默认图片 */
				
				if (!empty($this->rows['product']) && ($this->rows['product_image']->image == $this->rows['product']->image)) 
				{
					$image = $this->_db->select()
						->from(array('i' => 'product_image'),array('image'))
						->where('i.product_id = ?',$this->rows['product_image']->product_id)
						->where('i.id <> ?',$this->rows['product_image']->id)
						->where('i.status <> ?',-1)
						->order('i.id ASC')
						->query()
						->fetchColumn();

					if (!empty($image)) 
					{
						$this->rows['product']->image = $image;
						$this->rows['product']->save();
					}
				}
				
				$this->rows['product_image']->delete();
				 $json['errno'] = 1;
                 $json['errmsg'] = "操作成功";
				break ;
			
			default :
				break ;
		}
		
		$this->_helper->json($json);
	}
}

?>