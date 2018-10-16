<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");


//修改事务提醒状态--yc
update_sms_status('45',$AFF_ID);

$query="select * from AFFAIR where AFF_ID='$AFF_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $USER_ID=$ROW["USER_ID"];
    $BEGIN_TIME=$ROW["BEGIN_TIME"];
    $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
    $END_TIME=$ROW["END_TIME"];
    if($END_TIME!=0)
        $END_TIME=date("Y-m-d H:i:s",$END_TIME);
    $TYPE=$ROW["TYPE"];
    $REMIND_DATE=$ROW["REMIND_DATE"];
    $REMIND_TIME=$ROW["REMIND_TIME"];
    $TAKER=$ROW["TAKER"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    if($MANAGER_ID!=""&&substr($MANAGER_ID,-1)!=",")
        $MANAGER_ID.=",";
    if($MANAGER_ID!="")
        $MANAGER_NAME=td_trim(GetUserNameById($MANAGER_ID));
    if($TAKER!="")
        $TAKER_NAME=td_trim(GetUserNameById($TAKER));
    if($MANAGER_ID=="" && $USER_ID!="")
        $CREATOR_NAME=td_trim(GetUserNameById($USER_ID));
    if(substr($MANAGER_ID_NAME,-1)==",")
        $MANAGER_ID_NAME=substr($MANAGER_ID_NAME,0,-1);
    if($USER_ID!=$_SESSION["LOGIN_USER_ID"]&&!stristr($TAKER,$_SESSION["LOGIN_USER_ID"]))
        exit;
    $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
    if($TYPE=="2")
        $AFF_TIME=_("每日 ").$REMIND_TIME;
    elseif($TYPE=="3")
    {
        if($REMIND_DATE=="1")
            $REMIND_DATE=_("一");
        elseif($REMIND_DATE=="2")
            $REMIND_DATE=_("二");
        elseif($REMIND_DATE=="3")
            $REMIND_DATE=_("三");
        elseif($REMIND_DATE=="4")
            $REMIND_DATE=_("四");
        elseif($REMIND_DATE=="5")
            $REMIND_DATE=_("五");
        elseif($REMIND_DATE=="6")
            $REMIND_DATE=_("六");
        elseif($REMIND_DATE=="0")
            $REMIND_DATE=_("日");
        $AFF_TIME=_("每周").$REMIND_DATE." ".$REMIND_TIME;
    }
    elseif($TYPE=="4")
        $AFF_TIME=_("每月").$REMIND_DATE._("日 ").$REMIND_TIME;
    elseif($TYPE=="5")
        $AFF_TIME=_("每年").str_replace("-",_("月"),$REMIND_DATE)._("日 ").$REMIND_TIME;
    elseif($TYPE=="6")
        $AFF_TIME=_("工作日 ").$REMIND_TIME;
    $CONTENT=td_htmlspecialchars($CONTENT);

    $TITLE=csubstr($CONTENT,0,10);
}

$HTML_PAGE_TITLE = $TITLE;
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/calendar/css/calendar_note.css">
<body>
<div id="main">
    <div class="calendar-note-border-color" style="height:4px;"></div>
    <div class="calendar-note-head  ">
        <h1><?=$TITLE?><b style="font-size: 12px; margin-left: 20px; color: #FFFFFF;padding: 4px; border-radius: 4px;background:<?=$backcolor?>;"><?=$OVER_STATUS1?></b></h1>
    </div>
    <div class="calendar-note-content">
        <div class="calendar-note"><label class="calendar-note-font"><?=_("提醒时间：")?></label><span style="color: #427297;font-family: arial; "><?=$AFF_TIME?></span></div>


        <? if($TAKER_NAME!="" || $OWNER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font"><?=_("创建者：")?></label><?=$CREATOR_NAME?></div>
            <div class="calendar-note-desc"></div>
        <? }?>
        <? if($MANAGER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font"><?=_("管理者：")?></label><?=$MANAGER_NAME?></div>
        <? }?>
        <? if($TAKER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font" style="float:left"><?=_("参与者：")?></label><div style="word-break:break-all;float:left;width:245px"><?=$TAKER_NAME?></div></div>
            <div class="calendar-note-desc"></div>
        <? }?>
        <? if($OWNER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font"><?=_("所属者：")?></label><?=$OWNER_NAME?></div>
            <div class="calendar-note-desc"></div>
        <? }?>
        <div class="calendar-note" style="min-height:150px; clear:both;">
            <label class="calendar-note-font"><?=_("日程内容：")?></label>
            <div class="calendar-note-desc" style="word-break: break-all;width:250px;height:150px;overflow:auto;">
                <?=nl2br($CONTENT)?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
