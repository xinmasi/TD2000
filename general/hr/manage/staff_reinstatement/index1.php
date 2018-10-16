<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("Ա����ְ����");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
function delete_reinstatement(REINSTATEMENT_ID)
{
  msg='<?=_("ȷ��Ҫɾ�����ְ��Ϣ��")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?REINSTATEMENT_ID=" + REINSTATEMENT_ID+"&PAGE_START=<?=$PAGE_START?>";
     window.location=URL;
  }
}

function check_all()
{
   for(i=0;i<document.getElementsByName("email_select").length;i++)
   {
      if(document.getElementsByName("allbox").item(0).checked)
         document.getElementsByName("email_select").item(i).checked=true;
      else
         document.getElementsByName("email_select").item(i).checked=false;
   }

   if(i==0)
   {
      if(document.getElementsByName("allbox").item(0).checked)
         document.getElementsByName("email_select").checked=true;
      else
         document.getElementsByName("email_select").checked=false;
   }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox").item(0).checked=false;
}

function get_checked()
{
   checked_str="";
   for(i=0;i<document.getElementsByName("email_select").length;i++)
   {

      el=document.getElementsByName("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
   }

  if(i==0)
  {
      el=document.getElementsByName("email_select");
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
     alert("<?=_("Ҫɾ��Ա����ְ��Ϣ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ�����ְ��Ϣ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?REINSTATEMENT_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
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
$WHERE_STR = hr_priv("REINSTATEMENT_PERSON");
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_REINSTATEMENT where ".$WHERE_STR;
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("����Ա����ְ")?></span>&nbsp;
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
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("��ְ��Ա")?></td>
      <td nowrap align="center"><?=_("����ְ��")?></td>
      <td nowrap align="center"><?=_("��ְ����")?></td>
      <td nowrap align="center"><?=_("�⸴ְ����")?></td>
      <td nowrap align="center"><?=_("���ʻָ�����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </tr>
<?
}

$query = "SELECT * from HR_STAFF_REINSTATEMENT where ".$WHERE_STR." order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query, $connstatus);
$REINSTATEMENT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $REINSTATEMENT_COUNT++;

   $REINSTATEMENT_ID=$ROW["REINSTATEMENT_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $REAPPOINTMENT_TIME_PLAN=$ROW["REAPPOINTMENT_TIME_PLAN"];
   $REAPPOINTMENT_TYPE=$ROW["REAPPOINTMENT_TYPE"];
   $REINSTATEMENT_PERSON=$ROW["REINSTATEMENT_PERSON"];
   $NOW_POSITION=$ROW["NOW_POSITION"];
   $FIRST_SALARY_TIME=$ROW["FIRST_SALARY_TIME"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
	 $REAPPOINTMENT_TYPE=get_hrms_code_name($REAPPOINTMENT_TYPE,"HR_STAFF_REINSTATEMENT");
	 $REINSTATEMENT_PERSON_NAME = substr(GetUserNameById($REINSTATEMENT_PERSON),0,-1);
	 if($REINSTATEMENT_PERSON_NAME == ""){
		 $REINSTATEMENT_PERSON_NAME = "<font color=red>"._("�û���ɾ��")."</font>";
	 }
	 
?>
   <tr class="TableData">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$REINSTATEMENT_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$REINSTATEMENT_PERSON_NAME?></td>
      <td nowrap align="center"><?=$NOW_POSITION?></td>
      <td nowrap align="center"><?=$REAPPOINTMENT_TYPE?></td>
      <td nowrap align="center"><?=$REAPPOINTMENT_TIME_PLAN=="0000-00-00"?"":$REAPPOINTMENT_TIME_PLAN;?></td>
      <td nowrap align="center"><?=$FIRST_SALARY_TIME=="0000-00-00"?"":$FIRST_SALARY_TIME;?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('reinstatement_detail.php?REINSTATEMENT_ID=<?=$REINSTATEMENT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
      <a href="modify.php?REINSTATEMENT_ID=<?=$REINSTATEMENT_ID?>"> <?=_("�޸�")?></a>&nbsp;
			<a href="javascript:delete_reinstatement(<?=$REINSTATEMENT_ID?>);"> <?=_("ɾ��")?></a>&nbsp;
      </td>
   </tr>
<?
}

if($TOTAL_ITEMS>0)
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡԱ����ְ��Ϣ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp; 
     </td>
   </tr>
<?
}else{
   Message("",_("��Ա����ְ��Ϣ��¼"));	
}
?>
</table>
</body>

</html>
