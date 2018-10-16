<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("general/attendance/manage/check_func.func.php");

$HTML_PAGE_TITLE = _("���°��¼��ѯ");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
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

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
    <span class="big3"> <?=_("���°��ѯ���")?> - [<?=format_date($DATE1)?> <?=_("��")?> <?=format_date($DATE2)?> <?=$MSG?>]</span><br>
    </td>
  </tr>
</table>

<br>

<?
 $query = "SELECT * from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $NO_DUTY_USER=$ROW["PARA_VALUE"];

 $WHERE_STR=" where 1=1";
 if($DEPARTMENT1!="ALL_DEPT")
 {
	  $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
    $WHERE_STR.=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
 }


$query4 = "SELECT USER.USER_NAME,USER.USER_ID,USER.USER_PRIV_NAME,DEPARTMENT.DEPT_NAME from USER,USER_EXT,USER_PRIV,DEPARTMENT ".$WHERE_STR." and (USER.NOT_LOGIN = 0 or USER.NOT_MOBILE_LOGIN = 0) and not find_in_set(USER.USER_ID,'$NO_DUTY_USER') and USER_EXT.DUTY_TYPE!='99' and USER_EXT.USER_ID=USER.USER_ID and DEPARTMENT.DEPT_ID = USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV order by DEPT_NO,PRIV_NO,USER_NO,USER_NAME";
$cursor4= exequery(TD::conn(),$query4);
$USER_COUNT=0;
$USERS_TEM=array();
$DUTY_INFO_ARR=array();
while($ROW4=mysql_fetch_array($cursor4))
{
   $USER_COUNT++;
   $USER_NAME=$ROW4["USER_NAME"];
   $DEPT_NAME=$ROW4["DEPT_NAME"];
   $USER_ID=$ROW4["USER_ID"];
   $USER_PRIV_NAME=$ROW4["USER_PRIV_NAME"];

	$DAYS_TEM=array();
	$USER_INFO=array("USER_NAME"=>$USER_NAME,"DEPT_NAME"=>$DEPT_NAME,"USER_PRIV_NAME"=>$USER_PRIV_NAME);
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
				"REMARK"=>str_replace("\n","<br>",$ROW["REMARK"])
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
				$cn = $I%2==0?"2":"1";
				if($ROW["DUTY_TIME".$I]!="")
					$DUTY_TYPE_ARR["TYPE"][$I]=array( "DUTY_TIME" => $ROW["DUTY_TIME".$I] ,"DUTY_TYPE" => $cn);
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
			$SHOW_HOLIDAY.=_("�ڼ���");
			$OUGHT_TO=0;
		}
		else if(($IS_HOLIDAY1=check_holiday1($J,$GENERAL))!=0)//�Ƿ�˫����
		{
			$SHOW_HOLIDAY.=_("������");
			$OUGHT_TO=0;
		}
		else if(($IS_EVECTION =check_evection($USER_ID,$J))!=0)//�Ƿ����
		{
			$SHOW_HOLIDAY.=_("����");
			$OUGHT_TO=0;
		}
		if($SHOW_HOLIDAY!="" || $SHOW_HOLIDAY2!="")
			$CLASS="TableContent";
		else
			$CLASS="TableData";
		$DAYS_TEM[$J]["CLASS"]=$CLASS;
		$DAYS_TEM[$J]["DUTY_TYPE"]=$DUTY_TYPE;
		$REGISTERS_TEM=array();
		foreach($DUTY_TYPE_ARR["TYPE"] as $REGISTER_TYPE => $DUTY_TYPE_ONE)//�������Ű���Ҫ�Ǽǵ�
		{
			$START_OR_END=$DUTY_TYPE_ONE["DUTY_TYPE"];			//���°ࣺ1���ϰ࣬2���°ࡣ
			$DUTY_TIME_OUGHT=$DUTY_TYPE_ONE["DUTY_TIME"];//�趨�Ŀ���ʱ�䡣
			$DUTY_ONE_ARR=$DUTY_ARR[$REGISTER_TYPE];//��Ӧ�ĵǼǼ�¼
			$OUGHT_TO1=1;
			$HAS_DUTY=0;
			if(is_array($DUTY_ONE_ARR) && !empty($DUTY_ONE_ARR))
			{
				foreach($DUTY_ONE_ARR as $KEY => $VALUE)
					$$KEY=$VALUE;
				$HAS_DUTY=1;
			}

			//��¼��ȡ����$REGISTER_TIME���Ǽ�ʱ�䣬$REGISTER_IP���Ǽ�IP ��$REMARK����ע
			//var_dump(check_leave($USER_ID,$J,$DUTY_TYPE_ARR[$REGISTER_TYPE]["DUTY_TIME"]));
			$SHOW_HOLIDAY2="";
			$SHOW_HOLIDAY2="";
			$aaa = strtotime($DUTY_TYPE_ARR["TYPE"][$REGISTER_TYPE]["DUTY_TIME"]);
			$date_info=date('H:i:s',$aaa);
			//��ʱ�����ġ�
			if(($IS_LEAVE=check_leave($USER_ID,$J,$date_info))!="0")//�Ƿ����
			{
				$SHOW_HOLIDAY2.=_("���")."-$IS_LEAVE";
				$OUGHT_TO1=0;
			}
			else if(($IS_OUT=check_out($USER_ID,$J,$date_info))!=0)//�Ƿ����
			{
				$SHOW_HOLIDAY2.=_("���");
				$OUGHT_TO1=0;
			}

			$SHOW_STR="";
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
					$SHOW_STR.=$REGISTER_TIME._("�ٵ�");//�ٵ�

				else if($START_OR_END=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==-1)
					$SHOW_STR.=$REGISTER_TIME._("����");//����
				else
					$SHOW_STR.=$REGISTER_TIME;
				if($SHOW_HOLIDAY!="")
					$SHOW_STR.=_("��").$SHOW_HOLIDAY._("��");
				else if($SHOW_HOLIDAY2!="")
					$SHOW_STR.=_("��").$SHOW_HOLIDAY2._("��");
			if($REMARK!="")
                 {
                    $REMARK=_("˵����").$REMARK;
                 	$SHOW_STR.=$REMARK;
                 }
			}
			else if($HAS_DUTY==0 && $OUGHT_TO==1 && $OUGHT_TO1==1)//Ӧ�õǼǣ�û�еǼǵ�
				$SHOW_STR.=_("δ�Ǽ�");
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
	}
	$USERS_TEM[$USER_ID]["DAYS"]=$DAYS_TEM;
}
if($USER_COUNT==0)
{
	Message(_("��ʾ"),_("�����°��¼��"));
	Button_Back();
	exit;
}
if(count($DUTY_INFO_ARR)>0)
{
	$table_head=array();
   	foreach($DUTY_INFO_ARR as $DUTY_TYPE => $DUTY_ARR)
   	{
   		$head_tem=array();
   		$head_tem[]=_("����");
   		$head_tem[]=_("����");
   		$head_tem[]=_("����");
   		$head_tem[]=_("��ɫ");
		foreach($DUTY_ARR["TYPE"] as $INFO)
		{
			if($INFO["DUTY_TYPE"]==1)
				$TYPE_NAME=_("���ϰࣩ");
			else
				$TYPE_NAME=_("���°ࣩ");
			$head_tem[]=$INFO["DUTY_TIME"].$TYPE_NAME;
 		}
 		 $table_head[$DUTY_TYPE]["title"]=$DUTY_ARR["NAME"];
 		 $table_head[$DUTY_TYPE]["head"]=$head_tem;
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
					$line_tem=array();
					$line_tem[]=$DATE._("��").get_week($DATE)._("��");
					$line_tem[]=$USER_INFO["USER_NAME"];
     				$line_tem[]=$USER_INFO["DEPT_NAME"];
     				$line_tem[]=$USER_INFO["USER_PRIV_NAME"];
					foreach($DATE_ARR["REGISTERS"] as $REGISTER_TYPE => $SHOW_STR)
						$line_tem[]=$SHOW_STR;
					$table_line[$DATE_ARR["DUTY_TYPE"]][]=$line_tem;
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
ob_end_clean();
require_once ('inc/ExcelWriter.php');
$NEWFILENAME=_("���°���ϸ��¼");
$objExcel = new ExcelWriter();
$objExcel->setFileName($NEWFILENAME);

foreach($table_head as $DUTY_TYPE => $show_head)
{
	$ROW_TITLE=format_cvs($show_head["title"]);
	$objExcel->addRow($ROW_TITLE);
	$ROW_HEAD="";
	foreach($show_head["head"] as $head_ele)
		$ROW_HEAD.=format_cvs($head_ele).",";
	$objExcel->addRow($ROW_HEAD);
	if(count($table_line)>0)
	{
		foreach($table_line[$DUTY_TYPE] as $line_list)
		{
			$ROW_OUT="";
			foreach($line_list as $value)
			{
				$ROW_OUT.=format_cvs($value).",";
			}
			$objExcel->addRow($ROW_OUT);
		}
	}
}
$objExcel->Save();
?>