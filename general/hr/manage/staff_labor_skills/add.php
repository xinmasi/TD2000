<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("新建劳动技能");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($ISSUE_DATE!="" && !is_date($ISSUE_DATE))
{
   Message("",_("发证日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($EXPIRE_DATE!="" && !is_date($EXPIRE_DATE))
{
   Message("",_("证书到期日期应为日期型，如：1999-01-01"));
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

//------------------- 劳动技能信息 -----------------------
if($SKILLS_CERTIFICATE==_("是"))
   $SKILLS_CERTIFICATE=1;
if($SKILLS_CERTIFICATE==_("否"))
   $SKILLS_CERTIFICATE=0;
   
if($SPECIAL_WORK==_("是"))
   $SPECIAL_WORK=1;
if($SPECIAL_WORK==_("否"))
   $SPECIAL_WORK=0;



$query="insert into HR_STAFF_LABOR_SKILLS (
  CREATE_USER_ID,
  CREATE_DEPT_ID,
  STAFF_NAME,
  ABILITY_NAME,
  SPECIAL_WORK,
  SKILLS_LEVEL,
  SKILLS_CERTIFICATE,
  ISSUE_DATE,
  EXPIRE_DATE,
  EXPIRES,
  ISSUING_AUTHORITY,
  REMARK,
  ATTACHMENT_ID,
  ATTACHMENT_NAME,
  ADD_TIME,
  LAST_UPDATE_TIME)
values
( '".$_SESSION["LOGIN_USER_ID"]."',
	'".$_SESSION["LOGIN_DEPT_ID"]."',
	'$STAFF_NAME',
	'$ABILITY_NAME',
	'$SPECIAL_WORK',
	'$SKILLS_LEVEL',
	'$SKILLS_CERTIFICATE',
	'$ISSUE_DATE',
	'$EXPIRE_DATE',
	'$EXPIRES',
	'$ISSUING_AUTHORITY',
	'$REMARK',
	'$ATTACHMENT_ID',
	'$ATTACHMENT_NAME',
	'$CUR_TIME',
	'$CUR_TIME')";
exequery(TD::conn(),$query);

Message("",_("成功增加劳动技能信息！"));
?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='new.php'"></center>
</body>
</html>
