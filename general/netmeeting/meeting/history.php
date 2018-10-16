<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("查看聊天室聊天记录");
include_once("inc/header.inc.php");

$query = "SELECT * from NETMEETING where MEET_ID='$MEET_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $SUBJECT=$ROW["SUBJECT"];
   $SUBJECT=str_replace("<","&lt",$SUBJECT);
   $SUBJECT=str_replace(">","&gt",$SUBJECT);
   $SUBJECT=stripslashes($SUBJECT);
}
?>
<body class="bodycolor" topmargin=5 >
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/source.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=$SUBJECT?><?=_("文本会议记录")?></span></td>
  </tr>
</table>
<table>
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$MSG_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".msg";
if(!file_exists($MSG_FILE))
{
	$fp=td_fopen($MSG_FILE,"r");
	flock ($fp,2);
	fclose($fp);
}

$LINES=file($MSG_FILE);
$LINES_COUNT=count($LINES);

for($I=0;$I<$LINES_COUNT;$I++)
{
	$STR=substr($LINES[$I],0,strlen($LINES[$I])-1);
	
	$TO_STR=substr($STR,0,strpos($STR,"@+#"));
	
	$TO_ID="";
	$FROM_ID="";
	
	if($TO_STR!="")
	{
		$TO_STR=strtok($TO_STR,",");
		$TO_ID=$TO_STR;
		$TO_STR=strtok(",");
		$FROM_ID=$TO_STR;
		
		$POS=strpos($STR,"@+#");
		$STR=substr($STR,$POS+3);
	}
	if($STR!="")
	{
		if($TO_ID=="" || $TO_ID==$_SESSION["LOGIN_USER_ID"] || $FROM_ID==$_SESSION["LOGIN_USER_ID"])
		{
			$OUT_PUT="<span>".$STR."</span><br>";
			
			$OUT_PUT=str_replace(chr(34),_("”"),$OUT_PUT);
			echo "<tr class=TableData><td>" ; 
			echo $OUT_PUT;
			echo "</td></tr>";
	
		}
	}
}

?>
</table>
</body>
</html>