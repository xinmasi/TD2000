<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_org.php");

if(MYOA_IS_UN == 1)
	$EXCEL_OUT="DEPARTMENT,NAME,REASON,IP,DATE_OF_LEAVE,TIME_OF_LEAVE,TIME_OF_BACK,APPROVAL_OF_STAFF,STATUS";
else
	$EXCEL_OUT=array(_("部门"), _("姓名"), _("外出原因"), _("登记IP"), _("外出日期"), _("外出时间"), _("归来时间"), _("审批人员"), _("状态"));

if($DEPARTMENT1!="ALL_DEPT")
{
	 $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
   $where_str=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}

$query = "SELECT * from ATTEND_OUT,USER,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$where_str." and ATTEND_OUT.USER_ID=USER.USER_ID and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') order by DEPT_NO,USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query);
$OUT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID=format_cvs($ROW["USER_ID"]);
  $USER_NAME=format_cvs($ROW["USER_NAME"]);
  $OUT_TYPE=str_replace("\r\n","",format_cvs($ROW["OUT_TYPE"]));
	$OUT_TYPE=str_replace(",",_("，"),format_cvs($OUT_TYPE));
  $SUBMIT_TIME=format_cvs($ROW["SUBMIT_TIME"]);
  $SUBMIT_DATE=substr(format_cvs($SUBMIT_TIME),0,10);
  $OUT_TIME1=format_cvs($ROW["OUT_TIME1"]);
  $OUT_TIME2=format_cvs($ROW["OUT_TIME2"]);
  $REGISTER_IP=format_cvs($ROW["REGISTER_IP"]);
  $ALLOW=format_cvs($ROW["ALLOW"]);
  $STATUS=format_cvs($ROW["STATUS"]);
  $DEPT_ID=format_cvs($ROW["DEPT_ID"]);
  $USER_DEPT_NAME=format_cvs($ROW["DEPT_NAME"]);
  $LEADER_ID=format_cvs($ROW["LEADER_ID"]);

  $LEADER_NAME="";
  $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
  $cursor1= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor1))
     $LEADER_NAME=format_cvs($ROW["USER_NAME"]);

  if(!is_dept_priv($DEPT_ID))
     continue;

  $OUT_COUNT++;

  if($STATUS=="0")
     $STATUS_DESC=_("外出");
  else if($STATUS=="1")
     $STATUS_DESC=_("已归来");
  if($ALLOW=='0')
     $STATUS_DESC=_("待批");
  if($ALLOW=='2')
     $STATUS_DESC=_("不批准");



  $EXCEL_OUT ="$USER_DEPT_NAME,$USER_NAME,$OUT_TYPE,$REGISTER_IP,$SUBMIT_DATE,$OUT_TIME1,$OUT_TIME2,$LEADER_NAME,$STATUS_DESC";
	$objExcel->addRow($EXCEL_OUT);
}

ob_end_clean();

$objExcel->Save();
?>