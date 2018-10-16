<?

$PRIV_NO_FLAG="2";
$MANAGE_FLAG="1";
$MODULE_ID=4;
include_once("inc/my_priv.php");
include_once("inc/utility_org.php");


//获取模块管理员权限
if(is_module_manager(2) && $_SESSION["LOGIN_USER_PRIV"]!=1)
{
	$DEPT_PRIV = 1;
	$ROLE_PRIV = 2;
	
}

//---------第一次加载页面，要根据管理范围列出人员，而不是显示本部门----------
if($DEPT_PRIV=="0")  //本部门
{
    $query = "select DEPT_ID from DEPARTMENT where DEPT_PARENT='".$_SESSION["LOGIN_DEPT_ID"]."'";
    $cursor = exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $DEPT_IDS.= $ROW["DEPT_ID"].",";
    }
    $DEPT_IDS = td_trim($DEPT_IDS);
    if($DEPT_IDS!="")
    {
        $WHERE_STRS.=" and (b.DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'or b.DEPT_ID in($DEPT_IDS))";
    }
    else
    {
        $WHERE_STRS.=" and b.DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
    }
    //$WHERE_STRS.=" and b.DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
}
else if($DEPT_PRIV=="2") //指定部门
{
    $DEPT_ID_STR=td_trim($DEPT_ID_STR);
    if($DEPT_ID_STR!="")
        $WHERE_STRS.=" and b.DEPT_ID in ($DEPT_ID_STR)";
}
else if($DEPT_PRIV=="3")  //指定人员
{  
    $USER_ID_STR=td_trim($USER_ID_STR);
    if($USER_ID_STR!="")
        $WHERE_STRS.=" and find_in_set(b.USER_ID,'$USER_ID_STR')";
}
else if($DEPT_PRIV=="4")  //本人
{
    $WHERE_STRS.=" and b.USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
}
if($ROLE_PRIV == "0")   //低角色的用户
    $WHERE_STRS.=" and g.PRIV_NO>'$MY_PRIV_NO'";
else if($ROLE_PRIV == "1")  //同角色和低角色的用户
    $WHERE_STRS.=" and g.PRIV_NO>='$MY_PRIV_NO'";
else if($ROLE_PRIV == "3")  //指定角色的用户  2:所有角色的用户
{
	$PRIV_ID_STR=td_trim($PRIV_ID_STR);
	if($PRIV_ID_STR!="")
	{
        $WHERE_STRS.=" and g.USER_PRIV in ($PRIV_ID_STR)";
    }
}
?>