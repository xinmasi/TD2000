<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("���µ�����Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_transfer(TRANSFER_ID)
{
  msg='<?=_("ȷ��Ҫɾ���������µ�����Ϣ��")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?TRANSFER_ID=" + TRANSFER_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("Ҫɾ�����µ�����Ϣ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ���������µ�����Ϣ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?TRANSFER_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
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

if($TRANSFER_DATE1!="")
{
  $TIME_OK=is_date($TRANSFER_DATE1);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $TRANSFER_DATE1=$TRANSFER_DATE1." 00:00:00";
}

if($TRANSFER_DATE2!="")
{
  $TIME_OK=is_date($TRANSFER_DATE2);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $TRANSFER_DATE2=$TRANSFER_DATE2." 23:59:59";
}

if($TRANSFER_EFFECTIVE_DATE1!="")
{
  $TIME_OK=is_date($TRANSFER_EFFECTIVE_DATE1);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $TRANSFER_EFFECTIVE_DATE1=$TRANSFER_EFFECTIVE_DATE1." 00:00:00";
}

if($TRANSFER_EFFECTIVE_DATE2!="")
{
  $TIME_OK=is_date($TRANSFER_EFFECTIVE_DATE2);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $TRANSFER_EFFECTIVE_DATE2=$TRANSFER_EFFECTIVE_DATE2." 23:59:59";
}
//------------------------ ���������ַ��� ------------------
$CONDITION_STR="";
if($TRAN_REASON!="")
   $CONDITION_STR.=" and TRAN_REASON like '%".$TRAN_REASON."%'";
if($TRANSFER_PERSON!="")
   $CONDITION_STR.=" and TRANSFER_PERSON='$TRANSFER_PERSON'";
if($TRANSFER_TYPE!="")
   $CONDITION_STR.=" and TRANSFER_TYPE='$TRANSFER_TYPE'";
if($TRANSFER_DATE1!="")
  $CONDITION_STR.=" and TRANSFER_DATE>='$TRANSFER_DATE1'";
if($TRANSFER_DATE2!="")
  $CONDITION_STR.=" and TRANSFER_DATE<='$TRANSFER_DATE2'";   
if($TRANSFER_EFFECTIVE_DATE1!="")
   $CONDITION_STR.=" and TRANSFER_EFFECTIVE_DATE>='$TRANSFER_EFFECTIVE_DATE1'";
if($TRANSFER_EFFECTIVE_DATE2!="")
   $CONDITION_STR.=" and TRANSFER_EFFECTIVE_DATE<='$TRANSFER_EFFECTIVE_DATE2'";   
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("���µ�����Ϣ��ѯ���")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("TRANSFER_PERSON").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_TRANSFER where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$TRANSFER_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $TRANSFER_COUNT++;

   $TRANSFER_ID=$ROW["TRANSFER_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $TRANSFER_PERSON=$ROW["TRANSFER_PERSON"];
   $TRANSFER_TYPE=$ROW["TRANSFER_TYPE"];
   $TRANSFER_DATE=$ROW["TRANSFER_DATE"];
   $TRANSFER_EFFECTIVE_DATE=$ROW["TRANSFER_EFFECTIVE_DATE"];
   $ADD_TIME=$ROW["ADD_TIME"];
  	
  $TRANSFER_TYPE=get_hrms_code_name($TRANSFER_TYPE,"HR_STAFF_TRANSFER");
  
   $TRANSFER_PERSON_NAME=substr(GetUserNameById($TRANSFER_PERSON),0,-1);
   if($TRANSFER_PERSON_NAME=="")
   {    
     $query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$TRANSFER_PERSON'";
     $cursor2= exequery(TD::conn(),$query2);
     if($ROW2=mysql_fetch_array($cursor2))
        $TRANSFER_PERSON_NAME=$ROW2["STAFF_NAME"];    	
    $TRANSFER_PERSON_NAME=$TRANSFER_PERSON_NAME."(<font color='red'>"._("�û���ɾ��")."</font>)";
   }
  
  if($TRANSFER_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("������Ա")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("������Ч����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
     	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$TRANSFER_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$TRANSFER_PERSON_NAME?></td>
      <td nowrap align="center"><?=$TRANSFER_TYPE?></td>
      <td nowrap align="center"><?=$TRANSFER_DATE=="0000-00-00"?"":$TRANSFER_DATE;?></td>
      <td nowrap align="center"><?=$TRANSFER_EFFECTIVE_DATE=="0000-00-00"?"":$TRANSFER_EFFECTIVE_DATE;?></td>
      <td align="center">
			<a href="javascript:;" onClick="window.open('transfer_detail.php?TRANSFER_ID=<?=$TRANSFER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
      <a href="modify.php?TRANSFER_ID=<?=$TRANSFER_ID?>"> <?=_("�޸�")?></a>&nbsp;
			<a href="javascript:delete_transfer(<?=$TRANSFER_ID?>);"> <?=_("ɾ��")?></a>&nbsp;
      </td>
      </td>
	</tr>
<?
}

if($TRANSFER_COUNT==0)
{
   Message("",_("�޷������������µ�����Ϣ��"));
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
