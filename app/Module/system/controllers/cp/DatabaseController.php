<?php
class Systemcp_DatabaseController extends Core2_Controller_Action_Cp
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
		$dir = 'runtime/database/';
		if (!file_exists($dir))
		{
			mkdir($dir);
		}
		$files = scandir($dir);
		$lastTime = 0;
		$length = count($files);
		//上次备份时间
		for ($i=2;$i<$length;$i++)
		{
			$filetime = filemtime($dir.$files[$i]);
			if ($filetime>$lastTime)
			{
				$lastTime = $filetime;
			}
		}
		$this->view->lastTime = $lastTime;
	}
	
	/**
	 *  备份数据
	 */
	public function ajaxAction()
	{		
		if (!$this->_request->isXmlHttpRequest())
		{
			exit;
		}
		
		$op = $this->_request->getQuery('op','');
		$json = array();
		$this->_helper->viewRenderer->setNoRender();
		
		/* 检验传值 */
		
		if (!ajax($this))
		{
			$json['errno'] = 2;
	   		$json['errmsg'] = $this->error->firstMessage();
	   		$this->_helper->json($json);
		}
		
		switch ($op)
		{	
			/* 数据备份*/
			
			case 'backup':
				
				/* 检验传值 */
				
				if (!params($this))
				{
					$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
						array(
							'href' => '/admincp',
							'text' => '返回')
					));
				}
				
				$dir = 'runtime/database/';
				
				/* 查出所有表名*/
				
				$sql = 'show tables';
				$mysql = "set charset utf8;\r\n";
				$table = $this->_db->query($sql)->fetchAll();
				$length = count($table);
				
				$tableId = 0;
				$page = $this->paramInput->page;
				$date = isset($this->paramInput->date) ? $this->paramInput->date : date('YmdHis');
				if (isset($this->paramInput->tableId))
				{
					$tableId = $this->paramInput->tableId;
					$first = $tableId;
				}
				
				/* 循环表*/
				
				for ($tableId;$tableId<$length;$tableId++)
				{
					$nowTable = current($table[$tableId]);
					
					//分卷继续
					if (isset($first) && $first == $tableId)
					{
						$fied = isset($this->paramInput->fied) ? $this->paramInput->fied : 1;
						if (!isset($this->paramInput->fied))
						{
							//建表sql
							$create = $this->_db->query("show create table `{$nowTable}`")->fetch();
							$mysql .= "DROP TABLE IF EXISTS `{$nowTable}`;\r\n";
							$mysql .= $create['Create Table'] . ";\r\n";
						}
					}
					else 
					{
						//建表sql
						$create = $this->_db->query("show create table `{$nowTable}`")->fetch();
						$mysql .= "DROP TABLE IF EXISTS `{$nowTable}`;\r\n";
						$mysql .= $create['Create Table'] . ";\r\n";
						$fied = 1;
					}
						
					/* 取数据*/
							
					while (1)
					{
						$fieds = $this->_db->select()
							->from($nowTable)
							->limitPage($fied, 1000)
							->query()
							->fetchAll();
						$count = count($fieds);
						
						if($count == 0)
		 				{
		 					break;
		 				}
		 				
		 				/* 拼接sql*/
		 				
		 				foreach ($fieds as $data)
		 				{
		 					$keys = array_keys($data);
		 					$keys = array_map('addslashes', $keys);
		 					$keys = join('`,`', $keys);
		 					$keys = "`{$keys}`";
		 					$vals = array_values($data);
		 					$vals = array_map('addslashes', $vals);
		 					$vals = join("','", $vals);
		 					$vals = "'{$vals}'";
		 					$mysql .= "insert into `{$nowTable}`({$keys}) values({$vals});\r\n";
		 				}
		 				$fied++;
		 				
		 				/* 分卷结束写入文件*/
		 				
		 				if (strlen($mysql) > 1024*1024)
		 				{ 				
		 					$filename = "{$dir}zhongyouwl{$date}page{$page}.sql";
			 				$fp = fopen($filename, 'w');
			 				fputs($fp, $mysql);
			 				fclose($fp);
		 					$page++;
		 					$json['errno'] = 0;
		 					$json['page'] = $page;
		 					
		 					/* 下次分卷链接*/
		 					
		 					$json['url'] = '/systemcp/database/ajax?op=backup&page='.$page.'&tableId='.$tableId.'&fied='.$fied.'&date='.$date;
		 					if($count < 1000)
		 					{
		 						$json['url'] = '/systemcp/database/ajax?op=backup&page='.$page.'&tableId='.($tableId+1).'&date='.$date;
		 					}
		 					$this->_helper->json($json);
		 				}
		 				
		 				if($count < 1000)
		 				{
		 					break;
		 				}
					}
				}
				
				/* 备份结束写入文件*/
				
				$filename = "{$dir}zhongyouwl{$date}page{$page}.sql";
				$fp = fopen($filename, 'w');
				fputs($fp, $mysql);
				fclose($fp);
		
				/* 压缩文件*/
				
				$zip = new ZipArchive();
				if ($zip->open("{$dir}zhongyouwl{$date}.zip",ZIPARCHIVE::CREATE) === TRUE)
				{
					//加入文件
					for ($i = 1;$i<=$page;$i++)
					{
						$filename = "zhongyouwl{$date}page{$i}.sql";
						$zip->addFile($dir.$filename,$filename);	
					}
					$zip->close();
					
					//删除sql文件
					for ($i = 1;$i<=$page;$i++)
					{
						$filename = "zhongyouwl{$date}page{$i}.sql";
						unlink($dir.$filename);
					}
				}
				else
				{
					$json['errno'] = 2;
					$json['errmsg'] = '压缩文件出错';
					$this->_helper->json($json);
				}		
				$json['errno'] = 1;
				$json['time'] = date("Y-m-d H:i:s");
				//下载
				$json['downUrl'] = '/'."{$dir}zhongyouwl{$date}.zip";
				$this->_helper->json($json);
				break;
			
			/* 删除文件*/
				
			case 'delete':
				
				$dir = 'runtime/database/';
				//单个
				if (!is_array($this->input->filename))
				{
					unlink($dir.$this->input->filename);
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				//批量
				foreach ($this->input->filename as $filename)
				{
					unlink($dir.$filename);
				}
				$json['errno'] = 0;
				$this->_helper->json($json);
				break;
				
			default:
				break;
		}
	}
	
	/**
	 *  备份文件列表
	 */
	public function listAction()
	{
		$dir = "runtime/database/";
		$files = scandir($dir);
		$length = count($files);
		
		//文件信息
		$file = array();
		for ($i=$length-1;$i>1;$i--)
		{
			$file[$i]['filename'] = $files[$i];
			$file[$i]['filetime'] = date("Y-m-d H:i:s",filemtime($dir.$files[$i]));
			$file[$i]['filesize'] = filesize($dir.$files[$i]);
			$file[$i]['url'] = '/'."{$dir}{$files[$i]}";
		}
		$this->view->file = $file;
	}
}
?>