<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" align="absmiddle"><span class="big3"> <?=_("排班导入")?></span><br>
    </td>
  </tr>
</table>
  <br>
<table class="TableBlock" align="center" width="500">  
<form name="form1" method="post" action="import_submit.php?FLOW_ID=<?=$FLOW_ID?>" enctype="multipart/form-data" onSubmit="return CheckForm();">
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b><?=_("下载导入模板：")?></b></td>
   <td width="400" align="left">
   	<a href="#" onClick="window.location='templet_export.php'"><?=_("排班模板下载")?></a>
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
    <?=_("1、先下载排班模版，在对应列填上相应数据。注意，导入文件中的用户名不能为空。")?>
    <br>
    <?=_("2、排班类型和值班类型要填入已存在的排班和值班类型，如果填入的类型不存在，则不导入。")?>
    <br>
    <?=_("3、排班开始时间和排班结束时间不能为空，否则不导入。")?>
    </span>
  </td>
 </tr>  
 <tr class="TableData" align="center" height="30">
  <td width="150" align="right"><b><?=_("提醒：")?></b></td>
  <td width="400" align="left"> 
	<?=sms_remind(55);?>
  </td>
</tr>
<?
}
?>
<tr>
  <td nowrap  class="TableControl" colspan="3" align="center">
  	<input type="submit" value="<?=_("导入")?>" class="BigButton">&nbsp;
  	<input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="parent.close();">
  </td>
 </tr> 
</table>
</form>

</body>
</html>
