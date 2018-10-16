<?
include_once("inc/auth.inc.php");

if($_SESSION["LOGIN_NOT_VIEW_USER"])
{
   Message("",_("无查看用户的权限"),"blank");
   exit;
}
if($TO_ID=="" || $TO_ID=="undefined")
{
   $TO_ID="TO_ID";
   $TO_NAME="TO_NAME";
}
if($MANAGE_FLAG=="undefined")
   $MANAGE_FLAG="";
if($MODULE_ID=="undefined")
   $MODULE_ID="";
if($FORM_NAME=="" || $FORM_NAME=="undefined")
   $FORM_NAME="form1";

$HTML_PAGE_TITLE = _("选择人员");
include_once("inc/header.inc.php");
?>

<style>
html,body{
    overflow: hidden;
    height: 100%;
}
#west{
    width:160px;
    position: absolute;
    top:0;
    left:0;
    bottom:0;
    overflow: auto;
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
    left:160px;
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
    left:160px;
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
    padding-right: 160px;
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
  <frameset cols="200,*"  rows="*" frameborder="YES" border="1" framespacing="0">
     <frame name="dept" src="dept.php?MODULE_ID=<?=$MODULE_ID?>&TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&MANAGE_FLAG=<?=$MANAGE_FLAG?>&FORM_NAME=<?=$FORM_NAME?>">
     <frame name="user" src="user.php?MODULE_ID=<?=$MODULE_ID?>&TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&MANAGE_FLAG=<?=$MANAGE_FLAG?>&FORM_NAME=<?=$FORM_NAME?>">
  </frameset>
   <frame name="control" scrolling="no" src="control.php?TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&MANAGE_FLAG=<?=$MANAGE_FLAG?>&FORM_NAME=<?=$FORM_NAME?>">
</frameset>-->

<body>
    <div id="west">
        <iframe id="dept" name="dept" frameborder="0" src="dept.php?MODULE_ID=<?=$MODULE_ID?>&TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&MANAGE_FLAG=<?=$MANAGE_FLAG?>&FORM_NAME=<?=$FORM_NAME?>"></iframe>
    </div>
    <div id="center">
        <iframe id="user" name="user" frameborder="0" src="user.php?MODULE_ID=<?=$MODULE_ID?>&TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&MANAGE_FLAG=<?=$MANAGE_FLAG?>&FORM_NAME=<?=$FORM_NAME?>"></iframe>
    </div>
    <div id="footer">
        <iframe id="control" name="control" scrolling="no" frameborder="0" src="control.php?TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&MANAGE_FLAG=<?=$MANAGE_FLAG?>&FORM_NAME=<?=$FORM_NAME?>"></iframe>
    </div>
</body>
