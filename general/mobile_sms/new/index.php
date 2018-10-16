<?
include_once("inc/auth.inc.php");
include_once("inc/td_core.php");
include_once("inc/utility_msg.php");

if(!function_exists('mb_substr'))
{
    $THE_PATH2=realpath(MYOA_ROOT_PATH."../")."\\bin\\";
    $msg=sprintf(_("1、确认文件%sphp_mbstring.dll存在；")."<br>"._("2、请用记事本打开OA服务器中的%sphp.ini文件；")."<br>"._("3、在文件中查找“;extension=php_mbstring.dll”，删除前面的“;”符号，然后保存并关闭文件；")."<br>"._("4、重新启动Office_Anywhere服务。"),$THE_PATH,$THE_PATH2);
    //Message (_("请联系管理员开启mb_string扩展"),_("1、确认文件").$THE_PATH._("php_mbstring.dll存在；<br>2、请用记事本打开OA服务器中的").$THE_PATH2._("php.ini文件；<br>3、在文件中查找“;extension=php_mbstring.dll”，删除前面的“;”符号，然后保存并关闭文件；<br>4、重新启动Office_Anywhere服务。"));
    Message (_("请联系管理员开启mb_string扩展"),$msg);
    exit;
}

//-- 允许外发 --
$query = "select * from SMS2_PRIV";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $OUT_PRIV=$ROW["OUT_PRIV"];
}

if(find_id($OUT_PRIV,$_SESSION["LOGIN_USER_ID"]))
{
    $SMS_ROLE=1;
}

$HTML_PAGE_TITLE = _("公费手机短信");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
    if(document.form1.TO_ID.value==""<?if($SMS_ROLE==1){?> && document.form1.TO_ID1.value==""<?}?>)
    {
        alert("<?=_("请添加收信人！")?>");
        return (false);
    }
    
    if(document.form1.CONTENT.value=="")
    {
        alert("<?=_("短信内容不能为空！")?>");
        return (false);
    }
    
    if(document.form1.CONTENT.value.length > 70)
    {
        msg='<?=_("您的短信内容超过70字，将被拆分为多条发送，是否继续发送？")?>';
        
        if(window.confirm(msg))
        {
            return (true);
        }
        else
        {
            return (false);
        }
    }
    
    return (true);
}

function notice()
{
    msg="<?=_("注意：")?>\n\n<?=_("所发送的手机短信将在本系统中进行记录，")?>\n<?=_("请勿发送与工作无关的涉及个人隐私的信息，")?>\n<?=_("请提醒接收方：其直接回复的信息也可能导致隐私泄露。")?>";
    alert(msg);
}

function CheckSend()
{
    if(event.keyCode==10)
    {
        if(CheckForm())
        {
            document.form1.submit();
        }
    }
}

var cap_max=200;

function getLeftChars(varField)
{
    var i = 0;
    var counter = 0;
    var cap = cap_max;
    var leftchars = cap - varField.value.length;
    
    return (leftchars);
}

function  ce_len(str)
{
    var celen=0;
    for(var k=0;k< str.length;k++)
    {
        if(str.charAt(k)>'~')
        {
            celen+=2;
        }
        else
        {
            celen++;
        }
    }
    return celen;
}

function onCharsChange(varField)
{
    var leftChars = getLeftChars(varField);
    if ( leftChars >= 0)
    {
        document.form1.charsmonitor1.value=cap_max-leftChars;
        document.form1.charsmonitor2.value=leftChars;
        return true;
    }
    else
    {
        document.form1.charsmonitor1.value=cap_max;
        document.form1.charsmonitor2.value="0";
        window.alert("<?=_("短信内容超过字数限制！")?>");
        var len = document.form1.CONTENT.value.length + leftChars;
        document.form1.CONTENT.value = document.form1.CONTENT.value.substring(0, len);
        leftChars = getLeftChars(document.form1.CONTENT);
        if ( leftChars >= 0)
        {
            document.form1.charsmonitor1.value=cap_max-leftChars;
            document.form1.charsmonitor2.value=leftChars;
        }
        return false;
    }
}

<?
$query = "SELECT USER_NAME,DEPT_NAME from USER,DEPARTMENT where USER.DEPT_ID=DEPARTMENT.DEPT_ID AND USER.USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $USER_NAME=$ROW["USER_NAME"];
    $DEPT_NAME=$ROW["DEPT_NAME"];   
}
?>

function LoadDo()
{
<?
    if($CONTENT=="")
    {
?>
        SignName();
<?
    }
    else
    {
?>
        onCharsChange(document.form1.CONTENT);
        document.form1.CONTENT.focus();
<?
    }
?>
}

function SignName()
{
    document.form1.CONTENT.value+="<?=$USER_NAME?>(<?=$DEPT_NAME?>):";
    onCharsChange(document.form1.CONTENT);
    document.form1.CONTENT.focus();
}

function ClearContent()
{
    document.form1.CONTENT.value="";
    onCharsChange(document.form1.CONTENT);
    document.form1.CONTENT.focus();
}

function LoadWindow3()
{
    URL="/module/addr_select/?FIELD=MOBIL_NO&TO_ID=TO_ID1";
    loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
    //window.open(URL,"read_notify","height=400,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=150,resizable=yes");
    if(window.showModalDialog){
    	window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:450px;dialogHeight:350px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
	}else{
		window.open(URL,"load_dialog_win","height=350,width=450,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
	}
}

function ClearSendUser()
{
    document.form1.TO_ID1.value="";
}

</script>

<body class="bodycolor" onload="LoadDo();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/mobile_sms.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("发送手机短信")?><?if($SMS_ROLE!=1)echo _("（无外发权限）");?></span>
        </td>
    </tr>
</table>
<br>

<form action="submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
<table class="TableBlock" width="650" align="center">
    <tr>
        <td nowrap class="TableData"><?=_("收信人[内部用户]：")?></td>
        <td nowrap class="TableData">
            <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
            <textarea cols=55 name=TO_NAME rows=3 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
            <a href="javascript:;" class="orgAdd" onClick="SelectUser('42','','TO_ID', 'TO_NAME',1)"><?=_("添加")?></a>
            <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("清空")?></a>
        </td>
    </tr>

<?
if($SMS_ROLE==1)
{
?>
    <tr>
        <td nowrap class="TableData"><?=_("收信人[外部号码]：")?></td>
        <td class="TableData">
            <?=_("号码之间请用逗号分隔或每行一条")?><br>
            <textarea cols=55 name=TO_ID1 rows=3 class="BigInput" wrap="yes"><?=$TO_ID1?></textarea>
            <a href="javascript:;" class="orgAdd" onClick="LoadWindow3()" title="<?=_("从通讯簿添加收信人")?>"><?=_("添加")?></a>
            <a href="javascript:;" class="orgClear" onClick="ClearSendUser()"><?=_("清空")?></a>
        </td>
    </tr>
<?
}
?>
    <tr>
        <td nowrap class="TableData"> <?=_("短信内容：")?></td>
        <td class="TableData">
<?
            $msg1=sprintf(_("已输入%s字符，剩余%s字符，每条70字，最多可拆分成3条发送"),"<input class='SmallStatic' type=text name=charsmonitor1 size=3 readonly=true style='height:26px;'>","<input class='SmallStatic' type=text name=charsmonitor2 size=3 readonly=true style='height:26px;'>");
?>
            <?=$msg1 ?><br>
            <textarea cols=70 name="CONTENT" rows=5 class="BigInput" wrap="on" onpaste="return onCharsChange(this);" onKeyUp="return onCharsChange(this);" onkeypress="CheckSend()"><?=$CONTENT?></textarea>
            <br><?=_("按Ctrl+回车键发送消息")?> &nbsp;<a href="javascript:notice();"><?=_("隐私警示")?></a>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("发送时间：")?></td>
        <td class="TableData">
            <input type="text" name="SEND_TIME" size="19" maxlength="19" class="BigInput" value="<?=date("Y-m-d H:i:s",time())?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        </td>
    </tr>
    <tr align="center" class="TableControl">
        <td colspan="2" nowrap>
            <input type="submit" value="<?=_("发送")?>" class="BigButton">&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" value="<?=_("签名")?>" class="BigButton" onclick="SignName()">&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" value="<?=_("清空内容")?>" class="BigButton" onclick="ClearContent()">
        </td>
    </tr>
</table>
</form>
<br>

<?
$OPT_NAME="SMS";
$OPT_ALERT=_("手机短信组件尚未注册，如需购买，请联系开发厂商");
if(!tdoa_optional($OPT_NAME))
{
    echo "<div align=center style='font-size:9pt;color:gray'>$OPT_ALERT</div>";
    
    if(rand(0,100)<=10)
    {
        echo "<script>alert('$OPT_ALERT');</script>";
    }
}
?>

</body>
</html>