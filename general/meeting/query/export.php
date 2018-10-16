<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$CUR_DATE=date("Y-m-d",time());

//-----------�Ϸ���У��---------
if($M_START_B!="")
{
    $TIME_OK=is_date($M_START_B);
    if(!$TIME_OK)
    {
        $msg=sprintf(_("��ʼʱ��ĸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE);
        Message(_("����"),$msg);
        Button_Back();
        exit;
    }
}

if($M_END_B!="")
{
    $TIME_OK=is_date($M_END_B);
    if(!$TIME_OK)
    {
        $msg=sprintf(_("��ʼʱ��ĸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE);
        Message(_("����"),$msg);
        Button_Back();
        exit;
    }
}

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "M_NAME,M_TOPIC,M_DESC,M_PROPOSER,M_REQUEST_TIME,M_ATTENDEE,M_ATTENDEE_OUT,DEPT_NAME,PRIV_NAME,SECRET_TO_ID,M_START_B,M_END_B,M_ROOM,ADMIN,M_STATUS";//Ӣ�İ����ʾ
else
    $OUTPUT_HEAD = _("��������").","._("��������").","._("��������").","._("������").","._("����ʱ��").","._("��ϯ��Ա(�ڲ�)").","._("��ϯ��Ա(�ⲿ)").","._("������Χ(����)").","._("������Χ(��ɫ)").","._("������Χ(��Ա)").","._("��ʼʱ��").","._("����ʱ��").","._("������").","._("�������Ա").","._("״̬");

ob_end_clean();

require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("���������Ϣ"));
$objExcel->addHead($OUTPUT_HEAD);

//------------------------ ���������ַ��� ------------------
if($M_START_B!="")
    $M_START_B=$M_START_B." 00:00:00";
else
    $M_START_B="";
if($M_END_B!="")
    $M_END_B=$M_END_B." 23:59:59";
else
    $M_START_B="";

$CONDITION_STR="";
if($M_NAME!="")
    $CONDITION_STR.=" and M_NAME like '%".$M_NAME."%'";
if($TO_ID!="")
    $CONDITION_STR.=" and M_PROPOSER='$TO_ID'";
if($M_START_B!="")
    $CONDITION_STR.=" and M_START>='$M_START_B'";
if($M_END_B!="")
    $CONDITION_STR.=" and M_START<='$M_END_B'";
if($M_ROOM!="")
    $CONDITION_STR.=" and M_ROOM='$M_ROOM'";
if($M_STATUS!="")
    $CONDITION_STR.=" and M_STATUS='$M_STATUS'";
if($COPY_TO_ID!=""){
    $COPY_TO_ID_ARR = array_filter(explode(",",$COPY_TO_ID));
    foreach ($COPY_TO_ID_ARR as $v){
        $CONDITION_STR.=" and find_in_set('$v',M_ATTENDEE)";
    }
}

if($_SESSION["LOGIN_USER_PRIV"]!=1)
    $query = "SELECT * from MEETING where (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SECRET_TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',M_ATTENDEE) or M_PROPOSER='".$_SESSION["LOGIN_USER_ID"]."' or M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."') ";
else
    $query = "SELECT * from MEETING where 1=1 ";
$query.=$CONDITION_STR." order by M_START desc, M_ROOM desc";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $M_NAME=format_cvs($ROW["M_NAME"]);
    $M_TOPIC=format_cvs($ROW["M_TOPIC"]);

    $M_DESC=format_cvs($ROW["M_DESC"]);
    $M_PROPOSER=format_cvs(td_trim(GetUserNameById($ROW["M_PROPOSER"])));
    $M_REQUEST_TIME=format_cvs($ROW["M_REQUEST_TIME"]);
    $M_ATTENDEE=format_cvs(td_trim(GetUserNameById($ROW["M_ATTENDEE"])));
    $M_ATTENDEE_OUT=format_cvs($ROW["M_ATTENDEE_OUT"]);
    $SECRET_TO_ID=format_cvs(td_trim(GetUserNameById($ROW["SECRET_TO_ID"])));
    $PRIV_ID=$ROW["PRIV_ID"];
    $TO_ID=$ROW["TO_ID"];
    $M_START=format_cvs($ROW["M_START"]);
    $M_MANAGER=format_cvs(td_trim(GetUserNameById($ROW["M_MANAGER"])));
    $M_END=format_cvs($ROW["M_END"]);//format_cvs����һ���ļ���ʽ  ���ݵ�������ת��Ϊ����Ļ���   Ҫ���������
    $M_ROOM=format_cvs($ROW["M_ROOM"]);
    $M_STATUS=format_cvs($ROW["M_STATUS"]);
    $RECORDER=format_cvs($ROW["RECORDER"]);
    $REASON=format_cvs($ROW["REASON"]);

    $query4 = "SELECT MR_NAME from MEETING_ROOM where MR_ID='$M_ROOM'";
    $cursor4= exequery(TD::conn(),$query4);
    $ROW4=mysql_fetch_array($cursor4);
    $M_ROOM_NAME=$ROW4["MR_NAME"];

    if($M_STATUS==0)
        $M_STATUS_DESC=_("����");
    elseif($M_STATUS==1)
        $M_STATUS_DESC=_("��׼");
    elseif($M_STATUS==2)
        $M_STATUS_DESC=_("������");
    elseif($M_STATUS==3)
        $M_STATUS_DESC=_("δ��׼");
    elseif($M_STATUS==4)
        $M_STATUS_DESC=_("�ѽ���");//td_trim()  ȥ�����Ķ���

    $DEPT_NAME = format_cvs(td_trim(GetDeptNameById($TO_ID))); //��������
    $PRIV_NAME = format_cvs(td_trim(GetPrivNameById($PRIV_ID))); //������ɫ

    $M_DESC = strip_tags($M_DESC);
    $M_DESCS = str_replace("&nbsp;","  ",$M_DESC);

    $OUTPUT = $M_NAME.",".$M_TOPIC.",".$M_DESCS.",".$M_PROPOSER.",".$M_REQUEST_TIME.",".$M_ATTENDEE.",".$M_ATTENDEE_OUT.",".$DEPT_NAME.",".$PRIV_NAME.",".$SECRET_TO_ID.",".$M_START.",".$M_END.",".$M_ROOM_NAME.",".$M_MANAGER.",".$M_STATUS_DESC;
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>