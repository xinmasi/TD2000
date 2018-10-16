<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
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
//-- 保存 --
$query="update  HR_TRAINING_RECORD set 
          CREATE_USER_ID='{$_SESSION['LOGIN_USER_ID']}',
          CREATE_DEPT_ID='{$_SESSION['LOGIN_DEPT_ID']}',
          STAFF_USER_ID='$STAFF_USER_ID',
          T_INSTITUTION_NAME='$T_INSTITUTION_NAME',          
          TRAINNING_COST='$TRAINNING_COST',
          DUTY_SITUATION='$DUTY_SITUATION',    
          TRAINNING_SITUATION='$TRAINNING_SITUATION',
          T_EXAM_RESULTS='$T_EXAM_RESULTS',  
          T_EXAM_LEVEL='$T_EXAM_LEVEL',
          T_COMMENT='$T_COMMENT',
          REMARK='$REMARK',
		  ATTACHMENT_ID='$ATTACHMENT_ID',
		  ATTACHMENT_NAME='$ATTACHMENT_NAME'";
$query.=" where RECORD_ID='$RECORD_ID'";
exequery(TD::conn(),$query);

Message("",_("修改成功！"));
Button_Back();
?>
</body>
</html>
