<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("�Ͷ�������Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_skills(SKILLS_ID)
{
  msg='<?=_("ȷ��Ҫɾ�����Ͷ�������Ϣ��")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?SKILLS_ID=" + SKILLS_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("Ҫɾ���Ͷ�������Ϣ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ�����Ͷ�������Ϣ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?SKILLS_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
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

if($ISSUE_DATE1!="")
{
  $TIME_OK=is_date($ISSUE_DATE1);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $ISSUE_DATE1=$ISSUE_DATE1." 00:00:00";
}

if($ISSUE_DATE2!="")
{
  $TIME_OK=is_date($ISSUE_DATE2);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $ISSUE_DATE2=$ISSUE_DATE2." 23:59:59";
}

if($EXPIRE_DATE1!="")
{
  $TIME_OK=is_date($EXPIRE_DATE1);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $EXPIRE_DATE1=$EXPIRE_DATE1." 00:00:00";
}

if($EXPIRE_DATE2!="")
{
  $TIME_OK=is_date($EXPIRE_DATE2);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $EXPIRE_DATE2=$EXPIRE_DATE2." 23:59:59";
}
//------------------------ ���������ַ��� ------------------
$CONDITION_STR="";
if($ABILITY_NAME!="")
   $CONDITION_STR.=" and ABILITY_NAME like '%".$ABILITY_NAME."%'";
if($STAFF_NAME!="")
   $CONDITION_STR.=" and STAFF_NAME like '%".$STAFF_NAME."%'";
if($ISSUING_AUTHORITY!="")
   $CONDITION_STR.=" and ISSUING_AUTHORITY like '%".$ISSUING_AUTHORITY."%'";
if($ISSUE_DATE1!="")
  $CONDITION_STR.=" and ISSUE_DATE>='$ISSUE_DATE1'";
if($ISSUE_DATE2!="")
  $CONDITION_STR.=" and ISSUE_DATE<='$ISSUE_DATE2'";   
if($EXPIRE_DATE1!="")
   $CONDITION_STR.=" and EXPIRE_DATE>='$EXPIRE_DATE1'";
if($EXPIRE_DATE2!="")
   $CONDITION_STR.=" and EXPIRE_DATE<='$EXPIRE_DATE2'";   
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("�Ͷ�������Ϣ��ѯ���")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_LABOR_SKILLS where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$SKILLS_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $SKILLS_COUNT++;

   $SKILLS_ID=$ROW["SKILLS_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $ABILITY_NAME=$ROW["ABILITY_NAME"];
   $SKILLS_LEVEL=$ROW["SKILLS_LEVEL"];
   $ISSUE_DATE=$ROW["ISSUE_DATE"];
   $EXPIRES=$ROW["EXPIRES"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
  	
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
    if($STAFF_NAME1=="")
	  {
	     $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW1=mysql_fetch_array($cursor1))
         $STAFF_NAME1=$ROW1["STAFF_NAME"];
	     $STAFF_NAME1=$STAFF_NAME1."("."<font color='red'>"._("�û���ɾ��")."</font>".")";
	   }
  
  if($SKILLS_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("��λԱ��")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("��֤����")?></td>
      <td nowrap align="center"><?=_("��Ч��")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
     	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$SKILLS_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$ABILITY_NAME?></td>
      <td nowrap align="center"><?=$SKILLS_LEVEL?></td>
      <td nowrap align="center"><?=$ISSUE_DATE?></td>
      <td nowrap align="center"><?=$EXPIRES?></td>
      <td align="center">
			<a href="javascript:;" onClick="window.open('skills_detail.php?SKILLS_ID=<?=$SKILLS_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
      <a href="modify.php?SKILLS_ID=<?=$SKILLS_ID?>"> <?=_("�޸�")?></a>
			<a href="javascript:delete_skills(<?=$SKILLS_ID?>);"> <?=_("ɾ��")?></a>
      </td>
      </td>
	</tr>
<?
}

if($SKILLS_COUNT==0)
{
   Message("",_("�޷����������Ͷ�������Ϣ��"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ�Ͷ�������Ϣ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp; 
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
