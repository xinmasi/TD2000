<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("Ñ¡Ôñ¼Æ»®±àºÅ");
include_once("inc/header.inc.php");
?>
<style>
html,body{
    overflow: hidden;
    height: 100%;
}
#header{
    height:40px;
    width: 100%;
    position: absolute;
    top:0;
    left:0;
    bottom:0;
    overflow: hidden;
}
#header iframe{
    width: 100%;
    height: 100%;
    display: block;
    position: absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
}
#center{
    position: absolute;
    top:40px;
    bottom:0;
    left:0;
    right:0;
    overflow: hidden;
}
#center iframe{
    width: 100%;
    height: 100%;
    display: block;
    position: absolute;
    top:0;
    bottom:30px;
    left:0;
    right:0;
}

</style>
<body>
    <div id="header">
        <iframe id="query" name="query" frameborder="0" scrolling="no" src="query.php"></iframe>
    </div>
    <div id="center">
        <iframe id="plan_no_info" name="plan_no_info" frameborder="0" src="plan_no_info.php"></iframe>
    </div>
</body>
<!--
<frameset cols="*"  rows="40,*" frameborder="YES" border="1" framespacing="0" id="bottom">
  <frame name="query" src="query.php" scrolling="NO" frameborder="YES">
  <frame name="plan_no_info" src="plan_no_info.php" frameborder="NO">
 </frameset>
</frameset>-->

</html>
