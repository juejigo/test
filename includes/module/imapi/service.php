<?php

/**
 *  随机会员ID
 */
function radomUserId()
{
    return session_id();
}

/**
 *  随机会员名
 */
function radomUserName()
{
	$random = mt_rand(1,9999);
    return "游客_{$random}";
}

?>