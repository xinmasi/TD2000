<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("��������");
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
   { alert("<?=_("��ѡ��Ҫ������ļ���")?>");
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" align="absmiddle"><span class="big3"> <?=_("�Űർ��")?></span><br>
    </td>
  </tr>
</table>
  <br>
<table class="TableBlock" align="center" width="500">  
<form name="form1" method="post" action="import_submit.php?FLOW_ID=<?=$FLOW_ID?>" enctype="multipart/form-data" onSubmit="return CheckForm();">
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b><?=_("���ص���ģ�壺")?></b></td>
   <td width="400" align="left">
   	<a href="#" onClick="window.location='templet_export.php'"><?=_("�Ű�ģ������")?></a>
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
  <td width="150" align="right"><b><?=_("˵����")?></b></td>
  <td width="400" align="left">
  	<span>
    <?=_("1���������Ű�ģ�棬�ڶ�Ӧ��������Ӧ���ݡ�ע�⣬�����ļ��е��û�������Ϊ�ա�")?>
    <br>
    <?=_("2���Ű����ͺ�ֵ������Ҫ�����Ѵ��ڵ��Ű��ֵ�����ͣ������������Ͳ����ڣ��򲻵��롣")?>
    <br>
    <?=_("3���Ű࿪ʼʱ����Ű����ʱ�䲻��Ϊ�գ����򲻵��롣")?>
    </span>
  </td>
 </tr>  
 <tr class="TableData" align="center" height="30">
  <td width="150" align="right"><b><?=_("���ѣ�")?></b></td>
  <td width="400" align="left"> 
	<?=sms_remind(55);?>
  </td>
</tr>
<?
}
?>
<tr>
  <td nowrap  class="TableControl" colspan="3" align="center">
  	<input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;
  	<input type="button" value="<?=_("�ر�")?>" class="BigButton" onclick="parent.close();">
  </td>
 </tr> 
</table>
</form>

</body>
</html>
