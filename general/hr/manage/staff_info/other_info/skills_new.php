<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("�½��Ͷ�������Ϣ");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
   if(document.form1.STAFF_NAME.value=="")
   { 
      alert("<?=_("Ա����������Ϊ�գ�")?>");
      return (false);
   }
   if(document.form1.ABILITY_NAME.value=="")
   { 
      alert("<?=_("�������Ʋ���Ϊ�գ�")?>");
      return (false);
   }
   if(document.form1.EXPIRES.value=="")
   { 
      alert("<?=_("��Ч�ڲ���Ϊ�գ�")?>");
      return (false);
   }
   if(document.form1.ISSUE_DATE.value=="")
   { 
      alert("<?=_("��ѡ��֤���ڣ�")?>");
      return (false);
   }
   if(document.form1.ISSUE_DATE.value!="" && document.form1.EXPIRE_DATE.value!="" && document.form1.ISSUE_DATE.value > document.form1.ISSUE_DATE.value)
   { 
      alert("<?=_("֤�鵽�����ڲ���С�ڷ�֤���ڣ�")?>");
      return (false);
   }
 return (true);
}
function upload_attach()
{
  if(CheckForm())
   {   
     document.form1.submit();
   }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?SKILLS_ID=<?=$SKILLS_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½��Ͷ�������Ϣ")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="skills_add.php"  method="post" name="form1" onsubmit="return CheckForm();">
<table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("��λԱ����")?></td>
      <td class="TableData">
<?
$STAFF_NAME =$USER_ID;
$STAFF_NAME1 = substr( getUserNameById($USER_ID), 0, -1);
?>
        <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="<?=_("$STAFF_NAME1")?>">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("ѡ��")?></a>
      </td>
      <td nowrap class="TableData"><?=_("�������ƣ�")?></td>
      <td class="TableData">
        <INPUT type="text"name="ABILITY_NAME" class=BigInput size="15" value="<?=$ABILITY_NAME?>">
      </td>
    </tr>
    <tr>
    	 <td nowrap class="TableData"><?=_("������ҵ��")?></td>
      <td class="TableData">
       <INPUT type="radio" name="SPECIAL_WORK" value="1" checked> <?=_("��")?>&nbsp;&nbsp;  
			 <INPUT type="radio" name="SPECIAL_WORK" value="0"> <?=_("��")?> 
      </td>
    	<td nowrap class="TableData"><?=_("����")?></td>
      <td class="TableData">
       <INPUT type="text"name="SKILLS_LEVEL" class=BigInput size="15" value="<?=$SKILLS_LEVEL?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����֤��")?></td>
      <td class="TableData">
        <INPUT type="radio" name="SKILLS_CERTIFICATE" value="1" checked> <?=_("��")?>&nbsp;&nbsp;  
			  <INPUT type="radio" name="SKILLS_CERTIFICATE" value="0"> <?=_("��")?> 
      </td>
      <td nowrap class="TableData"><?=_("��֤���ڣ�")?></td>
      <td class="TableData">
       <input type="text" name="ISSUE_DATE" size="15" maxlength="10" class="BigInput" value="<?=$ISSUE_DATE?>" onClick="WdatePicker()"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��Ч�ڣ�")?></td>
      <td class="TableData">
        <INPUT type="text"name="EXPIRES" class=BigInput size="15" value="<?=$EXPIRES?>">&nbsp;<?=_("��")?>
      </td>
      <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
      <td class="TableData">
       <input type="text" name="EXPIRE_DATE" size="15" maxlength="10" class="BigInput" value="<?=$EXPIRE_DATE?>" onClick="WdatePicker()"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��֤����/��λ��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="ISSUING_AUTHORITY" cols="78" rows="3" class="BigInput" value=""><?=$ISSUING_AUTHORITY?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ע��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="78" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
      </td>
    </tr> 
    <tr height="25" id="attachment1">
      <td nowrap class="TableData" ><span id="ATTACH_LABEL"><?=_("�����ϴ���")?></span></td>
      <td class="TableData"colspan=3>
        <script>ShowAddFile();</script>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
        <input type="submit" value="<?=_("����")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>
</body>
</html>