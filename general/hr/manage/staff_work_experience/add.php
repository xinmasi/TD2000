<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("新建工作经历");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($START_DATE!="" && !is_date($START_DATE))
{
   Message("",_("开始日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($END_DATE!="" && !is_date($END_DATE))
{
   Message("",_("结束日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
//--------- 上传附件 ----------
$ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
$ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();

   $ATTACHMENT_ID.=$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME.=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$KEY_PERFORMANCE);
$KEY_PERFORMANCE = replace_attach_url($KEY_PERFORMANCE);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}

$query="insert into HR_STAFF_WORK_EXPERIENCE(
  CREATE_USER_ID,
  CREATE_DEPT_ID,
  STAFF_NAME,
  START_DATE,
  END_DATE,
  WORK_UNIT,
  MOBILE,
  WORK_BRANCH,
  POST_OF_JOB,
  WORK_CONTENT,
  KEY_PERFORMANCE,
  REASON_FOR_LEAVING,
  WITNESS,
  ATTACHMENT_ID,
  ATTACHMENT_NAME,
  REMARK,
  ADD_TIME,
  LAST_UPDATE_TIME)
values
( '".$_SESSION["LOGIN_USER_ID"]."',
	'".$_SESSION["LOGIN_DEPT_ID"]."',
	'$STAFF_NAME',
	'$START_DATE',
	'$END_DATE',
	'$WORK_UNIT',
	'$MOBILE',
	'$WORK_BRANCH',
	'$POST_OF_JOB',
	'$WORK_CONTENT',
	'$KEY_PERFORMANCE',
	'$REASON_FOR_LEAVING',
	'$WITNESS',
	'$ATTACHMENT_ID',
	'$ATTACHMENT_NAME',
	'$REMARK',
	'$CUR_TIME',
	'$CUR_TIME')";
exequery(TD::conn(),$query);

Message("",_("成功增加工作经历信息！"));
?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='new.php'"></center>
</body>
</html>
