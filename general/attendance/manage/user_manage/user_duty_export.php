<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("../check_func.func.php");
$HTML_PAGE_TITLE = _("上下班记录查询");
include_once("inc/header.inc.php");

?>
<body class="bodycolor">
<?
//----------- 合法性校验 ---------
if($DATE1!="")
{
    $TIME_OK=is_date($DATE1);
    
    if(!$TIME_OK)
    {
        Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($DATE2!="")
{
    $TIME_OK=is_date($DATE2);
    
    if(!$TIME_OK)
    {
        Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if(compare_date($DATE1,$DATE2)==1)
{
    Message(_("错误"),_("查询的起始日期不能晚于截止日期"));
    Button_Back();
    exit;
}
$select = "select DEPT_ID from USER where USER_ID='$USER_ID'";
$arr = exequery(TD::conn(),$select);
if($row = mysql_fetch_array($arr))
{
	$DEPT_ID=$row[0];
}

//验证人力资源管理员
$is_manage = 0;
$sql = "SELECT DEPT_ID FROM hr_manager WHERE find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_MANAGER) and DEPT_ID = '$DEPT_ID'";
$cursor1 = exequery(TD::conn(),$sql);
if($arr=mysql_fetch_array($cursor1))
{
	$is_manager = 1;
}

if(!is_dept_priv($DEPT_ID) && $is_manager!=1)
{
    Message(_("错误"),_("不属于管理范围内的用户"));
    exit;
}

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
		//按日算的
		if(($IS_HOLIDAY=check_holiday($J))!=0)//是否休息日
		{
			$SHOW_HOLIDAY.=_("节假日");
			$OUGHT_TO=0;
		}
		else if(($IS_HOLIDAY1=check_holiday1($J,$GENERAL))!=0)//是否双休日
		{
			$SHOW_HOLIDAY.=_("公休日");
			$OUGHT_TO=0;
		}
		else if(($IS_EVECTION =check_evection($USER_ID,$J))!=0)//是否出差
		{
			$SHOW_HOLIDAY.=_("出差"); 
			$OUGHT_TO=0;
		}
		
		$DAYS_TEM[$J]["DUTY_TYPE"]=$DUTY_TYPE;
		$REGISTERS_TEM=array();
		$HAS_DUTY_DAY=0;
		foreach($DUTY_TYPE_ARR["TYPE"] as $REGISTER_TYPE => $DUTY_TYPE_ONE)//遍历按排班需要登记的
		{
			$START_OR_END=$DUTY_TYPE_ONE["DUTY_TYPE"];			//上下班：1：上班，2：下班。
			$DUTY_TIME_OUGHT=$DUTY_TYPE_ONE["DUTY_TIME"];//设定的考勤时间。
			$DUTY_ONE_ARR=$DUTY_ARR[$REGISTER_TYPE];//相应的登记记录
			
			$HAS_DUTY=0;
			if(is_array($DUTY_ONE_ARR) && !empty($DUTY_ONE_ARR))
			{
				foreach($DUTY_ONE_ARR as $KEY => $VALUE)
					$$KEY=$VALUE;
				$HAS_DUTY=1;
				$HAS_DUTY_DAY=1;
			}

			//记录已取出：$REGISTER_TIME：登记时间，$REGISTER_IP：登记IP 。$REMARK：备注
			
			$SHOW_HOLIDAY2="";			
			//$date_info=date('H:i:s',$DUTY_TYPE_ARR["TYPE"][$REGISTER_TYPE]["DUTY_TIME"]);
			$date_info = $DUTY_TYPE_ARR ["TYPE"] [$REGISTER_TYPE] ["DUTY_TIME"];//高煜 2014-06-26 检查发现上面的代码导致一个错误，已修改为此代码。
			//按时间点算的。
			if(($IS_LEAVE=check_leave($USER_ID,$J,$date_info))!="0")//是否请假
			{
				$SHOW_HOLIDAY2.=_("请假");
				$OUGHT_TO=0;
			}
			else if(($IS_OUT=check_out($USER_ID,$J,$date_info))!=0)//是否外出
			{
				$SHOW_HOLIDAY2.=_("外出");
				$OUGHT_TO=0;
			}
			else if($OUGHT_TO!=0)
				$OUGHT_TO=1;
			$SHOW_STR="";
			if($HAS_DUTY==1)//已经登记
			{
				$REGISTER_TIME2=$DUTY_ONE_ARR["REGISTER_TIME"];
				$REGISTER_TIME=$DUTY_ONE_ARR["REGISTER_TIME"];
				$REGISTER_TIME=strtok($REGISTER_TIME," ");
				$REGISTER_TIME=strtok(" ");
				
				//迟到早退不算全勤，$IS_ALL=0;
					//echo $USER_ID."应该：$DUTY_TIME_OUGHT--实际：$REGISTER_TIME";
					//echo "所得：".compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)."<br>";
				if($START_OR_END=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==1)
					$SHOW_STR.=$REGISTER_TIME._("迟到");//迟到
				
				else if($START_OR_END=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==-1)
					$SHOW_STR.=$REGISTER_TIME._("早退");//早退
				else
					$SHOW_STR.=$REGISTER_TIME;
				if($SHOW_HOLIDAY!="")
					$SHOW_STR.=_("（").$SHOW_HOLIDAY._("）");
				else if($SHOW_HOLIDAY2!="")
					$SHOW_STR.=_("（").$SHOW_HOLIDAY2._("）");
			if($REMARK!="")
                 {
                    $REMARK=_("说明：").$REMARK;
                 	$SHOW_STR.=$REMARK;
                 }
			}
			else if (($HAS_DUTY == 0 && $OUGHT_TO == 1) || ($HAS_DUTY == 0 && $OUGHT_TO == 0 && $SHOW_HOLIDAY=='' && $SHOW_HOLIDAY2=='')) 			// 应该登记，没有登记的
			{
				$SHOW_STR.=_("未登记");
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
   		$head_tem=array();
   		$head_tem[]=_("日期");
		foreach($DUTY_ARR["TYPE"] as $INFO)
		{
			if($INFO["DUTY_TYPE"]==1)
				$TYPE_NAME=_("（上班）");
			else
				$TYPE_NAME=_("（下班）");
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
			//表头结束。
			$USER_INFO=$USER_DATA["INFO"];
			
			if(count($USER_DATA["DAYS"])>0)
			{
				foreach($USER_DATA["DAYS"] as $DATE => $DATE_ARR)
				{	
					$line_tem=array();
					$line_tem[]=$DATE._("（").get_week($DATE)._("）");
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
	Message(_("警告"),_("排班类型有误"));
	Button_Back();
}
ob_end_clean();
require_once ('inc/ExcelWriter.php');

$NEWFILENAME=sprintf(_("%s的上下班详细记录"), $USER_NAME);
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