<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("�����ע");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<script type="text/javascript">
	function checkForm(){
		if(trim(document.form1.WRITE_TIME.value) == ""){
			alert("ʱ�䲻��Ϊ�գ�");
			return false;
		}
		if(trim(document.form1.CONTENT.value) == ""){
			alert("��ע���ݲ���Ϊ�գ�");
			return false;
		}
	}

	function trim(str){
		return str.replace(/(^\s*)|(\s*$)/g,"");
	}	
	
</script>
<form action="add.php"  method="post" name="form1" onsubmit="return checkForm();"> 
<table class="TableList" width="95%"  align="center" > 
   <tr>
    <td nowrap class="TableContent" width="90"><?=_("��עʱ�䣺")?></td>
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
?>
    <td class="TableData">
      <input type="text" name="WRITE_TIME" size="19" readonly maxlength="100" class="BigStatic" value="<?=$CUR_TIME?>">       
    </td>
   </tr>
   <tr>
     <td nowrap class="TableContent"> <?=_("��ע���ݣ�")?></td>
     <td class="TableData" colspan="1">
       <textarea name="CONTENT" class="BigInput" cols="80" rows="6"></textarea>
     </td>
   </tr>
    <tr>
      <td nowrap class="TableContent"> <?=_("������Ŀ��Ա��")?></td>
      <td class="TableData">
		<?=sms_remind(42);?>
      </td>
    </tr>       
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="hidden" value="<?=$PROJ_ID?>" name="PROJ_ID">
      <input type="hidden" value="<?=$CUR_TIME?>" name="CUR_TIME">
      <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;&nbsp;
    </td>
</table>
</form>
</body>
</html>