<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$ITEMS_IN_PAGE=10;
if(!isset($start) || $start=="")
{
    $start=0;
}
   
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


<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css"/>

<script>
jQuery.noConflict();
function CheckForm() {
	if(document.form2.REASON.value=="")
	{
		alert("请填写不批准的原因！");
		document.form2.REASON.focus();
		return false;
	}else
	{
		document.form2.submit();
		document.getElementById("bz1").disabled = "disabled";
		document.form2.action = "";
	}
}

function set_action(M_ID,CYCLE,CYCLE_NO)
{
	document.form2.action ="checkup_cycle_deny.php?CHECK_STR="+M_ID+"&M_STATUS=3&CYCLE=" + CYCLE+"&CYCLE_NO=" + CYCLE_NO;
    return true;
}

function delete_meeting(M_ID,M_STATUS,CYCLE,CYCLE_NO)
{
    msg='<?=_("确认要删除该会议吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?M_ID_STR=" + M_ID + "&M_STATUS=" + M_STATUS + "&CYCLE=" + CYCLE+"&CYCLE_NO=" + CYCLE_NO;
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

function check_up(CYCLE,CYCLE_NO)
{
    check_str="";
    check_str=get_checked();
    
    if(check_str=="")
    {
        alert("<?=_("要批量批准待批会议，请至少选择其中一条。")?>");
        return;
    }
    
    msg='<?=_("确认要批准所选待批会议吗？")?>';
    if(window.confirm(msg))
    {
        url="checkup_cycle.php?CHECK_STR="+ check_str+"&CYCLE="+CYCLE+"&CYCLE_NO="+CYCLE_NO;
        location=url;
        
    }
}

function check_up_deny(CYCLE,CYCLE_NO)
{
    check_str="";
    check_str=get_checked();
    
    if(check_str=="")
    {
        alert("<?=_("要批量不批准待批会议，请至少选择其中一条。")?>");
        return;
    }
    if(check_str!=="")
    {
    	
	    msg='<?=_("确认要不批准所选待批会议吗？")?>';
	    if(window.confirm(msg))
	    {
			ShowDialog('DenyReason');
	        document.form2.action="checkup_cycle_deny.php?CHECK_STR="+ check_str +"&M_STATUS=3&CYCLE=" + CYCLE+"&CYCLE_NO=" + CYCLE_NO ;
	        
	        return true;
	    }
	}
}

function createXMLHttpRequest() {
  http_request=false;
  if(window.XMLHttpRequest){
     http_request=new XMLHttpRequest();
     if(http_request.overrideMimeType){
        http_request.overrideMimeType("text/xml");
     }
   }else if(window.ActiveXObject){
      try{
         http_request=new ActiveXObject("Msxml2.XMLHttp");
      }catch(e){
          try{
                http_request=new ActiveXobject("Microsoft.XMLHttp");
          }catch(e){}
      }
    }
    if(!http_request){
       window.alert("<?=_("创建")?>XMLHttp<?=_("对象失败！")?>");
       return false;
    }
}

function my_query()
{
	var div=$("time_status2");
	div.innerHTML = "<?=_("数据加载中")?>...";
	createXMLHttpRequest();
	var url1='';
	
	if(document.getElementById('W1').checked)
	   url1 +="&W1="+$("W1").value;
	if(document.getElementById('W2').checked)
	   url1 +="&W2="+$("W2").value;
	if(document.getElementById('W3').checked)
	   url1 +="&W3="+$("W3").value;
	if(document.getElementById('W4').checked)
	   url1 +="&W4="+$("W4").value;
	if(document.getElementById('W5').checked)
	   url1 +="&W5="+$("W5").value;
	if(document.getElementById('W6').checked)
	   url1 +="&W6="+$("W6").value;
	if(document.getElementById('W7').checked)
	   url1 +="&W7="+$("W7").value;
	var URL = "gethtml.php?CYCLE_NO="+$("CYCLE_NO").value+url1;
  http_request.open("GET",URL,true);
	http_request.onreadystatechange =function(){
		if (http_request.readyState == 4) {
			if (http_request.status == 200) {
				div.innerHTML= http_request.responseText;
			}
		}
	}
	http_request.send(null);
}
function order_by(field,asc_desc)
{
    window.location="manage_cycle.php?CYCLE=<?=$CYCLE?>&CYCLE_NO=<?=$CYCLE_NO?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}
</script>


<body class="bodycolor">
<?
if($ASC_DESC=="0" || !isset($ASC_DESC)){
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
}else if($ASC_DESC=="1"){
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
}

$CUR_TIME=date("Y-m-d H:i:s",time());
$M_STATUS_DESC=_("待批会议");

$sql = "select MEETING_OPERATOR from meeting_rule";
$result= exequery(TD::conn(),$sql);
if($row=mysql_fetch_array($result))
{
    $meeting_operator = $row["MEETING_OPERATOR"];
}
if($meeting_operator!='')
{
    $meeting_operator = td_trim($meeting_operator);
    $manage = explode(",",$meeting_operator);
}
if($_SESSION["LOGIN_USER_PRIV"]==1 || in_array($_SESSION["LOGIN_USER_ID"],$manage))
{
   $query = "SELECT count(*) from MEETING where M_STATUS='0' and CYCLE='$CYCLE' and CYCLE_NO='$CYCLE_NO'";
}
else
{
   $query = "SELECT count(*) from MEETING where M_STATUS='0' and CYCLE='$CYCLE' and CYCLE_NO='$CYCLE_NO' and M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."'";
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

<?
   Message("",_("无").$M_STATUS_DESC);
   exit;
}
?>

<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small"  align="center">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="22" height="18"><span class="big3"> <?=$M_STATUS_DESC?></span>
  </td>
  <?
  	$MSG_MEETING_COUNT=sprintf(_("共%s条会议记录"),"<span class='big4'>&nbsp;".$MEETING_COUNT."</span>&nbsp;");
  ?>
  <td valign="bottom" class="small1"><?=$MSG_MEETING_COUNT ?>
  </td><td align="right" valign="bottom" class="small1"><?=page_bar($start,$MEETING_COUNT,$ITEMS_IN_PAGE)?></td>
</tr>
<?
	if($CYCLE == '1'){
?>
  <tr>
    <td nowrap class="TableContent" width="100"> <?=_("申请星期：")?></td>
    <td class="TableData" colspan="3">
     <input type="hidden" id="CYCLE_NO" name="CYCLE_NO" value="<?=$CYCLE_NO?>">
     <span id="WEEKEND1"><input type="checkbox" name="W1" id="W1" value="1" checked><?=_("星期一")?></span>
     <span id="WEEKEND2"><input type="checkbox" name="W2" id="W2" value="2" checked><?=_("星期二")?></span>
     <span id="WEEKEND3"><input type="checkbox" name="W3" id="W3" value="3" checked><?=_("星期三")?></span>
     <span id="WEEKEND4"><input type="checkbox" name="W4" id="W4" value="4" checked><?=_("星期四")?></span>
     <span id="WEEKEND5"><input type="checkbox" name="W5" id="W5" value="5" checked><?=_("星期五")?></span>
     <span id="WEEKEND6"><input type="checkbox" name="W6" id="W6" value="6" checked><?=_("星期六")?></span>
     <span id="WEEKEND0"><input type="checkbox" name="W7" id="W7" value="0" checked><?=_("星期日")?></span>
     <input type="button" onclick="my_query()" value="<?=_("查询")?>">
    </td>
  </tr>
<?
}
?>
</table>
<div id="time_status2">
<table width="95%" class="TableList" align="center">
<tr class="TableHeader">
  <td nowrap align="center"><?=_("选择")?></td>
  <td nowrap align="center"><?=_("名称")?></td>
  <td nowrap align="center"><?=_("申请人")?></td>
  <td nowrap align="center" onClick="order_by('START_TIME','<?if($FIELD=="START_TIME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><?=_("开始时间")?><?if($FIELD=="START_TIME"||$FIELD=="") echo $ORDER_IMG;?></td>
  <td nowrap align="center"><?=_("会议室")?></td>
  <td nowrap align="center"><?=_("预约状态")?></td>
  <td nowrap align="center"><?=_("操作")?></td>
</tr>
<?
//============================ 显示会议情况 =======================================
if($_SESSION["LOGIN_USER_PRIV"]==1 || in_array($_SESSION["LOGIN_USER_ID"],$manage))
{
   $query = "SELECT * from MEETING where M_STATUS='0' and CYCLE='$CYCLE' and CYCLE_NO='$CYCLE_NO'";
}
else
{
   $query = "SELECT * from MEETING where M_STATUS='0' and CYCLE='$CYCLE' and CYCLE_NO='$CYCLE_NO' and M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."'";
}
if($FIELD=="START_TIME")
{
    if($ASC_DESC == "0"){
        $query.=" order by M_START asc ";
    }else if($ASC_DESC == "1"){
        $query.=" order by M_START desc ";
    }
}
$MEETING_COUNT=0;
$query.=" limit $start,$ITEMS_IN_PAGE ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $MEETING_COUNT++;

   $M_ID=$ROW["M_ID"];
   $M_NAME=$ROW["M_NAME"];
   $CYCLE=$ROW["CYCLE"];
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
<?
$SS=substr(check_room($M_ID,$M_ROOM,$M_START,$M_END), 0, 1);


if(!is_number($SS))
{
?>
	<td>&nbsp;<input type="checkbox" name="meeting_select" id="meeting_select" value="<?=$M_ID?>"></td>
<?
}
else
{
?>
   <td></td>
<?
}

?>   	 
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
echo _("<a href=\"../apply/new.php?M_ID=$M_ID&FLAG=1\">修改</a>&nbsp;");
if(!is_number($SS))
echo _("<a href='checkup_cycle.php?CHECK_STR=$M_ID&CYCLE=$CYCLE&CYCLE_NO=$CYCLE_NO'> 批准</a>&nbsp;");
echo _("<a href='#' onClick=\"jQuery.noConflict();set_action(".$M_ID.",".$CYCLE.",".$CYCLE_NO.");ShowDialog('DenyReason')\";> "._("不准")."</a>&nbsp");
?>
  <a href="javascript:delete_meeting('<?=$M_ID?>','<?=$M_STATUS?>','<?=$CYCLE?>','<?=$CYCLE_NO?>');"> <?=_("删除")?></a>
  </td>
   </tr>
<?
}
?>
</div>
<tr class="TableControl">
<td colspan="19">
    <input type="checkbox" name="allbox" id="allbox_for"><label for="allbox_for"><?=_("全选")?></label> &nbsp;
    <a href="javascript:check_up('<?=$CYCLE?>','<?=$CYCLE_NO?>');" title="<?=_("批量批准待批会议")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/user_group.gif" align="absMiddle"><?=_("批量批准待批会议")?></a>&nbsp;
    <a href="#" onClick="check_up_deny('<?=$CYCLE?>','<?=$CYCLE_NO?>');" title="<?=_("批量不批准待批会议")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("批量不批准待批会议")?></a>&nbsp;
    </td>
</tr>
</table>

<div id="overlay"></div>
<div id="DenyReason" class="ModalDialog" style="width:500px;">
  <div class="header"><span class="title">不批准会议</span><a class="operation" href="javascript:HideDialog('DenyReason');"><img src="/static/images/close.png"/></a></div>
 <form id="form2" action="" enctype="multipart/form-data" method="post" name="form2">
  <table class="TableBlock" width="97%" align="center" border="0" style="margin-top:10px;">
    <tr>
      <td width="22%" class="TableContent" align="center">不准原因:</td>
      <td class="TableData">
      <textarea class="SmallInput" name="REASON" cols="50" rows="5"></textarea> 
      </td>
    </tr>
   
  <tr>
  <td class="TableData" align="center"  colspan="2">
  <input type="button" class="SmallButton" value="提交" id="bz1" onClick="return CheckForm();">&nbsp;&nbsp;
  <input type="button" class="SmallButton" value="取消" onClick="HideDialog('DenyReason')"></td>
  </tr>
  </table>
<br>
</form>
</div>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden

</body>

</html>
