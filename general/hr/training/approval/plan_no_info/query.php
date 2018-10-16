<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>



<style>
#center iframe{
    width: 100%;
    height: 100%;
    display: block;
    position: absolute;
    top:30px;
    bottom:0;
    left:0;
    right:0;
}
</style>

<body bgcolor="#E8E8E8" topmargin="5">

<center>

 <form method="post" action="plan_no_info.php" target="plan_no_info" name="form1">
  <?=_("计划名称：")?>
  <input type="text" name="KEY_WORD" size="10" class="BigInput">
  <input type="submit" name="Submit" value="<?=_("模糊查询")?>" class="BigButton">
 </form>
</center>
<div id="center">
    <iframe name="plan_no_info" src="plan_no_info.php" frameborder="NO"></iframe>
</div>
</body>
</html>
