<?
$SESSION_WRITE_CLOSE = 0;
include_once("inc/auth.inc.php");

//lp 2014/7/10 4:27:02 �˳���ʱ������ֻ�˳���ǰ��session
$query="delete from USER_ONLINE where SID = '".session_id()."'";
exequery(TD::conn(), $query);

clear_online_status();

session_start();
session_unset();
session_destroy();

$params = session_get_cookie_params();
setcookie(session_name(), session_id(), 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

$HTML_PAGE_TITLE = _("�˳�ϵͳ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><span class="big3"> <?=_("��л��ʹ�ñ�ϵͳ���ٻᣡ")?></span><br>
    </td>
  </tr>
</table>

<br>

<br>
<div align="center" class="big1">
<b>

<?=_("���Ѿ���ȫ���˳���ϵͳ�����ڿ��Թر������")?>
</span>
</div>

<br>
<div align="center">
  <input type="button" value="<?=_("���µ�¼")?>" class="BigButton" onclick="location='../'">&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="<?=_("�ر������")?>" class="BigButton" onclick="window.close()">
</div>
</body>
</html>
