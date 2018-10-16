<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$ITEMS_IN_PAGE=10;
if(!isset($start) || $start=="")
   $start=0;
   
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

$HTML_PAGE_TITLE = _("会议管理");
include_once("inc/header.inc.php");
?>


<script>
window.setTimeout('this.location.reload();',40000);

function delete_meeting(M_ID,M_STATUS)
{
    msg='<?=_("确认要删除该会议吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?M_ID_STR=" + M_ID + "&M_STATUS=" + M_STATUS;
        window.location=URL;
    }
}

function confirm_end(M_ID)
{
 msg='<?=_("确认要结束该会议吗？")?>';
 if(window.confirm(msg))
 {
   URL="checkup.php?M_ID=" + M_ID + "&M_STATUS=" + 4;
   window.location=URL;
 }
}
</script>


<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----自动开始----------
$query = "SELECT * from MEETING  where M_STATUS=1";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $M_ID3=$ROW["M_ID"];
   $M_START3=$ROW["M_START"];
   if($CUR_TIME>=$M_START3)
   {
   	  exequery(TD::conn(),"UPDATE MEETING SET M_STATUS= '2' WHERE M_ID='$M_ID3'");
   }
}

//-----自动结束----------
$query = "SELECT * from MEETING  where M_STATUS=2";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $M_ID3=$ROW["M_ID"];
   $M_END3=$ROW["M_END"];
   if($CUR_TIME>=$M_END3)
   {
   	  exequery(TD::conn(),"UPDATE MEETING SET M_STATUS= '4' WHERE M_ID='$M_ID3'");
   }
}

if($M_STATUS==0)
   $M_STATUS_DESC=_("待批会议");
elseif($M_STATUS==1)
   $M_STATUS_DESC=_("已准会议");
elseif($M_STATUS==2)
   $M_STATUS_DESC=_("进行中会议");
elseif($M_STATUS==3)
   $M_STATUS_DESC=_("未批准会议");
elseif($M_STATUS==4)
   $M_STATUS_DESC=_("已结束");

if($_SESSION["LOGIN_USER_PRIV"]==1)
   $query = "SELECT count(*) from MEETING where M_STATUS='$M_STATUS'";
else
   $query = "SELECT count(*) from MEETING where M_STATUS='$M_STATUS' and  M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."'";

   $cursor= exequery(TD::conn(),$query);
   $MEETING_COUNT=0;
   if($ROW=mysql_fetch_array($cursor))
      $MEETING_COUNT=$ROW[0];
   if($MEETING_COUNT==0)
{
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
   <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="22" height="18"><span class="big3"> <?=$M_STATUS_DESC?></span>
   </td>
 </tr>
</table>

<?
   Message("",_("无").$M_STATUS_DESC);
   exit;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="22" height="18"><span class="big3"> <?=$M_STATUS_DESC?></span>
  </td>
  <?
  	$MSG_MEETING_COUNT=sprintf(_("共%s条会议记录"),"<span class='big4'>&nbsp;".$MEETING_COUNT."</span>&nbsp;");
  ?>
  <td valign="bottom" class="small1"><?=$MSG_MEETING_COUNT ?>
  </td><td align="right" valign="bottom" class="small1"><?=page_bar($start,$MEETING_COUNT,$ITEMS_IN_PAGE)?></td>
</tr>
</table>
<table width="95%" class="TableList">
<tr class="TableHeader">
  <td nowrap align="center"><?=_("名称")?></td>
  <td nowrap align="center"><?=_("申请人")?></td>
  <td nowrap align="center"><?=_("开始时间")?></td>
  <td nowrap align="center"><?=_("会议室")?></td>
<?
if($M_STATUS==0)
{
?>
     <td nowrap align="center"><?=_("预约状态")?></td>
<?
}
?>
   <td nowrap align="center"><?=_("操作")?></td>
</tr>

<?
//============================ 显示会议情况 =======================================
if($_SESSION["LOGIN_USER_PRIV"]==1)
 $query = "SELECT * from MEETING where M_STATUS='$M_STATUS' limit $start,$ITEMS_IN_PAGE";
else
 $query = "SELECT * from MEETING where M_STATUS='$M_STATUS' and  M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."' limit $start,$ITEMS_IN_PAGE";

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
    $M_ATTENDEE_OUT=$ROW["M_ATTENDEE_OUT"];

    $query1 = "SELECT * from USER where USER_ID='$M_PROPOSER'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
      $USER_NAME=$ROW1["USER_NAME"];

    $USER_NAME2="";
    $TOK=strtok($M_ATTENDEE,",");
    while($TOK!="")
    {
     $query2 = "SELECT * from USER where USER_ID='$TOK'";
     $cursor2= exequery(TD::conn(),$query2);
     if($ROW=mysql_fetch_array($cursor2))
        $USER_NAME2.=$ROW["USER_NAME"].",";
     $TOK=strtok(",");
     }
    $M_ATTENDEE_NAME=_("内部：$USER_NAME2 <br>外部：$M_ATTENDEE_OUT");

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
?>
   <tr class="<?=$TableLine?>">
     <td nowrap align="center"><?=$M_NAME?></td>
     <td align="center"><?=$USER_NAME?></td>
     <td align="center"><?=$M_START?></td>
     <td align="center"><?=$M_ROOM_NAME?></td>
<?
if($M_STATUS==0)
{
?>
     <td nowrap align="center">
<?
$SS=substr(check_room($M_ID,$M_ROOM,$M_START,$M_END), 0, 1);
if(!is_number($SS))
  echo _("无冲突");
else
{
?>
  <a href="javascript:;" onClick="window.open('conflict_detail.php?M_ID=<?=check_room($M_ID,$M_ROOM,$M_START,$M_END)?>','','height=350,width=450,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=150,resizable=yes');"><font color="red"><?=_("预约冲突")?></font></a>
<?
}
?>
     </td>
<?
}
?>
     <td nowrap align="center"><a href="javascript:;" onClick="window.open('../query/meeting_detail.php?M_ID=<?=$M_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
     <a href="javascript:;" onClick="window.open('../apply/select.php?MR_ID=<?=$M_ROOM?>&ACTION=SEE','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=100,resizable=yes');"><?=_("预约情况")?></a><br>

<?
if($M_STATUS==0)
{
   	echo _("<a href=\"../apply/new.php?M_ID=$M_ID&FLAG=1\">修改</a>&nbsp;");
    echo _("<a href='checkup.php?M_ID=$M_ID&M_STATUS=1'> 批准</a>&nbsp;");
    echo _("<a href='checkup.php?M_ID=$M_ID&M_STATUS=3'> 不准</a>&nbsp;");
}
elseif($M_STATUS==1)
{
    echo _("<a href=\"../apply/new.php?M_ID=$M_ID&FLAG=1\">修改</a>&nbsp;");
    echo _("<a href='checkup.php?M_ID=$M_ID&M_STATUS=0'> 撤销</a>&nbsp;");
}
elseif($M_STATUS==2)
{
?>
     <a href="javascript:;" onClick="window.open('../apply/modify.php?M_ID=<?=$M_ID?>','','height=300,width=450,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=150,resizable=no');"><?=_("修改")?></a>&nbsp;
     <a href="javascript:;" onClick="window.open('../summary/summary.php?M_ID=<?=$M_ID?>','','height=540,width=740,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=150,top=100,resizable=yes');"><?=_("会议纪要")?></a>&nbsp;
     <a href="javascript:confirm_end('<?=$M_ID?>');"> <?=_("结束")?></a>&nbsp;
<?
}
elseif($M_STATUS==3)
    echo _("<a href='checkup.php?M_ID=$M_ID&M_STATUS=1'> 批准</a>&nbsp;");
elseif($M_STATUS==4)
{
?>
   <a href="javascript:;" onClick="window.open('../summary/summary.php?M_ID=<?=$M_ID?>','','height=540,width=740,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=150,top=100,resizable=yes');"><?=_("会议纪要")?></a>&nbsp;
<?
}
?>
  <a href="javascript:delete_meeting('<?=$M_ID?>','<?=$M_STATUS?>');"> <?=_("删除")?></a>
  </td>
   </tr>
<?
}//while
?>
</table>
</body>

</html>
