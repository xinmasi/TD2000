<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("社会关系");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($BIRTHDAY!="" && !is_date($BIRTHDAY))
{
   Message("",_("出生日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
//--------- 上传附件 ----------
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();

   $ATTACHMENT_ID=$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;
//------------------- 新增职称评定信息 -----------------------
$query="insert into HR_STAFF_RELATIVES(CREATE_USER_ID,CREATE_DEPT_ID,MEMBER,RELATIONSHIP,BIRTHDAY,POLITICS,WORK_UNIT,UNIT_ADDRESS,POST_OF_JOB,OFFICE_TEL,HOME_ADDRESS,HOME_TEL,JOB_OCCUPATION,STAFF_NAME,PERSONAL_TEL,REMARK,ATTACHMENT_ID,ATTACHMENT_NAME,ADD_TIME,LAST_UPDATE_TIME) 
		                               values ('{$_SESSION['LOGIN_USER_ID']}','{$_SESSION['LOGIN_DEPT_ID']}','$MEMBER','$RELATIONSHIP','$BIRTHDAY','$POLITICS','$WORK_UNIT','$UNIT_ADDRESS','$POST_OF_JOB','$OFFICE_TEL','$HOME_ADDRESS','$HOME_TEL','$JOB_OCCUPATION','$STAFF_NAME','$PERSONAL_TEL','$REMARK','$ATTACHMENT_ID','$ATTACHMENT_NAME','$CUR_TIME','$CUR_TIME')";
exequery(TD::conn(),$query);
Message("",_("成功增加社会关系信息！"));
Button_Back();
?>
</body>
</html>
