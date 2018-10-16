<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_org.php");
function module_priv_user_ids($MY_PRIV)
{
	$PRIV_NO_FLAG="2";
	include_once("inc/my_priv.php");
	
	if(!is_array($MY_PRIV))
		return false;

	$DEPT_PRIV=$MY_PRIV["DEPT_PRIV"];
	$ROLE_PRIV=$MY_PRIV["ROLE_PRIV"];
	$DEPT_ID_STR=$MY_PRIV["DEPT_ID_STR"];
	$DEPT_ID_STR=GetUnionSetOfChildDeptId($DEPT_ID_STR);
	$PRIV_ID_STR=$MY_PRIV["PRIV_ID_STR"];
	$USER_ID_STR=$MY_PRIV["USER_ID_STR"];
	$MY_PRIV_NO=$MY_PRIV["MY_PRIV_NO"];
	$WHERE_STR="";
	if($PRIV_NO_FLAG==3)
	{
		$ROLE_PRIV=0;
		$WHERE_STR=" and USER.USER_PRIV!=1 ";
	}
	$USER_IDS_STR="";
	//只管理指定人员
	if($DEPT_PRIV==3 || $DEPT_PRIV==4)
	{
		$USER_IDS_STR=$USER_ID_STR;
		return $USER_IDS_STR;
	}
	
	//需要从数据库取值
	//角色判断
	if($ROLE_PRIV==3) //指定角色
		$WHERE_STR.="and find_in_set(USER_PRIV.USER_PRIV,'$PRIV_ID_STR') ";
	else if($ROLE_PRIV == "0") //低角色
  	$WHERE_STR.=" and USER_PRIV.PRIV_NO>'$MY_PRIV_NO' ";
	else if($ROLE_PRIV == "1") //同或低角色
  	$WHERE_STR.=" and USER_PRIV.PRIV_NO>='$MY_PRIV_NO' ";
	
	//部门判断
	if($DEPT_PRIV==2)
		$WHERE_STR.=" and find_in_set(USER.DEPT_ID,'$DEPT_ID_STR') ";
	else if($DEPT_PRIV==0)
		$WHERE_STR.=" and USER.DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."' ";
	$query="select USER.USER_ID as USER_ID from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV ".$WHERE_STR;
	$cursor=exequery(TD::conn(),$query);
	while($ROW=mysql_fetch_array($cursor))
		$USER_IDS_STR.=$ROW["USER_ID"].",";
	return $USER_IDS_STR;
}

