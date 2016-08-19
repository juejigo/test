<?php
class Productcp_ContractController extends Core2_Controller_Action_Cp
{
  public function init()
    {
        parent::init();
        
        $this->models['contract'] = new Model_Contract();
    }
    
    public function indexAction()
    {
        $this->_redirect('/productcp/contract/list');
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
            ->from(array('t' => 'contract'),array(new Zend_Db_Expr('COUNT(*)')))
            ->where('t.status = ?',1);
        
        /* 分页 */
        
        $count = $select->query()
        ->fetchColumn();
        $corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/productcp/tag/list?page={page}");
        $this->view->pagebar = $corepage->output();
        
        /* 列表 */
        
        $contractList = $select->reset(Zend_Db_Select::COLUMNS)
        ->columns('*','t')
        ->limitPage($corepage->currPage(),$perpage)
        ->query()
        ->fetchAll();
       $this->view->contractList = $contractList;
    }
    
    /**
     * 添加
     */
    public function addAction()
    {
        if ($this->_request->isPost())
        {
            if (form($this))
            {
                $this->rows['contract'] = $this->models['contract']->createRow();
    
                /* 添加 */
    
                $this->rows['contract']->contract_name = $this->input->contract_name;
                $this->rows['contract']->content = $this->input->getUnescaped('content');
                $this->rows['contract']->status = 1;
                $contract = $this->rows['contract']->save();
    
                if($contract)
                {
                    $this->_helper->notice('编辑成功','','success',array(
                        array(
                            'href' => "/productcp/contract",
                            'text' => '返回')
                    ));
                }
                else
                {
                    $this->_helper->notice('编辑失败','','error',array(
                        array(
                            'href' => "/productcp/contract",
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
                    'href' => '/productcp/contract',
                    'text' => '返回')
            ));
        }
    
        //查询数据
        $contract = $this->_db->select()    
            ->from(array('o' => 'contract'))
            ->where('o.id = ?',$this->paramInput->id)
            ->query()
            ->fetch();
        
        $this->view->contract = $contract;
        
        if ($this->_request->isPost())
        {
            if (form($this))
            {
                
                $this->rows['contract'] = $this->models['contract']->find($this->input->id)->current();
                /* 更新 */
    
                $this->rows['contract']->contract_name = $this->input->contract_name;
                $this->rows['contract']->content = $this->input->getUnescaped('content');
                $contract = $this->rows['contract']->save();
    
                /* 提示 */
    
                if($contract)
                {
                    $this->_helper->notice('修改成功','','success',array(
                        array(
                            'href' => "/productcp/contract",
                            'text' => '返回')
                    ));
                }
                else
                {
                    $this->_helper->notice('修改失败','','error',array(
                        array(
                            'href' => "/productcp/contract",
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
    
        /* 删除行程*/
    
        $this->rows['contract'] = $this->models['contract']->find($this->paramInput->id)->current();
        $this->rows['contract']->status = -1;
        $this->rows['contract']->save();
        
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