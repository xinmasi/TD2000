<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//2013-4-11 主服务查询
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";
$query="select * from CALENDAR where CAL_ID='$CAL_ID' and (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER))";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
    $CAL_TIME=$ROW["CAL_TIME"];
    $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
    $END_TIME=$ROW["END_TIME"];
    $END_TIME=date("Y-m-d H:i:s",$END_TIME);
    $CAL_LEVEL=$ROW["CAL_LEVEL"];
    $CONTENT=$ROW["CONTENT"];
    $OVER_STATUS=$ROW["OVER_STATUS"];
    $CONTENT=td_htmlspecialchars($CONTENT);
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $OWNER=$ROW["OWNER"];
    $TAKER=$ROW["TAKER"];
    $CREATOR=$ROW["USER_ID"];
    $FROM_MODULE = $ROW["FROM_MODULE"];
    $URL = $ROW["URL"];
    if($FROM_MODULE==1 || $FROM_MODULE==2)
    {
        $URLS = $URL;
    }
    $MANAGER_NAME="";
    if($MANAGER_ID!="")
        $MANAGER_NAME=td_trim(GetUserNameById($MANAGER_ID));
    if($TAKER!="")
        $TAKER_NAME=td_trim(GetUserNameById($TAKER));
    if($OWNER!="")
        $OWNER_NAME=td_trim(GetUserNameById($OWNER));
    $CREATOR_NAME=td_trim(GetUserNameById($CREATOR));
    if($OVER_STATUS=="0")
    {
        if(compare_time($CUR_TIME,$END_TIME)>0)
            $OVER_STATUS1="<font id=show1_$CAL_ID style='color:#FF0000'><b>"._("已超时")."</b></font>";
        else if(compare_time($CUR_TIME,$CAL_TIME)<0)
            $OVER_STATUS1="<font id=show1_$CAL_ID style='color:#0000FF'><b>"._("未开始")."</b></font>";
        else
            $OVER_STATUS1="<font id=show1_$CAL_ID style='color:#0000FF'><b>"._("进行中")."</b></font>";
    }
    else
    {
        $OVER_STATUS1="<font id=show1_$CAL_ID style='color:#00AA00'><b>"._("已完成")."</b></font>";
    }
    if($MANAGER_NAME=="")
        $OVER_STATUS1=$OVER_STATUS1;

    $TITLE=csubstr($CONTENT,0,10);

    if(substr($CAL_TIME,0,10) == $CUR_DATE && substr($END_TIME,0,10) == $CUR_DATE)
    {
        $CAL_TIME=substr($CAL_TIME,11,5);
        $END_TIME=substr($END_TIME,11,5);
    }
    else
    {
        $CAL_TIME=substr($CAL_TIME,0,16);
        $END_TIME=substr($END_TIME,0,16);
    }

}
else
{
    $HTML_PAGE_TITLE = $TITLE;
    include_once("inc/header.inc.php");
    Message("",_("未找到符合条件的结果"), '', $BUTTON_CLOSE);
    exit;
}
//修改事务提醒状态--yc
update_sms_status('5',$CAL_ID);

$HTML = '<div class="small" style="text-align:left;height:250px;overflow: auto;word-break:break-all;">';
$HTML.='<div style=float:right;margin-right:30px;><img src="'.MYOA_STATIC_SERVER.'/static/images/cal.png" style="width:64px;height:64px;"></div>';
$HTML.= $CAL_TIME.' - '.$END_TIME.'<br>';
if($TAKER_NAME!="" || $OWNER_NAME!="")
    $HTML.= _("创建者：").$CREATOR_NAME.'<br>';
if($MANAGER_NAME!="")
    $HTML.= _("安排者：").$MANAGER_NAME.'<br>';
if($TAKER_NAME!="")
    $HTML.= _("参与者：").$TAKER_NAME.'<br>';
if($OWNER_NAME!="")
    $HTML.= _("所属者：").$OWNER_NAME.'<br>';
$HTML.= '&nbsp;'.$OVER_STATUS1;

$HTML.= '<hr><p style="overflow:auto">';
if($URLS!="")
{
    $HTML.='<a href="'.$URLS.'" target="_blank">'.nl2br($CONTENT).'</a>';
}
else
{
    $HTML.=nl2br($CONTENT);
}
//$HTML.= '<a href="" target="_blank">'.nl2br($CONTENT).'</a>';
$HTML.= '</p></div>';
if($OVER_STATUS==0)
    $STATUS=_("完成");
else
    $STATUS=_("未完成");
//自己创建者、自己是安排者、OA管理员、所属者 有修改和删除的权限
if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($OWNER,$_SESSION["LOGIN_USER_ID"]))
{
    $FLAG=1;
    if($OVER_STATUS==0)
    {
        $FALG_STATU='<input type="button" value="'._("修改").'" class="btn" onclick="edit_cal(\''.$CAL_ID.'\')">&nbsp;&nbsp;<input type="button" value="'._("删除").'"  class="btn btn-danger" onclick="del_cal(\''."$CAL_ID".'\',1,\''.$IS_MAIN.'\')">&nbsp;&nbsp;';
    }
}
else
{
    $FLAG=0;
    $FALG_STATU='';
}
//给安排者和创建者 可发短信
if($MANAGER_ID!="" && $MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
    $TO_MSGID=$MANAGER_ID;
if(find_id($TAKER,$_SESSION["LOGIN_USER_ID"]) && $_SESSION["LOGIN_USER_ID"]!=$CREATOR)
    $TO_MSGID=$CREATOR;
if(($MANAGER_ID!="" && $MANAGER_ID!=$_SESSION["LOGIN_USER_ID"]) ||(find_id($TAKER,$_SESSION["LOGIN_USER_ID"]) && $_SESSION["LOGIN_USER_ID"]!=$CREATOR) )
    $FALG_STATU .="<input type='button'  onclick='sms_back(\"".urlencode($TO_MSGID)."\",\"".urlencode($MANAGER[$TO_MSGID])."\");' value='"._("回短信")."' class='btn'>&nbsp;";
if($AJAX == "1")
{
    ob_end_clean();
    echo $HTML;
    echo '<div align="center"><input type="button" value="'._("详情").'" title="'._("生成便签窗口").'"  class="btn btn-info" onclick="cal_note(\''."$CAL_ID".'\',\''.$IS_MAIN.'\')">&nbsp;&nbsp;<input type="button" value='."$STATUS".' class="btn" onclick="set_status_note(this, '."$CAL_ID".',\''.$IS_MAIN.'\');">&nbsp;&nbsp;'."$FALG_STATU".' <input type="button" value="'._("关闭").'" class="btn" onclick="HideDialog(\'form_div\');"></div>';
    exit;
}

$HTML_PAGE_TITLE = $TITLE;
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/calendar/css/calendar_note.css">

<body>
<div id="main">
    <div class="calendar-note-head">
        <h1><?=$TITLE?><b style="font-size: 12px; margin-left: 20px; color: #FFFFFF;padding: 4px; border-radius: 4px;background:<?=$backcolor?>;"><?=$OVER_STATUS1?></b></h1>
    </div>
    <div class="calendar-note-content">
        <div class="calendar-note"><label class="calendar-note-font"><?=_("开始时间：")?></label><span style="color: #427297;font-family: arial; "><?=$CAL_TIME?></span></div>
        <div class="calendar-note"><label class="calendar-note-font"><?=_("结束时间：")?></label><span style="color: #427297;font-family: arial; "><?=$END_TIME?></span></div>


        <?if($TAKER_NAME!="" || $OWNER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font"><?=_("创建者：")?></label><?=$CREATOR_NAME?></div>
            <div class="calendar-note-desc"></div>
        <?}?>
        <?if($MANAGER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font"><?=_("管理者：")?></label><?=$MANAGER_NAME?></div>
        <?}?>
        <?if($TAKER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font"><?=_("执行者：")?></label><?=$TAKER_NAME?></div>
            <div class="calendar-note-desc"></div>
        <?}?>
        <? if($OWNER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font"><?=_("所属者：")?></label><?=$OWNER_NAME?></div>
            <div class="calendar-note-desc"></div>
        <?}?>
        <div class="calendar-note">
            <label class="calendar-note-font"><?=_("日程内容：")?></label>
            <div class="calendar-note-desc">
                <?=nl2br($CONTENT)?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
