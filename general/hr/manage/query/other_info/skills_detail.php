<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("�Ͷ�������ϸ��Ϣ");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<body class="bodycolor">
<?
$query = "SELECT * from HR_STAFF_LABOR_SKILLS where SKILLS_ID='$SKILLS_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $ABILITY_NAME=$ROW["ABILITY_NAME"];
   $SPECIAL_WORK=$ROW["SPECIAL_WORK"];
   $SKILLS_LEVEL=$ROW["SKILLS_LEVEL"];
   $SKILLS_CERTIFICATE=$ROW["SKILLS_CERTIFICATE"];
   $ISSUE_DATE=$ROW["ISSUE_DATE"];
   $EXPIRE_DATE=$ROW["EXPIRE_DATE"];
   $EXPIRES=$ROW["EXPIRES"];
   $ISSUING_AUTHORITY=$ROW["ISSUING_AUTHORITY"];
   $REMARK=$ROW["REMARK"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ADD_TIME=$ROW["ADD_TIME"];   
   
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);   
?>
<table class="TableBlock" width="82%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ƣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$ABILITY_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������ҵ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?if($SPECIAL_WORK==1) echo _("��");if($SPECIAL_WORK==0)echo _("��");?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����")?></td>
    <td align="left" class="TableData" width="180"><?=$SKILLS_LEVEL?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����֤��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?if($SKILLS_CERTIFICATE==1) echo _("��");if($SKILLS_CERTIFICATE==0) echo _("��");?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��֤���ڣ�")?></td>
    <td align="left" class="TableData" width="180"><?=$ISSUE_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��Ч�ڣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$EXPIRES?><?=_("���꣩")?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ڣ�")?></td>
    <td align="left" class="TableData" width="180"><?=$EXPIRE_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��֤����/��λ��")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$ISSUING_AUTHORITY?></td>
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
