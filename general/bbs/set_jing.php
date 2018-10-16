<?
include_once("inc/auth.inc.php");

if(isset($Submit))
{
   $query="update BBS_COMMENT set JING='$JING' where find_in_set(COMMENT_ID,'$DELETE_STR')";
   
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

$HTML_PAGE_TITLE = _("精华处理");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="small">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif"  width="17" height="17"><b><?=_("精华")?></b><br>
  </td>
</tr>
</table>	
<form enctype="multipart/form-data" action="set_jing.php" method="post" name="form1">
<div style="text-align:center;">
  <div id="set_jing">
    <input type="radio" name="JING" value="1" id="JING1" checked><label for="JING1"><?=_("加精")?></label>&nbsp;&nbsp;
    <input type="radio" name="JING" value="0" id="JING2"><label for="JING2"><?=_("取消加精")?></label>
  </div>
</div>  
<br><br>
<center>
	<input type="hidden" value="<?=$DELETE_STR?>" name="DELETE_STR">
	<input type="hidden" value="<?=$BOARD_ID?>" name="BOARD_ID">
	<input type="submit" value="<?=_("确定")?>" name="Submit" class="BigButton">&nbsp;&nbsp;
	<input type="button" value="<?=_("取消")?>" class="BigButton" onclick="window.close()">
</center>
</form>
</body>
</html>