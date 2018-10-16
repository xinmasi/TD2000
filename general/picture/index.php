<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("Í¼Æ¬ä¯ÀÀ");
include_once("inc/header.inc.php");
?>
<style>
html,body{height:100%;}
.picture_wrapper{
    position: relative;
    height: 100%;
}
.picture_l{
    position: absolute;
    top: 0;
    left: 0;
    width: 200px;
    height: 100%;
}
.picture_r{
    margin-left: 201px;
    border-left: 1px solid #ddd;
    height: 100%;
}
</style>
<div class="picture_wrapper">
    <div class="picture_l">
        <iframe name="PRO_LIST" id="PRO_LIST" src="pro_list.php" noresize frameborder="0" style="height:100%;width:100%"></iframe>
    </div>
    <div class="picture_r">
        <iframe name="list" src="index3.php" noresize frameborder="0" style="height:100%;width:100%"></iframe>
    </div>
</div>

<!--
<frameset rows="*" cols="*" frameborder="YES" border="0" framespacing="0">
  <frameset name="test" rows="*" cols="200,*" frameborder="YES" border="0" framespacing="0">
    <frame name="PRO_LIST" id="PRO_LIST" scrolling="auto" frameborder="YES" noresize src="pro_list.php">
    <frame name="list" scrolling="yes" noresize src="index3.php" frameborder="YES" style="border-left: 1px solid #DDD;">
  </frameset>
</frameset>
-->

</html>
