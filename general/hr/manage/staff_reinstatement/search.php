<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("Ա����ְ��Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
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
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
 //-----------�Ϸ���У��---------

if($APPLICATION_DATE1!="")
{
  $TIME_OK=is_date($APPLICATION_DATE1);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $APPLICATION_DATE1=$APPLICATION_DATE1." 00:00:00";
}

if($APPLICATION_DATE2!="")
{
  $TIME_OK=is_date($APPLICATION_DATE2);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $APPLICATION_DATE2=$APPLICATION_DATE2." 23:59:59";
}

if($REAPPOINTMENT_TIME_FACT1!="")
{
  $TIME_OK=is_date($REAPPOINTMENT_TIME_FACT1);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $REAPPOINTMENT_TIME_FACT1=$REAPPOINTMENT_TIME_FACT1." 00:00:00";
}

if($REAPPOINTMENT_TIME_FACT2!="")
{
  $TIME_OK=is_date($REAPPOINTMENT_TIME_FACT2);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $REAPPOINTMENT_TIME_FACT2=$REAPPOINTMENT_TIME_FACT2." 23:59:59";
}
//------------------------ ���������ַ��� ------------------
$CONDITION_STR="";
if($REAPPOINTMENT_STATE!="")
   $CONDITION_STR.=" and REAPPOINTMENT_STATE like '%".$REAPPOINTMENT_STATE."%'";
if($MATERIALS_CONDITION!="")
   $CONDITION_STR.=" and MATERIALS_CONDITION like '%".$MATERIALS_CONDITION."%'";
if($REINSTATEMENT_PERSON!="")
   $CONDITION_STR.=" and REINSTATEMENT_PERSON='$REINSTATEMENT_PERSON'";
if($REAPPOINTMENT_TYPE!="")
   $CONDITION_STR.=" and REAPPOINTMENT_TYPE='$REAPPOINTMENT_TYPE'";
if($APPLICATION_DATE1!="")
  $CONDITION_STR.=" and APPLICATION_DATE>='$APPLICATION_DATE1'";
if($APPLICATION_DATE2!="")
  $CONDITION_STR.=" and APPLICATION_DATE<='$APPLICATION_DATE2'";   
if($REAPPOINTMENT_TIME_FACT1!="")
   $CONDITION_STR.=" and REAPPOINTMENT_TIME_FACT>='$REAPPOINTMENT_TIME_FACT1'";
if($REAPPOINTMENT_TIME_FACT2!="")
   $CONDITION_STR.=" and REAPPOINTMENT_TIME_FACT<='$REAPPOINTMENT_TIME_FACT2'";   
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("Ա����ְ��Ϣ��ѯ���")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("REINSTATEMENT_PERSON").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_REINSTATEMENT where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
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
  
   $REINSTATEMENT_PERSON_NAME=substr(GetUserNameById($REINSTATEMENT_PERSON),0,-1);
   if($REINSTATEMENT_PERSON_NAME=="")
    {
        	$query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$REINSTATEMENT_PERSON'";
          $cursor2= exequery(TD::conn(),$query2);
          if($ROW2=mysql_fetch_array($cursor2))
          	$REINSTATEMENT_PERSON_NAME=$ROW2["STAFF_NAME"];    	
    	     $REINSTATEMENT_PERSON_NAME=$REINSTATEMENT_PERSON_NAME."("."<font color=red>"._("�û���ɾ��")."</font>".")";	
	}
  if($REINSTATEMENT_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("��ְ��Ա")?></td>
      <td nowrap align="center"><?=_("����ְ��")?></td>
      <td nowrap align="center"><?=_("��ְ����")?></td>
      <td nowrap align="center"><?=_("�⸴ְ����")?></td>
      <td nowrap align="center"><?=_("���ʻָ�����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
     	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$REINSTATEMENT_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$REINSTATEMENT_PERSON_NAME?></td>
      <td nowrap align="center"><?=$NOW_POSITION?></td>
      <td nowrap align="center"><?=$REAPPOINTMENT_TYPE?></td>
      <td nowrap align="center"><?=$REAPPOINTMENT_TIME_PLAN=="0000-00-00"?"":$REAPPOINTMENT_TIME_PLAN;?></td>
      <td nowrap align="center"><?=$FIRST_SALARY_TIME=="0000-00-00"?"":$FIRST_SALARY_TIME;?></td>
      <td align="center">
			<a href="javascript:;" onClick="window.open('reinstatement_detail.php?REINSTATEMENT_ID=<?=$REINSTATEMENT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
      <a href="modify.php?REINSTATEMENT_ID=<?=$REINSTATEMENT_ID?>"> <?=_("�޸�")?></a>&nbsp;
			<a href="javascript:delete_reinstatement(<?=$REINSTATEMENT_ID?>);"> <?=_("ɾ��")?></a>&nbsp;
      </td>
      </td>
	</tr>
<?
}

if($REINSTATEMENT_COUNT==0)
{
   Message("",_("�޷���������Ա����ְ��Ϣ��"));
   Button_Back();
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
   Button_Back();
}
?>
</body>

</html>
