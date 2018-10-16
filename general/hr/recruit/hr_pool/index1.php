<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("�˲ŵ�������");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
function delete_pool(EXPERT_ID)
{
  msg='<?=_("ȷ��Ҫɾ�����˲ŵ�����Ϣ��")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?EXPERT_ID=" + EXPERT_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("Ҫɾ���˲ŵ�����Ϣ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ�����˲ŵ�����Ϣ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?EXPERT_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
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
$WHERE_STR = hr_priv("");

if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_RECRUIT_POOL where ".$WHERE_STR;
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("�����˲ŵ���")?></span>&nbsp;
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
      <td nowrap align="center"><?=_("�ƻ�����")?></td>
      <td nowrap align="center"><?=_("ӦƸ������")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("��ϵ�绰")?></td>
      <td nowrap align="center"><?=_("ѧ��")?></td>
      <td nowrap align="center"><?=_("רҵ")?></td>
      <td nowrap align="center"><?=_("��λ")?></td>
      <td nowrap align="center"><?=_("���ʱ��")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </tr>
<?
}
	
	
$query = "SELECT * from  HR_RECRUIT_POOL where ".$WHERE_STR." order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query, $connstatus);
$POOL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $POOL_COUNT++;

   $EXPERT_ID=$ROW["EXPERT_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $PLAN_NAME=$ROW["PLAN_NAME"];
   $POSITION=$ROW["POSITION"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $EMPLOYEE_NAME=$ROW["EMPLOYEE_NAME"];
   $EMPLOYEE_BIRTH=$ROW["EMPLOYEE_BIRTH"];
   $EMPLOYEE_PHONE=$ROW["EMPLOYEE_PHONE"];
   $EMPLOYEE_HIGHEST_SCHOOL=$ROW["EMPLOYEE_HIGHEST_SCHOOL"];
   $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
   
   $EMPLOYEE_HIGHEST_SCHOOL=get_hrms_code_name($EMPLOYEE_HIGHEST_SCHOOL,"STAFF_HIGHEST_SCHOOL");
	 $POSITION=get_hrms_code_name($POSITION,"POOL_POSITION");
	 $EMPLOYEE_MAJOR=get_hrms_code_name($EMPLOYEE_MAJOR,"POOL_EMPLOYEE_MAJOR");
?>
   <tr class="TableData">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$EXPERT_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$PLAN_NAME?></td>
      <td nowrap align="center"><?=$EMPLOYEE_NAME?></td>
      <td nowrap align="center"><?=$EMPLOYEE_BIRTH?></td>
      <td nowrap align="center"><?=$EMPLOYEE_PHONE?></td>
      <td nowrap align="center"><?=$EMPLOYEE_HIGHEST_SCHOOL?></td>
      <td nowrap align="center"><?=$EMPLOYEE_MAJOR?></td>
      <td nowrap align="center"><?=$POSITION?></td>
      <td nowrap align="center"><?=$ADD_TIME?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('pool_detail.php?EXPERT_ID=<?=$EXPERT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
      <a href="modify.php?EXPERT_ID=<?=$EXPERT_ID?>"> <?=_("�޸�")?></a>&nbsp;
			<a href="javascript:delete_pool(<?=$EXPERT_ID?>);"> <?=_("ɾ��")?></a>&nbsp;
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
       <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ�˲ŵ���")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp; 
     </td>
   </tr>
<?
}else{
   Message("",_("���˲ŵ�����¼"));	
}
?>
</table>
</body>

</html>
