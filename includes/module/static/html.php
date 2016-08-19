<?php
function params(&$controller)
{
    $request = $controller->getRequest();
    $action = strtolower($request->getActionName());
    $controller->params = $request->getQuery();
     if ($action == 'aboutus')
    {
        /* 构造验证器 */

        $filters = array();
        $validators = array();
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
