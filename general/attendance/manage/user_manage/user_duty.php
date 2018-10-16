<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("../check_func.func.php");

$HTML_PAGE_TITLE = _("���°��¼��ѯ");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.js"></script>
<style type="text/css">
	.icon16-remind_2{
		background:url(/static/modules/workflow/images/mobile_sms.gif) no-repeat;
		margin-left:2px;
		width:16px;
		height:20px;
		padding-bottom:5px;
		padding-left:16px;
	}
	#mapiframe{width: 100%;height: 325px;}
	body{
		margin: 20px;
	}
</style>
<script language="JavaScript">
function remark(USER_ID,REGISTER_TYPE,REGISTER_TIME)
{
  URL="../user_manage/remark.php?USER_ID="+USER_ID+"&REGISTER_TYPE="+REGISTER_TYPE+"&REGISTER_TIME="+REGISTER_TIME;
  myleft=(screen.availWidth-650)/2;
  window.open(URL,"formul_edit","height=250,width=450,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function showDuty(user_id, gmid, date){
	$('#myModal').on('shown', function () {
		$(this).find("#mapiframe").attr("src", "view.php?USER_ID="+user_id+"&mid=" + gmid);
	});
	$('#myModal').modal();
	$('#myModalLabel').text(date);
}
</script>


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

$query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $DAY_TOTAL=$ROW[0]+1;

$MSG = sprintf(_("��%d��"), $DAY_TOTAL);
?>

<h5 class="attendance-title"><?=_("���°��ѯ���")?> - [<?=format_date($DATE1)?> <?=_("��")?> <?=format_date($DATE2)?> <?=$MSG?>]</h5>


<?
$WHERE_STR="";

$query4 = "SELECT USER.USER_NAME,DEPARTMENT.DEPT_NAME from USER,DEPARTMENT where DEPARTMENT.DEPT_ID = USER.DEPT_ID and USER.USER_ID='$USER_ID' ";
$cursor4= exequery(TD::conn(),$query4);
$USERS_TEM=array();
$DUTY_INFO_ARR=array();
if($ROW4=mysql_fetch_array($cursor4))
{
   $USER_NAME=$ROW4["USER_NAME"];
   $DEPT_NAME=$ROW4["DEPT_NAME"];
	$DAYS_TEM=array();
	$USER_INFO=array("USER_NAME"=>$USER_NAME,"DEPT_NAME"=>$DEPT_NAME);
	$USERS_TEM[$USER_ID]["INFO"]=$USER_INFO;
   for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
   {
   		$DUTY_ARR=array();
		$query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') order by REGISTER_TIME DESC";
		$cursor= exequery(TD::conn(),$query);
		while($ROW=mysql_fetch_array($cursor))
		{
			$DUTY_ARR[$ROW["REGISTER_TYPE"]]=array(
				"DUTY_TYPE"=> $ROW["DUTY_TYPE"],
				"REGISTER_TIME"=>$ROW["REGISTER_TIME"],
				"REGISTER_IP"=>$ROW["REGISTER_IP"],
				"REMARK"=>str_replace("\n","<br>",$ROW["REMARK"]),
				"IS_MOBILE_DUTY"=>$ROW["IS_MOBILE_DUTY"],
				"ATTEND_MOBILE_ID"=>$ROW['ATTEND_MOBILE_ID']
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
					$DUTY_TYPE_ARR["TYPE"][$I]=array( "DUTY_TIME" => $ROW["DUTY_TIME".$I] ,"DUTY_TYPE" => $ROW["DUTY_TYPE".$I]);
			}
			$DUTY_TYPE_ARR["NAME"]=$DUTY_NAME;
		}
		//if(empty($result_arr[$DUTY_TYPE]["INFO"]))
		if(!isset($DUTY_INFO_ARR[$DUTY_TYPE]))
			$DUTY_INFO_ARR[$DUTY_TYPE]=$DUTY_TYPE_ARR;
		//if(empty($result_arr[$DUTY_TYPE]["USERS"][$USER_ID]["INFO"]))
			
		$OUGHT_TO=1;
		$SHOW_HOLIDAY="";
		//�������
		if(($IS_HOLIDAY=check_holiday($J))!=0)//�Ƿ���Ϣ��
		{
			$SHOW_HOLIDAY.="<font color='#008000'>"._("�ڼ���")."</font>";
			$OUGHT_TO=0;
		}
		else if(($IS_HOLIDAY1=check_holiday1($J,$GENERAL))!=0)//�Ƿ�˫����
		{
			$SHOW_HOLIDAY.="<font color='#008000'>"._("������")."</font>";
			$OUGHT_TO=0;
		}
		else if(($IS_EVECTION =check_evection($USER_ID,$J))!=0)//�Ƿ����
		{
			$SHOW_HOLIDAY.="<font color='#008000'>"._("����")."</font>"; 
			$OUGHT_TO=0;
		}
//		if($SHOW_HOLIDAY!="" || $SHOW_HOLIDAY2!="")
//			$CLASS="TableContent";
//		else
			$CLASS="TableData";
		$DAYS_TEM[$J]["CLASS"]=$CLASS;
		$DAYS_TEM[$J]["DUTY_TYPE"]=$DUTY_TYPE;
		$REGISTERS_TEM=array();
		$HAS_DUTY_DAY=0;
		foreach($DUTY_TYPE_ARR["TYPE"] as $REGISTER_TYPE => $DUTY_TYPE_ONE)//�������Ű���Ҫ�Ǽǵ�
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
				$HAS_DUTY_DAY=1;
			}

			//��¼��ȡ����$REGISTER_TIME���Ǽ�ʱ�䣬$REGISTER_IP���Ǽ�IP ��$REMARK����ע
			
			$SHOW_HOLIDAY2="";			
			//$date_info=date('H:i:s',$DUTY_TYPE_ARR["TYPE"][$REGISTER_TYPE]["DUTY_TIME"]);
			$date_info = $DUTY_TYPE_ARR ["TYPE"] [$REGISTER_TYPE] ["DUTY_TIME"];//���� 2014-06-26 ��鷢������Ĵ��뵼��һ���������޸�Ϊ�˴��롣
			//��ʱ�����ġ�
			if(($IS_LEAVE=check_leave($USER_ID,$J,$date_info))!="0")//�Ƿ����
			{
				$SHOW_HOLIDAY2.="<font color='#008000'>"._("���")."-$IS_LEAVE</font>";
				$OUGHT_TO=0;
			}
			else if(($IS_OUT=check_out($USER_ID,$J,$date_info))!=0)//�Ƿ����
			{
				$SHOW_HOLIDAY2.="<font color='#008000'>"._("���")."</font>";
				$OUGHT_TO=0;
			}
			else if($OUGHT_TO!=0)
				$OUGHT_TO=1;
			
			$SHOW_STR="";
			if($DUTY_ONE_ARR["IS_MOBILE_DUTY"]){
				$SHOW_STR='<a onclick="showDuty(\''.$USER_ID.'\', '.$DUTY_ONE_ARR['ATTEND_MOBILE_ID'].', \''.$J.'\')" href="javascript:void(0);"><span class="icon16-remind_2" title="�ƶ��˲���"></span></a>';
			}
			if($HAS_DUTY==1)//�Ѿ��Ǽ�
			{
				$REGISTER_TIME2=$DUTY_ONE_ARR["REGISTER_TIME"];
				$REGISTER_TIME=$DUTY_ONE_ARR["REGISTER_TIME"];
				$REGISTER_TIME=strtok($REGISTER_TIME," ");
				$REGISTER_TIME=strtok(" ");
				
				//�ٵ����˲���ȫ�ڣ�$IS_ALL=0;
					//echo $USER_ID."Ӧ�ã�$DUTY_TIME_OUGHT--ʵ�ʣ�$REGISTER_TIME";
					//echo "���ã�".compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)."<br>";
				if($START_OR_END=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==1)
					$SHOW_STR.=$REGISTER_TIME." <font color=red><b>"._("�ٵ�")."</b></font>";//�ٵ�
				
				else if($START_OR_END=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==-1)
					$SHOW_STR.=$REGISTER_TIME." <font color=red><b>"._("����")."</b></font>";//����
				else
					$SHOW_STR.=$REGISTER_TIME;
				if($SHOW_HOLIDAY!="")
					$SHOW_STR.=_("��").$SHOW_HOLIDAY._("��");
				else if($SHOW_HOLIDAY2!="")
					$SHOW_STR.=_("��").$SHOW_HOLIDAY2._("��");
                 if($REMARK!="")
                 {
                    $REMARK="<br>"._("˵����").$REMARK;
                 	$SHOW_STR.=$REMARK."&nbsp;<a href=\"javascript:remark('$USER_ID','$REGISTER_TYPE','$REGISTER_TIME2');\" title=\""._("�޸�˵��")."\">"._("�޸�")."</a>";
                 }
			}
			else if (($HAS_DUTY == 0 && $OUGHT_TO == 1) || ($HAS_DUTY == 0 && $OUGHT_TO == 0 && $SHOW_HOLIDAY=='' && $SHOW_HOLIDAY2=='')) 			// Ӧ�õǼǣ�û�еǼǵ�
			{
				$SHOW_STR.=_("δ�Ǽ�");
			}
			else
			{
				if($SHOW_HOLIDAY!="")
					$SHOW_STR.=$SHOW_HOLIDAY;
				else if($SHOW_HOLIDAY2!="")
					$SHOW_STR.=$SHOW_HOLIDAY2;
			}
			$REGISTERS_TEM[$REGISTER_TYPE]=$SHOW_STR;

		}
		$DAYS_TEM[$J]["REGISTERS"]=$REGISTERS_TEM;
		$DAYS_TEM[$J]["DUTY_TYPE"]=$DUTY_TYPE;
		$DAYS_TEM[$J]["HAS_DUTY_DAY"]=$HAS_DUTY_DAY;
	}
	$USERS_TEM[$USER_ID]["DAYS"]=$DAYS_TEM;
}

if(count($DUTY_INFO_ARR)>0)
{
	$table_head=array();
   	foreach($DUTY_INFO_ARR as $DUTY_TYPE => $DUTY_ARR)
   	{
   		ob_start();
?>
<h5 class="attendance-title"><?=$DUTY_ARR["NAME"]?></h5>
<table class="table table-bordered" align="center">
	<tr class="">
		<th nowrap align="center"><?=_("����")?></th>
		<th nowrap align="center"><?=_("����")?></th>
		<th nowrap align="center"><?=_("����")?></th>
<?
		foreach($DUTY_ARR["TYPE"] as $INFO)
		{
			if($INFO["DUTY_TYPE"]==1)
				$TYPE_NAME=_("���ϰࣩ");
			else
				$TYPE_NAME=_("���°ࣩ");
			echo "<th nowrap align=\"center\">".$INFO["DUTY_TIME"].$TYPE_NAME."</th>";
 		}
		echo '</tr>';
		$table_head[$DUTY_TYPE]=ob_get_contents();
		ob_clean();
   	}
   	$table_line=array();
   	if(count($USERS_TEM)>0)
   	{
	   	foreach($USERS_TEM as $USER_ID => $USER_DATA)
	   	{
			//��ͷ������
			$USER_INFO=$USER_DATA["INFO"];
			
			if(count($USER_DATA["DAYS"])>0)
			{
				foreach($USER_DATA["DAYS"] as $DATE => $DATE_ARR)
				{
					$has_duty_day_tem=$DATE_ARR["HAS_DUTY_DAY"];
					ob_start();
?>
		<tr class="<?=$DATE_ARR["CLASS"]?>">
			<td nowrap align="center"><?=$DATE?>(<?=get_week($DATE)?>)</td>
			<td nowrap align="center"><?=$USER_INFO["USER_NAME"]?></td>
     		<td nowrap align="center"><?=$USER_INFO["DEPT_NAME"]?></td>
<?
					foreach($DATE_ARR["REGISTERS"] as $REGISTER_TYPE => $SHOW_STR)
					{
?>
			<td nowrap align="center" <?=$has_duty_day_tem==0?"style=\"color:red\"":""?> ><?=$SHOW_STR?></td>
<?
					}
					echo "</tr>";
					$table_line[$DATE_ARR["DUTY_TYPE"]][]=ob_get_contents();
					ob_clean();
				}
 		  	}
 		}
	}
}
else
{
	Message(_("����"),_("�Ű���������"));
	Button_Back();
}
$show_table="";
foreach($table_head as $DUTY_TYPE => $show_head)
{
	if(!empty($table_line[$DUTY_TYPE]) && is_array($table_line[$DUTY_TYPE]))
	{
		$show_table.=$show_head;
		foreach($table_line[$DUTY_TYPE] as $show_line)
			$show_table.=$show_line;
		$show_table.="</table>";
	}
}
echo $show_table;
?>
		<!-- Modal -->
		<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">��</button>
				<h3 id="myModalLabel">�ֻ����ڲ鿴</h3>
			</div>
			<div class="modal-body" id="myModalBody">
				<iframe src="" frameborder="0" id="mapiframe"></iframe>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">�ر�</button>
			</div>
		</div>

</body>
</html>