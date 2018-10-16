<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$query = "SELECT * from MEETING_EQUIPMENT where GROUP_YN='0' and EQUIPMENT_STATUS='1' and MR_ID='$M_ROOM' order by EQUIPMENT_NO";
$cursor= exequery(TD::conn(),$query);
$EQUIPMENT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
	$EQUIPMENT_COUNT++;
	$EQUIPMENT_ID=$ROW["EQUIPMENT_ID"];
  $EQUIPMENT_NO=$ROW["EQUIPMENT_NO"];
  $EQUIPMENT_NAME=$ROW["EQUIPMENT_NAME"];
  $REMARK=strip_tags($ROW["REMARK"]);  
?>   
    <input type="checkbox" name="SB_<?=$EQUIPMENT_ID?>" id="SB_<?=$EQUIPMENT_ID?>" value="<?=$EQUIPMENT_ID?>"><label title="<?=$REMARK?>" for="SB_<?=$EQUIPMENT_ID?>"><?=$EQUIPMENT_NAME?></label>
<?
}

$query = "SELECT * from MEETING_EQUIPMENT where GROUP_YN='1' and EQUIPMENT_STATUS='1' and MR_ID='$M_ROOM' group by GROUP_NO";
$cursor= exequery(TD::conn(),$query);
$EQUIPMENT_COUNT2=0;
while($ROW=mysql_fetch_array($cursor))
{
	 $EQUIPMENT_COUNT++;
	 $EQUIPMENT_COUNT2++;
	 $GROUP_NO1=$ROW["GROUP_NO"];

   $query1 = "SELECT * from MEETING_EQUIPMENT where GROUP_YN='1' and EQUIPMENT_STATUS='1' and MR_ID='$M_ROOM' and GROUP_NO='$GROUP_NO1'";
   $cursor1= exequery(TD::conn(),$query1);
   $COUNT[$GROUP_NO1]=0;
   while($ROW1=mysql_fetch_array($cursor1))
   {
   	  $COUNT[$GROUP_NO1]++;
   	  $EQUIPMENT_NAME1=$ROW1["EQUIPMENT_NAME"];
   	  $EQUIPMENT_ID1=$ROW1["EQUIPMENT_ID"];   	  
   	  $REMARK1=$ROW1["REMARK"];
   	  if($COUNT[$GROUP_NO1]==1)
   	  {
   	     echo "&nbsp;&nbsp;<select name=\"SB_".$EQUIPMENT_ID1."\" class=\"BigSelect\">";
   	     echo "  <option value=\"\">"._("Ñ¡Ôñ").get_code_name($GROUP_NO1,"MEETING_EQUIPMENT")."</option>";
   	  }
?>   
      <option value="<?=$EQUIPMENT_ID1?>" title="<?=$REMARK1?>"><?=$EQUIPMENT_NAME1?></option>
<?
   }
   echo "</select>";
}
if($EQUIPMENT_COUNT==0)
   echo _("ÎÞ¼ÇÂ¼");
?>	