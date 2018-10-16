<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("劳动技能信息修改");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
<script type="text/javascript" src="/inc/js_lang.php"></script>
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
      alert("<?=_("员工姓名不能为空！")?>");
      return (false);
   }
   if(document.form1.ABILITY_NAME.value=="")
   { 
      alert("<?=_("技能名称不能为空！")?>");
      return (false);
   }
   if(document.form1.EXPIRES.value=="")
   { 
      alert("<?=_("有效期不能为空！")?>");
      return (false);
   }
   if(document.form1.ISSUE_DATE.value=="")
   { 
      alert("<?=_("请选择发证日期！")?>");
      return (false);
   }
   if(document.form1.ISSUE_DATE.value!="" && document.form1.ISSUE_DATE.value!="" && document.form1.ISSUE_DATE.value > document.form1.ISSUE_DATE.value)
   { 
      alert("<?=_("证书到期日期不能小于发证日期！")?>");
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

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?SKILLS_ID=<?=$SKILLS_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
</script>

<?
$query="select * from HR_STAFF_LABOR_SKILLS where SKILLS_ID='$SKILLS_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $SKILLS_ID=$ROW["SKILLS_ID"];
   $USER_ID=$ROW["USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
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
   $SELECT_FLAG=0;
   if($STAFF_NAME1=="")
   {
      $SELECT_FLAG=1;
      $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
         $STAFF_NAME1=$ROW1["STAFF_NAME"];
   }
  
  if($ISSUE_DATE=="0000-00-00")
     $ISSUE_DATE="";  
  if($EXPIRE_DATE=="0000-00-00")
     $EXPIRE_DATE="";
     
}
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("编辑劳动技能信息")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<form action="update.php"  method="post" name="form1" enctype="multipart/form-data"  onsubmit="return CheckForm();">
 <table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("单位员工：")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="<?=$STAFF_NAME1?>">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
<?
if($SELECT_FLAG==0) 
{ 
?>
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("选择")?></a>
<?
} 
?>
      </td>
      <td nowrap class="TableData"><?=_("技能名称：")?></td>
      <td class="TableData">
        <INPUT type="text"name="ABILITY_NAME" class=BigInput size="15" value="<?=$ABILITY_NAME?>">
      </td>
    </tr>
    <tr>
    	 <td nowrap class="TableData"><?=_("特种作业：")?></td>
      <td class="TableData">
       <INPUT type="radio" name="SPECIAL_WORK" value="1" <?if($SPECIAL_WORK=="1") echo "checked";?>><?=_("是")?>&nbsp;&nbsp;  
			 <INPUT type="radio" name="SPECIAL_WORK" value="0" <?if($SPECIAL_WORK=="0") echo "checked";?>><?=_("否")?>
      </td>
    	<td nowrap class="TableData"><?=_("级别：")?></td>
      <td class="TableData">
       <INPUT type="text"name="SKILLS_LEVEL" class=BigInput size="15" value="<?=$SKILLS_LEVEL?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("技能证：")?></td>
      <td class="TableData">
        <INPUT type="radio" name="SKILLS_CERTIFICATE" value="1" <?if($SKILLS_CERTIFICATE=="1") echo "checked";?>><?=_("是")?>&nbsp;&nbsp;  
			  <INPUT type="radio" name="SKILLS_CERTIFICATE" value="0" <?if($SKILLS_CERTIFICATE=="0") echo "checked";?>><?=_("否")?> 
      </td>
      <td nowrap class="TableData"><?=_("发证日期：")?></td>
      <td class="TableData">
       <input type="text" name="ISSUE_DATE" size="15" maxlength="10" class="BigInput" value="<?=$ISSUE_DATE?>" onClick="WdatePicker()"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("有效期：")?></td>
      <td class="TableData">
        <INPUT type="text"name="EXPIRES" class=BigInput size="15" value="<?=$EXPIRES?>">
      </td>
      <td nowrap class="TableData"><?=_("到期日期：")?></td>
      <td class="TableData">
       <input type="text" name="EXPIRE_DATE" size="15" maxlength="10" class="BigInput" value="<?=$EXPIRE_DATE?>" onClick="WdatePicker()"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("发证机关/单位：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="ISSUING_AUTHORITY" cols="77" rows="3" class="BigInput" value=""><?=$ISSUING_AUTHORITY?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("备注：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="77" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
      </td>
    </tr> 
    <tr class="TableData" id="attachment2">
      <td nowrap><?=_("附件文档：")?></td>
      <td nowrap colspan=3>
<?
if($ATTACHMENT_ID=="")
   echo _("无附件");
else
   echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);
?>      
      </td>
  </tr>  
  <tr height="25" id="attachment1">
      <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
      <td class="TableData" colspan=3>
        <script>ShowAddFile();</script>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      </td>
  </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
      	<input type="hidden" value="<?=$SKILLS_ID?>" name="SKILLS_ID">
        <input type="submit" value="<?=_("保存")?>" class="BigButton">
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>