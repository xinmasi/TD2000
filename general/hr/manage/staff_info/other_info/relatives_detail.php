<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("����ϵ��ϸ��Ϣ");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<?
$query = "SELECT * from HR_STAFF_RELATIVES where RELATIVES_ID ='$RELATIVES_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$MEMBER=$ROW["MEMBER"];	
  $RELATIONSHIP=$ROW["RELATIONSHIP"];   
  $BIRTHDAY=$ROW["BIRTHDAY"];
  $POLITICS=$ROW["POLITICS"];
  $WORK_UNIT=$ROW["WORK_UNIT"];
  $UNIT_ADDRESS=$ROW["UNIT_ADDRESS"];
  $POST_OF_JOB=$ROW["POST_OF_JOB"];
  $OFFICE_TEL=$ROW["OFFICE_TEL"];	
  $HOME_ADDRESS=$ROW["HOME_ADDRESS"];   
  $HOME_TEL=$ROW["HOME_TEL"];
  $JOB_OCCUPATION=$ROW["JOB_OCCUPATION"];
  $STAFF_NAME=$ROW["STAFF_NAME"];
  $PERSONAL_TEL=$ROW["PERSONAL_TEL"];
  $REMARK=$ROW["REMARK"];
	$ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
  $ADD_TIME =$ROW["ADD_TIME"];
  	
  $RELATIONSHIP=get_hrms_code_name($RELATIONSHIP,"HR_STAFF_RELATIVES");
  $POLITICS=get_hrms_code_name($POLITICS,"STAFF_POLITICAL_STATUS");
   
  $STAFF_NAME1 = substr(GetUserNameById($STAFF_NAME),0,-1);
?>
<table class="TableBlock" width="82%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��Ա������")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$MEMBER?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�뱾�˹�ϵ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$RELATIONSHIP?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ڣ�")?></td>
    <td align="left" class="TableData" width="180"><?=$BIRTHDAY?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������ò��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$POLITICS?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("ְҵ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$JOB_OCCUPATION?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������λ��")?></td>
    <td align="left" class="TableData" width="180"><?=$WORK_UNIT?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ϵ�绰����λ����")?></td>
    <td align="left" class="TableData" width="180"><?=$OFFICE_TEL?></td>
  </tr>  
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ϵ�绰�����ˣ���")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$PERSONAL_TEL?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ϵ�绰����ͥ����")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$HOME_TEL?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��λ��ַ��")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$UNIT_ADDRESS?></td>
  </tr>
    <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ͥ��ַ��")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$HOME_ADDRESS?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ע��")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$REMARK?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�����ĵ���")?></td>
    <td nowrap align="left" class="TableData" colspan="3">
<?
    if($ATTACHMENT_ID=="")
       echo _("�޸���");
    else
       echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,0,1,0);

?>
    </td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�Ǽ�ʱ�䣺")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$ADD_TIME?></td>
  </tr>
</table>
<?
}
else
  Message("",_("δ�ҵ���Ӧ��¼��"));
?>
</body>

</html>
