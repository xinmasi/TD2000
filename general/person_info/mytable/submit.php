<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="update USER set $POS='$FLD_STR' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(), $query);

updateUserCache($_SESSION["LOGIN_UID"]);

Message(_("��ʾ"),_("���涨���ѱ��棡"));
?>

<div align="center">
  <input type="button"  value="<?=_("����")?>" class="BigButton" name="back" onClick="location='config.php?POS=<?=$POS?>&IS_MAIN=1'">
</div>
</body>
</html>