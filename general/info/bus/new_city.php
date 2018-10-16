<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("新建城市");
include_once("inc/header.inc.php");
?>


<script>
function CheckForm()
{
   if(document.form1.CITY_NAME.value == "")
   {
      alert("<?=_("城市名不能为空")?>");
      return false;
   }
   if(document.form1.CITY_ID.value == "")
   {
      alert("<?=_("城市简称不能为空")?>");
      return false;
   }
   <?
   mysql_select_db("BUS", TD::conn());
   
   $query = "SELECT * from city";
	 $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {   $CITY_ID2=$ROW["city_id"];
   ?>
   if(document.form1.CITY_ID.value=="<?=$CITY_ID2?>")
   {
   	  alert("<?=_("城市简称已经存在")?>");
      return false;
   }
   <?
   }
   ?>
   document.form1.submit();
}

</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/info.gif" height="22" width="20" align="absmiddle"><span class="big3">  <?=_("新建城市")?></span>
    </td>
  </tr>
</table>
<?
mysql_select_db("BUS", TD::conn());

   $query="SELECT * from CITY where ID='$ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $ID=$ROW["id"];
      $CITY_ID=$ROW["CITY_ID"];
      $CITY_NAME=$ROW["CITY_NAME"];
   }

?>
<table class="TableBlock" width="350" align="center">
<form enctype="multipart/form-data" action="add_city.php" method="post" name="form1">
  <tr>
    <td nowrap class="TableContent" width="80"><?=_("城市：")?></td>
    <td class="TableData" colspan="3">
    	<input type="text" name="CITY_NAME" size="20" maxlength="100" class="BigInput" value="<?=$CITY_NAME?>">
    </td>
  </tr>
  <tr>
    <td nowrap class="TableContent" width="80"><?=_("城市简称：")?></td>
    <td class="TableData" colspan="3">
    	<input type="text" name="CITY_ID" size="10" maxlength="100" class="BigInput" value="<?=$CITY_ID?>">  <?=_("（如“北京”简称“BJ”）")?>
    </td>
  </tr>
  <tr class="TableControl">
    <td nowrap colspan="4" align="center">
      <input type="button" value="<?=_("保存")?>" class="BigButton"  onClick="CheckForm();">&nbsp;&nbsp;
      <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="history.back();">
    </td>
  </tr>
</form>
</table>
</body>
</html>