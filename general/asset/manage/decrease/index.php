<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("减少资产管理");
include_once("inc/header.inc.php");
?>


<script>
function sendForm()
{
   document.form1.submit();
}
</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif" align="absmiddle"><span class="big3"> <?=_("减少固定资产")?></span><br>
    </td>
  </tr>
</table>
<br>

<table class="TableBlock" width="95%">
 <form enctype="multipart/form-data" action="submit.php"  method="post" name="form1">
  <tr>
   <td nowrap class="TableData" width="80"><?=_("减少类型：")?></td>
   <td nowrap class="TableData">
    <select name="DCR_PRCS_ID" class="BigSelect">
<?
 $query="SELECT * from CP_PRCS_PROP where left(PRCS_CLASS,1)='D' order by PRCS_CLASS";
 $cursor=exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $PRCS_ID=$ROW["PRCS_ID"];
    $PRCS_LONG_DESC=$ROW["PRCS_LONG_DESC"];
?>
        <option value="<?=$PRCS_ID?>"><?=$PRCS_LONG_DESC?></option>
<?
 }
?>
    </select>
   </td>
  </tr>
  <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="hidden" value="<?=$CPTL_ID?>"  name="CPTL_ID">
        <input type="button" value="<?=_("确定")?>" class="BigButton" onclick="sendForm();">
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();">
      </td>
    </tr>
   </form>
</table>

</body>
</html>