<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>


<script>
function CheckForm()
{
   if(document.form1.GROUP_NAME.value=="")
      alert("<?=_("��������Ϊ�գ�")?>");
   else
      document.form1.submit();

}

</script>

<body class="bodycolor" onload="form1.ORDER_NO.focus()">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�����")?></span>
    </td>
  </tr>
</table>

<br>

<table class="TableBlock" width="400" align="center">
  <form action="insert.php" name="form1">
    <tr>
      <td nowrap class="TableContent"><?=_("����ţ�")?></td>
      <td class="TableData"><input type="text" name="ORDER_NO" size="20" class="BigInput"></td>
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"><?=_("�û������ƣ�")?></td>
      <td class="TableData"><input type="text" name="GROUP_NAME" size="30" class="BigInput"></td>
    </tr>
    <tr>
      <td nowrap class="TableControl" colspan="2" align="center">
          <input type="button" value="<?=_("�ύ")?>" class="BigButton" title="<?=_("�ύ����")?>" name="button1" OnClick="CheckForm()">&nbsp&nbsp&nbsp&nbsp
          <input type="button" value="<?=_("����")?>" class="BigButton" title="<?=_("����")?>" name="button2" OnClick="location='index.php'">
      </td>
    </tr>
    </form>
</table>

</body>
</html>