<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("����ϵ��ѯ");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_relatives(RELATIVES_ID)
{
  msg='<?=_("ȷ��Ҫɾ��������ϵ��")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?RELATIVES_ID=" + RELATIVES_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("Ҫɾ������ϵ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ��������ϵ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?RELATIVES_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
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
if($JOB_OCCUPATION!="")
   $CONDITION_STR.=" and JOB_OCCUPATION like '%".$JOB_OCCUPATION."%'";
if($STAFF_NAME!="")
   $CONDITION_STR.=" and STAFF_NAME='$STAFF_NAME'";
if($MEMBER!="")
   $CONDITION_STR.=" and MEMBER='$MEMBER'";
if($RELATIONSHIP!="")
  $CONDITION_STR.=" and RELATIONSHIP='$RELATIONSHIP'";
if($WORK_UNIT!="")
   $CONDITION_STR.=" and WORK_UNIT like '%".$WORK_UNIT."%'";
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("����ϵ��ѯ���")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR= hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT DEPT_ID from HR_MANAGER where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',DEPT_HR_MANAGER)";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
   $DEPT_ID_STR.=$ROW["DEPT_ID"].",";

$query = "SELECT * from HR_STAFF_RELATIVES where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$RELATIVES_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $RELATIVES_COUNT++;

   $RELATIVES_ID=$ROW["RELATIVES_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $MEMBER=$ROW["MEMBER"];
   $RELATIONSHIP=$ROW["RELATIONSHIP"];
   $PERSONAL_TEL=$ROW["PERSONAL_TEL"];
   $JOB_OCCUPATION=$ROW["JOB_OCCUPATION"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $ADD_TIME=$ROW["ADD_TIME"];
  	
   $RELATIONSHIP=get_hrms_code_name($RELATIONSHIP,"HR_STAFF_RELATIVES");
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	  if($STAFF_NAME1=="")
	  {
	     $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW1=mysql_fetch_array($cursor1))
         $STAFF_NAME1=$ROW1["STAFF_NAME"];
	     $STAFF_NAME1=$STAFF_NAME1."("."<font color='red'>"._("�û���ɾ��")."</font>".")";
	   }
 if($RELATIVES_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("��λԱ��")?></td>
      <td nowrap align="center"><?=_("��Ա����")?></td>
      <td nowrap align="center"><?=_("�뱾�˹�ϵ")?></td>
      <td nowrap align="center"><?=_("ְҵ")?></td>
      <td nowrap align="center"><?=_("��ϵ�绰�����ˣ�")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
     	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$RELATIVES_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$MEMBER?></td>
      <td nowrap align="center"><?=$RELATIONSHIP?></td>
      <td nowrap align="center"><?=$JOB_OCCUPATION?></td>
      <td nowrap align="center"><?=$PERSONAL_TEL?></td>
      <td align="center">
			<a href="javascript:;" onClick="window.open('relatives_detail.php?RELATIVES_ID=<?=$RELATIVES_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
      <a href="modify.php?RELATIVES_ID=<?=$RELATIVES_ID?>"> <?=_("�޸�")?></a>&nbsp;
			<a href="javascript:delete_relatives(<?=$RELATIVES_ID?>);"> <?=_("ɾ��")?></a>&nbsp;
      </td>
      </td>
	</tr>
<?
}

if($RELATIVES_COUNT==0)
{
   Message("",_("�޷�������������ϵ��Ϣ��"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ����ϵ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp; 
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
