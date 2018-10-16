<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("Ñ¡ÔñÈËÔ±");
include_once("inc/header.inc.php");
?>
<style>
html,body{
    overflow: hidden;
    height: 100%;
}
#west{
    width:200px;
    position: absolute;
    top:0;
    left:0;
    bottom:0;
    overflow: hidden;
    overflow-y: auto;
}
#west iframe{
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
    top:0px;
    bottom:30px;
    left:200px;
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
#footer{
    position: absolute;
    height: 30px;
    bottom:0;
    left:200px;
    right:0;
    overflow: hidden;
}
#footer iframe{
    width: 100%;
    height: 100%;
    display: block;
    position: absolute;
    bottom:0;
    left:0;
    right:0;
}

* html{
    padding-right: 200px;
}
* html #west{
    height:100%;
}
* html #center{
    position: relative;
}
</style>
<!--
<frameset rows="*,30"  rows="*" frameborder="NO" border="1" framespacing="0" id="bottom">
   <frameset cols="155,*"  rows="*" frameborder="YES" border="1" framespacing="0" id="bottom">
      <frame name="dept" src="dept.php?FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>">
      <frame name="user" src="blank.php">
   </frameset>
   <frame name="control" scrolling="no" src="control.php">
</frameset>

-->
<body>
    <div id="west">
        <iframe id="dept" name="dept" frameborder="0" src="dept.php?FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>"></iframe>
    </div>
    <div id="center">
        <iframe id="user" name="user" frameborder="0" src="blank.php"></iframe>
    </div>
    <div id="footer">
        <iframe id="control" name="control" scrolling="no" frameborder="0" src="control.php"></iframe>
    </div>
</body>