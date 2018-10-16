<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("上下班记录查询");
include_once("inc/header.inc.php");
?>
<script language="JavaScript">
function remark(USER_ID,REGISTER_TYPE,REGISTER_TIME)
{
  URL="remark.php?USER_ID="+USER_ID+"&REGISTER_TYPE="+REGISTER_TYPE+"&REGISTER_TIME="+REGISTER_TIME;
  myleft=(screen.availWidth-650)/2;
  window.open(URL,"formul_edit","height=250,width=450,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

</script>
<body class="bodycolor">
<?
  //----------- 合法性校验 ---------
  if($DATE1!="")
  {
    $TIME_OK=is_date($DATE1);

    if(!$TIME_OK)
    { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if($DATE2!="")
  {
    $TIME_OK=is_date($DATE2);

    if(!$TIME_OK)
    { Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if(compare_date($DATE1,$DATE2)==1)
  { Message(_("错误"),_("查询的起始日期不能晚于截止日期"));
    Button_Back();
    exit;
  }

 $query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $DAY_TOTAL=$ROW[0]+1;

 $query = "SELECT * from USER_EXT,USER where USER.UID=USER_EXT.UID and USER.USER_ID='$USER_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $DUTY_TYPE=$ROW["DUTY_TYPE"];
    $USER_NAME=$ROW["USER_NAME"];
    $DEPT_ID=$ROW["DEPT_ID"];
 }

 if(!is_dept_priv($DEPT_ID) && $_SESSION["LOGIN_USER_PRIV"]!=1)
 {
 	  Message(_("错误"),_("不属于管理范围内的用户"));
    exit;
 }
 
 $MSG = sprintf(_("共 %d 天"), $DAY_TOTAL);
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
    <span class="big3"> <?=_("上下班查询结果")?> - [<?=format_date($DATE1)?> <?=_("至")?> <?=format_date($DATE2)?> <?=$MSG?>]</span>&nbsp;&nbsp;
    </td>
  </tr>
</table>

<br>

<table class="TableList"  width="95%" align="center">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("日期")?></td>
<?
 $COUNT = 0;
 if($DUTY_TIME1!="")
 {
    $COUNT++;
    if($DUTY_TYPE1=="1")
       $DUTY_TYPE_DESC1=_("上班");
    else
       $DUTY_TYPE_DESC1=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME1?>)</td>
<?
 }
 if($DUTY_TIME2!="")
 {
    $COUNT++;
    if($DUTY_TYPE2=="1")
       $DUTY_TYPE_DESC2=_("上班");
    else
       $DUTY_TYPE_DESC2=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME2?>)</td>
<?
 }
 if($DUTY_TIME3!="")
 {
    $COUNT++;
    if($DUTY_TYPE3=="1")
       $DUTY_TYPE_DESC3=_("上班");
    else
       $DUTY_TYPE_DESC3=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME3?>)</td>
<?
 }
 if($DUTY_TIME4!="")
 {
    $COUNT++;
    if($DUTY_TYPE4=="1")
       $DUTY_TYPE_DESC4=_("上班");
    else
       $DUTY_TYPE_DESC4=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME4?>)</td>
<?
 }
 if($DUTY_TIME5!="")
 {
    $COUNT++;
    if($DUTY_TYPE5=="1")
       $DUTY_TYPE_DESC5=_("上班");
    else
       $DUTY_TYPE_DESC5=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME5?>)</td>
<?
 }
 if($DUTY_TIME6!="")
 {
    $COUNT++;
    if($DUTY_TYPE6=="1")
       $DUTY_TYPE_DESC6=_("上班");
    else
       $DUTY_TYPE_DESC6=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME6?>)</td>
<?
 }
?>
  </tr>

<?
$DUTY_TIME1_COMPARE=$DUTY_TIME1!=""? $DUTY_TYPE_DESC1."(".$DUTY_TIME1.")":"";
$DUTY_TIME2_COMPARE=$DUTY_TIME2!=""? $DUTY_TYPE_DESC2."(".$DUTY_TIME2.")":"";
$DUTY_TIME3_COMPARE=$DUTY_TIME3!=""? $DUTY_TYPE_DESC3."(".$DUTY_TIME3.")":"";
$DUTY_TIME4_COMPARE=$DUTY_TIME4!=""? $DUTY_TYPE_DESC4."(".$DUTY_TIME4.")":"";
$DUTY_TIME5_COMPARE=$DUTY_TIME5!=""? $DUTY_TYPE_DESC5."(".$DUTY_TIME5.")":"";
$DUTY_TIME6_COMPARE=$DUTY_TIME6!=""? $DUTY_TYPE_DESC6."(".$DUTY_TIME6.")":"";

for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
{
   $WEEK=date("w",strtotime($J));
   $HOLIDAY="";
   $query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$J' and END_DATE>='$J'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
        $HOLIDAY=_("节假日");
   else
   {
        if(find_id($GENERAL,$WEEK))
           $HOLIDAY=_("公休日");
   }

   if($HOLIDAY=="")
      $TableLine="TableData";
   else
      $TableLine="TableContent";

   //查询考勤记录
   $query1 = "SELECT * from ATTEND_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') GROUP by to_days(REGISTER_TIME)";
   $cursor1= exequery(TD::conn(),$query1);
   $LINE_COUNT=0;
   if($ROW=mysql_fetch_array($cursor1))
   {
     $LINE_COUNT++;
     $REGISTER_TIME=$ROW["REGISTER_TIME"];

     $query="select * from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
     $cursor= exequery(TD::conn(),$query);
     if($ROW=mysql_fetch_array($cursor))
        $HOLIDAY=_("出差");
     $EXCEL_OUT.= $J._("(").get_week($J)."),";
?>
       <tr class="<?=$TableLine?>">
         <td nowrap align="center"><?=$J?>(<?=get_week($J)?>)</td>

<?
    //---- 第1组 -----
   for($I=1;$I<=6;$I++)
    {
       $DUTY_TIME_I="DUTY_TIME".$I;
       $DUTY_TIME_I=$$DUTY_TIME_I;
        if($I%2==0)
        {
            $DUTY_TYPE_I = 2;
        }else
        {
            $DUTY_TYPE_I = 1;
        }

       if($DUTY_TIME_I=="" || $DUTY_TIME_I=="00:00:00")
          continue;

       $HOLIDAY1="";
       if($HOLIDAY=="")
       {
          $query="select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1<='$J $DUTY_TIME_I' and LEAVE_DATE2>='$J $DUTY_TIME_I'";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
          {
    	       $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
    	       $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");          
             $HOLIDAY1=_("请假-$LEAVE_TYPE2");
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
       $ADD_IP_FLAG=0;
       $REGISTER_IP="";
       $query = "SELECT * from ATTEND_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') and REGISTER_TYPE='$I'";
       $cursor= exequery(TD::conn(),$query);
       if($ROW=mysql_fetch_array($cursor))
       {
          $REGISTER_TIME2=$ROW["REGISTER_TIME"];
          $REGISTER_TIME=$ROW["REGISTER_TIME"];
          $REGISTER_IP=$ROW["REGISTER_IP"];
          $REMARK=$ROW["REMARK"];
          $REMARK=str_replace("\n","  ",$REMARK);
          $REGISTER_TIME=strtok($REGISTER_TIME," ");
          $REGISTER_TIME=strtok(" ");

          if($HOLIDAY1==""&&$DUTY_TYPE_I=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_I)==1)
             {$REGISTER_TIME.="(".$REGISTER_IP.")"._(" 迟到");$ADD_IP_FLAG=1;}

          if($HOLIDAY1==""&&$DUTY_TYPE_I=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_I)==-1)
             {$REGISTER_TIME.="(".$REGISTER_IP.")"._(" 早退");$ADD_IP_FLAG=1;}

          if($REMARK!="")
             $REMARK="  "._("说明：").$REMARK;
       }
       else
       {
          if($HOLIDAY1=="")
          {
             $REGISTER_TIME=_("未登记");
             $ADD_IP_FLAG=1;
          }
          else
          {
          	 if($REGISTER_IP!="")
                $REGISTER_TIME=$HOLIDAY1."(".$REGISTER_IP.")";
             else
                $REGISTER_TIME=$HOLIDAY1;
             $ADD_IP_FLAG=1;
          }
       }

       if($ADD_IP_FLAG!=1)
          $REGISTER_TIME.="(".$REGISTER_IP.")";
       $EXCEL_OUT.=$REGISTER_TIME.$REMARK.",";
?>
         <td nowrap align="center"><?=$REGISTER_TIME?><?=$REMARK?>&nbsp;
         </td>
<?
    }
?>
       </tr>
<?
   $EXCEL_OUT.="\n";
   }
   else //未查到考勤记录
   {
    $EXCEL_OUT.= $J._("(").get_week($J)."),";
?>
       <tr class="<?=$TableLine?>">
         <td nowrap align="center"><?=$J?>(<?=get_week($J)?>)</td>
<?
        for($I=1;$I<=$COUNT;$I++)
        {
            $DUTY_TIME_I="DUTY_TIME".$I;
            $DUTY_TIME_I=$$DUTY_TIME_I;
            if($I%2==0)
            {
                $DUTY_TYPE_I = 2;
            }else
            {
                $DUTY_TYPE_I = 1;
            }
        
          	$OUT = "";
            $query="select USER_ID from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
               $OUT=_("出差");
        
            $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$J') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
               $OUT=_("未登记(外出)");
        
            $query="select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 <= '$J $DUTY_TIME_I' and LEAVE_DATE2 >= '$J $DUTY_TIME_I'";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
    	         $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
    	         $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");            
               $OUT=_("请假-$LEAVE_TYPE2");
            }
        
        	 if($OUT!="" && $HOLIDAY=="")
        	    $EXCEL_OUT.=$OUT.",";
        	 else if($HOLIDAY!="")
              $EXCEL_OUT.=$HOLIDAY.",";
           else
             $EXCEL_OUT.=_("未登记,");
        }
         $EXCEL_OUT.="\n";
?>
       </tr>
<?
   } 
}//for
?>
</table>
<?
ob_end_clean();
require_once ('inc/ExcelWriter.php');

if(MYOA_IS_UN == 1)
	$OUTPUT_HEAD="DATE".",".$DUTY_TIME1_COMPARE.",".$DUTY_TIME2_COMPARE.",".$DUTY_TIME3_COMPARE.",".$DUTY_TIME4_COMPARE.",".$DUTY_TIME5_COMPARE.",".$DUTY_TIME6_COMPARE;
else
  $OUTPUT_HEAD=_("日期").",".$DUTY_TIME1_COMPARE.",".$DUTY_TIME2_COMPARE.",".$DUTY_TIME3_COMPARE.",".$DUTY_TIME4_COMPARE.",".$DUTY_TIME5_COMPARE.",".$DUTY_TIME6_COMPARE;

$NEWFILENAME=sprintf(_("%s的上下班详细记录"), $USER_NAME);
$objExcel = new ExcelWriter();
$objExcel->setFileName($NEWFILENAME);
$objExcel->addHead($OUTPUT_HEAD);

$lines=explode("\n",$EXCEL_OUT);
foreach($lines as $line_str)
{
	$ROW_OUT="";
	if($line_str!="")
	{
		$line=explode(",",$line_str);
		foreach($line as $value)
		{
			$ROW_OUT.=format_cvs($value).",";
		}
		$objExcel->addRow($ROW_OUT);
	}
} 
$objExcel->Save();

?>