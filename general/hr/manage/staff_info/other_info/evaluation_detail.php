<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("ְ��������ϸ��Ϣ");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<?
$query = "SELECT * from HR_STAFF_TITLE_EVALUATION where EVALUATION_ID='$EVALUATION_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $POST_NAME=$ROW["POST_NAME"];
   $GET_METHOD=$ROW["GET_METHOD"];
   $REPORT_TIME=$ROW["REPORT_TIME"];
   $RECEIVE_TIME=$ROW["RECEIVE_TIME"];
   $APPROVE_PERSON=$ROW["APPROVE_PERSON"];
   $APPROVE_NEXT=$ROW["APPROVE_NEXT"];
   $APPROVE_NEXT_TIME=$ROW["APPROVE_NEXT_TIME"];
   $REMARK=$ROW["REMARK"];
   $EMPLOY_POST=$ROW["EMPLOY_POST"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $EMPLOY_COMPANY=$ROW["EMPLOY_COMPANY"];
   $BY_EVALU_STAFFS=$ROW["BY_EVALU_STAFFS"];
   $ADD_TIME=$ROW["ADD_TIME"];
  	
  $GET_METHOD=get_hrms_code_name($GET_METHOD,"HR_STAFF_TITLE_EVALUATION");
   
   
   $BY_EVALU_NAME=substr(GetUserNameById($ROW["BY_EVALU_STAFFS"]),0,-1);
   $APPROVE_PERSON_NAME=substr(GetUserNameById($ROW["APPROVE_PERSON"]),0,-1);
?>
<table class="TableBlock" width="82%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$BY_EVALU_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��׼�ˣ�")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$APPROVE_PERSON_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ȡְ�ƣ�")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$POST_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ȡ��ʽ��")?></td>
    <td align="left" class="TableData Content"><?=$GET_METHOD?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�걨ʱ�䣺")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$REPORT_TIME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ȡʱ�䣺")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$RECEIVE_TIME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�´��걨ʱ�䣺")?></td>
    <td align="left"class="TableData" width="180"><?=$APPROVE_NEXT_TIME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�´��걨ְ�ƣ�")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$APPROVE_NEXT?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("Ƹ��ְ��")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$EMPLOY_POST?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("Ƹ�õ�λ��")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$EMPLOY_COMPANY?></td>
  </tr>      
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("Ƹ�ÿ�ʼʱ�䣺")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$START_DATE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("Ƹ�ý���ʱ�䣺")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$END_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������飺")?></td>
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
