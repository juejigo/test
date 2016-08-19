<?php

/**
 *  检验参数
 */
function params(&$controller)
{
    $request = $controller->getRequest();
    $action = strtolower($request->getActionName());
    $controller->params = $request->getQuery();

    if ($action == 'index')
    {
        /* 构造验证器 */

        $filters = array(
            'vote_id' => 'Int',
        );
        $validators = array(
            'vote_id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '活动不允许为空',
                array('DbRowExists',array(
                    'table' => 'vote',
                    'field' => 'id',
                    'where' => array('status in (?)' => array(2,1)),
                )),
                'messages' => array('活动不存在'),
            )
        );
        $controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);

        /* 验证器检验 */

        if (!$controller->paramInput->isValid())
        {
            $controller->error->import($controller->paramInput->getMessages());
        }

        if ($controller->error->hasError())
        {
            return false;
        }
        return true;
    }
    elseif ($action == 'rank')
    {
        /* 构造验证器 */
        
        $filters = array(
            'vote_id' => 'Int',
        );
        $validators = array(
            'vote_id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '活动不允许为空',
                array('DbRowExists',array(
                    'table' => 'vote',
                    'field' => 'id',
                    'where' => array('status = ?' => 1),
                )),
                'messages' => array('活动不存在'),
            )
        );
        $controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);
        
        /* 验证器检验 */
        
        if (!$controller->paramInput->isValid())
        {
            $controller->error->import($controller->paramInput->getMessages());
        }
        
        if ($controller->error->hasError())
        {
            return false;
        }
        return true;
        
    }
    elseif ($action == 'rule')
    {
        /* 构造验证器 */
        
        $filters = array(
            'vote_id' => 'Int',
        );
        $validators = array(
            'vote_id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '活动不允许为空',
                array('DbRowExists',array(
                    'table' => 'vote',
                    'field' => 'id',
                    'where' => array('status = ?' => 1),
                )),
                'messages' => array('活动不存在'),
            )
        );
        $controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);
        
        /* 验证器检验 */
        
        if (!$controller->paramInput->isValid())
        {
            $controller->error->import($controller->paramInput->getMessages());
        }
        
        if ($controller->error->hasError())
        {
            return false;
        }
        return true;
        
    }
    elseif ($action == 'expirelist')
    {
        /* 构造验证器 */
        
        $filters = array(
            'vote_id' => 'Int',
        );
        $validators = array(
            'vote_id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '活动不允许为空',
                array('DbRowExists',array(
                    'table' => 'vote',
                    'field' => 'id',
                    'where' => array('status = ?' => 1),
                )),
                'messages' => array('活动不存在'),
            )
        );
        $controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);
        
        /* 验证器检验 */
        
        if (!$controller->paramInput->isValid())
        {
            $controller->error->import($controller->paramInput->getMessages());
        }
        
        if ($controller->error->hasError())
        {
            return false;
        }
        return true;
        
    }
    elseif ($action == 'expire')
    {
        /* 构造验证器 */
        
        $filters = array(
            'expire_id' => 'Int',
        );
        $validators = array(
        	'expire_id' => array(
        		'presence' => 'required',
        		'allowEmpty' => false,
        		'notEmptyMessage' => '过期活动不允许为空',
        		array('DbRowExists',array(
        			'table' => 'vote',
        			'field' => 'id',
        			'where' => array('status = ?' => 2),
        		)),
        		'messages' => array('过期活动不存在'),
        	)
        );
        $controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);
        
        /* 验证器检验 */
        
        if (!$controller->paramInput->isValid())
        {
            $controller->error->import($controller->paramInput->getMessages());
        }
        
        if ($controller->error->hasError())
        {
            return false;
        }
        return true;
        
    }
    elseif ($action == 'next')
    {
        /* 构造验证器 */
        
        $filters = array(
            'vote_id' => 'Int',
        );
        $validators = array(
            'vote_id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '活动不允许为空',
                array('DbRowExists',array(
                    'table' => 'vote',
                    'field' => 'id',
                    'where' => array('status = ?' => 1),
                )),
                'messages' => array('活动不存在'),
            )
        );
        $controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);
        
        /* 验证器检验 */
        
        if (!$controller->paramInput->isValid())
        {
            $controller->error->import($controller->paramInput->getMessages());
        }
        
        if ($controller->error->hasError())
        {
            return false;
        }
        return true;
        
    }

    return false;
}

/**
 *  检验 ajax
 */
function ajax(&$controller)
{
    $request = $controller->getRequest();
    $op = $request->getQuery('op','');
    $controller->data = $request->getPost();

    if ($op == 'list')
    {
        /* 构造验证器 */

        $filters = array(
            'page' => 'Int',
            'vote_id' => 'Int',
        );
        $validators = array(
            'page' => array(
                'allowEmpty' => false,
                'notEmptyMessage' => '参数错误',
                array('GreaterThan',0),
                'messages' => array('参数错误'),
                'default' => '1'
            ),
            'vote_id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '活动不允许为空',
                array('DbRowExists',array(
                    'table' => 'vote',
                    'field' => 'id',
                    'where' => array('status = ?' => 1),
                )),
                'messages' => array('活动不存在'),
            )
        );
        $controller->input = new Core_Filter_Input($filters,$validators,$controller->data);

        /* 验证器检验 */

        if (!$controller->input->isValid())
        {
            $controller->error->import($controller->input->getMessages());
        }

        if ($controller->error->hasError())
        {
            return false;
        }
        return true;
    }
    elseif ($op == 'vote')
    {
        /* 构造验证器 */
        
        $filters = array(
            'player_id' => 'Int',
            'vote_id' => 'Int',
        );
        $validators = array(
            'player_id' => array(
                'allowEmpty' => false,
                'notEmptyMessage' => '得票人不会空',
                array('DbRowExists',array(
                    'table' => 'vote_player',
                    'field' => 'id',
                    'where' => array('status = ?' => 1),
                )),
                'messages' => array('该得票人不合法'),
            ),
            'vote_id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '活动不允许为空',
                array('DbRowExists',array(
                    'table' => 'vote',
                    'field' => 'id',
                    'where' => array('status = ?' => 1),
                )),
                'messages' => array('活动不存在'),
            )
        );
        $controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
        
        /* 验证器检验 */
        
        if (!$controller->input->isValid())
        {
            $controller->error->import($controller->input->getMessages());
        }
        
        if ($controller->error->hasError())
        {
            return false;
        }
        return true;
    }
    elseif ($op == 'comment')
    {
        /* 构造验证器 */
        
        $filters = array(
            'player_id' => 'Int',
            'vote_id' => 'Int',
        );
        $validators = array(
            'player_id' => array(
                'allowEmpty' => false,
                'notEmptyMessage' => '被评论人不会空',
                array('DbRowExists',array(
                    'table' => 'vote_player',
                    'field' => 'id',
                    'where' => array('status = ?' => 1),
                )),
                'messages' => array('被评论人不合法'),
            ),
            'vote_id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '活动不允许为空',
                array('DbRowExists',array(
                    'table' => 'vote',
                    'field' => 'id',
                    'where' => array('status = ?' => 1),
                )),
                'messages' => array('活动不存在'),
            )
        );
        $controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
        
        /* 验证器检验 */
        
        if (!$controller->input->isValid())
        {
            $controller->error->import($controller->input->getMessages());
        }
        
        if ($controller->error->hasError())
        {
            return false;
        }
        return true;
    }
    elseif ($op == 'commentlist')
    {
        /* 构造验证器 */

        $filters = array(
            'page' => 'Int',
            'vote_id' => 'Int',
        );
        $validators = array(
            'page' => array(
                'allowEmpty' => false,
                'notEmptyMessage' => '参数错误',
                array('GreaterThan',0),
                'messages' => array('参数错误'),
                'default' => '1'
            ),
        	'player_id' => array(
        		'allowEmpty' => false,
        		'notEmptyMessage' => '被评论人不会空',
        		array('DbRowExists',array(
        			'table' => 'vote_player',
        			'field' => 'id',
        			'where' => array('status = ?' => 1),
        			)),
        		'messages' => array('被评论人不合法'),
        	),
            'vote_id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '活动不允许为空',
                array('DbRowExists',array(
                    'table' => 'vote',
                    'field' => 'id',
                    'where' => array('status = ?' => 1),
                )),
                'messages' => array('活动不存在'),
            )
        );
        $controller->input = new Core_Filter_Input($filters,$validators,$controller->data);

        /* 验证器检验 */

        if (!$controller->input->isValid())
        {
            $controller->error->import($controller->input->getMessages());
        }

        if ($controller->error->hasError())
        {
            return false;
        }
        return true;
    }
    return false;
}
?>