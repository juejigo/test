<?php 
 $adminMenu = array(
 	'控制面板' => array(
 		'概况' => array(
 			'icon' => 'fa fa-home',
 			'概况' => array(
 				'module' => 'admincp',
 				'controller' => 'index',
 				'action' => 'index',
 				'url' => '/admincp/index/index',
 			)
 		),
 	),
 	'商品' => array(
 		'商品管理' => array(
 			'icon' => 'fa fa-star-half-o',
 			'商品列表' => array(
 				'module' => 'productcp',
 				'controller' => 'product',
 				'action' => 'list',
 				'url' => '/productcp/product/list',
 				'highlight_action' => array('list','edit','contract','trip','ticket','addon','visa','room','edittrip','editroom'),
 			    'highlight_controller' => array('product','image')
 			),
 			'添加商品' => array(
 				'module' => 'productcp',
 				'controller' => 'product',
 				'action' => 'add',
 				'url' => '/productcp/product/add',
 				),
 			),
 		'商品分类管理' => array(
 			'icon' => 'fa fa-chain',
 			'商品分类列表' => array(
 				'module' => 'productcp',
 				'controller' => 'cate',
 				'action' => 'list',
 				'url' => '/productcp/cate/list',
 				'highlight_action' => array('list','edit','add'),
 			),
 		),
 		'类型管理' => array(
 			'icon' => 'fa fa-indent',
 			'类型列表' => array(
 				'module' => 'productcp',
 				'controller' => 'type',
 				'action' => 'list',
 				'url' => '/productcp/type/list',
 				'highlight_action' => array('list','edit'),
 				
 			),
 			'添加类型' => array(
 				'module' => 'productcp',
 				'controller' => 'type',
 				'action' => 'add',
 				'url' => '/productcp/type/add',
 			),
 		),
//  		'品牌管理' => array(
//  			'icon' => 'icon-paper-plane',
//  			'品牌列表' => array(
// 				'module' => 'productcp',
//  				'controller' => 'brand',
//  				'action' => 'list',
//  				'url' => '/productcp/brand/list',
//  				'highlight_action' => array('list','edit'),
//  			), 
//  			'添加品牌' => array(
// 				'module' => 'productcp',
//  				'controller' => 'brand',
//  				'action' => 'add',
//  				'url' => '/productcp/brand/add',
//  			), 
//  		),
 		'规格管理' => array(
 			'icon' => 'fa fa-list',
 			'规格列表' => array(
 				'module' => 'productcp',
 				'controller' => 'spec',
 				'action' => 'list',
 				'url' => '/productcp/spec/list',
 				'highlight_action' => array('list','edit'),
 			),
 		),
 		'标签管理' => array(
 			'icon' => 'fa fa-paperclip',
 			'标签列表' => array(
 				'module' => 'productcp',
 				'controller' => 'tag',
 				'action' => 'list',
 				'url' => '/productcp/tag/list',
 				'highlight_action' => array('list','edit','add','taglist'),
 			),
 		),
 	    '合同管理' => array(
 			'icon' => 'fa fa-folder-open',
 			'合同列表' => array(
 			    'module' => 'productcp',
 			    'controller' => 'contract',
 			    'action' => 'list',
 			    'url' => '/productcp/contract/list',
 			    'highlight_action' => array('list','edit','add'),
 			),
 	    ),
 	    '保险管理' => array(
 			'icon' => 'fa fa-shield',
 			'保险列表' => array(
 			    'module' => 'productcp',
 			    'controller' => 'addon',
 			    'action' => 'list',
 			    'url' => '/productcp/addon/list',
 			    'highlight_action' => array('list','edit','add'),
 			),
 	    ),
 	    '签证管理' => array(
 			'icon' => 'fa fa-building-o',
 			'签证列表' => array(
 			    'module' => 'productcp',
 			    'controller' => 'visa',
 			    'action' => 'list',
 			    'url' => '/productcp/visa/list',
 			    'highlight_action' => array('list','edit','add'),
 			),
 	    ),
 		'预约管理' => array(
 			'icon' => 'fa fa-clock-o',
 			'预约列表' => array(
 				'module' => 'productcp',
 				'controller' => 'appointment',
 				'action' => 'list',
 				'url' => '/productcp/appointment/list',
 				'highlight_action' => array('list','edit','add'),
 			),
 		),
 	), 
 	'订单' => array(
 		'订单管理' => array(
 			'icon' => 'icon-basket',
 			'订单列表' => array(
				'module' => 'ordercp',
				'controller' => 'order',
				'action' => 'list',
				'url' => '/ordercp/order/list',
 				'highlight_action' => array('list','detail')
				),
			'有效订单' => array(
					'module' => 'ordercp',
					'controller' => 'order',
					'action' => 'list',
					'url' => '/ordercp/order/list?status=20',
					'params' => array(
							'status' => 20,
					)
			),
			'待出行' => array(
					'module' => 'ordercp',
					'controller' => 'order',
					'action' => 'list',
					'url' => '/ordercp/order/list?status=2',
					'params' => array(
							'status' => 2,
					)
			),
			'退款订单' => array(
					'module' => 'ordercp',
					'controller' => 'order',
					'action' => 'list',
					'url' => '/ordercp/order/list?status=13',
					'params' => array(
							'status' => 13,
					)
			),
 		),
 	),
 		
 	'会员'=>array(
 		'会员管理' => array(
 			'icon' => 'fa fa-male',
 			'会员列表' => array(
 					'module' => 'usercp',
 					'controller' => 'member',
 					'action' => 'list',
 					'url' => '/usercp/member/list',
 					'highlight_action' => array('list','edit','add'),
 					'highlight_controller' => array('privilege')
 			),	
 			'等级管理' => array(
 					'module' => 'usercp',
 					'controller' => 'group',
 					'action' => 'list',
 					'url' => '/usercp/group/list',
 					'highlight_action' => array('list','edit','add'),
 					
 			),		
 		),
 			
 		'代理商管理' => array(
 			'icon' => 'icon-wallet',
 			'代理商列表' => array(
 					'module' => 'usercp',
 					'controller' => 'agent',
 					'action' => 'list',
 					'url' => '/usercp/agent/list',
 					'highlight_action' => array('list','edit','add'),
 			),
 		),
 			
 		'供货商管理' => array(
 			'icon' => 'fa fa-truck',
 			'供货商列表' => array(
 					'module' => 'usercp',
 					'controller' => 'supplier',
 					'action' => 'list',
 					'url' => '/usercp/supplier/list',
 					'highlight_action' => array('list','edit','add'),
 			),
 		), 		
 		
 		'专属客服' => array(
 			'icon' => 'fa fa-female',
 			'客服列表' => array(
 					'module' => 'servicecp',
 					'controller' => 'staff',
 					'action' => 'list',
 					'url' => '/servicecp/staff/list',
 					'highlight_action' => array('list','edit','add'),
 			),
 		),
 	),

	'页面' => array(
		'推荐位管理' => array(
			'icon' => 'icon-wallet',
			'推荐位组表表' => array(
					'module' => 'positioncp',
					'controller' => 'position',
					'action' => 'group',
					'url' => '/positioncp/position/group',
					'highlight_action' => array('group','groupadd','groupedit','list','edit','add'),
					'highlight_controller' => array('data')
			),
		),			
		'新闻管理' => array(
			'icon' => 'fa fa-space-shuttle',
			'新闻列表' => array(
					'module' => 'newscp',
					'controller' => 'news',
					'action' => 'list',
					'url' => '/newscp/news/list',
					'highlight_action' => array('list','edit','add')
			),
			'分类列表' => array(
					'module' => 'newscp',
					'controller' => 'cate',
					'action' => 'list',
					'url' => '/newscp/cate/list',
					'highlight_action' => array('list','edit','add')
			),
		),
	),
 		
	'财务' => array(
		'财务统计' => array(
			'icon' => 'fa fa-jpy',
 			'提现列表' => array(
 				'module' => 'fundscp',
				'controller' => 'funds',
				'action' => 'list',
 				'url' => '/fundscp/funds/list',
 			),
			'已提现列表' => array(
				'module' => 'fundscp',
				'controller' => 'funds',
				'action' => 'list',
				'url' => '/fundscp/funds/list?status=1&auth=1',
				'params' => array(
								'status' => 1,
								'auth' => 1,
				)
			),
 		),
		'明细' => array(
			'icon' => 'fa fa-money',
			'流水帐列表' => array(
				'module' => 'financecp',
				'controller' => 'detail',
				'action' => 'flow',
				'url' => '/financecp/detail/flow'
				),

			),			
 		),
 	'统计' => array(
 		'订单统计' => array(
 			'icon' => 'icon-basket',
 			'销售统计' => array(
 				'module' => 'statisticcp',
				'controller' => 'order',
				'action' => 'sales',
				'url' => '/statisticcp/order/sales'
 				),
 			'状态统计' => array(
 				'module' => 'statisticcp',
				'controller' => 'order',
				'action' => 'status',
				'url' => '/statisticcp/order/status'
 				),
 		),
 		'会员统计' => array(
 			'icon' => 'fa fa-male',
 			'注册统计' => array(
 				'module' => 'statisticcp',
				'controller' => 'user',
				'action' => 'register',
				'url' => '/statisticcp/user/register'
 				),
 			'购买统计' => array(
 				'module' => 'statisticcp',
				'controller' => 'user',
				'action' => 'buy',
				'url' => '/statisticcp/user/buy'
 			),
 		), 		
 	),
 		
	'营销' => array(
		'一元夺宝' => array(
			'icon' => 'fa fa-reddit',
			'期数列表' => array(
				'module' => 'onecp',
				'controller' => 'phase',
				'action' => 'list',
				'url' => '/onecp/phase/list',
				'highlight_action' => array('list','edit','add','detail'),
			),
			'夺宝记录列表' => array(
				'module' => 'onecp',
				'controller' => 'order',
				'action' => 'list',
				'url' => '/onecp/order/list',
				'highlight_action' => array('list','detail'),
			)
		),
		'投票管理' => array(
			'icon' => 'fa fa-trophy',
			'活动列表' => array(
				'module' => 'votecp',
				'controller' => 'vote',
				'action' => 'list',
				'url' => '/votecp/vote/list',
				'highlight_action' => array('list','edit','add'),
				'highlight_controller' => array('player','record','comment')
			),
		),
	    '刮刮卡管理' => array(
	        'icon' => 'fa fa-credit-card',
	        '刮刮卡列表' => array(
	            'module' => 'scrathcp',
	            'controller' => 'scrath',
	            'action' => 'list',
	            'url' => '/scrathcp/scrath/list',
	            'highlight_action' => array('list','edit','add'),
	            'highlight_controller' => array('card')
	        ),
	    ),
	    '推送' => array(
	        'icon' => 'fa fa-bullhorn',
	        '推送' => array(
	            'module' => 'pushcp',
	            'controller' => 'push',
	            'action' => 'jpush',
	            'url' => '/pushcp/push/jpush',
	        ),
	    ),
	),
 		
 	'设置' => array(
 		'系统设置' => array(
 			'icon' => 'fa fa-cog',
 			'基本设置' => array(
 				'module' => 'systemcp',
				'controller' => 'setting',
				'action' => 'config',
				'url' => '/systemcp/setting/config',		
 			),
 			'物流管理' => array(
 				'module' => 'systemcp',
 				'controller' => 'setting',
 				'action' => 'shippingtpl',
 				'url' => '/systemcp/setting/shippingtpl',
 			),
 		),
 		'数据管理' => array(
 			'icon' => 'fa fa-database',
 			'数据备份' => array(
 				'module' => 'systemcp',
 				'controller' => 'database',
 				'action' => 'index',
 				'url' => '/systemcp/database/index',
 			),
 			'数据下载' => array(
 				'module' => 'systemcp',
 				'controller' => 'database',
 				'action' => 'list',
 				'url' => '/systemcp/database/list',
 			),
 		),
 	),	
);

?>