<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;
$PAGE_SIZE = 10;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("��Ƹ�ƻ���Ϣ");
include_once("inc/header.inc.php");
?>





<script>
function delete_plan(PLAN_ID)
{
  if(confirm("<?=_("ȷ��Ҫɾ������Ƹ�ƻ���ɾ���󽫲��ɻָ�")?>"))
     location = "delete.php?PLAN_ID="+PLAN_ID;
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox").item(0).checked=false;
}

function check_all()
{
   for(i=0;i<document.getElementsByName("hrms_select").length;i++)
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
      {
      	 val=el.value;
         delete_str+=val + ",";
      }
   }

   if(i==0)
   {
      el=document.getElementsByName("hrms_select");
      if(el.checked)
      {  
      	 val=el.value;
         delete_str+=val + ",";
      }
   }
   if(delete_str=="")
   {
      alert("<?=_("Ҫɾ����Ƹ�ƻ���������ѡ������һ����")?>");
      return;
   }

   msg='<?=_("ȷ��Ҫɾ����ѡ��Ƹ�ƻ���")?>';
   if(window.confirm(msg))
   {
      url="delete.php?PLAN_ID="+ delete_str +"&start=<?=$start?>";
      location=url;
   }
}
</script>

<?
$WHERE_STR = hr_priv("");
$query = "SELECT * from HR_RECRUIT_PLAN WHERE".$WHERE_STR;
$cursor=exequery(TD::conn(),$query, $connstatus);
$STAFF_COUNT = mysql_num_rows($cursor);

$query = "SELECT * from HR_RECRUIT_PLAN WHERE".$WHERE_STR."ORDER BY ADD_TIME desc limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query, $connstatus);
$COUNT = mysql_num_rows($cursor);
?>

<body class="bodycolor">
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("��Ƹ�ƻ���Ϣ")?></span><br>
    	</td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE)?></td>
	</tr>
</table>
<?
if($COUNT <= 0)
{
   Message("", _("�޷�����������Ƹ�ƻ�"));
   exit;
}
?>
<table class="TableList" width="100%">
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("�ƻ����")?></td>
      <td nowrap align="center"><?=_("�ƻ�����")?></td>
      <td nowrap align="center"><?=_("��Ƹ����")?></td>
      <td nowrap align="center"><?=_("��ʼ����")?></td>
      <td nowrap align="center"><?=_("�ƻ�״̬")?></td>
      <td width="150"><?=_("����")?></td>
   </thead>
<?
while($ROW=mysql_fetch_array($cursor))
{
   $REQUIREMENTS_COUNT++;

   $PLAN_ID=$ROW["PLAN_ID"];
   $PLAN_NO=$ROW["PLAN_NO"];
   $PLAN_NAME=$ROW["PLAN_NAME"];
   $PLAN_DITCH=$ROW["PLAN_DITCH"];
   $PLAN_BCWS=$ROW["PLAN_BCWS"];
   $PLAN_RECR_NO=$ROW["PLAN_RECR_NO"];
   $REGISTER_TIME=$ROW["REGISTER_TIME"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $RECRUIT_DIRECTION=$ROW["RECRUIT_DIRECTION"]; 
   $RECRUIT_REMARK=$ROW["RECRUIT_REMARK"];
   $APPROVE_PERSON=$ROW["APPROVE_PERSON"];
   $APPROVE_DATE=$ROW["APPROVE_DATE"];
   $APPROVE_COMMENT=$ROW["APPROVE_COMMENT"];
   $APPROVE_RESULT=$ROW["APPROVE_RESULT"];
   $PLAN_STATUS=$ROW["PLAN_STATUS"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ADD_TIME=$ROW["ADD_TIME"];

?>
<tr class="TableData">
      <td align="center"><input type="checkbox" name="hrms_select" value="<?=$PLAN_ID?>" onClick="check_one(self);"></td>
      <td align="center"><?=$PLAN_NO?></td>
      <td align="center"><?=$PLAN_NAME?></td>
      <td align="center"><?=$PLAN_RECR_NO?></td>
      <td align="center"><?=$START_DATE?></td>
      <td align="center"><?if($PLAN_STATUS==0) echo _("������");?><?if($PLAN_STATUS==1) echo "<font color=green>"._("����׼")."</font>";?><?if($PLAN_STATUS==2) echo "<font color=red>"._("δ��׼")."</font>";?></td>
      <td align="center">
      	<a href="javascript:;" onClick="window.open('plan_detail.php?PLAN_ID=<?=$PLAN_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
        <?if($PLAN_STATUS==0) {?><a href="modify.php?PLAN_ID=<?=$PLAN_ID?>"><?=_("�༭")?></a>&nbsp;<?}?>
        <a href="javascript:delete_plan('<?=$PLAN_ID?>');"><?=_("ɾ��")?></a>&nbsp;
      </td>
</tr>
<?
}
?>
<tr class="TableControl">
      <td colspan="19">
         <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label> &nbsp;
         <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ��Ƹ�ƻ�")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ����ѡ��Ƹ�ƻ�")?></a>&nbsp;
      </td>
   </tr>
</table>
<br>
</body>
</html>