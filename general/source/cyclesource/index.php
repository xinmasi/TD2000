<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
if(!isset($TYPE))
   $TYPE="0";
if(!isset($PAGE_SIZE))
   $PAGE_SIZE =15;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("��������Դ����");
include_once("inc/header.inc.php");
?>

<Script language="JavaScript">
function delete_source(CYCID)
{
	msg='<?=_("ȷ��Ҫɾ������Դ�����԰�����")?>';
	if(window.confirm(msg))
	{
	  URL="delete.php?CYCID="+CYCID;
	  window.location=URL;
	}
}
function check_all()
{
	for (i=0;i<document.getElementsByName("source_select").length;i++)
	{
		if(document.getElementById("allbox_for").checked)
		   document.getElementsByName("source_select").item(i).checked=true;
		else
		   document.getElementsByName("source_select").item(i).checked=false;
 	}
	if(i==0)
	{
   	if(document.getElementById("allbox_for").checked)
      	document.getElementsByName("source_select").checked=true;
  		else
      	document.getElementsByName("source_select").checked=false;
 	}
}
function check_one(el)
{
   if(!el.checked)
      document.getElementById("allbox_for").checked=false;
}
function get_checked()
{
	delete_str="";
	for(i=0;i<document.getElementsByName("source_select").length;i++)
	{
		el=document.getElementsByName("source_select").item(i);
		if(el.checked)
		{  
			val=el.value;
		   delete_str+=val + ",";
		}
	}
	if(i==0)
	{
	   el=document.getElementsByName("source_select");
	   if(el.checked)
	   { 
	   	val=el.value;
	      delete_str+=val + ",";
	   }
	}
	return delete_str;
}
function delete_mail()
{
	delete_str=get_checked();
	if(delete_str=="")
	{
		alert("<?=_("Ҫɾ����Ϣ��������ѡ������һ����")?>");
		return;
	}
	msg='<?=_("ȷ��Ҫɾ����ѡ��Ϣ��")?>';
	if(window.confirm(msg))
	{
		url="delete.php?DELETE_STR="+ delete_str +"&start=<?=$start?>";
		location=url;
	}
}
function newwin(url)
{
	myleft=(screen.availWidth-600)/2;
	var newwin=window.open(url,"newwin","toolbar=no,location=no,left="+myleft+",top=100,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=620,height=450");
	newwin.focus();
}
function newwin2(url)
{
	myleft=(screen.availWidth-820)/2;
	var newwin=window.open(url,"newwin","toolbar=no,location=no,left="+myleft+",top=20,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=820,height=550");
	newwin.focus();
}
</script>
<body class="bodycolor">
<table border="0" width="70%" cellspacing="0" cellpadding="3" class="small">
	<tr>
		<td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/source.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��������Դ�����б�")?> </span>

	   &nbsp;<input type="button" value="<?=_("�½������԰���")?>" class="SmallButton" onclick="newwin('add.php')">

		</td>
   </tr>
</table>
<br>
<?
//----- ��Դ�б� ------
if(!isset($TOTAL_ITEMS))
{
	$query = "SELECT count(*) from OA_CYCLESOURCE_USED,OA_SOURCE where  OA_SOURCE.SOURCEID=OA_CYCLESOURCE_USED.SOURCEID ";
	$cursor= exequery(TD::conn(),$query);
	$TOTAL_ITEMS=0;
	if($ROW=mysql_fetch_array($cursor))
		$TOTAL_ITEMS=$ROW[0];
}
if($TOTAL_ITEMS==0)
{
	Message("",_("û�пɹ������Դ�����԰�����Ϣ��"));
	exit;
}
?>
<form name="form1">
<table border="0" width="70%" cellspacing="0" cellpadding="3" class="small" align="center">
  <tr>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
  </tr>
</table>
</form>
<?
$query = "select OA_SOURCE.SOURCEID as SOURCEID ,OA_CYCLESOURCE_USED.CYCID as CYCID,OA_CYCLESOURCE_USED.B_APPLY_TIME as B_APPLY_TIME,OA_CYCLESOURCE_USED.E_APPLY_TIME as E_APPLY_TIME,OA_CYCLESOURCE_USED.USER_ID as USER_ID,OA_SOURCE.SOURCENAME as SOURCENAME from OA_CYCLESOURCE_USED,OA_SOURCE  where  OA_SOURCE.SOURCEID=OA_CYCLESOURCE_USED.SOURCEID order by OA_CYCLESOURCE_USED.CYCID";
$query .= " limit $start,$PAGE_SIZE";
$cursor = exequery(TD::conn(),$query);
$COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{  
	$CYCID=$ROW["CYCID"];
	$SOURCEID = $ROW["SOURCEID"];
	$SOURCENAME = $ROW["SOURCENAME"];
	$B_APPLY_TIME = $ROW["B_APPLY_TIME"];
	$E_APPLY_TIME = $ROW["E_APPLY_TIME"];
	$USER_ID=$ROW["USER_ID"];
	$query1 = "select * from USER where USER_ID='$USER_ID'";
	$cursor1 = exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
   	$USER_NAME = $ROW1["USER_NAME"];
   $COUNT++;
   if($COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
   if($COUNT==1)
   {
?>
<table width=70% class="TableList"  align="center">
	<tr class="TableHeader">
    		<td  nowrap align="center"><?=_("ѡ��")?></td>
			<td nowrap align="center"> <?=_("��Դ����")?> </td>
			<td nowrap align="center"> <?=_("��ʼ����")?> </td>
			<td nowrap align="center"> <?=_("��������")?></td>
			<td nowrap align="center"> <?=_("ʹ����")?> </td>
			<td nowrap align="center" ><?=_("����")?></td>
	  
  </tr>  
<?
   }
?>
  <tr class=<?=$TableLine?>>

    	<td nowrap  width=40><input type="checkbox" name="source_select" value="<?=$CYCID?>" onClick="check_one(self);"></td>
   	<td nowrap align="center" ><a href="javascript:newwin2('../manage/apply.php?SOURCEID=<?=$SOURCEID?>')"><?=$SOURCENAME?></a></td>
	   <td nowrap align="center" ><?=$B_APPLY_TIME?></td>
	   <td nowrap align="center" ><?=$E_APPLY_TIME?></td>
	   <td nowrap align="center" ><?=$USER_NAME?></td>

		<td nowrap align="center" >
			 <a href="javascript:newwin('show.php?CYCID=<?=$CYCID?>&start=<?=$start?>')"><?=_("����")?></a>&nbsp;
			 <a href="javascript:newwin('add.php?CYCID=<?=$CYCID?>')"><?=_("�༭")?></a>&nbsp;
			 <a href="javascript:delete_source(<?=$CYCID?>)"><?=_("ɾ��")?></a>
		</td>
	</tr>
<?
}

?>
	<tr class="TableControl">
		<td colspan="6">
	  		<input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">
    		<label for="allbox_for"><?=_("ȫѡ")?></label> &nbsp;
    		<a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ����Ϣ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp;
		</td>	
	</tr>

</table>
</BODY>
</HTML>

