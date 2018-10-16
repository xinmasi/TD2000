<?
include_once("inc/auth.inc.php");

$WHERE_STR = "";
$WHERE_STR2 = "";
$POST_CAL_TYPE = $CAL_TYPE;
$BEGIN_DATE1=$BEGIN_DATE;
$END_DATE1=$END_DATE;
$CUR_DATE=date("Y-m-d",time());
if($BEGIN_DATE=="")
	 $BEGIN_DATE = substr(date('Y-m-d',strtotime($CUR_DATE.' -3 month')),0,7)."-01";
if($END_DATE=="")
	 $END_DATE = substr(date('Y-m-d',strtotime($CUR_DATE.' +12 month')),0,7)."-01";

$BEGIN_DATE=strtotime($BEGIN_DATE);
$END_DATE=strtotime($END_DATE);
$WHERE_STR2 .= "and   (
   (END_TIME!='0' and BEGIN_TIME <='$BEGIN_DATE' and END_TIME>='$BEGIN_DATE') or
   (END_TIME!='0' and BEGIN_TIME >='$BEGIN_DATE' and  END_TIME<='$END_DATE') or
   (END_TIME!='0' and BEGIN_TIME <='$END_DATE' and  END_TIME>='$END_DATE') or
   (END_TIME='0' and  BEGIN_TIME <='$BEGIN_DATE') or
   (END_TIME='0' and  BEGIN_TIME >='$BEGIN_DATE' and BEGIN_TIME<='$END_DATE')
  ) ";

$BEGIN_DATE2 = $BEGIN_DATE = $BEGIN_DATE." 00:00:01";
$END_DATE2 = $END_DATE = $END_DATE." 23:59:59";
$WHERE_STR .= "and  (
   (END_TIME!='0' and BEGIN_TIME <='$BEGIN_DATE' and END_TIME>='$BEGIN_DATE') or
   (END_TIME!='0' and BEGIN_TIME >='$BEGIN_DATE' and  END_TIME<='$END_DATE') or
   (END_TIME!='0' and BEGIN_TIME <='$END_DATE' and  END_TIME>='$END_DATE') or
   (END_TIME='0' and  BEGIN_TIME <='$BEGIN_DATE') or
   (END_TIME='0' and  BEGIN_TIME >='$BEGIN_DATE' and BEGIN_TIME<='$END_DATE')
  ) ";

if($_SESSION["LOGIN_USER_PRIV"]==1)
{
   if($TO_ID_IN==""&&$PRIV_ID_IN==""&&$TO_ID3_IN=="")
      $ADMIN_WHERE_STR = "USER_ID='".$_SESSION["LOGIN_USER_ID"]."' ";
   else
   {
      if($TO_ID_IN!="ALL_DEPT")
      {
      	 $query = "SELECT USER_ID from USER where (find_in_set(DEPT_ID,'$TO_ID_IN') or find_in_set(USER_PRIV,'$PRIV_ID_IN') or find_in_set(USER_ID,'$TO_ID3_IN')) and (NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0')";
         $cursor= exequery(TD::conn(),$query);
         while($ROW=mysql_fetch_array($cursor))
            $USER_ID_STR.=$ROW["USER_ID"].",";
      }else{
      	  $query = "SELECT USER_ID from USER where NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0'";
         $cursor= exequery(TD::conn(),$query);
         while($ROW=mysql_fetch_array($cursor))
            $USER_ID_STR.=$ROW["USER_ID"].",";
      }
      $ADMIN_WHERE_STR = "find_in_set(USER_ID,'$USER_ID_STR') ";
   }
}else{
   $ADMIN_WHERE_STR = "USER_ID='".$_SESSION["LOGIN_USER_ID"]."' ";
}


//周期性事务
$XML_OUT = "<?xml version=\"1.0\" encoding=\"".MYOA_CHARSET."\"?>\r\n";
$XML_OUT .= "<AFFAIRS>\r\n";
if($AFFAIR_CONTENT=="on")
{
   $query = "SELECT * from AFFAIR where ".$ADMIN_WHERE_STR.$WHERE_STR."order by BEGIN_TIME desc";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $AFF_ID=$ROW["AFF_ID"];
      $USER_ID=$ROW["USER_ID"];
      $BEGIN_TIME=$ROW["BEGIN_TIME"];
      $BEGIN_TIME=date("Y-m-d",$BEGIN_TIME);
      $BEGIN_TIME_TIME=$ROW["BEGIN_TIME_TIME"];
      $END_TIME_TIME=$ROW["END_TIME_TIME"];
      $END_TIME=$ROW["END_TIME"];
      if($END_TIME!=0)
      $END_TIME=date("Y-m-d",$END_TIME);
      $TYPE=$ROW["TYPE"];
      $REMIND_DATE=$ROW["REMIND_DATE"];
      $REMIND_TIME=$ROW["REMIND_TIME"];
      $CONTENT=$ROW["CONTENT"];
      $LAST_REMIND=$ROW["LAST_REMIND"];
      $SMS2_REMIND=$ROW["SMS2_REMIND"];
      $LAST_SMS2_REMIND=$ROW["LAST_SMS2_REMIND"];
      $MANAGER_ID=$ROW["MANAGER_ID"];


      $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
      $CONTENT=td_htmlspecialchars($CONTENT);

      $XML_OUT .= "  <AFFAIR>\r\n";
      $XML_OUT .= "    <FLAG>AFFAIR</FLAG>\r\n";
      $XML_OUT .= "    <BEGIN_DATE>$BEGIN_TIME</BEGIN_DATE>\r\n";
      $XML_OUT .= "    <END_DATE>$END_TIME</END_DATE>\r\n";
      $XML_OUT .= "    <BEGIN_TIME>$BEGIN_TIME_TIME</BEGIN_TIME>\r\n";
      $XML_OUT .= "    <END_TIME>$END_TIME_TIME</END_TIME>\r\n";
      $XML_OUT .= "    <TYPE>$TYPE</TYPE>\r\n";
      $XML_OUT .= "    <REMIND_DATE>$REMIND_DATE</REMIND_DATE>\r\n";
      $XML_OUT .= "    <REMIND_TIME>$REMIND_TIME</REMIND_TIME>\r\n";
      $XML_OUT .= "    <CONTENT>$CONTENT</CONTENT>\r\n";
      $XML_OUT .= "    <LAST_REMIND>$LAST_REMIND</LAST_REMIND>\r\n";
      $XML_OUT .= "    <LAST_SMS2_REMIND>$LAST_SMS2_REMIND</LAST_SMS2_REMIND>\r\n";
      $XML_OUT .= "    <SMS2_REMIND>$SMS2_REMIND</SMS2_REMIND>\r\n";
      $XML_OUT .= "    <MANAGER_ID>$MANAGER_ID</MANAGER_ID>\r\n";
      $XML_OUT .= "  </AFFAIR>\r\n";
   }
}
//日程
if($CALENDAR_CONTENT=="on")
{
   $CALENDAR_WHERE_STR = str_replace("BEGIN_TIME","CAL_TIME",$WHERE_STR);
   if($POST_CAL_TYPE!="")
      $CALENDAR_WHERE_STR .= " and CAL_TYPE ='$POST_CAL_TYPE'";

   $query = "SELECT * from CALENDAR where ".$ADMIN_WHERE_STR.$CALENDAR_WHERE_STR."order by CAL_TIME desc";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $CAL_TIME=$ROW["CAL_TIME"];
      $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
      $END_TIME=$ROW["END_TIME"];
      $END_TIME=date("Y-m-d H:i:s",$END_TIME);
      $CAL_TYPE=$ROW["CAL_TYPE"];
      $CAL_LEVEL=$ROW["CAL_LEVEL"];
      $CONTENT=$ROW["CONTENT"];
      $CONTENT=td_htmlspecialchars($CONTENT);
      $MANAGER_ID=$ROW["MANAGER_ID"];

      $XML_OUT .= "  <AFFAIR>\r\n";
      $XML_OUT .= "    <FLAG>CALENDAR</FLAG>\r\n";
      $XML_OUT .= "    <CAL_TIME>$CAL_TIME</CAL_TIME>\r\n";
      $XML_OUT .= "    <END_TIME>$END_TIME</END_TIME>\r\n";
      $XML_OUT .= "    <CAL_TYPE>$CAL_TYPE</CAL_TYPE>\r\n";
      $XML_OUT .= "    <CAL_LEVEL>$CAL_LEVEL</CAL_LEVEL>\r\n";
      $XML_OUT .= "    <CONTENT>$CONTENT</CONTENT>\r\n";
      $XML_OUT .= "    <MANAGER_ID>$MANAGER_ID</MANAGER_ID>\r\n";
      $XML_OUT .= "  </AFFAIR>\r\n";
   }
}

//任务
if($TASK_CONTENT=="on")
{
   //$TMP_STR = str_replace("BEGIN_TIME","BEGIN_DATE",$WHERE_STR2);
   //$TASK_WHERE_STR = str_replace("END_TIME","END_DATE",$TMP_STR);
   //$TASK_WHERE_STR=" AND BEGIN_DATE>='$BEGIN_DATE1' and (END_DATE<='$END_DATE1' or END_DATE='0000-00-00')";
   $TASK_WHERE_STR = " AND ( 
  (END_DATE!='0000-00-00' and BEGIN_DATE <='$BEGIN_DATE1' and END_DATE>='$BEGIN_DATE1') or 
  (END_DATE!='0000-00-00' and BEGIN_DATE >='$BEGIN_DATE1' and  END_DATE<='$END_DATE1') or 
  (END_DATE!='0000-00-00' and BEGIN_DATE <='$END_DATE1' and  END_DATE>='$END_DATE1') or 
  (END_DATE='0000-00-00' and  BEGIN_DATE <='$BEGIN_DATE1') or 
  (END_DATE='0000-00-00' and  BEGIN_DATE >='$BEGIN_DATE1' and BEGIN_DATE<='$END_DATE1') 
 )";
   if($POST_CAL_TYPE!="")
      $TASK_WHERE_STR .= " and TASK_TYPE ='$POST_CAL_TYPE'";

   $query = "SELECT * from TASK where ".$ADMIN_WHERE_STR.$TASK_WHERE_STR."order by BEGIN_DATE desc";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $TASK_NO=$ROW["TASK_NO"];
      $BEGIN_DATE=$ROW["BEGIN_DATE"];
      $END_DATE=$ROW["END_DATE"];
      $TASK_TYPE=$ROW["TASK_TYPE"];
      $TASK_STATUS=$ROW["TASK_STATUS"];
      $COLOR=$ROW["COLOR"];
      $IMPORTANT=$ROW["IMPORTANT"];
      $RATE=$ROW["RATE"];
      $FINISH_TIME=$ROW["FINISH_TIME"];
      $TOTAL_TIME=$ROW["TOTAL_TIME"];
      $USE_TIME=$ROW["USE_TIME"];
      $EDIT_TIME=$ROW["EDIT_TIME"];
      $CAL_ID=$ROW["CAL_ID"];
      $SUBJECT=$ROW["SUBJECT"];
      $SUBJECT=td_htmlspecialchars($SUBJECT);
      $CONTENT=$ROW["CONTENT"];
      $MANAGER_ID=$ROW["MANAGER_ID"];

      if($FINISH_TIME=="0000-00-00 00:00:00")
         $FINISH_TIME="";
      if($BEGIN_DATE=="0000-00-00")
         $BEGIN_DATE="";
      if($END_DATE=="0000-00-00")
         $END_DATE="";

      $XML_OUT .= "  <AFFAIR>\r\n";
      $XML_OUT .= "    <FLAG>TASK</FLAG>\r\n";
      $XML_OUT .= "    <TASK_NO>$TASK_NO</TASK_NO>\r\n";
      $XML_OUT .= "    <BEGIN_DATE>$BEGIN_DATE</BEGIN_DATE>\r\n";
      $XML_OUT .= "    <END_DATE>$END_DATE</END_DATE>\r\n";
      $XML_OUT .= "    <TASK_TYPE>$TASK_TYPE</TASK_TYPE>\r\n";
      $XML_OUT .= "    <TASK_STATUS>$TASK_STATUS</TASK_STATUS>\r\n";
      $XML_OUT .= "    <COLOR>$COLOR</COLOR>\r\n";
      $XML_OUT .= "    <IMPORTANT>$IMPORTANT</IMPORTANT>\r\n";
      $XML_OUT .= "    <RATE>$RATE</RATE>\r\n";
      $XML_OUT .= "    <FINISH_TIME>$FINISH_TIME</FINISH_TIME>\r\n";
      $XML_OUT .= "    <TOTAL_TIME>$TOTAL_TIME</TOTAL_TIME>\r\n";
      $XML_OUT .= "    <USE_TIME>$USE_TIME</USE_TIME>\r\n";
      $XML_OUT .= "    <SUBJECT>$SUBJECT</SUBJECT>\r\n";
      $XML_OUT .= "    <CONTENT>$CONTENT</CONTENT>\r\n";
      $XML_OUT .= "    <EDIT_TIME>$EDIT_TIME</EDIT_TIME>\r\n";
      $XML_OUT .= "    <CAL_ID>$CAL_ID</CAL_ID>\r\n";
      $XML_OUT .= "    <MANAGER_ID>$MANAGER_ID</MANAGER_ID>\r\n";
      $XML_OUT .= "  </AFFAIR>\r\n";
   }
}
$XML_OUT .= "</AFFAIRS>\r\n";

//echo $XML_OUT;exit;

ob_end_clean();
Header("Cache-control: private");
Header("Content-type: application/octet-stream");
Header("Accept-Ranges: bytes");
Header("Accept-Length: ".strlen($XML_OUT));
Header("Content-Length: ".strlen($XML_OUT));
Header("Content-Disposition: attachment; ".get_attachment_filename($CONTENT_NAME.".xml"));
echo $XML_OUT;
?>