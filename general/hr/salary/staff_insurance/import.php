<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("导入数据");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
 $query = "SELECT count(*) from SAL_DATA where FLOW_ID='$FLOW_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
   $ROW_COUNT=$ROW[0];
 }
?>
<script type="text/javascript">
function CheckForm()
{
   if(document.form1.EXCEL_FILE.value=="")
   {
   	 alert("<?=_("请选择要导入的文件！")?>");
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
<?
 if ($ROW_COUNT!=0)
  {
?>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" align="absmiddle"><span class="big3"> <?=_("导入xls工资数据(该流程已录入过数据)")?></span><br>
    </td>
<?
  }
  else
  {
?>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" align="absmiddle"><span class="big3"> <?=_("导入xls工资数据")?></span><br>
    </td>
<?
  }
?>
  </tr>
</table>
<br>
	
<table class="TableBlock" align="center" width="70%">
<form name="form1" method="post" action="import_submit.php?FLOW_ID=<?=$FLOW_ID?>" enctype="multipart/form-data" onSubmit="return CheckForm();">
 <tr class="TableData" align="center" height="30">
  <td width="150" align="right"><b><?=_("下载导入模板：")?></b></td>
  <td width="400" align="left">
  	<a href="#" onClick="window.location='templet_export.php'"><?=_("员工薪酬基数")?></a>
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
    <?=_("1、请导入.xls文件。")?>
    <br>
    <?=_("2、使用工资报表模板导入数据，先填内容再导入。")?>
    <br>
    <?=_("3、工资报表模板中，姓名不能为空，为空则不能导入。")?>
    <br>
    <?=_("4、日期的格式应如：2009-10-15。")?>
    </span>
  </td>
 </tr>
 <tr>
  <td nowrap  class="TableControl" colspan="2" align="center">
  	<input type="submit" value="<?=_("导入")?>" class="BigButton">&nbsp;&nbsp;&nbsp;
    <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';" title="<?=_("返回")?>">
  </td>
 </tr> 
</form>
</table>

</body>
</html>
