<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("�½���ͬ��Ϣ");
include_once("inc/header.inc.php");


$uid       = td_trim(GetUidByUserID($STAFF_NAME));
$user_info = TD::get_cache('C_USER_'.floor($uid/MYOA_C_USER_GROUP_BY));
$user_name = $user_info[$uid]['BYNAME']

?>

<body class="bodycolor" topmargin="5">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------�ж��Ƿ�Ϊ����Ա-------------------------------------
if($STAFF_NAME=="admin")
{
   Message("",_("ϵͳ����Ա��������Ӻ�ͬ��"));
   Button_Back();
   exit;
}
//-----------------У��-------------------------------------
if($MAKE_CONTRACT!="" && !is_date($MAKE_CONTRACT))
{
   Message("",_("��ͬǩ������ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
if($CONTRACT_EFFECTIVE_TIME!="" && !is_date($CONTRACT_EFFECTIVE_TIME))
{
   Message("",_("��Ч����ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}

if($CONTRACT_END_DATE!="" && !is_date($CONTRACT_END_DATE))
{
   Message("",_("��ͬ��ֹ����ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
if($TRAIL_OVER_TIME!="" && !is_date($TRAIL_OVER_TIME))
{
   Message("",_("���ý�ֹ����ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
if($CONTRACT_REMOVE_TIME!="" && !is_date($CONTRACT_REMOVE_TIME))
{
   Message("",_("��ͬ�������ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}

if($CONTRACT_RENEW_TIME!="" && !is_date($CONTRACT_RENEW_TIME))
{
   Message("",_("��Լ����ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
//��������
$REMIND_TYPE=0;
if($SMS_REMIND=="on" && $SMS2_REMIND=="on")
	$REMIND_TYPE=3;
else if($SMS_REMIND=="on" && $SMS2_REMIND!="on")
	$REMIND_TYPE=1;
else if($SMS2_REMIND=="on" && $SMS_REMIND!="on")
	$REMIND_TYPE=2;
//--------- �ϴ����� ----------
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();
   $ATTACHMENT_ID=$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

//------------------- ��ͬ��Ϣ -----------------------
if($TRAIL_PASS_OR_NOT==_("��"))
{
    $TRAIL_PASS_OR_NOT=1;
    if($PASS_OR_NOT==_("��"))
        $PASS_OR_NOT=1;
    if($PASS_OR_NOT==_("��"))
        $PASS_OR_NOT=0;
}
if($TRAIL_PASS_OR_NOT==_("��"))
{
    $TRAIL_PASS_OR_NOT=0;
    $TRAIL_OVER_TIME="";
}

if($REMOVE_OR_NOT==_("��"))
   $REMOVE_OR_NOT=1;
if($REMOVE_OR_NOT==_("��"))
{
    $REMOVE_OR_NOT=0;
    $CONTRACT_REMOVE_TIME="";
}

if($RENEW_OR_NOT==_("��"))
   $RENEW_OR_NOT=1;
if($RENEW_OR_NOT==_("��"))
{
    $RENEW_OR_NOT=0;
    $CONTRACT_RENEW_TIME_NEW="0000-00-00";
}
if($TRAIL_PASS_OR_NOT==0 && $REMOVE_OR_NOT==0 && $RENEW_OR_NOT==0)
{
    $PASS_OR_NOT=1;
}

if($STAFF_CONTRACT_NO =="")
{
    $STAFF_CONTRACT_NO=  time();
}
//------- �жϺ�ͬ״̬ --------
if($TRAIL_PASS_OR_NOT==1 && $PASS_OR_NOT==0 && $REMOVE_OR_NOT==0 && $RENEW_OR_NOT==0)
{
    $STATUS=1;
}
elseif($REMOVE_OR_NOT==1)
{
    $STATUS=3;
}
else
{
    $STATUS=2;
}
//��ͬ�����ж�
//------- ����Ա����ɫ --------
if($role != "")
{
$query2 = "SELECT * from  user_priv WHERE USER_PRIV = '$role'";
$cursor2= exequery(TD::conn(),$query2);
if($ROW2=mysql_fetch_array($cursor2))
{
    $PRIV_NO = $ROW2["PRIV_NO"];
    $PRIV_NAME= $ROW2["PRIV_NAME"];
}
$query="UPDATE user SET USER_PRIV='$role',USER_PRIV_NO='$PRIV_NO',USER_PRIV_NAME='$PRIV_NAME' WHERE USER_ID = '$STAFF_NAME'";
exequery(TD::conn(),$query);
}
//------------------- ��Ϣ��ʾ���� -----------------------
$query = "SELECT PARA_VALUE from  sys_para where PARA_NAME='TRIAL_LABOR_DAY'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
    $TRIAL_LABOR_DAY=explode(",",$ROW['PARA_VALUE']);
}
if($TRAIL_PASS_OR_NOT==1 && $PASS_OR_NOT==0 && $REMOVE_OR_NOT==0 && $RENEW_OR_NOT==0)
{
    if($TRIAL_LABOR_DAY[0]!=null)
    {
        $REMIND_TIME=date('Y-m-d   H:i:s',strtotime($TRAIL_OVER_TIME."-".$TRIAL_LABOR_DAY[0]." days +9 hours"));
    }
}

if($PASS_OR_NOT==1 && $REMOVE_OR_NOT==0 && $RENEW_OR_NOT==0)
{
    if($TRIAL_LABOR_DAY[1]!=null)
    {
        $REMIND_TIME=date('Y-m-d   H:i:s',strtotime($CONTRACT_END_DATE."-".$TRIAL_LABOR_DAY[1]." days +9 hours"));
    }
}
if($REMOVE_OR_NOT==1 && $RENEW_OR_NOT==0)
{
    if($TRIAL_LABOR_DAY[1]!=null)
    {
        $REMIND_TIME=date('Y-m-d   H:i:s',strtotime($CONTRACT_REMOVE_TIME."-".$TRIAL_LABOR_DAY[1]." days +9 hours"));
    }
}
if($RENEW_OR_NOT==1)
{
    if($TRIAL_LABOR_DAY[1]!=null)
    {
        $REMIND_TIME=date('Y-m-d   H:i:s',strtotime($CONTRACT_RENEW_TIME."-".$TRIAL_LABOR_DAY[1]." days +9 hours"));
    }
}
if($CONTRACT_RENEW_TIME!="" && $RENEW_OR_NOT==1)
{
    $CONTRACT_RENEW_TIME_NEW=$CONTRACT_RENEW_TIME."|";
}
else
{
    $CONTRACT_RENEW_TIME_NEW="0000-00-00";
}
//------------------- Ա���ж��Ƿ��Ѿ��½���ͬ -----------------------
$query = "SELECT CONTRACT_END_TIME from  hr_staff_contract where STAFF_NAME='$STAFF_NAME' and CONTRACT_TYPE='$CONTRACT_TYPE'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
    Message("",_("��ͬ��Ϣ�Ѵ���"));
    Button_Back();
    exit;
}
$sql="insert into  HR_STAFF_CONTRACT (
  CREATE_USER_ID,
  CREATE_DEPT_ID,
  STAFF_NAME,
  STAFF_USER_NAME,
  STAFF_CONTRACT_NO,
  USER_NAME,
  CONTRACT_TYPE,
  CONTRACT_SPECIALIZATION,
  MAKE_CONTRACT,
  TRAIL_EFFECTIVE_TIME,
  PROBATIONARY_PERIOD,
  TRAIL_OVER_TIME,
  PASS_OR_NOT ,
  PROBATION_END_DATE,
  PROBATION_EFFECTIVE_DATE,
  ACTIVE_PERIOD,
  CONTRACT_END_TIME,
  REMOVE_OR_NOT,
  CONTRACT_REMOVE_TIME,
  STATUS,
  SIGN_TIMES,
  REMARK,
  REMIND_TIME,
  REMIND_TYPE,
  ATTACHMENT_ID,
  ATTACHMENT_NAME,
  REMIND_USER,
  ADD_TIME,
  LAST_UPDATE_TIME,
  HAS_REMINDED,
  RENEW_TIME,
  CONTRACT_ENTERPRIES,
  IS_TRIAL,
  IS_RENEW)
values
( '".$_SESSION["LOGIN_USER_ID"]."',
	'".$_SESSION["LOGIN_DEPT_ID"]."',
	'$STAFF_NAME',
	'$STAFF_NAME',
	'$STAFF_CONTRACT_NO',
	'$user_name',
	'$CONTRACT_TYPE',
	'$CONTRACT_SPECIALIZATION',
	'$MAKE_CONTRACT',
	'$CONTRACT_EFFECTIVE_TIME',
	'',
	'$TRAIL_OVER_TIME',
	'$PASS_OR_NOT',
	'$CONTRACT_END_DATE',
	'$CONTRACT_EFFECTIVE_TIME',
	'',
	'$CONTRACT_END_DATE',
	'$REMOVE_OR_NOT',
	'$CONTRACT_REMOVE_TIME',
	'$STATUS',
	'',
	'$REMARK',
	'$REMIND_TIME',
	'$REMIND_TYPE',
	'$ATTACHMENT_ID',
	'$ATTACHMENT_NAME',
	'$TO_ID',
	'$CUR_TIME',
	'$CUR_TIME',
	'0',
    '$CONTRACT_RENEW_TIME_NEW',
    '$CONTRACT_ENTERPRIES',
    '$TRAIL_PASS_OR_NOT',
    '$RENEW_OR_NOT')";
exequery(TD::conn(),$sql);


$CONTRACT_ID =mysql_insert_id();
save_field_data("CONTRACT",$CONTRACT_ID,$_POST);

//------- �������� --------�ڶ�ʱ�������������д���
//$CONTRACT_ID = mysql_insert_id();
//$REMAND_USERS=$TO_ID;
//if($TRIAL_LABOR_DAY[0]!="" && $TRIAL_LABOR_DAY[1]!="")
//{
//    $query = "SELECT a.DEPT_HR_MANAGER,b.USER_NAME from HR_MANAGER a left join USER b on a.DEPT_ID=b.DEPT_ID where b.USER_ID='$STAFF_NAME'";
//    $cursor= exequery(TD::conn(),$query);
//    $DEPT_HR_MANAGER="";
//    if($ROW=mysql_fetch_array($cursor))
//    {
//        $USER_NAME = $ROW["USER_NAME"];
//        $DEPT_HR_MANAGER = $ROW["DEPT_HR_MANAGER"];
//    }
//
//$REMAND_USERS=$DEPT_HR_MANAGER.$REMAND_USERS;
//$REMAND_USERS=str_replace(",,",",",$REMAND_USERS);
//$REMAND_USERS_trim=  trim($REMAND_USERS,',');
//$REMAND_USERS_ARRAY=  explode(',', $REMAND_USERS_trim);
//$REMAND_USERS_NEW="";
//if(in_array($STAFF_NAME, $REMAND_USERS_ARRAY))
//{
//    foreach ($REMAND_USERS_ARRAY as $key => $value)
//    {
//        if($value !=$STAFF_NAME)
//        $REMAND_USERS_NEW.=$value.",";
//    }
//}
//if($TRAIL_OVER_TIME!="" && $REMIND_TIME!="" && $TRAIL_PASS_OR_NOT==1 && $PASS_OR_NOT==0)
//{
//  if($REMAND_USERS != "")
//  {
//    $REMIND_URL="hr/manage/staff_contract/modify.php?CONTRACT_ID=".$CONTRACT_ID;
//    $SMS_CONTENT=$USER_NAME._("���������������뼰ʱ����ת��������");
//    send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS_NEW,63,$SMS_CONTENT,$REMIND_URL);
//  }
//
//  $REMIND_URL="ipanel/hr/contract_detail_info.php?CONTRACT_ID=".$CONTRACT_ID;
//  $SMS_CONTENT=$USER_NAME._("���������������뼰ʱ����ת��������");
//  send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$STAFF_NAME,63,$SMS_CONTENT,$REMIND_URL);
//}
//else
//{
//    if($REMAND_USERS != "")
//    {
//        $REMIND_URL="hr/manage/staff_contract/modify.php?CONTRACT_ID=".$CONTRACT_ID;
//        $SMS_CONTENT= $USER_NAME._("��ͬ�������ڣ��뼰ʱ������ǩ������");
//        send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS_NEW,63,$SMS_CONTENT,$REMIND_URL);
//    }
//
//    $REMIND_URL="ipanel/hr/contract_detail_info.php?CONTRACT_ID=".$CONTRACT_ID;
//    $SMS_CONTENT= $USER_NAME._("��ͬ�������ڣ��뼰ʱ������ǩ������");
//    send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$STAFF_NAME,63,$SMS_CONTENT,$REMIND_URL);
//}
////��������
//if($CONTRACT_END_TIME=="" && $TRAIL_OVER_TIME!="" && $REMIND_TIME!="" && $SMS2_REMIND=="on")
//{
//   $SMS_CONTENT=_("OA��ͬ����:").$USER_NAME._("���������������뼰ʱ����ת��������");
//   send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,63);
//}
//else if ($CONTRACT_END_TIME!="" && $REMIND_TIME!="" && $SMS_REMIND=="on")
//{
//    $SMS_CONTENT=_("OA��ͬ����:").$USER_NAME._("��ͬ�ѵ��ڣ��뼰ʱ������ǩ������");
//    send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,63);
//}
//
//}

Message("",_("�ɹ����Ӻ�ͬ��Ϣ��"));
?>
<center>
<input type="button" value="����" class="BigButtonA" onClick="location='new.php';">
</center>
</body>
</html>
