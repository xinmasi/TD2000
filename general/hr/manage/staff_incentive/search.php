<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("������Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_incentive(INCENTIVE_ID)
{
  msg='<?=_("ȷ��Ҫɾ���ý�����Ϣ��")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?INCENTIVE_ID=" + INCENTIVE_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("Ҫɾ��������Ϣ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ���ý�����Ϣ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?INCENTIVE_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
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

if($INCENTIVE_TIME1!="")
{
  $TIME_OK=is_date($INCENTIVE_TIME1);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $INCENTIVE_TIME1=$INCENTIVE_TIME1." 00:00:00";
}

if($INCENTIVE_TIME2!="")
{
  $TIME_OK=is_date($INCENTIVE_TIME2);

  if(!$TIME_OK)
  { Message(_("����"),_("���ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $INCENTIVE_TIME2=$INCENTIVE_TIME2." 23:59:59";
}

//------------------------ ���������ַ��� ------------------
$CONDITION_STR="";
if($HR_TABLE==1)
{
	 $YEAR=date("Y",time());
   $START_DATE=$YEAR."-01-01";
   $END_DATE=$YEAR."-12-31";
   $CONDITION_STR.=" and INCENTIVE_TIME >= '$START_DATE' and INCENTIVE_TIME <= '$END_DATE'";
}
else
{
if($STAFF_NAME!="")
   $CONDITION_STR.=" and STAFF_NAME='$STAFF_NAME'";
if($INCENTIVE_ITEM!="")
   $CONDITION_STR.=" and INCENTIVE_ITEM='$INCENTIVE_ITEM'";
if($INCENTIVE_TYPE!="")
   $CONDITION_STR.=" and INCENTIVE_TYPE='$INCENTIVE_TYPE'";
if($INCENTIVE_TIME1!="")
  $CONDITION_STR.=" and INCENTIVE_TIME>='$INCENTIVE_TIME1'";
if($INCENTIVE_TIME2!="")
  $CONDITION_STR.=" and INCENTIVE_TIME<='$INCENTIVE_TIME2'";   
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("������Ϣ��ѯ���")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_INCENTIVE where".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$INCENTIVE_COUNT=0;
$ALL_INCENTIVE_AMOUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $INCENTIVE_COUNT++;

   $INCENTIVE_ID=$ROW["INCENTIVE_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $INCENTIVE_ITEM=$ROW["INCENTIVE_ITEM"];
   $INCENTIVE_TIME=$ROW["INCENTIVE_TIME"]=="0000-00-00"?"":$ROW["INCENTIVE_TIME"];
   $INCENTIVE_TYPE=$ROW["INCENTIVE_TYPE"];
   $INCENTIVE_AMOUNT=$ROW["INCENTIVE_AMOUNT"];
   $ADD_TIME=$ROW["ADD_TIME"];
  	
   $ALL_INCENTIVE_AMOUNT +=  $INCENTIVE_AMOUNT;
  	
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 if($STAFF_NAME1=="")
	 {
	   $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
       $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
          $STAFF_NAME1=$ROW1["STAFF_NAME"];

	    $STAFF_NAME1=$STAFF_NAME1."("."<font color='red'>"._("�û���ɾ��")."</font>".")";
	  }
	 $INCENTIVE_ITEM=get_hrms_code_name($INCENTIVE_ITEM,"HR_STAFF_INCENTIVE1");
	 $INCENTIVE_TYPE=get_hrms_code_name($INCENTIVE_TYPE,"INCENTIVE_TYPE");
  
  if($INCENTIVE_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("��λԱ��")?></td>
      <td nowrap align="center"><?=_("������Ŀ")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("���ͽ��")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
     	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$INCENTIVE_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$INCENTIVE_ITEM?></td>
      <td nowrap align="center"><?=$INCENTIVE_TIME?></td>
      <td nowrap align="center"><?=$INCENTIVE_TYPE?></td>
      <td nowrap align="center"><?=$INCENTIVE_AMOUNT?></td>
      <td align="center">
			<a href="javascript:;" onClick="window.open('incentive_detail.php?INCENTIVE_ID=<?=$INCENTIVE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
      <a href="modify.php?INCENTIVE_ID=<?=$INCENTIVE_ID?>"> <?=_("�޸�")?></a>
			<a href="javascript:delete_incentive(<?=$INCENTIVE_ID?>);"> <?=_("ɾ��")?></a>
      </td>
      </td>
	</tr>
<?
}

if($INCENTIVE_COUNT==0)
{
   Message("",_("�޷��������Ľ�����Ϣ��"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ������Ϣ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp; 
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=_("�ܼƣ�").$ALL_INCENTIVE_AMOUNT?>
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
