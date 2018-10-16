<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
$query = "SELECT * from ZBAP_PAIBAN where PAIBAN_ID='$PAIBAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $PAIBAN_ID=$ROW["PAIBAN_ID"];
   $ZHIBANREN=$ROW["ZHIBANREN"];
   $PAIBAN_TYPE=$ROW["PAIBAN_TYPE"];
   $ZHIBAN_TYPE=$ROW["ZHIBAN_TYPE"];
   $ZBSJ_B=$ROW["ZBSJ_B"];
   $ZBSJ_E=$ROW["ZBSJ_E"]; 
   $ZBYQ=$ROW["ZBYQ"];
   $BEIZHU=$ROW["BEIZHU"]; 
   $PAIBAN_APR=$ROW["PAIBAN_APR"]; 
   $ANPAI_TIME=$ROW["ANPAI_TIME"];  
   $ZB_RZ=$ROW["ZB_RZ"];
  
   $PAIBAN_APR_NAME="";
   if($PAIBAN_APR!="")
   {
      $query1 = "SELECT USER_NAME from USER where USER_ID='$PAIBAN_APR'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
         $PAIBAN_APR_NAME="<b>"._("安排人：")."</b>".$ROW1["USER_NAME"]."<br>";
   }
   $ZHIBANREN_NAME="";
   if($ZHIBANREN!="")
   {
      $query1 = "SELECT USER_NAME from USER where USER_ID='$ZHIBANREN'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
         $ZHIBANREN_NAME="<b>"._("值班人：")."</b>".$ROW1["USER_NAME"]."<br>";
   }
                  
             
   $TITLE=csubstr($CONTENT,0,10);
   if(substr($CAL_TIME,0,10) == $CUR_DATE && substr($END_TIME,0,10) == $CUR_DATE)
   {
      $CAL_TIME=substr($CAL_TIME,11,5);
      $END_TIME=substr($END_TIME,11,5);
   }
   else
   {
      $CAL_TIME=substr($CAL_TIME,0,16);
      $END_TIME=substr($END_TIME,0,16);
   }
}
//修改事务提醒状态--yc
update_sms_status('55',$PAIBAN_ID);

$HTML_PAGE_TITLE = $TITLE;
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">


<body bgcolor="#FFFFCC" topmargin="5" style="background:none;">

<div class="small" style="margin-top:5px;">
<?
$CUR_TIME=date("Y-m-d",time());
if(substr($ZBSJ_B,0,10) < $CUR_TIME)
   $ZHIBAN_STATUS = "<font color='red'>"._("时间已过")."</font><br>";
else if(substr($ZBSJ_B,0,10) == $CUR_TIME)
   $ZHIBAN_STATUS = "<font color='red'>"._("今天值班")."</font><br>";
else
   $ZHIBAN_STATUS = "<font color='red'>"._("未到时间")."</font><br>";
   
echo $ZHIBANREN_NAME."<b>"._("  排班类型：")."</b>".get_code_name($PAIBAN_TYPE,"PAIBAN_TYPE")."<br>";
echo "<b>"._("  值班时间:")."</b>".$ZBSJ_B." - ".$ZBSJ_E." <br> $ZHIBAN_STATUS<br>";
echo "<b>$PAIBAN_APR_NAME"._("  值班类型：")."</b>".get_code_name($ZHIBAN_TYPE,"ZHIBAN_TYPE")."<br>";
?>
<hr>
<?
echo "<b>"._("值班要求:")."</b>".$ZBYQ."<br>";
echo "<b>"._("备注:")."</b>".$BEIZHU."<br>";

if($ZB_RZ!="")
{
    echo "<hr>";
    echo str_replace("\n","<br>",$ZB_RZ);
}
?>
</div>
</body>
</html>
