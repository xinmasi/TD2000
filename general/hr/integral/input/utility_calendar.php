<?
include_once("inc/conn.php");
include_once("inc/utility.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
//增加日程
function add_calendar($CAL_ARRAY,$IS_REMIND,$IS_REMIND2) // $USER_ARRAY = array('USER_ID'=>'用户', 'CAL_TIME'=>'开始时间戳','END_TIME'=>'结束时间戳','CAL_TYPE'=>'事务类型','CAL_LEVEL'=>'优先级','CONTENT'=>'内容','OWNER'=>'所属者','TAKER'=>'参与者'); 
{
    if(!is_array($CAL_ARRAY) || sizeof($CAL_ARRAY) < 1)
    return;
    $CAL_KEY_STR="";
    $CAL_KEY_VALUE="";
    $REMIND_USER="";
    foreach($CAL_ARRAY  as $key => $value)
    {
        $CAL_KEY_STR.=$key.",";
        $CAL_KEY_VALUE.="'".$value."',";
        if($key=="USER_ID")
            $USER_ID=$value;
        if($key=="TAKER")
            $TAKER=$value;
        if($key=="OWNER")
            $OWNER=$value;
        if($key=="CONTENT")
            $CONTENT=$value;
        $REMIND_USER.=$USER_ID.",".$TAKER.",".$OWNER;
    }
    $REMIND_USER=td_trim($REMIND_USER);
    $CAL_KEY_STR=td_trim($CAL_KEY_STR);
    $CAL_KEY_VALUE=td_trim($CAL_KEY_VALUE);
    $query="insert into CALENDAR($CAL_KEY_STR) values($CAL_KEY_VALUE)";
    //echo $query;exit;
    exequery(TD::conn(),$query);
    $CAL_ID=mysql_insert_id();
    if($IS_REMIND==1)
    {
        if($REMIND_USER!="")
        {
            $REMIND_URL="1:calendar/arrange/note.php?CAL_ID=".$CAL_ID;
            $SMS_CONTENT=_("请查看日程安排！")."\n"._("内容：").csubstr($CONTENT,0,100);
            send_sms("",$_SESSION["LOGIN_USER_ID"],$REMIND_USER,5,$SMS_CONTENT,$REMIND_URL,$CAL_ID);
        }
    }
    if($IS_REMIND2==1)
    {
         $SMS_CONTENT=_("OA日程安排:").$CONTENT;
         send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$REMIND_USER,$SMS_CONTENT,5);
    }
    $STATUS = "";
    if($CAL_ID!="")
    {
        $STATUS = "success";
    }
    else
    {
        $STATUS = "error";
    }
    
    $a_return = array();
    $a_return['cal_id'] = $CAL_ID;
    $a_return['status'] = $STATUS;
    
    return $a_return;
}
//修改日程
function update_calendar($CAL_ARRAY,$CAL_ID) // $USER_ARRAY = array('USER_ID'=>'用户', 'CAL_TIME'=>'开始时间戳','END_TIME'=>'结束时间戳','CAL_TYPE'=>'事物类型','CAL_LEVEL'=>'优先级','CONTENT'=>'内容','OWNER'=>'所属者','TAKER'=>'参与者'); 
{
   $CAL_KEY_STR="";
   $CAL_KEY_VALUE="";
   $REMIND_USER="";
   if(!is_array($CAL_ARRAY) || sizeof($CAL_ARRAY) < 1 || $CAL_ID=="")
   		return;
   foreach($CAL_ARRAY  as $key => $value)
   {
       $SQL.=$key."='".$value."',";
       $REMIND_USER.=$USER_ID.",".$TAKER.",".$OWNER;
   }
   $REMIND_USER=td_trim($REMIND_USER);
   $CAL_KEY_STR=td_trim($SQL);
   $CAL_KEY_VALUE=td_trim($CAL_KEY_VALUE);
   $query="UPDATE CALENDAR set ".$CAL_KEY_STR." where CAL_ID='$CAL_ID'";
   $cursor=exequery(TD::conn(),$query);
    if($cursor)
    {
        $STATUS = "success";
    }
    else
    {
        $STATUS = "error";
    }
    
    $a_return = array();
    $a_return['cal_id'] = $CAL_ID;
    $a_return['status'] = $STATUS;
    
    return $a_return;
}

//获取已完成的工作事务日程数量
function GetCalendarNum($USER_ID_STR="",$BEGIN_TIME="",$END_TIME="")
{
   $USER_ID_STR=td_trim($USER_ID_STR);
   $WHERE="";
   $ARRARY=array();
   if($USER_ID_STR!="") // 为空的时候是所有人
   {
   	  $WHERE.=" and find_in_set(USER_ID,'$USER_ID_STR') ";
   }
   if($BEGIN_TIME!="")
   {
   	 $BEGIN_TIME=strtotime($BEGIN_TIME);
   	 $WHERE.=" and CAL_TIME >= '$BEGIN_TIME' ";
   }
   if($END_TIME!="")
   {
   	 $END_TIME=strtotime($END_TIME);
   	 $WHERE.=" and END_TIME <= '$END_TIME' ";
   }
   $query="select USER_ID,count(USER_ID) from CALENDAR where CAL_TYPE!='2' and USER_ID<>'' ".$WHERE." and OVER_STATUS='1' group by USER_ID";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
	 {
      $ARRARY[$ROW[0]]=$ROW[1];
	 }
	 return $ARRARY;
}
//获取已完成的工作任务数量
function GetTaskNum($USER_ID_STR="",$BEGIN_TIME="",$END_TIME="")
{
   $USER_ID_STR=td_trim($USER_ID_STR);
   $WHERE="";
   $ARRARY=array();
   if($USER_ID_STR!="") // 为空的时候是所有人
   {
   	  $WHERE.=" and find_in_set(USER_ID,'$USER_ID_STR') ";
   }
   if($BEGIN_TIME!="")
   {
   	 $WHERE.=" and BEGIN_DATE >= '$BEGIN_TIME' ";
   }
   if($END_TIME!="")
   {
   	 $WHERE.=" and END_DATE <= '$END_TIME' ";
   }
   $query="select USER_ID,count(USER_ID) from TASK where TASK_TYPE!='2' and USER_ID<>'' ".$WHERE." and TASK_STATUS='3' group by USER_ID";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
	 {
      $ARRARY[$ROW[0]]=$ROW[1];
	 }
	 return $ARRARY;
}
//同步删除任务中心任务、日程
function delete_taskcenter($CATAGORY,$SOURCEID,$IS_STR="")
{
		if($IS_STR==1)
	{
		$SOURCEID=td_trim($SOURCEID);
		$query="delete from TASKCENTER where CATAGORY='$CATAGORY' and SOURCEID in ($SOURCEID)";
   }
	else
	   $query="delete from TASKCENTER where CATAGORY='$CATAGORY' and SOURCEID = '$SOURCEID'";
	exequery(TD::conn(),$query);
}
//同步删除任务中心中的工作流数据  
function delete_taskcenter_workflow($RUN_ID,$PRCS_ID="",$FLOW_PRCS="",$IS_DEL="")
{
	if($IS_DEL==1)//删除流程时
	{
		$query="delete from TASKCENTER where END_TIME='$RUN_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and CATAGORY='WORKFLOW'";
	}
	else
	{	
		if($FLOW_PRCS!="")
			$query="delete from TASKCENTER where END_TIME='$RUN_ID' and TAKER='$FLOW_PRCS' and SOURCEID='$PRCS_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and CATAGORY='WORKFLOW'";
		else
		  $query="delete from TASKCENTER where END_TIME='$RUN_ID' and  SOURCEID='$PRCS_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and CATAGORY='WORKFLOW'";
	}
	exequery(TD::conn(),$query);
}
//同步更新任务中心中的工作流数据  
function update_taskcenter_workflow($RUN_ID,$PRCS_ID,$PRCS_FLAG,$TIME_OUT_FLAG,$FLOW_PRCS="")
{
	$PRCS_FLAG_STATUS=$PRCS_FLAG.",".$TIME_OUT_FLAG;//合
	if($FLOW_PRCS!="")
		$query="update TASKCENTER set STATUS='$PRCS_FLAG_STATUS' where END_TIME='$RUN_ID' and TAKER='$FLOW_PRCS' and SOURCEID='$PRCS_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and CATAGORY='WORKFLOW'";
   else
   	$query="update TASKCENTER set STATUS='$PRCS_FLAG_STATUS' where END_TIME='$RUN_ID' and SOURCEID='$PRCS_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and CATAGORY='WORKFLOW'";
	exequery(TD::conn(),$query);
}

if(!function_exists('htmlFilter'))
{   
function htmlFilter($s)
{
    return preg_replace(
        array(
            "/<(script.*?)>(.*?)<(\/script.*?)>/si",
            "/<(\/?script.*?)>/si",
            "/<(style.*?)>(.*?)<(\/style.*?)>/si",
            "/<(\/?style.*?)>/si",
            "/<(link.*?)>(.*?)<(\/link.*?)>/si",
            "/<(\/?link.*?)>/si", 
            "/<(\/?meta.*?)>/si",
            "/<(iframe.*?)>(.*?)<(\/iframe.*?)>/si",
            "#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i",
            '/\t/m',
        ), '', $s);
}
}

if(!function_exists('htmlFilter4Array'))
{  
function htmlFilter4Array($data)
{
    foreach($data as $k=>$v)
    {
        if(is_array($v))
        {
            $data[$k] = htmlFilter4Array($v);   
        }
        else
        {   
            
            $data[$k] = htmlFilter(iconv(MYOA_CHARSET, 'utf-8', $v));
        }
    }
    return $data;
}
}

if(!function_exists('retJson'))
{  
function retJson($data = array())
{
    ob_end_clean();
    header("Cache-Control: no-cache, must-revalidate" );
    header("Pragma: no-cache" );
    header("Content-type: application/json; charset=".MYOA_CHARSET);

    $data = htmlFilter4Array($data);
    echo json_encode($data);
    exit; 
}
}

//获取数据
function get_list_data($view,$begin_date,$end_date,$CONDITION_STR="",$IS_MAIN="")
{
	$begin_date_u=strtotime($begin_date." 00:00:00");
	$end_date_u=strtotime($end_date." 23:59:59");
	$CUR_TIME = date("Y-m-d H:i:s",time());
	$dataBack = array(); 
	
	if($IS_MAIN==1)
	{
		$QUERY_MASTER=TRUE;
	}
	else
	{
		$QUERY_MASTER=FALSE;
	}
	if($view=="agendaWeek") //周视图
	{
		//日程信息
		$query = "SELECT * from CALENDAR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER)) ".$CONDITION_STR." and (CAL_TIME>='$begin_date' and CAL_TIME<='$end_date' || END_TIME>='$begin_date' and END_TIME<='$end_date' || CAL_TIME<='$begin_date' and END_TIME>='$end_date') order by CAL_TIME";
		$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
		while($ROW=mysql_fetch_array($cursor))
		{
			$CAL_ID=$ROW["CAL_ID"];
			$CAL_TIME=$ROW["CAL_TIME"];
			$CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
			$END_TIME=$ROW["END_TIME"];
			$END_TIME=date("Y-m-d H:i:s",$END_TIME);
			$CAL_TYPE=$ROW["CAL_TYPE"];
			$CAL_LEVEL=$ROW["CAL_LEVEL"];
			$CONTENTS=$CONTENT=$ROW["CONTENT"];
			$MANAGER_ID=$ROW["MANAGER_ID"];
			$OVER_STATUS=$ROW["OVER_STATUS"];
			$OWNER=$ROW["OWNER"];
			$TAKER=$ROW["TAKER"];
			$CREATOR=$ROW["USER_ID"];
			$ALLDAY = $ROW["ALLDAY"];
			$FROM_MODULE = $ROW["FROM_MODULE"];
			//$CONTENT = csubstr(strip_tags($CONTENT),0,80);
			$URL = $ROW["URL"]; 
			//是否有编辑、删除权限
			if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($OWNER,$_SESSION["LOGIN_USER_ID"])) //有修改权限
			{
				$EDIT_FLAG=1;
				$DELE_FLAG=1;
				$EDITABLE = true;
			}
			else
			{
				$EDIT_FLAG=0;
				$DELE_FLAG=0;
				$EDITABLE = false;
			}

			//是否是跨天事件
			if(substr($CAL_TIME,0,10) != substr($END_TIME,0,10) || $ALLDAY==1)
	   		{
	   			$ALL_DAY=true;
	   		}
	   		else
	   		{
	   			$ALL_DAY=false;	
	   		}
	   		//类型信息调整为颜色信息
	   		if($CAL_LEVEL==0)
	   		{
	   		    $CAL_COLOR = "fc-event-color";
	   		}
			else if($CAL_LEVEL==1) //如果是重要紧急 红色
			{
				$CAL_COLOR="fc-event-color1";
			}
			else if($CAL_LEVEL==2) //如果是重要不紧急 黄色
			{
				$CAL_COLOR="fc-event-color2";
			}
			else if($CAL_LEVEL==3) //如果是不重要紧急 绿色
			{
				$CAL_COLOR="fc-event-color3";
			}
			else if($CAL_LEVEL==4) //如果是不重要紧急 灰色
			{
				$CAL_COLOR="fc-event-color4";
			}
			else if($CAL_LEVEL==5)
			{
				$CAL_COLOR="fc-event-color5";
			}
			else if($CAL_LEVEL==6)
			{
			    $CAL_COLOR = "fc-event-color6";
			}			
			//状态信息
			if($OVER_STATUS=="0")
			{
			    
				if(compare_time($CUR_TIME,$END_TIME)>0)
				{
					$STATUS_COLOR="#FF0000";
					$STATUS=_("已超时");
				}
				else if(compare_time($CUR_TIME,$CAL_TIME)<0)
				{
					$STATUS_COLOR="#0000FF";
					$STATUS=_("未开始");
				}
				else
				{
					$STATUS_COLOR="#0000FF";
					$STATUS=_("进行中");
				}
			}
			else
			{
				$STATUS_COLOR="#00AA00";
				$STATUS=_("已完成");
			}
			if($FROM_MODULE==1)     //外出登记
			{
			    $FROM_CLASS = "fc-event-from-out";
			    //$CONTENT = $CONTENT."<a href='$URL' target='_blank'>"._("查看详细")."</a>";
			}
			else if($FROM_MODULE==2)    //会议申请
			{
			    $FROM_CLASS = "fc-event-from-meeting";
			    //$CONTENT = $CONTENT." <a href='$URL' target='_blank'>"._("查看详细")."</a>";
			}
			else if($FROM_MODULE==3)     //工作计划
			{
			    $FROM_CLASS = "fc-event-from-work";
			}
			else if($FROM_MODULE==4)      //人力资源
			{
			    $FROM_CLASS = "fc-event-from-hr";
			}
			else
			{
			    $FROM_CLASS = "";
			}
		    $className = $CAL_COLOR." ".$FROM_CLASS;		
			$dataBack[] = array(
			"view"=> $view,
			"type"=>"calendar",
			"id" => $CAL_ID, //主ID
			"title"=> $CONTENT,
			"start"=> $CAL_TIME ,
			"end"=> $END_TIME,
			"allDay"=> $ALL_DAY,
			"urls" => $URL,
			"className"=> $className,
			"state"=> $STATUS,
			"originalTitle"	=> $CONTENTS,
			"overstatus" => $OVER_STATUS,
			"edit" => $EDIT_FLAG,
			"dele" => $DELE_FLAG,
			"editable" => $EDITABLE		
			);			
		}
		//日常事务，按日提醒
		$querys = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and BEGIN_TIME<='$end_date' and TYPE='2' order by BEGIN_TIME desc";
		$cursors= exequery(TD::conn(),$querys,$QUERY_MASTER);
		while($ROW=mysql_fetch_array($cursors))
		{
			$AFF_ID=$ROW["AFF_ID"];
			$BEGIN_TIME=$ROW["BEGIN_TIME"];
			$END_TIME=$ROW["END_TIME"];
			$REMIND_DATE=$ROW["REMIND_DATE"];
			$REMIND_TIME=$ROW["REMIND_TIME"];
			$CONTENTS=$CONTENT=$ROW["CONTENT"];
			$LAST_REMIND=$ROW["LAST_REMIND"];
			$TAKER=$ROW["TAKER"];
			$CREATOR=$ROW["USER_ID"];
			$MANAGER_ID=$ROW["MANAGER_ID"];
			$BEGIN_TIME_TIME = $ROW["BEGIN_TIME_TIME"];
			$END_TIME_TIME = $ROW["END_TIME_TIME"];
			//是否有编辑、删除权限
			if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1) //有修改权限
			{
				$EDIT_FLAG=1;
				$DELE_FLAG=1;
			}
			else
			{
				$EDIT_FLAG=0;
				$DELE_FLAG=0;
			}

			$CONTENT=csubstr(strip_tags($CONTENT),0,80);
			if($LAST_REMIND=="0000-00-00")
				$LAST_REMIND="";
			if($END_TIME=="0000-00-00")
				$END_TIME="";
		   	$AFF_TITLE=_("提醒时间：每日 ").substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
		   	if($END_TIME!="" && $END_TIME!=0)
		     	$END_TIME=date("Y-m-d H:i:s",$END_TIME);
		    $BEGIN_TIME = date("Y-m-d H:i:s",$BEGIN_TIME);
		   	$REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));
		   	$URL="<a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");'></a>";
		   	for($I=0;$I< 7;$I++)
		   	{//如果起始时间大于这个时间，安排才生效
		      	if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-d",$begin_date+$I*86400) && ($END_TIME=="" ||$END_TIME==0  || substr($END_TIME, 0, 10)>=date("Y-m-d",$begin_date+$I*86400)))
		      	{
		      	 	$AFF_BEGIN_DATE=date("Y-m-d",$begin_date+$I*86400)." ".$REMIND_TIME;
		      	 	//$AFF_BEGIN_DATE = date("Y-m-d",$begin_date+$I*86400)." ".$BEGIN_TIME_TIME;
		      	 	//$AFF_END_DATE = date("Y-m-d",$begin_date+$I*86400)." ".$END_TIME_TIME;
		      	 	$dataBack[] = array(
						"view"=> $view,
						"type"=>"affair",
						"id" => $AFF_ID, //主ID
						"title"=> $CONTENT,
						"start"=> $AFF_BEGIN_DATE ,
						"end"=> "",
						"allDay"=> false,
						"urls" => "",
						"color"=> " ",
						"editable"=> false,
						"state"=> " ",
						"originalTitle"	=> $CONTENTS,
						"edit" => $EDIT_FLAG,
						"dele" => $DELE_FLAG			
					);		
		      	}
		   	}
		}
			
		//周期性事务 按周提醒
		$query1 = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and BEGIN_TIME<='$end_date' and TYPE='3' and (END_TIME='' or END_TIME='0' or END_TIME>='$begin_date') order by BEGIN_TIME desc";
		$cursor1= exequery(TD::conn(),$query1,$QUERY_MASTER);
		while($ROW1=mysql_fetch_array($cursor1))
		{
			$AFF_ID=$ROW1["AFF_ID"];
			$BEGIN_TIME=$ROW1["BEGIN_TIME"];
			$END_TIME=$ROW1["END_TIME"];
			if($END_TIME!=0 && $END_TIME!="")
			$END_TIME=date("Y-m-d H:i:s",$END_TIME);
			$REMIND_DATE=$ROW1["REMIND_DATE"];
			$REMIND_TIME=$ROW1["REMIND_TIME"];
			$CONTENTS=$CONTENT=$ROW1["CONTENT"];
			$LAST_REMIND=$ROW1["LAST_REMIND"];
			$TAKER=$ROW1["TAKER"];
			$BEGIN_TIME_TIME = $ROW1["BEGIN_TIME_TIME"];
			$END_TIME_TIME = $ROW1["END_TIME_TIME"];
			$CREATOR = $ROW1["USER_ID"];
			$MANAGER_ID = $ROW1["MANAGER_ID"];
			//是否有编辑、删除权限
			if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1) //有修改权限
			{
				$EDIT_FLAG=1;
				$DELE_FLAG=1;
			}
			else
			{
				$EDIT_FLAG=0;
				$DELE_FLAG=0;
			}			
			$CONTENT=csubstr(strip_tags($CONTENT),0,80);
			if($LAST_REMIND=="0000-00-00")
				$LAST_REMIND="";
			if($REMIND_DATE==0)
				$REMIND_DATE_DESC=_("日");
			else if($REMIND_DATE==1)
				$REMIND_DATE_DESC=_("一");
			else if($REMIND_DATE==2)
				$REMIND_DATE_DESC=_("二");
			else if($REMIND_DATE==3)
				$REMIND_DATE_DESC=_("三");
			else if($REMIND_DATE==4)
				$REMIND_DATE_DESC=_("四");
			else if($REMIND_DATE==5)
				$REMIND_DATE_DESC=_("五");
			else if($REMIND_DATE==6)
				$REMIND_DATE_DESC=_("六");
			$BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
			$END_TIME=date("Y-m-d H:i:s",$END_TIME);	  
			$AFF_TITLE=_("提醒时间：每周").$REMIND_DATE_DESC." ".substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
			$URL="<a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");'></a>";
			$REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));
			if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-d",$begin_date+$REMIND_DATE*86400))
			{
				 $REMIND_DATE==0 ? 6 : $REMIND_DATE-1;
				 $AFF_BEGIN_DATE=date("Y-m-d",$begin_date+$REMIND_DATE*86400)." ".$REMIND_TIME;
				 //$AFF_BEGIN_DATE = date("Y-m-d",$begin_date+$REMIND_DATE*86400)." ".$BEGIN_TIME_TIME;
				 //$AFF_END_DATE = date("Y-m-d",$begin_date+$REMIND_DATE*86400)." ".$END_TIME_TIME;
		      	 $dataBack[] = array(
					"view"=> $view,
					"type"=>"affair",
					"id" => $AFF_ID, //主ID
					"title"=> $CONTENT,
					"start"=> $AFF_BEGIN_DATE ,
					"end"=> "",
					"allDay"=> false,
					"urls" => "",
					"color"=> " ",
					"editable"=> false,
					"state"=> " ",
					"originalTitle"	=> $CONTENTS,
					"edit" => $EDIT_FLAG,
					"dele" => $DELE_FLAG			
					);		
			}
			
		}
		//日常事务，按月提醒
		$query2 = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and TYPE='4' and BEGIN_TIME<='$end_date' order by BEGIN_TIME desc";
		$cursor2= exequery(TD::conn(),$query2,$QUERY_MASTER);
		while($ROW2=mysql_fetch_array($cursor2))
		{
			$AFF_ID=$ROW2["AFF_ID"];
			$BEGIN_TIME=$ROW2["BEGIN_TIME"];
			$END_TIME=$ROW2["END_TIME"];
			$BEGIN_TIME_TIME = $ROW2["BEGIN_TIME_TIME"];
			$END_TIME_TIME = $ROW2["END_TIME_TIME"];
			$BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
			
	   		if($END_TIME!=0 && $END_TIME!="")
	      		$END_TIME=date("Y-m-d H:i:s",$END_TIME);
   			$REMIND_DATE=$ROW2["REMIND_DATE"];
   			$REMIND_TIME=$ROW2["REMIND_TIME"];
  			$CONTENTS=$CONTENT=$ROW2["CONTENT"];
   			$LAST_REMIND=$ROW2["LAST_REMIND"];
   			$TAKER=$ROW2["TAKER"];
   			$CREATOR=$ROW2["USER_ID"];
   			$MANAGER_ID=$ROW2["MANAGER_ID"];
   			//是否有编辑、删除权限
			if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1) //有修改权限
			{
				$EDIT_FLAG=1;
				$DELE_FLAG=1;
			}
			else
			{
				$EDIT_FLAG=0;
				$DELE_FLAG=0;
			} 

   			if(strtotime(date("Y-m-d",$begin_date)) > strtotime(date("Y-m-",$begin_date).$REMIND_DATE) || strtotime(date("Y-m-d",$end_date)) < strtotime(date("Y-m-",$begin_date).$REMIND_DATE))
      			continue;
  			$CONTENTS=strip_tags($CONTENT);
   			$CONTENT=csubstr(strip_tags($CONTENT),0,80);
   			if($LAST_REMIND=="0000-00-00")
      			$LAST_REMIND="";
   			$AFF_TITLE=_("提醒时间：每月").$REMIND_DATE._("日 ").substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
  		 	//$CONTENT="<div id=\"div_".$AFF_ID."\"  style='width:90%;background:".$AFF_DIV_COLOR."'>".substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1);' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a></div>";
   			//$CONTENT=substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a><br>";
   			$REMIND_WEEK=date("w",strtotime(date("Y-m-",$begin_date).$REMIND_DATE));
   			$REMIND_WEEK= $REMIND_WEEK==0 ? 6: $REMIND_WEEK-1;
   			$REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));
   			if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-",$begin_date).$REMIND_DATE)
   			{
   				
   				$AFF_BEGIN_DATE=date("Y-m-",$begin_date).$REMIND_DATE." ".$REMIND_TIME;
		      	$dataBack[] = array(
					"view"=> $view,
					"type"=>"affair",
					"id" => $AFF_ID, //主ID
					"title"=> $CONTENT,
					"start"=> $AFF_BEGIN_DATE ,
					"end"=> "",
					"allDay"=> false,
					"urls" => "",
					"color"=> " ",
					"editable"=> false,
					"state"=> " ",
					"originalTitle"	=> $CONTENTS,
					"edit" => $DELE_FLAG,
					"dele" => $DELE_FLAG			
					);		
      		}
		}

		//日常事务，按年提醒
		$query3 = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and BEGIN_TIME<='$end_date' and TYPE='5' and (END_TIME='' or END_TIME='0' or END_TIME>='$begin_date_u') order by BEGIN_TIME desc";
		$cursor3= exequery(TD::conn(),$query3,$QUERY_MASTER);
		while($ROW3=mysql_fetch_array($cursor3))
		{
   			$AFF_ID=$ROW3["AFF_ID"];
   			$BEGIN_TIME=$ROW3["BEGIN_TIME"];
   			$BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
   			$END_TIME=$ROW3["END_TIME"];
   			if($END_TIME!=0 && $END_TIME!="")
      			$END_TIME=date("Y-m-d H:i:s",$END_TIME);
   			$REMIND_DATE=$ROW3["REMIND_DATE"];
   			$REMIND_TIME=$ROW3["REMIND_TIME"];
   			$CONTENTS=$CONTENT=$ROW3["CONTENT"];
   			$TAKER=$ROW3["TAKER"];  
   			$LAST_REMIND=$ROW3["LAST_REMIND"];
   			$BEGIN_TIME_TIME = $ROW3["BEGIN_TIME_TIME"];
   			$END_TIME_TIME = $ROW3["END_TIME_TIME"];
   			$CREATOR = $ROW3["USER_ID"];
   			$MANAGER_ID = $ROW3["MANAGER_ID"];
   			//是否有编辑、删除权限
			if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1) //有修改权限
			{
				$EDIT_FLAG=1;
				$DELE_FLAG=1;
			}
			else
			{
				$EDIT_FLAG=0;
				$DELE_FLAG=0;
			}
   			if(strtotime(date("Y-n-j",$begin_date)) >= strtotime(date("Y-",$begin_date).$REMIND_DATE) || strtotime(date("Y-n-j",$end_date)) <= strtotime(date("Y-",$begin_date).$REMIND_DATE))
      			continue;
   			$CONTENT=csubstr(strip_tags($CONTENT),0,80);
   			if($LAST_REMIND=="0000-00-00")
      			$LAST_REMIND="";
   			$AFF_TITLE=_("提醒时间：每年").str_replace("-",_("月"),$REMIND_DATE)._("日 ").substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
	 		//$CONTENT=substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a><br>";
   			$REMIND_WEEK=date("w",strtotime(date("Y-",$begin_date).$REMIND_DATE));
   			$REMIND_WEEK= $REMIND_WEEK==0 ? 6: $REMIND_WEEK-1;
   			$REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));
   			if(substr($BEGIN_TIME, 0, 10)<=date("Y-",$begin_date).$REMIND_DATE)
   			{
   				$AFF_BEGIN_DATE=date("Y-",$begin_date).$REMIND_DATE." ".$REMIND_TIME;
   				//$AFF_BEGIN_DATE=date("Y-",$begin_date).$REMIND_DATE." ".$BEGIN_TIME_TIME;
   				//$AFF_END_DATE=date("Y-",$begin_date).$REMIND_DATE." ".$END_TIME_TIME;
		      	$dataBack[] = array(
					"view"=> $view,
					"type"=>"affair",
					"id" => $AFF_ID, //主ID
					"title"=> $CONTENT,
					"start"=> $AFF_BEGIN_DATE ,
					"end"=> "",
					"allDay"=> false,
					"urls" => "",
					"color"=> " ",
					"editable"=> false,
					"state"=> " ",
					"originalTitle"	=> $CONTENTS,
					"edit" => $EDIT_FLAG,
					"dele" => $DELE_FLAG	
					);	
      		}
		}
		//日常事务，按工作日提醒
		$query = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and BEGIN_TIME<='$end_date' and TYPE='6' and (END_TIME='' or END_TIME='0' or END_TIME>='$begin_date') order by BEGIN_TIME desc";
		$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
		while($ROW=mysql_fetch_array($cursor))
		{
			$AFF_ID=$ROW["AFF_ID"];
			$BEGIN_TIME=$ROW["BEGIN_TIME"];
			$END_TIME=$ROW["END_TIME"];
			$REMIND_DATE=$ROW["REMIND_DATE"];
			$REMIND_TIME=$ROW["REMIND_TIME"];
			$CONTENTS=$CONTENT=$ROW["CONTENT"];
			$LAST_REMIND=$ROW["LAST_REMIND"];
			$CONTENT=csubstr(strip_tags($CONTENT),0,80);
			$TAKER=$ROW["TAKER"];
			$CREATOR = $ROW["USER_ID"];
   			$MANAGER_ID = $ROW["MANAGER_ID"];
   			//是否有编辑、删除权限
			if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1) //有修改权限
			{
				$EDIT_FLAG=1;
				$DELE_FLAG=1;
			}
			else
			{
				$EDIT_FLAG=0;
				$DELE_FLAG=0;
			} 
   			if($LAST_REMIND=="0000-00-00")
      			$LAST_REMIND="";
   			if($END_TIME=="0000-00-00")
      			$END_TIME="";
   			$BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);	  
   			$AFF_TITLE=_("提醒时间：每日 ").substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
   			//$CONTENT=substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a><br>";
   			if($END_TIME!="" && $END_TIME!=0)
     			$END_TIME=date("Y-m-d H:i:s",$END_TIME);
   			$REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));
			for($I=0;$I< 7;$I++)
			{//如果起始时间大于这个时间，安排才生效
				if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-d",$begin_date+$I*86400) && date("w",$begin_date+$I*86400)!=0 && date("w",$begin_date+$I*86400)!=6 && ($END_TIME=="" ||$END_TIME==0  || substr($END_TIME, 0, 10)>=date("Y-m-d",$begin_date+$I*86400)))
				{
					$AFF_BEGIN_DATE=date("Y-m-d",$begin_date+$I*86400)." ".$REMIND_TIME;
		      		$dataBack[] = array(
					"view"=> $view,
					"type"=>"affair",
					"id" => $AFF_ID, //主ID
					"title"=> $CONTENT,
					"start"=> $AFF_BEGIN_DATE ,
					"end"=> "",
					"allDay"=> false,
					"urls" => "",
					"color"=> " ",
					"editable"=> false,
					"state"=> " ",
					"originalTitle"	=> $CONTENTS,
					"edit" => $EDIT_FLAG,
					"dele" => $DELE_FLAG			
					);	
				}
			}
		}       		
    }
    if($view=="agendaDay") //日视图
    {
    	//日程信息
    	$begin_date1 = date("Y-m-d",$begin_date);
		$query = "SELECT * from CALENDAR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER))".$CONDITION_STR." and to_days(from_unixtime(CAL_TIME))<=to_days('$begin_date1') and to_days(from_unixtime(END_TIME))>=to_days('$begin_date1') order by CAL_TIME";
 		$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
    	while($ROW=mysql_fetch_array($cursor))
		{
			$CAL_ID=$ROW["CAL_ID"];
			$CAL_TIME=$ROW["CAL_TIME"];
			$CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
			$END_TIME=$ROW["END_TIME"];
			$END_TIME=date("Y-m-d H:i:s",$END_TIME);
			$CAL_TYPE=$ROW["CAL_TYPE"];
			$CAL_LEVEL=$ROW["CAL_LEVEL"];
			$CONTENT=$CONTENTS=$ROW["CONTENT"];
			$MANAGER_ID=$ROW["MANAGER_ID"];
			$OVER_STATUS=$ROW["OVER_STATUS"];
			$OWNER=$ROW["OWNER"];
			$TAKER=$ROW["TAKER"];
			$CREATOR=$ROW["USER_ID"];
			$ALLDAY = $ROW["ALLDAY"];
			$FROM_MODULE = $ROW["FROM_MODULE"];
			$URL = $ROW["URL"];
			//$CONTENT=csubstr(strip_tags($CONTENT),0,80);
			//是否有编辑权限
			if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($OWNER,$_SESSION["LOGIN_USER_ID"])) //有修改权限
			{
				$EDIT_FLAG=1;
				$DELE_FLAG=1;
				$EDITABLE = true;
			}
			else
			{
				$EDIT_FLAG=0;
				$DELE_FLAG=0;
				$EDITABLE = false;
			}
			//是否是跨天事件
			if(substr($CAL_TIME,0,10) != substr($END_TIME,0,10) || $ALLDAY==1)
	   		{
	   			$ALL_DAY=true;
	   		}
	   		else
	   		{
	   			$ALL_DAY=false;	
	   		}
	   		//类型信息调整为颜色信息
			if($CAL_LEVEL==0)
	   		{
	   		    $CAL_COLOR = "fc-event-color";
	   		}
			else if($CAL_LEVEL==1) //如果是重要紧急 红色
			{
				$CAL_COLOR="fc-event-color1";
			}
			else if($CAL_LEVEL==2) //如果是重要不紧急 黄色
			{
				$CAL_COLOR="fc-event-color2";
			}
			else if($CAL_LEVEL==3) //如果是不重要紧急 绿色
			{
				$CAL_COLOR="fc-event-color3";
			}
			else if($CAL_LEVEL==4) //如果是不重要紧急 灰色
			{
				$CAL_COLOR="fc-event-color4";
			}
			else if($CAL_LEVEL==5)
			{
				$CAL_COLOR="fc-event-color5";
			}
			else if($CAL_LEVEL==6)
			{
			    $CAL_COLOR = "fc-event-color6";
			}			
			//状态信息
			if($OVER_STATUS=="0")
			{
				if(compare_time($CUR_TIME,$END_TIME)>0)
				{
					$STATUS_COLOR="#FF0000";
					$STATUS=_("已超时");
				}
				else if(compare_time($CUR_TIME,$CAL_TIME)<0)
				{
					$STATUS_COLOR="#0000FF";
					$STATUS=_("未开始");
				}
				else
				{
					$STATUS_COLOR="#0000FF";
					$STATUS=_("进行中");
				}
			}
			else
			{
				$STATUS_COLOR="#00AA00";
				$STATUS=_("已完成");
			}
			if($FROM_MODULE==1)     //外出登记
			{
			    $FROM_CLASS = "fc-event-from-out";
			    //$CONTENT = $CONTENT."<a href='$URL' target='_blank'>"._("查看详细")."</a>";
			}
			else if($FROM_MODULE==2)    //会议申请
			{
			    $FROM_CLASS = "fc-event-from-meeting";
			    //$CONTENT = $CONTENT." <a href='$URL' target='_blank'>"._("查看详细")."</a>";
			}
			else if($FROM_MODULE==3)     //工作计划
			{
			    $FROM_CLASS = "fc-event-from-work";
			}
			else if($FROM_MODULE==4)      //人力资源
			{
			    $FROM_CLASS = "fc-event-from-hr";
			}
			else
			{
			    $FROM_CLASS = "";
			}
		    $className = $CAL_COLOR." ".$FROM_CLASS;	
			$dataBack[] = array(
			"view"=> $view,
			"type"=>"calendar",
			"id" => $CAL_ID, //主ID
			"title"=> $CONTENT,
			"start"=> $CAL_TIME ,
			"end"=> $END_TIME,
			"allDay"=> $ALL_DAY,
			"urls" => $URL,
			"className"=> $className,
			"state"=> $STATUS,
			"originalTitle"=>$CONTENTS,
			"overstatus" => $OVER_STATUS,
			"edit" => $DELE_FLAG,
			"dele" => $DELE_FLAG,
			"editable" => $EDITABLE		
			);			
		}
		//周期性事务
    	$query = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and BEGIN_TIME<='$begin_date' and (END_TIME='' or END_TIME='0' or END_TIME>='$begin_date') and (TYPE='2' or TYPE='3' and REMIND_DATE='".date("w",$begin_date)."' or TYPE='6' or  TYPE='4' and REMIND_DATE='".date("j",$begin_date)."' or TYPE='5' and REMIND_DATE='".date("n-j",$begin_date)."') order by BEGIN_TIME desc";
		$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
		while($ROW=mysql_fetch_array($cursor))
		{
   			$AFF_ID=$ROW["AFF_ID"];
   			$BEGIN_TIME=$ROW["BEGIN_TIME"];
   			$BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
   			$END_TIME=$ROW["END_TIME"];
   			if($END_TIME!=0)
      			$END_TIME=date("Y-m-d H:i:s",$END_TIME);
   			$REMIND_DATE=$ROW["REMIND_DATE"];
   			$REMIND_TIME=$ROW["REMIND_TIME"];
   			$CONTENTS=$CONTENT=$ROW["CONTENT"];
   			$TYPE=$ROW["TYPE"];
   			$LAST_REMIND=$ROW["LAST_REMIND"];
   			$CONTENT=csubstr(strip_tags($CONTENT),0,100);
   			if($LAST_REMIND=="0000-00-00")
      			$LAST_REMIND="";
      		$CREATOR = $ROW["USER_ID"];
   			$MANAGER_ID = $ROW["MANAGER_ID"];
   			//是否有编辑、删除权限
			if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1) //有修改权限
			{
				$EDIT_FLAG=1;
				$DELE_FLAG=1;
			}
			else
			{
				$EDIT_FLAG=0;
				$DELE_FLAG=0;
			}	
   			if($TYPE==6 && (date("w",$DATE)==0 || date("w",$DATE)==6))
    			continue;
   			switch($TYPE)
   			{
     			case "2":
         			$TYPE_DESC=_("每日");
         			break;
     			case "3":
         			$TYPE_DESC="";
	         		if($REMIND_DATE=="1")
	            		$REMIND_DATE=_("每周一");
	        		elseif($REMIND_DATE=="2")
	            		$REMIND_DATE=_("每周二");
	         		elseif($REMIND_DATE=="3")
	            		$REMIND_DATE=_("每周三");
	         		elseif($REMIND_DATE=="4")
	            		$REMIND_DATE=_("每周四");
	         		elseif($REMIND_DATE=="5")
	            		$REMIND_DATE=_("每周五");
	         		elseif($REMIND_DATE=="6")
	            		$REMIND_DATE=_("每周六");
	        	 	elseif($REMIND_DATE=="0")
	            		$REMIND_DATE=_("每周日");
         			break;
    		    case "4":
         			$TYPE_DESC=_("每月");
         			$REMIND_DATE.=_("日");
         			break;
     			case "5":
					$TYPE_DESC=_("每年");
					$REMIND_DATE=str_replace("-",_("月"),$REMIND_DATE)._("日");
					break;
				case "6":
					$TYPE_DESC=_("工作日");
					break;
   			}
			$AFF_TITLE=_("提醒时间：").$TYPE_DESC.$REMIND_DATE." ".substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
			//$CONTENT=substr($REMIND_TIME,0,-3)." <a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a><br>";
   			$REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));
   			if($TYPE!=6)
   			{
		      	if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-d",$begin_date))
		      	{	
		      		//$AFF_BEGIN_DATE=$begin_date.$REMIND_HOUR;
		      		$AFF_BEGIN_DATE=date("Y-m-d",$begin_date).$REMIND_TIME;
      				$dataBack[] = array(
					"view"=> $view,
					"type"=>"affair",
					"id" => $AFF_ID, //主ID
					"title"=> $CONTENT,
					"start"=> $AFF_BEGIN_DATE ,
					"end"=> "",
					"allDay"=> false,
					"urls" => "",
					"color"=> " ",
					"editable"=> false,
					"state"=> " ",
					"originalTitle"	=> $CONTENTS,
					"edit" => $EDIT_FLAG,
					"dele" => $DELE_FLAG			
					);	    		
      			}
   			}
   			else
   			{
      			if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-d",$DATE) && date("w",$DATE)!=0 && date("w",$DATE)!=1 )
      			{
         			$AFF_BEGIN_DATE=$begin_date.$REMIND_TIME;
      				$dataBack[] = array(
					"view"=> $view,
					"type"=>"affair",
					"id" => $AFF_ID, //主ID
					"title"=> $CONTENT,
					"start"=> $AFF_BEGIN_DATE ,
					"end"=> "",
					"allDay"=> false,
					"urls" => "",
					"color"=> " ",
					"editable"=> false,
					"state"=> " ",
					"originalTitle"	=> $CONTENTS,
					"edit" => $EDIT_FLAG,
					"dele" => $DELE_FLAG			
					);	    		  	
        		}
   			}  
		}
    }
    if($view=="month")//月视图
    {
        $YEAR = date("Y",$end_date);
        $MONTH = date("m",$end_date);
    	//日程
    	$query = "SELECT * from CALENDAR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER))".$CONDITION_STR." and (CAL_TIME>='$begin_date' and CAL_TIME<='$end_date' || END_TIME>='$begin_date' and END_TIME<='$end_date' || CAL_TIME<='$begin_date' and END_TIME>='$end_date') order by CAL_TIME";
		$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
		while($ROW=mysql_fetch_array($cursor))
		{
		    $CAL_ID=$ROW["CAL_ID"];
			$CAL_TIME=$ROW["CAL_TIME"];
			$CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
			$END_TIME=$ROW["END_TIME"];
			$END_TIME=date("Y-m-d H:i:s",$END_TIME);
			$CAL_TYPE=$ROW["CAL_TYPE"];
			$CAL_LEVEL=$ROW["CAL_LEVEL"];
			$CONTENT=$CONTENTS=$ROW["CONTENT"];
			$MANAGER_ID=$ROW["MANAGER_ID"];
			$OVER_STATUS=$ROW["OVER_STATUS"];
			$OWNER=$ROW["OWNER"];
			$TAKER=$ROW["TAKER"];
			$CREATOR=$ROW["USER_ID"];
			$ALLDAY = $ROW["ALLDAY"];
			$FROM_MODULE = $ROW["FROM_MODULE"];
			$URL = $ROW["URL"];
   			//是否有编辑、删除权限,无编辑、删除权限时也没有拖拽功能
			if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($OWNER,$_SESSION["LOGIN_USER_ID"])) //有修改权限
			{
				$EDIT_FLAG=1;
				$DELE_FLAG=1;
				$EDITABLE = true;
			}
			else
			{
				$EDIT_FLAG=0;
				$DELE_FLAG=0;
				$EDITABLE = false;
			}
		
			//$CONTENT = csubstr(strip_tags($CONTENT),0,80);
		
			
			//类型信息调整为颜色信息
			if($CAL_LEVEL==0)
	   		{
	   		    $CAL_COLOR = "fc-event-color";
	   		}
			else if($CAL_LEVEL==1) //如果是重要紧急 红色
			{
				$CAL_COLOR="fc-event-color1";
			}
			else if($CAL_LEVEL==2) //如果是重要不紧急 黄色
			{
				$CAL_COLOR="fc-event-color2";
			}
			else if($CAL_LEVEL==3) //如果是不重要紧急 绿色
			{
				$CAL_COLOR="fc-event-color3";
			}
			else if($CAL_LEVEL==4) //如果是不重要紧急 灰色
			{
				$CAL_COLOR="fc-event-color4";
			}
			else if($CAL_LEVEL==5)
			{
				$CAL_COLOR="fc-event-color5";
			}
			else if($CAL_LEVEL==6)
			{
			    $CAL_COLOR = "fc-event-color6";
			}			
			
			if($OVER_STATUS=="0")
			{
			    if(compare_time($CUR_TIME,$END_TIME) > 0)
			    {
			        $STATUS_COLOR = "#FF0000";
			        $STATUS = _("已超时");
			    }
			    else if(compare_time($CUR_TIME,$CAL_TIME) < 0)
			    {
			        $STATUS_COLOR = "#0000FF";
			        $STATUS = _("未开始");
			    }
			    else
			    {
			        $STATUS_COLOR = "#0000FF";
			        $STATUS = _("进行中");
			    }
			}
			else
			{
			    $STATUS_COLOR = "#00AA00";
			    $STATUS = _("已完成");
			}
			//是否是跨天事务
			if(substr($CAL_TIME,0,10) != substr($END_TIME,0,10) || $ALLDAY==1)
			{
			    $ALL_DAY = true;
			}
			else
			{
			    $ALL_DAY = false;
			}
			if($FROM_MODULE==1)     //外出登记
			{
			    $FROM_CLASS = "fc-event-from-out";
			    //$CONTENT = $CONTENT."<a href='$URL' target='_blank'>"._("查看详细")."</a>";
			}
			else if($FROM_MODULE==2)    //会议申请
			{
			    $FROM_CLASS = "fc-event-from-meeting";
			    //$CONTENT = $CONTENT." <a href='$URL' target='_blank'>"._("查看详细")."</a>";
			}
			else if($FROM_MODULE==3)     //工作计划
			{
			    $FROM_CLASS = "fc-event-from-work";
			}
			else if($FROM_MODULE==4)      //人力资源
			{
			    $FROM_CLASS = "fc-event-from-hr";
			}
			else
			{
			    $FROM_CLASS = "";
			}			
		    $className = $CAL_COLOR." ".$FROM_CLASS;
			$dataBack[] = array(
			"view"=> $view,
			"type"=>"calendar",
			"id" => $CAL_ID, //主ID
			"title"=> $CONTENT,
			"start"=> $CAL_TIME ,
			"end"=> $END_TIME,
			"allDay"=> $ALL_DAY,
			"urls" => $URL,
			"className"=> $className,
			"state"=> $STATUS,
			"originalTitle"=> $CONTENTS,
			"overstatus" => $OVER_STATUS,
			"edit" => $EDIT_FLAG,
			"dele" => $DELE_FLAG,
			"editable" => $EDITABLE		
			);			    
		}		
		
		//周期性事务
		$days = round(($end_date - $begin_date)/3600/24);
		$query = "select * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) order by AFF_ID desc";
		//echo $query;
		$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
		while($ROW=mysql_fetch_array($cursor))
		{
		    $AFF_ID = $ROW["AFF_ID"];
		    $TYPE = $ROW["TYPE"];
		    $REMIND_DATE = $ROW["REMIND_DATE"];
	        $LAST_REMIND = $ROW["LAST_REMIND"];
	        $BEGIN_TIME = $ROW["BEGIN_TIME"];
	        $END_TIME = $ROW["END_TIME"];
	        $REMIND_TIME = $ROW["REMIND_TIME"];
	        $CONTENTS=$CONTENT = $ROW["CONTENT"];
	        $CREATOR = $ROW["USER_ID"];
   			$MANAGER_ID = $ROW["MANAGER_ID"];
   			//是否有编辑、删除权限
			if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1) //有修改权限
			{
				$EDIT_FLAG=1;
				$DELE_FLAG=1;
			}
			else
			{
				$EDIT_FLAG=0;
				$DELE_FLAG=0;
			}
	        $CONTENT=csubstr(strip_tags($CONTENT),0,100);
	        $day = round(($END_TIME - $BEGIN_TIME)/3600/24);
	        
            for($I=0;$I<=$days;$I++)
            {
                $AFF_BEGIN_DATE = date("Y-m-d",$begin_date+$I*86400)." ".$REMIND_TIME;
                //$BEGIN_TIME_U = $BEGIN_TIME+$I*86400;
                $BEGIN_TIME_U = $begin_date+$I*86400;
                $BEGIN_TIME_N = date("Y-m-d",$BEGIN_TIME_U);
                $BEGIN_TIME_O = date("Y-m-d",$BEGIN_TIME);
                if(compare_date($BEGIN_TIME_N,$BEGIN_TIME_O)<0)
                {
                    continue;
                }
                if($END_TIME!= "" && $END_TIME!=0)
                {
	                $END_TIME_U = date("Y-m-d",$END_TIME);
	                $END_TIME_U1= date("Y-m-d",$begin_date+$I*86400);
	                if(compare_date($END_TIME_U,$END_TIME_U1)<0)
	                {
	                    continue;
	                }
                }
                if($TYPE=="3")
                {	                    
                    if(date("w",$BEGIN_TIME_U)!=$REMIND_DATE)
                        continue;
                }
                if($TYPE=="4")
                {
                    //$REMIND_DATE = substr($REMIND_DATE,0,1);
                    if(date("j",$BEGIN_TIME_U)!=$REMIND_DATE)
                        continue;    
                }
                if($TYPE=="5")
                {
                    if(date("n-j",$BEGIN_TIME_U)!=$REMIND_DATE)
                        continue;     
                }
                if($TYPE=="6")
                {
                    if(date("w",$BEGIN_TIME_U)=="6" || date("w",$BEGIN_TIME_U)=="0")
                        continue;                     
                }
                $dataBack[] = array(
	                    "view"=> $view,
    					"type"=>"affair",
    					"id" => $AFF_ID, //主ID
    					"title"=> $CONTENT,
    					"start"=> $AFF_BEGIN_DATE ,
    					"end"=> "",
    					"allDay"=> false,
    					"urls" => "",
    					"color"=> " ",
						"editable"=> false,
    					"state"=> " ",
    					"originalTitle"	=> $CONTENTS,
    					"edit" => $EDIT_FLAG,
    					"dele" => $DELE_FLAG	
    					);
            }        
		}
		//任务
		$tquery = "SELECT * from TASK where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by TASK_ID desc";
		$tcursor = exequery(TD::conn(),$tquery,$QUERY_MASTER);
		while($TROW=mysql_fetch_array($tcursor))
		{
		    $TASK_ID=$TROW["TASK_ID"];      
            $BEGIN_DATE =$TROW["BEGIN_DATE"];
            $END_DATE=$TROW["END_DATE"];
            $MANAGER_ID=$TROW["MANAGER_ID"]; 
            $SUBJECT=$TROW["SUBJECT"];
            $COLOR = $TROW["COLOR"];
            if($COLOR==0)
            {
                $TASK_COLOR = "fc-event-color";
            }
            else if($COLOR==1)
            {
                $TASK_COLOR = "fc-event-color1";
            }
            else if($COLOR==2)
            {
                $TASK_COLOR = "fc-event-color2";
            }
            else if($COLOR==3)
            {
                $TASK_COLOR = "fc-event-color3";
            }
            else if($COLOR==4)
            {
                $TASK_COLOR = "fc-event-color4";
            }
            else if($COLOR==5)
            {
                $TASK_COLOR = "fc-event-color5";                
            }
            else if($COLOR==6)
            {
                $TASK_COLOR = "fc-event-color6";
            }
            $SUBJECT=_("任务:").csubstr(strip_tags($SUBJECT),0,38);
            for($I=0;$I<=$days;$I++)
            {
                $BEGIN_TIME_U = $begin_date+$I*86400;
                $BEGIN_TIME_N = date("Y-m-d",$BEGIN_TIME_U);
                if(compare_date($BEGIN_TIME_N,$BEGIN_DATE) < 0)
                {
                    continue;
                }
                if($END_DATE!= "" && $END_DATE!="0000-00-00")
                {

                    $END_TIME_U1= date("Y-m-d",$begin_date+$I*86400);
                    if(compare_date($END_DATE,$END_TIME_U1)<0)
                    {
                        continue;
                    }
                }
                
                $TASK_BEGIN_DATE = date("Y-m-d",$begin_date+$I*86400);
                $dataBack[] = array(
					"view"=> $view,
					"type"=>"task",
					"id" => $TASK_ID, //主ID
					"title"=> $SUBJECT,
					"start"=> $TASK_BEGIN_DATE,
					"end"=> "",
					"allDay"=> false,
					"urls" => "",
					"color"=> " ",
					"editable"=> false,
					"state"=> " "	,
					"className" => $TASK_COLOR,
					"originalTitle"	=> $SUBJECT,
					"edit" => 1,
					"dele" => 1		
					);	                 
            }
		}
    }
   return $dataBack; 
}
function get_aff_list_data($cal_array,$begin_date_u,$end_date_u)
{
	$CUR_TIME = date("Y-m-d H:i:s",time());
	$dataBack = array(); 
	
	$begin_date_a = $cal_array['BEGIN_TIME'];
	$end_date_a = $cal_array['END_TIME'];
	
	$s_show_date = '';
	
	if($cal_array['END_TIME'])
	{
    	if($begin_date_a <= $begin_date_u && $end_date_a >= $begin_date_u && $end_date_a <= $end_date_u)
    	{
    	    $days = round(($end_date_a - $begin_date_u)/3600/24);
    	    $s_show_date = $begin_date_u;
    	}
    	else if($begin_date_a <= $begin_date_u && $end_date_a >= $end_date_u)
    	{
    	    $days = round(($end_date_u - $begin_date_u)/3600/24);
    	    $s_show_date = $begin_date_u;
    	}
    	else if($begin_date_a >= $begin_date_u && $begin_date_a <= $end_date_u && $end_date_a <= $end_date_u)
    	{
    	    $days = round(($end_date_a - $begin_date_a)/3600/24);
    	    $s_show_date = $begin_date_a;
    	}
    	else if($begin_date_a >= $begin_date_u && $begin_date_a <= $end_date_u && $end_date_a >= $end_date_u)
    	{
    	    $days = round(($end_date_u - $begin_date_a)/3600/24);
    	    $s_show_date = $begin_date_a;
    	}
	}
	else
	{
	    if($begin_date_a >= $begin_date_u && $begin_date_a <= $end_date_u)
	    {
    	    $days = round(($end_date_u - $begin_date_a)/3600/24);
    	    $s_show_date = $begin_date_a;
	    }
    	else if($begin_date_a <= $begin_date_u)
    	{
    	    $days = round(($end_date_u - $begin_date_a)/3600/24);
    	    $s_show_date = $begin_date_a;
    	}
	}
    
    for($I=0; $I <= $days; $I++)
    {
        $TYPE = $cal_array['TYPE'];
        $REMIND_DATE = $cal_array['REMIND_DATE'];
        $s_el_time = $s_show_date+$I*86400;
        
        if($TYPE=="3")
        {
            if(date("w",$s_el_time) != $REMIND_DATE)
                continue;
        }
        if($TYPE=="4")
        {
            $REMIND_DATE = substr($REMIND_DATE,0,1);
            if(date("j",$s_el_time)!=$REMIND_DATE)
                continue;    
        }
        if($TYPE=="5")
        {
            if(date("n-j",$s_el_time)!=$REMIND_DATE)
                continue;     
        }
        if($TYPE=="6")
        {
            if(date("w",$s_el_time)=="6" || date("w",$s_el_time)=="0")
                continue;                     
        }
        
        $AFF_BEGIN_DATE = date("Y-m-d",$s_show_date+$I*86400)." ".$cal_array['REMIND_TIME'];
        $dataBack[] = array(
            //"view"=> $view,
            "type"=>"affair",
            "id" => $cal_array['CAL_ID'], //主ID
            "title"=> $cal_array['CONTENT'],
            "start"=> $AFF_BEGIN_DATE ,
            "end"=> "",
            "allDay"=> false,
            "color"=> " ",
            "editable"=> false,
            "state"=> " ",
            "originalTitle"	=> $cal_array['CONTENT'],
            "urls" => "",
            "edit" => 1,
            "dele" => 1
            	
        );
    }
    return $dataBack; 
}
//日程完成、未完成动作
function calendar_change_state($CAL_ID,$OVER_STATUS)
{
	if($CAL_ID=="")
	{
		$STATUS="error";	
	}
	$query = "UPDATE CALENDAR SET OVER_STATUS='$OVER_STATUS' WHERE  (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER)) and CAL_ID='$CAL_ID'";
	$cursor = exequery(TD::conn(),$query);
	if($OVER_STATUS==1)//如果标记为完成删除任务中心中的该条记录
	{
		delete_taskcenter('CALENDAR',$CAL_ID);
	}
    if($cursor)
    {
    	$STATUS="success";	
    }
    else
    {
    	$STATUS="error";		
    }
	return $STATUS;
}
//删除日程
function delete_calendar($CAL_ID)
{
	if($CAL_ID != "")
	{
   		$query="select * from CALENDAR where CAL_ID in ($CAL_ID)";
   		$cursor= exequery(TD::conn(),$query,true);
   		if($ROW=mysql_fetch_array($cursor))
   		{
			$CAL_TIME=$ROW["CAL_TIME"];
			$CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
			$CONTENT=$ROW["CONTENT"];
			$SMS_CONTENT=_("请查看日程安排！")."\n"._("内容：").csubstr($CONTENT,0,100);
			$SMS_CONTENT2=_("OA日程安排:").$CONTENT;   
			$query="delete from SMS2 where SEND_TIME<='$CAL_TIME' and CONTENT='$SMS_CONTENT2' and SEND_FLAG='0'";
			exequery(TD::conn(),$query);
   		}
		$query="delete from CALENDAR where  CAL_ID in ($CAL_ID)";
		$cursor =exequery(TD::conn(),$query);
		if($cursor)
			$STATUS="success";
   		//同步删除任务中心中日程信息
  	 	delete_taskcenter('CALENDAR',$CAL_ID,1);
   		delete_remind_sms(5, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT, $CAL_TIME);
	}
	else
	{
		$STATUS="error";
	}
	return $STATUS;
}
//添加周期性事务
function add_affair($AFF_ARRAY,$IS_REMIND)//$AFF_ARRAY = array('USER_ID'=>'用户ID','BEGIN_TIME'=>'起始日期','END_TIME'=>'结束日期','TYPE'=>'提醒类型','CONTENT'=>'事务内容','CAL_TYPE'=>'事务类型','TAKER'=>'参与人','BEGIN_TIME_TIME'=>'开始时间','END_TIME_TIME'=>'结束时间')
{
    if(!is_array($AFF_ARRAY) || sizeof($AFF_ARRAY) < 1)
        return;
    $AFF_KEY_STR = "";
    $AFF_KEY_VAL = "";
    $REMIND_USER = "";
    foreach($AFF_ARRAY as $key => $value)
    {
        $AFF_KEY_STR.=$key.",";
        $AFF_KEY_VAL.="'".$value."',"; 
    }
    $AFF_KEY_STR = td_trim($AFF_KEY_STR);
    $AFF_KEY_VAL = td_trim($AFF_KEY_VAL);
    $query = "insert into AFFAIR ($AFF_KEY_STR) values ($AFF_KEY_VAL)";
    //file_put_contents("a.txt",$query);
    
    exequery(TD::conn(),$query);
    $AFF_ID = mysql_insert_id();
    affair_sms();
    if($AFF_ID!="")
    {
        $STATUS = "success";
    }
    else
    {
        $STATUS = "error";
    } 
    
    $a_return = array();
    $a_return['cal_id'] = $AFF_ID;
    $a_return['status'] = $STATUS;
    
    return $a_return;
}
//修改周期性事务
function update_affair($AFF_ARRAY,$AFF_ID)
{
    if(!is_array($AFF_ARRAY) || sizeof($AFF_ARRAY) < 1)
        return;
    $AFF_KEY_STR = "";
    foreach($AFF_ARRAY as $key => $value)
    {
        $AFF_KEY_STR.=$key."='".$value."',";
    }
    $AFF_KEY_STR = td_trim($AFF_KEY_STR);
    $query = "update AFFAIR set ".$AFF_KEY_STR."where AFF_ID='$AFF_ID'";
    $cursor=exequery(TD::conn(),$query);
    affair_sms();
    if($cursor)
    {
        $STATUS = "success";
    }
    else
    {
        $STATUS = "error";
    }
    
    $a_return = array();
    $a_return['cal_id'] = $AFF_ID;
    $a_return['status'] = $STATUS;
    
    return $a_return;
}
//删除周期性事务
function delete_affair($AFF_ID)
{
    if($AFF_ID!="")
    {
        $query = "delete from AFFAIR where AFF_ID='$AFF_ID'";
        $cursor = exequery(TD::conn(),$query);
        if(mysql_affected_rows() > 0)
        {
            $STATUS = "success";
        }
        else
        {
            $STATUS = "error";
        }
    }
    else
    {
        $STATUS = "error";
    }
    return $STATUS;
}
//删除任务
function delete_task($TASK_ID)
{
    if($TASK_ID!="")
    {
        $query = "select * from TASK where TASK_ID in($TASK_ID)";
        $cursor = exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $SUBJECT=$ROW["SUBJECT"];                
            $MSG = sprintf(_("请查看%s安排的任务！"), $_SESSION["LOGIN_USER_NAME"]);
            $SMS_CONTENT=$MSG."\n"._("标题：").csubstr($SUBJECT,0,50);
            $SMS_CONTENT2=_("OA任务:").$SUBJECT;
            $REMIND_URL="1:calendar/task/note.php?TASK_ID=".$TASK_ID;
            $ADD_TIME=$ROW['ADD_TIME'];
            $MANAGER_ID=$ROW["MANAGER_ID"];
            $REMIND_TIME=$ROW["REMIND_TIME"];
        }
        $query = "delete from TASK where TASK_ID='$TASK_ID'";
        $cursor = exequery(TD::conn(),$query);
        if(mysql_affected_rows() > 0)
        {
            $STATUS = "success";
            delete_taskcenter('TASK',$TASK_ID);
            delete_remind_sms(5,$_SESSION["LOGIN_USER_ID"],$SMS_CONTENT,"",$REMIND_URL);
        }
        else
        {
            $STATUS = "error";
        }
    }
    else
    {
        $STATUS = "error";
    }
    return $STATUS;
}
//获取日程详细信息
function get_cal_detail($CAL_ID,$VIEW)
{
    if(!isset($CAL_ID))
        exit;
    $dataBack = array();
    if($VIEW=="calendar")
    {
        $query = "select * from CALENDAR where CAL_ID='$CAL_ID'";
        $cursor = exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $CAL_TIME = $ROW["CAL_TIME"];
            $END_TIME = $ROW["END_TIME"];
            $CAL_TYPE = $ROW["CAL_TYPE"];
            $CAL_LEVEL = $ROW["CAL_LEVEL"];
            $CONTENT = $ROW["CONTENT"];
            $MANAGER_ID = $ROW["MANAGER_ID"];
            $OVER_STATUS = $ROW["OVER_STATUS"];
            $BEFORE_REMAIND = $ROW["BEFORE_REMAIND"];
            $OWNER = $ROW["OWNER"];
            $TAKER = $ROW["TAKER"];
            $ALLDAY = $ROW["ALLDAY"];
            $TAKER_NAME=GetUserNameById($TAKER);
            $OWNER_NAME=GetUserNameById($OWNER);
            $CAL_DATE = date("Y-m-d",$CAL_TIME);
            $END_DATE = date("Y-m-d",$END_TIME);
            $CAL_TIME_TIME = date("h:i A",$CAL_TIME);
            $END_TIME_TIME = date("h:i A",$END_TIME);
            if($END_TIME == '0' || $END_TIME == '')
            {
                $END_DATE = '';
                $END_TIME_TIME = '';
            }
            if($BEFORE_REMAIND=="")
            {
                $BEFORE_DAY="0";
                $BEFORE_HOUR="0";
                $BEFORE_MIN="10";
            }
            else
            {
                $REMAIND_ARRAY=explode("|",$BEFORE_REMAIND);                
                $BEFORE_DAY=intval($REMAIND_ARRAY[0]);
                $BEFORE_HOUR=intval($REMAIND_ARRAY[1]);
                $BEFORE_MIN=intval($REMAIND_ARRAY[2])==0 ? 10 : intval($REMAIND_ARRAY[2]);
            }
            $dataBack = array(
                'cal_id' => $CAL_ID,
                'cal_date' => $CAL_DATE,
                'end_date' => $END_DATE,
                'cal_type' => $CAL_TYPE,
                'cal_time' => $CAL_TIME_TIME,
                'end_time' => $END_TIME_TIME,
                'cal_level' => $CAL_LEVEL,
                'content' => $CONTENT,
                'manager_id' => $MANAGER_ID,
                'over_status' => $OVER_STATUS,
                'owner_name' => $OWNER_NAME,
                'taker_name' => $TAKER_NAME,
                'allday' => $ALLDAY,
                'before_day' => $BEFORE_DAY,
                'before_hour' => $BEFORE_HOUR,
                'before_min' => $BEFORE_MIN,
                'owner' => $OWNER,
                'taker' => $TAKER
            );        
        }        
    }
    else if($VIEW=="affair")
    {
        $query="select * from AFFAIR where AFF_ID='$CAL_ID'";
        $cursor = exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $BEGIN_TIME=$ROW["BEGIN_TIME"];
            $BEGIN_TIME_TIME=$ROW["BEGIN_TIME_TIME"];
            $BEGIN_TIME=date("Y-m-d",$BEGIN_TIME);
            $END_TIME=$ROW["END_TIME"];    
            if($END_TIME!=0)
                $END_TIME=date("Y-m-d",$END_TIME);
            $END_TIME_TIME=$ROW["END_TIME_TIME"];
            $USER_ID=$ROW["USER_ID"];
            $TYPE=$ROW["TYPE"];
            $REMIND_DATE=$ROW["REMIND_DATE"];
            $REMIND_TIME=$ROW["REMIND_TIME"];
            $CONTENT=$ROW["CONTENT"];
            $CAL_TYPE=$ROW["CAL_TYPE"];
            $SMS2_REMIND=$ROW["SMS2_REMIND"];
            $TAKER=$ROW["TAKER"];
            $TAKER_NAME=GetUserInfoByUID(UserId2Uid($TAKER),"USER_NAME");
            $ALLDAY = $ROW["ALLDAY"];
            $CAL_TIME_TIME = date("h:i A",strtotime($BEGIN_TIME_TIME));
            $END_TIME_TIME = date("h:i A",strtotime($END_TIME_TIME));
            $REMIND_TIME = date("h:i A",strtotime($REMIND_TIME));      
            
            $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
            if($TYPE=="5")
            {
                $REMIND_ARR=explode("-",$REMIND_DATE);
                $REMIND_DATE_MON=$REMIND_ARR[0];
                $REMIND_DATE_DAY=$REMIND_ARR[1];
            }
            $dataBack = array(
                'cal_id' => $CAL_ID,
                'cal_date' => $BEGIN_TIME,
                'end_date' => $END_TIME,
                'cal_time' => $CAL_TIME_TIME,
                'end_time' => $END_TIME_TIME,
                'type' => $TYPE,
                'content' => $CONTENT,
                'cal_type' => $CAL_TYPE,
                'taker' => $TAKER,
                'taker_name' => $TAKER_NAME,
                'allday' => $ALLDAY,
                'remind_date' => $REMIND_DATE,
                'remind_time' => $REMIND_TIME
            );
        }        
    }    
    return $dataBack;    
}
?>