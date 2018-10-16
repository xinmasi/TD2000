<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
ob_end_clean();

$query = "SELECT OPERATOR from MEETING_ROOM where MR_ID='$M_ROOM'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $OPERATOR=$ROW["OPERATOR"];
   
   if($OPERATOR!="")
   {     
      $OPERATOR_ARRAY = explode(",",$OPERATOR);
      $OPERATOR_ARRAY_COUNT = count($OPERATOR_ARRAY);
      if($OPERATOR_ARRAY[$OPERATOR_ARRAY_COUNT-1]=="")$OPERATOR_ARRAY_COUNT--;
      echo "<select name=\"M_MANAGER\" class=\"BigSelect\">";
      for($I=0; $I < $OPERATOR_ARRAY_COUNT;$I++)
      {
?>    
            <option value="<?=$OPERATOR_ARRAY[$I]?>"><?=substr(getUserNameByID($OPERATOR_ARRAY[$I]),0,-1)?></option>
<?    
      }
   }   
   else
   {
     
      $SYS_PARA_ARRAY=get_sys_para("MEETING_OPERATOR");
      $OPERATOR=$SYS_PARA_ARRAY["MEETING_OPERATOR"];
      if($OPERATOR!="")
      { 
         $OPERATOR_ARRAY = explode(",",$OPERATOR);
         $OPERATOR_ARRAY_COUNT = count($OPERATOR_ARRAY);
         if($OPERATOR_ARRAY[$OPERATOR_ARRAY_COUNT-1]=="")$OPERATOR_ARRAY_COUNT--;
         echo "<select name=\"M_MANAGER\" class=\"BigSelect\">";
         for($I=0; $I < $OPERATOR_ARRAY_COUNT;$I++)
         {
?>    
               <option value="<?=$OPERATOR_ARRAY[$I]?>"><?=substr(getUserNameByID($OPERATOR_ARRAY[$I]),0,-1)?></option>
<?    
         }
      }
   }
echo "</select>";
}

$POSTFIX = _("£¬");
$query = "SELECT USER_ID,USER_NAME from USER,USER_ONLINE where USER.UID=USER_ONLINE.UID and USER_ID!='' and find_in_set(USER_ID,'$OPERATOR') group by USER_ONLINE.UID order by USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID=$ROW["USER_ID"];
  $USER_NAME=$ROW["USER_NAME"];
  $ONLINE_USER_NAME.=$USER_NAME.$POSTFIX;
}

echo "|".substr($ONLINE_USER_NAME,0,-strlen($POSTFIX));
?>