<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;
$PAGE_SIZE = 10;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("��Ƹ¼����Ϣ");
include_once("inc/header.inc.php");

$manager_str = "";
if($_SESSION['LOGIN_USER_ID'] != 'admin')
{
    $sql = "SELECT ID,DEPT_ID FROM hr_manager WHERE find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_MANAGER) or find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_SPECIALIST)";
    $cursor1 = exequery(TD::conn(),$sql);
    while($arr=mysql_fetch_array($cursor1))
    {
        $dept_id_el = $row['DEPT_ID'];
        
        $manager_str .= (find_id($manager_str, $dept_id_el) ? "" : $dept_id_el.",");
    }
}
?>

<script>
function delete_info(RECRUITMENT_ID)
{
  if(confirm("<?=_("ȷ��Ҫɾ������Ƹ¼����Ϣ��ɾ���󽫲��ɻָ�")?>"))
     location = "delete.php?RECRUITMENT_ID="+RECRUITMENT_ID;
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox").item(0).checked=false;
}

function check_all()
{
 for (i=0;i<document.getElementsByName("hrms_select").length;i++)
 {
   if(document.getElementsByName("allbox").item(0).checked)
      document.getElementsByName("hrms_select").item(i).checked=true;
   else
      document.getElementsByName("hrms_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox").item(0).checked)
      document.getElementsByName("hrms_select").checked=true;
   else
      document.getElementsByName("hrms_select").checked=false;
 }
}
function delete_mail()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("hrms_select").length;i++)
  {

      el=document.getElementsByName("hrms_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("hrms_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("Ҫɾ����Ƹ¼����Ϣ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ����ѡ��Ƹ¼����Ϣ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?RECRUITMENT_ID="+ delete_str +"&start=<?=$start?>";
    location=url;
  }
}

function check_front(EXPERT_ID)
{
  URL="check_front.php?EXPERT_ID="+EXPERT_ID;
  myleft=(screen.availWidth-500)/2;
  window.open(URL,"check_front","height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
  
}
</script>

<?
$WHERE_STR = hr_priv("");
$query = "SELECT * from HR_RECRUIT_RECRUITMENT WHERE".$WHERE_STR;
$cursor=exequery(TD::conn(),$query, $connstatus);
$STAFF_COUNT = mysql_num_rows($cursor);

$query = "SELECT * from HR_RECRUIT_RECRUITMENT WHERE".$WHERE_STR."ORDER BY RECRUITMENT_ID desc limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query, $connstatus);
$COUNT = mysql_num_rows($cursor);
?>
<body class="bodycolor">
<?
if($COUNT <= 0)
{
   Message("", _("�޷�����������Ƹ¼����Ϣ"));
   exit;
}
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("��Ƹ¼����Ϣ")?></span><br>
    	</td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE)?></td>
	</tr>
</table>
<table class="TableList" width="100%">
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("�ƻ����")?></td>
      <td nowrap align="center"><?=_("��Ƹ��λ")?></td>
      <td nowrap align="center"><?=_("ӦƸ������")?></td>
      <td nowrap align="center"><?=_("¼�ø�����")?></td>
      <td nowrap align="center"><?=_("¼������")?></td>
      <td width="250"><?=_("����")?></td>
   </thead>
<?
while($ROW=mysql_fetch_array($cursor))
{
   $REQUIREMENTS_COUNT++;

   $RECRUITMENT_ID=$ROW["RECRUITMENT_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $PLAN_NO=$ROW["PLAN_NO"];
   $APPLYER_NAME=$ROW["APPLYER_NAME"];
   $JOB_STATUS=$ROW["JOB_STATUS"];
   $ASSESSING_OFFICER=$ROW["ASSESSING_OFFICER"];
   $ASS_PASS_TIME=$ROW["ASS_PASS_TIME"];
   $DEPARTMENT=$ROW["DEPARTMENT"];
   $TYPE=$ROW["TYPE"];
   $ADMINISTRATION_LEVEL=$ROW["ADMINISTRATION_LEVEL"];
   $JOB_POSITION=$ROW["JOB_POSITION"]; 
   $PRESENT_POSITION=$ROW["PRESENT_POSITION"];
   $ON_BOARDING_TIME=$ROW["ON_BOARDING_TIME"];
   $STARTING_SALARY_TIME=$ROW["STARTING_SALARY_TIME"];
   $REMARK=$ROW["REMARK"];
   $OA_NAME=$ROW["OA_NAME"];
   $EXPERT_ID=$ROW["EXPERT_ID"];
   
   $ASSESSING_OFFICER_NAME=substr(GetUserNameById($ASSESSING_OFFICER),0,-1);
   
   $query1 = "SELECT * from HR_STAFF_INFO WHERE USER_ID='$OA_NAME'";
   $cursor1=exequery(TD::conn(),$query1);
   $USER_COUNT = mysql_num_rows($cursor1);
?>
<tr class="TableData">
      <td align="center"><input type="checkbox" name="hrms_select" value="<?=$RECRUITMENT_ID?>" onClick="check_one(self);"></td>
      <td align="center"><?=$PLAN_NO?></td>
      <td align="center"><?=$JOB_STATUS?></td>
      <td align="center"><?=$APPLYER_NAME?></td>
      <td align="center"><?=$ASSESSING_OFFICER_NAME?></td>
      <td align="center"><?=$ASS_PASS_TIME?></td>
      <td align="center">
      	<INPUT type="hidden" name="EXPERT_ID" value="<?=$EXPERT_ID?>">
      	<a href="javascript:;" onClick="window.open('recruitment_detail.php?RECRUITMENT_ID=<?=$RECRUITMENT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
        <?if($USER_COUNT==0 && find_id($manager_str, $DEPARTMENT)) echo sprintf("<a href='javascript:;' onClick=window.open('new_staff.php?EXPERT_ID=$EXPERT_ID','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=270,resizable=yes');>"._("�����µ���")."</a>")?>
        <a href="modify.php?RECRUITMENT_ID=<?=$RECRUITMENT_ID?>"><?=_("�༭")?></a>&nbsp;&nbsp;
        <a href="javascript:delete_info('<?=$RECRUITMENT_ID?>');"><?=_("ɾ��")?></a>
      </td>
</tr>
<?
}
?>
<tr class="TableControl">
      <td colspan="19">
         <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label> &nbsp;
         <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ��Ƹ¼����Ϣ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ����ѡ��Ƹ¼����Ϣ")?></a>&nbsp;
      </td>
   </tr>
</table>
<br>
</body>
</html>