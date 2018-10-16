<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("��Ƹ�������");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
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

function export_requirements()
{
  export_str=get_checked();
  if(export_str=="")
  {
     alert("<?=_("Ҫ������Ƹ������Ϣ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫ������ѡ��Ƹ������Ϣ��")?>';
  if(window.confirm(msg))
  {
    url="export.php?REQUIREMENTS_ID="+ export_str +"&PAGE_START=<?=$PAGE_START?>";
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
   $query = "SELECT count(*) from HR_RECRUIT_REQUIREMENTS where ".$WHERE_STR;
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("������Ƹ����")?></span>&nbsp;
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
      <td nowrap align="center"><?=_("������")?></td>
      <td nowrap align="center"><?=_("�����λ")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("������")?></td>
      <td nowrap align="center"><?=_("�ù�����")?></td>
      <td width="180"><?=_("����")?></td>
  </tr>
<?
}

$query = "SELECT * from  HR_RECRUIT_REQUIREMENTS where ".$WHERE_STR." order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query, $connstatus);
$REQUIREMENTS_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $REQUIREMENTS_COUNT++;

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
         <a href="modify.php?REQUIREMENTS_ID=<?=$REQUIREMENTS_ID?>"> <?=_("�޸�")?></a>&nbsp;
			   <a href="javascript:delete_requirements(<?=$REQUIREMENTS_ID?>);"> <?=_("ɾ��")?></a>&nbsp;
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
       <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ��Ƹ������Ϣ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp; 
       <a href="javascript:export_requirements(<?=$REQUIREMENTS_ID?>);"><img src="<?=MYOA_STATIC_SERVER?>/static/images/export.png" align="absMiddle"><?=_("����")?></a>&nbsp;
     </td>
   </tr>
<?
}else{
   Message("",_("����Ƹ������Ϣ��¼"));	
}
?>
</table>
</body>

</html>
