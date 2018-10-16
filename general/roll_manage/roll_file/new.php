<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("新建文件");
include_once("inc/header.inc.php");

$ROLL_ID_COOKIE=$_COOKIE['ROLL_ID_COOKIE'];
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
   if(document.form1.FILE_CODE.value=="")
   { alert("<?=_("文件号不能为空！")?>");
     return (false);
   }

   if(document.form1.FILE_TITLE.value=="")
   { alert("<?=_("文件标题不能为空！")?>");
     return (false);
   }
   if(document.form1.SECRET.value == ""){
   		alert("<?=_("案卷密级不能为空！")?>");
   		return (false);
   }   
   return (true);
}

function sendForm(publish)
{
 document.form1.OP.value=publish;
 if(CheckForm())
   document.form1.submit();
}

</script>


<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建文件")?></span>
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="add.php"  method="post" name="form1">
<table class="TableBlock" width="90%"  align="center">
<input type="hidden" value="0" name="FILE_ID">
  <TR>
      <TD class="TableData"><?=_("文件号：")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_CODE" id="FILE_CODE" size=20 maxlength="100" dataType="Require" require="true" msg="<?=_("文件号不能为空！")?>" class="BigInput"><span style="color:red;">&nbsp;*</span>
      </TD>
      <TD class="TableData"><?=_("文件主题词：")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_SUBJECT" id="FILE_SUBJECT" size=30 maxlength="100" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("文件标题：")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_TITLE" id="FILE_TITLE" size=30 maxlength="100" dataType="Require" require="true" msg="<?=_("文件标题不能为空！")?>" class="BigInput"><span style="color:red;">&nbsp;*</span>
      </TD>
      <TD class="TableData"><?=_("文件辅标题：")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_TITLE0" id="FILE_TITLE0" size=30 maxlength="100" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("发文单位：")?></TD>
      <TD class="TableData">
       <INPUT name="SEND_UNIT" id="SEND_UNIT" size=30 dataType="Require" require="true" msg="<?=_("发文单位不能为空！")?>" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("发文日期：")?></TD>
      <TD class="TableData">
        <input type="text" name="SEND_DATE" size="10" maxlength="10" class="BigInput" value="<?=$SEND_DATE?>" onClick="WdatePicker()">
       
      </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("密级：")?></TD>
      <TD class="TableData">
	<select name="SECRET" class="BigSelect">
	<option value="" ></option>
    <?=code_list("RMS_SECRET",$SECRET, "D", "", "")?>
	</select><span style="color:red;">&nbsp;*</span>
      </TD>
      <TD class="TableData"><?=_("紧急等级：")?></TD>
      <TD class="TableData">
	<select name="URGENCY" class="BigSelect">
	<option value="" ></option>
    <?=code_list("RMS_URGENCY",$URGENCY)?>
	</select>
     </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("文件分类：")?></TD>
      <TD class="TableData">
	<select name="FILE_TYPE" class="BigSelect">
	  <option value="" ></option>
    <?=code_list("RMS_FILE_TYPE",$FILE_TYPE)?>
	</select>
      </TD>
      <TD class="TableData"><?=_("公文类别：")?></TD>
      <TD class="TableData">
	<select name="FILE_KIND" class="BigSelect">
	<option value="" ></option>
    <?=code_list("RMS_FILE_KIND",$FILE_KIND)?>
	</select>
     </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("文件页数：")?></TD>
      <TD class="TableData">
        <input type="text" name="FILE_PAGE" value="" size="10" maxlength="50" class="BigInput" dataType="Number" require="false" msg="<?=_("文件页数必须是数字！")?>">
      </TD>
      <TD class="TableData"><?=_("打印页数：")?></TD>
      <TD class="TableData">
        <input type="text" name="PRINT_PAGE" value="" size="10" maxlength="50" class="BigInput" dataType="Number" require="false" msg="<?=_("打印页数必须是数字！")?>">
      </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("备注：")?></TD>
      <TD class="TableData"><input type="text" name="REMARK" value="" size="30" maxlength="100" class="BigInput"></TD>
      <TD class="TableData"><?=_("所属案卷：")?></TD>
      <TD class="TableData">
<select name=ROLL_ID class="SmallSelect">
	<?
	$query_str = '';
	$DEPT_ID   = '';
	$DEPT_ID   = get_dept_parent_all($_SESSION['LOGIN_DEPT_ID']);
	if($_SESSION["LOGIN_USER_PRIV"]!=1)
	{
		$query_str.=' AND (DEPT_ID = 0 or DEPT_ID in ('.$DEPT_ID.$_SESSION['LOGIN_DEPT_ID'].')';
	}
	if($_SESSION["LOGIN_USER_PRIV"]!=1 && $_SESSION['LOGIN_DEPT_ID_OTHER']!="")
	{
		$query_str.= 'or FIND_IN_SET (DEPT_ID,"'.($_SESSION['LOGIN_DEPT_ID_OTHER']).'") ';
	}
	if($query_str!="")
	{
		$query_str.= ")";
	}
    $query = 'SELECT * from RMS_ROLL where STATUS=0 '.$query_str.' order by ROLL_CODE asc';
	$cursor= exequery(TD::conn(),$query);
	 while($ROW=mysql_fetch_array($cursor))
	 {	
		$ROLL_ID   = $ROW["ROLL_ID"];
		$ROLL_CODE = $ROW["ROLL_CODE"];
		$ROLL_NAME = $ROW["ROLL_NAME"];
	?>
		<option value=<?=$ROLL_ID?> <? if($ROLL_ID==$ROLL_ID_COOKIE) echo "selected"?> ><?=$ROLL_CODE?> - <?=$ROLL_NAME?></option>
	<?
	 }	
	?>
	</select>
      </TD>
   </TR>
    <tr height="25">
      <td nowrap class="TableData"><?=_("查看附件是否需要审批：")?></td>
      <td class="TableData">
         <input type="radio" name = "ISAUDIT" value="1" checked="true"><?=_("是")?>
         <input type="radio" name = "ISAUDIT" value="0"><?=_("否")?>
      </td>
      <td nowrap class="TableData"><?=_("提醒管理员：")?></td>
      <td class="TableData">
         <?=sms_remind(37);?>
      </td>
    </tr>     
    <tr>    
      <td nowrap class="TableData"><?=_("附件权限：")?></td>
      <td class="TableData" colspan="3">
      	<input type="checkbox" name="DOWNLOAD" id="DOWNLOAD" checked><label for="DOWNLOAD"><?=_("允许下载")?>Office<?=_("附件")?></label>&nbsp;&nbsp;
        <input type="checkbox" name="PRINT" id="PRINT" checked><label for="PRINT"><?=_("允许打印")?>Office<?=_("附件")?></label>&nbsp;&nbsp;&nbsp;<font color="gray"><?=_("都不选中则只能阅读附件内容")?></font>
      </td>
    </tr>
<?
if($ATTACHMENT_ID!="" && $ATTACHMENT_NAME!="")
{
?>
    <tr>
      <td nowrap class="TableData"><?=_("附件：")?></td>
      <td class="TableData" colspan="3"><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1)?></td>
    </tr>
<?
}
?>   
    <tr height="25">
      <td nowrap class="TableData"><?=_("附件选择：")?></td>
      <td class="TableData" colspan="3">
         <script>ShowAddFile();</script>
      </td>
    </tr>   
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
        <input type="hidden" name="OP" value="">
        <input type="button" value="<?=_("新建")?>" class="BigButton" onClick="sendForm('1');">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重置")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>