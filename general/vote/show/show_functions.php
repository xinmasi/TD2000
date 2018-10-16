<?php
/************
* �鿴��½�û��Ƿ����ò鿴Ȩ��  
*@author	songyang
*@version 1.0
*@param string $VIEW_RESULT_PRIV_ID ��ͶƱ�����ݿ��б����"�鿴Ȩ�޷�Χ����ɫ��"��Ϣ
*@param string $VIEW_RESULT_USER_ID ��ͶƱ�����ݿ��б����"�鿴Ȩ�޷�Χ����Ա��"��Ϣ
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
/************�鿴��½�û��Ƿ����ò鿴Ȩ��  ����***************/

/*************��ʹͶƱ����Ϊ��������鿴�� �в鿴Ȩ�޵��û�Ҳ���Բ鿴*********
*@author  songyang
*@version 1.0
*@param string $VIEW_RESULT_PRIV_ID ��ͶƱ�����ݿ��б����"�鿴Ȩ�޷�Χ����ɫ��"��Ϣ
*@param string $VIEW_RESULT_USER_ID ��ͶƱ�����ݿ��б����"�鿴Ȩ�޷�Χ����Ա��"��Ϣ
*@param string $VOTE_ID ��ͶƱ��ID
*@return string
 */
function showUserLookToResultLink($VIEW_RESULT_PRIV_ID, $VIEW_RESULT_USER_ID, $VOTE_ID)
{
	$str_return_link='';
	if(find_id($VIEW_RESULT_PRIV_ID, $_SESSION["LOGIN_USER_PRIV"]))
	{
		$str_return_link='<a href="javascript:view_result('."'".$VOTE_ID."'".')'._("�鿴���").'</a>';
	}
	
	if(find_id($VIEW_RESULT_USER_ID, $_SESSION["LOGIN_USER_ID"]))
	{
		$str_return_link='<a href="javascript:view_result('."'".$VOTE_ID."'".')'._("�鿴���").'</a>';
	}
	
	if(check_priv_other($VIEW_RESULT_PRIV_ID, $_SESSION["LOGIN_USER_PRIV_OTHER"]))
	{
		$str_return_link='<a href="javascript:view_result('."'".$VOTE_ID."'".')'._("�鿴���").'</a>';
	}
	
	return $str_return_link;
}
/*************��ʹͶƱ����Ϊ��������鿴�� �в鿴Ȩ�޵��û�Ҳ���Բ鿴   ����*********/

?>