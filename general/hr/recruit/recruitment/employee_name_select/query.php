<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body bgcolor="#E8E8E8" topmargin="5">

<center>

 <form method="post" action="employee_name_info.php" target="employee_name_info" name="form1">
  <?=_("���/ӦƸ������")?>
  <input type="text" name="KEY_WORD" size="10" class="BigInput">
  <input type="submit" name="Submit" value="<?=_("ģ����ѯ")?>" class="BigButton">
 </form>
</center>

</body>
</html>