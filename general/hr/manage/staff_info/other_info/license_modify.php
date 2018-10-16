<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("证照信息修改");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
    if(document.form1.STAFF_NAME.value=="")
    {
        alert("<?=_("员工姓名不能为空！")?>");
        return (false);
    }
    if(document.form1.LICENSE_TYPE.value=="")
    {
        alert("<?=_("证件类型不能为空！")?>");
        return (false);
    }
    if(document.form1.LICENSE_NO.value=="")
    {
        alert("<?=_("证件编号不能为空！")?>");
        return (false);
    }
    if(document.form1.LICENSE_NAME.value=="")
    {
        alert("<?=_("证件名称不能为空！")?>");
        return (false);
    }
    if(document.form1.NOTIFIED_BODY.value=="")
    {
        alert("<?=_("发证机构不能为空！")?>");
        return (false);
    }
    if(document.form1.GET_LICENSE_DATE.value!="" && document.form1.EFFECTIVE_DATE.value!="" && document.form1.GET_LICENSE_DATE.value > document.form1.EFFECTIVE_DATE.value)
    {
        alert("<?=_("生效日期不能小于取证日期！")?>");
        return (false);
    }
    if(document.form1.EFFECTIVE_DATE.value!="" && document.form1.EXPIRE_DATE.value!="" && document.form1.EFFECTIVE_DATE.value >= document.form1.EXPIRE_DATE.value)
    {
        alert("<?=_("到期日期不能小于生效日期！")?>");
        return (false);
    }
    return (true);
}

function upload_attach()
{
    if(CheckForm())
    {
        document.form1.submit();
    }
}
function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
    var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
    if(window.confirm(msg))
    {
        URL="delete_attach.php?LICENSE_ID=<?=$LICENSE_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}

function expandIt()
{
    whichEl =document.getElementById("menu");
    if (document.form1.EXPIRATION_PERIOD[0].checked == true)
    {
        whichEl.style.display = '';
        document.getElementById("menu2").style.display ='';

    }
    if (document.form1.EXPIRATION_PERIOD[1].checked == true)
    {
        whichEl.style.display = 'none';
        document.getElementById("menu2").style.display ='none';
    }
}
function resetTime()
{
    document.form1.REMIND_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
}
</script>


<?
$query="select * from HR_STAFF_LICENSE where LICENSE_ID='$LICENSE_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
    $LICENSE_ID=$ROW["LICENSE_ID"];
    $USER_ID=$ROW["USER_ID"];
    $DEPT_ID=$ROW["DEPT_ID"];
    $STAFF_NAME=$ROW["STAFF_NAME"];
    $LICENSE_TYPE=$ROW["LICENSE_TYPE"];
    $LICENSE_NO=$ROW["LICENSE_NO"];
    $LICENSE_NAME=$ROW["LICENSE_NAME"];
    $NOTIFIED_BODY=$ROW["NOTIFIED_BODY"];
    $GET_LICENSE_DATE=$ROW["GET_LICENSE_DATE"];
    $EFFECTIVE_DATE=$ROW["EFFECTIVE_DATE"];
    $EXPIRATION_PERIOD=$ROW["EXPIRATION_PERIOD"];
    $EXPIRE_DATE=$ROW["EXPIRE_DATE"];
    $STATUS=$ROW["STATUS"];
    $REMARK=$ROW["REMARK"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
    $REMIND_TIME =$ROW["REMIND_TIME"];
    $ADD_TIME =$ROW["ADD_TIME"];
    $REMIND_USER =$ROW["REMIND_USER"];

    $STAFF_NAME1=substr(GetUserNameById($ROW["STAFF_NAME"]),0,-1);
    $REMIND_USER_NAME=substr(GetUserNameById($REMIND_USER),0,-1);
    if($GET_LICENSE_DATE=="0000-00-00")
        $GET_LICENSE_DATE="";
    if($EFFECTIVE_DATE=="0000-00-00")
        $EFFECTIVE_DATE="";
    if($EXPIRE_DATE=="0000-00-00")
        $EXPIRE_DATE="";
    if($REMIND_TIME=="0000-00-00 00:00:00")
        $REMIND_TIME="";
}

?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("编辑证照信息")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<form action="license_update.php"  method="post" name="form1" enctype="multipart/form-data" onsubmit="return CheckForm();">
    <table class="TableBlock" width="80%" align="center">
        <tr>
            <td nowrap class="TableData"><?=_("单位员工：")?></td>
            <td class="TableData">
                <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
                <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="<?=$STAFF_NAME1?>">&nbsp;
                <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("选择")?></a>
            </td>
            <td nowrap class="TableData"><?=_("证照类型：")?></td>
            <td class="TableData" >
                <select name="LICENSE_TYPE" style="background: white;" title="<?=_("证照类型可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
                    <option value=""><?=_("证照类型")?>&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
                    <?=hrms_code_list("HR_STAFF_LICENSE1",$LICENSE_TYPE)?>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("证照编号：")?></td>
            <td class="TableData">
                <INPUT type="text"name="LICENSE_NO" class=BigInput size="15" value="<?=$LICENSE_NO?>">
            </td>
            <td nowrap class="TableData"><?=_("证照名称：")?></td>
            <td class="TableData">
                <INPUT type="text"name="LICENSE_NAME" class=BigInput size="15" value="<?=$LICENSE_NAME?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("取证日期：")?></td>
            <td class="TableData">
                <input type="text" name="GET_LICENSE_DATE" size="15" maxlength="10" class="BigInput" value="<?=$GET_LICENSE_DATE?>" onClick="WdatePicker()"/>
            </td>
            <td nowrap class="TableData"><?=_("生效日期：")?></td>
            <td class="TableData">
                <input type="text" name="EFFECTIVE_DATE" size="15" maxlength="10" class="BigInput" value="<?=$EFFECTIVE_DATE?>" onClick="WdatePicker()"/>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("状态：")?></td>
            <td class="TableData">
                <select name="STATUS" style="background: white;" title="<?=_("状态可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
                    <option value=""><?=_("状态")?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
                    <?=hrms_code_list("HR_STAFF_LICENSE2",$STATUS)?>
                </select>
            </td>
            <td nowrap class="TableData"><?=_("期限限制：")?></td>
            <td class="TableData">
                <INPUT type="radio" name="EXPIRATION_PERIOD" onclick="expandIt()" value="1" <?if($EXPIRATION_PERIOD=="1") echo "checked";?>> <?=_("是")?>&nbsp;&nbsp;
                <INPUT type="radio" name="EXPIRATION_PERIOD" onclick="expandIt()" value="0" <?if($EXPIRATION_PERIOD=="0") echo "checked";?>> <?=_("否")?>
            </td>
        </tr>
        <tr style="display:<?if($EXPIRATION_PERIOD==0) echo "none"; ?>" id="menu">
            <td nowrap class="TableData"><?=_("到期日期：")?></td>
            <td class="TableData">
                <input type="text" name="EXPIRE_DATE" size="15" maxlength="10" class="BigInput" value="<?=$EXPIRE_DATE?>" onClick="WdatePicker()"/>
            </td>
            <td nowrap class="TableData"> <?=_("提醒时间：")?></td>
            <td class="TableData">
                <input type="text" name="REMIND_TIME" size="20" maxlength="20" class="BigInput" value="<?=$REMIND_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("置为当前时间")?></a>
                <?=_("（为空则不提醒）")?>
            </td>
        </tr>
        <tr style="display:<?if($EXPIRATION_PERIOD==0) echo "none"; ?>" id="menu2">
            <td nowrap class="TableData"><?=_("提醒人员：")?></td>
            <td class="TableData" colspan=3>
                <input type="hidden" name="TO_ID" id="TO_ID" value="<?=$REMIND_USER?>">
                <textarea name="TO_NAME" id="TO_NAME" cols="82" rows="3"  class="SmallStatic" wrap="yes" readonly><?=$REMIND_USER_NAME?></textarea>
                <a href="#" class="orgAdd" onClick="SelectUser('27','','TO_ID', 'TO_NAME')" title="<?=_("添加提醒人员")?>"><?=_("添加")?></a>
                <a href="#" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')" title="<?=_("清空提醒人员")?>"><?=_("清空")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("发证机构：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="NOTIFIED_BODY" cols="70" rows="3" class="BigInput" value=""><?=$NOTIFIED_BODY?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("备注：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="REMARK" cols="70" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
            </td>
        </tr>
        <tr class="TableData" id="attachment2">
            <td nowrap><?=_("附件文档：")?></td>
            <td nowrap colspan=3>
                <?
                if($ATTACHMENT_ID=="")
                    echo _("无附件");
                else
                    echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);
                ?>
            </td>
        </tr>
        <tr height="25" id="attachment1">
            <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
            <td class="TableData" colspan=3>
                <script>ShowAddFile();</script>
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("提醒：")?></td>
            <td class="TableData" colspan=3>
                <?=sms_remind(59);?>
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan=4 nowrap>
                <input type="hidden" value="<?=$LICENSE_ID?>" name="LICENSE_ID">
                <input type="submit" value="<?=_("保存")?>" class="BigButton">
                <!--<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">-->
            </td>
        </tr>
    </table>
</form>
</body>
</html>