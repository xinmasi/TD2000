<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

if(MYOA_IS_UN == 1)
	$EXCEL_OUT="DEPARTMENT,NAME,REASON,IP,DATE_OF_LEAVE,TIME_OF_LEAVE,TIME_OF_BACK,APPROVAL_OF_STAFF,STATUS\n";
else
	$EXCEL_OUT=_("����").","._("����").","._("���ԭ��").","._("�Ǽ�IP").","._("�������").","._("���ʱ��").","._("����ʱ��").","._("������Ա").","._("״̬")."\n";

if(MYOA_IS_UN == 1)
	$FILE_NAME = "ATTENDANCE DATA FOR OUT";
else
	$FILE_NAME = _("�����������");

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName($FILE_NAME);
$objExcel->addHead($EXCEL_OUT);

$is_manager = 0;
if($DEPARTMENT1!="ALL_DEPT")
{
	$DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
	$where_str=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
	
	$sql = "SELECT DEPT_ID FROM hr_manager WHERE find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_MANAGER) and DEPT_ID = '$DEPARTMENT1'";
	$cursor1 = exequery(TD::conn(),$sql);
	if($arr=mysql_fetch_array($cursor1))
	{
		$is_manager = 1;
	}
}

$query = "SELECT * from ATTEND_OUT,USER,USER_EXT,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$where_str." and ATTEND_OUT.USER_ID=USER.USER_ID and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE!='99' and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') order by DEPT_NO,USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query);
$OUT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID=format_cvs($ROW["USER_ID"]);
  $USER_NAME=format_cvs($ROW["USER_NAME"]);
  $OUT_TYPE=str_replace("\r\n","",format_cvs($ROW["OUT_TYPE"]));
	$OUT_TYPE=str_replace(",",_("��"),format_cvs($OUT_TYPE));
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

  if(!is_dept_priv($DEPT_ID) && $is_manager !=1)
     continue;

  $OUT_COUNT++;

  if($STATUS=="0")
     $STATUS_DESC=_("���");
  else if($STATUS=="1")
     $STATUS_DESC=_("�ѹ���");
  if($ALLOW=='0')
     $STATUS_DESC=_("����");
  if($ALLOW=='2')
     $STATUS_DESC=_("����׼");

  $EXCEL_OUT ="$USER_DEPT_NAME,$USER_NAME,$OUT_TYPE,$REGISTER_IP,$SUBMIT_DATE,$OUT_TIME1,$OUT_TIME2,$LEADER_NAME,$STATUS_DESC\n";

  $objExcel->addRow($EXCEL_OUT);
}

ob_end_clean();
$objExcel->Save();
?>