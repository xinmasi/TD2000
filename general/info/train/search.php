<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�г�ʱ�̲�ѯ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("�г�ʱ�̲�ѯ���")?> (<?=_("�����ʾ100��")?>)</span><br>
    </td>
  </tr>
</table>
<br>

<?
mysql_select_db("TRAIN", TD::conn());

//========================================== ֱ�Ӳ鳵�� ===============================
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
    <td nowrap align="center"><?=_("����")?> </td>
    <td nowrap align="center"><?=_("����ʱ��")?> </td>
    <td nowrap align="center"><?=_("��վʱ��")?> </td>
    <td nowrap align="center"><?=_("������")?> </td>
    <td nowrap align="center"><?=_("Ӳ��Ʊ��")?> </td>
    <td nowrap align="center"><?=_("����")?> </td>
  </tr>

<?
		$TRAINID=$ROW[0];		// ��id��
		$TRAIN_FSTATION=$ROW[1];	// ����վ��վ��
		$TRAIN_ESTATION=$ROW[2];	// �յ�վ��վ��
		$DEPART_TIME=$ROW[3];		// ����ʱ��
		$ARRIVE_TIME=$ROW[4];       	// ��վʱ��
		$DISTANCE=$ROW[5];		// ����վ�����
		$TRAIN_KIND=$ROW[6];		// ������id��
		$DAY=$ROW[7];			// ��������

		$ARRIVE_TIME=substr($ARRIVE_TIME,0,5);
	  $DEPART_TIME=substr($DEPART_TIME,0,5);

		//����������������������������������������ʱ�䣭������������������������������������������
		$TOTAL_HOUR=24*$DAY+substr($ARRIVE_TIME,0,2)-substr($DEPART_TIME,0,2);	//��Сʱ��
		$TOTAL_MINUTE=substr($ARRIVE_TIME,3,2)-substr($DEPART_TIME,3,2);		//�ܷ�����

		if($TOTAL_MINUTE<0)
		{
			$TOTAL_HOUR=$TOTAL_HOUR-1;
			$TOTAL_MINUTE=60+$TOTAL_MINUTE;
		}

		//��$DEPART_TIME��$ARRIVE_TIME ת���ɱ�׼ʱ���ʽ
		if($ARRIVE_TIME=="00:00")
		   $ARRIVE_TIME=_("<--ʼ��վ-->");

	  if($DEPART_TIME=="00:00")
		   $DEPART_TIME=_("<--�յ�վ-->");

		//---------------------------------------------------------------------------------------------
		$query2="select pass.zhanci,stationa.station,stationb.station,kind.kind,kind.id from pass,kind,train,station stationa,station stationb ";
		$query2.="where pass.trainid=$TRAINID and train.id=pass.trainid and kind.id=train.kind and stationa.id=train.fstation and stationb.id=train.estation order by pass.zhanci desc";
		$cursor2=exequery(TD::conn(),$query2);
		if($ROW=mysql_fetch_array($cursor2))
		{
			$NUMBER_OF_STATION=floor($ROW[0]);		// ��վ��
			$FIRST_STATION=$ROW[1];				// ʼ��վ
			$END_STATION=$ROW[2];				// �յ�վ
			$TRAIN_KIND_NAME=$ROW[3]; 			// ������
			$TRAIN_KIND_NUM=$ROW[4];			// �����ͱ��
		}
		$TRAIN_NAME=sprintf(_("%s�Σ�%s--%s��%s�г�"),strtoupper($TRAIN),$FIRST_STATION,$END_STATION,$TRAIN_KIND_NAME);

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

		$DISTANCE2=$DISTANCE+100;   // $DISTANCEΪһ���м����

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
               	  case "0":$DAY_NAME=_("����");break;
               	  case "1":$DAY_NAME=_("����");break;
               	  case "2":$DAY_NAME=_("������");break;
               	  case "3":$DAY_NAME=_("������");break;
               	  case "4":$DAY_NAME=_("������");break;
               	  case "5":$DAY_NAME=_("������");break;
               	  case "6":$DAY_NAME=_("������");break;
                }
?>

<tr class="TableData">
	<td nowrap align="center"><a href="detail.php?TRAIN_NAME=<?=$TRAIN_NAME?>&DEPART_TIME=<?=$DEPART_TIME?>&ARRIVE_TIME=<?=$ARRIVE_TIME?>&TOTAL_HOUR=<?=$TOTAL_HOUR?>&TOTAL_MINUTE=<?=$TOTAL_MINUTE?>&DISTANCE=<?=$DISTANCE?>&NUMBER_OF_STATION=<?=$NUMBER_OF_STATION?>&PRICE_HARDSEAT=<?=$PRICE_HARDSEAT?>&PRICE_SOFTSEAT=<?=$PRICE_SOFTSEAT?>&PRICE_HARDBED_TOP=<?=$PRICE_HARDBED_TOP?>&PRICE_HARDBED_MIDDLE=<?=$PRICE_HARDBED_MIDDLE?>&PRICE_HARDBED_BELOW=<?=$PRICE_HARDBED_BELOW?>&PRICE_SOFTBED_TOP=<?=$PRICE_SOFTBED_TOP?>&PRICE_SOFTBED_BELOW=<?=$PRICE_SOFTBED_BELOW?>&DAY=<?=$DAY?>&TRAINID=<?=$TRAINID?>" ><?=$TRAIN_NAME?></a></td>
	<td nowrap align="center"><?=$DEPART_TIME?></td>
	<td nowrap align="center"><?=$DAY_NAME?> <?=$ARRIVE_TIME?></td>
	<td nowrap align="center"><?=$DISTANCE?></td>
	<td nowrap align="center"><?=$PRICE_HARDSEAT?></td>
	<td nowrap align="center">
	    <a href="detail.php?TRAIN_NAME=<?=$TRAIN_NAME?>&DEPART_TIME=<?=$DEPART_TIME?>&ARRIVE_TIME=<?=$ARRIVE_TIME?>&TOTAL_HOUR=<?=$TOTAL_HOUR?>&TOTAL_MINUTE=<?=$TOTAL_MINUTE?>&DISTANCE=<?=$DISTANCE?>&NUMBER_OF_STATION=<?=$NUMBER_OF_STATION?>&PRICE_HARDSEAT=<?=$PRICE_HARDSEAT?>&PRICE_SOFTSEAT=<?=$PRICE_SOFTSEAT?>&PRICE_HARDBED_TOP=<?=$PRICE_HARDBED_TOP?>&PRICE_HARDBED_MIDDLE=<?=$PRICE_HARDBED_MIDDLE?>&PRICE_HARDBED_BELOW=<?=$PRICE_HARDBED_BELOW?>&PRICE_SOFTBED_TOP=<?=$PRICE_SOFTBED_TOP?>&PRICE_SOFTBED_BELOW=<?=$PRICE_SOFTBED_BELOW?>&DAY=<?=$DAY?>&TRAINID=<?=$TRAINID?>" ><?=_("����")?></a>
	</td>
</tr>

<?
	}//if
}//if


//========================================== ģ����ѯ ===============================
else
{
	$query="select passa.trainid,passa.day,passb.day,passa.zhanci,passb.zhanci,passa.depart,passb.arrive,passa.distance,passb.distance,stationa.station,stationb.station from pass passa,pass passb,station stationa,station stationb ";
	$query.="where passb.trainid=passa.trainid and passb.zhanci>passa.zhanci and passa.station=stationa.id and passb.station=stationb.id and stationa.station like '%$START%' and stationb.station like '%$END%'";
	$cursor=exequery(TD::conn(),$query);         //���л�id�ŵĴ�Ľ��������������˺ܶ���Ϣ��������ʾ�����������Ϣ����ѭ�������
	$CIRCLE_TIMES=0;

	while($ROW=mysql_fetch_array($cursor))
	{

	if($CIRCLE_TIMES==0)
	{
?>
<table class="TableList" width="95%" align="center">
 <tr class="TableHeader">
    <td nowrap align="center"><?=_("����")?> </td>
    <td nowrap align="center"><?=_("����ʱ��")?> </td>
    <td nowrap align="center"><?=_("��վʱ��")?> </td>
    <td nowrap align="center"><?=_("������")?> </td>
    <td nowrap align="center"><?=_("Ӳ��Ʊ��")?> </td>
    <td nowrap align="center"><?=_("����")?> </td>
  </tr>
<?
	 }
		$TRAINID=$ROW[0];		// ��id��
		$PASSA_DAY=$ROW[1];	        // �������ڣ������죩
		$PASSB_DAY=$ROW[2];		// ��վ���ڣ������죩
		$PASSA_ZHANCI=$ROW[3];		// ����վ��վ��
		$PASSB_ZHANCI=$ROW[4];		// �յ�վ��վ��
		$DEPART_TIME=$ROW[5];		// ����ʱ��
		$ARRIVE_TIME=$ROW[6];            // ��վʱ��
		$PASSA_DISTANCE=$ROW[7];		// ����վ�����
		$PASSB_DISTANCE=$ROW[8];		// �յ�վ�����
		$STATIONA_STATION=$ROW[9];      // ����վվ��
		$STATIONB_STATION=$ROW[10];      // �յ�վվ��

		$ARRIVE_TIME=substr($ARRIVE_TIME,0,5);
	  $DEPART_TIME=substr($DEPART_TIME,0,5);

		//����������������������������������������ʱ�䣭������������������������������������������
		//������������������������������$TOTAL_HOURΪ��Сʱ����$TOTAL_MINUTEΪ�ܷ�����������������
		$DAY=$PASSB_DAY-$PASSA_DAY;
		$TOTAL_HOUR=24*$DAY+substr($ARRIVE_TIME,0,2)-substr($DEPART_TIME,0,2);	//��Сʱ��
		$TOTAL_MINUTE=substr($ARRIVE_TIME,3,2)-substr($DEPART_TIME,3,2);		//�ܷ�����

		if($TOTAL_MINUTE<0)
		{
			$TOTAL_HOUR=$TOTAL_HOUR-1;
			$TOTAL_MINUTE=60+$TOTAL_MINUTE;
		}

		//��$DEPART_TIME��$ARRIVE_TIME ת���ɱ�׼ʱ���ʽ
		if($ARRIVE_TIME=="00:00")
		   $ARRIVE_TIME=_("<--ʼ��վ-->");

	  if($DEPART_TIME=="00:00")
		   $DEPART_TIME=_("<--�յ�վ-->");

		//---------------------------------------------------------------------------------------------
		$NUMBER_OF_STATION=$PASSB_ZHANCI-$PASSA_ZHANCI;              //�����ĳ�վ��
		$DISTANCE=$PASSB_DISTANCE-$PASSA_DISTANCE;                   //���й�����

		//������Ҫ��һ��select���鴦 ���Σ�ʼ��վ���յ�վ����������
		$query="select train.train,train.kind,stationa.station,stationb.station,kind.kind from train,station stationa,station stationb,kind ";
		$query.="where train.id=$TRAINID and train.kind=kind.id	and stationa.id=train.fstation and stationb.id=train.estation";
		$cursor1=exequery(TD::conn(),$query);
		if($ROW=mysql_fetch_array($cursor1))
		{
			$CHECI=$ROW[0];							        // ����
			$TRAIN_KIND_NUM=$ROW[1];						// ������id��
			$FIRST_STATION=$ROW[2];							// ʼ��վ
			$END_STATION=$ROW[3];							// �յ�վ
			$TRAIN_KIND_NAME=$ROW[4];						// ������
		}

    $TRAIN_NAME1=sprintf(_("%s��%s"),$STATIONA_STATION,$STATIONB_STATION);
    $TRAIN_NAME2=sprintf(_("%s��(%s--%s)%s�г�"),$CHECI,$FIRST_STATION,$END_STATION,$TRAIN_KIND_NAME);

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

    $DISTANCE2=$DISTANCE+100;   //$DISTANCEΪһ���м����
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
     	  case "0":$DAY_NAME=_("����");break;
     	  case "1":$DAY_NAME=_("����");break;
     	  case "2":$DAY_NAME=_("������");break;
     	  case "3":$DAY_NAME=_("������");break;
     	  case "4":$DAY_NAME=_("������");break;
     	  case "5":$DAY_NAME=_("������");break;
     	  case "6":$DAY_NAME=_("������");break;
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
	<td nowrap align="center"><a href="detail.php?TRAIN_NAME=<?=($TRAIN_NAME1."<BR>".$TRAIN_NAME2)?>&DEPART_TIME=<?=$DEPART_TIME?>&ARRIVE_TIME=<?=$ARRIVE_TIME?>&TOTAL_HOUR=<?=$TOTAL_HOUR?>&TOTAL_MINUTE=<?=$TOTAL_MINUTE?>&DISTANCE=<?=$DISTANCE?>&NUMBER_OF_STATION=<?=$NUMBER_OF_STATION?>&PRICE_HARDSEAT=<?=$PRICE_HARDSEAT?>&PRICE_SOFTSEAT=<?=$PRICE_SOFTSEAT?>&PRICE_HARDBED_TOP=<?=$PRICE_HARDBED_TOP?>&PRICE_HARDBED_MIDDLE=<?=$PRICE_HARDBED_MIDDLE?>&PRICE_HARDBED_BELOW=<?=$PRICE_HARDBED_BELOW?>&PRICE_SOFTBED_TOP=<?=$PRICE_SOFTBED_TOP?>&PRICE_SOFTBED_BELOW=<?=$PRICE_SOFTBED_BELOW?>&DAY=<?=$DAY?>&TRAINID=<?=$TRAINID?>" ><?=_("����")?> </a></td>
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
   Message("",_("û�з��������Ľ��"));
Button_Back();
?>

</body>
</html>
