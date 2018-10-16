<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("批量设置人力资源管理员");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("批量设置人力资源管理员")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="600" align="center" >
  <form action="all_submit.php"  method="post" name="form1">  
   <tr>
    <td nowrap class="TableData"><?=_("人力资源管理员：")?></td>
    <td nowrap class="TableData">
      <input type="hidden" name="COPY_TO_ID" value="">
      <textarea cols="50" name="COPY_TO_NAME" rows="5" class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a> 
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("人事专员：")?></td>
    <td nowrap class="TableData">
      <input type="hidden" name="TO_ID_HR" value="">
      <textarea cols="50" name="TO_NAME_HR" rows="5" class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','TO_ID_HR', 'TO_NAME_HR')"><?=_("添加")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID_HR', 'TO_NAME_HR')"><?=_("清空")?></a> 
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("请选择部门：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="">
        <textarea cols="50" name="TO_NAME" rows="5" class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('5')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("操作：")?></td>
      <td class="TableData">
        <input type="radio" name="OPERATION" value="0" id="OPERATION0" checked><label for="OPERATION0"><?=_("批量添加")?></label>
        <input type="radio" name="OPERATION" value="1" id="OPERATION1"><label for="OPERATION1"><?=_("批量删除")?></label>
      </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
      <input type="reset" value="<?=_("清空")?>" class="BigButton">
      </td>
    </tr>
  </form>
</table>

</body>
</html>