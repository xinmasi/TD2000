<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//include_once("pass_check_common.php");

$HTML_PAGE_TITLE = _("�޸ĳ�������");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<?
if(MYOA_IS_DEMO)
{
   Message(_("��ʾ"), _("��ʾ�治���޸ĳ�������"));
   Button_Back();
   exit;
}

//-------------����Ϸ��Լ���-------------------------------------------------
if($PASS1!=$PASS2)
{
  Message(_("����"),_("��������������벻һ�£�"));
  Button_back();
  exit;
}

if(strstr($PASS1,"\'")!=false)
{
  Message(_("����"),_("�������к��зǷ��ַ�"));
  Button_back();
  exit;
}

if($PASS1==$PASS0)
{
  Message(_("����"),_("�����벻����ԭ������ͬ��"));
  Button_back();
  exit;
}

$SYS_PARA_ARRAY = get_sys_para("SALARY_PASS");
$SUPER_PASS=$SYS_PARA_ARRAY["SALARY_PASS"]; 

if(crypt($PASS0, $SUPER_PASS)!= $SUPER_PASS)
{
  Message(_("����"),_("�����ԭ�����������!"));
  Button_back();
  exit;
}

$PASS1=crypt($PASS1);
set_sys_para(array("SALARY_PASS" => "$PASS1"));

Message(_("��ʾ"),_("ϵͳ�����������޸�!"));
?>

<div align="center">
 <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">
</div>

</body>
</html>
