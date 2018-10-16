<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_start();

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$query="select PHOTO_NAME from HR_RECRUIT_POOL where EXPERT_ID='$EXPERT_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $PHOTO_NAME=$ROW["PHOTO_NAME"];
else
   exit;
if($PHOTO_NAME!="")
{
	$FILENAME=MYOA_ATTACH_PATH."recruit_pic/$PHOTO_NAME";
	if(file_exists($FILENAME))
   {
      unlink($FILENAME);
   }
}
$query="update HR_RECRUIT_POOL set PHOTO_NAME='' where EXPERT_ID='$EXPERT_ID'";
exequery(TD::conn(),$query);
?>
<script>
location="modify.php?EXPERT_ID=<?=$EXPERT_ID?>";
</script>
</body>
</html>
