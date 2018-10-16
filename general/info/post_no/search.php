<?
include_once("inc/auth.inc.php");
mysql_select_db("BUS", TD::conn());

$HTML_PAGE_TITLE = _("邮政编码查询");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("邮政编码查询结果")?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

<?
 //============================ 显示市名 =======================================
 if($AREA!="")
    $query = "SELECT * from POST_TEL where POST_NO<>'' and (CITY like '%$AREA%' or COUNTY like '%$AREA%' or TOWN like '%$AREA%') order by POST_NO";
 else
    $query = "SELECT * from POST_TEL where POST_NO like '%$POST_NO%' order by POST_NO";

 $cursor= exequery(TD::conn(),$query);

 $AREA_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $AREA_COUNT++;
    if($AREA_COUNT==1)
    {
?>
  <table class="TableList" width="450" align="center">
    <tr class="TableHeader">
    	<td nowrap align="center"><?=_("省(直辖市/自治区)")?></td>
      <td nowrap align="center"><?=_("城市")?></td>
      <td nowrap align="center"><?=_("区/县")?></td>
      <td nowrap align="center"><?=_("街道")?></td>
      <td nowrap align="center"><?=_("邮编")?></td>
    </tr>
<?
    }

    $PROVINCE=$ROW["PROVINCE"];
    $CITY=$ROW["CITY"];
    $COUNTY=$ROW["COUNTY"];
    $TOWN=$ROW["TOWN"];
    $POST_NO=$ROW["POST_NO"];

    if($AREA_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$PROVINCE?></td>
      <td nowrap align="center"><?=$CITY?></td>
      <td nowrap align="center"><?=$COUNTY?></td>
      <td nowrap align="center"><?=$TOWN?></td>
      <td nowrap align="center"><?=$POST_NO?></td>
    </tr>
<?
 }

 if($AREA_COUNT>0)
 {
?>
  </table>
<?
 }
 else
   Message(_("提示"),_("没有符合条件的结果"));
?>

</div>

<?
Button_Back();
?>
</body>
</html>