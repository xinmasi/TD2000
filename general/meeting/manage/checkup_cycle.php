<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

function check_room($M_ID,$M_ROOM,$M_START,$M_END)
{
    $query="select * from MEETING where M_ID!='$M_ID' and M_ROOM='$M_ROOM' and (M_STATUS=1 or M_STATUS=2)";
    $cursor=exequery(TD::conn(),$query);
    $COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $M_START1=$ROW["M_START"];
        $M_END1=$ROW["M_END"];
        if(($M_START1>=$M_START and $M_END1<=$M_END) or ($M_START1<$M_START and $M_END1>$M_START) or ($M_START1<$M_END and $M_END1>$M_END) or ($M_START1<$M_START and $M_END1>$M_END))
        {
            $COUNT++;
            $M_IDD=$M_IDD.$ROW["M_ID"].",";
        }
    }

    $M_ID=$M_IDD;
    if($COUNT>=1)
    {
        return $M_ID;
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
$M_STATUS=1;
$CHECK_STR= td_trim($CHECK_STR);
$CHECK_ARRAY = explode(",",$CHECK_STR);
$COUNT1=0;
foreach($CHECK_ARRAY as $key=> $value)
{
    $M_ID = $value;
    $USER_IDS = "";

    $query="select M_START,M_END,M_STATUS,M_ROOM,CALENDAR from MEETING where M_ID='$M_ID'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $M_STATUS1=$ROW["M_STATUS"];
        $M_ROOM=$ROW["M_ROOM"];
        $M_START=$ROW["M_START"];
        $M_END=$ROW["M_END"];
        $CALENDAR=$ROW["CALENDAR"];
    }

    $SS=substr(check_room($M_ID,$M_ROOM,$M_START,$M_END), 0, 1);
    if(!is_number($SS))
    {
        $COUNT1++;
        $query="update MEETING set M_STATUS='$M_STATUS' where M_ID='$M_ID'";
        exequery(TD::conn(),$query);

        $query="select * from MEETING where M_ID='$M_ID'";
        $cursor=exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $M_PROPOSER      = $ROW["M_PROPOSER"];
            $M_ATTENDEE      = $ROW["M_ATTENDEE"];
            $SMS_REMIND      = $ROW["SMS_REMIND"];
            $SMS2_REMIND     = $ROW["SMS2_REMIND"];
            $M_NAME          = $ROW["M_NAME"];
            $M_ROOM          = $ROW["M_ROOM"];
            $TO_EMAIL        = $ROW["TO_EMAIL"];//获取邮件发送设置 发送1
            $M_START2        = $M_START = $ROW["M_START"];
            $M_END           = $ROW["M_END"];
            $RESEND_LONG     = $ROW["RESEND_LONG"];
            $RESEND_LONG_FEN = $ROW["RESEND_LONG_FEN"];
            $RESEND_SEVERAL  = $ROW["RESEND_SEVERAL"];
            $SECRET_TO_ID    = $ROW['SECRET_TO_ID']; //查看人员
            $PRIV_ID         = $ROW['PRIV_ID']; //查看角色
            $TO_ID           = $ROW['TO_ID']; //查看部门
            $TO_SCOPE        = $ROW['TO_SCOPE'];


            if($RESEND_SEVERAL > 4)
            {
                $RESEND_SEVERAL = 4;
            }

            $query="select MR_NAME from MEETING_ROOM where MR_ID='$M_ROOM'";
            $cursor=exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $MR_NAME=$ROW["MR_NAME"];
            }

            $query="select USER_NAME from USER where USER_ID='$M_PROPOSER'";
            $cursor=exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $M_PROPOSER_NAME=$ROW["USER_NAME"];
            }
        }
        if($TO_SCOPE == '1' && ($SECRET_TO_ID != "" || $PRIV_ID != "" || $TO_ID != ""))
        {
            if($PRIV_ID != "")
            {
                $PRIV_NAMES = td_trim(GetPrivNameById($PRIV_ID));
                $query="select USER_ID from user where find_in_set(USER_PRIV_NAME,'$PRIV_NAMES')";
                $cursor = exequery(TD::conn(),$query);
                while($ROW = mysql_fetch_array($cursor))
                {
                    $USER_IDS.= $ROW["USER_ID"].',';
                }
                $PRIV_ID_ARRAY = explode(",",$PRIV_ID);
                array_pop($PRIV_ID_ARRAY);
                foreach($PRIV_ID_ARRAY as $V)
                {
                    $query="select USER_ID from user where USER_PRIV_OTHER like '%$V%'";
                    $cursor = exequery(TD::conn(),$query);
                    while($ROW = mysql_fetch_array($cursor))
                    {
                        $USER_IDS.= $ROW["USER_ID"].',';
                    }
                }
            }

            if($TO_ID != ""){
                $TO_ID = td_trim($TO_ID);
                $query="select USER_ID from user where find_in_set(DEPT_ID,'$TO_ID')";
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
                $query="select USER_ID from user";
                $cursor = exequery(TD::conn(),$query);
                while($ROW = mysql_fetch_array($cursor))
                {
                    $USER_IDS.= $ROW["USER_ID"].',';
                }
            }

            $USER_ID_REPETITION = explode(",",$USER_IDS);
            array_pop($USER_ID_REPETITION);
            $USER_ID_REPETITION = array_unique($USER_ID_REPETITION);
            $USER_ID = implode(",",$USER_ID_REPETITION);

            $REMIND_URL1="1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
            $CONTENT = sprintf(_("%s在%s开会，会议名称：%s，请您查阅！"),$M_START,$MR_NAME,$M_NAME);
            send_sms("",$M_PROPOSER,$USER_ID,"8_1",$CONTENT,$REMIND_URL1,$M_ID);
            send_mobile_sms_user("",$M_PROPOSER,$USER_ID,$CONTENT,'8_1');

            $query="select SECRET_TO_ID,PRIV_ID,TO_ID,TO_SCOPE,M_START,M_PROPOSER from MEETING where M_ID='$V'";
            $cursor=exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $M_START = $ROW['M_START'];
                $MR_NAME = $ROW['MR_NAME'];
                $M_NAME = $ROW['M_NAME'];
                $M_PROPOSER = $ROW['M_PROPOSER'];
            }
            if($TO_EMAIL=="1")
            {
                //$M_PROPOSER  = $_SESSION["LOGIN_USER_ID"];
                $TIME    = date("Y-m-d H:i:s");
                $SUBJECT = _("会议查阅：").$M_NAME;
                $CONTENT = sprintf(_("%s在%s开会，会议名称：%s，请您查阅！"),$M_START,$MR_NAME,$M_NAME);
                $CONTENT          = stripslashes($CONTENT);
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
                $query="INSERT INTO email_body (FROM_ID,TO_ID2,SUBJECT,CONTENT,SEND_FLAG,SEND_TIME,COMPRESS_CONTENT) values ('$M_PROPOSER','$USER_ID','$SUBJECT','$CONTENT_STRIP','1','".strtotime($TIME)."',$COMPRESS_CONTENT)";
                exequery(TD::conn(),$query);
                $ROW_ID = mysql_insert_id();
                $USER_IDS    = td_trim($USER_ID);
                $MY_ARRAY    = explode(",",$USER_IDS);
                $ARRAY_COUNT = sizeof($MY_ARRAY);
                for($I=0;$I< $ARRAY_COUNT;$I++)
                {
                    $query = "INSERT INTO email(TO_ID,READ_FLAG,DELETE_FLAG,BOX_ID,BODY_ID,RECEIPT) values ('$MY_ARRAY[$I]','0','0','0','$ROW_ID','0')";
                    exequery(TD::conn(),$query);
                    $ROW_EMAIL_ID = mysql_insert_id();
                    $REMIND_URL="email/inbox/read_email/read_email.php?BOX_ID=0&BTN_CLOSE=1&FROM=1&EMAIL_ID=".$ROW_EMAIL_ID."&BODY_ID=".$ROW_ID;
                    $SMS_CONTENT=_("请查收我的邮件！")."\n"._("主题：").csubstr($SUBJECT,0,100);
                    send_sms("",$M_PROPOSER,$MY_ARRAY[$I],"2",$SMS_CONTENT,$REMIND_URL,$ROW_EMAIL_ID);
                }
            }
        }
        if($M_ATTENDEE != "")
        {
            //echo $M_ATTENDEE;echo "<br>";echo $M_PROPOSER_NAME;echo $M_START;  echo $M_NAME;
            $REMIND_URL1="1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
            $CONTENT = sprintf(_("%s通知您于%s在%s开会，会议名称：%s，请按时参加。"),$M_PROPOSER_NAME,$M_START,$MR_NAME,$M_NAME);
            send_sms("",$M_PROPOSER,$M_ATTENDEE,"8_1",$CONTENT,$REMIND_URL1,$M_ID);
            send_mobile_sms_user("",$M_PROPOSER,$M_ATTENDEE,$CONTENT,8);
        }

        $CONTENT=sprintf(_("您%s的会议申请已被批准！"),$M_START2);
        //$CONTENT=_("您").$M_START2._("的会议申请已被批准！");

        $SYS_PARA_ARRAY=get_sys_para("SMS_REMIND");
        $PARA_VALUE=$SYS_PARA_ARRAY["SMS_REMIND"];
        $SMS_REMIND1=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
        $SMS2_REMIND1=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);

        $REMIND_URL1="1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
        if(find_id($SMS_REMIND1,8) && $M_PROPOSER!="")
        {
            send_sms("",$M_PROPOSER,$M_PROPOSER,"8_1",$CONTENT,$REMIND_URL1,$M_ID);
        }

        if(($RESEND_LONG > 0 || $RESEND_LONG_FEN > 0 ) && $RESEND_SEVERAL > 0 && $M_START2!="NULL" && $M_ATTENDEE!="")
        {
            $NUM=($RESEND_LONG*60+$RESEND_LONG_FEN)/$RESEND_SEVERAL;
            for($I=0;$I < $RESEND_SEVERAL;$I++)
            {
                $SEND_TIME=strtotime($M_START2) - $RESEND_LONG*3600 - $RESEND_LONG_FEN*60 + $I*$NUM*60;
                $SEND_TIME=date("Y-m-d H:i:s",$SEND_TIME);
                $M_START_TEM=date("Y-m-d H:i",strtotime($M_START));
                $msg=sprintf(_("%s在%s开会，请按时参加。"),$M_START_TEM,$MR_NAME);

                send_sms($SEND_TIME,$M_PROPOSER,$M_ATTENDEE,"8_1",$msg,$REMIND_URL1,$M_ID);
                if(find_id($SMS2_REMIND1,8))
                    send_mobile_sms_user($SEND_TIME,$M_PROPOSER,$M_ATTENDEE,$msg,8);
            }
        }

        $CONTENT=_("会议:").$M_NAME;//.$M_ID._("：")
        $MY_ARRAY=td_trim($M_ATTENDEE);
        $MY_ARRAY=explode(",",$MY_ARRAY);
        $ARRAY_COUNT=sizeof($MY_ARRAY);
        $URL = '/general/meeting/query/meeting_detail.php?M_ID='.$M_ID;
        for($I=0;$I< $ARRAY_COUNT;$I++)
        {
            $query="insert into CALENDAR(USER_ID,CAL_TIME,END_TIME,CAL_TYPE,CAL_LEVEL,CONTENT,OVER_STATUS,FROM_MODULE,URL,M_ID) values ('$MY_ARRAY[$I]','".strtotime($M_START)."','".strtotime($M_END)."','1','1','$CONTENT','0','2','$URL','$M_ID')";
            exequery(TD::conn(),$query);
        }
    }
    else
    {
        $CONTENT=sprintf(_("您%s的会议申请时间冲突，进入待审状态！"),$M_START);
        //$CONTENT=_("您").$M_START._("的会议申请时间冲突，进入待审状态！");
        $REMIND_URL1="1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
        if(find_id($SMS_REMIND1,"8_1") && $M_PROPOSER!="")
        {
            send_sms("",$_SESSION["LOGIN_USER_ID"],$M_PROPOSER,"8_1",$CONTENT,$REMIND_URL1,$M_ID);
        }
        continue;
    }
    //如果批准通过才通知EMIAL
    if($M_STATUS=="1" && $TO_EMAIL=="1")
    {
        $TIME    = date("Y-m-d H:i:s");
        $SUBJECT = _("会议：").$M_NAME;
        $CONTENT = sprintf(_("通知您于%s在%s开会，会议名称：%s"),$M_START,$MR_NAME,$M_NAME);
        //$CONTENT=_("通知您于").$M_START._("在").$MR_NAME._("开会，会议名称：").$M_NAME;
        $CONTENT          = stripslashes($CONTENT);
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
        $query="INSERT INTO email_body (FROM_ID,TO_ID2,SUBJECT,CONTENT,SEND_FLAG,SEND_TIME,COMPRESS_CONTENT) values ('$M_PROPOSER','$M_ATTENDEE','$SUBJECT','$CONTENT_STRIP','1','".strtotime($TIME)."',$COMPRESS_CONTENT)";
        exequery(TD::conn(),$query);
        $ROW_ID      = mysql_insert_id();
        $MY_ARRAY    = td_trim($M_ATTENDEE);
        $MY_ARRAY    = explode(",",$MY_ARRAY);
        $ARRAY_COUNT = sizeof($MY_ARRAY);
        for($I=0;$I< $ARRAY_COUNT;$I++)
        {
            $query = "INSERT INTO email (TO_ID,READ_FLAG,DELETE_FLAG,BOX_ID,BODY_ID,RECEIPT) values ('$MY_ARRAY[$I]','0','0','0','$ROW_ID','0')";
            exequery(TD::conn(),$query);
            $ROW_EMAIL_ID = mysql_insert_id();
            $REMIND_URL="email/inbox/read_email/read_email.php?BOX_ID=0&BTN_CLOSE=1&FROM=1&EMAIL_ID=".$ROW_EMAIL_ID."&BODY_ID=".$ROW_ID;
            $SMS_CONTENT=_("请查收我的邮件！")."\n"._("主题：").csubstr($SUBJECT,0,100);
            send_sms("",$_SESSION["LOGIN_USER_ID"],$MY_ARRAY[$I],"2",$SMS_CONTENT,$REMIND_URL,$ROW_EMAIL_ID);
        }
    }
}
if($COUNT1>0)
{
    $REMIND_URL2="1:calendar/arrange/";
    $CONTENT=sprintf(_("参加周期性会议，会议名称：%s，详情查看个人日程安排"), $M_NAME);

    if($M_STATUS=="1" && ($SMS_REMIND=="1" || find_id($SMS_REMIND1,"8_1")) && $M_ATTENDEE!="")
    {
        send_sms("",$M_PROPOSER,$M_ATTENDEE,"8_1",$CONTENT,$REMIND_URL2);
    }

    if($M_STATUS=="1" && ($SMS2_REMIND=="1" || find_id($SMS2_REMIND1,8)) && $M_ATTENDEE!="")
    {
        send_mobile_sms_user("",$M_PROPOSER,$M_ATTENDEE,$M_PROPOSER_NAME.$CONTENT,8);
    }
}
//header("location: manage.php?M_STATUS=$M_STATUS1");
//header("location: manage1.php");
//找出0状态（未批准）符合cycle-no条件的 周期性会议
$query = "SELECT count(*) as num from MEETING where M_STATUS=0 and CYCLE=".$_GET['CYCLE']." and CYCLE_NO=".$_GET['CYCLE_NO'];
$cursor=exequery(TD::conn(),$query);
$num=mysql_fetch_array($cursor)['num'];
if($num == 0){
    header("location:manage1.php?M_STATUS=0");
}else{ 
    header("location:manage_cycle.php?M_STATUS=$M_STATUS&CYCLE=".$_GET['CYCLE']."&CYCLE_NO=".$_GET['CYCLE_NO']);  
}

?>
</body>
</html>
