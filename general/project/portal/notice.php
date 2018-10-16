<?
/**
*   proj_notice.php文件
*
*   文件内容描述：
*   1、页面消息提示；
*
*   @author  韦晟
*
*   @edit_time  2013/09/16
*
*/
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<body>
<br><br><br><br>
<?
Message("", _("试用版暂不开放此功能，敬请期待正式版"));
if($_GET['type']=='back')
{
    Button_Back();
}
?>
</body>
</html>