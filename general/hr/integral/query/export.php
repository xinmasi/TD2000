<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("../func.func.php");
ob_end_clean();

$qu="";
foreach($_GET as $k=>$v)
{
    $$k=$v;
    $qu.="&".$k."=".$v;
}
$FROM_DEPT_ID=$DEPT_ID;//存部门
$NEW_DEPT_ID=intval($NEW_DEPT_ID);
$WHERE_STR = "";
if($TO_ID!="")
    $WHERE_STR.=" and find_in_set(USER_ID,'$TO_ID') ";

if($DEPT_ID!="")//部门
{
    if($DEPT_ID!="0")// 查询全部人员
    {
        $DEPT_ID_CHILD = td_trim(GetChildDeptId($DEPT_ID));
        $WHERE_STR.=" and DEPT_ID in ($DEPT_ID_CHILD)";
    }
    else
    {
        $WHERE_STR.=" and DEPT_ID='0'";
    }
}
if($SEX!="")// 性别
    $WHERE_STR.=" and SEX='$SEX'";
if($USER_PRIV!="")//角色
    $WHERE_STR.=" and USER_PRIV='$USER_PRIV'";
$query="select user_id from user where 1=1 and dept_id!='0' ".$WHERE_STR;
$cursor=exequery(TD::conn(), $query);
$user_ids="";
while($row=mysql_fetch_array($cursor)){
    $user_ids.=$row["user_id"].",";
}
$rank_info=get_integrals($user_ids,$begin,$end,$INTEGRAL_TYPE,$ITEM_TYPE);
// $end=$start+$ITEMS_IN_PAGE;
arsort($rank_info);
//var_dump($rank_info);
//exit;
$OUTPUT_HEAD=array(_("部门"),_("姓名"),_("积分"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("积分导出"));
$objExcel->addHead($OUTPUT_HEAD);

foreach($rank_info as $key => $value)
{
    if($value=="")
        $value=0;
    $query="select dept_name,user_name from user left join department on user.dept_id=department.dept_id where user.user_id='$key'";
    $cursor=exequery(TD::conn(), $query);
    if($row=mysql_fetch_array($cursor))
    {
        $dept_name=format_cvs($row["dept_name"]);
        $user_name=format_cvs($row["user_name"]);
        $objExcel->addRow("$dept_name,$user_name,$value");
    }
}
$objExcel->Save();
