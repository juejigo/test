<?php
class Productcp_VisaController extends Core2_Controller_Action_Cp
{
    public function init()
    {
        parent::init();
        
        $this->models['visa'] = new Model_Visa();
    }
    
    public function indexAction()
    {
        $this->_redirect('/productcp/visa/list');
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
            ->from(array('t' => 'visa'),array(new Zend_Db_Expr('COUNT(*)')))
            ->where('t.parent_id = ?',0)
            ->where('t.status = ?',1);

        /* 分页 */
        
        $count = $select->query()
            ->fetchColumn();

        $corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/productcp/visa/list?page={page}");
        $this->view->pagebar = $corepage->output();
        
        /* 列表 */
        
        $viasList = $select->reset(Zend_Db_Select::COLUMNS)
            ->columns('*','t')
            ->limitPage($corepage->currPage(),$perpage)
            ->query()
            ->fetchAll();
        $this->view->visaList = $viasList;
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
                $this->rows['visa'] = $this->models['visa']->createRow();
        
                /* 添加 */
        
                $this->rows['visa']->visa_name = $this->input->visa_name;
                $this->rows['visa']->content = $this->input->getUnescaped('content');
                $this->rows['visa']->status = 1;
                $visa = $this->rows['visa']->save();
        
                if($visa)
                {
                    $this->_helper->notice('编辑成功','','success',array(
                        array(
                            'href' => "/productcp/visa",
                            'text' => '返回')
                    ));
                }
                else
                {
                    $this->_helper->notice('编辑失败','','error',array(
                        array(
                            'href' => "/productcp/visa",
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
                    'href' => '/productcp/visa',
                    'text' => '返回')
            ));
        }
        
        $visa = $this->_db->select()
            ->from(array('o' => 'visa'))
            ->where('o.id = ?',$this->paramInput->id)
            ->query()
            ->fetch();
        $this->view->visa = $visa;
        
        if ($this->_request->isPost())
        {
            if (form($this))
            {
                /* 更新 */
                $this->rows['visa'] = $this->models['visa']->find($this->input->id)->current();
                $this->rows['visa']->visa_name = $this->input->visa_name;
                $this->rows['visa']->content = $this->input->getUnescaped('content');
                $addon = $this->rows['visa']->save();
        
                /* 提示 */
        
                if($addon)
                {
                    $this->_helper->notice('修改成功','','success',array(
                        array(
                            'href' => "/productcp/visa",
                            'text' => '返回')
                    ));
                }
                else
                {
                    $this->_helper->notice('修改失败','','error',array(
                        array(
                            'href' => "/productcp/visa",
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
            $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
                array(
                    'href' => 'javascript:history.back();',
                    'text' => '返回')
            ));
        }
        
        /* 删除合同*/
        
        $this->rows['visa'] = $this->models[visa]->find($this->paramInput->id)->current();
        $this->rows['visa']->status = -1;
        $this->rows['visa']->save();
        
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
    
    /**
     * 子类签证
     */
    public function childerlistAction()
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
            ->from(array('t' => 'visa'),array(new Zend_Db_Expr('COUNT(*)')))
            ->where('t.status = ?',1)
            ->where('t.parent_id = ?',$this->paramInput->id);
        
        
        $this->view->parent_id = $this->paramInput->id;
        /* 分页 */
        
        $count = $select->query()
        ->fetchColumn();
        
        $corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/productcp/visa/childerlist?page={page}");
        $this->view->pagebar = $corepage->output();
        
        /* 列表 */
        
        $viasList = $select->reset(Zend_Db_Select::COLUMNS)
            ->columns('*','t')
            ->limitPage($corepage->currPage(),$perpage)
            ->query()
            ->fetchAll();
        $this->view->childervisaList = $viasList;
    }
    
    /**
     * 添加
     */
    public function childeraddAction()
    {
        if (!params($this))
        {
            /* 提示 */
    
            $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
                array(
                    'href' => '/productcp/visa',
                    'text' => '分类列表')
            ));
        }
    
       $this->view->parent_id = $this->paramInput->parent_id;
        
        if ($this->_request->isPost())
        {
            if (form($this))
            {
                $items = $_REQUEST['items'];
                

                $this->rows['visa'] = $this->models['visa']->createRow();
    
                /* 添加 */
    
                $this->rows['visa']->visa_name = $this->input->visa_name;
                $this->rows['visa']->status = 1;
                $this->rows['visa']->parent_id = $this->input->parent_id;
                $this->rows['visa']->total = count($items);
                $visa_id = $this->rows['visa']->save();
                
                foreach ($items as $row)
                {
                    $this->rows['visa'] = $this->models['visa']->createRow(); 
                    $this->rows['visa']->visa_name = $this->input->visa_name;
                    $this->rows['visa']->status = 1;
                    $this->rows['visa']->parent_id = $visa_id;
                    $this->rows['visa']->info_name = $row['info_name'];
                    $this->rows['visa']->info_type = $row['info_type'];
                    $this->rows['visa']->info_total = $row['info_total'];
                    $this->rows['visa']->info_content = $row['info'];
                    $this->rows['visa']->info_file = $row['info_file'];
                    $visa = $this->rows['visa']->save();
                }

                if($visa)
                {
                    $this->_helper->notice('编辑成功','','success',array(
                        array(
                            'href' => "/productcp/visa",
                            'text' => '返回')
                    ));
                }
                else
                {
                    $this->_helper->notice('编辑失败','','error',array(
                        array(
                            'href' => "/productcp/visa",
                            'text' => '返回')
                    ));
                }
            }
        }
    }
    
    /**
     * 修改
     */
    public function childereditAction()
    {
        if (!params($this))
        {
            /* 提示 */
             
            $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
                array(
                    'href' => '/productcp/visa',
                    'text' => '返回')
            ));
        }
    
        $this->view->parent_id  = $this->paramInput->parent_id;
        
        $visa = $this->_db->select()
            ->from(array('o' => 'visa'))
            ->where('o.id = ?',$this->paramInput->id)
            ->query()
            ->fetch();

        $visaitem = $this->_db->select()
            ->from(array('o' => 'visa'))
            ->where('o.parent_id = ?',$this->paramInput->id)
            ->query()
            ->fetchAll();
       
        $visa['items'] = $visaitem;

        $this->view->visa = $visa;
        
        if ($this->_request->isPost())
        {
            if (form($this))
            {
                $items = $_REQUEST['items'];
                
                /* 更新 */

                $this->rows['visa'] = $this->models['visa']->find($this->input->id)->current();
                $this->rows['visa']->visa_name = $this->input->visa_name;
                $this->rows['visa']->content = $this->input->content;
                $this->rows['visa']->parent_id = $this->input->parent_id;
                $this->rows['visa']->total = count($items);
                $addon = $this->rows['visa']->save();

                $this->models['visa']->delete(array('parent_id = ?' =>$addon));
                
                foreach ($items as $row)
                {
                    $this->rows['visa'] = $this->models['visa']->createRow();
                    $this->rows['visa']->visa_name = $this->input->visa_name;
                    $this->rows['visa']->status = 1;
                    $this->rows['visa']->parent_id = $addon;
                    $this->rows['visa']->info_name = $row['info_name'];
                    $this->rows['visa']->info_type = $row['info_type'];
                    $this->rows['visa']->info_total = $row['info_total'];
                    $this->rows['visa']->info_content = $row['info'];
                    $this->rows['visa']->info_file = $row['info_file'];
                    $visa = $this->rows['visa']->save();
                }
                
                /* 提示 */
    
                    $this->_helper->notice('修改成功','','success',array(
                        array(
                            'href' => "/productcp/visa",
                            'text' => '返回')
                    ));
            }
        }
    }
    
    /**
     * 删除
     */
    public function childerdeleteAction()
    {
        /* 取消视图 */
    
        $this->_helper->viewRenderer->setNoRender();
    
        $json = array();
    
        if (!params($this))
        {
            $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
                array(
                    'href' => 'javascript:history.back();',
                    'text' => '返回')
            ));
        }
    
        /* 删除合同*/
    
        $this->rows['visa'] = $this->models[visa]->find($this->paramInput->id)->current();
        $this->rows['visa']->status = -1;
        $this->rows['visa']->save();
        
         $this->models['visa']->delete(array('parent_id = ?' =>$this->paramInput->id));
    
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
    
    /**
     * 上传文件
     */
    public function fileupAction()
    {
	    $image = new Core2_File();
	    
	    $file=$image->upload('imgFile');
	    
        if($file)
        {
            $json['error'] = 0;
            $json['url'] = "/../../".$file;
            $this->_helper->json($json);
        }
        else
        {
            $json['error'] = 1;
            $json['message'] ="上传失败";
            $this->_helper->json($json);
        }
    }
    
}