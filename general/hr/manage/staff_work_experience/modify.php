<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php");

$HTML_PAGE_TITLE = _("���������޸�");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
   if(document.form1.STAFF_NAME.value=="")
   { 
      alert("<?=_("Ա����������Ϊ�գ�")?>");
      return (false);
   }
   if(document.form1.START_DATE.value!="" && document.form1.END_DATE.value!="" && document.form1.START_DATE.value >= document.form1.END_DATE.value)
   { 
      alert("<?=_("������ʼ���ڲ���С�ڹ����������ڣ�")?>");
      return (false);
   }
 return (true);
}
function upload_attach()
{
  if(CheckForm())
   {   
     document.form1.submit();
   }
}
function InsertImage(src)
{
    AddImage2Editor('KEY_PERFORMANCE', src);
}
function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?W_EXPERIENCE_ID=<?=$W_EXPERIENCE_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
</script>

<?
$query="select * from HR_STAFF_WORK_EXPERIENCE where W_EXPERIENCE_ID='$W_EXPERIENCE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $W_EXPERIENCE_ID=$ROW["W_EXPERIENCE_ID"];
   $USER_ID=$ROW["USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
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
   
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
   $SELECT_FLAG=0;
   if($STAFF_NAME1=="")
   {
      $SELECT_FLAG=1;
      $query = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
      $cursor= exequery(TD::conn(),$query);
      if($ROW=mysql_fetch_array($cursor))
         $STAFF_NAME1=$ROW["STAFF_NAME"];
   }  
  if($START_DATE=="0000-00-00")
     $START_DATE="";  
  if($END_DATE=="0000-00-00")
     $END_DATE="";
     
}
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�༭��������")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<form action="update.php"  method="post" name="form1" enctype="multipart/form-data"  onsubmit="return CheckForm();">
 <table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("��λԱ����")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="<?=$STAFF_NAME1?>">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
<?
if($SELECT_FLAG==0)
{
?>          
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("ѡ��")?></a>
<?
}
?>
      </td>
      <td nowrap class="TableData"><?=_("����ְ��")?></td>
      <td class="TableData"><INPUT type="text"name="POST_OF_JOB" class=BigInput size="15" value="<?=$POST_OF_JOB?>"></td>
    </tr>
    <tr>
    	 <td nowrap class="TableData"><?=_("���ڲ��ţ�")?></td>
      <td class="TableData"><INPUT type="text"name="WORK_BRANCH" class=BigInput size="15" value="<?=$WORK_BRANCH?>"></td>
    	<td nowrap class="TableData"><?=_("֤���ˣ�")?></td>
      <td class="TableData"><INPUT type="text"name="WITNESS" class=BigInput size="15" value="<?=$WITNESS?>"></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ʼ���ڣ�")?></td>
      <td class="TableData"><input type="text" name="START_DATE" size="15" maxlength="10" class="BigInput" id="start_time" value="<?=$START_DATE?>" onClick="WdatePicker()"/>   </td>
      <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
      <td class="TableData"><input type="text" name="END_DATE" size="15" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/></td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("��ҵ���")?></td>
      <td class="TableData" colspan=3><textarea name="MOBILE" cols="78" rows="3" class="BigInput" value=""><?=$MOBILE?></textarea></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("������λ��")?></td>
      <td class="TableData" colspan=3><textarea name="WORK_UNIT" cols="78" rows="3" class="BigInput" value=""><?=$WORK_UNIT?></textarea></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�������ݣ�")?></td>
      <td class="TableData" colspan=3><textarea name="WORK_CONTENT" cols="78" rows="3" class="BigInput" value=""><?=$WORK_CONTENT?></textarea></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ְԭ��")?></td>
      <td class="TableData" colspan=3><textarea name="REASON_FOR_LEAVING" cols="78" rows="3" class="BigInput" value=""><?=$REASON_FOR_LEAVING?></textarea></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ע��")?></td>
      <td class="TableData" colspan=3><textarea name="REMARK" cols="78" rows="3" class="BigInput" value=""><?=$REMARK?></textarea></td>
    </tr> 
    <tr class="TableData" id="attachment2">
      <td nowrap><?=_("�����ĵ���")?></td>
      <td nowrap colspan=3>
<?
if($ATTACHMENT_ID=="")
   echo _("�޸���");
else
   echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);
?>      
      </td>
  </tr>  
  <tr height="25" id="attachment1">
      <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("�����ϴ���")?></span></td>
      <td class="TableData" colspan=3>
        <script>ShowAddFile();</script>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      </td>
  </tr>
    <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("��Ҫҵ����")?>
<?
$editor = new Editor('KEY_PERFORMANCE') ;
$editor->Height = '300';
$editor->Value = $KEY_PERFORMANCE ;
$editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
//$editor->Config = array('model_type' => '14') ;
$editor->Create() ;
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
      	<input type="hidden" value="<?=$W_EXPERIENCE_ID?>" name="W_EXPERIENCE_ID">
        <input type="submit" value="<?=_("����")?>" class="BigButton">
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>