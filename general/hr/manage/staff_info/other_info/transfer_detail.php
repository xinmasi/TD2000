<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("���µ�����ϸ��Ϣ");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<?
$query = "SELECT * from HR_STAFF_TRANSFER where TRANSFER_ID='$TRANSFER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $TRANSFER_PERSON=$ROW["TRANSFER_PERSON"];
   $TRANSFER_DATE=$ROW["TRANSFER_DATE"];
   $TRANSFER_EFFECTIVE_DATE=$ROW["TRANSFER_EFFECTIVE_DATE"];
   $TRANSFER_TYPE=$ROW["TRANSFER_TYPE"];
   $TRAN_COMPANY_BEFORE=$ROW["TRAN_COMPANY_BEFORE"];
   $TRAN_DEPT_BEFORE=$ROW["TRAN_DEPT_BEFORE"];
   $TRAN_POSITION_BEFORE=$ROW["TRAN_POSITION_BEFORE"];
   $TRAN_COMPANY_AFTER=$ROW["TRAN_COMPANY_AFTER"];
   $TRAN_DEPT_AFTER=$ROW["TRAN_DEPT_AFTER"];
   $TRAN_POSITION_AFTER=$ROW["TRAN_POSITION_AFTER"];
   $TRAN_REASON=$ROW["TRAN_REASON"];
   $MATERIALS_CONDITION=$ROW["MATERIALS_CONDITION"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $REMARK=$ROW["REMARK"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
   $TRANSFER_TYPE=get_hrms_code_name($TRANSFER_TYPE,"HR_STAFF_TRANSFER");
   
   $TRANSFER_PERSON_NAME=substr(GetUserNameById($TRANSFER_PERSON),0,-1);
	 
	 $TRAN_DEPT_BEFORE_NAME=substr(GetDeptNameById($TRAN_DEPT_BEFORE),0,-1);
	 $TRAN_DEPT_AFTER_NAME=substr(GetDeptNameById($TRAN_DEPT_AFTER),0,-1);
?>
<table class="TableBlock" width="82%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRANSFER_PERSON_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ͣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRANSFER_TYPE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ڣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRANSFER_DATE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������Ч���ڣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRANSFER_EFFECTIVE_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����ǰ��λ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_COMPANY_BEFORE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������λ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_COMPANY_AFTER?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����ǰ���ţ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_DEPT_BEFORE_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ţ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_DEPT_AFTER_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����ǰְ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_POSITION_BEFORE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������ְ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_POSITION_AFTER?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��������������")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$MATERIALS_CONDITION?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����ԭ��")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$TRAN_REASON?></td>
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