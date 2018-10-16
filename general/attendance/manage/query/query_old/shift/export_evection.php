<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

if(MYOA_IS_UN == 1)
	$EXCEL_OUT="DEPARTMENT,NAME,TRAVEL_TO,IP,DATE_OF_START,DATE_OF_END,APPROVAL_OF_STAFF,STATUS\n";
else
	$EXCEL_OUT=_("部门").","._("姓名").","._("出差地点").","._("登记IP").","._("开始日期").","._("结束日期").","._("审批人员").","._("状态")."\n";

if(MYOA_IS_UN == 1)
	$FILE_NAME = "ATTENDANCE DATA FOR EVECTION";
else
	$FILE_NAME = _("考勤出差数据");	
	
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName($FILE_NAME);
$objExcel->addHead($EXCEL_OUT);

if($DEPARTMENT1!="ALL_DEPT")
{
	 $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
   $WHERE_STR=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}

$query = "SELECT * from ATTEND_EVECTION,USER,USER_EXT,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID and ATTEND_EVECTION.USER_ID=USER.USER_ID and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE='99' and ((to_days(EVECTION_DATE1)>=to_days('$DATE1') and to_days(EVECTION_DATE1)<=to_days('$DATE2')) or (to_days(EVECTION_DATE2)>=to_days('$DATE1') and to_days(EVECTION_DATE2)<=to_days('$DATE2')) or (to_days(EVECTION_DATE1)<=to_days('$DATE1') and to_days(EVECTION_DATE2)>=to_days('$DATE2'))) and ALLOW='1' order by DEPT_NO,USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query);
$EVECTION_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID=format_cvs($ROW["USER_ID"]);
  $DEPT_ID=format_cvs($ROW["DEPT_ID"]);
  $USER_NAME=format_cvs($ROW["USER_NAME"]);
  $EVECTION_ID=format_cvs($ROW["EVECTION_ID"]);
  $EVECTION_DATE1=format_cvs($ROW["EVECTION_DATE1"]);
  $EVECTION_DATE1=strtok($EVECTION_DATE1," ");
  $EVECTION_DATE2=format_cvs($ROW["EVECTION_DATE2"]);
  $EVECTION_DATE2=strtok($EVECTION_DATE2," ");
  $EVECTION_DEST=str_replace("\r\n","",format_cvs($ROW["EVECTION_DEST"]));
  $EVECTION_DEST=str_replace(",",_("，"),format_cvs($EVECTION_DEST));
  $STATUS=format_cvs($ROW["STATUS"]);
  $REGISTER_IP=format_cvs($ROW["REGISTER_IP"]);
  $LEADER_ID=format_cvs($ROW["LEADER_ID"]);

  $LEADER_NAME="";
  $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
  $cursor1= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor1))
     $LEADER_NAME=format_cvs($ROW["USER_NAME"]);

 if(!is_dept_priv($DEPT_ID))
    continue;

 $EVECTION_COUNT++;

 if($STATUS=="1")
    $STATUS=_("在外");
 else
    $STATUS=_("归来");
 $DEPT_ID=intval($DEPT_ID);
 $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
 $cursor1= exequery(TD::conn(),$query1);
 if($ROW=mysql_fetch_array($cursor1))
    $USER_DEPT_NAME=$ROW["DEPT_NAME"];

 $EXCEL_OUT="$USER_DEPT_NAME,$USER_NAME,$EVECTION_DEST,$REGISTER_IP,$EVECTION_DATE1,$EVECTION_DATE2,$LEADER_NAME,$STATUS\n";
 
 $objExcel->addRow($EXCEL_OUT);
}

ob_end_clean();

$objExcel->Save();
?>