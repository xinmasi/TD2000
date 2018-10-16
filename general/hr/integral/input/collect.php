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
	Message(_("提示"),_("已计算所选模块的积分！"));
	Button_back();
}
else
{
	Message(_("提示"),_("没有选择项目！"));
	Button_back();
}
?>
