<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>
<link rel="stylesheet"type="text/css" href="/<?=MYOA_STATIC_SERVER?>static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script> 
jQuery(document).ready(function(){      
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
}); 
</script> 
<script>
function CheckForm()
{
   if(document.form1.GROUP_NAME.value=="")
      alert("<?=_("组名不能为空！")?>");
   else
      document.form1.submit();
   
}

</script>

<body class="bodycolor" onload="form1.GROUP_NAME.focus()">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建分组")?></span>
    </td>
  </tr>
</table>

<br>
<form action="insert.php" name="form1" id="form1" method="post" enctype="multipart/form-data">
<table class="TableBlock"  width="470" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("排序号：")?></td>
      <td class="TableData"><input type="text" name="ORDER_NO" size="8" class="BigInput validate[required,custom[number],maxSize[10]]" data-prompt-position="centerRight:0,-6"></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("分组名称：")?></td>
      <td class="TableData"><input type="text" name="GROUP_NAME" size="20" class="BigInput validate[maxSize[25]]" data-prompt-position="centerRight:0,-6"></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("公布范围（部门）")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="">
        <textarea cols=30 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("公布范围（角色）")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="">
        <textarea cols=30 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("公布范围（人员）")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="">
        <textarea cols=30 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('107','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
    <tr>
      <td nowrap class="TableControl" colspan="2" align="center">
          <input type="button" value="<?=_("提交")?>" class="BigButton" title="<?=_("提交数据")?>" name="button1" OnClick="CheckForm()">&nbsp&nbsp&nbsp&nbsp
          <input type="button" value="<?=_("返回")?>" class="BigButton" title="<?=_("返回")?>" name="button2" OnClick="location='index.php'">
      </td>
    </tr>
    </form>
</table>
    
</body>
</html>