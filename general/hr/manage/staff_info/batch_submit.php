<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("general/system/log/annual_leave_log.php");
$CUR_TIME=date("Y-m-d H:i:s",time());

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$USER_ID=strtok($TO_ID,",");
while($USER_ID!="")
{
   if($USER_ID=="")
   {
   	  $USER_ID=strtok(",");
   	  continue;
   }
	
	//未建档的不修改
   $query="select count(*) from HR_STAFF_INFO,USER where HR_STAFF_INFO.USER_ID=USER.USER_ID and HR_STAFF_INFO.USER_ID='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   $ROW=mysql_fetch_array($cursor);
   $COUNT=$ROW[0];
   //var_dump($COUNT);
   if ($COUNT=="1")
   {
		  if($LEAVE_TYPE!="")
		  {
		    $query1="update HR_STAFF_INFO set LEAVE_TYPE='$LEAVE_TYPE',LAST_UPDATE_TIME='$CUR_TIME' where USER_ID='$USER_ID'";
		    exequery(TD::conn(),$query1);
            
		  }
		  if($WORK_STATUS!="")
		  {
		    $query1="update HR_STAFF_INFO set WORK_STATUS='$WORK_STATUS',LAST_UPDATE_TIME='$CUR_TIME' where USER_ID='$USER_ID'";
		    exequery(TD::conn(),$query1);
		  }
		
    	if(($SELECTITEM!="-1")&&($TContext!=""))
   		{
      	if($MODE=="overwrite")
				  $query1="update HR_STAFF_INFO set $SELECTITEM='$TContext',LAST_UPDATE_TIME='$CUR_TIME' where USER_ID='$USER_ID'";
			  else //MODE=append
			    $query1="update HR_STAFF_INFO set $SELECTITEM=CONCAT($SELECTITEM,'\n$TContext'),LAST_UPDATE_TIME='$CUR_TIME' where USER_ID='$USER_ID'";        	
	  		exequery(TD::conn(),$query1);
   		}
    
   		//--------------------批量修改自定义字段------------------------
   		//save_field_data("HRMS",$USER_ID,$_POST);
   		$USERDEF_FIELD=array();
   		//处理非复选框
   		while (list($key, $value) = each($_POST))
   		{
      	if(substr($key, 0, 7)!="USERDEF" || strstr(substr($key, 7),"_"))
        	continue;
     		$USERDEF_FIELD[$key]=$value;		  
   		}

   		//处理复选框，把相同复选框的值串起来并保存在数组CHECKBOX_FIELD里
   		reset($_POST);
   		while (list($key, $value) = each($_POST))
   		{
      		if(substr($key, 0, 7)!="USERDEF" || !strstr(substr($key, 7),"_"))
         	continue;

      		$ARRAY=explode("_",substr($key,7));
      		$USERDEF_FIELD["USERDEF".$ARRAY[0]].=$ARRAY[1].",";
   		}
  
   		reset($_POST);
   		while (list($key, $value) = each($USERDEF_FIELD))
   		{
	  		$query2 = "select * from FIELD_DATE where TABLENAME='HR_STAFF_INFO' and FIELDNO='$key' and IDENTY_ID='$USER_ID';";
      		$cursor2=exequery(TD::conn(),$query2);
	  		if (td_trim($value)!='')
	  		{
         	if(mysql_num_rows($cursor2)>0)
				  {
            //if($MODE=="overwrite")
            $query2 = "update FIELD_DATE set ITEM_DATE='$value' where TABLENAME='HR_STAFF_INFO' and FIELDNO='$key' and IDENTY_ID='$USER_ID';";
            //else
            //  $query2 = "update FIELD_DATE set ITEM_DATE=CONCAT(ITEM_DATE,'\n$value') where TABLENAME='HRMS' and FIELDNO='$key' and IDENTY_ID='$USER_ID';";
				  }
         	else
            $query2 = "insert into FIELD_DATE (TABLENAME,FIELDNO,IDENTY_ID,ITEM_DATE) values ('HR_STAFF_INFO','$key','$USER_ID','$value');";
		  		exequery(TD::conn(),$query2);	 
	  		}
	 		  //echo $query2;
	  	  //break;
   		}//end while list
   }
   $USER_ID=strtok(",");
}//while
if($LEAVE_TYPE!="")
{
    $log_data = array(
                    "des" => array(
                            "annualleave" => (double)$LEAVE_TYPE
                        )
                );
    addAnnualLeaveLog($TO_ID,$log_data,1);
}            
if($count_age == "on")
{
	$query="select USER_ID,STAFF_BIRTH from HR_STAFF_INFO ";
	$cur1=exequery(TD::conn(),$query);
	while($ROW=mysql_fetch_array($cur1))
	{
		$USER_ID=$ROW['USER_ID'];
		$STAFF_BIRTH=$ROW['STAFF_BIRTH'];
		if($STAFF_BIRTH!="0000-00-00" && $STAFF_BIRTH!="")
		{
			$agearray = explode("-",$STAFF_BIRTH);
			$cur = explode("-",$CUR_DATE);
			$year=$agearray[0];
			$STAFF_AGE=date("Y")-$year;
			if($cur[1] > $agearray[1] || $cur[1]==$agearray[1] && $cur[2]>=$agearray[2])
			{
				$STAFF_AGE++;
			}
			$STAFF_AGE=$STAFF_AGE-1;
		}
		else
		{
			$STAFF_AGE="";
		}
  	$query1="update HR_STAFF_INFO set STAFF_AGE='$STAFF_AGE' where USER_ID='$USER_ID'";
  	exequery(TD::conn(),$query1);
  }
}
if($count_job_age == "on")
{
	$query="select USER_ID,DATES_EMPLOYED from HR_STAFF_INFO ";
	$cur1=exequery(TD::conn(),$query);
	while($ROW=mysql_fetch_array($cur1))
	{
		$USER_ID=$ROW['USER_ID'];
		$DATES_EMPLOYED=$ROW['DATES_EMPLOYED'];
		if($DATES_EMPLOYED!="0000-00-00" && $DATES_EMPLOYED!="")
		{
			$agearray = explode("-",$DATES_EMPLOYED);
			$cur = explode("-",$CUR_DATE);
			$year=$agearray[0];
			$JOB_AGE=date("Y")-$year;
			if($cur[1] > $agearray[1] || $cur[1]==$agearray[1] && $cur[2]>=$agearray[2])
			{
				$JOB_AGE++;
			}
		}
		else
		{
			$JOB_AGE="";
		}
  	$query1="update HR_STAFF_INFO set JOB_AGE='$JOB_AGE' where USER_ID='$USER_ID'";
  	exequery(TD::conn(),$query1);
  }
}
if($count_work_age == "on")
{
	$query="select USER_ID,JOB_BEGINNING from HR_STAFF_INFO ";
	$cur1=exequery(TD::conn(),$query);
	while($ROW=mysql_fetch_array($cur1))
	{
		$USER_ID=$ROW['USER_ID'];
		$JOB_BEGINNING=$ROW['JOB_BEGINNING'];
		if($JOB_BEGINNING!="0000-00-00" && $JOB_BEGINNING!="")
		{
			$agearray = explode("-",$JOB_BEGINNING);
			$cur = explode("-",$CUR_DATE);
			$year=$agearray[0];
			$WORK_AGE=date("Y")-$year;
			if($cur[1] > $agearray[1] || $cur[1]==$agearray[1] && $cur[2]>=$agearray[2])
			{
				$WORK_AGE++;
			}
		}
		else
		{
			$WORK_AGE="";
		}
  	$query1="update HR_STAFF_INFO set WORK_AGE='$WORK_AGE' where USER_ID='$USER_ID'";
  	exequery(TD::conn(),$query1);
  }
}
   Message(_("提示"),_("批量更新成功！"));
?>
<div align="center">
	<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='batch_update.php'">
</div>
</body>
</html>