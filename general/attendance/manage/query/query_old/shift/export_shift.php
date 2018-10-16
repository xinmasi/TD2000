<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("轮班考勤统计导出");
include_once("inc/header.inc.php");


$EXCEL_OUT=array(_("部门"), _("姓名"), _("登记次数"));
if(MYOA_IS_UN == 1)
   $OUTPUT_HEAD="DEPTNAME,NAME,DJCS";
else
   $OUTPUT_HEAD=array(_("部门"),_("姓名"),_("登记次数"));

$query = "SELECT * from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $NO_DUTY_USER=$ROW["PARA_VALUE"];
   
$query4 = "select USER.USER_NAME,USER.USER_ID,DEPARTMENT.DEPT_NAME from USER,USER_EXT,USER_PRIV,DEPARTMENT,ATTEND_DUTY_SHIFT where not find_in_set(USER.USER_ID,'$NO_DUTY_USER') and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE='99' and USER.USER_PRIV=USER_PRIV.USER_PRIV and DEPARTMENT.DEPT_ID = USER.DEPT_ID and ATTEND_DUTY_SHIFT.USER_ID=USER.USER_ID group by ATTEND_DUTY_SHIFT.USER_ID order by DEPT_NO,PRIV_NO,USER_NO,USER_NAME";
$cursor4= exequery(TD::conn(),$query4);
$USER_COUNT=0;
$COUNT_ARRAY[$USER_COUNT][$COUNT_TYPE]=0;
while($ROW4=mysql_fetch_array($cursor4))
{
   $USER_COUNT++;	 
   $TIME_TOTAL = 0;
   $DUTY_TYPE=$ROW4["DUTY_TYPE"];
   $USER_NAME=$ROW4["USER_NAME"];
   $DEPT_NAME=$ROW4["DEPT_NAME"];
   $USER_ID=$ROW4["USER_ID"];
   
   $query5 = "select count(*) from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)>=to_days('$DATE1') and to_days(REGISTER_TIME)<=to_days('$DATE2')";
   $cursor5 = exequery(TD::conn(),$query5);
   $DJCS=mysql_fetch_row($cursor5);
   
	 $COUNT_ARRAY[$USER_COUNT][1]=$DEPT_NAME;
	 $COUNT_ARRAY[$USER_COUNT][2]=$USER_NAME;	    
	 $COUNT_ARRAY[$USER_COUNT][3]=$DJCS[0];

   for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
   {
       $WEEK=date("w",strtotime($J));
       $HOLIDAY="";
       $query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$J' and END_DATE>='$J'";
       $cursor= exequery(TD::conn(),$query);
       if($ROW=mysql_fetch_array($cursor))
       {
           for($I=1;$I<=6;$I++)
           {
              $DUTY_TIME_I="DUTY_TIME".$I;
              $DUTY_TIME_I=$$DUTY_TIME_I;
              $DUTY_TYPE_I="DUTY_TYPE".$I;
              $DUTY_TYPE_I=$$DUTY_TYPE_I;

              if($DUTY_TIME_I=="" || $DUTY_TIME_I=="00:00:00")
                 continue;

              $query = "SELECT * from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') and REGISTER_TYPE='$I'";
              $cursor= exequery(TD::conn(),$query);
              if($ROW=mysql_fetch_array($cursor))
              {
              	 if($DUTY_TYPE_I=="1")
              	    $COUNT_ARRAY[$USER_COUNT][9]++;
              	 if($DUTY_TYPE_I=="2")
              	    $COUNT_ARRAY[$USER_COUNT][10]++;
              }
           }

          $HOLIDAY=_("节假日");
       }
       else
       {
          if(find_id($GENERAL,$WEEK))
          {
             for($I=1;$I<=6;$I++)
             {
                $DUTY_TIME_I="DUTY_TIME".$I;
                $DUTY_TIME_I=$$DUTY_TIME_I;
                $DUTY_TYPE_I="DUTY_TYPE".$I;
                $DUTY_TYPE_I=$$DUTY_TYPE_I;

                if($DUTY_TIME_I=="" || $DUTY_TIME_I=="00:00:00")
                   continue;

                $query = "SELECT * from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') and REGISTER_TYPE='$I'";
                $cursor= exequery(TD::conn(),$query);
                if($ROW=mysql_fetch_array($cursor))
                {
                	 if($DUTY_TYPE_I=="1")
                	    $COUNT_ARRAY[$USER_COUNT][9]++;
                	 if($DUTY_TYPE_I=="2")
                	    $COUNT_ARRAY[$USER_COUNT][10]++;
                }
             }
             $HOLIDAY=_("公休日");
          }
       }

				
       $query1 = "SELECT * from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') GROUP by to_days(REGISTER_TIME)";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW=mysql_fetch_array($cursor1))
       {
       	  $REGISTER_TIME=$ROW["REGISTER_TIME"];
          $query="select * from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
             $HOLIDAY=_("出差");
					$count_all = 0;
          for($I=1;$I<=6;$I++)
          {
              $DUTY_TIME_I="DUTY_TIME".$I;
              $DUTY_TIME_I=$$DUTY_TIME_I;
              $DUTY_TYPE_I="DUTY_TYPE".$I;
              $DUTY_TYPE_I=$$DUTY_TYPE_I;

              if($DUTY_TIME_I=="" || $DUTY_TIME_I=="00:00:00")
                 continue;

              $HOLIDAY1="";
              if($HOLIDAY=="")
              {
                  $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1<='$J $DUTY_TIME_I' and LEAVE_DATE2>='$J $DUTY_TIME_I'";
                  $cursor= exequery(TD::conn(),$query);
                  if($ROW=mysql_fetch_array($cursor))
                  {
           	         $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
           	         $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
                     $HOLIDAY=_("请假");
                     $HOLIDAY1=_("请假");
                  }
              }
              else
                 $HOLIDAY1=$HOLIDAY;

              if($HOLIDAY==""&&$HOLIDAY1=="")
              {
                 $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$J') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
                 $cursor= exequery(TD::conn(),$query);
                 if($ROW=mysql_fetch_array($cursor))
                    $HOLIDAY1=_("外出");
              }

              $REGISTER_TIME=""; 
              $REMARK="";
              $query = "SELECT * from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') and REGISTER_TYPE='$I'";
              $cursor= exequery(TD::conn(),$query);
              if($ROW=mysql_fetch_array($cursor))
              {
                  $REGISTER_TIME2=$ROW["REGISTER_TIME"];
                  $REGISTER_TIME=$ROW["REGISTER_TIME"];
                  $ALL_MINITES[$USER_ID][$J][$I] = $REGISTER_TIME;
                  $REGISTER_TIME=strtok($REGISTER_TIME," ");
                  $REGISTER_TIME=strtok(" ");
                  
                  if($HOLIDAY1==""&&$DUTY_TYPE_I=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_I)==1)
                  {
                     $COUNT_ARRAY[$USER_COUNT][5]++;
                  	  $REGISTER_TIME.=_("迟到");
                  }
                  
                  if($HOLIDAY1==""&&$DUTY_TYPE_I=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_I)==-1)
                  {
                  	  $COUNT_ARRAY[$USER_COUNT][7]++;
                  	  $REGISTER_TIME.=_("早退");
                  }
                  if($HOLIDAY!=_("节假日") && $HOLIDAY!=_("公休日"))
                     $count_all++;
              }
              else
              {
                 if($HOLIDAY1=="")
                 {
                 	 if($DUTY_TYPE_I=="1")
                 	    $COUNT_ARRAY[$USER_COUNT][6]++;
                 	 if($DUTY_TYPE_I=="2")
                 	    $COUNT_ARRAY[$USER_COUNT][8]++;                 	
                      $REGISTER_TIME.=_("未登记");
                      
                 }
                 else
                    $REGISTER_TIME=$HOLIDAY1;
              }
          }
          
          
       for($l = 1 ;$l<= $COUNT/2;$l ++)
       {   
       	  if($ALL_MINITES[$USER_ID][$J][$l*2]!="" && $ALL_MINITES[$USER_ID][$J][$l*2-1]!="")           
       	     $TIME_TOTAL+= strtotime($ALL_MINITES[$USER_ID][$J][$l*2]) - strtotime($ALL_MINITES[$USER_ID][$J][$l*2-1]);
       }  
       $ALL_HOURS = floor($TIME_TOTAL / 3600);
       $HOUR1 = $TIME_TOTAL % 3600;
       $MINITE = floor($HOUR1 / 60);
       $COUNT_ARRAY[$USER_COUNT][4]= $ALL_HOURS._("时").$MINITE._("分") ;     

       }
       else
       {
           for($I=1;$I<=$COUNT;$I++)
           {
              $DUTY_TIME_I="DUTY_TIME".$I;
              $DUTY_TIME_I=$$DUTY_TIME_I;
              $DUTY_TYPE_I="DUTY_TYPE".$I;
              $DUTY_TYPE_I=$$DUTY_TYPE_I;
              
           	  $OUT = "";
              $query="select USER_ID from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
              $cursor= exequery(TD::conn(),$query);
              if($ROW=mysql_fetch_array($cursor))
                 $OUT=_("出差");
              $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$J') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
              $cursor= exequery(TD::conn(),$query);
              if($ROW=mysql_fetch_array($cursor))
                 $OUT=_("未登记外出");
              $query="select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 <= '$J $DUTY_TIME_I' and LEAVE_DATE2 >= '$J $DUTY_TIME_I'";
              $cursor= exequery(TD::conn(),$query);
              if($ROW=mysql_fetch_array($cursor))
              {
              	  $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
              	  $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
                 $OUT=_("请假");
              	
              }               
              
              if($OUT=="" && $HOLIDAY=="")
              {
              	 if($DUTY_TYPE_I=="1")
              	    $COUNT_ARRAY[$USER_COUNT][6]++;
              	 if($DUTY_TYPE_I=="2")
              	    $COUNT_ARRAY[$USER_COUNT][8]++;
                 //echo _("未登记");
              }
           }
       }
   }

}
require_once ('inc/ExcelWriter.php');
if($USER_COUNT<=0)
{
	  Message(_("提示"),_("没有找到要导出的记录！"));
      Button_Back();
      exit;
}
ob_end_clean();
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("上下班登记数据"));
$objExcel->addHead($OUTPUT_HEAD);

for($I=1;$I<=$USER_COUNT;$I++)
{
   $ROW_OUT=$COUNT_ARRAY[$I][1].",".$COUNT_ARRAY[$I][2].",".$COUNT_ARRAY[$I][3];
   $objExcel->addRow($ROW_OUT);
}
$objExcel->Save();

?>
</body>
</html>