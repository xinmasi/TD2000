<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("�����ѯ��ѯ");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<style>
  input.BigButtonA {width: 60px !important;}
</style>
<script Language=JavaScript>
function delete_ask_duty(ASK_DUTY_ID)
{
  msg='<?=_("ȷ��Ҫɾ���ò����Ϣ��")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?ASK_DUTY_ID=" + ASK_DUTY_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("Ҫɾ�������Ϣ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ���ò����Ϣ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?ASK_DUTY_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
    location=url;
  }
}

function change_type(type)
{
   window.location="index1.php?start=<?=$start?>";
}
</script>

<body class="bodycolor attendance">
  <table class="table table-bordered" style="margin-left: 50px;">
      <thead>
            <tr class="">
                <th nowrap align="center"><?=_("ȱ����")?></th>
                <th nowrap align="center"><?=_("�����")?></th>
                <th nowrap align="center"><?=_("���ʱ��")?></th>
                <th nowrap align="center"><?=_("ȱ����˵��ʱ��")?></th>
            </tr>
      </thead>
  
<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------�Ϸ���У��---------
if($CHECK_DUTY_TIME1!="")
{
  $TIME_OK=is_date($CHECK_DUTY_TIME1);
  if(!$TIME_OK)
  { Message(_("����"),sprintf(_("���ڵĸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE));
    Button_Back();
    exit;
  }
  $CHECK_DUTY_TIME1=$CHECK_DUTY_TIME1;
}

if($CHECK_DUTY_TIME2!="")
{
  $TIME_OK=is_date($CHECK_DUTY_TIME2);

  if(!$TIME_OK)
  { Message(_("����"),sprintf(_("���ڵĸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE));
    Button_Back();
    exit;
  }
  $CHECK_DUTY_TIME2=$CHECK_DUTY_TIME2;
}

//------------------------ ���������ַ��� ------------------
$CONDITION_STR="";
if($CHECK_DUTY_TIME1!="")
  $CONDITION_STR.=" and CHECK_DUTY_TIME>='$CHECK_DUTY_TIME1'";
if($CHECK_DUTY_TIME2!="")
  $CONDITION_STR.=" and CHECK_DUTY_TIME<='$CHECK_DUTY_TIME2'";
?>

<h5 class="attendance-title"><span class="big3"> <?=_("�����ѯ��ѯ���")?></span></h5><br>

<?
$query = "SELECT * from ATTEND_ASK_DUTY where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',CHECK_USER_ID) ".$CONDITION_STR." order by ASK_DUTY_ID desc";
$cursor=exequery(TD::conn(),$query);
$ASK_DUTY_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $ASK_DUTY_COUNT++;

   $ASK_DUTY_ID=$ROW["ASK_DUTY_ID"];
   $CHECK_USER_ID=$ROW["CHECK_USER_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CHECK_DUTY_MANAGER=$ROW["CHECK_DUTY_MANAGER"];

   $CHECK_DUTY_TIME=$ROW["CHECK_DUTY_TIME"];
   $RECORD_TIME=$ROW["RECORD_TIME"];
   $CHECK_DUTY_REMARK=$ROW["CHECK_DUTY_REMARK"];
   $EXPLANATION=$ROW["EXPLANATION"];

	 $CHECK_USER_NAME=substr(GetUserNameById($CHECK_USER_ID),0,-1);
   $CHECK_MANAGER_NAME=substr(GetUserNameById($CHECK_DUTY_MANAGER),0,-1);

  if($ASK_DUTY_COUNT==1)
  {
?>


<?
   }
?>
     <tr class="">
      <td nowrap align="center"><span <? if($EXPLANATION==""){ ?> style="color=red;" <? } ?> > <?=$CHECK_USER_NAME?></span></td>
      <td nowrap align="center"><?=$CHECK_MANAGER_NAME?></td>
      <td nowrap align="center"><?=$CHECK_DUTY_TIME=="0000-00-00 00:00:00"?"":$CHECK_DUTY_TIME;?></td>
      <td nowrap align="center"><?=$RECORD_TIME=="0000-00-00 00:00:00"?_("δ˵��"):$RECORD_TIME;?></td>
    </td>
	</tr>
<?
}
?>
</table>
<?
if($ASK_DUTY_COUNT==0)
{
  
   Message("",_("�޷��������Ĳ����ѯ��Ϣ��"));
   Button_Back();
   exit;
}
else
{
	Button_Back();
}
?>
</body>

</html>
