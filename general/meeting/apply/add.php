<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_org.php");
include_once("inc/flow_hook.php");
include_once("inc/itask/itask.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
//查看范围人员
function AllowUserIds($SECRET_TO_ID,$PRIV_ID,$TO_ID){
    if($PRIV_ID != ""){
        $PRIV_NAMES = td_trim(GetPrivNameById($PRIV_ID));
        $query  = "select USER_ID from user where find_in_set(USER_PRIV_NAME,'$PRIV_NAMES')";
        $cursor = exequery(TD::conn(),$query);
        while($ROW = mysql_fetch_array($cursor))
        {
            $USER_IDS.= $ROW["USER_ID"].',';
        }
        $PRIV_ID_ARRAY = explode(",",$PRIV_ID);
        array_pop($PRIV_ID_ARRAY);
        foreach($PRIV_ID_ARRAY as $V)
        {
            $query  = "select USER_ID from user where USER_PRIV_OTHER like '%$V%'";
            $cursor = exequery(TD::conn(),$query);
            while($ROW = mysql_fetch_array($cursor))
            {
                $USER_IDS.= $ROW["USER_ID"].',';
            }
        }
    }
    if($TO_ID != ""){
        $TO_ID  = td_trim($TO_ID);
        $query  = "select USER_ID from user where find_in_set(DEPT_ID,'$TO_ID')";
        $cursor = exequery(TD::conn(),$query);
        while($ROW = mysql_fetch_array($cursor))
        {
            $USER_IDS.= $ROW["USER_ID"].',';
        }
        $TO_ID_ARRAY = explode(",",$TO_ID);
        foreach($TO_ID_ARRAY as $V)
        {
            $query="select USER_ID from user where DEPT_ID_OTHER like '%$V%'";
            $cursor = exequery(TD::conn(),$query);
            while($ROW = mysql_fetch_array($cursor))
            {
                $USER_IDS.= $ROW["USER_ID"].',';
            }
        }
    }
    if($SECRET_TO_ID != ""){
        $USER_IDS.= $SECRET_TO_ID;
    }
    if($TO_ID == 'ALL_DEPT'){
        $query  ="select USER_ID from user where DEPT_ID != 0";   //离职人员不发送事务提醒
        $cursor = exequery(TD::conn(),$query);
        while($ROW = mysql_fetch_array($cursor))
        {
            $USER_IDS.= $ROW["USER_ID"].',';
        }
    }
    return $USER_IDS;
}
//验证会议室是否空闲
function check_room($M_ID,$M_ROOM,$M_START,$M_END)
{
    $query  = "SELECT M_START,M_END,M_ID,M_PROPOSER FROM meeting WHERE M_ID!='$M_ID' and M_ROOM='$M_ROOM' and (M_STATUS=0 or M_STATUS=1 or M_STATUS=2)";
    $cursor = exequery(TD::conn(),$query);
    $COUNT  = 0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $M_PROPOSER = $ROW["M_PROPOSER"];
        $M_START1   = $ROW["M_START"];
        $M_END1     = $ROW["M_END"];
        if(($M_START1>=$M_START and $M_END1<=$M_END) or ($M_START1< $M_START and $M_END1>$M_START) or ($M_START1<$M_END and $M_END1>$M_END) or ($M_START1<$M_START and $M_END1>$M_END))
        {
            $COUNT++;
            $M_IDD = $M_IDD.$ROW["M_ID"].",";
        }
    }

    $M_ID_STR = $M_IDD;
    if($COUNT>=1)
    {
        return $M_ID_STR;
    }
    else
    {
        return "#";
    }
}

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?

$SYS_PARA_ARRAY=get_sys_para("MEETING_IS_APPROVE");

/*
 * 如果不选择需要审核，会导致员工申请会议后不需要审核，视为立即批准
 * 会议申请是否需要审核 是  1
 */
$query  = "SELECT MEETING_IS_APPROVE FROM meeting_rule";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $IS_APPROVE = $ROW["MEETING_IS_APPROVE"];
}
$SMS_REMIND  = ($SMS_REMIND=="on") ? "1" : "0";
$SMS2_REMIND = ($SMS2_REMIND=="on") ? "1" : "0";

$SMS2_REMIND1= ($SMS2_REMIND1=="on") ? "1" : "0";

/* 会议时间合法性校验 */
if($M_START!="")
{
    $TIME_OK = is_date_time($M_START);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("开始时间格式不对，应形如 1999-1-2 09:30:00"));
        Button_Back();
        exit;
    }
}

if($M_END!="")
{
    $TIME_OK = is_date_time($M_END);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("结束时间格式不对，应形如 1999-1-2 09:30:00"));
        Button_Back();
        exit;
    }
}

if($M_START!="" && $M_END!="" && compare_date_time($M_END,$M_START)<=0)
{
    Message(_("错误"),_("结束日期不能小于起始日期！"));
    Button_Back();
    exit;
}
/* 会议时间合法性校验结束 */

$M_ROOM = $_POST['M_ROOM'];
$M_ROOM_new = $_POST['MR_ROOM'];
$NEW = $_POST['NEW'];
$M_ID   = $_POST['M_ID'];
$query  = "SELECT APPLY_WEEKDAYS FROM meeting_room WHERE MR_ID='$M_ROOM'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $APPLY_WEEKDAYS = $ROW["APPLY_WEEKDAYS"];
}
if($APPLY_WEEKDAYS=="") $APPLY_WEEKDAYS="1,2,3,4,5";
$check_week=1;
if(substr($M_START,0,10)==substr($M_END,0,10))
{
    $week = date("w",strtotime($M_START));
    if(!find_id($APPLY_WEEKDAYS,$week))
    {
        $check_week=0;
    }
}
else
{
    for($J=$M_START1;$J<=$M_END1;$J=date("Y-m-d",strtotime($J)+24*3600))
    {
        $week = date("w",strtotime($J));
        if(!find_id($APPLY_WEEKDAYS,$week))
        {
            $check_week = 0;
            break;
        }
    }
}

if($check_week==0) //不可申请的时间。
{
    Message(_("错误"),_("此时间段包含不允许申请会议的时段！"));
    Button_Back();
    exit;
}

check_room($M_ID,$M_ROOM,$M_START,$M_END);
$SS = substr(check_room($M_ID,$M_ROOM,$M_START,$M_END), 0, 1);
if(is_number($SS))
{
    $M_ATTENDEE = $COPY_TO_ID;

    setcookie("MEETING_M_NAME", "$M_NAME");
    setcookie("MEETING_M_TOPIC", "$M_TOPIC");
    setcookie("MEETING_M_ATTENDEE_OUT", "$M_ATTENDEE_OUT");
    setcookie("MEETING_M_ATTENDEE", "$M_ATTENDEE");
    setcookie("M_ROOM", "$M_ROOM");
    setcookie("MEETING_M_DESC", "$M_DESC");
    setcookie("MEETING_SECRET_TO_ID", "$SECRET_TO_ID");
    setcookie("MEETING_PRIV_ID", "$PRIV_ID");
    setcookie("MEETING_TO_ID", "$TO_ID");

    Message(_("错误"),_("该时段有会议冲突，<Br/>请返回重新选择会议时间段！"));
    Button_Back();
    exit;
}

setcookie("MEETING_M_NAME", "");
setcookie("MEETING_M_TOPIC", "");
setcookie("MEETING_M_ATTENDEE_OUT", "");
setcookie("MEETING_M_ATTENDEE", "");
setcookie("M_ROOM", "");
setcookie("MEETING_M_DESC", "");
setcookie("MEETING_SECRET_TO_ID", "");
setcookie("MEETING_PRIV_ID", "");
setcookie("MEETING_TO_ID", "");

//--------- 上传附件 ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS     = upload();
    $M_DESC          = ReplaceImageSrc($M_DESC, $ATTACHMENTS);
    $ATTACHMENT_ID   = $ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME = $ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
    $ATTACHMENT_ID   = $ATTACHMENT_ID_OLD;
    $ATTACHMENT_NAME = $ATTACHMENT_NAME_OLD;
}

$ATTACHMENT_ID   .= copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME .= $ATTACH_NAME;

$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$M_DESC);
$M_DESC = replace_attach_url($M_DESC);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}

$EQUIPMENT_ID_STR = "";
for($I=1;$I<=500;$I++)
{
    $TMP = "SB_".$I;
    if($$TMP!="")
    {
        $EQUIPMENT_ID_STR.= $$TMP.",";
    }
}

if($RECORDER_ID=="")
{
    if(substr($RECORDER_NAME,-1,1)==",")
    {
        $RECORDER_NAME = substr($RECORDER_NAME,0,-1);
    }

    $query  = "SELECT USER_ID FROM user WHERE USER_NAME='$RECORDER_NAME'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $RECORDER_ID = $ROW[0];
    }
}

$M_ATTENDEE1=$COPY_TO_ID.$RECORDER_ID.",";
//写入日程：是  1
if($CALENDAR=="on")
{
    $CALENDAR="1";
}
//添加电子邮件提醒控制 wrj 20140317
if($TO_EMAIL=="on")
{
    $TO_EMAIL="1";
}else
{
    $TO_EMAIL="0";
}
//是否提醒，查看范围(部门)
if($TO_SCOPE=="on")
{
    $TO_SCOPE="1";
}else
{
    $TO_SCOPE="0";
}

if($M_ID=="")
{//新增
    $query="INSERT INTO meeting(M_NAME,M_TOPIC,M_DESC,M_PROPOSER,M_REQUEST_TIME,M_ATTENDEE,M_START,M_END,M_ROOM,M_MANAGER,M_ATTENDEE_OUT,SMS_REMIND,SMS2_REMIND,ATTACHMENT_ID,ATTACHMENT_NAME,TO_ID,PRIV_ID,SECRET_TO_ID,RESEND_LONG,RESEND_LONG_FEN,RESEND_SEVERAL,EQUIPMENT_ID_STR,CALENDAR,RECORDER,TO_EMAIL,TO_SCOPE) values('$M_NAME','$M_TOPIC','$M_DESC','$M_PROPOSER','$M_REQUEST_TIME','$COPY_TO_ID','$M_START','$M_END','$M_ROOM','$M_MANAGER','$M_ATTENDEE_OUT','$SMS_REMIND','$SMS2_REMIND','$ATTACHMENT_ID','$ATTACHMENT_NAME','$TO_ID','$PRIV_ID','$SECRET_TO_ID','$RESEND_LONG','$RESEND_LONG_FEN','$RESEND_SEVERAL','$EQUIPMENT_ID_STR','$CALENDAR','$RECORDER_ID','$TO_EMAIL','$TO_SCOPE')";
    exequery(TD::conn(),$query);

    $M_ID = mysql_insert_id();
}
else
{
    $condition = 0;
    //修改  先删除日程再修改会议
    $query_r  = "SELECT * FROM meeting WHERE M_ID='$M_ID'";
    $cursor_r = exequery(TD::conn(),$query_r);
    if($ROW=mysql_fetch_array($cursor_r))
    {
        $M_START_OLD            = $ROW['M_START'];
        $M_START_OLDS           = $ROW['M_START'];
        $M_END_OLD              = $ROW['M_END'];
        $M_NAME_OLD             = $ROW["M_NAME"];
        $M_ATTENDEE_OLD         = $ROW["M_ATTENDEE"];
        $OP_M_STATUS            = $ROW["M_STATUS"];
        $RESEND_LONG_OLD        = $ROW["RESEND_LONG"];
        $RESEND_LONG_FEN_OLD    = $ROW["RESEND_LONG_FEN"];
    }

    $M_START_OLD = strtotime($M_START_OLD);
    $M_END_OLD   = strtotime($M_END_OLD);

    $query_r_o  = "SELECT MR_NAME FROM meeting_room WHERE MR_ID='$M_ROOM'";
    $cursor_r_o = exequery(TD::conn(),$query_r_o);
    $ROW_O=mysql_fetch_array($cursor_r_o);
    $ROOM_O = $ROW_O['MR_NAME'];

    $query_r_n  = "SELECT MR_NAME FROM meeting_room WHERE MR_ID='$M_ROOM_new'";
    $cursor_r_n = exequery(TD::conn(),$query_r_n);
    $ROW_N=mysql_fetch_array($cursor_r_n);
    $ROOM_N = $ROW_N['MR_NAME'];
    if($ROOM_O != $ROOM_N)
    {
        $condition = 1;
        $M_ROOM = $M_ROOM_new;
    }
//     $CALENDER_CONTENT =_("会议").$M_ID;
//     $query2="SELECT CAL_ID FROM CALENDAR WHERE CONTENT like '$CALENDER_CONTENT%'";
//     $cursor2=exequery(TD::conn(),$query2);
//     while($ROW2=mysql_fetch_array($cursor2))
//     {
//         $CAL_ID=$ROW2["CAL_ID"];
//         $query="DELETE FROM CALENDAR WHERE CAL_ID='$CAL_ID'";
//         exequery(TD::conn(),$query);
//     }

    if(($COPY_TO_ID != $M_ATTENDEE_OLD && $COPY_TO_ID!="") || $RESEND_LONG_OLD != $RESEND_LONG || $RESEND_LONG_FEN_OLD != $RESEND_LONG_FEN)
    {
        $condition = 1;
    }
    //删除关联日程
    $query="DELETE FROM CALENDAR WHERE M_ID='$M_ID' and FROM_MODULE='2'";
    exequery(TD::conn(),$query);
    //修改会议
    $query="UPDATE meeting set SECRET_TO_ID='$SECRET_TO_ID',PRIV_ID='$PRIV_ID',TO_ID='$TO_ID',M_NAME='$M_NAME',M_TOPIC='$M_TOPIC',M_DESC='$M_DESC',M_REQUEST_TIME='$M_REQUEST_TIME',M_ATTENDEE='$COPY_TO_ID',M_START='$M_START',M_END='$M_END',M_ROOM='$M_ROOM',M_MANAGER='$M_MANAGER',M_ATTENDEE_OUT='$M_ATTENDEE_OUT',SMS_REMIND='$SMS_REMIND',SMS2_REMIND='$SMS2_REMIND',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',RESEND_SEVERAL='$RESEND_SEVERAL',RESEND_LONG='$RESEND_LONG',RESEND_LONG_FEN='$RESEND_LONG_FEN',EQUIPMENT_ID_STR='$EQUIPMENT_ID_STR',CALENDAR='$CALENDAR',RECORDER='$RECORDER_ID',TO_EMAIL='$TO_EMAIL',M_STATUS='$OP_M_STATUS',TO_SCOPE='$TO_SCOPE' WHERE M_ID='$M_ID'";
    exequery(TD::conn(),$query);
    $COPY_TO_ID  = td_trim($COPY_TO_ID);
    $M_START_TEM = strtotime($M_START);
    $M_END_TEM   = strtotime($M_END);

    $REMIND_URL1 = "1:meeting/query/meeting_detail.php?M_ID=".$M_ID;


    // 时间改变发送提醒信息
    if($OP_M_STATUS==1 && ($M_START_OLD!=$M_START_TEM || $M_END_OLD!=$M_END_TEM || $condition==1))
    {
        //删除原有的循环提示
        $M_START2   = substr($M_START_OLDS,0,-3);
        $SRARCH_STR = sprintf(_("%s在%s开会，请按时参加"),$M_START2,$ROOM_O);

        $query1  = "SELECT BODY_ID FROM SMS_BODY WHERE CONTENT like '%$SRARCH_STR%'";
        $cursor1 = exequery(TD::conn(),$query1);
        while($ROW1=mysql_fetch_array($cursor1))
        {
            $BODY_ID = $ROW1["BODY_ID"];

            $query2  = "delete FROM SMS_BODY WHERE BODY_ID='$BODY_ID'";
            exequery(TD::conn(),$query2);
            $query2  = "delete FROM SMS WHERE BODY_ID='$BODY_ID'";
            exequery(TD::conn(),$query2);
        }
        /*$M_ATTENDEES = td_trim($M_ATTENDEE1);
        if(($RESEND_LONG > 0 || $RESEND_LONG_FEN > 0 ) && $RESEND_SEVERAL > 0 && $M_ATTENDEES!="" && $SMS_REMIND==1)
        {
            $NUM=($RESEND_LONG*60+$RESEND_LONG_FEN)/$RESEND_SEVERAL;
            for($I=0;$I < $RESEND_SEVERAL;$I++)
            {
                $SEND_TIME = strtotime($M_START) - $RESEND_LONG*3600 - $RESEND_LONG_FEN*60 + $I*$NUM*60;
                $SEND_TIMES = date("Y-m-d H:i:s",$SEND_TIME);
                $M_START2_TEM = date("Y-m-d H:i",strtotime($M_START));
                $REMIND_URL2 = "1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
                $CONTENT = sprintf(_("%s在%s开会，请按时参加。"),$M_START2_TEM,$ROOM_N);
                send_sms($SEND_TIMES,$M_PROPOSER,$M_ATTENDEE1,"8_1",$CONTENT,$REMIND_URL2,$M_ID);
                if($SMS2_REMIND == 1)
                    send_mobile_sms_user($SEND_TIME,$M_PROPOSER,$M_ATTENDEE1,$CONTENT,"8_1");
            }
        }*/
        if($SMS_REMIND=="1")
        {
            send_sms("",$_SESSION["LOGIN_USER_ID"],$COPY_TO_ID,'8_1',_("会议：“").$M_NAME._("”的时间有变更，新的时间为：").$M_START._("至").$M_END,$REMIND_URL1,$M_ID);
        }

        if($SMS2_REMIND=="1")
        {
            send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$COPY_TO_ID,_("会议：“").$M_NAME._("”的时间有变更，新的时间为：").$M_START._("至").$M_END,'8_1');
        }
    }elseif($ROOM_O != $ROOM_N)
    {
        if($SMS_REMIND=="1")
        {
            send_sms("",$_SESSION["LOGIN_USER_ID"],$COPY_TO_ID,'8_1',_("会议：“").$M_NAME._("”会议地址有变化,新地址！“").$ROOM_O._("”更改至“").$ROOM_N."”",$REMIND_URL1,$M_ID);
        }
        if($SMS2_REMIND=="1")
        {
            send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$COPY_TO_ID,_("会议：“").$M_NAME._("”会议地址有变化,新地址！“").$ROOM_O._("”更改至“").$ROOM_N."”",'8_1');
        }
    }elseif($OP_M_STATUS==1  && $M_START_OLD==$M_START_TEM && $M_END_OLD==$M_END_TEM)
    {
        if($SMS_REMIND=="1")
        {
            send_sms("",$_SESSION["LOGIN_USER_ID"],$COPY_TO_ID,'8_1',_("会议：“").$M_NAME._("”信息有变更,请查看！"),$REMIND_URL1,$M_ID);
        }
        if($SMS2_REMIND=="1")
        {
            send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$COPY_TO_ID,_("会议：“").$M_NAME._("”信息有变更,请查看！"),'8_1');
        }
    }
    //会议室改变发送提醒
    if($FLAG==1)
    {//修改操作
        if(find_id($_SESSION['LOGIN_FUNC_STR'],"88"))
        {
            if($OP_M_STATUS==1)
                header("location: ../manage/checkup.php?M_ID=$M_ID&M_STATUS=1&IS_APPROVE=2&FLAG=1");
            else
                header("location: ../manage/checkup.php?M_ID=$M_ID&IS_APPROVE=2&FLAG=1");
        }else
        {
            if($OP_M_STATUS==1)
                header("location: ../apply/checkup1.php?M_ID=$M_ID&M_STATUS=1&IS_APPROVE=2&FLAG=1");
            else
                header("location: ../apply/checkup1.php?M_ID=$M_ID&IS_APPROVE=2&FLAG=1");
        }

    }
}
// 获取内部出席人姓名
if($COPY_TO_ID)
{
    $M_ATTENDEE_NAME = GetUserNameById($COPY_TO_ID);
}
$query  = "SELECT MR_NAME FROM meeting_room WHERE MR_ID='$M_ROOM'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $MR_NAME = $ROW["MR_NAME"];
}

if($EQUIPMENT_ID_STR!='')
{
    $EQUIPMENT_ID_STR       = td_trim($EQUIPMENT_ID_STR);
    $EQUIPMENT_ID_STR_ARRAY = explode(',',$EQUIPMENT_ID_STR);
    foreach((array)$EQUIPMENT_ID_STR_ARRAY as $value)
    {
        $query  = "SELECT EQUIPMENT_NAME FROM meeting_equipment WHERE EQUIPMENT_ID='$value'";
        $cursor = exequery(TD::conn(),$query);
        if($ROW = mysql_fetch_array($cursor))
        {
            $EQUIPMENT_NAME .= $ROW["EQUIPMENT_NAME"].",";
        }
    }
}
//会议管理员
$query = "SELECT MEETING_OPERATOR,MEETING_IS_APPROVE from meeting_rule";
$cursor= exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $MEETING_OPERATOR = $ROW['MEETING_OPERATOR'];
    $MEETING_IS_APPROVE = $ROW['MEETING_IS_APPROVE'];
}
if($MEETING_IS_APPROVE == 1)
{
   $M_MANAGER = $MEETING_OPERATOR.$M_MANAGER;
}



$EQUIPMENT_NAME = td_trim($EQUIPMENT_NAME);

$data_array=array("KEY"=>"$M_ID","field"=>"M_ID","USER_NAME"=>$_SESSION["LOGIN_USER_NAME"],"EQUIPMENT"=>$EQUIPMENT_NAME,"M_ATTENDEE_OUT"=>$M_ATTENDEE_OUT,"M_ATTENDEE"=>$M_ATTENDEE_NAME,"M_NAME"=>$M_NAME,"M_TOPIC"=>$M_TOPIC,"M_DESC"=>$M_DESC,"USER_ID"=>$_SESSION["LOGIN_USER_ID"],"M_START"=>$M_START,"M_END"=>$M_END,"M_ROOM"=>$MR_NAME,"ATTACHMENT_ID"=>$ATTACHMENT_ID,"ATTACHMENT_NAME"=>$ATTACHMENT_NAME,"MODULE_SRC"=>"meeting","MODULE_DESC"=>"workflow");
$config = array("module"=>"meeting_apply");
$status = 0;
run_hook($data_array,$config);
if($status==0)
{
    $query  = "SELECT USER_NAME FROM user WHERE USER_ID='$M_PROPOSER'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $M_PROPOSER_NAME = $ROW["USER_NAME"];
    }

    if($OP==0)
    {
        header("location:new.php?M_ID=$M_ID");
    }
    else
    {
        //申请人对会议 新增，修改    (会议管理员修改OP_M_STATUS=1)
        if($OP_M_STATUS!=1)
        {
            $REMIND_URL="1:meeting/manage";
            //发送事务提醒消息给会议室管理员   是

            if($SMS_REMIND1=="on")
            {
                if(!$IS_APPROVE)
                {
                    //不需要申请直接生成会议
                    if(($RESEND_LONG > 0 || $RESEND_LONG_FEN > 0 )&& $RESEND_SEVERAL > 0)
                    {
                        $NUM=($RESEND_LONG*60+$RESEND_LONG_FEN)/$RESEND_SEVERAL;
                        for($I=0;$I < $RESEND_SEVERAL;$I++)
                        {
                            $SEND_TIME = strtotime($M_START) - $RESEND_LONG*3600 - $RESEND_LONG_FEN*60 + $I*$NUM*60;
                            $SEND_TIMES = date("Y-m-d H:i:s",$SEND_TIME);

                            $REMIND_URL1 = "1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
                            $M_START = substr($M_START,0,16);
                            $CONTENT = sprintf(_("%s在%s开会，请按时参加。"),$M_START,$MR_NAME,$M_NAME);
                            send_sms($SEND_TIMES,$M_PROPOSER,$M_ATTENDEE1,"8_1",$CONTENT,$REMIND_URL1,$M_ID);
                        }
                    }
                    $REMIND_URL1 = "1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
                    $M_START = substr($M_START,0,16);
                    $CONTENT = sprintf(_("%s通知您于%s在%s开会，会议名称：%s，请按时参加。"),$M_PROPOSER_NAME,$M_START,$MR_NAME,$M_NAME);
                    if(mb_detect_encoding($CONTENT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
                        $CONTENT = stripslashes($CONTENT);
                    }
                    send_sms("",$M_PROPOSER,$M_ATTENDEE1,"8_1",$CONTENT,$REMIND_URL1,$M_ID);
                    $CONTENT2 = sprintf(_("%s向您提交会议申请，会议名称：%s，由于设置为不需要审核模式，所以直接视为已批准！"),$M_PROPOSER_NAME,$M_NAME);
                    if(mb_detect_encoding($CONTENT2,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
                        $CONTENT2 = stripslashes($CONTENT2);
                    }
                    if($SMS_REMIND) {
                        send_sms("", $M_PROPOSER, $M_MANAGER, 8, $CONTENT2, $REMIND_URL, "0");
                    }
                    /*$WX_OPTIONS = array(
                        "module" => "meeting",
                        "module_action" => "meeting.read",
                        "user" => $M_ATTENDEE1,
                        "description" =>strip_tags($M_DESC),
                        "content" =>$CONTENT,
                        "params" => array(
                            "meeting_id" => $M_ID
                        )
                       );
                    WXQY_MEETING($WX_OPTIONS);*/
                    $CONTENT1=sprintf(_("您的会议申请已被批准，会议名称：%s。"),$M_NAME);
                    send_sms("",$_SESSION["LOGIN_USER_ID"],$M_PROPOSER,"8_1",$M_PROPOSER_NAME.$CONTENT1,$REMIND_URL1,$M_ID);
                    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$COPY_TO_ID,$CONTENT1,'8_1');
                    //查看范围（部门)提醒
                    if($TO_SCOPE == '1' && ($SECRET_TO_ID != "" || $PRIV_ID != "" || $TO_ID != "")){
                        $USER_IDS = AllowUserIds($SECRET_TO_ID,$PRIV_ID,$TO_ID);
                        $USER_ID_REPETITION = explode(",",$USER_IDS);
                        array_pop($USER_ID_REPETITION);
                        $USER_ID_REPETITION = array_unique($USER_ID_REPETITION);
                        $USER_ID = implode(",",$USER_ID_REPETITION);
                        $REMIND_URL1="1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
                        $CONTENT = sprintf(_("%s在%s开会，会议名称：%s，请您查阅！"),$M_START,$MR_NAME,$M_NAME);
                        send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,"8_1",$M_PROPOSER_NAME.$CONTENT,$REMIND_URL1,$M_ID);
                        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$CONTENT,'8_1');
                    }
                    //电子邮件提醒 不需要审批时
                    if($TO_EMAIL){
                        $TIME    = date("Y-m-d H:i:s");
                        $SUBJECT = _("会议：").$M_NAME;
                        if(mb_detect_encoding($SUBJECT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
                            $SUBJECT = stripslashes($SUBJECT);
                        }
                        $CONTENT = sprintf(_("%s通知您于%s在%s开会，会议名称：%s"),$M_PROPOSER_NAME,$M_START,$MR_NAME,$M_NAME);
                        $CONTENT          = gbk_stripslashes($CONTENT);
                        $CONTENT_STRIP    = strip_tags($CONTENT); //这个是存储的内容已经过滤html标签了
                        $COMPRESS_CONTENT = bin2hex(gzcompress($CONTENT));
                        //存储内容、压缩内容存储 edit  2012-05-29.....
                        $CONTENT_SIZE          = strlen($CONTENT);
                        $CONTENT_SIZE1         = strlen($CONTENT_STRIP);
                        $COMPRESS_CONTENT_SIZE = strlen($COMPRESS_CONTENT);
                        if($CONTENT_SIZE<($CONTENT_SIZE1+$COMPRESS_CONTENT_SIZE)) //如果正文大于过滤掉html标签与压缩内容之合
                        {
                            $CONTENT_STRIP    = addslashes($CONTENT);
                            $COMPRESS_CONTENT = "''";
                        }
                        else
                        {
                            $CONTENT_STRIP    = addslashes($CONTENT_STRIP);
                            $COMPRESS_CONTENT = '0x'.$COMPRESS_CONTENT;
                        }
                        if(mb_detect_encoding($CONTENT_STRIP,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
                            $CONTENT_STRIP = stripslashes($CONTENT_STRIP);
                        }
                        $USER_ID    = td_trim($M_ATTENDEE1);
                        $query="INSERT INTO email_body (FROM_ID,TO_ID2,SUBJECT,CONTENT,SEND_FLAG,SEND_TIME,COMPRESS_CONTENT) values ('".$_SESSION["LOGIN_USER_ID"]."','$USER_ID','$SUBJECT','$CONTENT_STRIP','1','".strtotime($TIME)."',$COMPRESS_CONTENT)";
                        exequery(TD::conn(),$query);
                        $ROW_ID = mysql_insert_id();
                        $MY_ARRAY   = explode(",",$USER_ID);
                        $ARRAY_COUNT = sizeof($MY_ARRAY);
                        for($I=0;$I< $ARRAY_COUNT;$I++)
                        {
                            $query = "INSERT INTO email(TO_ID,READ_FLAG,DELETE_FLAG,BOX_ID,BODY_ID,RECEIPT) values ('$MY_ARRAY[$I]','0','0','0','$ROW_ID','0')";
                            exequery(TD::conn(),$query);
                            $ROW_EMAIL_ID = mysql_insert_id();
                            $REMIND_URL="email/inbox/read_email/read_email.php?BOX_ID=0&BTN_CLOSE=1&FROM=1&EMAIL_ID=".$ROW_EMAIL_ID."&BODY_ID=".$ROW_ID;
                            $SMS_CONTENT=_("请查收我的邮件！")."\n"._("主题：").csubstr($SUBJECT,0,100);
                            send_sms("",$_SESSION["LOGIN_USER_ID"],$MY_ARRAY[$I],"2",$SMS_CONTENT,$REMIND_URL,$ROW_EMAIL_ID);
                        }
                        if($TO_SCOPE == '1' && ($SECRET_TO_ID != "" || $PRIV_ID != "" || $TO_ID != "")){
                            $SUBJECT = _("会议查阅：").$M_NAME;
                            if(mb_detect_encoding($SUBJECT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
                                $SUBJECT = stripslashes($SUBJECT);
                            }
                            $CONTENT = sprintf(_("%s通知您于%s在%s开会，会议名称：%s，请您查阅！"),$M_PROPOSER_NAME,$M_START,$MR_NAME,$M_NAME);
                            $CONTENT          = gbk_stripslashes($CONTENT);
                            $CONTENT_STRIP    = strip_tags($CONTENT); //这个是存储的内容已经过滤html标签了
                            $COMPRESS_CONTENT = bin2hex(gzcompress($CONTENT));
                            //存储内容、压缩内容存储 edit  2012-05-29.....
                            $CONTENT_SIZE          = strlen($CONTENT);
                            $CONTENT_SIZE1         = strlen($CONTENT_STRIP);
                            $COMPRESS_CONTENT_SIZE = strlen($COMPRESS_CONTENT);
                            if($CONTENT_SIZE<($CONTENT_SIZE1+$COMPRESS_CONTENT_SIZE)) //如果正文大于过滤掉html标签与压缩内容之合
                            {
                                $CONTENT_STRIP    = addslashes($CONTENT);
                                $COMPRESS_CONTENT = "''";
                            }
                            else
                            {
                                $CONTENT_STRIP    = addslashes($CONTENT_STRIP);
                                $COMPRESS_CONTENT = '0x'.$COMPRESS_CONTENT;
                            }
                            if(mb_detect_encoding($CONTENT_STRIP,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
                                $CONTENT_STRIP = stripslashes($CONTENT_STRIP);
                            }
                            $query = "INSERT INTO email_body (FROM_ID,TO_ID2,SUBJECT,CONTENT,SEND_FLAG,SEND_TIME,COMPRESS_CONTENT) values ('".$_SESSION["LOGIN_USER_ID"]."','$USER_IDS','$SUBJECT','$CONTENT_STRIP','1','".strtotime($TIME)."',$COMPRESS_CONTENT)";
                            exequery(TD::conn(),$query);
                            $ROW_ID = mysql_insert_id();
                            $USER_ID_REPETITION = explode(",",$USER_IDS);
                            array_pop($USER_ID_REPETITION);
                            $USER_ID_REPETITION = array_unique($USER_ID_REPETITION);
                            $ARRAY_COUNT1 = sizeof($USER_ID_REPETITION);
                            for($I=0;$I< $ARRAY_COUNT1;$I++)
                            {
                                $query = "INSERT INTO email(TO_ID,READ_FLAG,DELETE_FLAG,BOX_ID,BODY_ID,RECEIPT) values ('$USER_ID_REPETITION[$I]','0','0','0','$ROW_ID','0')";
                                exequery(TD::conn(),$query);
                                $ROW_EMAIL_ID = mysql_insert_id();
                                $REMIND_URL="email/inbox/read_email/read_email.php?BOX_ID=0&BTN_CLOSE=1&FROM=1&EMAIL_ID=".$ROW_EMAIL_ID."&BODY_ID=".$ROW_ID;
                                $SMS_CONTENT=_("请查收我的邮件！")."\n"._("主题：").csubstr($SUBJECT,0,100);
                                send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID_REPETITION[$I],"2",$SMS_CONTENT,$REMIND_URL,$ROW_EMAIL_ID);
                            }
                        }
                    }
                }
                elseif($NEW == 1 && $M_MANAGER != $_SESSION["LOGIN_USER_ID"])
                {//需要申请 发邮件给管理员
                
                    $CONTENT = sprintf(_("%s向您提交会议申请，会议名称：%s，请批示！"),$M_PROPOSER_NAME,$M_NAME);
                    send_sms("",$M_PROPOSER,$M_MANAGER,8,$CONTENT,$REMIND_URL,"0");
                }
            }
            //发送手机短信给会议室管理员   是
            if($SMS2_REMIND1=="on")
            {
                $CONTENT  = sprintf(_("%s向您提交会议申请，会议名称：%s，请批示！"),$M_PROPOSER_NAME,$M_NAME);
                $CONTENT2 = sprintf(_("%s向您提交会议申请，会议名称：%s，由于设置为不需要审核模式，所以直接视为已批准！"),$M_PROPOSER_NAME,$M_NAME);

                if(!$IS_APPROVE)
                {
                    send_mobile_sms_user("",$M_PROPOSER,$M_MANAGER,$CONTENT2,8);
                }
                else
                {
                    send_mobile_sms_user("",$M_PROPOSER,$M_MANAGER,$CONTENT,8);
                }
            }
        }elseif($NEW == 2 && $M_MANAGER != $_SESSION["LOGIN_USER_ID"])
        {
            //需要申请 发邮件给管理员
            $CONTENT = sprintf(_("%s向您发出更新提醒，会议名称：%s，请查看！"),$M_PROPOSER_NAME,$M_NAME);
            send_sms("",$M_PROPOSER,$M_MANAGER,"8_1",$CONTENT,$REMIND_URL,$M_ID);
        }

        if(!$IS_APPROVE)
        {//不需要申请直接生成日程，默认已经批准
            header("location: ../apply/checkup.php?M_ID=$M_ID&M_STATUS=1&IS_APPROVE=2");
        }

        Message(_("提示"),_("会议申请保存成功！"));
        Button_Back();
    }
}
?>

</body>
</html>
