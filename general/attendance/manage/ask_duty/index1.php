<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("�����ѯ����");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
function delete_ask_duty(ASK_DUTY_ID)
{
  msg='<?=_("ȷ��Ҫɾ���ò����ѯ��Ϣ��")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?ASK_DUTY_ID=" +ASK_DUTY_ID+"&PAGE_START=<?=$PAGE_START?>";
     window.location=URL;
  }
}

function check_all()
{
   for(i=0;i<document.all("email_select").length;i++)
   {
      if(document.all("allbox").checked)
         document.all("email_select").item(i).checked=true;
      else
         document.all("email_select").item(i).checked=false;
   }

   if(i==0)
   {
      if(document.all("allbox").checked)
         document.all("email_select").checked=true;
      else
         document.all("email_select").checked=false;
   }
}

function check_one(el)
{
   if(!el.checked)
      document.all("allbox").checked=false;
}

function get_checked()
{
   checked_str="";
   for(i=0;i<document.all("email_select").length;i++)
   {

      el=document.all("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
   }

  if(i==0)
  {
      el=document.all("email_select");
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }
  return checked_str;
}

function delete_mail()
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("Ҫɾ�������ѯ��Ϣ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ����������ѯ��Ϣ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?ASK_DUTY_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
    location=url;
  }
}

function change_type(type)
{
   window.location="index1.php?start=<?=$start?>";
}
</script>

<body class="bodycolor">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);

//OA����Ա ��������Ա �½��� 
//$WHERE_STR = hr_priv("BY_EVALU_STAFFS");
if(!isset($TOTAL_ITEMS))
{
	if($_SESSION["LOGIN_USER_PRIV"]==1)
		$query= "SELECT count(*) from attend_ask_duty ";
	else
   		$query = "SELECT count(*) from attend_ask_duty where (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',CREATE_USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',CHECK_DUTY_MANAGER))";
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);

?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("��������ѯ")?></span>&nbsp;
    </td>
<?
if($TOTAL_ITEMS>0)
{
?>    
    <td align="right" valign="bottom" class="small1"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
<?
}
?>
    </tr>
</table>
<?
if($TOTAL_ITEMS>0)
{
?>  
<div align=center>
<table class="TableList" width="95%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("ȱ����")?></td>
      <td nowrap align="center"><?=_("�����")?></td>
      <td nowrap align="center"><?=_("���ʱ��")?></td>
      <td nowrap align="center"><?=_("ȱ����˵��ʱ��")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </tr>
<?
}
if($_SESSION["LOGIN_USER_PRIV"]==1)
	$query="SELECT * from attend_ask_duty  order by ASK_DUTY_ID desc limit $PAGE_START, $PAGE_SIZE";
else
	$query = "SELECT * from attend_ask_duty where (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',CREATE_USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',CHECK_DUTY_MANAGER) ) order by ASK_DUTY_ID desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$ASK_DUTY_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $ASK_DUTY_COUNT++;

   $ASK_DUTY_ID=$ROW["ASK_DUTY_ID"];
   $CHECK_USER_ID=$ROW["CHECK_USER_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CHECK_DUTY_MANAGER=$ROW["CHECK_DUTY_MANAGER"];

   $CHECK_DUTY_TIME=$ROW["CHECK_DUTY_TIME"];
   $RECORD_TIME=$ROW["RECORD_TIME"];
   $CHECK_DUTY_REMARK=$ROW["CHECK_DUTY_REMARK"];
   $EXPLANATION=$ROW["EXPLANATION"];

   
	 
	 $CHECK_USER_NAME=substr(GetUserNameById($CHECK_USER_ID),0,-1);
   $CHECK_MANAGER_NAME=substr(GetUserNameById($CHECK_DUTY_MANAGER),0,-1);
?>
   <tr class="TableData">
<?
	if($_SESSION["LOGIN_USER_ID"]==$CREATE_USER_ID || $_SESSION["LOGIN_USER_PRIV"]==1)
	{
?>
      <td align="center">&nbsp;<input type="checkbox" name="email_select" value="<?=$ASK_DUTY_ID?>" onClick="check_one(self);"></td>
<?
	}
	else
	{
?>
	<td align="center">&nbsp;--</td>
<?
	}
?>
      <td nowrap align="center"><span <? if($EXPLANATION==""){ ?> style="color=red;" <? } ?> > <?=$CHECK_USER_NAME?></span></td>
      <td nowrap align="center"><?=$CHECK_MANAGER_NAME?></td>
      <td nowrap align="center"><?=$CHECK_DUTY_TIME=="0000-00-00 00:00:00"?"":$CHECK_DUTY_TIME;?></td>
      <td nowrap align="center"><?=$RECORD_TIME=="0000-00-00 00:00:00"?_("δ˵��"):$RECORD_TIME;?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('ask_duty_detail.php?ASK_DUTY_ID=<?=$ASK_DUTY_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
<?
	if($_SESSION["LOGIN_USER_ID"]==$CREATE_USER_ID || $_SESSION["LOGIN_USER_PRIV"]==1)
	{
?>
      <a href="modify.php?ASK_DUTY_ID=<?=$ASK_DUTY_ID?>"> <?=_("�޸�")?></a>&nbsp;
	 <a href="javascript:delete_ask_duty(<?=$ASK_DUTY_ID?>);"> <?=_("ɾ��")?></a>&nbsp;
<?
	}
?>
      </td>
   </tr>
<?
}

if($TOTAL_ITEMS>0)
{
?>

   <tr class="TableControl">
     <td colspan="19" >
       <div style="text-align:left;">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ�����Ϣ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp; 
        <div>
     </td>
   </tr>
    
<?
}else{
   Message("",_("��ȱ����Ա"));	
}
?>
</table>
</div>
</body>

</html>
