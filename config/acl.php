<?php

$defaultRole = 'guest';

$roles = array(
	array(
		'name' => 'guest',
		'allows' => array(
			array(
				'resource' => 'admin_index',
				'privilege' => array('index','logout')
			),
			array(
				'resource' => 'appapi_utility',
				'privilege' => null
			),
			array(
				'resource' => 'index',
				'privilege' => null
			),
			array(
				'resource' => 'imapi_service',
				'privilege' => null
			),
			array(
				'resource' => 'indexapi',
				'privilege' => null
			),
			array(
				'resource' => 'newsapi',
				'privilege' => null
			),
			array(
				'resource' => 'one',
				'privilege' => null
			),
			array(
				'resource' => 'pay',
				'privilege' => null
			),
			array(
				'resource' => 'public_public',
				'privilege' => null
			),
			array(
				'resource' => 'product',
				'privilege' => null
			),
			array(
				'resource' => 'productapi_cate',
				'privilege' => null
			),
			array(
				'resource' => 'productapi_feedback',
				'privilege' => null
			),
			array(
				'resource' => 'productapi_product',
				'privilege' => null
			),
			array(
				'resource' => 'regionapi',
				'privilege' => null
			),
		    array(
		        'resource' => 'static',
		        'privilege' => null
		    ),
			array(
				'resource' => 'search',
				'privilege' => null
			),
			array(
				'resource' => 'searchapi_product',
				'privilege' => null
			),
			array(
				'resource' => 'smsapi_sendcode',
				'privilege' => null
			),
			array(
				'resource' => 'user_account',
				'privilege' => null
			),
			array(
				'resource' => 'userapi_user',
				'privilege' => array('userinfo')
			),
			array(
				'resource' => 'userapi_account',
				'privilege' => null
			),
			array(
				'resource' => 'utilityapi',
				'privilege' => null
			),
			array(
				'resource' => 'utility',
				'privilege' => null
			),
			array(
				'resource' => 'vote',
				'privilege' => null
			),
			array(
				'resource' => 'wx',
				'privilege' => null
			),
		)
	),
	array(
		'name' => 'member',
		'inherit' => 'guest',
		'allows' => array(
			array(
				'resource' => 'cart',
				'privilege' => null
			),
			array(
				'resource' => 'cartapi',
				'privilege' => null
			),
			array(
				'resource' => 'consignee',
				'privilege' => null
			),
			array(
				'resource' => 'consigneeapi',
				'privilege' => null
			),
			array(
				'resource' => 'favorite',
				'privilege' => null
			),
			array(
				'resource' => 'favoriteapi',
				'privilege' => null
			),
			array(
				'resource' => 'funds',
				'privilege' => null
			),
			array(
				'resource' => 'fundsapi_bank',
				'privilege' => null
			),
			array(
				'resource' => 'oneuc',
				'privilege' => null
			),
			array(
				'resource' => 'order',
				'privilege' => null
			),
			array(
				'resource' => 'orderapi',
				'privilege' => null
			),
			array(
				'resource' => 'orderuc',
				'privilege' => null
			),
			array(
				'resource' => 'payapi_balance',
				'privilege' => null
			),
		    array(
		        'resource' => 'productapi_appointment',
		        'privilege' => null
		    ),
			array(
				'resource' => 'pushapi_jpush',
				'privilege' => null
			),
			array(
				'resource' => 'scrathuc',
				'privilege' => null
			),
			array(
				'resource' => 'serviceapi',
				'privilege' => null
			),
			array(
				'resource' => 'messageapi_message',
				'privilege' => null
			),
			array(
				'resource' => 'user',
				'privilege' => null
			),
			array(
				'resource' => 'userapi',
				'privilege' => null
			),
			array(
				'resource' => 'voteuc',
				'privilege' => null
			),
		)
	),
	array(
		'name' => 'promoter',
		'inherit' => 'member',
		'allows' => array(
		)
	),
);

$resources = array(
	'admin' => array(
		'admin_index'
	),
	'admincp' => array(
		'admincp_index',
		'admincp_account'
	),
	'appapi' => array(
		'appapi_utility',
	),
	'cartapi' => array(
		'cartapi_cart'
	),
	'cart' => array(
		'cart_cart'
	),
	'consigneeapi' => array(
		'consigneeapi_consignee'
	),
	'consignee' => array(
		'consignee_consignee'
	),
	'favoriteapi' => array(
		'favoriteapi_favorite'
	),
	'financecp' => array(
		'financecp_detail'
	),
	'favorite' => array(
		'favorite_favorite'
	),
	'funds' => array(
		'funds_funds',
	),
	'fundsapi' => array(
		'fundsapi_bank',
	),
	'fundscp' => array(
		'fundscp_funds',
	),
	'newsapi' => array(
		'newsapi_news',
	),
	'newscp' => array(
		'newscp_news',
		'newscp_cate'
	),
	'news' => array(
		'news_news',
		'news_cate'
	),
	'imapi' => array(
		'imapi_service'
	),
	'imageuc' => array(
		'imageuc_kindeditor',
		'imageuc_image'
	),
	'index' => array(
		'index_index'
	),
	'indexapi' => array(
		'indexapi_index'
	),
	'messageapi' => array(
		'messageapi_message'
	),
	'one' => array(
		'one_phase',
	),
	'onecp' => array(
		'onecp_phase',
		'onecp_order',
	),
	'oneuc' => array(
		'oneuc_order',
		'oneuc_member',
	),
	'order' => array(
		'order_order',
		'order_gowxpay',
	),
	'orderapi' => array(
		'orderapi_order',
	),
	'ordercp' => array(
		'ordercp_order',
	),
	'orderuc' => array(
		'orderuc_order',
		'order_linerorder',
		'orderuc_sn',
	),
	'ordersh' => array(
		'ordersh_order',
	),
	'smsapi' => array(
		'smsapi_sendcode',
	),
	'pay' => array(
		'pay_aliapp',
		'pay_wx',
		'pay_wxweb',
		'pay_balance',
	),
	'payapi' => array(
		'payapi_balance'
	),
	'positioncp' => array(
		'positioncp_position',
		'positioncp_data',
	),
	'product' => array(
		'product_brand',
		'product_cate',
		'product_feedback',
		'product_product',
	),
	'productapi' => array(
		'productapi_brand',
		'productapi_cate',
		'productapi_feedback',
		'productapi_product',
	    'productapi_appointment',
	),
	'productcp' => array(
		'productcp_brand',
		'productcp_image',
		'productcp_product',
		'productcp_spec',
		'productcp_tag',
		'productcp_type',
	),
	'productsh' => array(
		'productsh_product',
	),
	'pushapi' => array(
		'pushapi_jpush',
	),
	'pushcp' => array(
		'pushcp_push',
	),
	'regionapi' => array(
		'regionapi_region',
	),
    'static' => array(
        'static_html'
    ),
	'scrathcp' => array(
		'scrathcp_scrath',
		'scrathcp_card',
	),
	'scrathuc' => array(
		'scrathuc_scrath',
		'scrathuc_card',
	),
	'searchapi' => array(
		'searchapi_product',
	),
	'search' => array(
		'search_product',
	),
	'serviceapi' => array(
		'serviceapi_staff',	
	),
	'servicecp' => array(
		'servicecp_staff',	
	),
	'statisticcp' => array(
		'statisticcp_order',
		'statisticcp_user',
	),
	'systemcp' => array(
		'systemcp_database',
		'systemcp_setting',
	),
	'userapi' => array(
		'userapi_account',
		'userapi_funds',
		'userapi_member',
		'userapi_profile',
		'userapi_user',
		'userapi_wallet',
	),
	'user' => array(
		'user_account',
		'user_funds',
		'user_member',
		'user_profile',
		'user_user',
		'user_wallet',
	),
	'usercp' => array(
		'usercp_member',
		'usercp_privilege',
	),
	'utility' => array(
		'utility_captcha',
		'utility_email',
		'utility_qrcode'
	),
	'utilityapi' => array(
		'utilityapi_sms',
		'utilityapi_version'
	),
	'public' => array(
		'public_public'
	),
	'vote' => array(
        'vote_player',
        'vote_vote'
    ),
    'votecp' => array(
        'votecp_player',
        'votecp_record',
        'votecp_vote',
    	'votecp_comment'
    ),   
    'voteuc' => array(
        'voteuc_vote'
    ),
	'wx' => array(
		'wx_user',
		'wx_receiver'
	),
);

?>