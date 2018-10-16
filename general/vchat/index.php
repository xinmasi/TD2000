<?
include_once("inc/auth.inc.php");

$host_ip=gethostbyname($_SERVER["SERVER_NAME"]);
$URL="http://$host_ip:6000";

$HTML_PAGE_TITLE = _("语音聊天室");
include_once("inc/header.inc.php");
?>

<meta http-equiv="refresh" content="3;URL=<?=$URL?>">



<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vchat.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("语音聊天室")?></span><br>
    </td>
  </tr>
</table>

<br>

<?
Message("",_("正在连接到MeChat语音聊天服务器......")."<br><br>"._("本功能是与“MeChat聊天系统”合作，需要安装MeChat聊天服务器，免费版无后台管理功能。"));
?>