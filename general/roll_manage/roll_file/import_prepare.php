<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("导入文件");
include_once("inc/header.inc.php");
?>


<script Language="JavaScript">

function CheckForm2()
{
   if(document.form2.EXCEL_FILE.value=="")
   { alert("<?=_("请选择要导入的文件！")?>");
     return (false);
   }
   if (document.form2.EXCEL_FILE.value!="")
   {
     var file_temp=document.form2.EXCEL_FILE.value,file_name;
     var Pos;
     Pos=file_temp.lastIndexOf("\\");
     file_name=file_temp.substring(Pos+1,file_temp.length);
     document.form2.FILE_NAME.value=file_name;
   }

   return (true);
}

</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
      <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3">&nbsp;<?=_("导入文件")?></span>
      </td>
</tr>
</table>
<br>
<form name="form2" method="post" action="import.php" enctype="multipart/form-data" onSubmit="return CheckForm2();">
    <table class="TableBlock" align="center" width="70%">  
        <tr class="TableData" align="center" height="30">
            <td width="150" align="right"><b><?=_("下载导入模板：")?></b></td>
            <td width="400" align="left">
                <a href="#" onClick="window.location='templet_export.php'"><?=_("文件导入模板下载")?></a>
            </td>
        </tr>
        <tr class="TableData" align="center" height="30">
            <td width="150" align="right"><b>&nbsp;&nbsp;<?=_("选择导入的Excel文件：")?></b></td>
            <td align="left" width="400">
                <input type="file" name="EXCEL_FILE" class="BigInput" size="30" style="height:22px;line-height:22px;padding:1px;margin:0;">
                <input type="hidden" name="FILE_NAME">
                <label><?=_("是否自动覆盖:")?></label>
                <input type="radio" name="AUTO_COVER" value="1" checked><?=_('是')?>
                <input type="radio" name="AUTO_COVER" value="0"><?=_('否')?>
            </td>
        </tr> 
        <tr class="TableData" align="center" height="30">
            <td width="150" align="right"><b><?=_("说明：")?></b></td>
            <td width="400" align="left">
                <span>
                <?=_("1、请使用文件模板导入数据！")?>
                <br>
                <?=_("2、<span style='color:red;'>注意，文件号、文件标题、密级为必填项。</span>")?>
                </span>
            </td>
        </tr>  
        <tr>
            <td nowrap  class="TableControl" colspan="3" align="center">
                <input type="submit" value="<?=_("导入")?>" class="BigButton">
            </td>
        </tr> 
    </table>
</form>

</body>
</html>