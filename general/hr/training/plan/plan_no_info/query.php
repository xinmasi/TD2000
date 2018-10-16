<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body bgcolor="#E8E8E8" topmargin="5">

<center>

 <form method="post" action="plan_no_info.php" target="plan_no_info" name="form1">
  <?=_("计划名称：")?>
  <input type="text" name="KEY_WORD" size="10" class="BigInput">
  <input type="submit" name="Submit" value="<?=_("模糊查询")?>" class="BigButton">
 </form>
</center>

</body>
</html>
