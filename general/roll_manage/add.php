<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//----------- �Ϸ���У�� ---------
if($BEGIN_DATE!="")
{
  $TIME_OK=is_date($BEGIN_DATE);

  if(!$TIME_OK)
  { Message(_("����"),_("��ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
?>

<br>
<div align="center">
 <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php?TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&SUBJECT=<?=$SUBJECT?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&CONTENT=<?=$CONTENT?>'">
</div>

<?
    exit;
  }
}

if($END_DATE!="")
{
  $TIME_OK=is_date($END_DATE);

  if(!$TIME_OK)
  { Message(_("����"),_("��ֹ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
?>

<br>
<div align="center">
 <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php?TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&SUBJECT=<?=$SUBJECT?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&CONTENT=<?=$CONTENT?>'">
</div>

<?
    exit;
  }
}

$CUR_TIME=date("Y-m-d H:i:s",time());

//------------------- �������� -----------------------

//��ѯ��ѡ������������
$sql="SELECT DEPT_ID FROM rms_roll_room WHERE ROOM_ID = '$ROOM_ID'";
$cursor= exequery(TD::conn(),$sql);
if($ROW=mysql_fetch_array($cursor))
{
	$THIS_DEPT_ID = $ROW[0];
}

if($THIS_DEPT_ID!=0)
{
	//��ȡ�¼����ŵĲ���
	$THIS_DEPT_ID = GetUnionSetOfChildDeptId($THIS_DEPT_ID);
	
	if(!find_id($THIS_DEPT_ID,$DEPT_ID))
	{
		Message(_("����"),_("��ѡ���ű����Ǿ���������Ż�����������"));
		Button_Back();
		exit;	
	}
}

$sql1="SELECT * FROM rms_roll WHERE ROOM_ID = '$ROOM_ID' AND (ROLL_NAME = '$ROLL_NAME' OR ROLL_CODE = '$ROLL_CODE')";
$cur= exequery(TD::conn(),$sql1);
if(mysql_affected_rows()>0)
{
	Message(_("����"),_("��ͬ����ڰ���Ż򰸾����Ʊ���Ψһ"));
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
   Message("",_("�������ɹ���"));
   $paras = strpos($_SERVER["HTTP_REFERER"], "?") ? $paras = $_SERVER["HTTP_REFERER"]."&connstatus=1" : $paras = $_SERVER["HTTP_REFERER"]."?connstatus=1";
}
?>
<center>
		<input type="button" class="BigButton" value="<?=_("����")?>" onClick="window.location.href='<?=$paras?>'"/>
</center>
</body>
</html>
