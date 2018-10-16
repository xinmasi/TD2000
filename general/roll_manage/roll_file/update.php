<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("编辑文件");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//if($DOWNLOAD_YN=="on")
//   $DOWNLOAD_YN = 1;
//else
//   $DOWNLOAD_YN = 0;

$sql1="SELECT * FROM  rms_file WHERE ROLL_ID = '$ROLL_ID' AND (FILE_CODE = '$FILE_CODE' AND FILE_TITLE = '$FILE_TITLE') AND FILE_ID!= '$FILE_ID'";
$cur= exequery(TD::conn(),$sql1);
if(mysql_affected_rows()>0)
{
	Message(_("错误"),_("相同案卷文件号或文件名称必须唯一"));
	Button_Back();
	exit;	
}

   
if($DOWNLOAD=="on")
   $DOWNLOAD = 1;
else
   $DOWNLOAD = 0;
   
if($PRINT=="on")
   $PRINT = 1;
else
   $PRINT = 0;

//--------- 上传附件 ----------
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();
 
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
 $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
 $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$CUR_TIME=date("Y-m-d H:i:s",time());
$FILE_ID=intval($FILE_ID);
$query="update RMS_FILE set MOD_USER='".$_SESSION["LOGIN_USER_ID"]."',MOD_TIME='$CUR_TIME',FILE_CODE='$FILE_CODE',FILE_TITLE='$FILE_TITLE',FILE_TITLE0='$FILE_TITLE0',FILE_SUBJECT='$FILE_SUBJECT',SEND_UNIT='$SEND_UNIT',SEND_DATE='$SEND_DATE',SECRET='$SECRET',URGENCY='$URGENCY',FILE_KIND='$FILE_KIND',FILE_TYPE='$FILE_TYPE',FILE_PAGE='$FILE_PAGE',PRINT_PAGE='$PRINT_PAGE',BORROW='$BORROW',REMARK='$REMARK',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',ROLL_ID='$ROLL_ID',ISAUDIT='$ISAUDIT',DOWNLOAD='$DOWNLOAD',PRINT='$PRINT' where FILE_ID='$FILE_ID'";
exequery(TD::conn(),$query);

if($OP==0)
   header("location: modify.php?FILE_ID=$FILE_ID&CUR_PAGE=$CUR_PAGE&connstatus=1");
else
   header("location: index1.php?CUR_PAGE=$CUR_PAGE&connstatus=1");
?>

</body>
</html>
