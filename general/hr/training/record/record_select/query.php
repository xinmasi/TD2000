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

 <form method="post" action="record_select.php" target="record_select" name="form1">
  <?=_("ÅàÑµÃû³Æ£º")?>
  <input type="text" name="KEY_WORD" size="10" class="BigInput">
  <input type="submit" name="Submit" value="<?=_("Ä£ºý²éÑ¯")?>" class="BigButton">
 </form>
    
    <div id="center">
    <iframe name="record_select" src="record_select.php" frameborder="NO"></iframe>
    </div>
</center>

</body>
</html>
