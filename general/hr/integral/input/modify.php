<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");
include_once("../func.func.php");

$HTML_PAGE_TITLE = _("¼������޸�");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   if (getEditorText('INTEGRAL_REASON').length == 0 && getEditorHtml('INTEGRAL_REASON') == "" && document.form1.ATTACHMENT_ID_OLD.value == "")
   { alert("<?=_("�������ɲ���Ϊ�գ�")?>");
     return (false);
   }
   return (true);
}
function sendForm()
{
  if(CheckForm())
     document.form1.submit();
}

</script>


<?
$query="select * from HR_INTEGRAL_DATA where ID='$ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$ID=$ROW["ID"];
	$ITEM_ID=$ROW["ITEM_ID"];
	$INTEGRAL_REASON=$ROW["INTEGRAL_REASON"];
	$INTEGRAL_TYPE=$ROW["INTEGRAL_TYPE"];
	$USER_ID=$ROW["USER_ID"];
	$INTEGRAL_DATA=$ROW["INTEGRAL_DATA"];
	$CREATE_PERSON=$ROW["CREATE_PERSON"];
	$CREATE_TIME=$ROW["CREATE_TIME"];
	$INTEGRAL_TIME=$ROW["INTEGRAL_TIME"];
	$INTEGRAL_TYPE_SHOW="";
	if($INTEGRAL_TYPE==0)
		$INTEGRAL_TYPE_SHOW=_("δ���������¼��");
	else if($INTEGRAL_TYPE==1)
		$INTEGRAL_TYPE_SHOW=_("OAʹ�û���¼��");
	else if($INTEGRAL_TYPE==2)
		$INTEGRAL_TYPE_SHOW=_("���µ������Զ�����");
	else if($INTEGRAL_TYPE==3)
		$INTEGRAL_TYPE_SHOW=_("�Զ��������¼��");
	$ITEM_NAME=$ITEM_ID==0?_("δ������"):getItemName($ITEM_ID);
	$STATUS=$ROW["STATUS"];
	
	$USER_NAME = substr(GetUserNameById($USER_ID),0,-1);
	$CREATE_PERSON_NAME = substr(GetUserNameById($CREATE_PERSON),0,-1);
  
}
 
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�༭��¼�����")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<form action="update.php"  method="post" name="form1" enctype="multipart/form-data" onSubmit="return CheckForm();">
<table class="TableBlock" width="80%" align="center">
	 <tr>
      <td nowrap class="TableData"><?=_("���ֻ���ˣ�")?></td>
      <input type="hidden" name="INTEGRAL_TYPE" value="<?=$INTEGRAL_TYPE?>" />
      <td class="TableData" <?=($INTEGRAL_TYPE==1||$INTEGRAL_TYPE==2)?"":"colspan=3"?>>
      	 <input type="text" name="USER_NAME" size="14" class="BigStatic" readonly value="<?=$USER_NAME?>">&nbsp;
        <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
		<input type="hidden" name="INTEGRAL_TYPE1" value="<?=$INTEGRAL_TYPE1?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','USER_ID', 'USER_NAME','1')"><?=_("ѡ��")?></a>
      </td>
<? if($INTEGRAL_TYPE==1||$INTEGRAL_TYPE==2)
{
?>
      <td nowrap class="TableData"><?=_("���������ƣ�")?></td>
      <td class="TableData">
      	 <?=$ITEM_NAME?>
      </td>
<?
}
?>
    </tr>   
   <tr>
      <td nowrap class="TableData"><?=_("������Դ��")?></td>
      <td class="TableData" >
			<?=$INTEGRAL_TYPE_SHOW?>
      </td>
      <td nowrap class="TableData"><?=_("��ֵ��")?></td>
      <td class="TableData">
        <input type="text" name="INTEGRAL_DATA" class=BigInput size="12" value="<?=$INTEGRAL_DATA?>">&nbsp;
      </td>    
    </tr>
    <tr style="display:none;">
    	<td colspan=4><?=_("�������У������Զ���¼�����ѡ�������õĻ�����Ŀ������ʾ��ֵ��Χ��Ĭ�Ϸ�ֵ��")?>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�����Ա��")?></td>
      <td class="TableData">
        <input type="text" name="CREATE_PERSON_NAME" size="14" class="BigStatic" readonly value="<?=$CREATE_PERSON_NAME?>">&nbsp;
        <input type="hidden" name="CREATE_PERSON" value="<?=$CREATE_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','CREATE_PERSON', 'CREATE_PERSON_NAME','1')"><?=_("ѡ��")?></a>
      </td>
      <td nowrap class="TableData"> <?=_("���ֻ�����ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="INTEGRAL_TIME" size="16" maxlength="16" class="BigInput" value="<?=$INTEGRAL_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
      </td>
    </tr>
   <tr>
      <td nowrap class="TableData"> <?=_("���ѣ�")?></td>
      <td class="TableData" colspan=3>
<?=sms_remind(78);?>
      </td>
   </tr>
    <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("�������ɣ�")?>
<?
$editor = new Editor('INTEGRAL_REASON') ;
$editor->Height = '300';
$editor->ToolbarSet='Basic';
$editor->Value = $INTEGRAL_REASON ;
$editor->Create() ;
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
      	<input type="hidden" name="OP" value="">
      	<input type="hidden" value="<?=$ID?>" name="ID">
        <input type="submit" value="<?=_("����")?>" class="BigButton" onClick="sendForm();">
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='manage.php?INTEGRAL_TYPE1=<?=$INTEGRAL_TYPE1?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>