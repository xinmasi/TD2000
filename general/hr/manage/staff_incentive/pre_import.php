<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���͹�����Ϣ����");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">

function CheckForm2()
{
   if(document.form2.EXCEL_FILE.value=="")
   { alert("<?=_("��ѡ��Ҫ������ļ���")?>");
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
      <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3">&nbsp;<?=_("���͹�����Ϣ����")?></span>
      </td>
</tr>
</table>
<br>
<table class="TableBlock" align="center" width="70%">  
 <form name="form2" method="post" action="import.php" enctype="multipart/form-data" onSubmit="return CheckForm2();">
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b><?=_("���ص���ģ�壺")?></b></td>
   <td width="400" align="left">
   	<a href="#" onClick="window.location='templet_export.php'"><?=_("���͹�����Ϣ����ģ������")?></a>
   </td>
 </tr>
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b>&nbsp;&nbsp;<?=_("ѡ�����ļ���")?></b></td>
   <td align="left" width="400">
    <input type="file" name="EXCEL_FILE" class="BigInput" size="30">
    <input type="hidden" name="FILE_NAME">
    <input type="hidden" name="GROUP_ID" value="<?=$GROUP_ID?>">
   </td>
 </tr> 
 <tr class="TableData" align="center" height="30">
  <td align="right"><b><?=_("˵����")?></b></td>
  <td align="left">
  	<span>
    <?=_("1���뵼��.xls�ļ���")?>
    <br>  		
    <?=_("2���û�������λԱ��(OA����ʵ����)������дһ�������򲻵��롣")?>
    <br>
    <?=_("3���������ڸ�ʽ�磺2007-11-12")?>
    </span>
  </td>
 </tr>  
<tr>
  <td nowrap  class="TableControl" colspan="3" align="center">
  	<input type="submit" value="<?=_("����")?>" class="BigButton">
  </td>
 </tr> 
</table>
</form>
</body>
</html>