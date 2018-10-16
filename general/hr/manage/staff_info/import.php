<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("导入数据");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if($FILE_NAME=="")
{
?>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.EXCEL_FILE.value=="")
   { alert("<?=_("请选择要导入的文件！")?>");
     return (false);
   }

   if (document.form1.EXCEL_FILE.value!="")
   {
     var file_temp=document.form1.EXCEL_FILE.value,file_name;
     var Pos;
     Pos=file_temp.lastIndexOf("\\");
     file_name=file_temp.substring(Pos+1,file_temp.length);
     document.form1.FILE_NAME.value=file_name;
   }

   return (true);
}

</script>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" align="absmiddle"><span class="big3"> <?=_("导入人事数据")?></span><br>
    </td>
  </tr>
</table>
  <br>
<form name="form1" method="post" action="import_submit.php?FLOW_ID=<?=$FLOW_ID?>" enctype="multipart/form-data" onSubmit="return CheckForm();">
<table class="TableBlock" align="center" width="70%">  
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b><?=_("下载导入模板：")?></b></td>
   <td width="400" align="left">
   	<a href="#" onClick="window.location='templet_export.php'"><?=_("人事档案模板下载")?></a>
   </td>
 </tr>
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b>&nbsp;&nbsp;<?=_("选择导入文件：")?></b></td>
   <td align="left" width="400">
    <input type="file" name="EXCEL_FILE" class="BigInput" size="30">
    <input type="hidden" name="FILE_NAME">
   </td>
 </tr> 
 <tr class="TableData" align="center" height="30">
  <td width="150" align="right"><b><?=_("说明：")?></b></td>
  <td width="400" align="left">
  	<span>
    <?=_("1、使用人事档案模板的前三列为姓名、用户名、部门，部门名必须在OA系统管理中存在，余下的列名称必须与人员档案基本信息相对应；")?>
    <br>
    <?=_("2、人事档案模板中，用户名、姓名、部门、角色不能为空，为空则不能导入；")?>
    <br>
    <?=_("3、日期的格式应如：2009-10-15。")?>
    <br>
    <?=_("4、如部门存在多个需要写长部门名（必须是完整的），如：\OA项目组\第一开发组")?>
    </span>
  </td>
 </tr>  
<?
}
?>
<tr>
  <td nowrap  class="TableControl" colspan="3" align="center">
  	<input type="submit" value="<?=_("导入")?>" class="BigButton">
  </td>
 </tr> 
</table>
</form>

</body>
</html>
