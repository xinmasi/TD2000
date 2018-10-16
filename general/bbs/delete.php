<?
//URL:webroot\general\bbs\delete.php
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");		
include_once("inc/utility_org.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if($DELETE_STR=="")
   $DELETE_STR=$COMMENT_ID;

$query = "SELECT DEPT_ID,PRIV_ID,USER_ID,BOARD_HOSTER from BBS_BOARD where BOARD_ID='$BOARD_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $DEPT_ID = $ROW["DEPT_ID"];
   $PRIV_ID = $ROW["PRIV_ID"];      
   $USER_ID1 = $ROW["USER_ID"];
   $BOARD_HOSTER = $ROW["BOARD_HOSTER"];
}
$DELETE_STR=td_trim($DELETE_STR);
if($DELETE_STR!="")
{   $query = "SELECT * from BBS_COMMENT where COMMENT_ID in ($DELETE_STR) or PARENT in ($DELETE_STR)";
	
	$cursor = exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
	   $USER_ID2 .= $ROW["USER_ID"].",";
	
	if($_SESSION["LOGIN_USER_PRIV"]!=1 && !find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]) && !find_id($USER_ID2,$_SESSION["LOGIN_USER_ID"]))
	{
		 message(_("提示"),_("无效操作"));
		 exit;
	}
}

if($DELETE_STR!="")
{
   $query="select *  from BBS_COMMENT where COMMENT_ID in ($DELETE_STR)";
   $cursor = exequery(TD::conn(), $query);
   while($ROW=mysql_fetch_array($cursor))
   {
   	 $PARENT=$ROW["PARENT"];
   	 $USER_ID=$ROW["USER_ID"];
   	 $PARENT=intval($PARENT);
     if($PARENT!=0)
     {
        $query="update BBS_COMMENT set REPLY_CONT=REPLY_CONT-1 where COMMENT_ID='$PARENT'";
        exequery(TD::conn(), $query);
     }
      
   	 //--------- 删除帖子 用户积分跟着相应减少 ----------
   	 //$query1="update USER set BBS_COUNTER=BBS_COUNTER-1 where USER_ID='$USER_ID'";
	 $query1="update USER_EXT set BBS_COUNTER=BBS_COUNTER-1 where USER_ID='$USER_ID'";
     exequery(TD::conn(), $query1);   	 
   }
}
if($DELETE_STR!="")
{
   $query="delete from BBS_COMMENT where COMMENT_ID in ($DELETE_STR) or PARENT in ($DELETE_STR)";
   exequery(TD::conn(), $query);
   //记录系统日志  
   add_log(24,substr(GetUserNameById($_SESSION["LOGIN_USER_ID"]),0,-1)._("删除").substr(GetUserNameById($USER_ID),0,-1)._("回帖"),$_SESSION["LOGIN_USER_ID"]);
}
if($PARENT==0)
   header("location: board.php?BOARD_ID=$BOARD_ID&PAGE_START=$PAGE_START&IS_MAIN=1");
else
   header("location: comment.php?BOARD_ID=$BOARD_ID&COMMENT_ID=$PARENT&PAGE_START=$PAGE_START&IS_MAIN=1");
?>

</body>
</html>