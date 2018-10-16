<?
$PRIV_NO_FLAG="1";
$MANAGE_FLAG="1";
$MODULE_ID=9;
include_once("inc/my_priv.php");
//---------第一次加载页面，要根据管理范围列出人员，而不是显示本部门----------

if($DEPT_PRIV=="0")
{
    $WHERE_STR.=" and b.DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
}
else if($DEPT_PRIV=="2")
{
    $DEPT_ID_STR=td_trim($DEPT_ID_STR);
    if($DEPT_ID_STR!="")
        $WHERE_STR.=" and b.DEPT_ID in ($DEPT_ID_STR)";
}
else if($DEPT_PRIV=="3")
{
    $USER_ID_STR=td_trim($USER_ID_STR);
    if($USER_ID_STR!="")
        $WHERE_STR.=" and find_in_set(b.USER_ID,'$USER_ID_STR')";
}
else if($DEPT_PRIV=="4")
{
    $WHERE_STR.=" and b.USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
}
/*if($ROLE_PRIV == "0")
{
    $WHERE_STR.=" and c.PRIV_NO>'$MY_PRIV_NO'";
}
else if($ROLE_PRIV == "1")
    $WHERE_STR.=" and c.PRIV_NO>='$MY_PRIV_NO'";
else if($ROLE_PRIV == "3")
{
    $PRIV_ID_STR=td_trim($PRIV_ID_STR);
    if($PRIV_ID_STR!="")
        $WHERE_STR.=" and c.PRIV_NO in ($PRIV_ID_STR)";
}*/

?>