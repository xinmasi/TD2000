<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;
$PAGE_SIZE = 10;
if(!isset($start) || $start=="")
    $start=0;

$CUR_YEAR=date("Y");
$CUR_MON=date("n");

$NEXT_MONE=date('Y-m',strtotime('+1 month'));
$NEXT_MONE_ARRAY=explode("-",$NEXT_MONE);
$NEXT_CUR_MON=$NEXT_MONE_ARRAY[1];
$HTML_PAGE_TITLE = _("员工关怀提醒");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
function check_one(el)
{
    if(!el.checked)
        document.getElementsByName("allbox").item(0).checked=false;
}

function check_all()
{
    for (i=0;i<document.getElementsByName("sms_select").length;i++)
    {
        if(document.getElementsByName("allbox").item(0).checked)
        {
            if(!document.getElementsByName("sms_select").item(i).disabled)
                document.getElementsByName("sms_select").item(i).checked=true;
            else
                document.getElementsByName("sms_select").item(i).checked=false;
        }
        else
            document.getElementsByName("sms_select").item(i).checked=false;
    }

    if(i==0)
    {
        if(document.getElementsByName("allbox").item(0).checked && document.getElementsByName("sms_select").disabled==false)
            document.getElementsByName("sms_select").checked=true;
        else
            document.getElementsByName("sms_select").checked=false;
    }
}

function Send_To()
{
    TO_ID="";
    for(i=0;i<document.getElementsByName("sms_select").length;i++)
    {

        el=document.getElementsByName("sms_select").item(i);
        if(el.checked)
        {  val=el.value;
            TO_ID+=val + ",";
        }
    }

    if(i==0)
    {
        el=document.getElementsByName("sms_select");
        if(el.checked)
        {  val=el.value;
            TO_ID+=val + ",";
        }
    }

    if(TO_ID=="")
    {
        alert("<?=_("请至少选择一个客户")?>");
        return;
    }
    URL="/general/mobile_sms/new/?TO_ID1=" + TO_ID;
    window.location=URL;
}

function mobile_sms(MOBIL_NO_STR)
{
    URL="/general/mobile_sms/new/?TO_ID1="+MOBIL_NO_STR;
    myleft=(screen.availWidth-650)/2;
    window.open(URL,"mobile_sms","height=350,width=650,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/care.gif" align="absmiddle" width="17"><span class="big3"><?=sprintf(_("%s年%s月"), $CUR_YEAR, $CUR_MON)?>-<?=sprintf(_("%s年%s月"), $NEXT_MONE_ARRAY[0], $NEXT_MONE_ARRAY[1])?><?=_("生日的员工")?></span>
        </td>
    </tr>
</table>

<?
$query = "SELECT f.DEPT_NAME,b.USER_ID,b.USER_NAME,b.SEX,a.STAFF_EMAIL,a.STAFF_BIRTH,a.STAFF_MOBILE,b.MOBIL_NO,b.BIRTHDAY,b.EMAIL
         from HR_STAFF_INFO a
         LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
         LEFT OUTER JOIN DEPARTMENT f ON b.DEPT_ID=f.DEPT_ID
         LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where (b.DEPT_ID != '0' and Month(a.STAFF_BIRTH)='$CUR_MON' or Month(a.STAFF_BIRTH)='$NEXT_CUR_MON') ".$WHERE_STR."ORDER BY STAFF_BIRTH ASC";
$cursor= exequery(TD::conn(),$query, $connstatus);
while($ROW=mysql_fetch_array($cursor))
{
    $CARE_COUNT++;

    if($CARE_COUNT==1)
    {
?>
<table class="TableList" width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("选择")?></td>
        <td nowrap align="center"><?=_("员工姓名")?></td>
        <td nowrap align="center"><?=_("部门")?></td>
        <td nowrap align="center"><?=_("性别")?></td>
        <td nowrap align="center"><?=_("生日")?></td>
        <td nowrap align="center"><?=_("手机")?></td>
        <td nowrap align="center"><?=_("电子邮件")?></td>
    </tr>
    <?
    }

    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $EMAIL_TMP=$ROW["STAFF_EMAIL"];
    $SEX1=$ROW["SEX"];
    $BIRTHDAY_TMP=$ROW["STAFF_BIRTH"];
    $MOBILE_TMP=$ROW["MOBIL_NO"];
    $STAFF_MOBILE_HRMS=$ROW["STAFF_MOBILE"];//HRMS中的手机号码
    $EMAIL_USER=$ROW["EMAIL"];//USER表中的电子邮件
    $BIRTHDAY_USER=$ROW["BIRTHDAY"];//USER表中的出生日期
    $DEPT_NAME=$ROW["DEPT_NAME"];

    if($MOBILE_TMP!="" && $STAFF_MOBILE_HRMS=="")
        $MOBILE1=$MOBILE_TMP;
    elseif($MOBILE_TMP=="" && $STAFF_MOBILE_HRMS!="")
        $MOBILE1=$STAFF_MOBILE_HRMS;
    elseif($MOBILE_TMP!="" && $STAFF_MOBILE_HRMS!="")//如果人资资源和用户管理中用户手机号码都不为空，以用户管理为准
        $MOBILE1=$MOBILE_TMP;
    else
        $MOBILE1="";

    if($BIRTHDAY_USER!="" && $BIRTHDAY_TMP=="")
        $BIRTHDAY1=$BIRTHDAY_USER;
    elseif($BIRTHDAY_USER=="" && $BIRTHDAY_TMP!="")
        $BIRTHDAY1=$BIRTHDAY_TMP;
    elseif($BIRTHDAY_USER!="" && $BIRTHDAY_TMP!="")//如果人资资源和用户管理中用户出生日期都不为空，以人事档案处为准
        $BIRTHDAY1=$BIRTHDAY_TMP;
    else
        $BIRTHDAY1="";

    if($EMAIL_USER!="" && $EMAIL_TMP=="")
        $EMAIL1=$EMAIL_USER;
    elseif($EMAIL_USER=="" && $EMAIL_TMP!="")
        $EMAIL1=$EMAIL_TMP;
    elseif($EMAIL_USER!="" && $EMAIL_TMP!="")//如果人资资源和用户管理中用户EMAIL地址都不为空，以用户管理处为准
        $EMAIL1=$EMAIL_USER;
    else
        $EMAIL1="";

    if($SEX1=="0")
        $SEX_DESC=_("男");
    else
        $SEX_DESC=_("女");
    if($CARE_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
    ?>
    <tr class="<?=$TableLine?>" >
        <td>&nbsp;<input type="checkbox" <?if($MOBILE1=="") echo "DISABLED"?>  name="sms_select" value="<?=$MOBILE1?>" onClick="check_one(self);"></td>
        <td nowrap align="center"><a href="javascript:;" onClick="window.open('../staff_info/staff_detail.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=$USER_NAME?></a>&nbsp;</td>
        <td nowrap align="center"><?=$DEPT_NAME?></td>
        <td nowrap align="center"><?=$SEX_DESC?></td>
        <td nowrap align="center"><?=$BIRTHDAY1=="0000-00-00"?"":$BIRTHDAY1;?></td>
        <td nowrap align="center"><?if($MOBILE1=="") echo _("暂无手机号码");else echo '<a href="/general/mobile_sms/?TO_ID1='.$MOBILE1.'">'.$MOBILE1.'</a>';?></td>
        <td nowrap align="center"><?=$EMAIL1?></td>
    </tr>
    <?
    }//while

    if($CARE_COUNT==0)
    {
        Message(_("提示"),_("本月没有员工过生日"));
    }
    else
    {
    ?>
    <tr class="TableControl" <? if($SCOPE=="2") echo "style=display:none" ?>>
        <td colspan="10">
            &nbsp;<input type="checkbox" name="allbox" id="allbox_for"  onClick="check_all();">
            <label for="allbox_for"><?=_("全选")?></label> &nbsp;
            <input type="button" name="send" value="<?=_("发送手机短信")?>" class="BigButton" onClick="Send_To()">
</table>
<?
}
?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<br>

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/notify.gif" align="absmiddle"><span class="big3"> <?=_("员工关怀提醒")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br>
<form enctype="multipart/form-data" action="update_task.php"  method="post" name="form1" >
    <table class="TableBlock" width="60%" align="center">
        <tr>
            <td nowrap class="TableData"><?=_("提醒对象：")?></td>
            <td class="TableData">
                <input type="hidden" name="REMINDER" readonly value="<?=$REMINDER?>">
                <textarea cols=40 name="REMINDER_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$REMINDER_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','REMINDER', 'REMINDER_NAME','1')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('REMINDER', 'REMINDER_NAME')"><?=_("清空")?></a>
            </td>
        <tr>
        <tr>
            <td nowrap class="TableData"> <?=_("提醒：")?></td>
            <td class="TableData" colspan=3>
                <input type="checkbox" name="SMS_REMIND"><label for="SMS_REMIND"><?=_("事务提醒")?></label>
                <input type="checkbox" name="SMS2_REMIND"><label for="SMS2_REMIND"><?=_("手机短信")?></label>
            </td>
        </tr>
        <td nowrap class="TableData"><?=_("提醒内容：")?></td>
        <td class="TableData" colspan=3>
            <textarea name="CONTENT" cols="66" rows="5" class="BigInput" ><?=$CONTENT?></textarea>
        </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan=4 nowrap>
                <input type="submit" value="<?=_("保存")?>" class="BigButton">
            </td>
        </tr>
    </table>
</form>
</body>
</html>