<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("编辑计划类型");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.TYPE_NAME.value=="")
   { alert("<?=_("类型名称不能为空！")?>");
     return (false);
   }
}
</script>


<?
 $query = "SELECT * from PLAN_TYPE where TYPE_ID='$TYPE_ID'";
 $cursor= exequery(TD::conn(),$query);

 if($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_NAME=$ROW["TYPE_NAME"];
    $TYPE_NO=$ROW["TYPE_NO"];
 }
?>

<body class="bodycolor" onload="document.form1.TYPE_NO.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑计划类型")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock"  width="450" align="center" >
  <form action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData"><?=_("排序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NO" class="BigInput" size="2" maxlength="10" value="<?=$TYPE_NO?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("计划类型名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NAME" class="BigInput" size="25" maxlength="25" value="<?=$TYPE_NAME?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="hidden" value="<?=$TYPE_ID?>" name="TYPE_ID">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='index.php'">
    </td>
  </form>
</table>

</body>
</html>