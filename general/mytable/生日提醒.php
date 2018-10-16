<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("生日提醒");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'hr';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$COUNT1=0;
$COUNT2=0;

include_once("inc/chinese_date.php");
$BIRTHDAY_ARRAY1 = $BIRTHDAY_ARRAY2 = array();
$query = "SELECT USER_NAME,BIRTHDAY,IS_LUNAR from USER where (NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0') and DEPT_ID!=0 order by SUBSTRING(BIRTHDAY,6,5),USER_NAME ASC";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $USER_NAME=$ROW["USER_NAME"];
   $BIRTHDAY=$ROW["BIRTHDAY"];
   $IS_LUNAR=$ROW["IS_LUNAR"];

   if($IS_LUNAR==1)
   {
   	  $CUR_DATE=date("Y-m-d",time());
      $END_DATE = date("Y-m-d",strtotime("+30 days"));
      
      $CUR_DATE_LUNAR=Solar2LunarDate($CUR_DATE);
	  $CUR_DATE=date("Y-m-d",strtotime($CUR_DATE_LUNAR));
	  
	  $END_DATE_LUNAR=Solar2LunarDate($END_DATE);
	  $END_DATE=date("Y-m-d",strtotime($END_DATE_LUNAR));

    }else{
      $CUR_DATE=date("Y-m-d",time());
      $END_DATE = date("Y-m-d",strtotime("+30 days"));
  	}

   if(substr($BIRTHDAY,5,5) >= "01-01" && substr($BIRTHDAY,5,5) <= "01-31")
      $DATA=substr($END_DATE,0,4).substr($BIRTHDAY,4,6);
   else
      $DATA=substr($CUR_DATE,0,4).substr($BIRTHDAY,4,6);

   if($DATA< $CUR_DATE || $DATA> $END_DATE || $BIRTHDAY=="1900-01-01 00:00:00" || $BIRTHDAY=="0000-00-00 00:00:00")
      continue;
   //echo $CUR_DATE." ".$DATA."<br>";
   if($CUR_DATE==$DATA)
   {
   	  $COUNT1++;
      $PERSON_STR1.="<img src='".MYOA_STATIC_SERVER."/static/images/cake.png' align='absMiddle'>".$USER_NAME.$POSTFIX;
   }
   else
   {
   	  $COUNT2++;
   	  if($IS_LUNAR==1)
   	     $PERSON_STR2.="<img src='".MYOA_STATIC_SERVER."/static/images/cake.png' align='absMiddle'>".$USER_NAME."("._("农历").date("m-d",strtotime($DATA)).")".$POSTFIX;
   	  else
         $PERSON_STR2.="<img src='".MYOA_STATIC_SERVER."/static/images/cake.png' align='absMiddle'>".$USER_NAME."(".date("m-d",strtotime($DATA)).")".$POSTFIX;
      if(date("m",time())==12)
      {
      	 $M_D = date("m-d",strtotime($DATA));
         $ARRAY_KEY = "k".$M_D.$COUNT2;
         if(substr($M_D,0,2)==12)
            $BIRTHDAY_ARRAY1["$ARRAY_KEY"]= $USER_NAME;
         else
            $BIRTHDAY_ARRAY2["$ARRAY_KEY"]= $USER_NAME;
      }
   	}
}

if(date("m",time())==12)
{
	$PERSON_STR2="";
	if(is_array($BIRTHDAY_ARRAY1) && !empty($BIRTHDAY_ARRAY1))
  foreach($BIRTHDAY_ARRAY1 as $key => $value)
  	 $PERSON_STR2.="<img src='".MYOA_STATIC_SERVER."/static/images/cake.png' align='absMiddle'>".$value."(".substr($key,1,5).")".$POSTFIX;
	if(is_array($BIRTHDAY_ARRAY2) && !empty($BIRTHDAY_ARRAY2))
  foreach($BIRTHDAY_ARRAY2 as $key => $value)
  	 $PERSON_STR2.="<img src='".MYOA_STATIC_SERVER."/static/images/cake.png' align='absMiddle'>".$value."(".substr($key,1,5).")".$POSTFIX;

}

$PERSON_STR1 = substr($PERSON_STR1,0,-strlen($POSTFIX));
$PERSON_STR2 = substr($PERSON_STR2,0,-strlen($POSTFIX));

if($COUNT1>0)
   $MODULE_BODY.=sprintf(_("今日生日：%s，生日快乐!"), $PERSON_STR1)."<br> ";
if($COUNT2>0)
   $MODULE_BODY.=_("近期生日：").$PERSON_STR2;

if($COUNT1==0 && $COUNT2==0)
   $MODULE_BODY.= "<li>"._("近期无生日")."</li>";
}
?>