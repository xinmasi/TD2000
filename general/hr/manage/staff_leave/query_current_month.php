<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("����Ա����ְ��Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_leave(LEAVE_ID)
{
  msg='<?=_("ȷ��Ҫɾ������Ա����ְ��Ϣ��")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?LEAVE_ID=" + LEAVE_ID+"&PAGE_START=<?=$PAGE_START?>&PAGE_FROM=query_current_month";
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

  msg='<?=_("ȷ��Ҫɾ������Ա����ְ��Ϣ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?LEAVE_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>&PAGE_FROM=query_current_month";
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
$CUR_MONTH = date("Y-m");
$CUR_MONTH_BEGIN = $CUR_MONTH.'-01';
$CUR_MONTH_END = $CUR_MONTH.'-31';


//------------------------ ���������ַ��� ------------------
$CONDITION_STR="";
$CONDITION_STR.=" and QUIT_TIME_FACT>='$CUR_MONTH_BEGIN'";
$CONDITION_STR.=" and QUIT_TIME_FACT<='$CUR_MONTH_END'";   

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("����Ա����ְ��Ϣ��ѯ���")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("CREATE_USER_ID").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_LEAVE where ".$CONDITION_STR."order by ADD_TIME desc";

$cursor=exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   
   
   $LEAVE_ID=$ROW["LEAVE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $QUIT_TIME_PLAN=$ROW["QUIT_TIME_PLAN"];
   $QUIT_TYPE=$ROW["QUIT_TYPE"];
   $LAST_SALARY_TIME=$ROW["LAST_SALARY_TIME"];
   $LEAVE_PERSON=$ROW["LEAVE_PERSON"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $QUIT_TIME_FACT=$ROW["QUIT_TIME_FACT"]; 
   $POSITION=$ROW["POSITION"];
   $IS_REINSTATEMENT=$ROW["IS_REINSTATEMENT"];   
   $LEAVE_DEPT =$ROW["LEAVE_DEPT"];
   $SALARY =$ROW["SALARY"];
   
   //��ѯ��ְ�������������
   $query1 = "SELECT * from hr_staff_reinstatement where REINSTATEMENT_PERSON='".$LEAVE_PERSON."'";
   $cursor1=exequery(TD::conn(),$query1);
   $REINSTATEMENT_COUNT=0;
   while($ROW=mysql_fetch_array($cursor1))
   {
        $REINSTATEMENT_COUNT++;
   }
   
   if($REINSTATEMENT_COUNT){
        continue;
   }
   $LEAVE_COUNT++;   
   $LEAVE_DEPT_NAME=substr(GetDeptNameById($LEAVE_DEPT),0,-1); 
   $QUIT_TYPE=get_hrms_code_name($QUIT_TYPE,"HR_STAFF_LEAVE");
   $LEAVE_PERSON_NAME=substr(GetUserNameById($LEAVE_PERSON),0,-1);
	 if($LEAVE_PERSON_NAME=="")
	 {
	 	 $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW1=mysql_fetch_array($cursor1))
         $LEAVE_PERSON=$ROW1["STAFF_NAME"];
	    $LEAVE_PERSON_NAME=$LEAVE_PERSON."("._("<font color=red>�û���ɾ��</font>").")";	 
	 }
	 if($POSITION=="")
	 {
	    $query1 = "SELECT JOB_POSITION from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW1=mysql_fetch_array($cursor1))
           $POSITION=$ROW1["JOB_POSITION"];
     }
  
  if($LEAVE_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("��ְ��Ա")?></td>
      <td nowrap align="center"><?=_("��ְ����")?></td>
      <td nowrap align="center"><?=_("����ְ��")?></td>
      <td nowrap align="center"><?=_("��ְ����")?></td>
      <td nowrap align="center"><?=_("����ְ����")?></td>
      <td nowrap align="center"><?=_("ʵ����ְ����")?></td>
      <td nowrap align="center"><?=_("���ʽ�ֹ����")?></td>
      <td nowrap align="center"><?=_("��ְ����н��")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </thead> 
<?
   }
?>
     <tr class="TableData">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$LEAVE_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$LEAVE_PERSON_NAME?></td>
      <td nowrap align="center"><?=$LEAVE_DEPT_NAME?></td>       
      <td nowrap align="center"><?=$POSITION?></td>
      <td nowrap align="center"><?=$QUIT_TYPE?></td>
      <td nowrap align="center"><?=$QUIT_TIME_PLAN=="0000-00-00"?"":$QUIT_TIME_PLAN;?></td>
      <td nowrap align="center"><?=$QUIT_TIME_FACT=="0000-00-00"?"":$QUIT_TIME_FACT;?></td>
      <td nowrap align="center"><?=$LAST_SALARY_TIME=="0000-00-00"?"":$LAST_SALARY_TIME;?></td>
      <td nowrap align="center"><?=$SALARY;?></td>
      <td align="center">
      <a href="javascript:;" onClick="window.open('leave_detail.php?LEAVE_ID=<?=$LEAVE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
<?
   if($IS_REINSTATEMENT == 0)
   {
?>
        <a href="../staff_reinstatement/new.php?USER_ID=<?=$LEAVE_PERSON?>"> <?=_("��ְ")?></a>&nbsp;
<?
   }
?>      
      <a href="modify.php?LEAVE_ID=<?=$LEAVE_ID?>&PAGE_FROM=query_current_month"> <?=_("�޸�")?></a>&nbsp;
			<a href="javascript:delete_leave(<?=$LEAVE_ID?>);"> <?=_("ɾ��")?></a>&nbsp;
      </td>
      </td>
	</tr>
<?
}
if($LEAVE_COUNT==0)
{
   Message("",_("�޷���������Ա����ְ��Ϣ��"));
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡԱ����ְ��Ϣ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp; 
     </td>
   </tr>
</table>
<?
   //Button_Back();
}
?>
</body>

</html>
