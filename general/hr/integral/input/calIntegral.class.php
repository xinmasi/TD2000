<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("general/attendance/manage/check_func.func.php");
class calIntegral
{
	private $_modual_list;
	private $_item_score;
	private $_all_score;
	private $_last_cal_time;
	private $_conn;

	//�����Ӧ
	private $_list_array=array(
		"email" => "OASY_01",
		"workflow" => "OASY_02",
		"attend" => "OASY_03",
		"calendar" => "OASY_04",
		"diary" => "OASY_05",
		"project" => "OASY_06",
		"news" => "OASY_07",
		"knowledge" => "OASY_08",
		"hrms" => "RS001,RS002,RS003,RS004"
	);

	public function __construct($moduals=array()){
				$this->_conn=TD::conn();

		//��֯��Ҫ������ֵ�ģ���ַ���
		$moduals_str="";
		foreach($moduals as $value)
			$moduals_str.=$this->_list_array[$value].",";
		$moduals_str=trim($moduals_str,",");
		$this->_modual_list=$moduals_str;
		//print_r($this->_modual_list);


		//������ܻ����Ļ������ֵ
		$this->_setItemScore();
		//print_r($this->_item_score);
		if(empty($this->_item_score))
		{
			Message(_("��ʾ"),_("��ѡģ��û�ж�����û��֣�"));
			Button_back();
			exit;
		}
		//����ÿ���ϴμ�����ֵ�ʱ��
		$this->_setLastCalTime();
		//print_r($this->_last_cal_time);

		//������Ҫ�����ģ��Ļ���
		foreach($moduals as $value)
			eval('$this->_set_'.$value.'_integral();');
		if(empty($this->_all_score))
		{
			Message(_("��ʾ"),_("��ʱû�����ܻ�ô˻��֣�"));
			Button_back();
			exit;
		}

		//�洢��õĻ���
		$this->_calIntegral();
	}

	private function _set_email_integral()
	{
		$last_time=$this->_last_cal_time["OASY_01"];
		//���ʼ�
		if(isset($this->_item_score["OASY_01_01"]))
		{
			include_once("inc/utility_email.php");
			$email_arr=GetSentMailNum("",$last_time);
			if(!empty($email_arr))
			{
				foreach($email_arr as $USER_ID => $SUM)
				{
					if($SUM==0) continue;
					$this->_all_score[$USER_ID]["OASY_01_01"]["SUM"]=$SUM;
					$this->_all_score[$USER_ID]["OASY_01_01"]["SCORE"]=$this->_item_score["OASY_01_01"]["ITEM_VALUE"];
				}
			}
		}
	}

	private function _set_workflow_integral()
	{
		$last_time=$this->_last_cal_time["OASY_02"];
		//������
		if(isset($this->_item_score["OASY_02_01"]))
		{
			$query="SELECT count(*) as SUM,BEGIN_USER FROM FLOW_RUN WHERE BEGIN_TIME >='$last_time' GROUP BY BEGIN_USER";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["BEGIN_USER"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_02_01"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_02_01"]["SCORE"]=$this->_item_score["OASY_02_01"]["ITEM_VALUE"];
			}
		}

		//���칤��
		if(isset($this->_item_score["OASY_02_02"]))
		{
			$query="SELECT count(*) as SUM,USER_ID FROM FLOW_RUN_PRCS WHERE OP_FLAG=1 AND CREATE_TIME >='$last_time' GROUP BY USER_ID";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["USER_ID"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_02_02"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_02_02"]["SCORE"]=$this->_item_score["OASY_02_02"]["ITEM_VALUE"];
			}
		}

		//���칤��
		if(isset($this->_item_score["OASY_02_03"]))
		{
			$query="SELECT count(*) as SUM,USER_ID FROM FLOW_RUN_PRCS WHERE OP_FLAG=0 AND CREATE_TIME >='$last_time' GROUP BY USER_ID";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["USER_ID"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_02_03"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_02_03"]["SCORE"]=$this->_item_score["OASY_02_03"]["ITEM_VALUE"];
			}
		}

		//��ʱ����
		if(isset($this->_item_score["OASY_02_04"]))
		{
			$query="SELECT count(*) as SUM,USER_ID FROM FLOW_RUN_PRCS WHERE TIME_OUT_FLAG=1 AND CREATE_TIME >='$last_time' GROUP BY USER_ID";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["USER_ID"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_02_04"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_02_04"]["SCORE"]=$this->_item_score["OASY_02_04"]["ITEM_VALUE"];
			}
		}
	}

	private function _set_attend_integral()
	{
		$DATE1=$this->_last_cal_time["OASY_03"];
		if(strtotime($DATE1) <= strtotime("2009-01-01 00:00:00"))
			$DATE1="2009-01-01 00:00:00";
		$DATE1_TEM=date("Y-m-d",strtotime($DATE1));
		$DATE2=date("Y-m-d",time());
		//-----ȫ�ڣ��ٵ�����������ͳ��----start-------
		if(isset($this->_item_score["OASY_03_01"]) || isset($this->_item_score["OASY_03_05"]) || isset($this->_item_score["OASY_03_06"]))
		{
			$query1 = "SELECT USER.USER_ID from USER,USER_EXT,USER_PRIV where USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE!='99' and USER.NOT_LOGIN=0 and USER.USER_PRIV=USER_PRIV.USER_PRIV order by PRIV_NO,USER_NO,USER_NAME";
			$cursor1= exequery($this->_conn,$query1);
			while($ROW1=mysql_fetch_array($cursor1))
			{
			   $USER_ID=$ROW1["USER_ID"];
			   $all_day_tem= 0;//ȫ������
				$later_tem=0;//�ٵ�����
				$early_tem=0;//���˴���
				$no_work_tem=0;//��������
			   for($J=$DATE1_TEM;$J<$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
			   {
			   		$is_no_work=1;//�Ƿ������Ĭ����
			   		$DUTY_ARR=array();
					$query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') order by REGISTER_TIME DESC";
					$cursor= exequery($this->_conn,$query);
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
					$cursor= exequery($this->_conn,$query);
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
						//��ʱ�����ġ�
						if($IS_LEAVE=check_leave($USER_ID,$J,$DUTY_TYPE_ARR[$REGISTER_TYPE]["DUTY_TIME"])!=0)//�Ƿ����
							$OUGHT_TO=0;
						else if($IS_OUT=check_out($USER_ID,$J,$DUTY_TYPE_ARR[$REGISTER_TYPE]["DUTY_TIME"])!=0)//�Ƿ����
							$OUGHT_TO=0;

						if($HAS_DUTY==1)//�Ѿ��Ǽǣ������㣩
						{
							$is_no_work=0;//ֻҪ�Ǽ��ˣ��Ͳ��ǿ�����
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
									$late_time_tem++;//�ٵ�
								$IS_ALL=0;
							}
							if($START_OR_END=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==-1)
							{
								if($OUGHT_TO!=0)
									$early_tem++;//����
								$IS_ALL=0;
							}
						}
						else
							$IS_ALL=0;
					}
					if($OUGHT_TO==0)
						$is_no_work=0;
					if($IS_ALL==1)
						$all_day_tem++;//ȫ�ڵ�
					if($is_no_work==1)//����
						$no_work_tem++;
				}
				if($all_day_tem > 0 && $this->_item_score["OASY_03_01"] > 0)//ȫ��
				{
					$this->_all_score[$USER_ID]["OASY_03_01"]["SUM"]=$all_day_tem;
					$this->_all_score[$USER_ID]["OASY_03_01"]["SCORE"]=$this->_item_score["OASY_03_01"]["ITEM_VALUE"];
				}
				if($no_work_tem > 0 && $this->_item_score["OASY_03_06"] > 0)//����
				{
					$this->_all_score[$USER_ID]["OASY_03_06"]["SUM"]=$no_work_tem;
					$this->_all_score[$USER_ID]["OASY_03_06"]["SCORE"]=$this->_item_score["OASY_03_06"]["ITEM_VALUE"];
				}
				if($late_time_tem > 0 && $this->_item_score["OASY_03_05"] > 0)//�ٵ�
				{
					$this->_all_score[$USER_ID]["OASY_03_05"]["SUM"]=$late_time_tem;
					$this->_all_score[$USER_ID]["OASY_03_05"]["SCORE"]=$this->_item_score["OASY_03_05"]["ITEM_VALUE"];
				}

			}
		}
		//-----ȫ�ڣ��ٵ�����������ͳ��----end-------

		//�������
		if(isset($this->_item_score["OASY_03_02"]))
		{
			$query="select USER_ID,count(*) as SUM from ATTEND_EVECTION where RECORD_TIME >='$DATE1' and ALLOW=1 group by USER_ID";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["USER_ID"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_03_02"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_03_02"]["SCORE"]=$this->_item_score["OASY_03_02"]["ITEM_VALUE"];
			}
		}

		//�Ӱ����
		if(isset($this->_item_score["OASY_03_03"]))
		{
			$query="select USER_ID,count(*) as SUM from ATTENDANCE_OVERTIME where RECORD_TIME >='$DATE1' and (ALLOW=1 or ALLOW=3) group by USER_ID";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["USER_ID"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_03_03"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_03_03"]["SCORE"]=$this->_item_score["OASY_03_03"]["ITEM_VALUE"];
			}
		}

		//�������
		if(isset($this->_item_score["OASY_03_07"]))
		{
			$query="select USER_ID,count(*) as SUM from ATTEND_OUT where SUBMIT_TIME >='$DATE1' and ALLOW=1 group by USER_ID";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["USER_ID"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_03_07"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_03_07"]["SCORE"]=$this->_item_score["OASY_03_07"]["ITEM_VALUE"];
			}
		}

		//����ʱ��
		if(isset($this->_item_score["OASY_03_04"]))
		{
			$query="select USER_ID,ONLINE from USER where NOT_LOGIN=0";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["ONLINE"]/360000;
				$USER_ID=$ROW["USER_ID"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_03_04"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_03_04"]["SCORE"]=$this->_item_score["OASY_03_04"]["ITEM_VALUE"];
			}
		}
	}

	private function _set_calendar_integral()
	{
		include_once("general/hr/integral/input/utility_calendar.php");
		$last_time=$this->_last_cal_time["OASY_04"];
		//������ճ�����
		if(isset($this->_item_score["OASY_04_01"]))
		{
			$cal_arr=GetCalendarNum("",$last_time);
			if(!empty($cal_arr))
			{
				foreach($cal_arr as $USER_ID => $SUM)
				{
					if($SUM==0) continue;
					$this->_all_score[$USER_ID]["OASY_04_01"]["SUM"]=$SUM;
					$this->_all_score[$USER_ID]["OASY_04_01"]["SCORE"]=$this->_item_score["OASY_04_01"]["ITEM_VALUE"];
				}
			}
		}

		//����ɹ�������
		if(isset($this->_item_score["OASY_04_02"]))
		{
			$task_arr=GetTaskNum("",$last_time);
			if(!empty($task_arr))
			{
				foreach($task_arr as $USER_ID => $SUM)
				{
					if($SUM==0) continue;
					$this->_all_score[$USER_ID]["OASY_04_02"]["SUM"]=$SUM;
					$this->_all_score[$USER_ID]["OASY_04_02"]["SCORE"]=$this->_item_score["OASY_04_02"]["ITEM_VALUE"];
				}
			}
		}
	}

	private function _set_diary_integral()
	{
		$last_time=$this->_last_cal_time["OASY_05"];
		//д��־
		if(isset($this->_item_score["OASY_05_01"]))
		{
			$query="select USER_ID,count(*) as SUM from DIARY where  DIA_TIME >='$last_time' GROUP BY USER_ID";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["USER_ID"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_05_01"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_05_01"]["SCORE"]=$this->_item_score["OASY_05_01"]["ITEM_VALUE"];
			}
		}

		//д��־����
		if(isset($this->_item_score["OASY_05_02"]))
		{
			$query="select USER_ID,count(*) as SUM from DIARY_COMMENT WHERE SEND_TIME>='$last_time' GROUP BY USER_ID";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["USER_ID"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_05_02"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_05_02"]["SCORE"]=$this->_item_score["OASY_05_02"]["ITEM_VALUE"];
			}
		}
	}


	private function _set_project_integral()
	{
		$last_time=$this->_last_cal_time["OASY_06"];
		
		//��Ч������Ŀ������
		if(isset($this->_item_score["OASY_06_01"]))
		{	$last_date=date("Y-m-d",strtotime($last_time));
			$query="select PROJ_OWNER,count(*) as SUM from PROJ_PROJECT where PROJ_START_TIME >='$last_time' and PROJ_STATUS>=2 group by PROJ_OWNER";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["PROJ_OWNER"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_06_01"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_06_01"]["SCORE"]=$this->_item_score["OASY_06_01"]["ITEM_VALUE"];
			}
		}


		//�������������
		if(isset($this->_item_score["OASY_06_02"]))
		{
			$query="select LOG_USER,count(*) as SUM from PROJ_TASK_LOG where LOG_TIME >='$last_time'  and PERCENT=100 group by LOG_USER";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["LOG_USER"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_06_02"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_06_02"]["SCORE"]=$this->_item_score["OASY_06_02"]["ITEM_VALUE"];
			}
		}


		//������������
		if(isset($this->_item_score["OASY_06_03"]))
		{
			$query="select DEAL_USER,count(*) as SUM from PROJ_BUG where CREAT_TIME >='$last_time'  and STATUS>=3 group by DEAL_USER";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["DEAL_USER"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_06_03"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_06_03"]["SCORE"]=$this->_item_score["OASY_06_03"]["ITEM_VALUE"];
			}
		}


	}

	private function _set_news_integral()
	{
		include_once("inc/utility_notify.php");
		$last_time=$this->_last_cal_time["OASY_07"];

		//�ѷ��͹���֪ͨ
		if(isset($this->_item_score["OASY_07_01"]))
		{
			$send_notify_arr=GetNotifyNum("",$last_time);
			if(!empty($send_notify_arr))
			{
				foreach($send_notify_arr as $USER_ID => $SUM)
				{
					if($SUM==0) continue;
					$this->_all_score[$USER_ID]["OASY_07_01"]["SUM"]=$SUM;
					$this->_all_score[$USER_ID]["OASY_07_01"]["SCORE"]=$this->_item_score["OASY_07_01"]["ITEM_VALUE"];
				}
			}
		}

		//�ѷ�������
		if(isset($this->_item_score["OASY_07_02"]))
		{
			$pub_news_arr=GetNewsNum("",$last_time);
			if(!empty($pub_news_arr))
			{
				foreach($pub_news_arr as $USER_ID => $SUM)
				{
					if($SUM==0) continue;
					$this->_all_score[$USER_ID]["OASY_07_02"]["SUM"]=$SUM;
					$this->_all_score[$USER_ID]["OASY_07_02"]["SCORE"]=$this->_item_score["OASY_07_02"]["ITEM_VALUE"];
				}
			}
		}

		//����������
		if(isset($this->_item_score["OASY_07_03"]))
		{
			$audit_notify_arr=GetAuditNotifyNum("",$last_time);
			if(!empty($audit_notify_arr))
			{
				foreach($audit_notify_arr as $USER_ID => $SUM)
				{
					if($SUM==0) continue;
					$this->_all_score[$USER_ID]["OASY_07_03"]["SUM"]=$SUM;
					$this->_all_score[$USER_ID]["OASY_07_03"]["SCORE"]=$this->_item_score["OASY_07_03"]["ITEM_VALUE"];
				}
			}
		}

		//��������
		if(isset($this->_item_score["OASY_07_04"]))
		{
			$com_news_arr=GetNewsCommentNum("",$last_time);
			if(!empty($com_news_arr))
			{
				foreach($com_news_arr as $USER_ID => $SUM)
				{
					if($SUM==0) continue;
					$this->_all_score[$USER_ID]["OASY_07_04"]["SUM"]=$SUM;
					$this->_all_score[$USER_ID]["OASY_07_04"]["SCORE"]=$this->_item_score["OASY_07_04"]["ITEM_VALUE"];
				}
			}
		}

	}


	private function _set_knowledge_integral()
	{
		$last_time=$this->_last_cal_time["OASY_08"];

		//�ļ����½��ļ�
		if(isset($this->_item_score["OASY_08_01"]))
		{
			$query="select CREATER,count(*) as SUM from FILE_CONTENT where SEND_TIME >='$last_time'  group by CREATER";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["CREATER"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_08_01"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_08_01"]["SCORE"]=$this->_item_score["OASY_08_01"]["ITEM_VALUE"];
			}
		}
		//������������
		if(isset($this->_item_score["OASY_08_02"]))
		{
			$query="select USER_ID,count(*) as SUM from BBS_COMMENT where OLD_SUBMIT_TIME >='$last_time' and  IS_CHECK!=0 AND IS_CHECK!=2  group by USER_ID";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["USER_ID"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_08_02"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_08_02"]["SCORE"]=$this->_item_score["OASY_08_02"]["ITEM_VALUE"];
			}
		}

		//֪������
		if(isset($this->_item_score["OASY_08_03"]))
		{
			$query="select CREATOR,count(*) as SUM from WIKI_ASK where CREATE_TIME >='$last_time' group by CREATOR";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["CREATOR"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_08_03"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_08_03"]["SCORE"]=$this->_item_score["OASY_08_03"]["ITEM_VALUE"];
			}
		}

		//��������������
		if(isset($this->_item_score["OASY_08_04"]))
		{
			$query="select USER_ID,count(*) as SUM from BBS_COMMENT where OLD_SUBMIT_TIME >='$last_time' and  IS_CHECK!=0 AND IS_CHECK!=2 and LOCK_YN=1 group by USER_ID";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["USER_ID"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_08_04"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_08_04"]["SCORE"]=$this->_item_score["OASY_08_04"]["ITEM_VALUE"];
			}
		}

		//֪���ش�����
		if(isset($this->_item_score["OASY_08_05"]))
		{
			$query="select ANSWER_USER,count(*) as SUM from WIKI_ASK_ANSWER where ANSWER_TIME >='$last_time'  group by ANSWER_USER";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["ANSWER_USER"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_08_05"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_08_05"]["SCORE"]=$this->_item_score["OASY_08_05"]["ITEM_VALUE"];
			}
		}

		//�ٿ��½�����
		/*
		if(isset($this->_item_score["OASY_08_06"]))
		{
			$query="select ANSWER_USER,count(*) as SUM from WIKI_ASK_ANSWER where ANSWER_TIME >='$last_time'  group by ANSWER_USER";
			$cursor=exequery($this->_conn,$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				$SUM=$ROW["SUM"];
				$USER_ID=$ROW["ANSWER_USER"];
				if($SUM==0) continue;
				$this->_all_score[$USER_ID]["OASY_08_05"]["SUM"]=$SUM;
				$this->_all_score[$USER_ID]["OASY_08_05"]["SCORE"]=$this->_item_score["OASY_08_05"]["ITEM_VALUE"];
			}
		}
		*/
	}


	private function _set_hrms_integral()
	{
		//ѧ��,ְ��,��λ
		$query="select USER_ID,STAFF_HIGHEST_SCHOOL,PRESENT_POSITION,WORK_JOB from HR_STAFF_INFO";
		$cursor=exequery($this->_conn,$query);
		while($ROW=mysql_fetch_array($cursor))
		{
			$USER_ID=$ROW["USER_ID"];
			$STAFF_HIGHEST_SCHOOL=$ROW["STAFF_HIGHEST_SCHOOL"]; //ѧ��
			$PRESENT_POSITION=$ROW["PRESENT_POSITION"];//ְ��
			$WORK_JOB=$ROW["WORK_JOB"];//��λ
			if($STAFF_HIGHEST_SCHOOL!="" && $this->_item_score["XL".$STAFF_HIGHEST_SCHOOL]["ITEM_VALUE"]!=0)
			{
				$this->_all_score[$USER_ID]["RS001"]["SUM"]=1;
				$this->_all_score[$USER_ID]["RS001"]["SCORE"]=$this->_item_score["XL".$STAFF_HIGHEST_SCHOOL]["ITEM_VALUE"];
			}
			if($PRESENT_POSITION!="" && $this->_item_score["ZC".$PRESENT_POSITION]["ITEM_VALUE"]!=0)
			{
				$this->_all_score[$USER_ID]["RS002"]["SUM"]=1;
				$this->_all_score[$USER_ID]["RS002"]["SCORE"]=$this->_item_score["ZC".$PRESENT_POSITION]["ITEM_VALUE"];
			}
			if($WORK_JOB!="" && $this->_item_score["GW".$WORK_JOB]["ITEM_VALUE"]!=0)
			{
				$this->_all_score[$USER_ID]["RS004"]["SUM"]=1;
				$this->_all_score[$USER_ID]["RS004"]["SCORE"]=$this->_item_score["GW".$WORK_JOB]["ITEM_VALUE"];
			}
		}
		$CUR_DATE=date("Y-m-d",time());
		//֤��
		$query="select STAFF_NAME,LICENSE_TYPE from HR_STAFF_LICENSE where EXPIRE_DATE='0000-00-00' or EXPIRE_DATE > '$CUR_DATE'";
		$cursor=exequery($this->_conn,$query);
		while($ROW=mysql_fetch_array($cursor))
		{
			$USER_ID=$ROW["STAFF_NAME"];
			$LICENSE_TYPE=$ROW["LICENSE_TYPE"];
			if($LICENSE_TYPE!="" && $this->_item_score["ZZ".$LICENSE_TYPE]["ITEM_VALUE"]!=0)
			{
				$this->_all_score[$USER_ID]["RS003"]["SUM"]=1;
				$this->_all_score[$USER_ID]["RS003"]["SCORE"]+=(float)$this->_item_score["ZZ".$LICENSE_TYPE]["ITEM_VALUE"];
			}
		}
	}

	private function _setItemScore(){
		$item_score_arr=array();
		$query_sum="select sum(A.WEIGHT) as SUM_WEIGHT from HR_INTEGRAL_ITEM A left join HR_INTEGRAL_ITEM_TYPE B on A.TYPE_ID=B.TYPE_ID where find_in_set(B.TYPE_NO,'$this->_modual_list') and A.USED=1";
		$cursor_sum=exequery($this->_conn,$query_sum);
		if($ROW_SUM=mysql_fetch_array($cursor_sum))
			$SUM_WEIGHT=$ROW_SUM["SUM_WEIGHT"];
		$query="select * from HR_INTEGRAL_ITEM A left join HR_INTEGRAL_ITEM_TYPE B on A.TYPE_ID=B.TYPE_ID where find_in_set(B.TYPE_NO,'$this->_modual_list') and A.USED=1";
		$cursor=exequery($this->_conn, $query);
		while($ROW=mysql_fetch_array($cursor))
		{
			$one_item_arr=array();
			$ITEM_NO=$ROW["ITEM_NO"];
			$WEIGHT=$ROW["WEIGHT"];
			if($SUM_WEIGHT!=0 && $WEIGHT > 0)
				$one_item_arr["ITEM_VALUE"]=round($ROW["ITEM_VALUE"]*$WEIGHT/$SUM_WEIGHT,3);
			else
				$one_item_arr["ITEM_VALUE"]=$ROW["ITEM_VALUE"];
			$item_score_arr[$ITEM_NO]=$one_item_arr;
		}
		$this->_item_score=$item_score_arr;
	}

	private function _setLastCalTime()
	{
		$last_cal_time=array();
		$query="select TYPE_NO,CALCULATE_TIME from HR_INTEGRAL_ITEM_TYPE where find_in_set(TYPE_NO,'$this->_modual_list') ";
		$cursor=exequery($this->_conn,$query);
		while($ROW=mysql_fetch_array($cursor))
		{
			$TYPE_NO=$ROW["TYPE_NO"];
			$CALCULATE_TIME=$ROW["CALCULATE_TIME"];
			$last_cal_time[$TYPE_NO]=$CALCULATE_TIME;
		}
		$this->_last_cal_time=$last_cal_time;
	}

	private function _calIntegral()
	{
		foreach($this->_all_score as $user_id => $all)
		{
			$sum=0;
			$keys="";
			$values="";
			foreach($all as $key => $value)
			{
				$keys.=$key.",";
				$score_tem=(float)$value["SCORE"]*$value["SUM"];
				$sum+=$score_tem;
				$values.="'".$score_tem."',";
			}
			$keys=trim($keys,",");
			$values=trim($values,",");
			$CUR_TIME=date("Y-m-d H:i:s",time());
			$query="insert into HR_INTEGRAL_OA(USER_ID,CREATE_TIME,SUM,$keys) values ('$user_id','$CUR_TIME','$sum',$values)";
			exequery($this->_conn,$query);
			$query_update="update HR_INTEGRAL_ITEM_TYPE set CALCULATE_TIME='$CUR_TIME' where find_in_set(TYPE_NO,'$this->_modual_list')";
			exequery($this->_conn,$query_update);
		}
	}
}