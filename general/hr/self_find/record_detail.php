<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$query="select * from HR_TRAINING_RECORD where RECORD_ID='$RECORD_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{ 
   $STAFF_USER_ID=$ROW["STAFF_USER_ID"];
   $T_PLAN_NO=$ROW["T_PLAN_NO"];
   $T_PLAN_NAME=$ROW["T_PLAN_NAME"];
   $T_INSTITUTION_NAME=$ROW["T_INSTITUTION_NAME"];
   $TRAINNING_COST=$ROW["TRAINNING_COST"];
   $DUTY_SITUATION=$ROW["DUTY_SITUATION"];
   $TRAINNING_SITUATION=$ROW["TRAINNING_SITUATION"];
   $T_EXAM_RESULTS=$ROW["T_EXAM_RESULTS"];
   $T_EXAM_LEVEL=$ROW["T_EXAM_LEVEL"];
   $T_COMMENT=$ROW["T_COMMENT"];
   $REMARK=$ROW["REMARK"];

   $query2= "SELECT USER_NAME from  USER WHERE USER_ID='$STAFF_USER_ID'";
	 $cursor2=exequery(TD::conn(),$query2);
	 if($ROW2=mysql_fetch_array($cursor2))
	 {
	 		$STAFF_USER_NAME=$ROW2["USER_NAME"];
	 }
}

$HTML_PAGE_TITLE = _("��ѵ��ϸ��Ϣ");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>

<body class="bodycolor">

<table class="TableBlock" width="82%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ѵ�ƻ���ţ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$T_PLAN_NO?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ѵ�ƻ����ƣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$T_PLAN_NAME?></td>
  </tr>
  <tr>
  	<td nowrap align="left" width="120" class="TableContent"><?=_("��ѵ������")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$T_INSTITUTION_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ѵ���ã�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAINNING_COST?></td>
  </tr>
  <tr>
  	<td nowrap align="left" width="120" class="TableContent"><?=_("��ѵ���˳ɼ���")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$T_EXAM_RESULTS?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ѵ���˵ȼ���")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$T_EXAM_LEVEL?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ѵ�ˣ�")?></td>
    <td class="TableData" colspan=3><?=$STAFF_USER_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("���������")?></td>
    <td nowrap align="left" class="TableData" colspan=3><?=$DUTY_SITUATION?></td>
  </tr>
  <tr>
  	<td nowrap align="left" width="120" class="TableContent"><?=_("�ܽ���������")?></td>
    <td nowrap align="left" class="TableData" colspan=3><?=$TRAINNING_SITUATION?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("���ۣ�")?></td>
    <td nowrap align="left" class="TableData" colspan=3><?=$T_COMMENT?></td>
  </tr> 
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ע��")?></td>
    <td nowrap align="left" class="TableData" colspan=3><?=$REMARK?></td>
  </tr>
</table>
<center><input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>"></center>

</body>
</html>