<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("calIntegral.class.php");
?>
<body class="bodycolor">
<?
$DO_LIST=explode(',',trim($autoinfo,','));
//print_r($DO_LIST);
//exit();
if(isset($DO_LIST)){
	$aa=new calIntegral($DO_LIST);
	Message(_("��ʾ"),_("�Ѽ�����ѡģ��Ļ��֣�"));
	Button_back();
}
else
{
	Message(_("��ʾ"),_("û��ѡ����Ŀ��"));
	Button_back();
}
?>
