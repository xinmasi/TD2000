<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("ѧϰ������ϸ��Ϣ");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<style type="text/css">
table{table-layout: fixed;}
td{word-break: break-all; word-wrap:break-word;}
</style>
<?
$query = "SELECT * from HR_STAFF_LEARN_EXPERIENCE where L_EXPERIENCE_ID='$L_EXPERIENCE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $SCHOOL=$ROW["SCHOOL"];
   $SCHOOL_ADDRESS=$ROW["SCHOOL_ADDRESS"];
   $MAJOR=$ROW["MAJOR"];
   $ACADEMY_DEGREE=$ROW["ACADEMY_DEGREE"];
   $DEGREE=$ROW["DEGREE"];
   $POSITION=$ROW["POSITION"];
   $AWARDING=$ROW["AWARDING"];
   $CERTIFICATES=$ROW["CERTIFICATES"];
   $WITNESS=$ROW["WITNESS"];
   $REMARK=$ROW["REMARK"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ADD_TIME=$ROW["ADD_TIME"];

   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
   $ACADEMY_DEGREE=get_hrms_code_name($ACADEMY_DEGREE,"STAFF_HIGHEST_SCHOOL");
   $DEGREE=get_hrms_code_name($DEGREE,"EMPLOYEE_HIGHEST_DEGREE");
?>
<table class="TableBlock" width="82%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ѧרҵ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$MAJOR?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����ѧ����")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$ACADEMY_DEGREE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����ѧλ��")?></td>
    <td align="left" class="TableData" width="180"><?=$DEGREE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("���ΰ�ɣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$POSITION?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("֤���ˣ�")?></td>
    <td align="left" class="TableData" width="180"><?=$WITNESS?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����ԺУ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$SCHOOL?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("ԺУ���ڵأ�")?></td>
    <td align="left" class="TableData" width="180"><?=$SCHOOL_ADDRESS?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ʼ���ڣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$START_DATE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ڣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$END_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$AWARDING?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����֤�飺")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$CERTIFICATES?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ע��")?></td>
    <td align="left" class="TableData" colspan="3"><?=$REMARK?></td>
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
