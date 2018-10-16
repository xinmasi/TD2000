<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

function level_desc($level)
{
   switch($level)
   {
      case "1": return _("�ǳ���");
      case "2": return _("��");
      case "3": return _("��ͨ");
      case "4": return _("��");
   }
}
if($BUG_ID)
{
	$query = "select * from PROJ_BUG WHERE BUG_ID='$BUG_ID'";
	$cursor = exequery(TD::conn(), $query);
	if($ROW=mysql_fetch_array($cursor))
	{
		$BUG_NAME = $ROW["BUG_NAME"];
		$BUG_DESC = $ROW["BUG_DESC"];
		$LEVEL = $ROW["LEVEL"];
		$DEAL_USER = $ROW["DEAL_USER"];
		$DEAD_LINE = $ROW["DEAD_LINE"];
		$ATTACHMENT_ID  = $ROW["ATTACHMENT_ID"];
		$ATTACHMENT_NAME  = $ROW["ATTACHMENT_NAME"];
	}
	if($DEAL_USER)
	{
    $query = "select USER_NAME from USER WHERE USER_ID='$DEAL_USER'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
       $DEAL_USER_NAME = $ROW["USER_NAME"];
  }
}

$IMPORTANT_INFO='<span style="color:red">(*)</span>';
if(!isset($LEVEL))
  $LEVEL=3;

$HTML_PAGE_TITLE = _("������Ŀ����");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
String.prototype.trim= function()
{
    return this.replace(/(^\s*)|(\s*$)/g, "");
}

function CheckForm()
{
   if(document.form1.BUG_NAME.value.trim()==""||document.form1.BUG_DESC.value==""||document.form1.DEAL_USER.value==""||document.form1.DEAD_LINE.value=="")
   { alert("<?=_("����д�����ֶΣ�")?>");
     return (false);
   }

   return (true);
}
function mysubmit(flag)
{
	document.form1.SAVE_FLAG.value=flag;
	if(CheckForm())
	  document.form1.submit();
}
function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?BUG_ID=<?=$BUG_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME)+"&PROJ_ID=<?=$PROJ_ID?>&TASK_ID=<?=$TASK_ID?>";
    alert(URL);
    window.location=URL;
  }
}
function set_option(option, id, className)
{
  hideMenu();
  option = typeof(option)=="undefined" ? "" : option;
  $$(id.toUpperCase()+"_FIELD").value=option;
  
  $$(id).innerHTML=$$(id+'_'+option).innerHTML;
  $$(id).className=className+option;
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<tr><td>
	<img src="<?=MYOA_STATIC_SERVER?>/static/images/project/bug.gif" align="absmiddle"/>
	<span class="big3"><?=_("����������")?></span><td></tr>
</table>
<form enctype="multipart/form-data" name="form1" method="post" action="submit.php">
 <table class="TableList" border="0" width="80%" align="center">
   <tr>
  		<td nowrap class="TableContent"><?=_("�������ƣ�")?><?=$IMPORTANT_INFO?></td>
  	  <td class="TableData">
  	  	<input type="text" class="BigInput" name="BUG_NAME" value="<?=$BUG_NAME?>" size=20>
  	  </td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("���ȼ���")?></td>
  	  <td class="TableData">
        <a id="level" href="javascript:;" class="CalLevel<?=$LEVEL?>" onclick="showMenu(this.id,'1');" hidefocus="true"><?=level_desc($LEVEL)?><?=menu_arrow("DOWN")?></a>&nbsp;
        <div id="level_menu" class="attach_div" style="width:110px;">
           <a id="level_4" href="javascript:set_option('4','level','CalLevel');" class="CalLevel4"><?=_("��")?></a>
           <a id="level_3" href="javascript:set_option('3','level','CalLevel');" class="CalLevel3"><?=_("��ͨ")?></a>
           <a id="level_2" href="javascript:set_option('2','level','CalLevel');" class="CalLevel2"><?=_("��")?></a>
           <a id="level_1" href="javascript:set_option('1','level','CalLevel');" class="CalLevel1"><?=_("�ǳ���")?></a>
        </div>
        <input type="hidden" id="LEVEL_FIELD" name="LEVEL" value="<?=$LEVEL?>">
  	  </td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("����������")?><?=$IMPORTANT_INFO?></td>
  	  <td class="TableData">
  	  	<textarea cols="60" name="BUG_DESC" rows="5" style="overflow-y:auto;" class="BigInput" wrap="yes"><?=$BUG_DESC?></textarea>
  	  </td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("�����ˣ�")?><?=$IMPORTANT_INFO?></td>
  	  <td class="TableData">
     	<INPUT type="hidden" name="DEAL_USER" value="<?=$DEAL_USER?>">
     	<INPUT type="input" class="BigStatic" value="<?=$DEAL_USER_NAME?>" name="DEAL_USER_NAME" readonly>
     	<a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','','DEAL_USER', 'DEAL_USER_NAME')"><?=_("ѡ��")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('DEAL_USER', 'DEAL_USER_NAME')"><?=_("���")?></a>
  	  </td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("��������ޣ�")?><?=$IMPORTANT_INFO?></td>
  	  <td class="TableData"> 
  	    <INPUT type="text" readonly name="DEAD_LINE" class=BigInput size="10" value="<?=$DEAD_LINE?>" onClick="WdatePicker()">
        
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent"><?=_("�����ĵ���")?></td>
      <td nowrap class="TableData"><?if($ATTACHMENT_ID) echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,0,0,0);else echo _("�޸���");?></td>
    </tr>
    <tr height="25">
      <td nowrap class="TableContent"><?=_("����ѡ��")?></td>
      <td class="TableData">
        <script>ShowAddFile();</script>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent"><?=_("���Ѵ�����Ա��")?></td>
      <td class="TableData">
<?=sms_remind(42);?>
    </td>
    </tr>
    <tr align="center" class="TableControl">
    	<td colspan="2" nowrap>
      <input type="hidden" name="PROJ_ID" value="<?=$PROJ_ID?>">
      <input type="hidden" name="BUG_ID" value="<?=$BUG_ID?>">
      <input type="hidden" name="TASK_ID" value="<?=$TASK_ID?>">
      <input type="hidden" name="SAVE_FLAG" value="<?=$SAVE_FLAG?>">
    	<input type="button" value="<?=_("����")?>" class="BigButton" onclick="mysubmit('0');">
    	<input type="button" value="<?=_("�ύ")?>" class="BigButton" onclick="mysubmit('1');">
	    <input type="button" value="<?=_("����")?>" class="BigButton" onclick="location.href='index.php?PROJ_ID=<?=$PROJ_ID?>&TASK_ID=<?=$TASK_ID?>'">
	    </td>
  </tr>
 </table>
</form>
</body>
</html>