<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("����������Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_experience(W_EXPERIENCE_ID)
{
  msg='<?=_("ȷ��Ҫɾ�������������")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?W_EXPERIENCE_ID=" + W_EXPERIENCE_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("Ҫɾ������������Ϣ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ�������������")?>';
  if(window.confirm(msg))
  {
    url="delete.php?W_EXPERIENCE_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
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
//------------------------ ���������ַ��� ------------------
$CONDITION_STR="";
if($STAFF_NAME!="")
   $CONDITION_STR.=" and STAFF_NAME='$STAFF_NAME'";
if($POST_OF_JOB!="")
   $CONDITION_STR.=" and POST_OF_JOB like '%".$POST_OF_JOB."%'";
if($WORK_UNIT!="")
   $CONDITION_STR.=" and WORK_UNIT like '%".$WORK_UNIT."%'";  
if($MOBILE!="")
   $CONDITION_STR.=" and MOBILE like '%".$MOBILE."%'";   
if($WORK_CONTENT!="")
   $CONDITION_STR.=" and WORK_CONTENT like '%".$WORK_CONTENT."%'";  
if($KEY_PERFORMANCE!="")
   $CONDITION_STR.=" and KEY_PERFORMANCE like '%".$KEY_PERFORMANCE."%'";  
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("����������ѯ���")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_WORK_EXPERIENCE where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$EXPERIENCE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $EXPERIENCE_COUNT++;

   $W_EXPERIENCE_ID=$ROW["W_EXPERIENCE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $WORK_UNIT=$ROW["WORK_UNIT"];
   $MOBILE=$ROW["MOBILE"];
   $POST_OF_JOB=$ROW["POST_OF_JOB"];
   $WITNESS=$ROW["WITNESS"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
  	
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
    if($STAFF_NAME1=="")
    {    
      $query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
      $cursor2= exequery(TD::conn(),$query2);
      if($ROW2=mysql_fetch_array($cursor2))
         $STAFF_NAME1=$ROW2["STAFF_NAME"];    	
	    $STAFF_NAME1=$STAFF_NAME1."(<font color='red'>"._("�û���ɾ��")."</font>)";
    }
   if(strlen($WORK_UNIT) > 20)
	 $WORK_UNIT=substr($WORK_UNIT, 0, 20);
  
  if($EXPERIENCE_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("��λԱ��")?></td>
      <td nowrap align="center"><?=_("������λ")?></td>
      <td nowrap align="center"><?=_("��ҵ���")?></td>
      <td nowrap align="center"><?=_("����ְ��")?></td>
      <td nowrap align="center"><?=_("֤����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
     	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$W_EXPERIENCE_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$WORK_UNIT?></td>
      <td nowrap align="center"><?=$MOBILE?></td>
      <td nowrap align="center"><?=$POST_OF_JOB?></td>
      <td nowrap align="center"><?=$WITNESS?></td>
      <td align="center">
			<a href="javascript:;" onClick="window.open('experience_detail.php?W_EXPERIENCE_ID=<?=$W_EXPERIENCE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
      <a href="modify.php?W_EXPERIENCE_ID=<?=$W_EXPERIENCE_ID?>"> <?=_("�޸�")?></a>
			<a href="javascript:delete_experience(<?=$W_EXPERIENCE_ID?>);"> <?=_("ɾ��")?></a>
      </td>
      </td>
	</tr>
<?
}

if($EXPERIENCE_COUNT==0)
{
   Message("",_("�޷��������Ĺ���������Ϣ��"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ���µ�����Ϣ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp; 
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
