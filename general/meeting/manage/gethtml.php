<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
ob_end_clean();
echo '<table width="95%" class="TableList" align="center">';
echo '<tr class="TableHeader">';
echo '<td nowrap align="center">'._("选择").'</td>';
echo '<td nowrap align="center">'._("名称").'</td>';
echo '<td nowrap align="center">'._("申请人").'</td>';
echo '<td nowrap align="center">'._("开始时间").'</td>';
echo '<td nowrap align="center">'._("会议室").'</td>';
echo '<td nowrap align="center">'._("预约状态").'</td>';
echo '<td nowrap align="center">'._("操作").'</td>';
echo '</tr>';
//============================ 显示会议情况 =======================================
function check_room($M_ID,$M_ROOM,$M_START,$M_END)
{
   $query="select * from MEETING where M_ID!='$M_ID' and M_ROOM='$M_ROOM' and (M_STATUS=1 or M_STATUS=2)";
   $cursor=exequery(TD::conn(),$query);
   $COUNT=0;
   while($ROW=mysql_fetch_array($cursor))
   {
    $M_START1=$ROW["M_START"];
    $M_END1=$ROW["M_END"];
    if(($M_START1>=$M_START and $M_END1<=$M_END) or ($M_START1<$M_START and $M_END1>$M_START) or ($M_START1<$M_END and $M_END1>$M_END) or ($M_START1<$M_START and $M_END1>$M_END))
     {
     	  $COUNT++;
        $M_IDD=$M_IDD.$ROW["M_ID"].",";
     }
   }
   $M_ID=$M_IDD;
   if($COUNT>=1)
      return $M_ID;
   else
      return "#";
}

if($_SESSION["LOGIN_USER_PRIV"]==1)
   $query = "SELECT * from MEETING where M_STATUS='0' and CYCLE='1' and CYCLE_NO='$CYCLE_NO'";

else
   $query = "SELECT * from MEETING where M_STATUS='0' and CYCLE='1' and CYCLE_NO='$CYCLE_NO' and M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."'";
   
$cursor= exequery(TD::conn(),$query);
$MEETING_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $MEETING_COUNT++;

   $M_ID=$ROW["M_ID"];
   $M_NAME=$ROW["M_NAME"];
   $M_TOPIC=$ROW["M_TOPIC"];
   $M_PROPOSER=$ROW["M_PROPOSER"];
   $M_REQUEST_TIME=$ROW["M_REQUEST_TIME"];
   $M_ATTENDEE=$ROW["M_ATTENDEE"];
   $M_START =$ROW["M_START"];
   $M_END=$ROW["M_END"];
   $M_ROOM=$ROW["M_ROOM"];
   $M_STATUS=$ROW["M_STATUS"];
   $M_MANAGER=$ROW["M_MANAGER"];
   $WEEK=date('w',strtotime($M_START));

	 if($WEEK=='1' && $W1==""  )
	    continue;                              
	 if($WEEK=='2' && $W2==""  )
	    continue;                              
	 if($WEEK=='3' && $W3==""  )
	    continue;                                
	 if($WEEK=='4' && $W4==""  )
	    continue;                                  
	 if($WEEK=='5' && $W5==""  )
	    continue;                             
	 if($WEEK=='6' && $W6==""  )
	    continue;                             
	 if($WEEK=='0' && $W7==""  )
	    continue; 
   
   $query1 = "SELECT * from USER where USER_ID='$M_PROPOSER'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
     $USER_NAME=$ROW1["USER_NAME"];

   if($M_START=="0000-00-00 00:00:00")
      $M_START="";
   if($M_END=="0000-00-00 00:00:00")
      $M_END="";

   if($MEETING_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";

   $query = "SELECT * from MEETING_ROOM where MR_ID='$M_ROOM'";
   $cursor2= exequery(TD::conn(),$query);
   if($ROW2=mysql_fetch_array($cursor2))
      $M_ROOM_NAME=$ROW2["MR_NAME"];

echo '<tr class="'.$TableLine.'">';

$SS=substr(check_room($M_ID,$M_ROOM,$M_START,$M_END), 0, 1);


if(!is_number($SS))
   echo '<td>&nbsp;<input type="checkbox" name="meeting_select" value="'.$M_ID.'" onClick="check_one(self);"></td>';
else
   echo '<td></td>';	 
echo '<td nowrap align="center">'.$M_NAME.'</td>';
echo '<td align="center">'.$USER_NAME.'</td>';
echo '<td align="center">'.$M_START.'</td>';
echo '<td align="center">'.$M_ROOM_NAME.'</td>';
if($M_STATUS==0)
{
echo '<td nowrap align="center">';
if(!is_number($SS))
   echo _("无冲突");
else
   echo '<a href="javascript:;" onClick="window.open("conflict_detail.php?M_ID='.check_room($M_ID,$M_ROOM,$M_START,$M_END).'","","height=350,width=450,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=150,resizable=yes");"><font color="red">'._("预约冲突").'</font></a>';

echo '</td>';

}

echo '<td nowrap align="center"><a href="javascript:;" onClick="window.open(\'../query/meeting_detail.php?M_ID='.$M_ID.'\',\'\',\'height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes\');">'._("详细信息").'</a>&nbsp;';
echo '<a href="javascript:;" onClick="window.open(\'../apply/select.php?MR_ID=$M_ROOM&ACTION=SEE\',\'\',\'height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=100,resizable=yes\');">'._("预约情况").'</a><br>';
echo "<a href=\"../apply/new.php?M_ID=$M_ID&FLAG=1\">"._("修改")."</a>&nbsp;";
if(!is_number($SS))
echo "<a href='checkup.php?M_ID=$M_ID&M_STATUS=1'> "._("批准")."</a>&nbsp;";
echo "<a href='checkup.php?M_ID=$M_ID&M_STATUS=3'> "._("不准")."</a>&nbsp;";
echo '<a href="javascript:delete_meeting(\''.$M_ID.'\',\''.$M_STATUS.'\');"> '._("删除").'</a>';
echo '</td>';
echo '</tr>';

}//while
echo '</div>';
echo '<tr class="TableControl">';
echo '<td colspan="19">';
echo '<input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for">'._("全选").'</label> &nbsp;';
echo '<a href="javascript:check_up();" title="'._("批量批准待批会议").'"><img src="<?=MYOA_STATIC_SERVER?>/static/images/user_group.gif" align="absMiddle">'._("批量批准待批会议").'</a>&nbsp;';
echo '<a href="javascript:check_up_deny();" title="'._("批量不批准待批会议").'"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle">'._("批量不批准待批会议").'</a>&nbsp;';
echo '</td>';
echo '</tr>';
echo '</table>';