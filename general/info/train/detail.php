<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("列车时刻查询");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
switch($DAY)
{
	case "0":$DAY_NAME=_("当天");break;
	case "1":$DAY_NAME=_("次日");break;
	case "2":$DAY_NAME=_("第三天");break;
	case "3":$DAY_NAME=_("第四天");break;
	case "4":$DAY_NAME=_("第五天");break;
	case "5":$DAY_NAME=_("第六天");break;
	case "6":$DAY_NAME=_("第七天");break;

}

$TRAIN_NAME=strtok($TRAIN_NAME,"<BR>");
$TRAIN_DESC=$TRAIN_NAME;
$TRAIN_NAME=strtok("<BR>");

if($TRAIN_NAME!="")
{
   $TRAIN_NAME=" - ".$TRAIN_NAME;

   $STR=strtok($TRAIN_DESC,_("到"));
   $FROM_STATION=$STR;

   $STR=strtok(_("到"));
   $TO_STATION=$STR;
}

if($TOTAL_HOUR<=0)
   $TOTAL_HOUR="";
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("乘车基本信息")?> - <?=$TRAIN_DESC?><?=$TRAIN_NAME?></span><br>
    </td>
  </tr>
</table>
<br>
<table class="TableBlock" width="95%" align="center">
	<tr class="TableHeader">
 		<td nowrap align="center"><?=_("乘车时刻")?> </td>
 		<td nowrap align="center" colspan="3"><?=_("票价信息")?> </td>
 	</tr>
	<tr class="TableData">
 		<td nowrap><?=_("开车时间：")?><?=$DEPART_TIME?> </td>
		<td nowrap><?=_("硬座票价：")?><?=$PRICE_HARDSEAT?> </td>
		<td nowrap><?=_("硬卧上：")?><?=$PRICE_HARDBED_TOP?><?if($PRICE_HARDBED_TOP=="")echo _("无");?> </td>
		<td nowrap><?=_("软卧上：")?><?=$PRICE_SOFTBED_TOP?><?if($PRICE_SOFTBED_TOP=="")echo _("无");?> </td>
 	</tr>
	<tr class="TableData">
 		<td nowrap><?=_("到达时间：")?><?=$DAY_NAME?><?=$ARRIVE_TIME?> </td>
		<td nowrap> </td>
		<td nowrap><?=_("硬卧中：")?><?=$PRICE_HARDBED_MIDDLE?><?if($PRICE_HARDBED_MIDDLE=="")echo _("无");?> </td>
		<td nowrap> </td>
	</tr>
	<tr class="TableData">
 		<td nowrap><?=_("运行时间：")?><?=$TOTAL_HOUR?><?=_("小时")?><?=$TOTAL_MINUTE?><?=_("分钟")?> </td>
		<td nowrap><?=_("软座票价：")?><?=$PRICE_SOFTSEAT?><?if($PRICE_SOFTSEAT=="")echo _("无");?> </td>
 		<td nowrap><?=_("硬卧下：")?><?=$PRICE_HARDBED_BELOW?><?if($PRICE_HARDBED_BELOW=="")echo _("无");?> </td>
 		<td nowrap><?=_("软卧下：")?><?=$PRICE_SOFTBED_BELOW?><?if($PRICE_SOFTBED_BELOW=="")echo _("无");?> </td>
	</tr>
</table>

<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<br>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("列车经停站次")?> - <?=$TRAIN_DESC?> - 
    	<?
    		$msg=sprintf(_("全程%d公里，共经过%d个车站"),$DISTANCE,$NUMBER_OF_STATION);
    	?>
    	<?=$msg?>
    	</span><br>
    </td>
  </tr>
</table>

<br>
<table class="TableList" width="95%" align="center">
	<tr class="TableHeader">
			<td nowrap><?=_("站次")?> </td>
			<td nowrap><?=_("经停站")?> </td>
			<td nowrap><?=_("到站时间")?> </td>
			<td nowrap><?=_("发车时间")?> </td>
			<td nowrap><?=_("公里数")?> </td>
			<td nowrap><?=_("天数")?> </td>
	</tr>

<?
mysql_select_db("TRAIN", TD::conn());

$query1="select pass.zhanci,station.station,pass.arrive,pass.depart,pass.distance,pass.day from pass,station ";
$query1.="where pass.trainid=$TRAINID and station.id=pass.station order by pass.zhanci";
$cursor1=exequery(TD::conn(),$query1);

$TABLE_CLASS="TableData";

while($ROW=mysql_fetch_array($cursor1))
{
	$ZHANCI=floor($ROW[0]);
	$STATION=$ROW[1];
	$ARRIVE=$ROW[2];
	$DEPART=$ROW[3];
	$INTERVAL=floor($ROW[4]);
	$INTERVAL=$INTERVAL._("公里");
	$DAY=floor($ROW[5]);

	$ARRIVE=substr($ARRIVE,0,5);
	$DEPART=substr($DEPART,0,5);
	if($ARRIVE=="00:00")
		$ARRIVE=_("<--始发站-->");

	if($DEPART=="00:00")
		$DEPART=_("<--终点站-->");

        if($STATION==$FROM_STATION)
           $TABLE_CLASS="TableContent";

?>
	<tr class="<?=$TABLE_CLASS?>">
			<td nowrap><?=$ZHANCI?> </td>
			<td nowrap><?=$STATION?> </td>
			<td nowrap><?=$ARRIVE?> </td>
			<td nowrap><?=$DEPART?> </td>
			<td nowrap><?=$INTERVAL?> </td>
			<td nowrap><?=$DAY?> </td>
	</tr>
<?
        if($STATION==$TO_STATION)
           $TABLE_CLASS="TableData";
}
?>
</table>

<?
Button_Back();
?>
</body>
</html>
