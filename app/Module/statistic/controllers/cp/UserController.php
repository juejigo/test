<?php
class Statisticcp_UserController extends Core2_Controller_Action_Cp 
{
    /**
     *  初始化
     */
    public function init()
    {
        parent::init();
    }

    /**
     *  首页
     */
    
    public function indexAction() 
    {
       $this->_redirect('/statisticcp/user/register');        
    }

     public function registerAction()
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

     public function buyAction()
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
      *  返回注册统计
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
    //获取月份
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
         case 'register':
           $memsum = $this->_db->select()
            ->from(array('m' => 'member'),array('register_time'))
            ->where('register_time > ?',strtotime("$start_year-$start_month-$start_day 00:00:00"))
            ->where('register_time < ?',strtotime("$end_year-$end_month-$end_day 23:59:59"))   
            ->query()
            ->fetchAll();
  
        //转换日期格式  
        $date = array();
        for($i=0;$i<count($memsum);$i++)
        {
          $date[$i]=date('Y-n',$memsum[$i]['register_time']);
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
         if($summ)
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
       //print_r($result);
         $this->_helper->json($result); 
          break;

      case 'buy':

         /*获取买家数据 */

        $memsum = $this->_db->select()
            ->from(array('o' => 'order'),array('buyer_id'))       
            ->where('dateline > ?',strtotime("$start_year-$start_month-$start_day 00:00:00"))
            ->where('dateline < ?',strtotime("$end_year-$end_month-$end_day 23:59:59")) 
            ->where('status = ?',3)          
            ->query()
            ->fetchAll();
        
        //提取买家id
        foreach($memsum as $k => $v)
        {
          foreach($v as $a => $b)
            $arr[]=$b;
        }    
        
        //id 下单数
        $arr1=array();
        foreach ($arr as $a)
        {
          if (!isset($arr1[$a]))
          {
            $arr1[$a] = 1;
          }
          else 
          {
            $arr1[$a]++;
          }
        }
        
        //相同订单数的id数量
        $arr2=array();
        foreach ($arr1 as $a)
        {
          if (!isset($arr2[$a]))
          {
             $arr2[$a] = 1;
          }
          else 
          {
             $arr2[$a]++;
          }
        }        
         ksort($arr2);

         //完善数组格式  
         foreach($arr2 as $k => $v)
          {
            $arr3["$k 单"]=$v;
          }   

          $i=0;
          $j=0;
        foreach($arr3 as $k=>$v)
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
         
         default:
           break;
       }
        $this->_helper->json($json);
       
  
    }
    
  
  }
?>







