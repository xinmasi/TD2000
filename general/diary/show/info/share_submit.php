<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("工作日志共享");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/diary.css">

<body class="bodycolor" topmargin="5">
<?

/*//保留原有的共享名单
$query = "SELECT TO_ID from DIARY where DIA_ID='$DIA_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
	 $TO_ID_OLD = $ROW["TO_ID"];
*/
$MY_ARRAY=explode(",",$TO_ID);
$ARRAY_COUNT=sizeof($MY_ARRAY);
if($MY_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
for($I=0;$I < $ARRAY_COUNT;$I++)
{
   if(!find_id($TO_ID_OLD,$MY_ARRAY[$I]))
   {
      $TO_ID_OLD.=$MY_ARRAY[$I].",";
   }
}

$query="update DIARY set TO_ID='$TO_ID_OLD' where DIA_ID='$DIA_ID'";
exequery(TD::conn(),$query);
Message(_("提示"),_("保存成功"));

if($FROM_FLAG==1 || $FROM_FLAG==3)
   $BACK_RUL = "share.php?USER_ID=$USER_ID&DIA_ID=$DIA_ID&SUBJECT=$SUBJECT&USER_NAME=$USER_NAME&FROM_FLAG=$FROM_FLAG&IS_MAIN=1";
else if($FROM_FLAG==2)
   $BACK_RUL = "user_query.php";  
else if($FROM_FLAG==4)
   $BACK_RUL = "../share_read.php?DIA_ID=$DIA_ID&FROM_FLAG=1";  
?>	
<center>
	<input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='<?=$BACK_RUL?>'">
</center>		
</body>
</html>	