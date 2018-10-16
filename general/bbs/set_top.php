<?
include_once("inc/auth.inc.php");

if(isset($Submit))
{
   $query="update BBS_COMMENT set TOP='$TOP' where find_in_set(COMMENT_ID,'$DELETE_STR')";
   
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

$HTML_PAGE_TITLE = _("置顶");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="1" class="small">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif"  width="17" height="17"><b><?=_("置顶")?></b><br>
  </td>
</tr>
</table>	
	
<form enctype="multipart/form-data" action="set_top.php" method="post" name="form1">
<div style="text-align:center;">
  <div id="set_jing">  		
   <input type="radio" name="TOP" value="1" id="TOP1"><label for="TOP1"><?=_("置顶")?></label>&nbsp;&nbsp; 
   <input type="radio" name="TOP" value="0" id="TOP2"><label for="TOP2"><?=_("取消置顶")?></label>&nbsp;&nbsp;
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