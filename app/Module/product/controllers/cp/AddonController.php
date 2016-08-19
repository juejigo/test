<?php
class Productcp_AddonController extends  Core2_Controller_Action_Cp
{
    public function init()
    {
        parent::init();
        
        $this->models['product_addon'] = new Model_ProductAddon();
    }
    
    /**
     * 首页
     */
    public function indexAction()
    {
        $this->_redirect('/productcp/addon/list');
    }
    
    /**
     * 列表
     */
    public function listAction()
    {
        /* 检验传值 */
        
        if (!params($this))
        {
            /* 提示 */
            
            $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
                array(
                    'href' => '/admincp',
                    'text' => '返回')
            ));
        }

		/* 构造 SQL 选择器 */
        
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('t' => 'product_addon'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('t.status = ?',1)
			->where('t.addon_type = ?',0);
		
		/* 分页 */
		
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/productcp/addon/list?page={page}");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		
		$addonList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','t')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->addonList = $addonList;
    }
    
    /**
     * 添加
     */
    public function addAction()
    {
        if (!params($this))
        {
            /* 提示 */
            
            $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
                array(
                    'href' => '/productcp/addon/list',
                    'text' => '保险列表')
            ));
        }
        
        if ($this->_request->isPost())
        {
            if (form($this))
            {

                $this->rows['product_addon'] = $this->models['product_addon']->createRow();
        
                /* 添加 */
                $this->rows['product_addon']->title = $this->input->title;
                $this->rows['product_addon']->addon_name = $this->input->addon_name;
                $this->rows['product_addon']->addon_type = 0;
                $this->rows['product_addon']->type = $this->input->type;
                $this->rows['product_addon']->price = $this->input->price;
                $this->rows['product_addon']->info = $this->input->getUnescaped('info');
                $addon = $this->rows['product_addon']->save();
        
                if($addon)
                {
                    $this->_helper->notice('编辑成功','','success',array(
                        array(
                            'href' => "/productcp/addon/list",
                            'text' => '返回')
                    ));
                }
                else 
                {
                    $this->_helper->notice('编辑失败','','error',array(
                        array(
                            'href' => "/productcp/addon/list",
                            'text' => '返回')
                    ));
                }
            }
        }
    }
    
    /**
     * 修改
     */
    public function editAction()
    {
        if (!params($this))
        {
            /* 提示 */
            	
            $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
                array(
                    'href' => '/productcp/addon/list',
                    'text' => '返回')
            ));
        }

        //查询信息
        $addon = $this->_db->select()
            ->from(array('o' => 'product_addon'))
            ->where('o.id = ?',$this->paramInput->id)
            ->query()
            ->fetch();
        
        $this->view->addon = $addon;

        if ($this->_request->isPost())
        {
            if (form($this))
            {
                /* 更新 */
                $this->rows['product_addon'] = $this->models['product_addon']->find($this->input->id)->current();
                $this->rows['product_addon']->title = $this->input->title;
                $this->rows['product_addon']->addon_name = $this->input->addon_name;
                $this->rows['product_addon']->type = $this->input->type;
                $this->rows['product_addon']->price = $this->input->price;
                $this->rows['product_addon']->info = $this->input->getUnescaped('info');
                $addon = $this->rows['product_addon']->save();
        
                /* 提示 */
                if($addon)
                {
                    $this->_helper->notice('修改成功','','success',array(
                        array(
                            'href' => "/productcp/addon/list",
                            'text' => '返回')
                    ));
                }
                else 
                {
                    $this->_helper->notice('修改失败','','error',array(
                        array(
                            'href' => "/productcp/addon/list",
                            'text' => '返回')
                    ));
                }
            }
        }
    }
    
    /**
     * 删除
     */
    public function deleteAction()
    {
        /* 取消视图 */
        
        $this->_helper->viewRenderer->setNoRender();
        
        $json = array();
        
        if (!params($this))
        {
            if ($this->_request->isXmlHttpRequest())
            {
                $json['errno'] = 1;
                $json['errmsg'] = $this->error->firstMessage();
                $this->_helper->json($json);
            }
            else
            {
                $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
                    array(
                        'href' => 'javascript:history.back();',
                        'text' => '返回')
                ));
            }
        }
        
        /* 删除合同*/
        
        $this->rows['product_addon'] = $this->models['product_addon']->find($this->paramInput->id)->current();
        $this->rows['product_addon']->status = -1;
        $this->rows['product_addon']->save();
        
        if ($this->_request->isXmlHttpRequest())
        {
            $json['errno'] = 1;
            $this->_helper->json($json);
        }
        else
        {
            $this->_helper->notice('删除成功','','success',array(
                array(
                    'href' => 'javascript:history.back();',
                    'text' => '返回')
            ));
        }
    }
    
}