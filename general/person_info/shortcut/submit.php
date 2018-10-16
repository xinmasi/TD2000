<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="update USER set SHORTCUT='$FLD_STR' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(), $query);

updateUserCache($_SESSION["LOGIN_UID"]);

Message(_("提示"),_("菜单快捷组定义已保存！"));

$SHORTCUT = td_trim($FLD_STR);
?>

<script>
if(window.top.shortcutArray)
{
   eval('window.top.shortcutArray = Array(<?=$SHORTCUT?>);');
}
else
{
   window.parent.parent.shortcut.location.reload();
}
</script>
<div align="center">
  <input type="button"  value="<?=_("返回")?>" class="BigButton" name="back" onClick="location='index.php?IS_MAIN=1'">
</div>
</body>
</html>