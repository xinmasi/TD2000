<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("薪酬数据提交");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query = "SELECT USER_ID from USER where DEPT_ID='$DEPT_ID'";
$cursor= exequery(TD::conn(),$query);
$USER_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID=$ROW["USER_ID"];
  $OPERATION_ID=$USER_ID."_OPERATION";

  $TMP0 = $USER_ID."_ALL_BASE";
  $TMP1 = $USER_ID."_PENSION_BASE";
  $TMP2 = $USER_ID."_PENSION_U";
  $TMP3 = $USER_ID."_PENSION_P";
  $TMP4 = $USER_ID."_MEDICAL_BASE";
  $TMP5 = $USER_ID."_MEDICAL_U";
  $TMP6 = $USER_ID."_MEDICAL_P";
  $TMP7 = $USER_ID."_FERTILITY_BASE";
  $TMP8 = $USER_ID."_FERTILITY_U";
  $TMP9 = $USER_ID."_UNEMPLOYMENT_BASE";
  $TMP10 = $USER_ID."_UNEMPLOYMENT_U";
  $TMP11 = $USER_ID."_UNEMPLOYMENT_P";
  $TMP12 = $USER_ID."_INJURIES_BASE";
  $TMP13 = $USER_ID."_INJURIES_U";
  $TMP14 = $USER_ID."_HOUSING_BASE";
  $TMP15 = $USER_ID."_HOUSING_U";
  $TMP16 = $USER_ID."_HOUSING_P";

 if($$OPERATION_ID==1)
 {
    $query1="insert into HR_SAL_DATA(USER_ID";
    $STYLE_ARRAY=explode(",",$STYLE);
    //print_r($STYLE_ARRAY);
    $ARRAY_COUNT=sizeof($STYLE_ARRAY);
    $COUNT=0;
    if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")
       $ARRAY_COUNT--;
    for($I=0;$I<$ARRAY_COUNT;$I++)
        $query1.=",S".$STYLE_ARRAY[$I];
    $query1.=",ALL_BASE,PENSION_BASE,PENSION_U,PENSION_P,MEDICAL_BASE,MEDICAL_U,MEDICAL_P,FERTILITY_BASE,FERTILITY_U,UNEMPLOYMENT_BASE,UNEMPLOYMENT_U,UNEMPLOYMENT_P,INJURIES_BASE,INJURIES_U,HOUSING_BASE,HOUSING_U,HOUSING_P";   
    $query1.=") values ('$USER_ID',";

    for($I=0;$I < $ARRAY_COUNT;$I++)
    {
       $STR=$USER_ID."_".$STYLE_ARRAY[$I];
       if($$STR=="")
          $$STR="0";
       $query1.="'".$$STR."',";
    }
    $query1.= "'".$$TMP0."',";    
    $query1.= "'".$$TMP1."',";
    $query1.= "'".$$TMP2."',";
    $query1.= "'".$$TMP3."',";  
    $query1.= "'".$$TMP4."',";  
    $query1.= "'".$$TMP5."',";  
    $query1.= "'".$$TMP6."',";  
    $query1.= "'".$$TMP7."',";  
    $query1.= "'".$$TMP8."',";  
    $query1.= "'".$$TMP9."',";  
    $query1.= "'".$$TMP10."',";  
    $query1.= "'".$$TMP11."',";  
    $query1.= "'".$$TMP12."',";  
    $query1.= "'".$$TMP13."',";  
    $query1.= "'".$$TMP14."',";  
    $query1.= "'".$$TMP15."',";   
    $query1.= "'".$$TMP16."'";   
    $query1.=")";          
 }
 else
 {
  	$query1="update HR_SAL_DATA set ";
  	$STYLE_ARRAY=explode(",",$STYLE);
  	$ARRAY_COUNT=sizeof($STYLE_ARRAY);
    if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")
       $ARRAY_COUNT--;
  	for($I=0; $I< $ARRAY_COUNT;$I++)
    {
    	 $STR="S".$STYLE_ARRAY[$I];
  	   $STR_VALUE=$USER_ID."_".$STYLE_ARRAY[$I];
  	   if($$STR_VALUE=="")
          $$STR_VALUE="0";
       $query1.=$STR."='".$$STR_VALUE."',";
    }

    $query1.= "ALL_BASE='".$$TMP0."',";     
    $query1.= "PENSION_BASE='".$$TMP1."',";
    $query1.= "PENSION_U='".$$TMP2."',"; 
    $query1.= "PENSION_P='".$$TMP3."',";     
    $query1.= "MEDICAL_BASE='".$$TMP4."',";     
    $query1.= "MEDICAL_U='".$$TMP5."',";
    $query1.= "MEDICAL_P='".$$TMP6."',";
    $query1.= "FERTILITY_BASE='".$$TMP7."',";
    $query1.= "FERTILITY_U='".$$TMP8."',";
    $query1.= "UNEMPLOYMENT_BASE='".$$TMP9."',";
    $query1.= "UNEMPLOYMENT_U='".$$TMP10."',";
    $query1.= "UNEMPLOYMENT_P='".$$TMP11."',";     
    $query1.= "INJURIES_BASE='".$$TMP12."',";     
    $query1.= "INJURIES_U='".$$TMP13."',";
    $query1.= "HOUSING_BASE='".$$TMP14."',";    
    $query1.= "HOUSING_U='".$$TMP15."',";     
    $query1.= "HOUSING_P='".$$TMP16."'";               
    $query1.=" where USER_ID='$USER_ID'";
 }
 //echo $query1;echo "<br>";
 exequery(TD::conn(),$query1);
}
header("location:wage_list.php?DEPT_ID=$DEPT_ID");
?>
</body>
</html>
