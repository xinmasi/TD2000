<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("inc/flow_hook.php");

if(!$ITEMS_IN_PAGE)
   $ITEMS_IN_PAGE = get_page_size("MEETING", 10);
 
if(!isset($start) || $start=="")
   $start=0;

$i_colspan = 7;

$HTML_PAGE_TITLE = _("会议管理");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css"/>
<script>
jQuery.noConflict();

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
</script>

<body class="bodycolor">
<? 
	$CURRENTLY_AD_NAME = $_SESSION['LOGIN_USER_ID'];
	$sql1 = "SELECT MEETING_OPERATOR from meeting_rule";
	$result1 = exequery(TD::conn(),$sql1);
	while($ROW1 = mysql_fetch_array($result1))
	{
		
		$MEETING_OPERATOR = explode(',', $ROW1['MEETING_OPERATOR']);
		$MEETING_OPERATOR = $MEETING_OPERATOR[0];
	}
   $M_STATUS_DESC=_("会议纪要审批");
   if($_SESSION["LOGIN_USER_PRIV"] == 1)
   {
	    $query = "SELECT count(*) as ifhave from MEETING where M_STATUS='4'";
		$cursor= exequery(TD::conn(),$query);
		if($ROW=mysql_fetch_array($cursor))
		{
			$MEETING_COUNT = $ROW['ifhave'];
		}
   }else
   {
		$query = "SELECT count(*) as ifhave from MEETING where M_STATUS='4' and APPROVE_NAME='$CURRENTLY_AD_NAME'";
		$cursor= exequery(TD::conn(),$query);
		if($ROW=mysql_fetch_array($cursor))
		{
		$MEETING_COUNT = $ROW['ifhave'];
		}
   }
   if($MEETING_COUNT == 0)
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
   $query = "SELECT * from MEETING where M_STATUS='4' and APPROVE_NAME='$CURRENTLY_AD_NAME'";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
	   $APPROVE_NAME  = $ROW['APPROVE_NAME'];
	   $M_ID.= $ROW['M_ID'].',';
   }
   
   if(($MEETING_COUNT == 0 || $APPROVE_NAME != $CURRENTLY_AD_NAME) && $CURRENTLY_AD_NAME != 'admin')
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
    
    <td nowrap align="center"><?=_("名称")?></td>
    <td nowrap align="center"><?=_("申请人")?></td>
    <td nowrap align="center"><?=_("开始时间")?></td>
    <td nowrap align="center"><?=_("会议室")?></td>
	<td nowrap align="center"><?=_("会议纪要")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
</tr>
<?
//============================ 显示会议情况 =======================================
$query5 ="SELECT * FROM MEETING WHERE APPROVE_NAME = '$CURRENTLY_AD_NAME'";
$cursor= exequery(TD::conn(),$query5);
while($ROW5=mysql_fetch_array($cursor))
{
	$APPROVE_NAME = $ROW5[APPROVE_NAME];
}
if($_SESSION["LOGIN_USER_PRIV"] == '1' || $_SESSION['LOGIN_USER_ID'] == "$APPROVE_NAME")
{
	
		//echo "系统管理员";
		//$query ="SELECT * FROM MEETING WHERE M_PROPOSER='".$_SESSION['LOGIN_USER_ID']."' AND M_STATUS='4' ORDER BY M_START DESC,SUMMARY_STATUS DESC LIMIT $start,$ITEMS_IN_PAGE ";
		$query ="SELECT * FROM MEETING WHERE M_STATUS='4' ORDER BY M_START DESC,SUMMARY_STATUS DESC LIMIT $start,$ITEMS_IN_PAGE ";
		$cursor= exequery(TD::conn(),$query);
		$num = 0;
		while($ROW=mysql_fetch_array($cursor))
		{
		   $num++;
		   if($num%2==1)
			$TableLine="TableLine1";
		   else
			$TableLine="TableLine2";
		   //获取会议室名称
		   $M_ROOM_NAME="";
		   $query = "SELECT * from MEETING_ROOM where MR_ID='".$ROW['M_ROOM']."'";
		   $cursor2= exequery(TD::conn(),$query);
		   if($ROW2=mysql_fetch_array($cursor2))
		   $M_ROOM_NAME=$ROW2["MR_NAME"];
		?>
		<tr class="<?=$TableLine?>">
		<td align="center" nowrap width="30%"><?=$ROW['M_NAME']?></td>
		<td align="center"><?=rtrim(GetUserNameById($ROW['M_PROPOSER']),',')?></td>
		<td align="center"><?=$ROW['M_START']?></td>
		<td align="center"><?=$M_ROOM_NAME?></td>
		<td align="center">
			<?
			$status = $ROW['SUMMARY_STATUS'];
			switch($status)
			{
				case 0:
					echo "未提交";
				break;
				case 1:
					echo "待审批";
				break;
				case 2:
					echo "已发布";
				break;
				case 3:
					echo "驳回";
				break;
			}
			?>
		</td>
		<td  nowrap align="center">
     		<a href="javascript:;" onClick="window.open('../query/meeting_minutes_approval.php?M_ID=<?=$ROW['M_ID']?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详情")?></a>&nbsp;
		</td>
   </tr>
		<? }?>
   <!--
	<tr class="TableControl">
	<td colspan="<?=$i_colspan?>">
    &nbsp;<input type="checkbox" name="allbox" id="allbox_for"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
    <a href="javascript:delete_all_meeting('<?=$M_STATUS?>');" title="<?=_("批量删除")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("批量删除")?></a>&nbsp;
    </td>
	</tr>
    -->
</table>
<?}?>
</body>
</html>

		
