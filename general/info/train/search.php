<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("列车时刻查询");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("列车时刻查询结果")?> (<?=_("最多显示100条")?>)</span><br>
    </td>
  </tr>
</table>
<br>

<?
mysql_select_db("TRAIN", TD::conn());

//========================================== 直接查车次 ===============================
if($TRAIN!="")
{
	$query1="select id,fstation,estation,ftime,etime,distance,kind,day from train where train='$TRAIN' ";
	$cursor1=exequery(TD::conn(),$query1);
	if($ROW=mysql_fetch_array($cursor1))
	{
	   $CIRCLE_TIMES=1;
?>

<table class="TableList" width="95%" align="center">
 <tr class="TableHeader">
    <td nowrap align="center"><?=_("车次")?> </td>
    <td nowrap align="center"><?=_("发车时间")?> </td>
    <td nowrap align="center"><?=_("到站时间")?> </td>
    <td nowrap align="center"><?=_("公里数")?> </td>
    <td nowrap align="center"><?=_("硬座票价")?> </td>
    <td nowrap align="center"><?=_("操作")?> </td>
  </tr>

<?
		$TRAINID=$ROW[0];		// 火车id号
		$TRAIN_FSTATION=$ROW[1];	// 发车站的站名
		$TRAIN_ESTATION=$ROW[2];	// 终到站的站名
		$DEPART_TIME=$ROW[3];		// 发车时间
		$ARRIVE_TIME=$ROW[4];       	// 到站时间
		$DISTANCE=$ROW[5];		// 发车站的里程
		$TRAIN_KIND=$ROW[6];		// 火车类型id号
		$DAY=$ROW[7];			// 旅行天数

		$ARRIVE_TIME=substr($ARRIVE_TIME,0,5);
	  $DEPART_TIME=substr($DEPART_TIME,0,5);

		//－－－－－－－－－－－－－－－计算旅行总时间－－－－－－－－－－－－－－－－－－－－－－
		$TOTAL_HOUR=24*$DAY+substr($ARRIVE_TIME,0,2)-substr($DEPART_TIME,0,2);	//总小时数
		$TOTAL_MINUTE=substr($ARRIVE_TIME,3,2)-substr($DEPART_TIME,3,2);		//总分钟数

		if($TOTAL_MINUTE<0)
		{
			$TOTAL_HOUR=$TOTAL_HOUR-1;
			$TOTAL_MINUTE=60+$TOTAL_MINUTE;
		}

		//把$DEPART_TIME和$ARRIVE_TIME 转换成标准时间格式
		if($ARRIVE_TIME=="00:00")
		   $ARRIVE_TIME=_("<--始发站-->");

	  if($DEPART_TIME=="00:00")
		   $DEPART_TIME=_("<--终点站-->");

		//---------------------------------------------------------------------------------------------
		$query2="select pass.zhanci,stationa.station,stationb.station,kind.kind,kind.id from pass,kind,train,station stationa,station stationb ";
		$query2.="where pass.trainid=$TRAINID and train.id=pass.trainid and kind.id=train.kind and stationa.id=train.fstation and stationb.id=train.estation order by pass.zhanci desc";
		$cursor2=exequery(TD::conn(),$query2);
		if($ROW=mysql_fetch_array($cursor2))
		{
			$NUMBER_OF_STATION=floor($ROW[0]);		// 车站数
			$FIRST_STATION=$ROW[1];				// 始发站
			$END_STATION=$ROW[2];				// 终点站
			$TRAIN_KIND_NAME=$ROW[3]; 			// 火车类型
			$TRAIN_KIND_NUM=$ROW[4];			// 火车类型编号
		}
		$TRAIN_NAME=sprintf(_("%s次（%s--%s）%s列车"),strtoupper($TRAIN),$FIRST_STATION,$END_STATION,$TRAIN_KIND_NAME);

		switch($TRAIN_KIND_NUM)
		{
			case "1":$SEAT="kttkz";	$BED="kttkw";	break;
			case "2":$SEAT="ktpkz";	$BED="ktpkw";	break;
			case "3":$SEAT=""; 	$BED="";	break;
			case "4":$SEAT="kttkz";	$BED="kttkw";	break;
			case "5":$SEAT="tkz";	$BED="tkw";	break;
			case "6":$SEAT="pkz";	$BED="pkw";	break;
			case "7":$SEAT="";	$BED="";   	break;
			case "8":$SEAT="tkz";	$BED="tkw";	break;
		}//switch

		$DISTANCE2=$DISTANCE+100;   // $DISTANCE为一个中间变量

		$query3="select ".$SEAT.",".$BED." from price where distance>=".$DISTANCE." and distance<".$DISTANCE2;
		if(($TRAIN_KIND_NUM!=3)&&($TRAIN_KIND_NUM!=7))
		{
			$cursor3=exequery(TD::conn(),$query3);
			if($ROW=mysql_fetch_array($cursor3))
			{
				$PRICE_SEAT=$ROW[0];
				$PRICE_BED=$ROW[1];
			}//if

			$ARRAY_PRICE_SEAT=explode(",",$PRICE_SEAT);
			$ARRAY_PRICE_BED=explode(",",$PRICE_BED);
			$PRICE_HARDSEAT=$ARRAY_PRICE_SEAT[0];
			$PRICE_SOFTSEAT=$ARRAY_PRICE_SEAT[1];
			$PRICE_HARDBED_TOP=$ARRAY_PRICE_BED[0];
			$PRICE_HARDBED_MIDDLE=$ARRAY_PRICE_BED[1];
			$PRICE_HARDBED_BELOW=$ARRAY_PRICE_BED[2];
			$PRICE_SOFTBED_TOP=$ARRAY_PRICE_BED[3];
			$PRICE_SOFTBED_BELOW=$ARRAY_PRICE_BED[4];

		}//if

		else
		{
			$PRICE_HARDSEAT="";
			$PRICE_SOFTSEAT="";
			$PRICE_HARDBED_TOP="";
			$PRICE_HARDBED_MIDDLE="";
			$PRICE_HARDBED_BELOW="";
			$PRICE_SOFTBED_TOP="";
			$PRICE_SOFTBED_BELOW="";
		}

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
?>

<tr class="TableData">
	<td nowrap align="center"><a href="detail.php?TRAIN_NAME=<?=$TRAIN_NAME?>&DEPART_TIME=<?=$DEPART_TIME?>&ARRIVE_TIME=<?=$ARRIVE_TIME?>&TOTAL_HOUR=<?=$TOTAL_HOUR?>&TOTAL_MINUTE=<?=$TOTAL_MINUTE?>&DISTANCE=<?=$DISTANCE?>&NUMBER_OF_STATION=<?=$NUMBER_OF_STATION?>&PRICE_HARDSEAT=<?=$PRICE_HARDSEAT?>&PRICE_SOFTSEAT=<?=$PRICE_SOFTSEAT?>&PRICE_HARDBED_TOP=<?=$PRICE_HARDBED_TOP?>&PRICE_HARDBED_MIDDLE=<?=$PRICE_HARDBED_MIDDLE?>&PRICE_HARDBED_BELOW=<?=$PRICE_HARDBED_BELOW?>&PRICE_SOFTBED_TOP=<?=$PRICE_SOFTBED_TOP?>&PRICE_SOFTBED_BELOW=<?=$PRICE_SOFTBED_BELOW?>&DAY=<?=$DAY?>&TRAINID=<?=$TRAINID?>" ><?=$TRAIN_NAME?></a></td>
	<td nowrap align="center"><?=$DEPART_TIME?></td>
	<td nowrap align="center"><?=$DAY_NAME?> <?=$ARRIVE_TIME?></td>
	<td nowrap align="center"><?=$DISTANCE?></td>
	<td nowrap align="center"><?=$PRICE_HARDSEAT?></td>
	<td nowrap align="center">
	    <a href="detail.php?TRAIN_NAME=<?=$TRAIN_NAME?>&DEPART_TIME=<?=$DEPART_TIME?>&ARRIVE_TIME=<?=$ARRIVE_TIME?>&TOTAL_HOUR=<?=$TOTAL_HOUR?>&TOTAL_MINUTE=<?=$TOTAL_MINUTE?>&DISTANCE=<?=$DISTANCE?>&NUMBER_OF_STATION=<?=$NUMBER_OF_STATION?>&PRICE_HARDSEAT=<?=$PRICE_HARDSEAT?>&PRICE_SOFTSEAT=<?=$PRICE_SOFTSEAT?>&PRICE_HARDBED_TOP=<?=$PRICE_HARDBED_TOP?>&PRICE_HARDBED_MIDDLE=<?=$PRICE_HARDBED_MIDDLE?>&PRICE_HARDBED_BELOW=<?=$PRICE_HARDBED_BELOW?>&PRICE_SOFTBED_TOP=<?=$PRICE_SOFTBED_TOP?>&PRICE_SOFTBED_BELOW=<?=$PRICE_SOFTBED_BELOW?>&DAY=<?=$DAY?>&TRAINID=<?=$TRAINID?>" ><?=_("详情")?></a>
	</td>
</tr>

<?
	}//if
}//if


//========================================== 模湖查询 ===============================
else
{
	$query="select passa.trainid,passa.day,passb.day,passa.zhanci,passb.zhanci,passa.depart,passb.arrive,passa.distance,passb.distance,stationa.station,stationb.station from pass passa,pass passb,station stationa,station stationb ";
	$query.="where passb.trainid=passa.trainid and passb.zhanci>passa.zhanci and passa.station=stationa.id and passb.station=stationb.id and stationa.station like '%$START%' and stationb.station like '%$END%'";
	$cursor=exequery(TD::conn(),$query);         //带有火车id号的大的结果集，里面包含了很多信息，用于显示所需的其他信息将在循环理完成
	$CIRCLE_TIMES=0;

	while($ROW=mysql_fetch_array($cursor))
	{

	if($CIRCLE_TIMES==0)
	{
?>
<table class="TableList" width="95%" align="center">
 <tr class="TableHeader">
    <td nowrap align="center"><?=_("车次")?> </td>
    <td nowrap align="center"><?=_("发车时间")?> </td>
    <td nowrap align="center"><?=_("到站时间")?> </td>
    <td nowrap align="center"><?=_("公里数")?> </td>
    <td nowrap align="center"><?=_("硬座票价")?> </td>
    <td nowrap align="center"><?=_("操作")?> </td>
  </tr>
<?
	 }
		$TRAINID=$ROW[0];		// 火车id号
		$PASSA_DAY=$ROW[1];	        // 发车日期（整数天）
		$PASSB_DAY=$ROW[2];		// 到站日期（整数天）
		$PASSA_ZHANCI=$ROW[3];		// 发车站的站次
		$PASSB_ZHANCI=$ROW[4];		// 终到站的站次
		$DEPART_TIME=$ROW[5];		// 发车时间
		$ARRIVE_TIME=$ROW[6];            // 到站时间
		$PASSA_DISTANCE=$ROW[7];		// 发车站的里程
		$PASSB_DISTANCE=$ROW[8];		// 终到站的里程
		$STATIONA_STATION=$ROW[9];      // 发车站站名
		$STATIONB_STATION=$ROW[10];      // 终到站站名

		$ARRIVE_TIME=substr($ARRIVE_TIME,0,5);
	  $DEPART_TIME=substr($DEPART_TIME,0,5);

		//－－－－－－－－－－－－－－－计算旅行总时间－－－－－－－－－－－－－－－－－－－－－－
		//－－－－－－－－－－－－－－－$TOTAL_HOUR为总小时数、$TOTAL_MINUTE为总分钟数－－－－－－
		$DAY=$PASSB_DAY-$PASSA_DAY;
		$TOTAL_HOUR=24*$DAY+substr($ARRIVE_TIME,0,2)-substr($DEPART_TIME,0,2);	//总小时数
		$TOTAL_MINUTE=substr($ARRIVE_TIME,3,2)-substr($DEPART_TIME,3,2);		//总分钟数

		if($TOTAL_MINUTE<0)
		{
			$TOTAL_HOUR=$TOTAL_HOUR-1;
			$TOTAL_MINUTE=60+$TOTAL_MINUTE;
		}

		//把$DEPART_TIME和$ARRIVE_TIME 转换成标准时间格式
		if($ARRIVE_TIME=="00:00")
		   $ARRIVE_TIME=_("<--始发站-->");

	  if($DEPART_TIME=="00:00")
		   $DEPART_TIME=_("<--终点站-->");

		//---------------------------------------------------------------------------------------------
		$NUMBER_OF_STATION=$PASSB_ZHANCI-$PASSA_ZHANCI;              //经过的车站数
		$DISTANCE=$PASSB_DISTANCE-$PASSA_DISTANCE;                   //旅行公里数

		//下面需要用一个select语句查处 车次，始发站，终点站，车的类型
		$query="select train.train,train.kind,stationa.station,stationb.station,kind.kind from train,station stationa,station stationb,kind ";
		$query.="where train.id=$TRAINID and train.kind=kind.id	and stationa.id=train.fstation and stationb.id=train.estation";
		$cursor1=exequery(TD::conn(),$query);
		if($ROW=mysql_fetch_array($cursor1))
		{
			$CHECI=$ROW[0];							        // 车次
			$TRAIN_KIND_NUM=$ROW[1];						// 火车种类id号
			$FIRST_STATION=$ROW[2];							// 始发站
			$END_STATION=$ROW[3];							// 终点站
			$TRAIN_KIND_NAME=$ROW[4];						// 火车类型
		}

    $TRAIN_NAME1=sprintf(_("%s到%s"),$STATIONA_STATION,$STATIONB_STATION);
    $TRAIN_NAME2=sprintf(_("%s次(%s--%s)%s列车"),$CHECI,$FIRST_STATION,$END_STATION,$TRAIN_KIND_NAME);

    switch($TRAIN_KIND_NUM)
    {
    	case "1":$SEAT="kttkz";	$BED="kttkw";	break;
    	case "2":$SEAT="ktpkz";	$BED="ktpkw";	break;
    	case "3":$SEAT="";	$BED=""; 	break;
    	case "4":$SEAT="kttkz";	$BED="kttkw";	break;
    	case "5":$SEAT="tkz";	$BED="tkw";	break;
    	case "6":$SEAT="pkz";	$BED="pkw";	break;
    	case "7":$SEAT="";	$BED="";   	break;
    	case "8":$SEAT="tkz";	$BED="tkw";	break;
    }//switch

    $DISTANCE2=$DISTANCE+100;   //$DISTANCE为一个中间变量
    $query4="select ".$SEAT.",".$BED." from price where distance>=".$DISTANCE." and distance<".$DISTANCE2;
    if(($TRAIN_KIND_NUM!=3)&&($TRAIN_KIND_NUM!=7))
    {
    	$cursor4=exequery(TD::conn(),$query4);
    	if($ROW=mysql_fetch_array($cursor4))
    	{
    		$PRICE_SEAT=$ROW[0];
    		$PRICE_BED=$ROW[1];
    	}

    	$ARRAY_PRICE_SEAT=explode(",",$PRICE_SEAT);
    	$ARRAY_PRICE_BED=explode(",",$PRICE_BED);
    	$PRICE_HARDSEAT=$ARRAY_PRICE_SEAT[0];
    	$PRICE_SOFTSEAT=$ARRAY_PRICE_SEAT[1];
    	$PRICE_HARDBED_TOP=$ARRAY_PRICE_BED[0];
    	$PRICE_HARDBED_MIDDLE=$ARRAY_PRICE_BED[1];
    	$PRICE_HARDBED_BELOW=$ARRAY_PRICE_BED[2];
    	$PRICE_SOFTBED_TOP=$ARRAY_PRICE_BED[3];
    	$PRICE_SOFTBED_BELOW=$ARRAY_PRICE_BED[4];
    }
    else
    {
    	$PRICE_HARDSEAT="";
    	$PRICE_SOFTSEAT="";
    	$PRICE_HARDBED_TOP="";
    	$PRICE_HARDBED_MIDDLE="";
    	$PRICE_HARDBED_BELOW="";
    	$PRICE_SOFTBED_TOP="";
    	$PRICE_SOFTBED_BELOW="";
    }

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

    if(($CIRCLE_TIMES+1)%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
	<td nowrap align="center"><?=$TRAIN_NAME1?><br><a href="detail.php?TRAIN_NAME=<?=($TRAIN_NAME1."<BR>".$TRAIN_NAME2)?>&DEPART_TIME=<?=$DEPART_TIME?>&ARRIVE_TIME=<?=$ARRIVE_TIME?>&TOTAL_HOUR=<?=$TOTAL_HOUR?>&TOTAL_MINUTE=<?=$TOTAL_MINUTE?>&DISTANCE=<?=$DISTANCE?>&NUMBER_OF_STATION=<?=$NUMBER_OF_STATION?>&PRICE_HARDSEAT=<?=$PRICE_HARDSEAT?>&PRICE_SOFTSEAT=<?=$PRICE_SOFTSEAT?>&PRICE_HARDBED_TOP=<?=$PRICE_HARDBED_TOP?>&PRICE_HARDBED_MIDDLE=<?=$PRICE_HARDBED_MIDDLE?>&PRICE_HARDBED_BELOW=<?=$PRICE_HARDBED_BELOW?>&PRICE_SOFTBED_TOP=<?=$PRICE_SOFTBED_TOP?>&PRICE_SOFTBED_BELOW=<?=$PRICE_SOFTBED_BELOW?>&DAY=<?=$DAY?>&TRAINID=<?=$TRAINID?>" ><?=$TRAIN_NAME2?></a></td>
	<td nowrap align="center"><?=$DEPART_TIME?></td>
	<td nowrap align="center"><?=$DAY_NAME?> <?=$ARRIVE_TIME?></td>
	<td nowrap align="center"><?=$DISTANCE?></td>
	<td nowrap align="center"><?=$PRICE_HARDSEAT?></td>
	<td nowrap align="center"><a href="detail.php?TRAIN_NAME=<?=($TRAIN_NAME1."<BR>".$TRAIN_NAME2)?>&DEPART_TIME=<?=$DEPART_TIME?>&ARRIVE_TIME=<?=$ARRIVE_TIME?>&TOTAL_HOUR=<?=$TOTAL_HOUR?>&TOTAL_MINUTE=<?=$TOTAL_MINUTE?>&DISTANCE=<?=$DISTANCE?>&NUMBER_OF_STATION=<?=$NUMBER_OF_STATION?>&PRICE_HARDSEAT=<?=$PRICE_HARDSEAT?>&PRICE_SOFTSEAT=<?=$PRICE_SOFTSEAT?>&PRICE_HARDBED_TOP=<?=$PRICE_HARDBED_TOP?>&PRICE_HARDBED_MIDDLE=<?=$PRICE_HARDBED_MIDDLE?>&PRICE_HARDBED_BELOW=<?=$PRICE_HARDBED_BELOW?>&PRICE_SOFTBED_TOP=<?=$PRICE_SOFTBED_TOP?>&PRICE_SOFTBED_BELOW=<?=$PRICE_SOFTBED_BELOW?>&DAY=<?=$DAY?>&TRAINID=<?=$TRAINID?>" ><?=_("详情")?> </a></td>
</tr>
<?
	$CIRCLE_TIMES=$CIRCLE_TIMES+1;
	if($CIRCLE_TIMES>100)
		break;
	}//while
}//else
?>
</table>

<?

if($CIRCLE_TIMES==0)
   Message("",_("没有符合条件的结果"));
Button_Back();
?>

</body>
</html>
