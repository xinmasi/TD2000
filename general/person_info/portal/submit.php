<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if(MYOA_IS_DEMO)
{
   Message(_("��ʾ"),_("��ʾ�治�������Ż�"));
   Button_Back();
   exit;
}

$query="update USER set PORTAL='$FLD_STR' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(), $query);

updateUserCache($_SESSION["LOGIN_UID"]);

Message(_("��ʾ"),_("�Ż������ѱ��棡"));

$SHORTCUT = td_trim($FLD_STR);
?>

<script>

</script>
<div align="center">
  <input type="button"  value="<?=_("����")?>" class="BigButton" name="back" onClick="location='index.php?IS_MAIN=1'">
</div>
</body>
</html>