<?
$PRIV_NO_FLAG="2";
$MANAGE_FLAG="0";
$MODULE_ID=3;
include_once("inc/my_priv.php");
//---------第一次加载页面，要根据管理范围列出人员，而不是显示本部门----------
if($DEPT_ID=="")
{
  if($DEPT_PRIV==2)
  {
  	 if(!find_id($DEPT_ID_STR,$_SESSION["LOGIN_DEPT_ID"]))
  	 {
  	   $MY_ARRAY=explode(",",$DEPT_ID_STR);
  	   $DEPT_ID=$MY_ARRAY[0];
    }
    else
      	$DEPT_ID=$_SESSION["LOGIN_DEPT_ID"];
  }
  else
      	$DEPT_ID=$_SESSION["LOGIN_DEPT_ID"];
}

if($DEPT_PRIV!="3" && $DEPT_PRIV!="4" && !is_dept_priv($DEPT_ID, $DEPT_PRIV, $DEPT_ID_STR))
{
   if($ACCESS_FROM=="GRXX")
   {
  	  $GRXX = 1;
   }else{
      Message(_("禁止"),_("您没有该部门的查看权限"));
      exit;    	
   }

}

if($DEPT_PRIV=="3"||$DEPT_PRIV=="4")
   $WHERE_STR.=" and (find_in_set(USER_ID, '$USER_ID_STR') or USER.UID=".$_SESSION['LOGIN_UID'].")";
else if($DEPT_ID!=0)
   $WHERE_STR.=" and (DEPT_ID='$DEPT_ID' or find_in_set('$DEPT_ID',DEPT_ID_OTHER) or USER.UID=".$_SESSION['LOGIN_UID'].")"; //辅助部门包含当前部门的

if($ROLE_PRIV == "0")
   $WHERE_STR.=" and (USER_PRIV.PRIV_NO>'$MY_PRIV_NO' or USER.UID=".$_SESSION['LOGIN_UID'].")";
else if($ROLE_PRIV == "1")
   $WHERE_STR.=" and (USER_PRIV.PRIV_NO>='$MY_PRIV_NO' or USER.UID=".$_SESSION['LOGIN_UID'].")";
else if($ROLE_PRIV == "3")
   $WHERE_STR.=" and (find_in_set(USER_PRIV.USER_PRIV,'$PRIV_ID_STR') or USER.UID=".$_SESSION['LOGIN_UID'].")";
?>