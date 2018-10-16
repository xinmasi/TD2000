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

$HTML_PAGE_TITLE = _("�������");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css"/>
<script>
jQuery.noConflict();

function confirm_end(M_ID)
{
    msg='<?=_("ȷ��Ҫ�����û�����")?>';
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
		alert("<?=_("����д����׼��ԭ��")?>");
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
        alert("<?=_("Ҫ����ɾ�������¼��������ѡ������һ����")?>");
        return;
    }
    
    msg='<?=_("ȷ��Ҫɾ����������")?>';
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
   $M_STATUS_DESC=_("�����Ҫ����");
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
		   Message("",_("��").$M_STATUS_DESC);
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
   Message("",_("��").$M_STATUS_DESC);
   exit;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="22" height="18"><span class="big3"> <?=$M_STATUS_DESC?></span>
  </td>
  <?
  	$MSG_MEETING_COUNT=sprintf(_("��%s�������¼"),"<span class='big4'>&nbsp;".$MEETING_COUNT."</span>&nbsp;");
  ?>
  <td valign="bottom" class="small1"><?=$MSG_MEETING_COUNT?></td>
  <td align="right" valign="bottom" class="small1"><?=page_bar($start,$MEETING_COUNT,$ITEMS_IN_PAGE)?></td>
</tr>
</table>
<table width="95%" class="TableList" align="center">
<tr class="TableHeader">
    
    <td nowrap align="center"><?=_("����")?></td>
    <td nowrap align="center"><?=_("������")?></td>
    <td nowrap align="center"><?=_("��ʼʱ��")?></td>
    <td nowrap align="center"><?=_("������")?></td>
	<td nowrap align="center"><?=_("�����Ҫ")?></td>
    <td nowrap align="center"><?=_("����")?></td>
</tr>
<?
//============================ ��ʾ������� =======================================
$query5 ="SELECT * FROM MEETING WHERE APPROVE_NAME = '$CURRENTLY_AD_NAME'";
$cursor= exequery(TD::conn(),$query5);
while($ROW5=mysql_fetch_array($cursor))
{
	$APPROVE_NAME = $ROW5[APPROVE_NAME];
}
if($_SESSION["LOGIN_USER_PRIV"] == '1' || $_SESSION['LOGIN_USER_ID'] == "$APPROVE_NAME")
{
	
		//echo "ϵͳ����Ա";
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
		   //��ȡ����������
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
					echo "δ�ύ";
				break;
				case 1:
					echo "������";
				break;
				case 2:
					echo "�ѷ���";
				break;
				case 3:
					echo "����";
				break;
			}
			?>
		</td>
		<td  nowrap align="center">
     		<a href="javascript:;" onClick="window.open('../query/meeting_minutes_approval.php?M_ID=<?=$ROW['M_ID']?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("����")?></a>&nbsp;
		</td>
   </tr>
		<? }?>
   <!--
	<tr class="TableControl">
	<td colspan="<?=$i_colspan?>">
    &nbsp;<input type="checkbox" name="allbox" id="allbox_for"><label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;
    <a href="javascript:delete_all_meeting('<?=$M_STATUS?>');" title="<?=_("����ɾ��")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("����ɾ��")?></a>&nbsp;
    </td>
	</tr>
    -->
</table>
<?}?>
</body>
</html>

		
