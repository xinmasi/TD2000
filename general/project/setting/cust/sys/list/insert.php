<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");

$CONFIG_DATA  = "PROJ_NUM=".$PROJ_NUM."\r\n";
$CONFIG_DATA .= "PROJ_OWNER_NAME=".$PROJ_OWNER_NAME."\r\n";
$CONFIG_DATA .= "PROJ_START_TIME=".$PROJ_START_TIME."\r\n";
$CONFIG_DATA .= "PROJ_END_TIME=".$PROJ_END_TIME."\r\n";
$CONFIG_DATA .= "PROJ_ACT_END_TIME=".$PROJ_ACT_END_TIME."\r\n";
$CONFIG_DATA .= "PROJ_GLOBAL_VAL=".$PROJ_GLOBAL_VAL."\r\n";

$CONFIG_FILE = MYOA_ATTACH_PATH."config/project_list.ini";
if(td_file_put_contents($CONFIG_FILE, $CONFIG_DATA))
{
	Message("",_("�޸ĳɹ�!"));
	Button_Back();
}
else
{
	Message(_("��ʾ"),_("�޸�ʧ�ܣ������ļ�����ʧ��!"));
	Button_Back();
}
?>

