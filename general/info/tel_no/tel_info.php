<?
while(list($GET_KEY, $GET_VALUE) = each($_GET))
{
   $GET_VALUE = preg_replace("/<(script|iframe)(.|\n)*$/i", "", $GET_VALUE);
   $_GET[$GET_KEY] = $GET_VALUE;
   $$GET_KEY = $GET_VALUE;
}

include_once("inc/auth.inc.php");
mysql_select_db("BUS", TD::conn());

$HTML_PAGE_TITLE = _("电话区号");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("电话区号")?> - <?=$PROVINCE?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

<?
if(!$WORLD)
{
?>
  <table class="TableList" width="450" align="center">
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("城市")?></td>
      <td nowrap align="center"><?=_("区/县")?></td>
      <td nowrap align="center"><?=_("街道")?></td>
      <td nowrap align="center"><?=_("电话区号")?></td>
    </tr>
<?
}
else
{
?>
  <table class="TableList" width="450" align="center">
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("国家")?></td>
      <td nowrap align="center"><?=_("城市")?></td>
      <td nowrap align="center"><?=_("地区")?></td>
      <td nowrap align="center"><?=_("电话区号")?></td>
    </tr>
<?
}

 //============================ 显示市名 =======================================
 $query = "SELECT * from POST_TEL where PROVINCE='$PROVINCE' order by tel_no";
 $cursor= exequery(TD::conn(),$query);

 $AREA_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $AREA_COUNT++;

    $CITY=$ROW["CITY"];
    $COUNTY=$ROW["COUNTY"];
    $TOWN=$ROW["TOWN"];
    $TEL_NO=$ROW["TEL_NO"];

    if($AREA_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$CITY?></td>
      <td nowrap align="center"><?=$COUNTY?></td>
      <td nowrap align="center"><?=$TOWN?></td>
      <td nowrap align="center"><?=$TEL_NO?></td>
    </tr>
<?
 }
?>
  </table>

</div>

<?
Button_Back();
?>
</body>
</html>
