<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("����̶��ʲ�");
include_once("inc/header.inc.php");
?>

<script>
function CheckForm()
{
   if(document.getElementById("upload_file").value == "")
   {
	    alert("<?=_("�����ļ�����Ϊ��")?>");
		  return false;
	 }
	 else
   {
   	  document.form1.submit();
	 }
}	
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif" align="absmiddle"><span class="big3"> <?=_("����̶��ʲ�")?></span><br></td>
    </tr>
</table>
<br />
<br />
<form name="form1" method="post" action="import_new.php" enctype="multipart/form-data" onSubmit="return CheckForm();">
    <table class="TableBlock" align="center" width="70%">
        <tr class="TableData" align="center" height="30">
            <td width="150" align="right"><b>���ص���ģ�壺</b></td>
            <td width="400" align="left">
                <a href="#" onClick="window.location='templet_export.php'"><?=_("�̶��ʲ�������ģ������")?></a>
            </td>
        </tr>
        <tr class="TableData" align="center" height="30">
            <td width="150" align="right"><b>&nbsp;&nbsp;ѡ�����ļ���</b></td>
            <td align="left" width="400">
                <input type="file" name="EXCEL_FILE" class="BigInput" size="30">
                <input type="hidden" name="FILE_NAME">
            </td>
        </tr>
        <tr class="TableData" align="center" height="30">
            <td width="150" align="right"><b>˵����</b></td>
            <td width="400" align="left">
  	<span>
    1�������ļ��뵽�������ý��е������ã�������Ӧ�ĵ����ֶ�ƥ�䣻    <br>
    2��ģ���У������˲���Ϊ�գ�Ϊ�����ܵ��룻    <br>
    3�����ڵĸ�ʽӦ�磺2009-10-15��    </span>
            </td>
        </tr>
        <tr>
            <td nowrap  class="TableControl" colspan="3" align="center">
                <input type="submit" value="����" class="BigButton">
            </td>
        </tr>
    </table>
</form>
</body>
</html>
