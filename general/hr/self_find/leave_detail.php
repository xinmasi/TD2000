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
$query = "SELECT * from HR_STAFF_LEAVE where LEAVE_ID='$LEAVE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $QUIT_TIME_PLAN=$ROW["QUIT_TIME_PLAN"];
   $QUIT_TYPE=$ROW["QUIT_TYPE"];
   $QUIT_REASON=$ROW["QUIT_REASON"];
   $LAST_SALARY_TIME=$ROW["LAST_SALARY_TIME"];
   $TRACE=$ROW["TRACE"];
   $REMARK=$ROW["REMARK"];
   $QUIT_TIME_FACT=$ROW["QUIT_TIME_FACT"];
   $LEAVE_PERSON=$ROW["LEAVE_PERSON"];
   $MATERIALS_CONDITION=$ROW["MATERIALS_CONDITION"];
   $POSITION=$ROW["POSITION"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $APPLICATION_DATE =$ROW["APPLICATION_DATE"];
   $LEAVE_DEPT =$ROW["LEAVE_DEPT"];
   $LAST_UPDATE_TIME =$ROW["LAST_UPDATE_TIME"];
      
   if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
     $LAST_UPDATE_TIME="";   
   if($APPLICATION_DATE=="0000-00-00")
     $APPLICATION_DATE="";   
   if($QUIT_TIME_PLAN=="0000-00-00")
     $QUIT_TIME_PLAN="";
   if($QUIT_TIME_FACT=="0000-00-00")
     $QUIT_TIME_FACT="";  
   if($LAST_SALARY_TIME=="0000-00-00")
     $LAST_SALARY_TIME="";
     
   $QUIT_TYPE=get_hrms_code_name($QUIT_TYPE,"HR_STAFF_LEAVE");
      
   $LEAVE_PERSON_NAME=substr(GetUserNameById($LEAVE_PERSON),0,-1);
   $LEAVE_DEPT_NAME=substr(GetDeptNameById($LEAVE_DEPT),0,-1);
?>
<table class="TableBlock" width="82%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$LEAVE_PERSON_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ְ���ͣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$QUIT_TYPE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����ְ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$POSITION?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ְ���ţ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$LEAVE_DEPT_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("ȥ��")?></td>
    <td align="left" class="TableData" width="180"><?=$TRACE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ڣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$APPLICATION_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����ְ���ڣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$QUIT_TIME_PLAN?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("ʵ����ְ���ڣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$QUIT_TIME_FACT?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("���ʽ�ֹ���ڣ�")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$LAST_SALARY_TIME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ְ��������")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$MATERIALS_CONDITION?></td>
  </tr>      
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ְԭ��")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$QUIT_REASON?></td>
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
  <tr>    
    <td nowrap align="left" width="120" class="TableContent"><?=_("����޸�ʱ�䣺")?></td>
    <td nowrap align="left" class="TableData" width="180" colspan="3"><?=$LAST_UPDATE_TIME?></td>
  </tr>
</table>
<?
}
else
  Message("",_("δ�ҵ���Ӧ��¼��"));
?>
<center><input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>"></center>

</body>
</html>
