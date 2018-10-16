<?
include_once("inc/auth.inc.php");


$HTML_PAGE_TITLE = _("导入数据");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

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
function CheckForm2()
{
   if(document.form2.EXCEL_FILE2.value=="")
   { alert("<?=_("请选择要导入的文件！")?>");
     return (false);
   }

   if (document.form2.EXCEL_FILE2.value!="")
   {
     var file_temp=document.form2.EXCEL_FILE2.value,file_name;
     var Pos;
     Pos=file_temp.lastIndexOf("\\");
     file_name=file_temp.substring(Pos+1,file_temp.length);
     document.form2.FILE_NAME2.value=file_name;
   }

   return (true);
}
function KaoQin()
{
	  document.getElementById("lunban").style.display= "none";
	  document.getElementById("kaoqin").style.display= "inline";
}
function LunBan()
{
     document.getElementById( "kaoqin").style.display= "none";
     document.getElementById( "lunban").style.display= "inline";
}
</script>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big">
    	<img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" align="absmiddle"><span class="big3"> <?=_("导入上下班登记记录")?></span>
        <input type="button" value="<?=_("考勤导入")?>" class="BigButton" onclick="KaoQin();">
        <input type="button" value="<?=_("轮班导入")?>" class="BigButton" onclick="LunBan();">
    <br></td>
  </tr>
</table>
  <br>

<div id="kaoqin" name="kaoqin" style="display:inline;">
<table class="TableBlock" align="center" width="70%">  
<form name="form1" method="post" action="import_submit.php?FLOW_ID=<?=$FLOW_ID?>" enctype="multipart/form-data" onSubmit="return CheckForm();">
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b><?=_("下载导入模板：")?></b></td>
   <td width="400" align="left">
   	<a href="#" onClick="window.location='templet_export.php'"><?=_("考勤模板下载")?></a>
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
    <?=_("1、先下载考勤导入模版，在对应列填上相应数据。注意，导入文件中的用户名与姓名必须填写一项。")?>
    <br>
    <?=_("2、禁止修改导入模版列的顺序。")?>
    </span>
  </td>
 </tr>  
<tr>
  <td nowrap  class="TableControl" colspan="3" align="center">
  	<input type="submit" value="<?=_("导入")?>" class="BigButton">&nbsp;
  	<input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='index1.php'";>
  </td>
 </tr> 
</form>
</table>
</div>

<div id="lunban" name="lunban" style="display:none;">
<table class="TableBlock" align="center" width="70%">  
<form name="form2" method="post" action="import_submit2.php?FLOW_ID=<?=$FLOW_ID?>" enctype="multipart/form-data" onSubmit="return CheckForm2();">
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b><?=_("下载导入模板：")?></b></td>
   <td width="400" align="left">
   	<a href="#" onClick="window.location='templet_export2.php'"><?=_("轮班考勤模板下载")?></a>
   </td>
 </tr>
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b>&nbsp;&nbsp;<?=_("选择导入文件：")?></b></td>
   <td align="left" width="400">
    <input type="file" name="EXCEL_FILE2" class="BigInput" size="30">
    <input type="hidden" name="FILE_NAME2">
    <input type="hidden" name="GROUP_ID2" value="<?=$GROUP_ID?>">
   </td>
 </tr> 
 <tr class="TableData" align="center" height="30">
  <td width="150" align="right"><b><?=_("说明：")?></b></td>
  <td width="400" align="left">
  	<span>
    <?=_("1、先下载轮班考勤导入模版，在对应列填上相应数据。注意，导入文件中的用户名与姓名必须填写一项。")?>
    <br>
    <?=_("2、禁止修改导入模版列的顺序。")?>
    </span>
  </td>
 </tr>  
<tr>
  <td nowrap  class="TableControl" colspan="3" align="center">
  	<input type="submit" value="<?=_("导入")?>" class="BigButton">&nbsp;
  	<input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='index1.php'";>
  </td>
 </tr> 
</form>
</table>
</div>


</body>
</html>
