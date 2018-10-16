<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if(MYOA_IS_DEMO)
{
   Message(_("提示"),_("演示版不能设置门户"));
   Button_Back();
   exit;
}

$query="update USER set PORTAL='$FLD_STR' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(), $query);

updateUserCache($_SESSION["LOGIN_UID"]);

Message(_("提示"),_("门户设置已保存！"));

$SHORTCUT = td_trim($FLD_STR);
?>

<script>

</script>
<div align="center">
  <input type="button"  value="<?=_("返回")?>" class="BigButton" name="back" onClick="location='index.php?IS_MAIN=1'">
</div>
</body>
</html>