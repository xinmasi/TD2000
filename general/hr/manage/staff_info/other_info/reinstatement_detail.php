<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("Ա����ְ��ϸ��Ϣ");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<?
$query = "SELECT * from HR_STAFF_REINSTATEMENT where REINSTATEMENT_ID='$REINSTATEMENT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $REAPPOINTMENT_TIME_FACT=$ROW["REAPPOINTMENT_TIME_FACT"];
   $REAPPOINTMENT_TYPE=$ROW["REAPPOINTMENT_TYPE"];
   $REAPPOINTMENT_STATE=$ROW["REAPPOINTMENT_STATE"];
   $REMARK=$ROW["REMARK"];
   $REINSTATEMENT_PERSON=$ROW["REINSTATEMENT_PERSON"];
   $REAPPOINTMENT_TIME_PLAN=$ROW["REAPPOINTMENT_TIME_PLAN"];
   $NOW_POSITION=$ROW["NOW_POSITION"];
   $APPLICATION_DATE=$ROW["APPLICATION_DATE"];
   $MATERIALS_CONDITION=$ROW["MATERIALS_CONDITION"];
   $FIRST_SALARY_TIME=$ROW["FIRST_SALARY_TIME"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $REAPPOINTMENT_DEPT =$ROW["REAPPOINTMENT_DEPT"];
   
   $REAPPOINTMENT_TYPE=get_hrms_code_name($REAPPOINTMENT_TYPE,"HR_STAFF_REINSTATEMENT");
   
   $REINSTATEMENT_PERSON_NAME=substr(GetUserNameById($REINSTATEMENT_PERSON),0,-1);
   $REAPPOINTMENT_DEPT_NAME=substr(GetDeptNameById($REAPPOINTMENT_DEPT),0,-1);
?>
<table class="TableBlock" width="82%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$REINSTATEMENT_PERSON_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ְ���ͣ�")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$REAPPOINTMENT_TYPE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����ְ��")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$NOW_POSITION?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ְ���ţ�")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$REAPPOINTMENT_DEPT_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ڣ�")?></td>
    <td align="left" class="TableData" width="180"><?=$APPLICATION_DATE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�⸴ְ���ڣ�")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$REAPPOINTMENT_TIME_PLAN?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("ʵ�ʸ�ְ���ڣ�")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$REAPPOINTMENT_TIME_FACT?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("���ʻָ����ڣ�")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$FIRST_SALARY_TIME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ְ��������")?></td>
    <td nowrap align="left"class="TableData" colspan="3"><?=$MATERIALS_CONDITION?></td>
  </tr>      
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ְ˵����")?></td>
    <td nowrap align="left"class="TableData" colspan="3"><?=$REAPPOINTMENT_STATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ע��")?></td>
    <td nowrap align="left"class="TableData" colspan="3"><?=$REMARK?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�����ĵ���")?></td>
    <td nowrap align="left"class="TableData" colspan="3">
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
    <td nowrap align="left"class="TableData" colspan="3"><?=$ADD_TIME?></td>
  </tr>
</table>
<?
}
else
  Message("",_("δ�ҵ���Ӧ��¼��"));
?>
</body>

</html>
