<?php

class Productapi_AppointmentController extends Core2_Controller_Action_Api
{
	/**
	 *  初始化
	 */
    public function init()
    {
        parent::init();
        $this->models['product_appointment'] = new Model_ProductAppointment();
    }
    
    /**
     *  首页
     */
    public function listAction()
    {
        if (!form($this))
        {
            $this->json['errno'] = '1';
            $this->json['errmsg'] = $this->error->firstMessage();
            $this->_helper->json($this->json);
        }
        $this->json['appointment_list'] = array();

        $select = $this->_db->select()
            ->from(array('p' => 'product_appointment'),array(new Zend_Db_Expr('COUNT(*)')))
            ->where('p.status <> ?',-1)
            ->where('p.member_id = ?', $this->_user->id);
        
         // 总数
		$count = $select->query()
			->fetchColumn();
		
		// 数据
		if($this->input->perpage == "")
		{
		    $this->input->perpage = 4;
		}
		$results = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','p')
			->limitPage($this->input->page,$this->input->perpage)
			->query()
			->fetchAll();
        
		for ($i=0;$i<count($results);$i++)
		{
		    $results[$i]['play_days'] =($results[$i]['end_time'] - $results[$i]['start_time'])/86400;
		    $results[$i]['start_time'] = date("Y-m-d",$results[$i]['start_time']);
		    $results[$i]['end_time'] = date("Y-m-d",$results[$i]['end_time']);
		    unset($results[$i]['add_time']);
		    unset($results[$i]['status']);
		}
		
        $this->json['appointment_list'] = $results;
        $this->json['errno'] = 0;
        $this->_helper->json($this->json);
    }
    
    /**
     * 添加预约
     */
    public function createAction()
    {
        if (!form($this))
        {
            $this->json['errno'] = '1';
            $this->json['errmsg'] = $this->error->firstMessage();
            $this->_helper->json($this->json);
        }
        
        $nums = $this->_db->select()
        	->from(array('a' => 'product_appointment'),'id')
        	->where('a.member_id = ?',$this->_user->id)
        	->where('a.status = ?',0)
        	->query()
        	->fetchColumn();
        if (!empty($nums))
        {
        	$this->json['errno'] = '1';
        	$this->json['errmsg'] = '你的预约单正在等待确认.';
        	$this->_helper->json($this->json);
        }
        
        //计算结束时间
        $end_time = strtotime($this->input->start_time)+(intval($this->input->play_days)*86400);
        
        $this->rows['product_appointment'] = $this->models['product_appointment']->createRow(array(
            'tourism_type' => $this->input->tourism_type,
            'start_time' => strtotime($this->input->start_time),
            'end_time' => $end_time,
            'destination' => $this->input->destination,
            'phone' => $this->input->phone,
            'dateline' => time(),
            'member_id' => $this->_user->id,
        ));
        $this->rows['product_appointment']->save();
        
        $this->json['errno'] = '0';
        $this->json['appointment_id'] = $this->rows['product_appointment']->id;
        $this->_helper->json($this->json);
    }
    
    /**
     * 详情
     */
    public function detailAction()
    {
        if (!form($this))
        {
            $this->json['errno'] = '1';
            $this->json['errmsg'] = $this->error->firstMessage();
            $this->_helper->json($this->json);
        }
        $appon = $this->_db->select()
            ->from(array('o' => 'product_appointment'))
            ->where('o.id = ?',$this->input->id)
            ->where('o.status <> ?',-1)
            ->query()
            ->fetch();
        
        $this->json['appointment'] = $appon;
        $this->json['errno'] = '0';
        $this->_helper->json($this->json);
    }
    
    /**
     * 删除预约
     */
    public function deleteAction()
    {
        if (!form($this))
        {
            $this->json['errno'] = '1';
            $this->json['errmsg'] = $this->error->firstMessage();
            $this->_helper->json($this->json);
        }

        $this->rows['product_appointment'] = $this->models['product_appointment']->find($this->input->id)->current();
        $this->rows['product_appointment']->status = -1;
        $this->rows['product_appointment']->save();
        
        $this->json['errno'] = '0';
        $this->_helper->json($this->json);
    }
    
    /**
     * 预约类型
     */
    public function speclistAction()
    {
        $spec = array(
            array('id' => 1,'tourism_type' => '跟团游'),
            array('id' => 2,'tourism_type' => '自助游'),
            array('id' => 3,'tourism_type' => '自由行'),
            array('id' => 4,'tourism_type' => '自驾游'),
            array('id' => 5,'tourism_type' => '目的地服务'), 
        );
        
        $this->json['spec'] = $spec;
        $this->json['errno'] = '0';
        $this->_helper->json($this->json);
        
    }
}

?>