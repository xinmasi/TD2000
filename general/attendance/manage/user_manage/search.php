<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("../check_func.func.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("���������ѯ");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script>
function out_edit(OUT_ID)
{
 URL="out_edit.php?OUT_ID="+OUT_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"out_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function overtime_edit(OVERTIME_ID)
{
 URL="overtime_edit.php?OVERTIME_ID="+OVERTIME_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"overtime_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function leave_edit(LEAVE_ID)
{
 URL="leave_edit.php?LEAVE_ID="+LEAVE_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"leave_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function evection_edit(EVECTION_ID)
{
 URL="evection_edit.php?EVECTION_ID="+EVECTION_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"evection_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}


</script>
<style type="text/css">
  body{
    margin: 20px;
  }
</style>

<body class="">
<?
  //----------- �Ϸ���У�� ---------
  if($DATE1!="")
  {
    $TIME_OK=is_date($DATE1);

    if(!$TIME_OK)
    { Message(_("����"),_("��ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if($DATE2!="")
  {
    $TIME_OK=is_date($DATE2);

    if(!$TIME_OK)
    { Message(_("����"),_("��ֹ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if(compare_date($DATE1,$DATE2)==1)
  { Message(_("����"),_("��ѯ����ʼ���ڲ������ڽ�ֹ����"));
    Button_Back();
    exit;
  }

 $query = "SELECT * from USER_EXT,USER where USER.UID=USER_EXT.UID and USER.USER_ID='$USER_ID'";
 $cursor= exequery(TD::conn(),$query);
 $LINE_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
 {
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $DUTY_TYPE=$ROW["DUTY_TYPE"];
    $DEPT_ID=$ROW["DEPT_ID"];
 }

 if(!is_dept_priv($DEPT_ID) && $_SESSION["LOGIN_USER_PRIV"]!=1)
 {
  	 Message(_("����"),_("�����ڹ���Χ�ڵ��û�").$DEPT_ID);
     exit;
 }

 $CUR_DATE=date("Y-m-d",time());

 $query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $DAY_TOTAL=$ROW[0]+1;
    
$MSG = sprintf(_("�� %d ��"), $DAY_TOTAL);
?>

<!------------------------------------- ���°� ------------------------------->
<h5 class="attendance-title"><?=_("���°�ͳ��")?>
    (<?=$USER_NAME?> <?=_("��")?> <?=format_date($DATE1)?> <?=_("��")?> <?=format_date($DATE2)?> <?=$MSG?>)</h5>


<?
$USER_COUNT=0;
$COUNT_ARRAY[$USER_COUNT]=array();
for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
{
	$DUTY_ARR=array();
	$query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') order by REGISTER_TIME DESC";
	$cursor= exequery(TD::conn(),$query, $connstatus);
	while($ROW=mysql_fetch_array($cursor))
	{
		$DUTY_ARR[$ROW["REGISTER_TYPE"]]=array(
		"DUTY_TYPE"=> $ROW["DUTY_TYPE"],
		"REGISTER_TIME"=>$ROW["REGISTER_TIME"],
		"REGISTER_IP"=>$ROW["REGISTER_IP"],
		"REMARK"=>$ROW["REMARK"]
		);
	}
	foreach($DUTY_ARR as $tem)
		$DUTY_TYPE=$tem["DUTY_TYPE"];
	if($DUTY_TYPE=="")	$DUTY_TYPE=get_default_type($USER_ID);
	if($DUTY_TYPE=="" || $DUTY_TYPE==0)	$DUTY_TYPE=1;
	
	$query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
	$cursor= exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
	{
		$DUTY_NAME=$ROW["DUTY_NAME"];
		$GENERAL=$ROW["GENERAL"];
		$DUTY_TYPE_ARR=array();
		for($I=1;$I<=6;$I++)
		{
			if($ROW["DUTY_TIME".$I]!="")
				$DUTY_TYPE_ARR[$I]=array( "DUTY_TIME" => $ROW["DUTY_TIME".$I] ,"DUTY_TYPE" => $ROW["DUTY_TYPE".$I]);
		}
	}
	else
		continue;
	$COUNT=count($DUTY_TYPE_ARR);//���Ű�һ����Ҫ�ǼǴ�����
	$IS_ALL=1;//ȫ��
	$OUGHT_TO=1;//Ӧ�ÿ��ڵǼ�
	//�������
	if($IS_HOLIDAY=check_holiday($J)!=0)//�Ƿ���Ϣ��
		$OUGHT_TO=0;
	else if($IS_HOLIDAY1=check_holiday1($J,$GENERAL)!=0)//�Ƿ�˫����
		$OUGHT_TO=0;
	else if($IS_EVECTION =check_evection($USER_ID,$J)!=0)//�Ƿ����
		$OUGHT_TO=0;
	
	foreach($DUTY_TYPE_ARR as $REGISTER_TYPE => $DUTY_TYPE_ONE)//�������Ű���Ҫ�Ǽǵ�
	{
		$START_OR_END=$DUTY_TYPE_ONE["DUTY_TYPE"];			//���°ࣺ1���ϰ࣬2���°ࡣ
		$DUTY_TIME_OUGHT=$DUTY_TYPE_ONE["DUTY_TIME"];//�趨�Ŀ���ʱ�䡣
		$DUTY_ONE_ARR=$DUTY_ARR[$REGISTER_TYPE];//��Ӧ�ĵǼǼ�¼
		
		$HAS_DUTY=0;
		if(is_array($DUTY_ONE_ARR) && !empty($DUTY_ONE_ARR))
		{
			foreach($DUTY_ONE_ARR as $KEY => $VALUE)
				$$KEY=$VALUE;
			$HAS_DUTY=1;
		}
		
		//��¼��ȡ����$REGISTER_TIME���Ǽ�ʱ�䣬$REGISTER_IP���Ǽ�IP ��$REMARK����ע
		
		
		if($IS_HOLIDAY==1 || $IS_HOLIDAY1==1)
		{
			if($START_OR_END==1 && $HAS_DUTY==1)
				$COUNT_ARRAY[$USER_COUNT][9]++;
			else if($START_OR_END==2 && $HAS_DUTY==1)
				$COUNT_ARRAY[$USER_COUNT][10]++;
		}
		//��ʱ�����ġ�
		if($IS_LEAVE=check_leave($USER_ID,$J,$DUTY_TYPE_ARR[$REGISTER_TYPE]["DUTY_TIME"])!=0)//�Ƿ����
			$OUGHT_TO=0;
		else if($IS_OUT=check_out($USER_ID,$J,$DUTY_TYPE_ARR[$REGISTER_TYPE]["DUTY_TIME"])!=0)//�Ƿ����
			$OUGHT_TO=0;
		
		if($HAS_DUTY==1)//�Ѿ��Ǽǣ������㣩
		{
			$REGISTER_TIME2=$DUTY_ONE_ARR["REGISTER_TIME"];
			$REGISTER_TIME=$DUTY_ONE_ARR["REGISTER_TIME"];
			$ALL_MINITES[$USER_ID][$J][$REGISTER_TYPE] = $REGISTER_TIME;
			$REGISTER_TIME=strtok($REGISTER_TIME," ");
			$REGISTER_TIME=strtok(" ");
			
			//�ٵ����˲���ȫ�ڣ�$IS_ALL=0;
			//echo $USER_ID."Ӧ�ã�$DUTY_TIME_OUGHT--ʵ�ʣ�$REGISTER_TIME";
			//echo "���ã�".compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)."<br>";
			if($START_OR_END=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==1)
			{
				if($OUGHT_TO!=0)
					$COUNT_ARRAY[$USER_COUNT][5]++;//�ٵ�
				$IS_ALL=0;
			}
		
			if($START_OR_END=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==-1)
			{
				$IS_ALL=0;
				if($OUGHT_TO!=0)
					$COUNT_ARRAY[$USER_COUNT][7]++;//����
			}
		}
		else if($HAS_DUTY==0 && $OUGHT_TO==1)//Ӧ�õǼǣ�û�еǼǵ�
		{
			if($START_OR_END=="1")//�ϰ�δ�Ǽ�
				$COUNT_ARRAY[$USER_COUNT][6]++;
			if($START_OR_END=="2")//�°�δ�Ǽ�
				$COUNT_ARRAY[$USER_COUNT][8]++;
			$IS_ALL=0;
		}
		else
			$IS_ALL=0;
	}
	if($IS_ALL==1)
		$COUNT_ARRAY[$USER_COUNT][3]++;//ȫ�ڵ�
	for($l = 1 ;$l<= $COUNT/2;$l ++)
	{
		if($ALL_MINITES[$USER_ID][$J][$l*2]!="" && $ALL_MINITES[$USER_ID][$J][$l*2-1]!="")
		$TIME_TOTAL+= strtotime($ALL_MINITES[$USER_ID][$J][$l*2]) - strtotime($ALL_MINITES[$USER_ID][$J][$l*2-1]);
	}
	$ALL_HOURS = floor($TIME_TOTAL / 3600);
	$HOUR1 = $TIME_TOTAL % 3600;
	$MINITE = floor($HOUR1 / 60);
	$COUNT_ARRAY[$USER_COUNT][4]= $ALL_HOURS._("ʱ").$MINITE._("��") ;
}
?>

<table class="table table-bordered"  align="center">
  <tr class="">
    <th nowrap align="center"><?=_("ȫ��(��)")?></th>
    <th nowrap align="center"><?=_("�ٵ�")?></th>
    <th nowrap align="center"><?=_("�ϰ�δ�Ǽ�")?></th>
    <th nowrap align="center"><?=_("����")?></th>
    <th nowrap align="center"><?=_("�°�δ�Ǽ�")?></th>
    <th nowrap align="center"><?=_("�Ӱ��ϰ�Ǽ�")?></th>
    <th nowrap align="center"><?=_("�Ӱ��°�Ǽ�")?></th>
  </tr>
  <tr class="">
    <td nowrap align="center"><?=$COUNT_ARRAY[$USER_COUNT][3]?></td>
    <td nowrap align="center"><?=$COUNT_ARRAY[$USER_COUNT][5]?></td>
    <td nowrap align="center"><?=$COUNT_ARRAY[$USER_COUNT][6]?></td>
    <td nowrap align="center"><?=$COUNT_ARRAY[$USER_COUNT][7]?></td>
    <td nowrap align="center"><?=$COUNT_ARRAY[$USER_COUNT][8]?></td>
    <td nowrap align="center"><?=$COUNT_ARRAY[$USER_COUNT][9]?></td>
    <td nowrap align="center"><?=$COUNT_ARRAY[$USER_COUNT][10]?></td>
  </tr>
  <tr class="">
    <td style="text-align:center;" colspan=7>
    	<input type="button"  value="<?=_("�鿴���°�Ǽ�����")?>" class="btn btn-primary" onClick="location='user_duty.php?USER_ID=<?=$USER_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'">
    </td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<!------------------------------------- �����¼ ------------------------------->

<h5 class="attendance-title"><?=_("�����¼")?></h5>

<?
 $query = "SELECT * from ATTEND_OUT where USER_ID='$USER_ID' and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') order by SUBMIT_TIME";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $OUT_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $OUT_COUNT++;
   
   $OUT_ID=$ROW["OUT_ID"];   
   $OUT_TYPE=$ROW["OUT_TYPE"];
   $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
   $CREATE_DATE=$ROW["CREATE_DATE"];   
   $SUBMIT_DATE=substr($SUBMIT_TIME,0,10);
   $OUT_TIME1=$ROW["OUT_TIME1"];
   $OUT_TIME2=$ROW["OUT_TIME2"];
   $ALLOW=$ROW["ALLOW"];
   $STATUS=$ROW["STATUS"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $LEADER_ID=$ROW["LEADER_ID"];

    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $LEADER_NAME=$ROW["USER_NAME"];
      
		if($ALLOW=="0" && $STATUS=="0")
    	$STATUS_DESC=_("������");
  	else if($ALLOW=="1" && $STATUS=="0")
    	$STATUS_DESC="<font color=green>"._("����׼")."</font>";
  	else if($ALLOW=="2" && $STATUS=="0")
    	$STATUS_DESC="<font color=red>"._("δ��׼")."</font>";
  	else if($ALLOW=="1" && $STATUS=="1")
    	$STATUS_DESC=_("�ѹ���"); 
      	
    if($OUT_COUNT==1)
    {
?>

    <table class="table table-bordered" align="center">
    <tr class="">
      <th nowrap align="center"><?=_("����ʱ��")?></th>
      <th nowrap align="center"><?=_("���ԭ��")?></th>
      <th nowrap align="center"><?=_("�Ǽ�")?>IP</th>
      <th nowrap align="center"><?=_("�������")?></th>
      <th nowrap align="center"><?=_("���ʱ��")?></th>
      <th nowrap align="center"><?=_("����ʱ��")?></th>
      <th nowrap align="center"><?=_("������Ա")?></th>
      <th nowrap align="center"><?=_("״̬")?></th>
      <th nowrap align="center"><?=_("����")?></th>
    </tr>
<?
    }
?>
    <tr class="">
    	<td nowrap align="center"><?=$CREATE_DATE?></td>
      <td width="400" align="center"><?=$OUT_TYPE?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$SUBMIT_DATE?></td>
      <td nowrap align="center"><?=$OUT_TIME1?></td>
      <td nowrap align="center"><?=$OUT_TIME2?></td>
      <td nowrap align="center"><?=$LEADER_NAME?></td>
      <td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
        <a href=javascript:out_edit('<?=$OUT_ID?>'); title="<?=_("��OA����Ա�����޸�")?>"><?=_("�޸�")?></a>
      	<a href="delete_out.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&USER_ID=<?=$USER_ID?>&SUBMIT_TIME=<?=urlencode($SUBMIT_TIME)?>"><?=_("ɾ��")?></a>
<?
}
?>
      </td>
    </tr>
<?
 }

 if($OUT_COUNT>0)
 {
?>
    </table>
<?
 }
 else
    Message("",_("�������¼"));
?>



<table width="100%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>
<!------------------------------------- ��ټ�¼ ------------------------------->


<h5 class="attendance-title"><?=_("��ټ�¼")?></h5>

<?
 $query = "SELECT * from ATTEND_LEAVE where USER_ID='$USER_ID' and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3')";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $LEAVE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $LEAVE_COUNT++;

   $LEAVE_ID=$ROW["LEAVE_ID"];
   $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
   $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
   $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
   $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
   $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
   $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
   $LEAVE_TYPE=str_replace("<","&lt",$LEAVE_TYPE);
   $LEAVE_TYPE=str_replace(">","&gt",$LEAVE_TYPE);
   $LEAVE_TYPE=stripslashes($LEAVE_TYPE);

   $RECORD_TIME=$ROW["RECORD_TIME"];
   $ALLOW=$ROW["ALLOW"];
   $STATUS=$ROW["STATUS"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $LEADER_ID=$ROW["LEADER_ID"];

    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $LEADER_NAME=$ROW["USER_NAME"];

		if($ALLOW=="0" && $STATUS=="1")
     	$ALLOW_DESC=_("������");
  	else if($ALLOW=="1" && $STATUS=="1")
     	$ALLOW_DESC="<font color=green>"._("����׼")."</font>";
  	else if($ALLOW=="2" && $STATUS=="1")
     	$ALLOW_DESC="<font color=red>"._("δ��׼")."</font>";
  	else if($ALLOW=="3" && $STATUS=="1")
     	$ALLOW_DESC=_("��������");
  	else if($ALLOW=="3" && $STATUS=="2")
     	$ALLOW_DESC=_("������");

    if($LEAVE_COUNT==1)
    {
?>

    <table class="table table-bordered" align="center">
    <tr class="">
      <th nowrap align="center"><?=_("���ԭ��")?></th>
      <th nowrap align="center"><?=_("�������")?></th>      
      <th nowrap align="center"><?=_("����ʱ��")?></th>
      <th nowrap align="center"><?=_("ռ���ݼ�")?></th>
      <th nowrap align="center"><?=_("�Ǽ�")?>IP</th>
      <th nowrap align="center"><?=_("��ʼ����")?></th>
      <th nowrap align="center"><?=_("��������")?></th>
      <th nowrap align="center"><?=_("������Ա")?></th>
      <th nowrap align="center"><?=_("״̬")?></th>
      <th nowrap align="center"><?=_("����")?></th>
    </tr>
<?
    }
?>
    <tr class="">
      <td width="400" align="center"><?=$LEAVE_TYPE?></td>
      <td nowrap align="center"><?=$LEAVE_TYPE2?></td>
      <td nowrap align="center"><?=$RECORD_TIME?></td>
      <td align="center"><?=$ANNUAL_LEAVE?><?=_("��")?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$LEAVE_DATE1?></td>
      <td nowrap align="center"><?=$LEAVE_DATE2?></td>
      <td nowrap align="center"><?=$LEADER_NAME?></td>
      <td nowrap align="center"><?=$ALLOW_DESC?></td>
      <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
        <a href=javascript:leave_edit('<?=$LEAVE_ID?>'); title="<?=_("��OA����Ա�����޸�")?>"><?=_("�޸�")?></a>
      	<a href="delete_leave.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&USER_ID=<?=$USER_ID?>&LEAVE_ID=<?=$LEAVE_ID?>"><?=_("ɾ��")?></a>
<?
}
?>
      	</td>
    </tr>
<?
 }

 if($LEAVE_COUNT>0)
 {
?>
    
    </table>
<?
 }
 else
    Message("",_("����ټ�¼"));
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>


<!------------------------------------- �����¼ ------------------------------->


<h5 class="attendance-title"><?=_("�����¼")?></h5>
<?
 $query = "SELECT * from ATTEND_EVECTION where USER_ID='$USER_ID' and ((to_days(EVECTION_DATE1)>=to_days('$DATE1') and to_days(EVECTION_DATE1)<=to_days('$DATE2')) or (to_days(EVECTION_DATE2)>=to_days('$DATE1') and to_days(EVECTION_DATE2)<=to_days('$DATE2')) or (to_days(EVECTION_DATE1)<=to_days('$DATE1') and to_days(EVECTION_DATE2)>=to_days('$DATE2'))) and ALLOW='1'";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $EVECTION_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $EVECTION_COUNT++;

   $REGISTER_IP=$ROW["REGISTER_IP"];
   $EVECTION_ID=$ROW["EVECTION_ID"];
   $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
   $EVECTION_DATE1=strtok($EVECTION_DATE1," ");
   $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
   $EVECTION_DATE2=strtok($EVECTION_DATE2," ");
   $EVECTION_DEST=$ROW["EVECTION_DEST"];
   $LEADER_ID=$ROW["LEADER_ID"];
   $STATUS=$ROW["STATUS"];
   $ALLOW=$ROW["ALLOW"];
   $REASON=$ROW["REASON"];
   $RECORD_TIME=$ROW["RECORD_TIME"]=="0000-00-00 00:00:00" ? $EVECTION_DATE1 : $ROW["RECORD_TIME"];
   
    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $LEADER_NAME=$ROW["USER_NAME"];

  	if($ALLOW=="0" && $STATUS=="1")
     	$ALLOW_DESC=_("������");
  	else if($ALLOW=="1" && $STATUS=="1")
     	$ALLOW_DESC="<font color=green>"._("����׼")."</font>";
  	else if($ALLOW=="2" && $STATUS=="1")
     	$ALLOW_DESC="<font color=red>"._("δ��׼")."</font>";
  	else if($ALLOW=="1" && $STATUS=="2")
     	$ALLOW_DESC=_("�ѹ���");

    if($EVECTION_COUNT==1)
    {
?>

    <table class="table table-bordered" align="center">
    <tr class="">
      <th nowrap align="center"><?=_("����ʱ��")?></th>
      <th nowrap align="center"><?=_("����ص�")?></th>
      <th nowrap align="center"><?=_("����ԭ��")?></th>
      <th nowrap align="center"><?=_("�Ǽ�")?>IP</th>
      <th nowrap align="center"><?=_("��ʼ����")?></th>
      <th nowrap align="center"><?=_("��������")?></th>
      <th nowrap align="center"><?=_("������Ա")?></th>
      <th nowrap align="center"><?=_("״̬")?></th>
      <th nowrap align="center"><?=_("����")?></th>
    </tr>
<?
    }
?>
    <tr class="">
      <td nowrap align="center"><?=$RECORD_TIME?></td>
      <td nowrap align="center"><?=$EVECTION_DEST?></td>      
      <td width="400" align="center"><?=$REASON?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$EVECTION_DATE1?></td>
      <td nowrap align="center"><?=$EVECTION_DATE2?></td>
      <td nowrap align="center"><?=$LEADER_NAME?></td>
      <td nowrap align="center"><?=$ALLOW_DESC?></td>
      <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
        <a href=javascript:evection_edit('<?=$EVECTION_ID?>'); title="<?=_("��OA����Ա�����޸�")?>"><?=_("�޸�")?></a>
      	<a href="delete_evection.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&USER_ID=<?=$USER_ID?>&EVECTION_ID=<?=$EVECTION_ID?>"><?=_("ɾ��")?></a>
<?
}
?>
     </td>
    </tr>
<?
 }

 if($EVECTION_COUNT>0)
 {
?>
    
    </table>
<?
 }
 else
    Message("",_("�޳����¼"));
?>


<table width="100%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<!------------------------------------- �Ӱ��¼ ------------------------------->

<h5 class="attendance-title"><?=_("�Ӱ��¼")?></h5>

<?
 $query = "SELECT * from ATTENDANCE_OVERTIME where USER_ID='$USER_ID' and ((to_days(START_TIME)>=to_days('$DATE1') and to_days(START_TIME)<=to_days('$DATE2')) or (to_days(END_TIME)>=to_days('$DATE1') and to_days(END_TIME)<=to_days('$DATE2')) or (to_days(START_TIME)<=to_days('$DATE1') and to_days(END_TIME)>=to_days('$DATE2')))and allow in('1','3') order by RECORD_TIME desc";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $OVERTIME_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $OVERTIME_COUNT++;
   
    $OVERTIME_ID=$ROW["OVERTIME_ID"];
    $USER_ID=$ROW["USER_ID"];    
    $APPROVE_ID=$ROW["APPROVE_ID"];
    $RECORD_TIME=$ROW["RECORD_TIME"];
    $START_TIME=$ROW["START_TIME"];
    $END_TIME=$ROW["END_TIME"];
    $OVERTIME_HOURS=$ROW["OVERTIME_HOURS"];
    $OVERTIME_CONTENT=$ROW["OVERTIME_CONTENT"];
    $CONFIRM_TIME=$ROW["CONFIRM_TIME"];
    $CONFIRM_VIEW=$ROW["CONFIRM_VIEW"];
    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
    $REGISTER_IP=$ROW["REGISTER_IP"];
    $REASON=$ROW["REASON"];

    $APPROVE_NAME="";
    $query = "SELECT * from USER where USER_ID='$APPROVE_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $APPROVE_NAME=$ROW["USER_NAME"];

		if($ALLOW=="0" && $STATUS=="0")
    	 $ALLOW_DESC=_("������");
  	else if($ALLOW=="1" && $STATUS=="0")
     	$ALLOW_DESC="<font color=green>"._("����׼")."</font>";
  	else if($ALLOW=="2" && $STATUS=="0")
     	$ALLOW_DESC= "<font color=\"red\">"._("δ��׼")."</font>";   
  	else if($ALLOW=="3" && $STATUS=="0")
     	$ALLOW_DESC=_("����ȷ��");
  	else if($ALLOW=="3" && $STATUS=="1")
     	$ALLOW_DESC=_("��ȷ��");	

    if($OVERTIME_COUNT==1)
    {
?>
    <table class="table table-bordered" align="center">
    <tr class="">
      <th nowrap align="center"><?=_("����ʱ��")?></th>
      <th nowrap align="center"><?=_("�Ӱ�����")?></th>
      <th nowrap align="center"><?=_("�Ǽ�")?>IP</th>
      <th nowrap align="center"><?=_("�Ӱ࿪ʼʱ��")?></th>
      <th nowrap align="center"><?=_("�Ӱ����ʱ��")?></th>
      <th nowrap align="center"><?=_("ʱ��")?></th>
      <th nowrap align="center"><?=_("������Ա")?></th>
      <th nowrap align="center"><?=_("״̬")?></th>
      <th nowrap align="center"><?=_("����")?></th>
    </tr>
<?
    }
?>
    <tr class="">
    	<td nowrap align="center"><?=$RECORD_TIME?></td>
      <td width="400" align="center">
 <?
      echo $OVERTIME_CONTENT;
      if($CONFIRM_VIEW!="")
      {
         echo "<br>";
         echo _("<font color=blue>ȷ�������$CONFIRM_VIEW</font>");
      }
 ?>
      </td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$START_TIME?></td>
      <td nowrap align="center"><?=$END_TIME?></td>
      <td nowrap align="center"><?=$OVERTIME_HOURS?></td>
      <td nowrap align="center"><?=$APPROVE_NAME?></td>
      <td nowrap align="center"><?=$ALLOW_DESC?>	 </td>
      <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
        <a href=javascript:overtime_edit('<?=$OVERTIME_ID?>'); title="<?=_("��OA����Ա�����޸�")?>"><?=_("�޸�")?></a>
      	<a href="delete_overtime.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&USER_ID=<?=$USER_ID?>&RECORD_TIME=<?=urlencode($RECORD_TIME)?>"><?=_("ɾ��")?></a>
<?
}
?>
      </td>
    </tr>
<?
 }

 if($OVERTIME_COUNT>0)
 {
?>
    
    </table>
<?
 }
 else
    Message("",_("�޼Ӱ��¼"));
?>



<div align="center">
  <input type="button"  value="<?=_("����")?>" class="btn" onClick="location='user.php?USER_ID=<?=$USER_ID?>';">
</div>
</body>
</html>
