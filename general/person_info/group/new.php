<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>


<script>
function CheckForm()
{
   if(document.form1.GROUP_NAME.value=="")
      alert("<?=_("组名不能为空！")?>");
   else
      document.form1.submit();

}

</script>

<body class="bodycolor" onload="form1.ORDER_NO.focus()">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建分组")?></span>
    </td>
  </tr>
</table>

<br>

<table class="TableBlock" width="400" align="center">
  <form action="insert.php" name="form1">
    <tr>
      <td nowrap class="TableContent"><?=_("排序号：")?></td>
      <td class="TableData"><input type="text" name="ORDER_NO" size="20" class="BigInput"></td>
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"><?=_("用户组名称：")?></td>
      <td class="TableData"><input type="text" name="GROUP_NAME" size="30" class="BigInput"></td>
    </tr>
    <tr>
      <td nowrap class="TableControl" colspan="2" align="center">
          <input type="button" value="<?=_("提交")?>" class="BigButton" title="<?=_("提交数据")?>" name="button1" OnClick="CheckForm()">&nbsp&nbsp&nbsp&nbsp
          <input type="button" value="<?=_("返回")?>" class="BigButton" title="<?=_("返回")?>" name="button2" OnClick="location='index.php'">
      </td>
    </tr>
    </form>
</table>

</body>
</html>