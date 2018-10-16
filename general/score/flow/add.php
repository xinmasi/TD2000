<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("保存考核任务");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//----------- 合法性校验 ---------
if($BEGIN_DATE!="")
{
    $TIME_OK=is_date($BEGIN_DATE);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("生效日期格式不对，应形如 1999-1-2"));
        ?>
        <br>
        <div align="center">
            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&SUBJECT=<?=$SUBJECT?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&CONTENT=<?=$CONTENT?>'">
        </div>
        <?
        exit;
    }
}

if($END_DATE!="")
{
    $TIME_OK=is_date($END_DATE);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("终止日期格式不对，应形如 1999-1-2"));
        ?>
        <br>
        <div align="center">
            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&SUBJECT=<?=$SUBJECT?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&CONTENT=<?=$CONTENT?>'">
        </div>
        <?
        exit;
    }
}

$CUR_DATE=date("Y-m-d",time());

if($BEGIN_DATE=="")
    $BEGIN_DATE=$CUR_DATE;

if($END_DATE!="")
{
    if(compare_date($BEGIN_DATE,$END_DATE)==1)
    {
        Message(_("错误"),_("生效日期不能晚于终止日期"));
        ?>
        <br>
        <div align="center">
            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&SUBJECT=<?=$SUBJECT?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&CONTENT=<?=$CONTENT?>'">
        </div>
        <?
        exit;
    }

}
else
    $END_DATE="0000-00-00";

$SEND_TIME=date("Y-m-d H:i:s",time());

if($ANONYMITY=="on")
    $ANONYMITY='1';
else
    $ANONYMITY='0';

if($FLOW_FLAG=="on")
    $FLOW_FLAG='1';
else
    $FLOW_FLAG='0';

if($IS_SELF_ASSESSMENT=="on")
    $IS_SELF_ASSESSMENT=1;
else
    $IS_SELF_ASSESSMENT=0;
//------------------------------------------
$FLOW_ID=intval($FLOW_ID);
if($FLOW_ID=="")
    $query="insert into SCORE_FLOW (GROUP_ID,FLOW_TITLE,FLOW_DESC,FLOW_FLAG,BEGIN_DATE,END_DATE,SEND_TIME,RANKMAN,PARTICIPANT,ANONYMITY,CREATE_USER_ID,IS_SELF_ASSESSMENT,VIEW_USER_ID) values ('$GROUP_ID','$FLOW_TITLE','$FLOW_DESC','$FLOW_FLAG','$BEGIN_DATE','$END_DATE','$SEND_TIME','$SECRET_TO_ID','$PARTICIPANT_TO_ID','$ANONYMITY','".$_SESSION["LOGIN_USER_ID"]."','$IS_SELF_ASSESSMENT','$COPY_TO_ID')";
else
    $query="update SCORE_FLOW set GROUP_ID='$GROUP_ID',FLOW_TITLE='$FLOW_TITLE',FLOW_DESC='$FLOW_DESC',FLOW_FLAG='$FLOW_FLAG',BEGIN_DATE='$BEGIN_DATE',END_DATE='$END_DATE',SEND_TIME='$SEND_TIME',RANKMAN='$SECRET_TO_ID',PARTICIPANT='$PARTICIPANT_TO_ID',ANONYMITY='$ANONYMITY',IS_SELF_ASSESSMENT='$IS_SELF_ASSESSMENT',VIEW_USER_ID='$COPY_TO_ID' where FLOW_ID='$FLOW_ID'";
exequery(TD::conn(),$query);

if($FLOW_ID=="")
    $FLOW_ID=mysql_insert_id();

//------- 事务提醒 --------
if($SMS_REMIND=="on")
{
    if(compare_date($BEGIN_DATE,$CUR_DATE)!=0)
        $SEND_TIME=$BEGIN_DATE;

    if($IS_SELF_ASSESSMENT==1)
    {
        $SMS_CONTENT=_("请查看考核任务并对自己进行自评！")."\n"._("标题：").csubstr($FLOW_TITLE,0,100);
        $REMIND_URL="1:score/self_assessment/index.php";
        $USER_ID_STR = td_trim($PARTICIPANT_TO_ID);
        send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,"15",$SMS_CONTENT,$REMIND_URL);
    }
    $SMS_CONTENT=_("请查看考核任务！")."\n"._("标题：").csubstr($FLOW_TITLE,0,100);
    $REMIND_URL="1:score/submit/index.php";

    $ARRY_RANK   = explode(",",$SECRET_TO_ID);
    $ARRAY_COUNT = sizeof($ARRY_RANK);

    if($ARRY_RANK[$ARRAY_COUNT-1]=="")
        $ARRAY_COUNT--;

    $USER_ID_STR = "";
    for($I=0;$I<$ARRAY_COUNT;$I++)
    {
        $USER_ID_STR .= $ARRY_RANK[$I].",";
    }
    $USER_ID_STR = td_trim($USER_ID_STR);

    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,"15",$SMS_CONTENT,$REMIND_URL);
    //给查看范围的人添加发送事务提醒 20161205 spz
    $url = "1:score/flow/scoredate/query.php?GROUP_ID=$GROUP_ID&FLOW_ID=$FLOW_ID&ANONYMITY=$ANONYMITY";
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$COPY_TO_ID,"15",$SMS_CONTENT,$url);
}
if($SMS2_REMIND=="on")
{
    if(compare_date($BEGIN_DATE,$CUR_DATE)!=0)
        $SEND_TIME=$BEGIN_DATE;

    if($IS_SELF_ASSESSMENT==1)
    {
        $SMS_CONTENT=_("请查看考核任务并对自己进行自评！")."\n"._("标题：").csubstr($FLOW_TITLE,0,100);
        $REMIND_URL="1:score/self_assessment/index.php";
        $USER_ID_STR = td_trim($PARTICIPANT_TO_ID);
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,$SMS_CONTENT,15);
    }

    $SMS_CONTENT=_("请查看考核任务！")."\n"._("标题：").csubstr($FLOW_TITLE,0,100);
    $REMIND_URL="1:score/submit/index.php";
    if($USER_ID_STR=="")
    {
        $ARRY_RANK   = explode(",",$SECRET_TO_ID);
        $ARRAY_COUNT = sizeof($ARRY_RANK);

        if($ARRY_RANK[$ARRAY_COUNT-1]=="")
            $ARRAY_COUNT--;

        $USER_ID_STR = "";
        for($I=0;$I<$ARRAY_COUNT;$I++)
        {
            $USER_ID_STR .= $ARRY_RANK[$I].",";
        }
        $USER_ID_STR = td_trim($USER_ID_STR);
    }
    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,$SMS_CONTENT,15);
}

Message("",_("保存成功！"));
$paras = strpos($_SERVER["HTTP_REFERER"], "?") ? $paras = $_SERVER["HTTP_REFERER"]."&connstatus=1" : $paras = $_SERVER["HTTP_REFERER"]."?connstatus=1";

?>
<center>
    <input type="button" class="BigButton" value="<?=_("返回")?>" onClick="window.location.href='<?=$paras?>'"/>
</center>
</body>
</html>
