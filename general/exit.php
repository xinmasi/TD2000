<?
$SESSION_WRITE_CLOSE = 0;
include_once("inc/auth.inc.php");

//lp 2014/7/10 4:27:02 退出的时候增加只退出当前的session
$query="delete from USER_ONLINE where SID = '".session_id()."'";
exequery(TD::conn(), $query);

clear_online_status();

session_start();
session_unset();
session_destroy();

$params = session_get_cookie_params();
setcookie(session_name(), session_id(), 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

$HTML_PAGE_TITLE = _("退出系统");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><span class="big3"> <?=_("感谢您使用本系统，再会！")?></span><br>
    </td>
  </tr>
</table>

<br>

<br>
<div align="center" class="big1">
<b>

<?=_("您已经安全的退出了系统，现在可以关闭浏览器")?>
</span>
</div>

<br>
<div align="center">
  <input type="button" value="<?=_("重新登录")?>" class="BigButton" onclick="location='../'">&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="<?=_("关闭浏览器")?>" class="BigButton" onclick="window.close()">
</div>
</body>
</html>
