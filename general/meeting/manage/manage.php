<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/flow_hook.php");

if(!$ITEMS_IN_PAGE)
   $ITEMS_IN_PAGE = get_page_size("MEETING", 10);
 
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
    if(($M_START1>=$M_START and $M_END1<=$M_END) or ($M_START1< $M_START and $M_END1>$M_START) or ($M_START1<$M_END and $M_END1>$M_END) or ($M_START1<$M_START and $M_END1>$M_END))
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

$i_colspan = ($M_STATUS==0) ? 8 : 7;

$HTML_PAGE_TITLE = _("会议管理");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css"/>
<script>
jQuery.noConflict();

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
function form_view(RUN_ID) 
{ 
    window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0"); 
}
function CheckForm() {
	if(document.form2.REASON.value=="")
	{
		alert("<?=_("请填写不批准的原因！")?>");
		document.form2.REASON.focus();
		return false;
	}else
	{
		document.form2.submit();
		document.getElementById("bz1").disabled = "disabled";
		document.form2.action = "";
	}
}

function set_action(M_ID)
{
	document.form2.action ="checkup.php?M_ID="+M_ID+"&M_STATUS=3";
    return true;
}

jQuery(function(){
    jQuery("#allbox_for").click(function(){
        if(jQuery("#allbox_for").is(":checked"))
        {
            jQuery("[name='meeting_select']").attr("checked",'true');
        }
        else
        {
            jQuery("[name='meeting_select']").removeAttr("checked");
        }
    })
    
    jQuery("[name='meeting_select']").click(function(){
        jQuery("#allbox_for").removeAttr("checked");
    })
});

function get_checked()
{
    checked_str="";
    
    jQuery("input[name='meeting_select']:checkbox").each(function(){ 
        if(jQuery(this).attr("checked")){
            checked_str += jQuery(this).val()+","
        }
    })
    
    return checked_str;
}

function delete_all_meeting(M_STATUS)
{
    check_str="";
    check_str=get_checked();
    
    if(check_str=="")
    {
        alert("<?=_("要批量删除会议记录，请至少选择其中一条。")?>");
        return;
    }
    
    msg='<?=_("确认要删除所会议吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?M_ID_STR=" + check_str + "&M_STATUS=" + M_STATUS;
        window.location=URL;
    }
}
function checkup(M_ID)
{
	window.location.href = "checkup.php?M_ID="+M_ID+"&M_STATUS=1";
	document.getElementById("pz_"+M_ID).style.display = "none";
}
function checkup_cloud(M_ID)
{
    window.location.href = "checkup_cloud.php?M_ID="+M_ID+"&M_STATUS=1";
    document.getElementById("pz_"+M_ID).style.display = "none";
}
function order_by(field,asc_desc)
{
    window.location="manage.php?start=<?=$start?>&M_STATUS=<?=$M_STATUS?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}
</script>
<body class="bodycolor">
<?
if($ASC_DESC=="0" || !isset($ASC_DESC)) {
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
}else if($ASC_DESC=="1"){
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
}

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


//修改事务提醒状态--yc
update_sms_status('8',0);

$CUR_TIME=date("Y-m-d H:i:s",time());
//-----自动开始----------
$query = "SELECT * from MEETING where M_STATUS=1";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $M_ID=$ROW["M_ID"];
    $M_START=$ROW["M_START"];
    if($CUR_TIME>=$M_START)
    {
        exequery(TD::conn(),"UPDATE MEETING SET M_STATUS= '2' WHERE M_ID='$M_ID'");
    }
}

//-----自动结束----------
$query = "SELECT * from MEETING  where M_STATUS=2";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $M_ID3=$ROW["M_ID"];
    $M_END3=$ROW["M_END"];
	$RECORDER=$ROW["RECORDER"]; //会议纪要员
    if($CUR_TIME>=$M_END3)
    {
        exequery(TD::conn(),"UPDATE MEETING SET M_STATUS= '4',SUMMARY_STATUS='0'  WHERE M_ID='$M_ID3'");
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
if($_SESSION["LOGIN_USER_PRIV"] == 1 || (empty($MEETING_OPERATORS) != true))//如果是admin,或是会议管理员
{
   if($M_STATUS==0)
      $query = "SELECT count(*) from MEETING where M_STATUS='0' and CYCLE!='2' and CYCLE!='1'";
   else
      $query = "SELECT count(*) from MEETING where M_STATUS='$M_STATUS'";
	
}
else
{
	$CURRENT_NAME = $_SESSION['LOGIN_USER_ID'];
   if($M_STATUS==0)
      $query = "SELECT COUNT(*) FROM MEETING WHERE M_STATUS='0' AND CYCLE!='2' and CYCLE!='1' AND M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."'";
   else
	  $query = "SELECT count(*) from MEETING where M_STATUS='$M_STATUS' and (RECORDER='$CURRENT_NAME' or M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."')";
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

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="22" height="18"><span class="big3"> <?=$M_STATUS_DESC?></span>
  </td>
  <?
  	$MSG_MEETING_COUNT=sprintf(_("共%s条会议记录"),"<span class='big4'>&nbsp;".$MEETING_COUNT."</span>&nbsp;");
  ?>
  <td valign="bottom" class="small1"><?=$MSG_MEETING_COUNT?></td>
  <td align="right" valign="bottom" class="small1"><?=page_bar($start,$MEETING_COUNT,$ITEMS_IN_PAGE)?></td>
</tr>
</table>
<table width="95%" class="TableList" align="center">
<tr class="TableHeader">
    <td nowrap align="center"><?=_("选择")?></td>
    <td nowrap align="center"><?=_("名称")?></td>
    <td nowrap align="center"><?=_("申请人")?></td>
    <td nowrap align="center" onClick="order_by('START_TIME','<?if($FIELD=="START_TIME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><?=_("开始时间")?><?if($FIELD=="START_TIME"||$FIELD=="") echo $ORDER_IMG;?></td>
    <td nowrap align="center"><?=_("会议室")?></td>
<?
if($M_STATUS==0)
{
?>
     <td nowrap align="center"><?=_("预约状态")?></td>
<?
}
?>
	<td nowrap align="center"><?=_("会议纪要")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
</tr>
<?
//============================ 显示会议情况 =======================================
//if($_SESSION["LOGIN_USER_PRIV"]==1)
if($_SESSION["LOGIN_USER_PRIV"]==1 || (empty($MEETING_OPERATORS) != true))  
{
   if($M_STATUS=='0')
   {
      $query = "SELECT * from MEETING where M_STATUS='$M_STATUS' and CYCLE!='2' and CYCLE!='1'";
   }else
   {
      $query = "SELECT * from MEETING where M_STATUS='$M_STATUS' ";
   }
}
else
{
   if($M_STATUS=='0')
      $query = "SELECT * from MEETING where M_STATUS='$M_STATUS' and CYCLE!='2' and CYCLE!='1' and M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."'";
   else
      $query = "SELECT * from MEETING where M_STATUS='$M_STATUS' and (RECORDER='$CURRENT_NAME' or M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."')";
}
if($FIELD=="START_TIME")
{
    if($ASC_DESC == "0"){
        $query.=" order by M_START asc ";
    }else if($ASC_DESC == "1"){
        $query.=" order by M_START desc ";
    }
}else{
    $query.=" order by M_START asc ";
}
$query.=" limit $start,$ITEMS_IN_PAGE ";
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
   $SUMMARY_STATUS  = $ROW["SUMMARY_STATUS"];
   $M_ATTENDEE_OUT=$ROW["M_ATTENDEE_OUT"];
   //新增字段-会议类型:0-普通会议 1-云视频会议
   $M_TYPE=$ROW["M_TYPE"];
   //对会议名称截取显示
    if(strlen($M_NAME) > 30)
    {
        $M_NAME = csubstr($M_NAME,0,30)."...";
    }
    //若为云视频会议并且有选定会议室 则会议名称后有标识png
    if($M_TYPE == "1" && $M_ROOM != ""){
        $M_NAME = $M_NAME."<image src='/static/images/metting.png'></image>";
    }
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
    $M_ROOM_NAME="";
    if($M_TYPE == "1" && $M_ROOM == "0"){
        $M_ROOM_NAME=_("个人电脑");
    }else{
        $query = "SELECT * from MEETING_ROOM where MR_ID='$M_ROOM'";
        $cursor2= exequery(TD::conn(),$query);
        if($ROW2=mysql_fetch_array($cursor2))
            $M_ROOM_NAME=$ROW2["MR_NAME"];
    }
?>
   <tr class="<?=$TableLine?>">
    <td>&nbsp;<input type="checkbox" name="meeting_select" id="meeting_select" value="<?=$M_ID?>"></td>
     <td nowrap  width="30%"><?=$M_NAME?></td>
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
	<td>&nbsp;&nbsp;
	<?
		switch($SUMMARY_STATUS)
		{
			case 0:
				echo "未提交";
			break;
			case 1:
				echo "待审批";
			break;
			case 2:
				echo "<font color='green'>已发布</font>";
			break;
			case 3:
				echo "<font color='#FF0000'>驳回</font>";
			break;
		}
	?>
	</td>
    <td  nowrap align="center"><a href="javascript:;" onClick="window.open('../query/summary_detail.php?M_ID=<?=$M_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
     <a href="javascript:;" onClick="window.open('../apply/select.php?MR_ID=<?=$M_ROOM?>&ACTION=SEE','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left=200,resizable=yes');"><?=_("预约情况")?></a>

<?
if($M_STATUS==0)
{
    $is_run_hook=is_run_hook("M_ID",$M_ID);    
    if($is_run_hook!=0)
    {
?>

      <a href="javascript:;" onClick="form_view('<?=$is_run_hook?>')"><?=_("查看流程")?></a>
<?     
     }
    else
    {
     echo "<a href=\"../apply/new.php?NEW=2&M_ID=$M_ID&FLAG=1\">"._("修改")."</a>&nbsp;";
     if(!is_number($SS)){
         if($M_TYPE == "0"){
             echo "<a href='javascript:;' onClick='checkup(".$M_ID.");' id='pz_".$M_ID."'> "._("批准")."</a>&nbsp;";
         }elseif($M_TYPE == "1"){
             echo "<a href='javascript:;' onClick='checkup_cloud(".$M_ID.");' id='pz_".$M_ID."'> "._("批准")."</a>&nbsp;";
         }
     }
     echo "<a href='#' onClick=\"jQuery.noConflict();set_action(".$M_ID.");ShowDialog('DenyReason')\";> "._("不准")."</a>&nbsp";
    }
}
elseif($M_STATUS==1)
{
    echo _("<a href=\"../apply/new.php?NEW=2&M_ID=$M_ID&FLAG=1&OP_M_STATUS=1\">"._("修改")."</a>&nbsp;");
    echo _("<a href='checkup.php?M_ID=$M_ID&M_STATUS=0'>"._("撤销")."</a>&nbsp;");
}
elseif($M_STATUS==2 && $SUMMARY_STATUS == 0)
{
?>
     <a href="javascript:;" onClick="window.open('../apply/modify.php?NEW=1&M_ID=<?=$M_ID?>','','height=300,width=450,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=150,resizable=no');"><?=_("修改")?></a>&nbsp;
     <a href="javascript:;" onClick="window.open('../query/summary.php?M_ID=<?=$M_ID?>','','height=540,width=740,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=150,top=100,resizable=yes');"><?=_("会议纪要")?></a>&nbsp;
     <a href="javascript:confirm_end('<?=$M_ID?>');"> <?=_("结束")?></a>&nbsp;
<?
}
elseif($M_STATUS==3)
{
	  if(!is_number($SS))
    echo _("<a href='checkup.php?M_ID=$M_ID&M_STATUS=1'> 批准</a>&nbsp;");
}
elseif($M_STATUS==4 && ($SUMMARY_STATUS == 0 || $SUMMARY_STATUS==3) )
{
?>
   <a href="javascript:;" onClick="window.open('../summary/summary.php?M_ID=<?=$M_ID?>','','height=540,width=740,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=150,top=100,resizable=yes');"><?=_("会议纪要")?></a>&nbsp;
<?
}
?>
  <a href="javascript:delete_meeting('<?=$M_ID?>','<?=$M_STATUS?>','<?=$CYCLE?>');"> <?=_("删除")?></a>
  </td>
   </tr>
<?
}//while
?>
<tr class="TableControl">
<td colspan="<?=$i_colspan?>">
    <input type="checkbox" name="allbox" id="allbox_for"><label for="allbox_for"><?=_("全选")?></label> &nbsp;
    <a href="javascript:delete_all_meeting('<?=$M_STATUS?>');" title="<?=_("批量删除")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("批量删除")?></a>&nbsp;
    </td>
</tr>
</table>

<div id="overlay"></div>
<div id="DenyReason" class="ModalDialog" style="width:500px;">
  <div class="header"><span class="title"><?=_("不批准会议")?></span><a class="operation" href="javascript:HideDialog('DenyReason');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
 <form id="form2" action="" enctype="multipart/form-data" method="post" name="form2">
  <table class="TableBlock" width="97%" align="center" border="0" style="margin-top:10px;">
    <tr>
      <td width="22%" class="TableContent" align="center"><?=_("不准原因")?>:</td>
      <td class="TableData">
      <textarea class="SmallInput" name="REASON" cols="50" rows="5"></textarea> 
      </td>
    </tr>
   
  <tr>
  <td class="TableData" align="center"  colspan="2">
  <input type="button" class="SmallButton" value="<?=_("提交")?>" id="bz1" onClick="return CheckForm();">&nbsp;&nbsp;
  <input type="button" class="SmallButton" value="<?=_("取消")?>" onClick="HideDialog('DenyReason')"></td>
  </tr>
  </table>
<br>
</form>
</div>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden
</body>
</html>
