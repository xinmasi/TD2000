<?
include_once("inc/auth.inc.php");
include_once("fun_allcompute.func.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if($FILE_NAME=="")
{
 $operate="0";
 $query = "SELECT count(*) from SAL_DATA where FLOW_ID='$FLOW_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
   $ROW_COUNT=$ROW[0];
 }

?>
<script Language="JavaScript">
 function chk(input)
  {
    for(var i=0;i<document.form1.c1.length;i++)
    {
      document.form1.c1[i].checked = false;
    }

    input.checked = true;
    return true;
  }
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
  
<body class="bodycolor">
  <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
<?
   if ($ROW_COUNT!=0)
    {
?>
      <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" align="absmiddle"><span class="big3"> <?=_("����EXCEL��������(��������¼�������)")?></span><br>
      </td>
<?
    }
    else
    {
?>
      <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" align="absmiddle"><span class="big3"> <?=_("����EXCEL��������")?></span><br>
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
  <td width="150" align="right"><b><?=_("���ص���ģ�壺")?></b></td>
  <td width="400" align="left">
  	<a href="#" onClick="window.location='templet_export.php'"><?=_("�����ʵ���ģ������")?></a>
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
 <!--
 <tr class="TableData" align="center" height="30">
  <td width="150" align="right"><b><?=_("������ѡ��")?></b></td>
  <td width="400" align="left">
  	<input type="checkbox" name="compute" id="Id_compute" value="1" ><label for="Id_compute"><?=_("�����ͬʱ�������еļ�����")?></label>
  </td>
 </tr>-->
 <tr class="TableData" align="center" height="30">
  <td width="150" align="right"><b><?=_("˵����")?></b></td>
  <td width="400" align="left">
  	<span>
    <?=_("1���뵼��.xls�ļ���")?>
    <br>
    <?=_("2��ʹ�ò����ʵ���ģ�����˳��Ϊ�û�����������������Ŀ��")?>
    <br>
    <?=_("3�������ʵ���ģ���У���������Ϊ�գ�Ϊ�����ܵ��룻")?>
    </span>
  </td>
 </tr>
<?
}
?>
 <tr>
  <td nowrap  class="TableControl" colspan="3" align="center">
  	<input type="submit" value="<?=_("����")?>" class="BigButton">
    <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php?FLOW_ID=<?=$FLOW_ID?>&PAGE_START=<?=$PAGE_START?>';">
  </td>
 </tr>        
</form>
</table>

</body>
</html>
