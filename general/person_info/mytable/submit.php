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

Message(_("提示"),_("桌面定义已保存！"));
?>

<div align="center">
  <input type="button"  value="<?=_("返回")?>" class="BigButton" name="back" onClick="location='config.php?POS=<?=$POS?>&IS_MAIN=1'">
</div>
</body>
</html>