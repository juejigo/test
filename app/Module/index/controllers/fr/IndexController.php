<?php

class IndexController extends Core2_Controller_Action_Fr 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();

		$this->models['member'] = new Model_Member();
		$this->models['product_catedata'] = new Model_ProductCatedata();
		$this->models['product_cate'] = new Model_ProductCate();
		$this->models['product'] = new Model_Product();
		$this->models['news'] = new Model_News();
		$this->models['news_data'] = new Model_NewsData();
		$this->models['news_cate'] = new Model_NewsCate();

	}
	
	public function indexAction()
	{
	    require_once 'includes/function/position.php';
	    require_once 'includes/function/tag.php';

	    $positionId1 = array('id' => '72','limit' => '5');
	    $position1 = tagdecode($positionId1,'app');

  		$cate1 = '418';
	    $cate2 = '419';
	    $cate3 = '455';
	    $limit = "";
	    if(isMobile())
	    {	
	    	//首页五个固定推荐分类
	    	$cachIdMobile = 'index_index_index_products_mobile';
	    	if ($this->_cache->test($cachIdMobile))
	    	{
	    		$position1 = $this->_cache->load($cachIdMobile);
	    	}
	    	else
	    	{
		    	$positionId1 = array('id' => '72','limit' => '5');
	      		$position1 = decode($positionId1,'app');
	      		$this->_cache->save($position1,$cachIdMobile);
	    	}
	        $this->view->postition3 = $position1;
	        $limit = 10;  
	    }
        $cacheId = 'index_index_index_products';
        $limit = 4;
	    
	    //查询出境游商品
	    if ($this->_cache->test($cacheId))
	    {
	    	$product = $this->_cache->load($cacheId);
	    	$cjyProduct = $product[0];
	    	$gnyProduct = $product[1];
	    	$zixProduct = $product[2];
	    	$newjxuanProduct = $product[3];
	    }
	    else
	    {
	    	//查询出境游商品
	    	$cjyProduct = getCatIdByProduct($cate1,$limit);
	    	
	    	//查询国内游商品
	    	$gnyProduct = getCatIdByProduct($cate2,$limit);
	    	//查询自由行商品
	    	$zixProduct = getCatIdByProduct($cate3,$limit);
	    	
	    	/* 标签推荐位*/
	    	$jxuan = array('id' => '40','limit' => '5');
	    	$newjxuanProduct = tagdecode($jxuan,'web');
	    	
/* 	    	$positionDatas = $this->_db->select()
		    	->from(array('t'=>'product_tagdata'))
		    	->joinLeft(array('p' => 'product'), 't.product_id = p.id')
		    	->where('t.tag_id = ?',$tagId)
		    	->order('t.order asc')
		    	->limit(5)
		    	->query()
		    	->fetchAll();
	    	$newjxuanProduct = array();
	    	if (!empty($positionDatas))
	    	{
	    		foreach ($positionDatas as $key => $positionData)
	    		{
	    			$positionData['title'] = empty($positionData['title']) ? ' ' : $positionData['title'];
	    			$positionData['image'] = empty($positionData['image']) ? DOMAIN.'static/style/default/mix/company/images/moren.gif' : $positionData['image'];
	    			$newjxuanProduct[$key]['productInfo'] = $positionData;
	    			$newjxuanProduct[$key]['url'] = DOMAIN.'product/product/detail/?id='.$positionData['id'];
	    		}
	    	} */
	    	
	    	$product[0] = $cjyProduct;
	    	$product[1] = $gnyProduct;
	    	$product[2] = $zixProduct;
	    	$product[3] = $newjxuanProduct;
	    	$this->_cache->save($product,$cacheId);
	    }

        /* 定义推荐位ID */
	    $banner = 'index_index_index_banner';
	    if ($this->_cache->test($cacheId))
	    {
	    	$positions = $this->_cache->load($banner);
	    }
	    else
	    {
	        $positionId1 = array('id' => '69','limit' => '10');
        	$positions = decode($positionId1,'web');
        	$this->_cache->save($positions,$banner);
	    }
        $this->view->positions = $positions;
        
        $this->view->jxuan_product = $newjxuanProduct;
        $this->view->cate_id= 0;
        $this->view->cjy_cate = $cate1;
        $this->view->gny_cate  = $cate2;
        $this->view->yl_cate  = $cate3;
        $this->view->cjy_product = $cjyProduct;
        $this->view->gny_product = $gnyProduct;
        $this->view->zyx_product = $zixProduct;
        
	}
	
	public function downloadAction()
	{
		$this->_redirect('http://a.app.qq.com/o/simple.jsp?pkgname=com.zzb.zzbang');
	}
}

?>