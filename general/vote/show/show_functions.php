<?php
/************
* 查看登陆用户是否被设置查看权限  
*@author	songyang
*@version 1.0
*@param string $VIEW_RESULT_PRIV_ID 该投票在数据库中保存的"查看权限范围（角色）"信息
*@param string $VIEW_RESULT_USER_ID 该投票在数据库中保存的"查看权限范围（人员）"信息
*@return bool 
 ****************/
function checkUserLookToResultPriv($VIEW_RESULT_PRIV_ID, $VIEW_RESULT_USER_ID){
	if(find_id($VIEW_RESULT_PRIV_ID, $_SESSION["LOGIN_USER_PRIV"]))
	{
		return TRUE;
	}
	
	if(find_id($VIEW_RESULT_USER_ID, $_SESSION["LOGIN_USER_ID"]))
	{
		return TRUE;
	}
	
	if(check_priv_other($VIEW_RESULT_PRIV_ID, $_SESSION["LOGIN_USER_PRIV_OTHER"]))
	{
		return TRUE;
	}
	
	return FALSE;
}
/************查看登陆用户是否被设置查看权限  结束***************/

/*************即使投票被设为“不允许查看” 有查看权限的用户也可以查看*********
*@author  songyang
*@version 1.0
*@param string $VIEW_RESULT_PRIV_ID 该投票在数据库中保存的"查看权限范围（角色）"信息
*@param string $VIEW_RESULT_USER_ID 该投票在数据库中保存的"查看权限范围（人员）"信息
*@param string $VOTE_ID 该投票的ID
*@return string
 */
function showUserLookToResultLink($VIEW_RESULT_PRIV_ID, $VIEW_RESULT_USER_ID, $VOTE_ID)
{
	$str_return_link='';
	if(find_id($VIEW_RESULT_PRIV_ID, $_SESSION["LOGIN_USER_PRIV"]))
	{
		$str_return_link='<a href="javascript:view_result('."'".$VOTE_ID."'".')'._("查看结果").'</a>';
	}
	
	if(find_id($VIEW_RESULT_USER_ID, $_SESSION["LOGIN_USER_ID"]))
	{
		$str_return_link='<a href="javascript:view_result('."'".$VOTE_ID."'".')'._("查看结果").'</a>';
	}
	
	if(check_priv_other($VIEW_RESULT_PRIV_ID, $_SESSION["LOGIN_USER_PRIV_OTHER"]))
	{
		$str_return_link='<a href="javascript:view_result('."'".$VOTE_ID."'".')'._("查看结果").'</a>';
	}
	
	return $str_return_link;
}
/*************即使投票被设为“不允许查看” 有查看权限的用户也可以查看   结束*********/

?>