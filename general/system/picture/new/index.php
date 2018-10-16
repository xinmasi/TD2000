<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("新建图片目录");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.TO_ID.value==""&&document.form1.PRIV_ID.value==""&&document.form1.COPY_TO_ID.value=="")
   {
   	 alert("<?=_("请至少指定一种发布范围！")?>");
     return (false);
   }

   if(document.form1.PIC_NAME.value=="")
   {
   	 alert("<?=_("图片目录名称不能为空！")?>");
     return (false);
   }

   if(document.form1.PIC_PATH.value=="")
   {
   	 alert("<?=_("图片目录路径不能为空！")?>");
     return (false);
   }

   return (true);
}

</script>


<body class="bodycolor" onload="document.form1.PIC_NAME.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("新建图片目录")?></span>
    </td>
  </tr>
</table>

<br>
 <table class="TableBlock"  width="85%" align="center">
  <form action="submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><?=_("发布范围（部门）：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID">
        <textarea cols=40 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("发布范围（角色）：")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="">
        <textarea cols=40 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("发布范围（人员）：")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="">
        <textarea cols=40 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('116','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"> <?=_("图片目录名称：")?></td>
      <td class="TableData">
        <input type="text" name="PIC_NAME" size="36" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("图片目录路径：")?></td>
      <td class="TableData">
        <input type="text" name="PIC_PATH" size="36" class="BigInput">  <?=_("说明：OA服务器的本地路径(如:D:\MYOA)")?>
      </td>
    </tr>
    <tr>	
    	<td nowrap class="TableData"><?=_("图片显示行/列：")?></td>
    	<td class="TableData"><?=sprintf(_("每页显示%s行，每行显示%s个"), '<input type="text" name="ROW_PIC" id="ROW_PIC" size="10" class="BigInput" value="'.($ROW_PIC==""?5:$ROW_PIC).'" />','<input type="text" name="ROW_PIC_NUM" id="ROW_PIC_NUM" size="10" class="BigInput" value="'.($ROW_PIC_NUM==""?7:$ROW_PIC_NUM).'" />')?>
    	</td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='../'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>