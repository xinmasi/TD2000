<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("../func.func.php");
//echo $TO_NAME."____".$SEX."______".$DEPT_ID."_______".$USER_PRIV."________".$INTEGRAL_TYPE."_______".$begin."________".$end;
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
       $WHERE_STR.=" and user.DEPT_ID in ($DEPT_ID_CHILD)";
    }
    else
    {
       $WHERE_STR.=" and user.DEPT_ID='0'";
    }
 }
if($SEX!="")// 性别
    $WHERE_STR.=" and user.SEX='$SEX'";
if($USER_PRIV!="")//角色
    $WHERE_STR.=" and user.USER_PRIV='$USER_PRIV'";
$query="select user.USER_ID,user.USER_NAME,department.DEPT_NAME from user,department where user.DEPT_ID=department.DEPT_ID and user.dept_id!='0' ".$WHERE_STR;
$cursor=exequery(TD::conn(), $query);
$result_arr=array();
$count=0;
while($row=mysql_fetch_array($cursor)){
    $USER_ID_POINT=$row["USER_ID"];
    $USER_NAME_POINT=$row["USER_NAME"];
    $DEPART_ID_POINT=$row["DEPT_NAME"];
    $where_str_oa=" and USER_ID='$USER_ID_POINT' ";
    $where_str_in=" and USER_ID='$USER_ID_POINT' ";
    if($begin!="")
    {
        $where_str_oa.=" and CREATE_TIME>='$begin.00:00:00' ";
        $where_str_in.=" and INTEGRAL_TIME>='$begin.00:00:00' ";
    }
    if($end!="")
    {
        $where_str_oa.=" and CREATE_TIME<='$end.23:59:59' ";
        $where_str_in.=" and INTEGRAL_TIME<='$end.23:59:59' ";
    }
    $has_oa=0;
    $has_in=0;
    if($INTEGRAL_TYPE!="")
    {
        if(find_id($INTEGRAL_TYPE, 1) || find_id($INTEGRAL_TYPE, 2))
            $has_oa=1;
        if($INTEGRAL_TYPE==0 || $INTEGRAL_TYPE==3)
        {
            $has_in=1;
            if($INTEGRAL_TYPE==0)
                $where_str_in.=" and INTEGRAL_TYPE=0 ";
            else if($INTEGRAL_TYPE==3)
                $where_str_in.=" and INTEGRAL_TYPE=3 ";
            else
                $where_str_in.=" (INTEGRAL_TYPE=3 or INTEGRAL_TYPE=0) ";
        }
    }
    else
    {
        $has_oa=1;
        $has_in=1;
    }
    //查询OA计算项目
    if($has_oa==1)
    {
        $sum_oa_tem=array();
        $sql_oa="select * from hr_integral_oa where 1=1 ".$where_str_oa."";
        $cursor_oa=db_query($sql_oa, TD::conn());
        while($r_oa=mysql_fetch_assoc($cursor_oa))
        {
            $USER_ID=$r_oa["USER_ID"];
            $USER_NAME=trim(GetUserNameById($USER_ID),",");
            $CREATE_TIME=$r_oa["CREATE_TIME"];
            $RECORD_ID=$r_oa["RECORD_ID"];
            foreach($r_oa as $key => $value)
            {
                if($value==0) continue;
                if(in_array($key,array("RECORD_ID","USER_ID","SUM","MEMO","CREATE_TIME"))) continue;
                if(in_array($key,array("RS001","RS002","RS003","RS004","RS005")))
                    $OA_TYPE=2;
                else
                    $OA_TYPE=1;
                $ITEM_NAME="";
                if($OA_TYPE == 1)
                    $ITEM_NAME=getItemNameByNo($key);
                else
                    $ITEM_NAME=$RS_ARR[$key];
                $result_arr[$count]["INTEGRAL_TYPE"]="OA计算项目";
                $result_arr[$count]["ITEM_NAME"]=$ITEM_NAME;
                $result_arr[$count]["USER_NAME"]=$USER_NAME_POINT;
                $result_arr[$count]["INTEGRAL_TIME"]=$CREATE_TIME;
                $result_arr[$count]["REASON"]="";
                $result_arr[$count]["POINT"]=$value;
                $result_arr[$count]["DEPAT"]=$DEPART_ID_POINT;
                $count++;
            }
        }
    }
    //查询手动记录的积分项目
    if($has_in==1)
    {
        $selected_item_id="";
        if($ITEM_TYPE!="")
        {
            $where_str_sql=" and type_id='$ITEM_TYPE' ";
            $sql="select item_id from hr_integral_item where 1=1".$where_str_sql;
            $cur=exequery(TD::conn(), $sql);
            while($r=mysql_fetch_array($cur))
            {
                $selected_item_id.=$r["item_id"].",";
            }
        }
        if($ITEM_ID!="")
            $selected_item_id.=$ITEM_ID;
        if($selected_item_id!="")
            $where_str_in.=" and find_in_set(item_id,'$selected_item_id') ";

        $sql_in="select * from hr_integral_data where 1=1 ".$where_str_in;
        $cursor_in=db_query($sql_in, TD::conn());
        while($r_in=mysql_fetch_array($cursor_in))
        {
            if($r_in["INTEGRAL_DATA"]==0) continue;
            $INTEGRAL_TYPE_POINT=$r_in["INTEGRAL_TYPE"];
            $USER_ID=$r_in["USER_ID"];
            $USER_NAME=trim(GetUserNameById($USER_ID),",");
            $INTEGRAL_REASON=$r_in["INTEGRAL_REASON"];
            $INTEGRAL_DATA=$r_in["INTEGRAL_DATA"];
            $CREATE_PERSON=$r_in["CREATE_PERSON"];
            $INTEGRAL_TIME=$r_in["INTEGRAL_TIME"];
            $ITEM_NO=$r_in["ITEM_ID"];
            $ITEM_NAME = $ITEM_NO==0 ? _("未定义") : getItemName($ITEM_NO);

            if($INTEGRAL_TYPE_POINT == '3')
                $INTEGRAL_TYPE_POINT=_("自定义项积分录入");
            else
                $INTEGRAL_TYPE_POINT=_("未定义项积分录入");

            $result_arr[$count]["INTEGRAL_TYPE"]=$INTEGRAL_TYPE_POINT;
            $result_arr[$count]["ITEM_NAME"]=$ITEM_NAME;
            $result_arr[$count]["USER_NAME"]=$USER_NAME_POINT;
            $result_arr[$count]["INTEGRAL_TIME"]=$INTEGRAL_TIME;
            $result_arr[$count]["REASON"]=$INTEGRAL_REASON;
            $result_arr[$count]["POINT"]=$INTEGRAL_DATA;
            $result_arr[$count]["DEPAT"]=$DEPART_ID_POINT;
            $count++;
        }
    }

}
$OUTPUT_HEAD=array(_("部门"),_("积分人"),_("积分类型"),_("积分项目"),_("积分时间"),_("积分理由"),_("分值"));

ob_end_clean();
require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("详细积分导出"));
$objExcel->addHead($OUTPUT_HEAD);

foreach($result_arr as $key => $value)
{
    $INTEGRAL_TYPE_INFO=format_cvs($value["INTEGRAL_TYPE"]);
    $ITEM_NAME_INFO=format_cvs($value["ITEM_NAME"]);
    $USER_NAME_INFO=format_cvs($value["USER_NAME"]);
    $INTEGRAL_TIME_INFO=format_cvs($value["INTEGRAL_TIME"]);
    $REASON_INFO=format_cvs($value["REASON"]);
    $POINT_INFO=format_cvs($value["POINT"]);
    $DEPAT_INFO=format_cvs($value["DEPAT"]);
    $REASON_INFO = strip_tags($REASON_INFO);
    $REASON_INFO = str_replace("&nbsp;","  ",$REASON_INFO); 
    $objExcel->addRow("$DEPAT_INFO,$USER_NAME_INFO,$INTEGRAL_TYPE_INFO,$ITEM_NAME_INFO,$INTEGRAL_TIME_INFO,$REASON_INFO,$POINT_INFO");
}
$objExcel->Save();
