<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_org.php");

$EXCEL_OVERTIME=array(_("部门"), _("姓名"), _("加班内容"), _("登记IP"), _("开始时间"), _("结束时间"), _("加班时长"), _("审批人员"), _("确认时间"), _("状态"));

if($DEPARTMENT1!="ALL_DEPT")
{
	 $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
   $where_str=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}

$query = "SELECT * from ATTENDANCE_OVERTIME,USER,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$where_str." and ATTENDANCE_OVERTIME.USER_ID=USER.USER_ID and ((to_days(START_TIME)>=to_days('$DATE1') and to_days(START_TIME)<=to_days('$DATE2')) or (to_days(END_TIME)>=to_days('$DATE1') and to_days(END_TIME)<=to_days('$DATE2')) or (to_days(START_TIME)<=to_days('$DATE1') and to_days(END_TIME)>=to_days('$DATE2')))and allow in('1','3') order by DEPT_NO,USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query);
$OVERTIME_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID=$ROW["USER_ID"];
  $USER_NAME=$ROW["USER_NAME"];
  $APPROVE_ID=$ROW["APPROVE_ID"];
  $RECORD_TIME=$ROW["RECORD_TIME"];
  $START_TIME=$ROW["START_TIME"];
  $END_TIME=$ROW["END_TIME"];
  $OVERTIME_HOURS=$ROW["OVERTIME_HOURS"];
  $OVERTIME_CONTENT=$ROW["OVERTIME_CONTENT"];
  $OVERTIME_CONTENT=str_replace("\n","",$OVERTIME_CONTENT);
  $OVERTIME_CONTENT=str_replace("\r","",$OVERTIME_CONTENT);
  $CONFIRM_VIEW=$ROW["CONFIRM_VIEW"];
  $ALLOW=$ROW["ALLOW"];
  $STATUS=$ROW["STATUS"];
  $REASON=$ROW["REASON"];
  $REASON=str_replace(",",_("，"),$REASON);
  $REASON=str_replace("\n","",$REASON);
  $REASON=str_replace("\r","",$REASON);
  $DEPT_ID=$ROW["DEPT_ID"];
  $USER_DEPT_NAME=$ROW["DEPT_NAME"];
  $CONFIRM_TIME=$ROW["CONFIRM_TIME"];
  $REGISTER_IP=$ROW["REGISTER_IP"];
   
  $APPROVE_NAME="";
  $query = "SELECT * from USER where USER_ID='$APPROVE_ID'";
  $cursor1= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor1))
     $APPROVE_NAME=$ROW["USER_NAME"];

  if(!is_dept_priv($DEPT_ID))
     continue;

  $OVERTIME_COUNT++;

  if($STATUS=="0")
      $STATUS_DESC=_("未确认");
  else if($STATUS=="1")
     $STATUS_DESC=_("已确认");

  if($CONFIRM_VIEW!="")
     $OVERTIME_CONTENT=$OVERTIME_CONTENT._(" 确认意见：").$CONFIRM_VIEW;
     
  $EXCEL_OVERTIME.="$USER_DEPT_NAME,$USER_NAME,$OVERTIME_CONTENT,$REGISTER_IP,$START_TIME,$END_TIME,$OVERTIME_HOURS,$APPROVE_NAME,$CONFIRM_TIME,$STATUS_DESC\n";
}

ob_end_clean();
Header("Cache-control: private");
Header("Content-type: application/vnd.ms-excel");
Header("Accept-Ranges: bytes");
Header("Accept-Length: ".strlen($EXCEL_OVERTIME));
Header("Content-Length: ".strlen($EXCEL_OVERTIME));
Header("Content-Disposition: attachment; ".get_attachment_filename(_("考勤加班数据").".csv"));

if(MYOA_IS_UN == 1)
   echo chr(0xEF).chr(0xBB).chr(0xBF);

echo $EXCEL_OVERTIME;
?>