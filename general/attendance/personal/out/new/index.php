<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
header("Cache-control: private");
$CUR_TIME=date("H:i",time());
$CUR_DAY=date("Y-m-d",time());

$OUT_TIME2=date("H",time())+2;
if($OUT_TIME2>23)
    $OUT_TIME2=23;
$OUT_TIME2.=date(":i",time());

$HTML_PAGE_TITLE = _("新建外出登记");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script>
function refreshPriv(user_id)
{
    jQuery.ajax({
        type: 'POST',
        url:'../../data.php',
        data:{
            assign_user_id: user_id
        },
        //async: true,
        success:function(d){
            var data = d;
            jQuery('#leader_id').html(data.leader_id);
        }
    });
}
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});

    jQuery(".TableData").delegate(".orgAdds","click",function(){
        var to_id = "TO_ID",to_name = "TO_NAME";

        window.org_select_callbacks = window.org_select_callbacks || {};

        window.org_select_callbacks.add = function(item_id, item_name){
            refreshPriv(item_id);
        };
        window.org_select_callbacks.remove = function(item_id, item_name){
            refreshPriv("");
        };
        window.org_select_callbacks.clear = function(){
        };

        SelectUserSingle('7', '100', to_id, to_name);
        return false;
    });
});
function CheckForm()
{
    var temp_time = "<?=$CUR_DAY?>";
    if(document.form1.OUT_TYPE.value=="")
    {
        alert("<?=_("外出原因不能为空！")?>");
        return false;
    }
    if(document.form1.OUT_DATE.value=="" || document.form1.OUT_TIME1.value=="" || document.form1.OUT_TIME2.value=="")
    {
        alert("<?=_("外出时间不能为空！")?>");
        return false;
    }
    return true;
}
function sendForm()
{
    if(CheckForm())
    {
        document.form1.submit();
        document.getElementById('BUTTON').disabled='disabled';
        //document.form1.action="";
    }
}
function td_calendar(fieldname)
{
    myleft=document.body.scrollLeft+event.clientX-event.offsetX+120;
    mytop=document.body.scrollTop+event.clientY-event.offsetY+230;

    window.showModalDialog("/inc/calendar.php?FIELDNAME="+fieldname,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:280px;dialogHeight:215px;dialogTop:"+mytop+"px;dialogLeft:"+myleft+"px");
}

function td_clock(fieldname,pare)
{
    myleft=document.body.scrollLeft+event.clientX-event.offsetX+120;
    mytop=document.body.scrollTop+event.clientY-event.offsetY+230;
    window.showModalDialog("../../clock.php?FIELDNAME="+fieldname,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:280px;dialogHeight:120px;dialogTop:"+mytop+"px;dialogLeft:"+myleft+"px");
}

function time_status(str)
{
    if(str=="1")

        document.form1.action="../../../../vehicle/new.php?FLAG=1";

    if(str=="0")

        document.form1.action="out.php";

}
</script>


<body class="bodycolor attendance" onLoad="document.form1.OUT_TYPE.focus();">

<h5 class="attendance-title"><span class="big3"> <?=_("新建外出登记")?></span></h5><br>
<br>

<form action="out.php"  method="post" id="form1" name="form1" class="big1">
    <table class="TableBlock" width="90%" align="center">
        <?
        $query = "select DEPT_HR_MANAGER from HR_MANAGER where DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
        $cursor=exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
            $DEPT_HR_MANAGER=$ROW["DEPT_HR_MANAGER"];

        /*$query2 = "select PARA_VALUE from SYS_PARA where PARA_NAME ='DEPT_HR_AGENT'";
        $cursor2=exequery(TD::conn(),$query2);
        if($ROW2=mysql_fetch_array($cursor2))
           $DEPT_HR_AGENT=$ROW2["PARA_VALUE"];*/
        $query2 = "select MANAGER_ID,MANAGERS,DEPT_ID_STR from ATTEND_LEAVE_MANAGER";
        $cursor2=exequery(TD::conn(),$query2);
        while($ROW2=mysql_fetch_array($cursor2))
        {
            $MANAGERS1.=$ROW2["MANAGERS"];
            $DEPT_ID_STR.=$ROW2["DEPT_ID_STR"];
        }
        if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($DEPT_HR_MANAGER,$_SESSION["LOGIN_USER_ID"]) || find_id($MANAGERS1,$_SESSION["LOGIN_USER_ID"]))
        {
            ?>
            <tr>
                <td nowrap class="TableData"> <?=_("是否批量添加：")?></td>
                <td class="TableData"><input type="checkbox" name="batch" id="batch"></td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("外出人：")?></td>
                <td nowrap class="TableData" id="WaiChuRenYuan2" style="display: none">
                    <input type="hidden" name="COPY_TO_ID" value="">
                    <textarea cols=21 name="COPY_TO_NAME" rows=2  wrap="yes" readonly></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectUser('7','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("选择")?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
                    <span style="font-size:12px;">  <?=_("（说明：不填写为本人外出登记。）")?></span>
                </td>
                <td class="TableData" id="WaiChuRenYuan1">
                    <input type="hidden" name="TO_ID" value="">
                    <input type="text" name="TO_NAME" size="13" value="" readonly>&nbsp;
                    <a href="javascript:;" class="orgAdd orgAdds" name="orgAdd" title="<?=_("指定外出人")?>"><?=_("指定")?></a>
                    <span style="font-size:12px;">  <?=_("（说明：不填写为本人外出登记。）")?></span>
                </td>
            </tr>
            <?
        }
        ?>
        <tr>
            <td nowrap class="TableData"> <?=_("外出原因：")?></td>
            <td class="TableData">
                <textarea name="OUT_TYPE" class="validate[required]]" data-prompt-position="centerRight:0,18" cols="60" rows="3"><?=$OUT_TYPE?></textarea>
                <?
                $query = "select PARA_VALUE from SYS_PARA where PARA_NAME='OUT_REQUIREMENT'";
                $cursor=exequery(TD::conn(),$query);
                if($ROW=mysql_fetch_array($cursor))
                    $OUT_REQUIREMENT=$ROW["PARA_VALUE"];
                if($OUT_REQUIREMENT!="")
                    echo "<br>"._("填写要求：")."<br>".str_replace(" ","&nbsp;",str_replace("\n","<br>",$OUT_REQUIREMENT));
                ?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("外出时间：")?></td>
            <td class="TableData">
                <?=_("日期")?> <input type="text" name="OUT_DATE" size="15" maxlength="10" class=" validate[required]" data-prompt-position="topRight:0,-8" value="<?=$CUR_DAY?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"/>
                <?=_("从")?> <input type="text" id="start_time" name="OUT_TIME1" size="5" maxlength="5" class="" readonly  value="<?=$CUR_TIME?>" onClick="WdatePicker({dateFmt:'HH:mm'})">
                <?=_("至")?> <input type="text" name="OUT_TIME2" size="5" maxlength="5" class="" readonly  value="<?=$OUT_TIME2?>" onClick="WdatePicker({dateFmt:'HH:mm',minDate:'#F{$dp.$D(\'start_time\')}'})"><br>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("审批人：")?></td>
            <td class="TableData">
                <select name="LEADER_ID" id="leader_id">
                    <?
                    include_once("../../manager.inc.php");
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("是否用车：")?></td>
            <td nowrap class="TableData">
                <input type="radio" name="RD" value="1" onClick="time_status('1')"><?=_("是")?>
                <input type="radio" name="RD" value="0" onClick="time_status('0')" checked="true"><?=_("否")?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("事务提醒：")?></td>
            <td class="TableData"> <?=sms_remind(6);?></td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="button" id="BUTTON" onClick='sendForm();' class="btn btn-primary" value="<?=_("申请外出")?>"  title="<?=_("申请外出")?>">&nbsp;&nbsp;
                <input type="button" class="btn" value="<?=_("返回上页")?>"  onClick="location='../'">&nbsp;&nbsp;
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery("#batch").click(function(){
        if(jQuery("#batch").prop("checked"))
        {
            jQuery("#WaiChuRenYuan2").show();
            jQuery("#WaiChuRenYuan1").hide();
        }
        else
        {
            jQuery("#WaiChuRenYuan2").hide();
            jQuery("#WaiChuRenYuan1").show();
        }
    })
});
</script>
</body>
</html>