<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------У��-------------------------------------
/*if($INTEGRAL_DATA!="" && !is_date($INTEGRAL_DATA))
{
   Message("",_("�ػ�����ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
*/
if(trim($USER_IDS,",")=="")
{
    Message("",_("��ѡ���û��ֵ���Ա"));
    Button_Back();
    exit;
}
if(trim($ITEM_ID,",")=="" && $INTEGRAL_TYPE==3)
{
    Message("",_("��ѡ���Զ��������"));
    Button_Back();
    exit;
}

if($INTEGRAL_TYPE!=3)
    $ITEM_ID="0";
if($INTEGRAL_TIME=="")
    $INTEGRAL_TIME=$CUR_TIME;
$USERS_ARR=explode(",",trim($USER_IDS,","));

foreach($USERS_ARR as $USER_ID)
{
    $CREATE_USER=$_SESSION["LOGIN_USER_ID"];
    $query="insert into HR_INTEGRAL_DATA(ITEM_ID,INTEGRAL_REASON,INTEGRAL_TYPE,USER_ID,INTEGRAL_DATA,CREATE_PERSON,CREATE_TIME,INTEGRAL_TIME,CREATE_USER) values ('$ITEM_ID','$INTEGRAL_REASON','$INTEGRAL_TYPE','$USER_ID','$INTEGRAL_DATA','$CREATE_PERSON','$CUR_TIME','$INTEGRAL_TIME','$CREATE_USER')";
    exequery(TD::conn(),$query);
    $ID=mysql_insert_id();
    $url="1:hr/self_find/integral/detail.php?ID=$ID&from=email";
    //------- �������� --------
    $SMS_CONTENT=_("���Ļ��ֱ䶯����鿴��");
    if($SMS_REMIND=="on")
        send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,78,$SMS_CONTENT,$url,$ID);

    if($SMS2_REMIND=="on")
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,78);
}
Message("",_("����¼��ɹ���"));
?>
<div align="center">
    <input type="button"  value="<?=_("����")?>" class="BigButton" onClick="location='index1.php';">
</div>
</body>
</html>
