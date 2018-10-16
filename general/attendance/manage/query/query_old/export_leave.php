<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_org.php");

$EXCEL_OUT=array(_("部门"), _("姓名"), _("请假原因"), _("占年休假"), _("登记IP"), _("开始日期"), _("结束日期"), _("审批人员"), _("状态"));

if($DEPARTMENT1!="ALL_DEPT")
{
	 $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
   $WHERE_STR=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}

$query = "SELECT * from ATTEND_LEAVE,USER,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$WHERE_STR." and ATTEND_LEAVE.USER_ID=USER.USER_ID and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3') order by DEPT_NO,USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID=$ROW["USER_ID"];
  $DEPT_ID=$ROW["DEPT_ID"];
  $USER_NAME=$ROW["USER_NAME"];
  $LEAVE_ID=$ROW["LEAVE_ID"];
  $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
  $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
  $REGISTER_IP=$ROW["REGISTER_IP"];
  $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];

  $LEAVE_TYPE=str_replace("\r\n","",$ROW["LEAVE_TYPE"]);
  $LEAVE_TYPE=str_replace(",",_("，"),$LEAVE_TYPE);
  $LEADER_ID=$ROW["LEADER_ID"];
  $STATUS=$ROW["STATUS"];

   $LEADER_NAME="";
   $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor1))
      $LEADER_NAME=$ROW["USER_NAME"];

  if(!is_dept_priv($DEPT_ID))
     continue;

  $LEAVE_COUNT++;

  if($STATUS==1)
     $STATUS=_("现行");
  else
     $STATUS=_("已销假");
  $DEPT_ID=intval($DEPT_ID);
  $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW=mysql_fetch_array($cursor1))
     $USER_DEPT_NAME=$ROW["DEPT_NAME"];

  $EXCEL_OUT.="$USER_DEPT_NAME,$USER_NAME,$LEAVE_TYPE,$ANNUAL_LEAVE,$REGISTER_IP,$LEAVE_DATE1,$LEAVE_DATE2,$LEADER_NAME,$STATUS\n";
}

ob_end_clean();
Header("Cache-control: private");
Header("Content-type: application/vnd.ms-excel");
Header("Accept-Ranges: bytes");
Header("Accept-Length: ".strlen($EXCEL_OUT));
Header("Content-Length: ".strlen($EXCEL_OUT));
Header("Content-Disposition: attachment; ".get_attachment_filename(_("考勤请假数据").".csv"));

if(MYOA_IS_UN == 1)
   echo chr(0xEF).chr(0xBB).chr(0xBF);

echo $EXCEL_OUT;
?>