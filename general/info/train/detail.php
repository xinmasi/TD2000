<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�г�ʱ�̲�ѯ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
switch($DAY)
{
	case "0":$DAY_NAME=_("����");break;
	case "1":$DAY_NAME=_("����");break;
	case "2":$DAY_NAME=_("������");break;
	case "3":$DAY_NAME=_("������");break;
	case "4":$DAY_NAME=_("������");break;
	case "5":$DAY_NAME=_("������");break;
	case "6":$DAY_NAME=_("������");break;

}

$TRAIN_NAME=strtok($TRAIN_NAME,"<BR>");
$TRAIN_DESC=$TRAIN_NAME;
$TRAIN_NAME=strtok("<BR>");

if($TRAIN_NAME!="")
{
   $TRAIN_NAME=" - ".$TRAIN_NAME;

   $STR=strtok($TRAIN_DESC,_("��"));
   $FROM_STATION=$STR;

   $STR=strtok(_("��"));
   $TO_STATION=$STR;
}

if($TOTAL_HOUR<=0)
   $TOTAL_HOUR="";
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("�˳�������Ϣ")?> - <?=$TRAIN_DESC?><?=$TRAIN_NAME?></span><br>
    </td>
  </tr>
</table>
<br>
<table class="TableBlock" width="95%" align="center">
	<tr class="TableHeader">
 		<td nowrap align="center"><?=_("�˳�ʱ��")?> </td>
 		<td nowrap align="center" colspan="3"><?=_("Ʊ����Ϣ")?> </td>
 	</tr>
	<tr class="TableData">
 		<td nowrap><?=_("����ʱ�䣺")?><?=$DEPART_TIME?> </td>
		<td nowrap><?=_("Ӳ��Ʊ�ۣ�")?><?=$PRICE_HARDSEAT?> </td>
		<td nowrap><?=_("Ӳ���ϣ�")?><?=$PRICE_HARDBED_TOP?><?if($PRICE_HARDBED_TOP=="")echo _("��");?> </td>
		<td nowrap><?=_("�����ϣ�")?><?=$PRICE_SOFTBED_TOP?><?if($PRICE_SOFTBED_TOP=="")echo _("��");?> </td>
 	</tr>
	<tr class="TableData">
 		<td nowrap><?=_("����ʱ�䣺")?><?=$DAY_NAME?><?=$ARRIVE_TIME?> </td>
		<td nowrap> </td>
		<td nowrap><?=_("Ӳ���У�")?><?=$PRICE_HARDBED_MIDDLE?><?if($PRICE_HARDBED_MIDDLE=="")echo _("��");?> </td>
		<td nowrap> </td>
	</tr>
	<tr class="TableData">
 		<td nowrap><?=_("����ʱ�䣺")?><?=$TOTAL_HOUR?><?=_("Сʱ")?><?=$TOTAL_MINUTE?><?=_("����")?> </td>
		<td nowrap><?=_("����Ʊ�ۣ�")?><?=$PRICE_SOFTSEAT?><?if($PRICE_SOFTSEAT=="")echo _("��");?> </td>
 		<td nowrap><?=_("Ӳ���£�")?><?=$PRICE_HARDBED_BELOW?><?if($PRICE_HARDBED_BELOW=="")echo _("��");?> </td>
 		<td nowrap><?=_("�����£�")?><?=$PRICE_SOFTBED_BELOW?><?if($PRICE_SOFTBED_BELOW=="")echo _("��");?> </td>
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("�г���ͣվ��")?> - <?=$TRAIN_DESC?> - 
    	<?
    		$msg=sprintf(_("ȫ��%d���������%d����վ"),$DISTANCE,$NUMBER_OF_STATION);
    	?>
    	<?=$msg?>
    	</span><br>
    </td>
  </tr>
</table>

<br>
<table class="TableList" width="95%" align="center">
	<tr class="TableHeader">
			<td nowrap><?=_("վ��")?> </td>
			<td nowrap><?=_("��ͣվ")?> </td>
			<td nowrap><?=_("��վʱ��")?> </td>
			<td nowrap><?=_("����ʱ��")?> </td>
			<td nowrap><?=_("������")?> </td>
			<td nowrap><?=_("����")?> </td>
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
	$INTERVAL=$INTERVAL._("����");
	$DAY=floor($ROW[5]);

	$ARRIVE=substr($ARRIVE,0,5);
	$DEPART=substr($DEPART,0,5);
	if($ARRIVE=="00:00")
		$ARRIVE=_("<--ʼ��վ-->");

	if($DEPART=="00:00")
		$DEPART=_("<--�յ�վ-->");

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
