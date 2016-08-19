<?php

class Newsapi_NewsController extends Core2_Controller_Action_Api  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  文章详情
	 */
	public function detailAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 文章 */
		
		$news = array();
		
		$r = $select = $this->_db->select()
			->from(array('a' => 'news'))
			->joinLeft(array('d' => 'news_data'),'d.news_id = a.id',array('content'))
			->where('a.id = ?',$this->input->id)
			->query()
			->fetch();
		
		$news['title'] = $r['title'];
		$news['image'] = thumbpath($r['image'],220);
		
		// 解决图文详情图片宽度自适应屏幕
		$news['content'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml"><head><meta content="width=device-width,height=device-height,initial-scale=1,user-scalable=0" name="viewport">
		<script src="' . DOMAIN . 'static/style/default/js/lib/jquery-1.9.1.min.js"></script>
		<script>$(function()
		{
			var as = setInterval(\'autosize()\',1);
		})
		function autosize()
		{
			$(\'img\').each(function (i,e) 
			{
				var iwidth = $(e).width();
				var windowWidth = $(window).width();
				var twidth = windowWidth - 40;
				
				if (iwidth > twidth)
				{
					$(e).css(\'width\',twidth + \'px\');
				}
			});
		}</script></head><body>' . htmlspecialchars_decode(stripcslashes($r['content'])) .'</body></html>';
		
		$news['views'] = $r['views'];
		$news['dateline'] = $r['dateline'];
		
		$this->json['news'] = $news;
		
		/* 浏览 + 1 */
		
		$this->_db->update('news',array('views' => new Zend_Db_Expr('views + 1')),array('id = ?' => $this->input->id));
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>