<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("修改案卷");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?

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

$sql1="SELECT * FROM rms_roll WHERE ROOM_ID = '$ROOM_ID' AND (ROLL_NAME = '$ROLL_NAME' OR ROLL_CODE = '$ROLL_CODE') AND ROLL_ID!= '$ROLL_ID'";
$cur= exequery(TD::conn(),$sql1);
if(mysql_affected_rows()>0)
{
	Message(_("错误"),_("相同卷库内案卷号或案卷名称必须唯一"));
	Button_Back();
	exit;	
}


$CUR_TIME=date("Y-m-d H:i:s",time());

$query="update RMS_ROLL set EDIT_DEPT='$EDIT_DEPT',ROOM_ID='$ROOM_ID',DEPT_ID='$DEPT_ID',MANAGER='$TO_ID',ROLL_CODE='$ROLL_CODE',ROLL_NAME='$ROLL_NAME',YEARS='$YEARS',BEGIN_DATE='$BEGIN_DATE',END_DATE='$END_DATE',DEADLINE='$DEADLINE',SECRET='$SECRET',CATEGORY_NO='$CATEGORY_NO',CATALOG_NO='$CATALOG_NO',ARCHIVE_NO='$ARCHIVE_NO',BOX_NO='$BOX_NO',MICRO_NO='$MICRO_NO',CERTIFICATE_KIND='$CERTIFICATE_KIND',CERTIFICATE_START='$CERTIFICATE_START',CERTIFICATE_END='$CERTIFICATE_END',ROLL_PAGE='$ROLL_PAGE',REMARK='$REMARK',READ_USER='$READ_USER' WHERE ROLL_ID='$ROLL_ID'";
exequery(TD::conn(),$query);

if($OP==0)
   header("location: modify.php?NEWS_ID=$NEWS_ID&CUR_PAGE=$CUR_PAGE&connstatus=1");
else
   header("location: index1.php?CUR_PAGE=$CUR_PAGE&connstatus=1");
?>

</body>
</html>
