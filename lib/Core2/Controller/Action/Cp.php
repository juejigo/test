<?php

abstract class Core2_Controller_Action_Cp extends Core2_Controller_Action_Abstract 
{
	/**
	 *  预处理
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  派发后
	 */
	public function postDispatch()
	{
		parent::postDispatch();
		/* SEO */
		if (!$this->view->engine->getTemplateVars('headerTitle')) 
		{
			$this->view->headerTitle = SITE_NAME . '管理后台';
		}
		if (!$this->view->engine->getTemplateVars('headerKeywords')) 
		{
			$this->view->headerKeywords = '';
		}
		if (!$this->view->engine->getTemplateVars('headerDescription')) 
		{
			$this->view->headerDescription = '';
		}

		if($this->_auth->hasIdentity())
		{
	 		$menu = $this->_createMenu();
	 		
	 		$this->view->menuGroups = $menu['groups']['menus'];
	 		$this->view->currGroup = $menu['groups']['curr'];
	 		
	 		$this->view->submenus = $menu['submenus']['menus'];
	 		$this->view->currSub = $menu['submenus']['curr'];
	 		$this->view->openSub = $menu['submenus']['open'];
		}
	}
	
	/**
	 *  生成菜单
	 */
	protected function _createMenu()
	{
		/* 载入acl及菜单 */
		
		$acl = $this->_cache->load('acl');
		$action = strtolower($this->_request->getActionName());
		$controller = strtolower($this->_request->getControllerName());
		$module = strtolower($this->_request->getModuleName());
		$params = $this->_request->getParams();
		array_splice($params,0,3);

		require_once 'includes/adminMenu.php';

		/* 根据权限生产菜单 */

		$menu = array();
		$allowed = array();
		foreach ($adminMenu as $groupName => $group)
		{
			$groupAllowed = false;
			foreach ($group as $setName => $submenus)
			{
				$icon = $submenus['icon'];
				unset($submenus['icon']);
				foreach ($submenus as $submenuName => $submenu)
				{	
					$resource = $module . '_' . $submenu['controller'];
					if (!$acl->has($resource))
					{
						$resource = null;
					}
					if ($acl->isAllowed($this->_user->role,$resource,$submenu['action']))
					{
						if ($groupAllowed == false)
						{
							$menu['groups']['menus'][] = array(
								'name' => $groupName,
								'href' => "/{$submenu['module']}/{$submenu['controller']}/{$submenu['action']}");
						}
						$groupAllowed = true;
						
						if ($submenu['module'] == $module && $submenu['controller'] == $controller && $submenu['action'] == $action) 
						{
							//是否带参
							if(isset($submenu['params']))
							{
								foreach ($submenu['params'] as $k => $v)
								{
									if(array_key_exists($k,$params))
									{
										if($v==$params[$k])
										{
											$menu['groups']['curr'] = $groupName;
											$menu['submenus']['curr'] = $submenuName;
											$menu['submenus']['open'] = $setName;
 										}
									}
								}
							}
							else
							{
								$menu['groups']['curr'] = $groupName;
								$menu['submenus']['curr'] = $submenuName;
								$menu['submenus']['open']=$setName;
							}
						}
						
						//方法不同时高亮
						else if($submenu['module'] == $module && $submenu['controller'] == $controller && $submenu['action'] != $action)
						{
							if(array_key_exists("highlight_action", $submenu))
							{
								if(in_array($action,$submenu['highlight_action']))
								{
									$menu['groups']['curr'] = $groupName;
									$menu['submenus']['curr'] = $submenuName;
									$menu['submenus']['open']= $setName;
								}
							}
						}
						
						//控制器不同时高亮
						else if($submenu['module'] == $module && $submenu['controller'] != $controller)
						{
							if(array_key_exists("highlight_controller", $submenu))
							{
								if(in_array($controller,$submenu['highlight_controller']))
								{
									$menu['groups']['curr'] = $groupName;
									$menu['submenus']['curr'] = $submenuName;
									$menu['submenus']['open']= $setName;
								}
							}
						}
						//$allowed[$groupName][$setName]['icon'] = $icon;
						$allowed[$groupName][$setName][$submenuName] = $submenu;
					}
				}
			}
		}
		if (!empty($menu['groups']['curr']))
		{
			$menu['submenus']['menus'] = $allowed[$menu['groups']['curr']];
		}
		else 
		{
			$menu['submenus']['menus'] = array_shift($allowed);
		}
		return $menu;
	}
}

?>