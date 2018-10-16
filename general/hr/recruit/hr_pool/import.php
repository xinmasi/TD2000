<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("导入数据");
include_once("inc/header.inc.php");
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


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" align="absmiddle"><span class="big3"> <?=_("导入人才数据")?></span><br>
    </td>
  </tr>
</table>
  <br>
<table class="TableBlock" align="center" width="70%">  
<form name="form1" method="post" action="import_submit.php?FLOW_ID=<?=$FLOW_ID?>" enctype="multipart/form-data" onSubmit="return CheckForm();">
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b><?=_("下载导入模板：")?></b></td>
   <td width="400" align="left">
   	<a href="#" onClick="window.location='templet_export.php'"><?=_("人才档案模板下载")?></a>
   </td>
 </tr>
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b>&nbsp;&nbsp;<?=_("选择导入文件：")?></b></td>
   <td align="left" width="400">
    <input type="file" name="EXCEL_FILE" class="BigInput" size="30">
    <input type="hidden" name="FILE_NAME">
    <input type="hidden" name="GROUP_ID" value="<?=$GROUP_ID?>">
   </td>
 </tr> 
<tr class="TableData" align="center" height="30">
  <td width="150" align="right"><b><?=_("说明：")?></b></td>
  <td width="400" align="left">
  	<span>
    <?=_("1、人才档案模板的人才档案表的前三列为计划名称、岗位、应聘人姓名，计划名称必须在招聘计划中存在，余下的列必须与人才档案基本信息相对应；")?>
    <br>
    <?=_("2、计划名称、应聘者姓名这两项不能为空，否则不能导入；")?>
    <br>
    <?=_("4、日期的格式应如：2009-10-15。")?>
    </span>
  </td>
 </tr>  
<tr>
  <td nowrap  class="TableControl" colspan="2" align="center">
  	<input type="submit" value="<?=_("导入")?>" class="BigButton">
  </td>
 </tr> 
</table>
</form>

</body>
</html>
