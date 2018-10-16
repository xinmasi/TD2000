<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

if(MYOA_IS_UN == 1)
	$EXCEL_OVERTIME="DEPARTMENT,NAME,CONTENT,IP,DATE_OF_LEAVE,DATE_OF_END,LENGTH_OF_OVERTIME,APPROVAL_OF_STAFF,CONFIRM_TIME,STATUS\n";
else
	$EXCEL_OVERTIME=_("部门").","._("姓名").","._("加班内容").","._("登记IP").","._("开始时间").","._("结束时间").","._("加班时长").","._("审批人员").","._("确认时间").","._("状态")."\n";

if(MYOA_IS_UN == 1)
	$FILE_NAME = "ATTENDANCE DATA FOR OVERTIME";
else
	$FILE_NAME = _("考勤加班数据");	

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName($FILE_NAME);
$objExcel->addHead($EXCEL_OVERTIME);

if($DEPARTMENT1!="ALL_DEPT")
{
	 $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
   $where_str=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}

$query = "SELECT * from ATTENDANCE_OVERTIME,USER,USER_EXT,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID and ATTENDANCE_OVERTIME.USER_ID=USER.USER_ID and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE='99' and ((to_days(START_TIME)>=to_days('$DATE1') and to_days(START_TIME)<=to_days('$DATE2')) or (to_days(END_TIME)>=to_days('$DATE1') and to_days(END_TIME)<=to_days('$DATE2')) or (to_days(START_TIME)<=to_days('$DATE1') and to_days(END_TIME)>=to_days('$DATE2')))and allow in('1','3') order by DEPT_NO,USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query);
$OVERTIME_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID=format_cvs($ROW["USER_ID"]);
  $USER_NAME=format_cvs($ROW["USER_NAME"]);
  $APPROVE_ID=format_cvs($ROW["APPROVE_ID"]);
  $RECORD_TIME=format_cvs($ROW["RECORD_TIME"]);
  $START_TIME=format_cvs($ROW["START_TIME"]);
  $END_TIME=format_cvs($ROW["END_TIME"]);
  $OVERTIME_HOURS=format_cvs($ROW["OVERTIME_HOURS"]);
  $OVERTIME_CONTENT=format_cvs($ROW["OVERTIME_CONTENT"]);
  $CONFIRM_VIEW=format_cvs($ROW["CONFIRM_VIEW"]);
  $ALLOW=format_cvs($ROW["ALLOW"]);
  $STATUS=format_cvs($ROW["STATUS"]);
  $REASON=format_cvs($ROW["REASON"]);
  $DEPT_ID=format_cvs($ROW["DEPT_ID"]);
  $USER_DEPT_NAME=format_cvs($ROW["DEPT_NAME"]);
  $CONFIRM_TIME=format_cvs($ROW["CONFIRM_TIME"]);
  $REGISTER_IP=format_cvs($ROW["REGISTER_IP"]);
   
  $REASON=str_replace(",",_("，"),$REASON);
  $APPROVE_NAME="";
  $query = "SELECT * from USER where USER_ID='$APPROVE_ID'";
  $cursor1= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor1))
     $APPROVE_NAME=format_cvs($ROW["USER_NAME"]);

  if(!is_dept_priv($DEPT_ID))
     continue;

  $OVERTIME_COUNT++;

  if($STATUS=="0")
      $STATUS_DESC=_("未确认");
  else if($STATUS=="1")
     $STATUS_DESC=_("已确认");

  if($CONFIRM_VIEW!="")
     $OVERTIME_CONTENT=$OVERTIME_CONTENT._(" 确认意见：").$CONFIRM_VIEW;
     
  $EXCEL_OVERTIME="$USER_DEPT_NAME,$USER_NAME,$OVERTIME_CONTENT,$REGISTER_IP,$START_TIME,$END_TIME,$OVERTIME_HOURS,$APPROVE_NAME,$CONFIRM_TIME,$STATUS_DESC\n";
	
	$objExcel->addRow($EXCEL_OVERTIME);
}

ob_end_clean();

$objExcel->Save();
?>