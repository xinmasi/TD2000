<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("ѧϰ������Ϣ�޸�");
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
   if(document.form1.START_DATE.value!="" && document.form1.END_DATE.value!="" && document.form1.START_DATE.value > document.form1.END_DATE.value)
   { 
      alert("<?=_("�������ڲ���С�ڿ�ʼ���ڣ�")?>");
      return (false);
   }
   if(document.getElementById("academy_degree").value=="")
   {
      alert("<?=_("ѧ������Ϊ�գ�")?>");
      return(false);  
   }
   if(document.getElementById("degree").value=="")
   {
      alert("<?=_("ѧλ����Ϊ�գ�")?>");
      return(false);  
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

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?L_EXPERIENCE_ID=<?=$L_EXPERIENCE_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
</script>

<?
$query="select * from HR_STAFF_LEARN_EXPERIENCE where L_EXPERIENCE_ID='$L_EXPERIENCE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $L_EXPERIENCE_ID=$ROW["L_EXPERIENCE_ID"];
   $USER_ID=$ROW["USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $SCHOOL=$ROW["SCHOOL"];
   $SCHOOL_ADDRESS=$ROW["SCHOOL_ADDRESS"];
   $MAJOR=$ROW["MAJOR"];
   $ACADEMY_DEGREE=$ROW["ACADEMY_DEGREE"];
//   $ACADEMY_DEGREE=get_hrms_code_name($ACADEMY_DEGREE,'STAFF_HIGHEST_SCHOOL');
   $DEGREE=$ROW["DEGREE"];
//   $DEGREE=get_hrms_code_name($DEGREE,'EMPLOYEE_HIGHEST_DEGREE');
   $POSITION=$ROW["POSITION"];
   $AWARDING=$ROW["AWARDING"];
   $CERTIFICATES=$ROW["CERTIFICATES"];
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
      $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
         $STAFF_NAME1=$ROW1["STAFF_NAME"];
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
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�༭ѧϰ������Ϣ")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<form action="update.php"  method="post" name="form1" enctype="multipart/form-data"  onsubmit="return CheckForm();">
 <table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("��λԱ��")?></td>
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
      <td nowrap class="TableData"><?=_("��ѧרҵ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="MAJOR" class=BigInput size="15" value="<?=$MAJOR?>">
      </td>
    </tr>
    <tr>
    	 <td nowrap class="TableData"><?=_("��ʼ���ڣ�")?></td>
      <td class="TableData">
       <input type="text" name="START_DATE" size="15" maxlength="10" class="BigInput" id="start_time" value="<?=$START_DATE?>" onClick="WdatePicker()"/>
      </td>
    	<td nowrap class="TableData"><?=_("�������ڣ�")?></td>
      <td class="TableData">
       <input type="text" name="END_DATE" size="15" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ѧ����")?></td>
      <td class="TableData">
        <!--<INPUT type="text"name="ACADEMY_DEGREE" class=BigInput size="15" value="<?=$ACADEMY_DEGREE?>">-->
    	<select id="academy_degree" name="ACADEMY_DEGREE" class="BigSelect" title="<?=_("��ְ״̬���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
        	<?=hrms_code_list("STAFF_HIGHEST_SCHOOL",$ACADEMY_DEGREE);?>
      </select>
      </td>
      <td nowrap class="TableData"><?=_("����ѧλ��")?></td>
      <td class="TableData">
       <!--<INPUT type="text"name="DEGREE" class=BigInput size="15" value="<?=$DEGREE?>">-->
    	<select id="degree" name="DEGREE" class="BigSelect" title="<?=_("��ְ״̬���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
        	<?=hrms_code_list("EMPLOYEE_HIGHEST_DEGREE",$DEGREE);?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("���ΰ�ɣ�")?></td>
      <td class="TableData">
        <INPUT type="text"name="POSITION" class=BigInput size="15" value="<?=$POSITION?>">
      </td>
      <td nowrap class="TableData"><?=_("֤���ˣ�")?></td>
      <td class="TableData">
       <INPUT type="text"name="WITNESS" class=BigInput size="15" value="<?=$WITNESS?>">
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ԺУ��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="SCHOOL" cols="78" rows="3" class="BigInput" value=""><?=$SCHOOL?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("ԺУ���ڵأ�")?></td>
      <td class="TableData" colspan=3>
        <textarea name="SCHOOL_ADDRESS" cols="78" rows="3" class="BigInput" value=""><?=$SCHOOL_ADDRESS?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�������")?></td>
      <td class="TableData" colspan=3>
        <textarea name="AWARDING" cols="78" rows="3" class="BigInput" value=""><?=$AWARDING?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����֤�飺")?></td>
      <td class="TableData" colspan=3>
        <textarea name="CERTIFICATES" cols="78" rows="3" class="BigInput" value=""><?=$CERTIFICATES?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ע��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="78" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
      </td>
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
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
      	<input type="hidden" value="<?=$L_EXPERIENCE_ID?>" name="L_EXPERIENCE_ID">
        <input type="submit" value="<?=_("����")?>" class="BigButton">
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>