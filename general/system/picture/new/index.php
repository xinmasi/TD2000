<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�½�ͼƬĿ¼");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.TO_ID.value==""&&document.form1.PRIV_ID.value==""&&document.form1.COPY_TO_ID.value=="")
   {
   	 alert("<?=_("������ָ��һ�ַ�����Χ��")?>");
     return (false);
   }

   if(document.form1.PIC_NAME.value=="")
   {
   	 alert("<?=_("ͼƬĿ¼���Ʋ���Ϊ�գ�")?>");
     return (false);
   }

   if(document.form1.PIC_PATH.value=="")
   {
   	 alert("<?=_("ͼƬĿ¼·������Ϊ�գ�")?>");
     return (false);
   }

   return (true);
}

</script>


<body class="bodycolor" onload="document.form1.PIC_NAME.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�½�ͼƬĿ¼")?></span>
    </td>
  </tr>
</table>

<br>
 <table class="TableBlock"  width="85%" align="center">
  <form action="submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><?=_("������Χ�����ţ���")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID">
        <textarea cols=40 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("������Χ����ɫ����")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="">
        <textarea cols=40 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("������Χ����Ա����")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="">
        <textarea cols=40 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('116','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"> <?=_("ͼƬĿ¼���ƣ�")?></td>
      <td class="TableData">
        <input type="text" name="PIC_NAME" size="36" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("ͼƬĿ¼·����")?></td>
      <td class="TableData">
        <input type="text" name="PIC_PATH" size="36" class="BigInput">  <?=_("˵����OA�������ı���·��(��:D:\MYOA)")?>
      </td>
    </tr>
    <tr>	
    	<td nowrap class="TableData"><?=_("ͼƬ��ʾ��/�У�")?></td>
    	<td class="TableData"><?=sprintf(_("ÿҳ��ʾ%s�У�ÿ����ʾ%s��"), '<input type="text" name="ROW_PIC" id="ROW_PIC" size="10" class="BigInput" value="'.($ROW_PIC==""?5:$ROW_PIC).'" />','<input type="text" name="ROW_PIC_NUM" id="ROW_PIC_NUM" size="10" class="BigInput" value="'.($ROW_PIC_NUM==""?7:$ROW_PIC_NUM).'" />')?>
    	</td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='../'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>