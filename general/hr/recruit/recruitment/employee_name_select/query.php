<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body bgcolor="#E8E8E8" topmargin="5">

<center>

 <form method="post" action="employee_name_info.php" target="employee_name_info" name="form1">
  <?=_("编号/应聘者姓名")?>
  <input type="text" name="KEY_WORD" size="10" class="BigInput">
  <input type="submit" name="Submit" value="<?=_("模糊查询")?>" class="BigButton">
 </form>
</center>

</body>
</html>
