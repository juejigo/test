<?php

/**
 *  检验参数
 */
function params(&$controller)
{
    $request = $controller->getRequest();
    $action = strtolower($request->getActionName());
    $controller->params = $request->getQuery();

    if ($action == 'info')
    {
        /* 构造验证器 */

        $filters = array(
            'id' => 'Int',
            'vote_id' => 'Int',
        );
        $validators = array(
            'id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '会员不允许为空',
                array('DbRowExists',array(
                    'table' => 'vote_player',
                    'field' => 'id',
                )),
                'messages' => array('会员不存在'),
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
    }elseif ($action == 'status')
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
    }elseif ($action == 'add')
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
    $action = strtolower($request->getActionName());
    $controller->data = $request->getPost();

    if ($action == 'add')
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