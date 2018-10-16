<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_start();

$CUR_TIME=date("Y-m-d H:i:s",time());

include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$query="select PHOTO_NAME from HR_STAFF_INFO  where USER_ID='$USER_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PHOTO_NAME=$ROW["PHOTO_NAME"];
}
if($PHOTO_NAME!="")
{
    $FILENAME=MYOA_ATTACH_PATH."hrms_pic/$PHOTO_NAME";
    if(file_exists($FILENAME))
    {
        unlink($FILENAME);
    }
}

$query="update HR_STAFF_INFO set PHOTO_NAME='',LAST_UPDATE_TIME='$CUR_TIME' where USER_ID='$USER_ID'";
exequery(TD::conn(),$query);
?>
<script>
location="staff_info.php?USER_ID=<?=$USER_ID?>";
</script>
</body>
</html>
