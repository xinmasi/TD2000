<?
include_once("inc/auth.inc.php");
//include_once("./super_pass/pass_check_common.php");

$HTML_PAGE_TITLE = _("输入超级密码");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script Language="JavaScript">
function pass_check()
{
   var SALARY_PASS = $("SALARY_PASS").value;
   $("SALARY_PASS_MSG").innerHTML = "<img src='<?=MYOA_STATIC_SERVER?>/static/images/loading_16.gif' align='absMiddle'> 检查中，请稍候……";
	_get("pass_check.php","SALARY_PASS="+SALARY_PASS, show_check_msg);
}

function show_check_msg(req)
{
    if(req.status==200)
    {
        if(req.responseText.indexOf('OK') > 0)
        {
            window.location="index1.php";
        }
        else
        {
            $("SALARY_PASS_MSG").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'> <?=_("密码错误，请重试")?>";
        }
    }
    else
    {
        $("SALARY_PASS_MSG").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'> <?=_("错误：")?>"+req.status;
    }
}
</script>

<body class="bodycolor" onLoad="$('SALARY_PASS').focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"><?=_("输入超级密码")?></span>
        </td>
    </tr>
</table>
<form method="post">
    <table class="TableBlock" width="50%" align="center">
        <tr>
            <td colspan=2>
                <b><?=_("说明：")?></b><?=_("第一次进入时密码为空，进入后即可看到“超级密码设置”")?>
            </td>
        </tr>
        <tr>
            <td class="TableContent">
                <?=_("请输入超级密码：")?>
            </td>
            <td class="TableData">
                <input type="password" id="SALARY_PASS" name="SALARY_PASS"  class="BigInput" size="30" onKeyPress="if(event.keyCode==13) pass_check();">
                <br>
                <span id="SALARY_PASS_MSG"></span><!-- 如果密码输错，在这里给出提示 -->
            </td>
        </tr>
        <tr>
            <td nowrap class="TableControl" align="center" colspan="2">
                <input class="BigButton" onClick="pass_check();" type="button" value="<?=_("确定")?>"/>
            </td>
        </tr>
    </table>
</form>
</body>
</html>