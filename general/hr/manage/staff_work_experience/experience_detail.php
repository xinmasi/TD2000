<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("����������ϸ��Ϣ");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("����������ϸ��Ϣ")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
$query = "SELECT * from HR_STAFF_WORK_EXPERIENCE where W_EXPERIENCE_ID='$W_EXPERIENCE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $WORK_UNIT=$ROW["WORK_UNIT"];
   $MOBILE=$ROW["MOBILE"];
   $WORK_BRANCH=$ROW["WORK_BRANCH"];
   $POST_OF_JOB=$ROW["POST_OF_JOB"];
   $WORK_CONTENT=$ROW["WORK_CONTENT"];
   $KEY_PERFORMANCE=$ROW["KEY_PERFORMANCE"];
   $REASON_FOR_LEAVING=$ROW["REASON_FOR_LEAVING"];
   $WITNESS=$ROW["WITNESS"];
   $REMARK=$ROW["REMARK"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $LAST_UPDATE_TIME =$ROW["LAST_UPDATE_TIME"];
      
   if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
     $LAST_UPDATE_TIME="";
     
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
   if($STAFF_NAME1=="")
   {    
     $query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
     $cursor2= exequery(TD::conn(),$query2);
     if($ROW2=mysql_fetch_array($cursor2))
        $STAFF_NAME1=$ROW2["STAFF_NAME"];    	
    $STAFF_NAME1=$STAFF_NAME1."(<font color='red'>"._("�û���ɾ��")."</font>)";
   }
?>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������λ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$WORK_UNIT?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ʼ���ڣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$START_DATE=="0000-00-00"?"":$START_DATE;?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ڣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$END_DATE=="0000-00-00"?"":$END_DATE;?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ҵ���")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$MOBILE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("���ڲ��ţ�")?></td>
    <td align="left" class="TableData" width="180"><?=$WORK_BRANCH?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("����ְ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$POST_OF_JOB?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("֤���ˣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$WITNESS?></td>      
  </tr>
    <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ݣ�")?></td>
    <td align="left" class="TableData" colspan="3"><?=$WORK_CONTENT?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��Ҫҵ����")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$KEY_PERFORMANCE?></td>
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
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ְԭ��")?></td>
    <td align="left" class="TableData" colspan="3"><?=$REASON_FOR_LEAVING?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ע��")?></td>
    <td align="left" class="TableData" colspan="3"><?=$REMARK?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�Ǽ�ʱ�䣺")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$ADD_TIME?></td>
  </tr>
  <tr>    
    <td nowrap align="left" width="120" class="TableContent"><?=_("����޸�ʱ�䣺")?></td>
    <td nowrap align="left" class="TableData" width="180" colspan="3"><?=$LAST_UPDATE_TIME?></td>
  </tr>
  <tr align="center" class="TableControl">
    <td colspan="4">
      <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>">
    </td>
  </tr>
</table>
<?
}
else
  Message("",_("δ�ҵ���Ӧ��¼��"));
?>
</body>

</html>
