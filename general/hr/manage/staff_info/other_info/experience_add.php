<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("新建学习经历");
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
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();

   $ATTACHMENT_ID=$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$query="insert into HR_STAFF_LEARN_EXPERIENCE (
  CREATE_USER_ID,
  CREATE_DEPT_ID,
  STAFF_NAME,
  START_DATE,
  END_DATE,
  SCHOOL,
  SCHOOL_ADDRESS,
  MAJOR,
  ACADEMY_DEGREE,
  DEGREE,
  POSITION,
  AWARDING,
  CERTIFICATES,
  WITNESS,
  REMARK,
  ATTACHMENT_ID,
  ATTACHMENT_NAME,
  ADD_TIME,
  LAST_UPDATE_TIME)
values
( '".$_SESSION["LOGIN_USER_ID"]."',
	'".$_SESSION["LOGIN_DEPT_ID"]."',
	'$STAFF_NAME',
	'$START_DATE',
	'$END_DATE',
	'$SCHOOL',
	'$SCHOOL_ADDRESS',
	'$MAJOR',
	'$ACADEMY_DEGREE',
	'$DEGREE',
	'$POSITION',
	'$AWARDING',
	'$CERTIFICATES',
	'$WITNESS',
	'$REMARK',
	'$ATTACHMENT_ID',
	'$ATTACHMENT_NAME',
	'$CUR_TIME',
	'$CUR_TIME')";
exequery(TD::conn(),$query);

$query="select MIN(DEGREE)as DEGREE from HR_STAFF_LEARN_EXPERIENCE where STAFF_NAME='$STAFF_NAME'";

$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$STAFF_HIGHEST_DEGREE=$ROW["DEGREE"];	
}
$query="update HR_STAFF_INFO set STAFF_HIGHEST_DEGREE='$STAFF_HIGHEST_DEGREE' where USER_ID ='$STAFF_NAME'";

exequery(TD::conn(),$query);
Message("",_("成功增加学习经历！"));

?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='experience_new.php'"></center>
</body>
</html>
