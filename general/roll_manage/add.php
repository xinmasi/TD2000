<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("发布案卷");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//----------- 合法性校验 ---------
if($BEGIN_DATE!="")
{
  $TIME_OK=is_date($BEGIN_DATE);

  if(!$TIME_OK)
  { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
?>

<br>
<div align="center">
 <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&SUBJECT=<?=$SUBJECT?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&CONTENT=<?=$CONTENT?>'">
</div>

<?
    exit;
  }
}

if($END_DATE!="")
{
  $TIME_OK=is_date($END_DATE);

  if(!$TIME_OK)
  { Message(_("错误"),_("终止日期格式不对，应形如 1999-1-2"));
?>

<br>
<div align="center">
 <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&SUBJECT=<?=$SUBJECT?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&CONTENT=<?=$CONTENT?>'">
</div>

<?
    exit;
  }
}

$CUR_TIME=date("Y-m-d H:i:s",time());

//------------------- 发布案卷 -----------------------

//查询所选卷库的所属部门
$sql="SELECT DEPT_ID FROM rms_roll_room WHERE ROOM_ID = '$ROOM_ID'";
$cursor= exequery(TD::conn(),$sql);
if($ROW=mysql_fetch_array($cursor))
{
	$THIS_DEPT_ID = $ROW[0];
}

if($THIS_DEPT_ID!=0)
{
	//获取下级部门的并集
	$THIS_DEPT_ID = GetUnionSetOfChildDeptId($THIS_DEPT_ID);
	
	if(!find_id($THIS_DEPT_ID,$DEPT_ID))
	{
		Message(_("错误"),_("所选部门必须是卷库所属部门或其下属部门"));
		Button_Back();
		exit;	
	}
}

$sql1="SELECT * FROM rms_roll WHERE ROOM_ID = '$ROOM_ID' AND (ROLL_NAME = '$ROLL_NAME' OR ROLL_CODE = '$ROLL_CODE')";
$cur= exequery(TD::conn(),$sql1);
if(mysql_affected_rows()>0)
{
	Message(_("错误"),_("相同卷库内案卷号或案卷名称必须唯一"));
	Button_Back();
	exit;	
}

$query="insert into RMS_ROLL(ROOM_ID,DEPT_ID,ROLL_CODE,ROLL_NAME,YEARS,BEGIN_DATE,END_DATE,DEADLINE,SECRET,CATEGORY_NO,CATALOG_NO,ARCHIVE_NO,BOX_NO,MICRO_NO,CERTIFICATE_KIND,CERTIFICATE_START,CERTIFICATE_END,ROLL_PAGE,REMARK,READ_USER,ADD_USER,ADD_TIME,MANAGER,EDIT_DEPT) values ('$ROOM_ID','$DEPT_ID','$ROLL_CODE','$ROLL_NAME','$YEARS','$BEGIN_DATE','$END_DATE','$DEADLINE','$SECRET','$CATEGORY_NO','$CATALOG_NO','$ARCHIVE_NO','$BOX_NO','$MICRO_NO','$CERTIFICATE_KIND','$CERTIFICATE_START','$CERTIFICATE_END','$ROLL_PAGE','$REMARK','$READ_USER','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','$TO_ID','$EDIT_DEPT')";

exequery(TD::conn(),$query);
$ROLL_ID=mysql_insert_id();

if($OP==0)
  header("location:modify.php?ROLL_ID=$ROLL_ID");
else
{
   Message("",_("案卷发布成功！"));
   $paras = strpos($_SERVER["HTTP_REFERER"], "?") ? $paras = $_SERVER["HTTP_REFERER"]."&connstatus=1" : $paras = $_SERVER["HTTP_REFERER"]."?connstatus=1";
}
?>
<center>
		<input type="button" class="BigButton" value="<?=_("返回")?>" onClick="window.location.href='<?=$paras?>'"/>
</center>
</body>
</html>
