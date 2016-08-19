<?php 
class Productcp_FeedbackController extends Core2_Controller_Action_Cp
{
    public function init()
    {
        parent::init();
        
        $this->models['product_feedback'] = new Model_ProductFeedback();
    }
    
    /**
     * 首页
     */
    public function indexAction()
    {
        $this->_redirect('/productcp/feedback/list');
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
            ->from(array('t' => 'product_feedback'),array(new Zend_Db_Expr('COUNT(*)')))
            ->where('t.product_id = ?',$this->paramInput->id)
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
}



