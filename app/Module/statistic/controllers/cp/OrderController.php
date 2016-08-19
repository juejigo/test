<?php
class Statisticcp_OrderController extends Core2_Controller_Action_Cp 
{
    /**
     *  初始化
     */
    public function init()
    {
        parent::init();
    }

	public function indexAction()
	{
		$this->_redirect('/statisticcp/order/sales');
	}

	public function salesAction()
	{
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/admincp',
					'text' => '返回')
				));
		}
	}

    public function statusAction()
    {
      if (!params($this))
     {
       $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
        array(
          'href' => '/admincp',
          'text' => '返回')
        ));
      }
    }

    /**
     *  返回销售统计
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
      $json['errno'] = 'error';
      $json['errmsg'] = $this->error->firstMessage();
      $this->_helper->json($json);
    }

      $start_date  = $this->_request->getPost('dateline_from','');
      $end_date = $this->_request->getPost('dateline_to','');
      if(empty($start_date))
       {
         $start_date='2015-01-01';
       }
      if(empty($end_date))
      {
         $end_date='2015-12-31';
      }
        $start_arr = explode("-", $start_date);
        $end_arr = explode("-", $end_date);

        $start_year = intval($start_arr[0]);
        $start_month = intval($start_arr[1]);
        $start_day =intval($start_arr[2]);

        $end_year = intval($end_arr[0]);
        $end_month = intval($end_arr[1]);
        $end_day =intval($end_arr[2]);

        $diff_year = $end_year-$start_year;

        $month_arr = array();
        
		/* 获取月份 */
    	
        if($diff_year == 0)
		{
			for($month = $start_month;$month<=$end_month;$month++)
			{
				$month_arr[] = $start_year.'-'.$month;
			}
		} 
		else 
		{
			for($year =$start_year;$year<=$end_year;$year++)
			{
				if($year == $start_year)
					{
						for($month = $start_month;$month<=12;$month++)
						{
							$month_arr[] = $year.'-'.$month;
						}
					}
				elseif($year==$end_year)
				{
					for($month = 1;$month<=$end_month;$month++)
					{
						$month_arr[] = $year.'-'.$month;
					}
				}
				else
				{
					for($month = 1;$month<=12;$month++)
					{
						$month_arr[] = $year.'-'.$month;
					}           
				}
			}
		}
        
        //创造待查的日期数组      
        $summ=array();
        foreach($month_arr as $k => $v)
        {
           $summ[$v] = 0;
        }


      switch ($op) {
         case 'sales':

        /* 获取订单日期数据 */
        $memsum = $this->_db->select()
            ->from(array('o' => 'order'),array('pay_time'))       
            ->where('pay_time > ?',strtotime("$start_year-$start_month-$start_day 00:00:00"))
            ->where('pay_time < ?',strtotime("$end_year-$end_month-$end_day 23:59:59"))           
            ->query()
            ->fetchAll();

        //转换日期格式    
        $date = array();
        for($i=0;$i<count($memsum);$i++)
        {
          $date[$i]=date('Y-n',$memsum[$i]['pay_time']);
        }
        
        //统计下单数量
        foreach($date as $k => $v)
        {
          $summ[$v]++;
        }
        
        //转换键值类型
        foreach($summ as $k => $v)
        {
          $summ[$k]=intval($v,10);
        }
        
        //转换数组格式
        $i=0;
        $j=0;
        foreach($summ as $k=>$v)
         {
           $c[$i][$j]=$k;
           $c[$i][$j+1]=$v;
           $i++;
         }

        if($c)
         {
          $bool=true;
         }
         else
         {
          $bool=false;
         }

        $result = array(
          'datas' => array(
            array('name'=>"$start_year",'data' => $c
                
            )
           ), 
        'success' => $bool
        );
         $this->_helper->json($result);
         break;

         case 'status':
         /* 获取订单日期数据*/
       $memsum = $this->_db->select()
            ->from(array('o' => 'order'),array('status'))       
            ->where('dateline > ?',strtotime("$start_year-$start_month-$start_day 00:00:00"))
            ->where('dateline < ?',strtotime("$end_year-$end_month-$end_day 23:59:59"))           
            ->query()
            ->fetchAll();
            
            //未付款、待发货、已完成、退货
            $nopay=0;
            $pay=0;
            $finish=0;
            $return=0;

              for($i=0;$i<count($memsum);$i++)
              {
                if($memsum[$i]['status']==0)
                {
                    $nopay++;
                }
                if($memsum[$i]['status']==1)
                {
                    $pay++;
                }
                if($memsum[$i]['status']==3)
                {
                    $finish++;
                }
                if($memsum[$i]['status']==13)
                {
                    $return++;
                }
              }
            
            //订单总数
            $sum=$nopay+$pay+$finish+$return;

            if($sum==0)
             {
              $bool = false;
             }
             else
             {
              $bool = true;
             }

            //百分比
            $pay=$pay/$sum;
            $nopay=$nopay/$sum;
            $finish=$finish/$sum;
            $return=$return/$sum;

            //转换数组
            $status=array(
                 array('待发货',$pay),
                 array('未付款',$nopay),
                 array('已退款',$return),
                 array('已完成',$finish)
                );

               $result = array(
                'datas' => array(
                   array('name'=>"$start_year",'data' => $status               
                   )
                 ), 
                'success' => $bool
                );
              $this->_helper->json($result);

          break;

          default:
          break;

    }
 $this->_helper->json($json);
    
}
  

}


?>








