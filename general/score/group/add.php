<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("新建考核指标");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//------------------- 保存 -----------------------
if($DIARY=="on")
  $GROUP_REFER=$GROUP_REFER."DIARY,";
if($CALENDAR=="on")
  $GROUP_REFER=$GROUP_REFER."CALENDAR,";  
 
$query="insert into SCORE_GROUP(GROUP_NAME,GROUP_DESC,GROUP_REFER,CREATE_USER_ID,TO_ID,PRIV_ID,USER_ID) values ('$GROUP_NAME','$GROUP_DESC','$GROUP_REFER','".$_SESSION["LOGIN_USER_ID"]."','$TO_ID','$PRIV_ID','$USER_ID')";
echo $query;
exequery(TD::conn(),$query);
header("location: index.php?CUR_PAGE=$CUR_PAGE&connstatus=1");
?>
</body>
</html>
