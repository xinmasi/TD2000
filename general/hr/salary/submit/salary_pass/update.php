<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//include_once("pass_check_common.php");

$HTML_PAGE_TITLE = _("修改超级密码");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<?
if(MYOA_IS_DEMO)
{
   Message(_("提示"), _("演示版不能修改超级密码"));
   Button_Back();
   exit;
}

//-------------输入合法性检验-------------------------------------------------
if($PASS1!=$PASS2)
{
  Message(_("错误"),_("两次输入的新密码不一致！"));
  Button_back();
  exit;
}

if(strstr($PASS1,"\'")!=false)
{
  Message(_("错误"),_("新密码中含有非法字符"));
  Button_back();
  exit;
}

if($PASS1==$PASS0)
{
  Message(_("错误"),_("新密码不能与原密码相同！"));
  Button_back();
  exit;
}

$SYS_PARA_ARRAY = get_sys_para("SALARY_PASS");
$SUPER_PASS=$SYS_PARA_ARRAY["SALARY_PASS"]; 

if(crypt($PASS0, $SUPER_PASS)!= $SUPER_PASS)
{
  Message(_("错误"),_("输入的原超级密码错误!"));
  Button_back();
  exit;
}

$PASS1=crypt($PASS1);
set_sys_para(array("SALARY_PASS" => "$PASS1"));

Message(_("提示"),_("系统超级密码已修改!"));
?>

<div align="center">
 <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php'">
</div>

</body>
</html>
