<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("会议管理");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css"/>
<script>
    function order_by(field,asc_desc)
    {
        window.location="manage1.php?&M_STATUS=<?=$M_STATUS?>&FIELD="+field+"&ASC_DESC="+asc_desc;
    }
</script>
<body class="bodycolor">
<?
if($ASC_DESC=="0" || !isset($ASC_DESC)) {
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
}else if($ASC_DESC=="1"){
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
}
//会议管理员
$query = "SELECT MEETING_OPERATOR,MEETING_IS_APPROVE from meeting_rule";
$cursor= exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $MEETING_OPERATOR = $ROW['MEETING_OPERATOR'];
    $MEETING_IS_APPROVE = $ROW['MEETING_IS_APPROVE'];
}
if($MEETING_IS_APPROVE == 1)
{
    $MEETING_OPERATORS = $_SESSION["LOGIN_USER_ID"];
    $MEETING_OPERATORS = strstr($MEETING_OPERATOR, $MEETING_OPERATORS);
}


$M_STATUS_DESC=_("待批周期性会议");  
$MEETING_COUNT=0;
if($_SESSION["LOGIN_USER_PRIV"]==1 || (empty($MEETING_OPERATORS) != true))
   $query = "SELECT CYCLE_NO from MEETING where M_STATUS='0' and CYCLE_NO!='0' group by CYCLE_NO";
else
   $query = "SELECT CYCLE_NO from MEETING where M_STATUS='0' and M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."' and CYCLE_NO!='0' group by CYCLE_NO";

$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
   $MEETING_COUNT++;

if($MEETING_COUNT==0)
{
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
   <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="22" height="18"><span class="big3"> <?=$M_STATUS_DESC?></span>
   </td>
 </tr>
</table>
<br />
<?
   Message("",_("无").$M_STATUS_DESC);
   exit;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small"  align="center">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="22" height="18"><span class="big3"> <?=$M_STATUS_DESC?></span>
  </td>
  <?
    $MSG_MEETING_COUNT=sprintf(_("共%s条会议记录"),"<span class='big4'>&nbsp;".$MEETING_COUNT."</span>&nbsp;");
  ?>
  <td valign="bottom" class="small1"><?=$MSG_MEETING_COUNT ?>
  </td><td align="right" valign="bottom" class="small1"></td>
</tr>
</table>
<table width="95%" class="TableList" align="center">
<tr class="TableHeader">
  <td nowrap align="center"><?=_("名称")?></td>
  <td nowrap align="center"><?=_("申请人")?></td>
  <td nowrap align="center" onClick="order_by('START_TIME','<?if($FIELD=="START_TIME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><?=_("开始时间")?><?if($FIELD=="START_TIME"||$FIELD=="") echo $ORDER_IMG;?></td>
  <td nowrap align="center"><?=_("会议室")?></td>
  <td nowrap align="center"><?=_("操作")?></td>
</tr>

<?
if($M_ID)
{
    //修改事务提醒状态--yc
    update_sms_status('8',$M_ID);
}

//============================ 显示会议情况 =======================================
//$query = "SELECT * from MEETING where M_STATUS='0' and CYCLE !='0' group by CYCLE_NO";
$query = "SELECT * from MEETING where M_STATUS='0' and CYCLE_NO !='' GROUP BY CYCLE_NO";
if($FIELD=="START_TIME")
{
    if($ASC_DESC == "0"){
        $query.=" order by M_START asc ";
    }else if($ASC_DESC == "1"){
        $query.=" order by M_START desc ";
    }
}
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
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
   $CYCLE_NO=$ROW["CYCLE_NO"];
   $CYCLE=$ROW["CYCLE"];

   $query1 = "SELECT * from USER where USER_ID='$M_PROPOSER'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $USER_NAME=$ROW1["USER_NAME"];     
   
   if($MEETING_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
   $M_ROOM_NAME="";
   $query2 = "SELECT * from MEETING_ROOM where MR_ID='$M_ROOM'";
   $cursor2= exequery(TD::conn(),$query2);
   
   	if($ROW2=mysql_fetch_array($cursor2))
   	  $M_ROOM_NAME=$ROW2["MR_NAME"];

?>
<tr class="<?=$TableLine?>">
   <td nowrap ><?=$M_NAME?></td>
   <td align="center"><?=$USER_NAME?></td>
   <td align="center"><?=$M_START?></td>
   <td align="center"><?=$M_ROOM_NAME?></td>
   <td nowrap align="center"><a href="manage_cycle.php?CYCLE=<?=$CYCLE?>&CYCLE_NO=<?=$CYCLE_NO?>"><?=_("周期性会议审批")?></a></td>
</tr>
<?
}
?>
</table>
</body>
</html>
