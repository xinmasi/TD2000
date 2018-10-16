<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
ob_start();

$HTML_PAGE_TITLE = _("招聘筛选");
include_once("inc/header.inc.php");
?>


<body class="bodycolor">
<?
//-----------------校验-------------------------------------
if($NEXT_DATE_TIME1!="" && $NEXT_DATE_TIME1!="0000-00-00 00:00:00")
{
  $TIME_OK=is_date_time($NEXT_DATE_TIME1);

  if(!$TIME_OK)
  { Message(_("错误"),_("起始时间格式不对，应形如 1999-1-2 09:30:00"));
    Button_Back();
    exit;
  }
}


if($STEP_FLAG==1)
{
	if($PASS_OR_NOT1==1)
	{
		  $query="update HR_RECRUIT_FILTER set
		  FILTER_METHOD1='$FILTER_METHOD1',
		  FILTER_DATE_TIME1='$FILTER_DATE_TIME1',
		  FIRST_CONTENT1='$FIRST_CONTENT1',
		  FIRST_VIEW1='$FIRST_VIEW1',
		  TRANSACTOR_STEP1='$TRANSACTOR_STEP1',
		  PASS_OR_NOT1='$PASS_OR_NOT1',
		  NEXT_TRANSA_STEP1='$NEXT_TRANSA_STEP1',
		  NEXT_DATE_TIME1='$NEXT_DATE_TIME1',
		  NEXT_TRANSA_STEP1='$NEXT_TRANSA_STEP1',
		  STEP_FLAG='2' where FILTER_ID='$FILTER_ID'";
		  exequery(TD::conn(),$query);

      if($IS_FINISH==1)
      {
      	 $query="update HR_RECRUIT_POOL set WHETHER_BY_SCREENING='1' where EXPERT_ID=(select EXPERT_ID from HR_RECRUIT_FILTER where FILTER_ID='$FILTER_ID')";
      	 exequery(TD::conn(),$query);
      	 $query="update HR_RECRUIT_FILTER set END_FLAG='2' where FILTER_ID='$FILTER_ID'";
      	 exequery(TD::conn(),$query);
      }

			$REMIND_URL1="1:hr/recruit/filter/index1.php";
			if($SMS_REMIND=="on" && $NEXT_TRANSA_STEP1!=""&&$IS_FINISH==0)
   			 send_sms("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP1,65,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),$REMIND_URL1);

			if($SMS2_REMIND=="on" && $NEXT_TRANSA_STEP1!=""&&$IS_FINISH==0)
   			 send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP1,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),65);
  }
  else
  {
  	  $query="update HR_RECRUIT_FILTER set
		  FILTER_METHOD1='$FILTER_METHOD1',
		  FILTER_DATE_TIME1='$FILTER_DATE_TIME1',
		  FIRST_CONTENT1='$FIRST_CONTENT1',
		  FIRST_VIEW1='$FIRST_VIEW1',
		  TRANSACTOR_STEP1='$TRANSACTOR_STEP1',
		  PASS_OR_NOT1='$PASS_OR_NOT1',
		  NEXT_TRANSA_STEP1='$NEXT_TRANSA_STEP1',
		  NEXT_DATE_TIME1='$NEXT_DATE_TIME1',
		  NEXT_TRANSA_STEP1='$NEXT_TRANSA_STEP1',
		  END_FLAG='1',
		  STEP_FLAG='2' where FILTER_ID='$FILTER_ID'";
  }

}
else if($STEP_FLAG==2)
{
	 if($PASS_OR_NOT2==1)
	 {
	    $query="update HR_RECRUIT_FILTER set
      FILTER_METHOD2='$FILTER_METHOD2',
      FILTER_DATE_TIME2='$FILTER_DATE_TIME2',
      FIRST_CONTENT2='$FIRST_CONTENT2',
      FIRST_VIEW2='$FIRST_VIEW2',
      TRANSACTOR_STEP2='$TRANSACTOR_STEP2',
      PASS_OR_NOT2='$PASS_OR_NOT2',
      NEXT_TRANSA_STEP2='$NEXT_TRANSA_STEP2',
      NEXT_DATE_TIME2='$NEXT_DATE_TIME2',
	  NEXT_TRANSA_STEP2='$NEXT_TRANSA_STEP2',
      STEP_FLAG='3' where FILTER_ID='$FILTER_ID'";
	  exequery(TD::conn(),$query);

      if($IS_FINISH==1)
      {
      	 $query="update HR_RECRUIT_POOL set WHETHER_BY_SCREENING='1' where EXPERT_ID=(select EXPERT_ID from HR_RECRUIT_FILTER where FILTER_ID='$FILTER_ID')";
      	 exequery(TD::conn(),$query);
      	 $query="update HR_RECRUIT_FILTER set END_FLAG='2' where FILTER_ID='$FILTER_ID'";
      	 exequery(TD::conn(),$query);
      }

      $REMIND_URL1="1:hr/recruit/filter/index1.php";
      if($SMS_REMIND=="on" && $NEXT_TRANSA_STEP2!="" && $IS_FINISH==0)
         send_sms("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP2,65,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),$REMIND_URL1);

      if($SMS2_REMIND=="on" && $NEXT_TRANSA_STEP2!="" && $IS_FINISH==0)
         send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP2,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),65);

	 }
	 else
	 {
	 	  $query="update HR_RECRUIT_FILTER set
      FILTER_METHOD2='$FILTER_METHOD2',
      FILTER_DATE_TIME2='$FILTER_DATE_TIME2',
      FIRST_CONTENT2='$FIRST_CONTENT2',
      FIRST_VIEW2='$FIRST_VIEW2',
      TRANSACTOR_STEP2='$TRANSACTOR_STEP2',
      PASS_OR_NOT2='$PASS_OR_NOT2',
      NEXT_TRANSA_STEP2='$NEXT_TRANSA_STEP2',
      NEXT_DATE_TIME2='$NEXT_DATE_TIME2',
	  NEXT_TRANSA_STEP2='$NEXT_TRANSA_STEP2',
      END_FLAG='1',
      STEP_FLAG='3' where FILTER_ID='$FILTER_ID'";
	 }


}
else if($STEP_FLAG==3)
{
	if($PASS_OR_NOT3==1)
	{
	   $query="update HR_RECRUIT_FILTER set
     FILTER_METHOD3='$FILTER_METHOD3',
     FILTER_DATE_TIME3='$FILTER_DATE_TIME3',
     FIRST_CONTENT3='$FIRST_CONTENT3',
     FIRST_VIEW3='$FIRST_VIEW3',
     TRANSACTOR_STEP3='$TRANSACTOR_STEP3',
     PASS_OR_NOT3='$PASS_OR_NOT3',
     NEXT_TRANSA_STEP3='$NEXT_TRANSA_STEP3',
     NEXT_DATE_TIME3='$NEXT_DATE_TIME3',
	 NEXT_TRANSA_STEP3='$NEXT_TRANSA_STEP3',
     STEP_FLAG='4' where FILTER_ID='$FILTER_ID'";
	 exequery(TD::conn(),$query);

      if($IS_FINISH==1)
      {
      	 $query="update HR_RECRUIT_POOL set WHETHER_BY_SCREENING='1' where EXPERT_ID=(select EXPERT_ID from HR_RECRUIT_FILTER where FILTER_ID='$FILTER_ID')";
      	 exequery(TD::conn(),$query);
      	 $query="update HR_RECRUIT_FILTER set END_FLAG='2' where FILTER_ID='$FILTER_ID'";
      	 exequery(TD::conn(),$query);
      }

     $REMIND_URL1="1:hr/recruit/filter/index1.php";
    if($SMS_REMIND=="on" && $NEXT_TRANSA_STEP3!=""&& $IS_FINISH==0)
       send_sms("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP3,65,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),$REMIND_URL1);

    if($SMS2_REMIND=="on" && $NEXT_TRANSA_STEP3!=""&& $IS_FINISH==0)
       send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP3,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),65);
  }
  else
	{
		$query="update HR_RECRUIT_FILTER set
     FILTER_METHOD3='$FILTER_METHOD3',
     FILTER_DATE_TIME3='$FILTER_DATE_TIME3',
     FIRST_CONTENT3='$FIRST_CONTENT3',
     FIRST_VIEW3='$FIRST_VIEW3',
     TRANSACTOR_STEP3='$TRANSACTOR_STEP3',
     PASS_OR_NOT3='$PASS_OR_NOT3',
     NEXT_TRANSA_STEP3='$NEXT_TRANSA_STEP3',
     NEXT_DATE_TIME3='$NEXT_DATE_TIME3',
	 NEXT_TRANSA_STEP3='$NEXT_TRANSA_STEP3',
     END_FLAG='1',
     STEP_FLAG='4' where FILTER_ID='$FILTER_ID'";
	}
}
else if($STEP_FLAG==4)
{
  if($PASS_OR_NOT4==1)
  {
	   $query="update HR_RECRUIT_FILTER set
     FILTER_METHOD4='$FILTER_METHOD4',
     FILTER_DATE_TIME4='$FILTER_DATE_TIME4',
     FIRST_CONTENT4='$FIRST_CONTENT4',
     FIRST_VIEW4='$FIRST_VIEW4',
     TRANSACTOR_STEP4='$TRANSACTOR_STEP4',
	 NEXT_TRANSA_STEP4='$NEXT_TRANSA_STEP4',
     PASS_OR_NOT4='$PASS_OR_NOT4',
     STEP_FLAG='5'
     where FILTER_ID='$FILTER_ID'";
	 exequery(TD::conn(),$query);

     if($IS_FINISH==1)
     {
      	 $query="update HR_RECRUIT_POOL set WHETHER_BY_SCREENING='1' where EXPERT_ID=(select EXPERT_ID from HR_RECRUIT_FILTER where FILTER_ID='$FILTER_ID')";
      	 exequery(TD::conn(),$query);
      	 $query="update HR_RECRUIT_FILTER set END_FLAG='2' where FILTER_ID='$FILTER_ID'";
      	 exequery(TD::conn(),$query);
     }

     $REMIND_URL1="1:hr/recruit/filter/index1.php";
     if($SMS_REMIND=="on" && $NEXT_TRANSA_STEP!="" && $IS_FINISH==0)
        send_sms("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP,65,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),$REMIND_URL1);

     if($SMS2_REMIND=="on" && $NEXT_TRANSA_STEP!="" && $IS_FINISH==0)
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),65);
   }
   else
   {
       $query="update HR_RECRUIT_FILTER set
       FILTER_METHOD4='$FILTER_METHOD4',
       FILTER_DATE_TIME4='$FILTER_DATE_TIME4',
       FIRST_CONTENT4='$FIRST_CONTENT4',
       FIRST_VIEW4='$FIRST_VIEW4',
       TRANSACTOR_STEP4='$TRANSACTOR_STEP4',
	   NEXT_TRANSA_STEP4='$NEXT_TRANSA_STEP4',
       PASS_OR_NOT4='$PASS_OR_NOT4',
       END_FLAG='1',
       STEP_FLAG='5'
       where FILTER_ID='$FILTER_ID'";
   }
}

exequery(TD::conn(),$query);

header("location:index1.php?FILTER_ID=$FILTER_ID&start=$start&connstatus=1");
?>
</body>
</html>