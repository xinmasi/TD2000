<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("ÐÞ¸Ä°¸¾í");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());

$query="update RMS_ROLL set EDIT_DEPT='$EDIT_DEPT',ROOM_ID='$ROOM_ID',DEPT_ID='$DEPT_ID',ROLL_CODE='$ROLL_CODE',ROLL_NAME='$ROLL_NAME',YEARS='$YEARS',BEGIN_DATE='$BEGIN_DATE',END_DATE='END_DATE',DEADLINE='$DEADLINE',SECRET='$SECRET',CATEGORY_NO='$CATEGORY_NO',CATALOG_NO='$CATALOG_NO',ARCHIVE_NO='$ARCHIVE_NO',BOX_NO='$BOX_NO',MICRO_NO='$MICRO_NO',CERTIFICATE_KIND='$CERTIFICATE_KIND',CERTIFICATE_START='$CERTIFICATE_START',CERTIFICATE_END='$CERTIFICATE_END',ROLL_PAGE='$ROLL_PAGE',BORROW='$BORROW',REMARK='$REMARK',READ_USER='$READ_USER' WHERE ROLL_ID='$ROLL_ID'";
exequery(TD::conn(),$query);

if($OP==0)
   header("location: modify.php?NEWS_ID=$NEWS_ID&CUR_PAGE=$CUR_PAGE");
else
   header("location: index1.php?CUR_PAGE=$CUR_PAGE");
?>

</body>
</html>
