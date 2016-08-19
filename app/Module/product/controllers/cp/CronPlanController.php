<?php
class Productcp_CronPlanController extends  Core2_Controller_Action_Cp
{
    public function init()
    {
        parent::init();
        
        $this->models['cron_plan'] = new Model_CronPlan();
    }
    
    /**
     * 首页
     */
    public function indexAction()
    {
        $this->_redirect('/productcp/cronplan/list');
    }
    
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
        ->from(array('t' => 'cron_plan'),array(new Zend_Db_Expr('COUNT(*)')))
        ->where('t.status = ?',1);
        
        /* 分页 */
        
        $count = $select->query()
        ->fetchColumn();
        $corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/productcp/tag/list?page={page}");
        $this->view->pagebar = $corepage->output();
        
        /* 列表 */
        
        $tagList = $select->reset(Zend_Db_Select::COLUMNS)
        ->columns('*','t')
        ->limitPage($corepage->currPage(),$perpage)
        ->query()
        ->fetchAll();
        $this->view->tagList = $tagList;
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
                    'href' => '/productcp/cronplan',
                    'text' => '分类列表')
            ));
        }
        
        if ($this->_request->isPost())
        {
            if (form($this))
            {
                $this->rows['cron_plan'] = $this->models['cron_plan']->createRow();
        
                /* 添加 */
        
                $this->rows['cron_plan']->script = $this->input->script;
                $this->rows['cron_plan']->params = $this->input->params;
                $this->rows['cron_plan']->run_time = $this->input->run_time;
                $contract = $this->rows['cron_plan']->save();
        
                if($contract)
                {
                    $this->_helper->notice('编辑成功','','success',array(
                        array(
                            'href' => "/productcp/cronplan",
                            'text' => '返回')
                    ));
                }
                else
                {
                    $this->_helper->notice('编辑失败','','error',array(
                        array(
                            'href' => "/productcp/cronplan",
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
        
        $this->rows['cron_plan'] = $this->models['cron_plan']->find($this->paramInput->id)->current();
        $this->rows['cron_plan']->status = -1;
        $this->rows['cron_plan']->save();
        
        if ($this->_request->isXmlHttpRequest())
        {
            $json['errno'] = 0;
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