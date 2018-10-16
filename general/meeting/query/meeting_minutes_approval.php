<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("会议详细信息");
include_once("inc/header.inc.php");
function change_time($BEGIN_TIME,$END_TIME)
{
    $BEGIN_TIME_U=strtotime($BEGIN_TIME);
    $END_TIME_U=strtotime($END_TIME);
    $BEGIN_DATE=date("Y-m-d",$BEGIN_TIME_U);
    $END_DATE=date("Y-m-d",$END_TIME_U);
    $BEGIN_TIMES=date("H:i:s",$BEGIN_TIME_U);
    $END_TIMES=date("H:i:s",$END_TIME_U);

    if($BEGIN_DATE==$END_DATE)
    {
        $DATE_DESC=$BEGIN_DATE.week_change($BEGIN_TIME_U).$BEGIN_TIMES." "._("至")." ".$END_TIMES;

    }
    else
    {
        $DATE_DESC=$BEGIN_DATE.week_change($BEGIN_TIME_U).$BEGIN_TIMES." "._("至")." ".$END_DATE.week_change($END_TIME_U).$END_TIMES;
    }

    return $DATE_DESC;

}

function week_change($DAY_DATE)
{
    switch(date("w",$DAY_DATE))
    {
        case 0:
            return _("（周日）");
        case 1:
            return _("（周一）");
        case 2:
            return _("（周二）");
        case 3:
            return _("（周三）");
        case 4:
            return _("（周四）");
        case 5:
            return _("（周五）");
        case 6:
            return _("（周六）");
    }
}
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.min.css">
<script src="/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="/static/js/bootstrap/js/bootstrap.min.js"></script>
<script>
//多工作关闭
function showModal(){
    $('#myModal').modal('show');
}
function close_this_new()
{
    TJF_window_close();
    window.location.reload();
}
$(document).ready(function(){
    $('#app_sure').click(function(){
        var reject_content = $('[name="reject_content"]').val();
        if($.trim(reject_content) == ""){
            alert('驳回内容不能为空!');
            return;
        }else{
            document.rejectform.submit();
        }
    })
})
</script>
<style>
#main{
    width:900px;
    margin:0px auto;
    border:1px solid #ccc;
    text-align:center;
    background-color:#fff;
}

.meeting-head{
    padding:5px;
    text-align:center;
    line-height:2em;
}
.meeting-content{
    padding:10px;
    font-size:16px;
}
.meeting{
    padding:15px 60px;
    text-align:left;
}
.meeting-font{
    font-size:18px;
    font-weight:bolder;
}
.meeting-footer{
    padding:10px 10px 20px;
    position:relative;
    right:45px;
    font-size:15px;
    text-align:right;
}
.meeting-right{
    float:right;
    position:relative;
    right:107px
}
.meeting-button{
    text-align:center;
    margin-bottom:10px;
    padding:20px;
}
.meeting-desc{
    padding:0px 60px 15px 60px;
    text-align:left;
    line-height:1.5;
}
<!--弹框-->
    element.style {
        display: block;
    }
.modal.fade.in {
    top: 10%;
}
.fade.in {
    opacity: 1;
}
.modal.fade {
    webkit-transition: opacity .3s linear,top .3s ease-out;
    moz-transition: opacity .3s linear,top .3s ease-out;
    o-transition: opacity .3s linear,top .3s ease-out;
    transition: opacity .3s linear,top .3s ease-out;
    top: -25%;
}
.fade {
    opacity: 0;
    webkit-transition: opacity 0.15s linear;
    moz-transition: opacity 0.15s linear;
    o-transition: opacity 0.15s linear;
    transition: opacity 0.15s linear;
}
.hide {
    display: none;
}
.modal {
    position: fixed;
    top: 10%;
    left: 50%;
    z-index: 1050;
    width: 560px;
    margin-left: -280px;
    background-color: #ffffff;
    border: 1px solid #999;
    border: 1px solid rgba(0,0,0,0.3);
    webkit-border-radius: 6px;
    moz-border-radius: 6px;
    border-radius: 6px;
    webkit-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
    moz-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
    box-shadow: 0 3px 7px rgba(0,0,0,0.3);
    webkit-background-clip: padding-box;
    moz-background-clip: padding-box;
    background-clip: padding-box;
    outline: none;
}
user agent stylesheetdiv {
    display: block;
}
Inherited from body.bodycolor
body {
    margin: 0;
    font-family: Simsun,Arial,sans-serif;
    font-size: 13px;
    line-height: 20px;
    color: #333333;
    background-color: #ffffff;
}
body, button, input, select, textarea {
    color: #393939;
}
Inherited from html
html {
    font-size: 100%;
    webkit-text-size-adjust: 100%;
    ms-text-size-adjust: 100%;
}

</style>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("会议详细信息")?></span><br>
        </td>
    </tr>
</table>
<?
$MR_ID=intval($MR_ID);

//修改事务提醒状态--yc
update_sms_status('802',$M_ID);

$query = "SELECT * from MEETING where M_ID='$M_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $MEETING_COUNT++;
    $M_ID=$ROW["M_ID"];
    $M_NAME=$ROW["M_NAME"];
    $M_TOPIC=$ROW["M_TOPIC"];
    $M_DESC=$ROW["M_DESC"];
    $M_PROPOSER=$ROW["M_PROPOSER"];
    $M_REQUEST_TIME=$ROW["M_REQUEST_TIME"];
    $M_ATTENDEE_OUT =$ROW["M_ATTENDEE_OUT"];
    $M_ATTENDEE_NOT =$ROW["M_ATTENDEE_NOT"];
    $M_ATTENDEE=$ROW["M_ATTENDEE"];
    $SUMMARY_STATUS = $ROW['SUMMARY_STATUS'];
    $SUMMARY = $ROW['SUMMARY'];
    $M_START=$ROW["M_START"];
    $M_END=$ROW["M_END"];
    $M_ROOM=$ROW["M_ROOM"];
    $M_STATUS=$ROW["M_STATUS"];
    $M_MANAGER=$ROW["M_MANAGER"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    $ATTACHMENT_ID1=$ROW["ATTACHMENT_ID1"];
    $ATTACHMENT_NAME1=$ROW["ATTACHMENT_NAME1"];
    $SECRET_TO_ID=$ROW["SECRET_TO_ID"];
    $PRIV_ID=$ROW["PRIV_ID"];
    $TO_ID=$ROW["TO_ID"];
    $EQUIPMENT_ID_STR=$ROW["EQUIPMENT_ID_STR"];
    $RECORDER=$ROW["RECORDER"];
    $REASON=$ROW["REASON"];
    $READ_PEOPLE_ID=$ROW["READ_PEOPLE_ID"];
    $M_FACT_ATTENDEE=$ROW["M_FACT_ATTENDEE"];
    $TOK=strtok($RECORDER,",");
    $RECORDER_NAME=GetUserNameById($TOK);
    if($RECORDER_NAME=="")
        $RECORDER_NAME=$TOK;

    $POSTFIX = _("，");
    $EQUIPMENT_NAME_STR="";
    $EQUIPMENT_ID_STR=td_trim($EQUIPMENT_ID_STR);
    if($EQUIPMENT_ID_STR!="")
    {
        $query2 = "SELECT EQUIPMENT_NAME from MEETING_EQUIPMENT where EQUIPMENT_ID in ($EQUIPMENT_ID_STR)";
        $cursor2= exequery(TD::conn(),$query2);
        while($ROW=mysql_fetch_array($cursor2))
            $EQUIPMENT_NAME_STR.=$ROW["EQUIPMENT_NAME"].$POSTFIX;
    }
    $USER_NAME2="";
    $TOK=strtok($M_ATTENDEE,",");
    while($TOK!="")
    {
        $query2 = "SELECT USER_NAME from USER where USER_ID='$TOK'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW=mysql_fetch_array($cursor2))
            $USER_NAME2.=$ROW["USER_NAME"].",";
        $TOK=strtok(",");
    }

    $USER_NAME2=substr($USER_NAME2,0,-1);
    $M_ATTENDEE_NAME_INNER="$USER_NAME2";
    $M_ATTENDEE_NAME_OUTER="$M_ATTENDEE_OUT";


    $M_ROOM=intval($M_ROOM);
    $query = "SELECT * from MEETING_ROOM where MR_ID='$M_ROOM'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW1=mysql_fetch_array($cursor1))
        $M_ROOM_NAME=$ROW1["MR_NAME"];

    if($M_STATUS==0)
    {
        $M_STATUS_DESC=_("待批");
        $backcolor = "#3a87ad";
    }
    elseif($M_STATUS==1)
    {
        $M_STATUS_DESC=_("已准");
        $backcolor = "#468847";
    }
    elseif($M_STATUS==2)
    {
        $M_STATUS_DESC=_("进行中");
        $backcolor = "#C9892E";
    }
    elseif($M_STATUS==3)
    {
        $M_STATUS_DESC=_("未批准");
        $backcolor = "#E55050";
    }
    elseif($M_STATUS==4)
    {
        $M_STATUS_DESC=_("已结束");
        $backcolor = "#999999";
    }
    if($flag==1)
    {
        $M_STATUS_DESC=_("已取消");
        $backcolor  = "#E55050" ;
    }

    $query = "SELECT USER_NAME from USER where USER_ID='$M_PROPOSER'";
    $cursor2= exequery(TD::conn(),$query);
    if($ROW2=mysql_fetch_array($cursor2))
        $USER_NAME=$ROW2["USER_NAME"];

    $query = "SELECT USER_NAME from USER where USER_ID='$M_MANAGER'";
    $cursor2= exequery(TD::conn(),$query);
    if($ROW2=mysql_fetch_array($cursor2))
        $USER_NAME_MANAGER=$ROW2["USER_NAME"];

    $TOK=strtok($SECRET_TO_ID,",");
    while($TOK!="")
    {
        $query2 = "SELECT USER_NAME from USER where USER_ID='$TOK'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW=mysql_fetch_array($cursor2))
            $USER_NAME3.=$ROW["USER_NAME"].",";
        $TOK=strtok(",");
    }

    $USER_NAME3=substr($USER_NAME3,0,-1);

    $TOK=strtok($PRIV_ID,",");
    while($TOK!="")
    {
        $query2 = "SELECT PRIV_NAME from USER_PRIV where USER_PRIV='$TOK'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW=mysql_fetch_array($cursor2))
            $PRIV_NAME.=$ROW["PRIV_NAME"].",";
        $TOK=strtok(",");
    }

    $PRIV_NAME=substr($PRIV_NAME,0,-1);

    $TOK=strtok($TO_ID,",");
    while($TOK!="")
    {
        $query2 = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID='$TOK'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW=mysql_fetch_array($cursor2))
            $DEPT_NAME.=$ROW["DEPT_NAME"].",";
        $TOK=strtok(",");
    }
    $DEPT_NAME=substr($DEPT_NAME,0,-1);

    if($TO_ID=="ALL_DEPT")
        $DEPT_NAME=_("全体部门");
    $DATE_DESC=change_time($M_START,$M_END);
?>
<div id="main" style="height:auto;">
    <div class="meeting-head">
        <h1><?=$M_NAME?><b style="font-size: 12px; margin-left: 20px; color: #FFFFFF;padding: 4px; border-radius: 4px;background:<?=$backcolor?>;"><?=$M_STATUS_DESC?></b></h1>
    </div>
    <div class="meeting-content">
        <div class="meeting"><label class="meeting-font"><?=_("会议地点：")?></label><?=$M_ROOM_NAME?></div>
        <!--<div class="meeting"><label class="meeting-font"><?=_("会议时间：")?></label><span style="color: #427297;font-family: arial; "><?=$M_START ._(" ~ ") .$M_END?></span></div>-->
        <div class="meeting"><label class="meeting-font"><?=_("会议时间：")?></label><span style="color: #427297;font-family: arial; "><?=$DATE_DESC?></span></div>
        <div class="meeting"><label class="meeting-font"><?=_("会议主题：")?></label><?=$M_TOPIC?></div>
        <?if($M_DESC!=""){?>
            <div class="meeting"><label class="meeting-font"><?=_("会议描述：")?></label></div>
            <div class="meeting-desc"><?=$M_DESC?></div>
        <?}?>
        <div class="meeting"><?if($RECORDER_NAME!=""){?><span style="display:-moz-inline-box; display:inline-block;width:400px"><label class="meeting-font"><?=_("会议纪要员：")?></label><?=$RECORDER_NAME?></span><?}?>  <label class="meeting-font" ><?=_("会议室管理员：")?></label><?=$USER_NAME_MANAGER?>  </div>
        <?if($M_ATTENDEE_NAME_OUTER!="")
        {
            ?>
            <div class="meeting"><label class="meeting-font"><?=_("嘉  宾：")?></label><?=$M_ATTENDEE_NAME_OUTER?></div>
        <?}?>
        <div class="meeting"><label class="meeting-font"><?=_("参会人员：")?></label><?=$M_ATTENDEE_NAME_INNER?></div>
        <?if($DEPT_NAME!="")
        {?>
            <div class="meeting"><label class="meeting-font"><?=_("发布范围(部门)：")?></label><?=$DEPT_NAME?></div>
            <?
        }
        if ($PRIV_NAME!="")
        {
            ?>
            <div class="meeting"><label class="meeting-font"><?=_("发布范围（角色）：")?></label><?=$PRIV_NAME?></div>
            <?
        }
        if($USER_NAME3!="")
        {
            ?>
            <div class="meeting"><label class="meeting-font"><?=_("发布范围（人员）：")?></label><?=$USER_NAME3?></div>
            <?
        }
        ?>
        <div class="meeting" width='750px'><label class="meeting-font"><?=_("会议纪要 ：")?></label><?=$SUMMARY?></div>
        <div class="meeting" width='750px'>
            <?
            if($ATTACHMENT_ID1=="")
            {
                echo _("无附件");
            }else
            {
                echo "<br/>".attach_link($ATTACHMENT_ID1,$ATTACHMENT_NAME1,1,1,1);
            }
            ?>
        </div>
        <div class="meeting"><label class="meeting-font"><?=_("附件文档:")?></label>
            <?
            if($ATTACHMENT_ID=="")
            {
                echo _("无附件");
            }else
            {

                echo "<br/>".attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,0,1,0);
            }
            ?>
        </div>
        <?if($EQUIPMENT_NAME_STR!="")
        {
            ?>
            <div class="meeting"><label class="meeting-font"><?=_("会议室设备：")?></label><?=substr($EQUIPMENT_NAME_STR,0,-strlen($POSTFIX))?></div>
        <?}

        ?>
        <?if($M_STATUS==3)
        {
            ?>
            <div class="meeting"><label class="meeting-font"><?=_("不准原因：")?></label><?=$REASON?></div>
        <?}?>
        <?if(($M_STATUS==2 || $M_STATUS==4) && $M_ATTENDEE_NOT!="")
        {
            ?>
            <div class="meeting"><label class="meeting-font"><?=_("缺席人员：")?></label><?=$M_ATTENDEE_NOT?></div>
        <?}?>
    </div>

    <div class="meeting-footer">
        <?=_("该会议由").$USER_NAME._("于  ").$M_REQUEST_TIME._(" 申请") ?><br/>

    </div>
    <form enctype="multipart/form-data" action="summary_approval.php" method="post">
    <?
    if($SUMMARY_STATUS == 2)
    {

    }else
    {
        echo sms_remind(8);
    }
    ?>
<?
}
else
    Message("",_("未找到相应记录！"));
?>
</div>
<div class="meeting-button">
<?
switch($SUMMARY_STATUS)
{
    case 0:
        //echo "未提交";
?>
        <input type="button" value="<?=_("打印")?>" class="BigButton" onclick="document.execCommand('Print');" title="<?=_("直接打印表格页面")?>" />
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="close_this_new();" title="<?=_("关闭窗口")?>" />
<?
    break;
    case 1:
        //echo "待审批";
?>
        <input type="hidden" value="<?=$M_NAME?>" name="M_NAME"><!-- 会议名称 -->

        <input type="hidden" value="<?=$READ_PEOPLE_ID?>" name="COPY_TO_ID"><!-- 指定读者 -->

        <input type="hidden" value="<?=$M_FACT_ATTENDEE?>" name="M_FACT_ATTENDEE_ID"><!-- 实际参会人员 -->

        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>"><!-- 附件上传 -->
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">

        <input type="hidden" value="<?=$M_ID?>" name="M_ID"><!-- ID -->

        <input type="button" value="<?=_("打印")?>" class="BigButton" onclick="document.execCommand('Print');" title="<?=_("直接打印表格页面")?>" />
        <input type="submit" value="<?=_("发布")?>" class="BigButton" />
        <input type="button" value="<?=_("驳回")?>" class="BigButton" onclick="showModal();" />
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="close_this_new();" title="<?=_("关闭窗口")?>" />
    </form>
<?
    break;
    case 2:
        echo "<font color='green'><h5>已发布</h5></font>";
    break;
    case 3:
        //echo "驳回";
?>
        <input type="button" value="<?=_("打印")?>" class="BigButton" onclick="document.execCommand('Print');" title="<?=_("直接打印表格页面")?>" />
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="close_this_new();" title="<?=_("关闭窗口")?>" />
<?
    break;
}
?>
</div>
<form name="rejectform" action="summary_refusall.php" method="post" enctype="multipart/form-data">
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel"><?= _("驳回会议纪要");?></h3>
        </div>
        <div class="modal-body">
            <p></p>
            <textarea name="reject_content" id="CONTENT" style="width:97%; height:100px; resize:none;"></textarea>
        </div>
        <div class="modal-footer">
            <input type="hidden" value="<?=$M_ID?>" name="M_ID">
            <?=sms_remind(8);?>
            <input type="button" id="app_sure" class="btn btn-primary" value="<?=_("确定")?>">&nbsp;
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?= _("关闭")?></button>
        </div>
    </div>
</form>
</body>

</html>
