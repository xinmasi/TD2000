<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("���Ϊ��Ŀģ��");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

  <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
      <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle"><span class="big3"> <?=_("���Ϊ��Ŀģ��")?></span><br>
      </td>
    </tr>
  </table>
  
<?
if(!$PROJ_NAME)
{
?>
<script Language="JavaScript">
String.prototype.trim = function()
{
  return this.replace(/(^[\s\t��]+)|([��\s\t]+$)/g, "");
};
function CheckForm()
{
   if(document.form1.PROJ_NAME.value.trim()=="")
   { 
   	 alert("<?=_("ģ�����Ʋ���Ϊ�գ�")?>");
     return (false);
   }
   return (true);
}
</script>

  <br>
  <br>

  <div align="center" class="Big1">
  <form name="form1" method="post" action="export.php" enctype="multipart/form-data" onSubmit="return CheckForm();">
  <b><?=_("ģ�����ƣ�")?></b>
    <input type="text" name="PROJ_NAME" class="BigInput" size="30"><br><br>
    <input type="hidden" value="<?=$PROJ_ID?>" name="PROJ_ID">
    <input type="submit" value="<?=_("����Ϊģ��")?>" class="BigButton">
    <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php?PROJ_ID=<?=$PROJ_ID?>'">
  </form>
  </div>
<?
   exit;
}
//�ų��ֶ�
$PROJ_FIELD = "PROJ_ID,PROJ_ACT_END_TIME,PROJ_STATUS,PROJ_OWNER,PROJ_PERCENT_COMPLETE,WORK_DAY,WORK_TIME,COST_TYPE,APPROVE_LOG";
$TASK_FIELD = "TASK_ID,PROJ_ID,TASK_PERCENT_COMPLETE,RUN_ID_STR";

$XML_OUT.= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
$XML_OUT.= "<Project>\r\n";
//-----------��Ŀ������Ϣ------------------
$query = "select * from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$XML_OUT.= "<BaseInfo>\r\n";
	$FIELD_NUM=mysql_num_fields($cursor);
	for($I=0;$I<$FIELD_NUM;$I++)
	{
		$FIELD_NAME=mysql_field_name($cursor,$I);
		if(!find_id($PROJ_FIELD,$FIELD_NAME))
		   $XML_OUT.= "<".$FIELD_NAME.">"."<![CDATA[".$ROW[$I]."]]></".$FIELD_NAME.">\r\n";
	}
	$XML_OUT.= "</BaseInfo>\r\n";
}

//-----------��Ŀ������Ϣ---------------------------
$query= "select * from PROJ_TASK where PROJ_ID='$PROJ_ID' order by TASK_ID";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$XML_OUT.= "<Task>\r\n";
	$FIELD_NUM=mysql_num_fields($cursor);
	for($I=0;$I<$FIELD_NUM;$I++)
	{
		$FIELD_NAME=mysql_field_name($cursor,$I);
	  if(!find_id($TASK_FIELD,$FIELD_NAME))
		   $XML_OUT.= "<".$FIELD_NAME.">"."<![CDATA[".$ROW[$I]."]]></".$FIELD_NAME.">\r\n";
	}
	$XML_OUT.= "</Task>\r\n";
}

//-----------��Ŀ�ĵ���Ϣ---------------------------
$query= "select * from PROJ_FILE_SORT where PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$XML_OUT.= "<FileSort>\r\n";
	$FIELD_NUM=mysql_num_fields($cursor);
	for($I=0;$I<$FIELD_NUM;$I++)
	{
		$FIELD_NAME=mysql_field_name($cursor,$I);
	  if($FIELD_NAME!="PROJ_ID" && $FIELD_NAME!="SORT_ID")
		   $XML_OUT.= "<".$FIELD_NAME.">"."<![CDATA[".$ROW[$I]."]]></".$FIELD_NAME.">\r\n";
	}
	$XML_OUT.= "</FileSort>\r\n";
}	

$XML_OUT.= "</Project>\r\n";

$XML_OUT=iconv(MYOA_DB_CHARSET,"utf-8",$XML_OUT);

//�����ļ�
$PATH=MYOA_ATTACH_PATH."proj_model";
if(!file_exists($PATH))
   mkdir($PATH, 0700);
$PROJ_FILE=MYOA_ATTACH_PATH."proj_model/".$PROJ_NAME.".xml";
if(file_exists($PROJ_FILE))
{
	 Message("",_("��ģ���Ѵ��ڣ�"),"error");
}
else
{
  td_file_put_contents($PROJ_FILE,$XML_OUT);
  Message("",_("ģ�屣��ɹ���"));
  echo '<div align="center"><input type="button" value="'._("����").'" class="BigButton" onclick="location=\'index.php?PROJ_ID='.$PROJ_ID.'\'"></div>';
}
?>