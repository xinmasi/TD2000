<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
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
//查询指定时间段内会议室事被使用的会议事件ID串
function check_room($M_ID,$M_ROOM,$M_START,$M_END)
{
    $query = "select M_START,M_END,M_ID from MEETING where M_ID!='$M_ID' and M_ROOM='$M_ROOM' and (M_STATUS=0 or M_STATUS=1 or M_STATUS=2)";
    $cursor = exequery(TD::conn(),$query);
    $COUNT = 0;
    while($ROW = mysql_fetch_array($cursor))
    {
        $M_START1   = $ROW["M_START"];
        $M_END1     = $ROW["M_END"];
        if(($M_START1 >= $M_START && $M_END1 <= $M_END) || ($M_START1 < $M_START && $M_END1 > $M_START) || ($M_START1 < $M_END && $M_END1 > $M_END) || ($M_START1 < $M_START && $M_END1 > $M_END))
        {
            $COUNT++;
            $M_IDD = $M_IDD.$ROW["M_ID"].",";
        }
    }

    $M_ID_STR = $M_IDD;
    if($COUNT >= 1)
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
<style>
#return
{
    position: absolute;/*绝对定位*/
    left:700px;/*在原来的位置向右移动*/
    top:130px;/*在原来的位置向下移动*/
}
#specifics
{
    position: absolute;/*绝对定位*/
    left:600px;/*在原来的位置向右移动*/
    top:150px;/*在原来的位置向下移动*/
}
</style>
<body class="bodycolor">

<?
$SYS_PARA_ARRAY = get_sys_para("MEETING_IS_APPROVE");

$query = "select MEETING_IS_APPROVE from MEETING_RULE";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $IS_APPROVE = $ROW["MEETING_IS_APPROVE"];
}

if($RD = "1")
{
    $CYCLE="1";
    $query="select max(CYCLE_NO) as COUNT from MEETING";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $CYCLE_NO=$ROW["COUNT"];
    }

    $CYCLE_NO++;
}

$SMS_REMIND = ($SMS_REMIND == "on") ? "1" : "0";
$SMS2_REMIND = ($SMS2_REMIND == "on") ? "1" : "0";

//----------- 合法性校验 ---------
if($M_START_DATE != "")
{
    $TIME_OK = is_date($M_START_DATE);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("开始时间格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($M_END_DATE != "")
{
    $TIME_OK = is_date($M_END_DATE);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("结束时间格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($M_START_TIME != "")
{
    $TIME_OK = is_time($M_START_TIME);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("开始时间格式不对，应形如 09:30:00"));
        Button_Back();
        exit;
    }
}

if($M_END_TIME != "")
{
    $TIME_OK = is_time($M_END_TIME);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("结束时间格式不对，应形如 09:30:00"));
        Button_Back();
        exit;
    }
}

$M_ROOM = $_POST['M_ROOM'];

$query = "select APPLY_WEEKDAYS from MEETING_ROOM where MR_ID='$M_ROOM'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $APPLY_WEEKDAYS = $ROW["APPLY_WEEKDAYS"];
}

if($APPLY_WEEKDAYS == "")
{
    $APPLY_WEEKDAYS = "1,2,3,4,5";
}

$check_week = 1;

if($M_START_DATE == $M_END_DATE)
{
    $week = date("w",strtotime($M_START_DATE));
    $week_tem = "W".($week == 0 ? 7 : $week);
    if(!find_id($week,$APPLY_WEEKDAYS) && $$week_tem == 1)
    {
        $check_week=0;
    }
}
else
{
    for($J=$M_START_DATE;$J<=$M_END_DATE;$J=date("Y-m-d",strtotime($J)+24*3600))
    {
        $APPLY_WEEKDAYS;
        $week = date("w",strtotime($J));
        $week_tem = "W".($week == 0 ? 7 : $week);
        if(find_id($week,$APPLY_WEEKDAYS) && $$week_tem == 0)
        {
            $check_week = 0;
            break;
        }
    }
}

if($check_week == 0) //不可申请的时间。
{
    Message(_("错误"),_("此时间段包含不允许申请会议的时段！"));
    Button_Back();
    exit;
}

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

check_room("",$M_ROOM,$M_START,$M_END);

//--------- 上传附件 ----------
if(count($_FILES) > 1)
{
    $ATTACHMENTS = upload();
    $M_DESC = ReplaceImageSrc($M_DESC, $ATTACHMENTS);
    $ATTACHMENT_ID = $ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME = $ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
    $ATTACHMENT_ID = $ATTACHMENT_ID_OLD;
    $ATTACHMENT_NAME = $ATTACHMENT_NAME_OLD;
}

$ATTACHMENT_ID .= copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME .= $ATTACH_NAME;

$TEMP = strtotime($M_END_DATE)-strtotime($M_START_DATE);
$TEMP = $TEMP/(60*60*24)+1;
for($i = 1; $i < $TEMP+1; $i++)
{
    $TEMP2 = strtotime($M_START_DATE)+60*60*24*$i;
    $TEMP2 = gmdate('Y-m-d',$TEMP2);
    if(date('w',strtotime($TEMP2)) == '1' && $W1 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '2' && $W2 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '3' && $W3 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '4' && $W4 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '5' && $W5 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '6' && $W6 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '0' && $W7 == ""  )
    {
        continue;
    }

    $M_START = $TEMP2." ".$M_START_TIME;
    $M_END = $TEMP2." ".$M_END_TIME;

    $SS = substr(check_room("",$M_ROOM,$M_START,$M_END), 0, 1);
    if(is_number($SS))
    {
        $M_ATTENDEE=$COPY_TO_ID;

        setcookie("MEETING_M_NAME", "$M_NAME");
        setcookie("MEETING_M_TOPIC", "$M_TOPIC");
        setcookie("MEETING_M_ATTENDEE_OUT", "$M_ATTENDEE_OUT");
        setcookie("MEETING_M_ATTENDEE", "$M_ATTENDEE");
        setcookie("M_ROOM", "$M_ROOM");
        setcookie("MEETING_M_DESC", "$M_DESC");
        setcookie("MEETING_SECRET_TO_ID", "$SECRET_TO_ID");
        setcookie("MEETING_PRIV_ID", "$PRIV_ID");
        setcookie("MEETING_TO_ID", "$TO_ID");

        Message(_("提示"),$M_START._("有会议冲突"));
        ?>
        <div id='specifics'>
            <center>
                <input type="button" class="BigButtonA" value="<?=_("详情")?>" onClick="window.open('../manage/conflict_detail.php?M_ID=<?=td_trim(check_room("",$M_ROOM,$M_START,$M_END,0,1))?>','','height=350,width=450,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=150,resizable=yes');">
            </center>
        </div>
        <div id='return'>
            <?Button_Back();exit;?>
        </div>
        <?
    }
}

$approve_m_id_str = '';
for($j = 1; $j < $TEMP+1; $j++)
{
    $TEMP2=strtotime($M_START_DATE)+60*60*24*$j;
    $TEMP2 = gmdate('Y-m-d',$TEMP2);
    if(date('w',strtotime($TEMP2)) == '1' && $W1 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '2' && $W2 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '3' && $W3 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '4' && $W4 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '5' && $W5 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '6' && $W6 == ""  )
    {
        continue;
    }
    if(date('w',strtotime($TEMP2)) == '0' && $W7 == ""  )
    {
        continue;
    }
    $M_START_ARRAY[] = $M_START = $TEMP2." ".$M_START_TIME;
    $M_END = $TEMP2." ".$M_END_TIME;

    $M_ATTENDEE = $COPY_TO_ID;

    $EQUIPMENT_ID_STR = "";
    for($I = 1; $I <= 500; $I++)
    {
        $TMP = "SB_".$I;
        if($$TMP!="")
        {
            $EQUIPMENT_ID_STR.= $$TMP.",";
        }
    }
    if($RECORDER_ID=="")
    {
        $RECORDER_ID=$RECORDER_NAME;
    }

    if($M_ID=="")
    {
        $query="insert into MEETING(M_NAME,M_TOPIC,M_DESC,M_PROPOSER,M_REQUEST_TIME,M_ATTENDEE,M_START,M_END,M_ROOM,M_MANAGER,M_ATTENDEE_OUT,SMS_REMIND,SMS2_REMIND,ATTACHMENT_ID,ATTACHMENT_NAME,TO_ID,PRIV_ID,SECRET_TO_ID,RESEND_LONG,RESEND_LONG_FEN,RESEND_SEVERAL,EQUIPMENT_ID_STR,CALENDAR,CYCLE,CYCLE_NO,RECORDER,TO_EMAIL,TO_SCOPE) values('$M_NAME','$M_TOPIC','$M_DESC','$M_PROPOSER','$M_REQUEST_TIME','$COPY_TO_ID','$M_START','$M_END','$M_ROOM','$M_MANAGER','$M_ATTENDEE_OUT','$SMS_REMIND','$SMS2_REMIND','$ATTACHMENT_ID','$ATTACHMENT_NAME','$TO_ID','$PRIV_ID','$SECRET_TO_ID','$RESEND_LONG','.$RESEND_LONG_FEN.','$RESEND_SEVERAL','$EQUIPMENT_ID_STR','$CALENDAR','$CYCLE','$CYCLE_NO','$RECORDER_ID','$TO_EMAIL','$TO_SCOPE')";
        exequery(TD::conn(),$query);

        $approve_m_id_str .= mysql_insert_id().',';


    }
    else
    {
        $query="update MEETING set SECRET_TO_ID='$SECRET_TO_ID',PRIV_ID='$PRIV_ID',TO_ID='$TO_ID',M_NAME='$M_NAME',M_TOPIC='$M_TOPIC',M_DESC='$M_DESC',M_REQUEST_TIME='$M_REQUEST_TIME',M_ATTENDEE='$COPY_TO_ID',M_START='$M_START',M_END='$M_END',M_ROOM='$M_ROOM',M_MANAGER='$M_MANAGER',M_ATTENDEE_OUT='$M_ATTENDEE_OUT',SMS_REMIND='$SMS_REMIND',SMS2_REMIND='$SMS2_REMIND',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',RESEND_SEVERAL='$RESEND_SEVERAL',RESEND_LONG='$RESEND_LONG',RESEND_LONG_FEN='$RESEND_LONG_FEN',EQUIPMENT_ID_STR='$EQUIPMENT_ID_STR',CALENDAR='$CALENDAR',CYCLE='$CYCLE',RECORDER='$RECORDER_ID',TO_EMAIL='$TO_EMAIL',TO_SCOPE='$TO_SCOPE' where M_ID='$M_ID'";
        exequery(TD::conn(),$query);

        $approve_m_id_str .= $M_ID.',';
    }
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

if($M_ID == "")
{
    $M_ID = mysql_insert_id();
}

$query = "select USER_NAME from USER where USER_ID='$M_PROPOSER'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $M_PROPOSER_NAME=$ROW["USER_NAME"];
}

$query = "SELECT MR_NAME from MEETING_ROOM where MR_ID='$M_ROOM'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $MR_NAME = $ROW["MR_NAME"];
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

if($OP == 0)
{
    header("location: new.php?M_ID=$M_ID");
}
else
{
    $REMIND_URL="1:meeting/manage/manage1.php?M_ID=".$M_ID;
    $REMIND_URL1="1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
    if($M_MANAGER!=""&&$SMS_REMIND1=="on")
    {
        if(!$IS_APPROVE)
        {
            if(($RESEND_LONG > 0 || $RESEND_LONG_FEN > 0 )&& $RESEND_SEVERAL > 0)
            {
                $NUM=($RESEND_LONG*60+$RESEND_LONG_FEN)/$RESEND_SEVERAL;
                for($I=0;$I < $RESEND_SEVERAL;$I++)
                {
                    foreach($M_START_ARRAY as $M_STARTS)
                    {
                        $SEND_TIME = strtotime($M_STARTS) - $RESEND_LONG*3600 - $RESEND_LONG_FEN*60 + $I*$NUM*60;
                        $SEND_TIMES = date("Y-m-d H:i:s",$SEND_TIME);
                        $query = "SELECT M_ID FROM meeting WHERE M_START ='$M_STARTS'";
                        $cursor = exequery(TD::conn(),$query);
                        if($ROW = mysql_fetch_array($cursor))
                        {
                            $M_ID = $ROW["M_ID"];
                        }
                        $REMIND_URL_NO_PASS = '1:meeting/query/meeting_detail.php?M_ID='.$M_ID;
                        $M_START = substr($M_STARTS,0,16);
                        $CONTENT = sprintf(_("%s通知您于%s在%s开会，请按时参加。"),GetUserNameByUserId($_SESSION["LOGIN_USER_ID"]),$M_START,$MR_NAME,$M_NAME);
                        send_sms($SEND_TIMES,$M_PROPOSER,$COPY_TO_ID,"8_1",$CONTENT,$REMIND_URL_NO_PASS,$M_ID);
                    }
                }
            }
            $M_START=date("Y-m-d H:i",strtotime($M_START));
            $CONTENT2 = sprintf(_("%s向您提交周期性会议申请，会议名称：%s，由于设置为不需要审核模式，所以直接视为已批准！"),$M_PROPOSER_NAME,$M_NAME);
            send_sms("",$M_PROPOSER,$M_MANAGER,8,$CONTENT2,$REMIND_URL,$M_ID);
        }
        else
        {
            $CONTENT = sprintf(_("%s向您提交周期性会议申请，会议名称：%s，请批示！"),$M_PROPOSER_NAME,$M_NAME);

            send_sms("",$M_PROPOSER,$M_MANAGER,8,$CONTENT,$REMIND_URL,$M_ID);
        }
    }

    if($SMS2_REMIND1=="on")
    {
        if(!$IS_APPROVE)
        {
            $CONTENT = sprintf(_("%s向您提交周期性会议申请，会议名称：%s，由于设置为不需要审核模式，所以直接视为已批准！"),$M_PROPOSER_NAME,$M_NAME);

            send_mobile_sms_user("",$M_PROPOSER,$M_MANAGER,$CONTENT,8);
        }
        else
        {
            $CONTENT = sprintf(_("%s向您提交周期性会议申请，会议名称：%s，请批示！"),$M_PROPOSER_NAME,$M_NAME);

            send_mobile_sms_user("",$M_PROPOSER,$M_MANAGER,$CONTENT,8);
        }
    }

    if(!$IS_APPROVE)
    {
        $CHECK_STR   = td_trim($approve_m_id_str);
        $CHECK_ARRAY = explode(",",$CHECK_STR);
        $COUNT1      = 0;
        foreach($CHECK_ARRAY as $key=> $value)
        {
            $M_ID   = $value;
            $query  = "select * from MEETING where M_ID='$M_ID'";
            $cursor = exequery(TD::conn(),$query);
            if($ROW = mysql_fetch_array($cursor))
            {
                $M_ROOM     = $ROW["M_ROOM"];
                $M_START    = $ROW["M_START"];
                $M_END      = $ROW["M_END"];
                $M_NAME     = $ROW["M_NAME"];
            }
            $REMIND_URL     = "1:meeting/manage/manage1.php?M_ID=".$M_ID;
            $REMIND_URL1    = "1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
            $SS = substr(check_room($M_ID,$M_ROOM,$M_START,$M_END), 0, 1);
            if(!is_number($SS)){
                $COUNT1++;
                $CONTENT1=sprintf(_("您 %s的会议申请已被批准"),$M_START);
                send_sms("",$_SESSION["LOGIN_USER_ID"],$M_PROPOSER,"8_1",$M_PROPOSER_NAME.$CONTENT1,$REMIND_URL1,$M_ID);
                send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$COPY_TO_ID,$CONTENT1,'8_1');
                if($SMS_REMIND){
                    $CONTENT = sprintf(_("%s通知您于%s在%s开会，会议名称：%s，请按时参加。"),td_trim(GetUserNameById($M_PROPOSER)),$M_START,$MR_NAME,$M_NAME);
                    send_sms("",$M_PROPOSER,$COPY_TO_ID,"8_1",$CONTENT,$REMIND_URL1,$M_ID);
                }
                //查看范围（部门)提醒
                if($TO_SCOPE == '1' && ($SECRET_TO_ID != "" || $PRIV_ID != "" || $TO_ID != "")){
                    $USER_IDS = AllowUserIds($SECRET_TO_ID,$PRIV_ID,$TO_ID);
                    $USER_ID_REPETITION = explode(",",$USER_IDS);
                    array_pop($USER_ID_REPETITION);
                    $USER_ID_REPETITION = array_unique($USER_ID_REPETITION);
                    $USER_ID = implode(",",$USER_ID_REPETITION);
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
                    $CONTENT          = sprintf(_("%s通知您于%s在%s开会，会议名称：%s"),$M_PROPOSER_NAME,$M_START,$MR_NAME,$M_NAME);
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
                    $query = "INSERT INTO email_body (FROM_ID,TO_ID2,SUBJECT,CONTENT,SEND_FLAG,SEND_TIME,COMPRESS_CONTENT) values ('".$_SESSION["LOGIN_USER_ID"]."','$M_ATTENDEE','$SUBJECT','$CONTENT_STRIP','1','".strtotime($TIME)."',$COMPRESS_CONTENT)";
                    exequery(TD::conn(),$query);
                    $ROW_ID      = mysql_insert_id();
                    $MY_ARRAY    = explode(",",$M_ATTENDEE);
                    $ARRAY_COUNT = sizeof($MY_ARRAY);
                    for($I=0;$I< $ARRAY_COUNT;$I++)
                    {
                        $query = "INSERT INTO email(TO_ID,READ_FLAG,DELETE_FLAG,BOX_ID,BODY_ID,RECEIPT) values ('$MY_ARRAY[$I]','0','0','0','$ROW_ID','0')";
                        exequery(TD::conn(),$query);
                        $ROW_EMAIL_ID = mysql_insert_id();
                        $REMIND_URL     = "email/inbox/read_email/read_email.php?BOX_ID=0&BTN_CLOSE=1&FROM=1&EMAIL_ID=".$ROW_EMAIL_ID."&BODY_ID=".$ROW_ID;
                        $SMS_CONTENT    = _("请查收我的邮件！")."\n"._("主题：").csubstr($SUBJECT,0,100);
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
                        $USER_IDS = AllowUserIds($SECRET_TO_ID,$PRIV_ID,$TO_ID);
                        $query = "INSERT INTO email_body (FROM_ID,TO_ID2,SUBJECT,CONTENT,SEND_FLAG,SEND_TIME,COMPRESS_CONTENT) values ('".$_SESSION["LOGIN_USER_ID"]."','$USER_IDS','$SUBJECT','$CONTENT_STRIP','1','".strtotime($TIME)."',$COMPRESS_CONTENT)";
                        exequery(TD::conn(),$query);
                        $ROW_ID = mysql_insert_id();
                        $USER_IDS    = td_trim($USER_ID);
                        $MY_ARRAY    = explode(",",$USER_IDS);
                        $MY_ARRAY    = array_unique($MY_ARRAY);
                        $MY_ARRAY    = array_filter($MY_ARRAY);
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
                    }
                }
            }
        }
        if($COUNT1>0)
        {
            $REMIND_URL2 = "1:calendar/arrange/";
            $CONTENT     = sprintf(_("参加周期性会议，会议名称：%s，详情查看个人日程安排"), $M_NAME);
            if(($SMS_REMIND=="1" || find_id($SMS_REMIND1,"8_1")) && $M_ATTENDEE!="")
            {
                send_sms("",$M_PROPOSER,$M_ATTENDEE,"8_1",$CONTENT,$REMIND_URL2);
            }
            if(($SMS2_REMIND=="1" || find_id($SMS2_REMIND1,8)) && $M_ATTENDEE!="")
            {
                send_mobile_sms_user("",$M_PROPOSER,$M_ATTENDEE,$M_PROPOSER_NAME.$CONTENT,8);
            }
        }
        header("location: ../apply/checkup_cycle.php?CHECK_STR=$approve_m_id_str");
    }

    Message(_("提示"),_("会议申请保存成功！"));
    Button_Back();
}
?>

</body>
</html>
