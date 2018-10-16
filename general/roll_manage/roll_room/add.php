<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("新建卷库");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());

//------------------- 新建卷库 -----------------------
$query="insert into RMS_ROLL_ROOM(DEPT_ID,VIEW_DEPT_ID,ROOM_CODE,ROOM_NAME,REMARK,MANAGE_USER,ADD_USER) values ('$DEPT_ID','$TO_ID','$ROOM_CODE','$ROOM_NAME','$REMARK','$USER_ID','".$_SESSION["LOGIN_USER_ID"]."')";
exequery(TD::conn(),$query);

$ROOM_ID=mysql_insert_id();

if($OP==0)
  header("location:modify.php?ROOM_ID=$ROOM_ID&connstatus=1");
else
{
   Message("",_("卷库新建成功！"));
   $paras = strpos($_SERVER["HTTP_REFERER"], "?") ? $paras = $_SERVER["HTTP_REFERER"]."&connstatus=1" : $paras = $_SERVER["HTTP_REFERER"]."?connstatus=1";
}
?>
<center>
		<input type="button" class="BigButton" value="<?=_("返回")?>" onclick="window.location.href='<?=$paras?>'"/>
</center>
</body>
</html>
