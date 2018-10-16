<?
include_once("inc/auth.inc.php");

if(isset($Submit))
{
	
   if($DELETE_STR=="")
      $query = "update BBS_COMMENT set BOARD_ID='$TO_ID' where COMMENT_ID='$COMMENT_ID' or PARENT='$COMMENT_ID'";
   else
      $query = "update BBS_COMMENT set BOARD_ID='$TO_ID' where find_in_set(COMMENT_ID,'$DELETE_STR') or find_in_set(PARENT,'$DELETE_STR')";
   exequery(TD::conn(),$query);
   
   
?>

<script>
var url_ole=window.opener.location.href;
var url_search=window.opener.location.search;
	if(url_ole.indexOf("?IS_MAIN=1")>0 || url_ole.indexOf("&IS_MAIN=1")>0)
		window.opener.location.reload();
	else
	{
		if(url_search=="")
			window.opener.location.href=url_ole+"?IS_MAIN=1";
		else
			window.opener.location.href=url_ole+"&IS_MAIN=1"; 
	} 		
//opener.location.reload();
window.close();
</script>

<?
}

$HTML_PAGE_TITLE = _("帖子转移");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="small">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif"  width="17" height="17"><span class="big3"> <?=_("帖子转移")?></span><br>
  </td>
</tr>
</table>
<br>

<form enctype="multipart/form-data" action="move.php" method="post" name="form1">
<div style="text-align:center;">
  <div id="set_jing" nowrap>
    <font size="5"><?=_("转移至：")?></font>
    	<select name="TO_ID" class="BigSelect">
<?
$BOARD_ID =intval($BOARD_ID);
$query = "SELECT * from BBS_BOARD where BOARD_ID!='$BOARD_ID' and (DEPT_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',BOARD_HOSTER)) order by BOARD_NO";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $BOARD_ID=$ROW["BOARD_ID"];
  $BOARD_NAME=$ROW["BOARD_NAME"];
?>
        <option value="<?=$BOARD_ID?>" <? if($TO_ID==$BOARD_ID) echo "selected";?>><?=$BOARD_NAME?></option>
<?
}
?>
      </select>
  </div>
</div>        
<br><br>
<center>
<?
if($DELETE_STR=="")
{
?>
	<input type="hidden" value="<?=$COMMENT_ID?>" name="COMMENT_ID">
<?
}
else
{
?>
	<input type="hidden" value="<?=$DELETE_STR?>" name="DELETE_STR">
<?
}
?>
	<input type="hidden" value="<?=$BOARD_ID?>" name="BOARD_ID">
	<input type="submit" value="<?=_("确定")?>" name="Submit" class="BigButton">&nbsp;&nbsp;
	<input type="button" value="<?=_("取消")?>" class="BigButton" onclick="window.close()">
</center>
</form>
</body>

</html>