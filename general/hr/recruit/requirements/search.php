<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("��Ƹ������Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_requirements(REQUIREMENTS_ID)
{
  msg='<?=_("ȷ��Ҫɾ��������Ƹ������Ϣ��")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?REQUIREMENTS_ID=" + REQUIREMENTS_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("Ҫɾ����Ƹ������Ϣ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ��������Ƹ������Ϣ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?REQUIREMENTS_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
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

if($REQU_TIME1!="")
{
  $TIME_OK=is_date($REQU_TIME1);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $REQU_TIME1=$REQU_TIME1." 00:00:00";
}

if($REQU_TIME2!="")
{
  $TIME_OK=is_date($REQU_TIME2);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $REQU_TIME2=$REQU_TIME2." 23:59:59";
}
//------------------------ ���������ַ��� ------------------
$CONDITION_STR="";
if($REQU_NO!="")
   $CONDITION_STR.=" and REQU_NO='$REQU_NO'";
if($REQU_NUM!="")
   $CONDITION_STR.=" and REQU_NUM='$REQU_NUM'";
if($REQU_JOB!="")
   $CONDITION_STR.=" and REQU_JOB like '%".$REQU_JOB."%'";   
if($REQU_DEPT!="")
   $CONDITION_STR.=" and REQU_DEPT='$REQU_DEPT'";  
if($REQU_TIME1!="")
   $CONDITION_STR.=" and REQU_TIME>='$REQU_TIME1'";
if($REQU_TIME2!="")
   $CONDITION_STR.=" and REQU_TIME<='$REQU_TIME2'";   
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("��Ƹ������Ϣ��ѯ���")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("").$CONDITION_STR;
$query = "SELECT * from HR_RECRUIT_REQUIREMENTS where".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$HR_RECRUIT_REQUIREMENTS=0;
while($ROW=mysql_fetch_array($cursor))
{
   $HR_RECRUIT_REQUIREMENTS++;

   $REQUIREMENTS_ID=$ROW["REQUIREMENTS_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $REQU_NO=$ROW["REQU_NO"];
   $REQU_JOB=$ROW["REQU_JOB"];
   $REQU_NUM=$ROW["REQU_NUM"];
   $REQU_DEPT=$ROW["REQU_DEPT"];
   $REQU_TIME=$ROW["REQU_TIME"];
   $ADD_TIME=$ROW["ADD_TIME"]; 

  if($REQU_DEPT=="ALL_DEPT")
     $REQU_DEPT_NAME=_("ȫ�岿��");
  else  
     $REQU_DEPT_NAME=substr(GetDeptNameById($REQU_DEPT),0,-1);	
  
  if($HR_RECRUIT_REQUIREMENTS==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("������")?></td>
      <td nowrap align="center"><?=_("�����λ")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("������")?></td>
      <td nowrap align="center"><?=_("�ù�����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
     	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$REQUIREMENTS_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$REQU_NO?></td>
      <td nowrap align="center"><?=$REQU_JOB?></td>
      <td nowrap align="center"><?=$REQU_NUM?><?=_("���ˣ�")?></td>
      <td nowrap align="center"><?=$REQU_DEPT_NAME?></td>
      <td nowrap align="center"><?=$REQU_TIME?></td>
      <td align="center">
			<a href="javascript:;" onClick="window.open('requirements_detail.php?REQUIREMENTS_ID=<?=$REQUIREMENTS_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
      <a href="modify.php?REQUIREMENTS_ID=<?=$REQUIREMENTS_ID?>"> <?=_("�޸�")?></a>
			<a href="javascript:delete_requirements(<?=$REQUIREMENTS_ID?>);"> <?=_("ɾ��")?></a>
      </td>
      </td>
	</tr>
<?
}

if($HR_RECRUIT_REQUIREMENTS==0)
{
   Message("",_("�޷�����������Ƹ������Ϣ��"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ��Ƹ������Ϣ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp; 
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
