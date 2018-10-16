<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("讨论区列表");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script Language="JavaScript">
function globalMessage()
{
	var URL="edit.php?BOARD_ID=-1&BTN_CLOSE=1";
  window.open(URL,"globalmessage","height=520,width=755,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=150,top=100,resizable=yes");
}
</script>


<body class="bodycolor">
<div id="top_header">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td width="11%" class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"> <?=_("讨论区列表")?><br>
    </td>
    <td width="89%">
<? 
if($_SESSION["LOGIN_USER_PRIV"]==1)//OA管理员
{
?>
    <input type="button" value="<?=_("全局公告")?>" class="BigButton" onclick="globalMessage()">
<?
}
?>
    </td>
  </tr>
</table>
</div>

<div id="title_body">
<?
//-------列出所有可见公告板标题-------
$query = "SELECT * from  BBS_BOARD  where (DEPT_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_ID) ".dept_other_sql("DEPT_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) ".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',BOARD_HOSTER)) order by BOARD_NO";
$cursor = exequery(TD::conn(),$query);
$BOARD_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $BOARD_COUNT++;
   $BOARD_ID = $ROW["BOARD_ID"];
   $DEPT_ID = $ROW["DEPT_ID"];
   $PRIV_ID = $ROW["PRIV_ID"];      
   $USER_ID = $ROW["USER_ID"];
   $BOARD_HOSTER = $ROW["BOARD_HOSTER"];   
   $BOARD_NAME = $ROW["BOARD_NAME"];
   $ANONYMITY_YN = $ROW["ANONYMITY_YN"];
   $WELCOME_TEXT = $ROW["WELCOME_TEXT"];
   $NEED_CHECK = $ROW["NEED_CHECK"]; 
   $BOARD_NAME=str_replace("<","&lt",$BOARD_NAME);
   $BOARD_NAME=str_replace(">","&gt",$BOARD_NAME);
//   $BOARD_NAME=stripslashes($BOARD_NAME);
	
	 if($NEED_CHECK=="0")
      $NEED_CHECK=_("不需审核");
   else if($NEED_CHECK=="1")
      $NEED_CHECK=_("需要审核");
 

   $USER_NAME_HOSTER="";
   $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'$BOARD_HOSTER')";
   $cursor1= exequery(TD::conn(),$query1);
   $POSTFIX = _("，");
   while($ROW=mysql_fetch_array($cursor1))
      $USER_NAME_HOSTER.=$ROW["USER_NAME"].$POSTFIX;
   
   $USER_NAME_HOSTER=substr($USER_NAME_HOSTER,0,-strlen($POSTFIX));

   if($ANONYMITY_YN=="0")
      $ANONYMITY_YN=_("禁止匿名");
   else if($ANONYMITY_YN=="1")
      $ANONYMITY_YN=_("允许匿名");
   else if($ANONYMITY_YN=="2")
      $ANONYMITY_YN=_("禁止发帖");

   $query1 = "SELECT COMMENT_ID from BBS_COMMENT where BOARD_ID='$BOARD_ID' and PARENT=0";
   $cursor1 = exequery(TD::conn(),$query1);
   $TITLE_NUM=mysql_num_rows($cursor1);
   
   $query1 = "SELECT COMMENT_ID from BBS_COMMENT where BOARD_ID='$BOARD_ID'";
   $cursor1 = exequery(TD::conn(),$query1);
   $RELAY_NUM=mysql_num_rows($cursor1);
   
   $LAST_USER_NAME="";
   $LAST_SUBMIT_TIME="";  
   $LAST_STR=""; 
   $query1 = "SELECT PARENT,COMMENT_ID,USER.USER_NAME,BBS_COMMENT.OLD_SUBMIT_TIME,BBS_COMMENT.AUTHOR_NAME from BBS_COMMENT,USER where BBS_COMMENT.BOARD_ID='$BOARD_ID' and USER.USER_ID=BBS_COMMENT.USER_ID  order by SUBMIT_TIME desc,COMMENT_ID desc limit 0,1";
   $cursor1 = exequery(TD::conn(), $query1);
   if($ROW=mysql_fetch_array($cursor1))
   {
      $COMMENT_ID1 = $ROW["COMMENT_ID"];
      $PARENT1 = $ROW["PARENT"];     
      $LAST_USER_NAME = $ROW["USER_NAME"];
      $AUTHOR_NAME = $ROW["AUTHOR_NAME"];      
      $LAST_OLD_SUBMIT_TIME = $ROW["OLD_SUBMIT_TIME"];              	
   	}
   	
   	if($PARENT1==0)
       $PARENT1=$COMMENT_ID1;
       
   	if($LAST_USER_NAME!="")
   	   $LAST_STR="<a href='comment.php?BOARD_ID=$BOARD_ID&COMMENT_ID=$PARENT1&PAGE_START=1'>".$LAST_OLD_SUBMIT_TIME." by <br>".$AUTHOR_NAME."</a>";
   
   if($BOARD_COUNT==1)
   {
?>
  <table class="table_header" align="center">
    <tr>
      <td nowrap align="left" width="40%"><?=_("讨论区")?></td>
      <td nowrap align="center" width="10%"><?=_("发帖")?></td>    
      <td nowrap align="center" width="10%"><?=_("审核")?></td>   
      <td nowrap align="center" width="5%"><?=_("主题")?></td> 
      <td nowrap align="center" width="5%"><?=_("帖数")?></td>             
      <td nowrap align="center" width="20%"><?=_("最后发表")?></td>   
      <td nowrap align="center" width="15%"><?=_("版主")?></td>      
    </tr>
  </table>
  <table class="title_table" align="center" >    
<?
    }
?>
    <tr class="table_row" onMouseOver="this.style.backgroundColor='#F5FBFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
      <td align="left" width="40%" class="fast_replay_td">
      	    <span id="a_span"><a href="board.php?BOARD_ID=<?=$BOARD_ID?>"><b><?=$BOARD_NAME?></b></a></span>
      <br /><span id="welcome_span"><?=$WELCOME_TEXT?></span>
      </td>
      <td align="center" width="10%" class="fast_replay_td" nowrap><?=$ANONYMITY_YN?></td>
      <td align="center" width="10%" class="fast_replay_td" nowrap><?=$NEED_CHECK?></td>
      <td align="center" width="5%" class="fast_replay_td"><?=$TITLE_NUM?></td>
      <td align="center" width="5%" class="fast_replay_td"><?=$RELAY_NUM?></td>
      <td align="center" width="20%" class="fast_replay_td"><?=$LAST_STR?></td>                        
      <td align="center" width="15%" class="fast_replay_td"><?=$USER_NAME_HOSTER?></td>          
    </tr>
<?
 }

 if($BOARD_COUNT>0)
 {
?>
  </table>
<?
 }
 else
    Message(_("提示"),"<br>"._("尚未定义讨论区"));
?>
</div>
</body>
</html>