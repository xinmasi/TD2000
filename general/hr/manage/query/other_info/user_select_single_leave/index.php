<?
include_once("inc/auth.inc.php");

if($_SESSION['LOGIN_NOT_VIEW_USER'])
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
<frameset rows="*,30"  rows="*" frameborder="NO" border="1" framespacing="0" id="bottom">
  <frameset cols="200,*"  rows="*" frameborder="YES" border="1" framespacing="0">
     <frame name="dept" src="dept.php?MODULE_ID=<?=$MODULE_ID?>&TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&MANAGE_FLAG=<?=$MANAGE_FLAG?>&FORM_NAME=<?=$FORM_NAME?>">
     <frame name="user" src="user.php?MODULE_ID=<?=$MODULE_ID?>&TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&MANAGE_FLAG=<?=$MANAGE_FLAG?>&FORM_NAME=<?=$FORM_NAME?>">
  </frameset>
   <frame name="control" scrolling="no" src="control.php?TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&MANAGE_FLAG=<?=$MANAGE_FLAG?>&FORM_NAME=<?=$FORM_NAME?>">
</frameset>
