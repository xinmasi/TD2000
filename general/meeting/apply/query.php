<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/flow_hook.php");

$ITEMS_IN_PAGE=10;
if(!isset($start) || $start=="")
   $start=0;
   

$HTML_PAGE_TITLE = _("会议申请");
include_once("inc/header.inc.php");
?>



<script>
window.setTimeout('this.location.reload();',40000);

function delete_meeting(M_ID,M_STATUS)
{
 msg='<?=_("确认要删除该会议吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?M_ID=" + M_ID + "&M_STATUS=" + M_STATUS;
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
function form_view(RUN_ID) 
{ 
window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0"); 
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
   $M_ID3=intval($M_ID3);
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
   $M_ID3=intval($M_ID3);
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

if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!="1")
{
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    if($dept_id)
    {
        $dept_str = $dept_id;
    }
    else
    {
        $dept_str = $_SESSION["LOGIN_DEPT_ID"];
    }
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id != "") {
        $query = "SELECT count(*) from MEETING where M_STATUS='$M_STATUS' and find_in_set(M_PROPOSER,'".$user_id."')";
    }else{
        $query = "SELECT count(*) from MEETING where M_STATUS='$M_STATUS' and M_PROPOSER='".$_SESSION["LOGIN_USER_ID"]."'";
    }
}else{
    $query = "SELECT count(*) from MEETING where M_STATUS='$M_STATUS' and M_PROPOSER='".$_SESSION["LOGIN_USER_ID"]."'";
}
$cursor= exequery(TD::conn(),$query);
$MEETING_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $MEETING_COUNT=$ROW[0];
if($MEETING_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big">
      <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="22" height="18"><span class="big3"> <?=$M_STATUS_DESC?></span>
    </td>
  </tr>
</table>
<br />
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
  	$STR_MEETING_COUNT=sprintf(_("共%s条会议记录"),"<span class='big4'>&nbsp;".$MEETING_COUNT."</span>&nbsp;");
  ?>
  <td valign="bottom" class="small1"><?=$STR_MEETING_COUNT?></td>
  <td align="right" valign="bottom" class="small1"><?=page_bar($start,$MEETING_COUNT,$ITEMS_IN_PAGE)?></td>
</tr>
</table>

<table class="TableBlock" width="95%" align="center">
<tr class="TableHeader">
  <td nowrap align="center"><?=_("名称")?></td>
  <td nowrap align="center"><?=_("申请人")?></td>
  <td align="center"><?=_("出席人员")?></td>
  <td nowrap align="center"><?=_("开始时间")?></td>
  <td align="center"><?=_("结束时间")?></td>
  <td nowrap align="center"><?=_("会议室")?></td>
  <td nowrap align="center"><?=_("操作")?></td>
</tr>

<?
//============================ 显示会议情况 =======================================
//$query = "SELECT * from MEETING,USER where M_STATUS='$M_STATUS' and M_PROPOSER='".$_SESSION["LOGIN_USER_ID"]."' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by M_START asc limit $start,$ITEMS_IN_PAGE ";
if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
{
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    if($dept_id)
    {
        $dept_str = $dept_id;
    }
    else
    {
        $dept_str = $_SESSION["LOGIN_DEPT_ID"];
    }
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id != "") {
        $query = "SELECT * from MEETING,USER where M_STATUS='$M_STATUS' and FIND_IN_SET(M_PROPOSER,'".$user_id."') and FIND_IN_SET(USER_ID,'".$user_id."') order by M_START asc limit $start,$ITEMS_IN_PAGE ";
    }else{
        $query = "SELECT * from MEETING,USER where M_STATUS='$M_STATUS' and M_PROPOSER='".$_SESSION["LOGIN_USER_ID"]."' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by M_START asc limit $start,$ITEMS_IN_PAGE ";
    }
}else{
    $query = "SELECT * from MEETING,USER where M_STATUS='$M_STATUS' and M_PROPOSER='".$_SESSION["LOGIN_USER_ID"]."' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by M_START asc limit $start,$ITEMS_IN_PAGE ";
}
$cursor= exequery(TD::conn(),$query);
$MEETING_COUNT=0;
while($ROW=mysql_fetch_array($cursor)) {
    $MEETING_COUNT++;

    $M_ID = $ROW["M_ID"];
    $M_NAME = $ROW["M_NAME"];
    $M_TOPIC = $ROW["M_TOPIC"];
    $M_PROPOSER = $ROW["M_PROPOSER"];
    $M_REQUEST_TIME = $ROW["M_REQUEST_TIME"];
    $M_ATTENDEE = $ROW["M_ATTENDEE"];
    $M_START = $ROW["M_START"];
    $M_END = $ROW["M_END"];
    $M_ROOM = $ROW["M_ROOM"];
    $M_STATUS = $ROW["M_STATUS"];
    $M_MANAGER = $ROW["M_MANAGER"];
    $M_ATTENDEE_OUT = $ROW["M_ATTENDEE_OUT"];

    $query1 = "SELECT USER_NAME from USER where USER_ID='$M_PROPOSER'";
    $cursor1 = exequery(TD::conn(), $query1);
    if ($ROW1 = mysql_fetch_array($cursor1))
        $USER_NAME = $ROW1["USER_NAME"];

    $USER_NAME2 = "";
    $TOK = strtok($M_ATTENDEE, ",");
    while ($TOK != "") {
        $query2 = "SELECT USER_NAME from USER where USER_ID='$TOK'";
        $cursor2 = exequery(TD::conn(), $query2);
        if ($ROW = mysql_fetch_array($cursor2))
            $USER_NAME2 .= $ROW["USER_NAME"] . ",";
        $TOK = strtok(",");
    }
    $USER_NAME2 = substr($USER_NAME2, 0, -1);
    $M_ATTENDEE_NAME = _("内部：$USER_NAME2 <br>外部：$M_ATTENDEE_OUT");

    if ($M_START == "0000-00-00 00:00:00")
        $M_START = "";
    if ($M_END == "0000-00-00 00:00:00")
        $M_END = "";

    if ($MEETING_COUNT % 2 == 1)
        $TableLine = "TableLine1";
    else
        $TableLine = "TableLine2";
    $M_ROOM_NAME = "";
    $query2 = "SELECT MR_NAME from MEETING_ROOM where MR_ID='$M_ROOM'";
    $cursor2 = exequery(TD::conn(), $query2);
    if ($ROW2 = mysql_fetch_array($cursor2)) {
        $M_ROOM_NAME = $ROW2["MR_NAME"];
        ?>
        <tr class="<?= $TableLine ?>">
        <td nowrap align="center"><?= $M_NAME ?></td>
        <td align="center"><?= $USER_NAME ?></td>
        <td align="left" style="word-break:break-all;"><?= $M_ATTENDEE_NAME ?></td>
        <td align="center"><?= $M_START ?></td>
        <td align="center"><?= $M_END ?></td>
        <td align="center"><?= $M_ROOM_NAME ?></td>
        <td nowrap align="center">
        <a href="#"
           onClick="window.open('../query/meeting_detail.php?M_ID=<?= $M_ID ?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?= _("详细信息") ?></a>
        &nbsp;
        <?
    }
    if ($M_STATUS == 2) {
    ?>
        <a href="javascript:confirm_end('<?= $M_ID ?>');"> <?= _("结束") ?></a>&nbsp;
        <a href="javascript:;"
           onClick="window.open('../apply/modify.php?M_ID=<?= $M_ID ?>','','height=260,width=420,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=150,resizable=no');"><?= _("修改") ?></a>&nbsp;
        <a href="javascript:;"
           onClick="window.open('../query/summary.php?M_ID=<?= $M_ID ?>','','height=570,width=740,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=150,top=80,resizable=yes');"><?= _("会议纪要") ?></a>&nbsp;
        <?
    }
    $is_run_hook = is_run_hook("M_ID", $M_ID);
    if ($is_run_hook != 0) {
        ?>
        <a href="javascript:;" onClick="form_view('<?= $is_run_hook ?>')"><?= _("查看流程") ?></a>
        <?
    }else{
        if ($M_STATUS == 0 || $M_STATUS == 3) {
            echo sprintf("<a href=\"new.php?M_ID=$M_ID&FLAG=1\">" . _("修改") . "</a>&nbsp;");
            echo sprintf("<a href=\"javascript:delete_meeting('$M_ID','$M_STATUS');\">" . _("删除") . "</a>");
        }
        ?>
        </td>
        </tr>
        <?
    }
}
?>
</table>
</body>

</html>
