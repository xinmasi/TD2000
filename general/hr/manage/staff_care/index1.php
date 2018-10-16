<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("Ա���ػ�����");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
function delete_care(CARE_ID)
{
  msg='<?=_("ȷ��Ҫɾ������Ա���ػ���")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?CARE_ID=" + CARE_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("Ҫɾ������Ա���ػ���������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ������Ա���ػ���")?>';
  if(window.confirm(msg))
  {
    url="delete.php?CARE_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
    location=url;
  }
}

function change_type(type)
{
   window.location="index1.php?start=<?=$start?>&CARE_TYPE="+type;
}
</script>

<body class="bodycolor">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);

$WHERE_STR = hr_priv("BY_CARE_STAFFS");
if($CARE_TYPE!="")
   $WHERE_STR .= " and CARE_TYPE='$CARE_TYPE'";

if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_CARE where ".$WHERE_STR;
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("����Ա���ػ�")?></span>&nbsp;
       <select name="CARE_TYPE" class="BigSelect" onChange="change_type(this.value);">
          <option value="" <?if($CARE_TYPE=="") echo " selected";?>><?=_("��������")?></option>
          <?=hrms_code_list("HR_STAFF_CARE",$CARE_TYPE)?>
       </select>
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
      <td nowrap align="center"><?=_("�ػ�����")?></td>
      <td nowrap align="center"><?=_("���ػ�Ա��")?></td>
      <td nowrap align="center"><?=_("�ػ���֧����/��")?></td>
      <td nowrap align="center"><?=_("������")?></td>
      <td nowrap align="center"><?=_("�ػ�����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </tr>
<?
}

$query = "SELECT * from HR_STAFF_CARE where ".$WHERE_STR." order by CARE_DATE desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query, $connstatus);
$CARE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $CARE_COUNT++;

   $CARE_ID=$ROW["CARE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $BY_CARE_STAFFS=$ROW["BY_CARE_STAFFS"];
   $CARE_DATE=$ROW["CARE_DATE"]=="0000-00-00"?"":$ROW["CARE_DATE"];
   $PARTICIPANTS=$ROW["PARTICIPANTS"];
   $CARE_FEES=$ROW["CARE_FEES"];
   $CARE_TYPE=$ROW["CARE_TYPE"];
   $ADD_TIME=$ROW["ADD_TIME"]=="0000-00-00 00:00:00"?"":$ROW["ADD_TIME"];
   
   
   $TYPE_NAME=get_hrms_code_name($CARE_TYPE,"HR_STAFF_CARE");
   
   $BY_CARE_STAFFS_NAME = substr(GetUserNameById($BY_CARE_STAFFS),0,-1);
  if($BY_CARE_STAFFS_NAME=="")
  {
  	 $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$BY_CARE_STAFFS'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
        	$BY_CARE_STAFFS_NAME=$ROW1["STAFF_NAME"];
      $BY_CARE_STAFFS_NAME=$BY_CARE_STAFFS_NAME."("."<font color='red'>"._("�û���ɾ��")."</font>".")";
   }
   else
   {
      $query2 = "SELECT DEPT_ID from USER where USER_ID='$BY_CARE_STAFFS'";
      $cursor2= exequery(TD::conn(),$query2);
      if($ROW2=mysql_fetch_array($cursor2))
      	$DEPT_ID=$ROW2['DEPT_ID'];
      if($DEPT_ID=='0')
    		$BY_CARE_STAFFS_NAME=$BY_CARE_STAFFS_NAME."("."<font color='red'>"._("��ְ/�ⲿ��Ա")."</font>".")";   		
   }
   $PARTICIPANTS_NAME = substr(GetUserNameById($PARTICIPANTS),0,-1);
?>
   <tr class="TableData">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$CARE_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$TYPE_NAME?></td>
      <td nowrap align="center"><?=$BY_CARE_STAFFS_NAME?></td>
      <td nowrap align="center"><?=$CARE_FEES?> (<?=_("Ԫ")?>)</td>
      <td align="center"><?=$PARTICIPANTS_NAME?></td>
      <td nowrap align="center"><?=$CARE_DATE?></td>
      <td align="center">
      <a href="javascript:;" onClick="window.open('care_detail.php?CARE_ID=<?=$CARE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
      <a href="modify.php?CARE_ID=<?=$CARE_ID?>"> <?=_("�޸�")?></a>&nbsp;
			<a href="javascript:delete_care(<?=$CARE_ID?>);"> <?=_("ɾ��")?></a>&nbsp;
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
       <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡԱ���ػ�")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp; 
     </td>
   </tr>
<?
}else{
   Message("",_("��Ա���ػ���¼"));	
}
?>
</table>
</body>

</html>
