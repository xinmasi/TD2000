<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("�༭�ļ�");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
   if(document.form1.FILE_CODE.value=="")
   { alert("<?=_("�ļ��Ų���Ϊ�գ�")?>");
     return (false);
   }

   if(document.form1.FILE_TITLE.value=="")
   { alert("<?=_("�ļ����Ʋ���Ϊ�գ�")?>");
     return (false);
   }
   if(document.form1.SECRET.value == ""){
   		alert("<?=_("�����ܼ�����Ϊ�գ�")?>");
   		return (false);
   }
   document.form1.OP.value="1";
   return (true);
}

function sendForm(publish)
{
 document.form1.OP.value="1";
 if(CheckForm())
   document.form1.submit();
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
     URL="delete_attach.php?FILE_ID=<?=$FILE_ID?>&start=<?=$start?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
     window.location=URL;
  }
}
</script>

<?
$FILE_ID=intval($FILE_ID);
$query="select * from RMS_FILE where FILE_ID='$FILE_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
	$FILE_CODE=$ROW["FILE_CODE"];
	$ROLL_ID=$ROW['ROLL_ID'];
    $FILE_SUBJECT=$ROW["FILE_SUBJECT"];
    $FILE_TITLE=$ROW["FILE_TITLE"];
    $FILE_TITLE0=$ROW["FILE_TITLE0"];
    $SEND_UNIT=$ROW["SEND_UNIT"];
    $SEND_DATE=$ROW["SEND_DATE"];
    $SECRET=$ROW["SECRET"];
    $URGENCY=$ROW["URGENCY"];
    $FILE_TYPE=$ROW["FILE_TYPE"];
    $FILE_KIND=$ROW["FILE_KIND"];
    $FILE_PAGE=$ROW["FILE_PAGE"];
    $PRINT_PAGE=$ROW["PRINT_PAGE"];
    $REMARK=$ROW["REMARK"];
    $DOWNLOAD = $ROW["DOWNLOAD"];
    $PRINT = $ROW["PRINT"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    $ISAUDIT=$ROW["ISAUDIT"];
	if ($SEND_DATE=='0000-00-00') $SEND_DATE='';
}

?>
<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�༭�ļ�")?></span>
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="update.php"  method="post" name="form1">
<table class="TableBlock" width="90%" align="center">
  <TR>
      <TD class="TableData"><?=_("�ļ��ţ�")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_CODE" id="FILE_CODE" size=20 maxlength="100" value="<?=$FILE_CODE?>" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("�ļ�����ʣ�")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_SUBJECT" id="FILE_SUBJECT" size=30 maxlength="100" value="<?=$FILE_SUBJECT?>" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("�ļ����⣺")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_TITLE" id="FILE_TITLE" size=30 maxlength="100" value="<?=$FILE_TITLE?>" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("�ļ������⣺")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_TITLE0" id="FILE_TITLE0" size=30 maxlength="100" value="<?=$FILE_TITLE0?>" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("���ĵ�λ��")?></TD>
      <TD class="TableData">
       <INPUT name="SEND_UNIT" id="SEND_UNIT" size=30 value="<?=$SEND_UNIT?>" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("�������ڣ�")?></TD>
      <TD class="TableData">
        <input type="text" name="SEND_DATE" size="10" maxlength="10" class="BigInput" value="<?=$SEND_DATE?>" onClick="WdatePicker()">
       
      </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("�ܼ���")?></TD>
      <TD class="TableData">
	<select name="SECRET" class="BigSelect">
	<option value="" ></option>
    <?=code_list("RMS_SECRET",$SECRET, "D", "", "")?>
	</select>
      </TD>
      <TD class="TableData"><?=_("�����ȼ���")?></TD>
      <TD class="TableData">
	<select name="URGENCY" class="BigSelect">
	<option value="" ></option>
    <?=code_list("RMS_URGENCY",$URGENCY)?>
	</select>
     </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("�ļ����ࣺ")?></TD>
      <TD class="TableData">
	<select name="FILE_TYPE" class="BigSelect">
	  <option value="" ></option>
    <?=code_list("RMS_FILE_TYPE",$FILE_TYPE)?>
	</select>
      </TD>
      <TD class="TableData"><?=_("�������")?></TD>
      <TD class="TableData">
	<select name="FILE_KIND" class="BigSelect">
	<option value="" ></option>
    <?=code_list("RMS_FILE_KIND",$FILE_KIND)?>
	</select>
     </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("�ļ�ҳ����")?></TD>
      <TD class="TableData">
        <input type="text" name="FILE_PAGE" value="<?=$FILE_PAGE?>" size="10" maxlength="50" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("��ӡҳ����")?></TD>
      <TD class="TableData">
        <input type="text" name="PRINT_PAGE" value="<?=$PRINT_PAGE?>" size="10" maxlength="50" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("��ע��")?></TD>
      <TD class="TableData"><input type="text" name="REMARK" value="<?=$REMARK?>" size="30" maxlength="100" class="BigInput"></TD>
      <TD class="TableData"><?=_("��������")?></TD>
      <TD class="TableData">
<select name=ROLL_ID class="SmallSelect">
	<?
    $query = "SELECT * from RMS_ROLL where STATUS=0 order by ROLL_CODE desc";
	$cursor= exequery(TD::conn(),$query, $connstatus);;
	 while($ROW=mysql_fetch_array($cursor))
	 {	
		$ROLL_ID1=$ROW["ROLL_ID"];
		$ROLL_CODE=$ROW["ROLL_CODE"];
		$ROLL_NAME=$ROW["ROLL_NAME"];
	?>
		<option value=<?=$ROLL_ID1?> <? if($ROLL_ID==$ROLL_ID1) echo 'selected'; ?>><?=$ROLL_CODE?> - <?=$ROLL_NAME?></option>
	<?
	 }	
	?>
	</select>
      </TD>
   </TR>
    <tr height="25">
      <td nowrap class="TableData"><?=_("����Ȩ�ޣ�")?></td>
      <td class="TableData">
      	<input type="checkbox" name="DOWNLOAD" id="DOWNLOAD" <?if($DOWNLOAD==1) echo "checked";?>><label for="DOWNLOAD"><?=_("��������")?>Office<?=_("����")?></label>&nbsp;&nbsp;
        <input type="checkbox" name="PRINT" id="PRINT" <?if($PRINT==1) echo "checked";?>><label for="PRINT"><?=_("�����ӡ")?>Office<?=_("����")?></label>&nbsp;&nbsp;&nbsp;<br/><font color="gray"><?=_("����ѡ����ֻ���Ķ���������")?></font>
         
      </td>
      <td nowrap class="TableData"><?=_("�鿴�����Ƿ���Ҫ������")?></td>
      <td class="TableData">
         <input type="radio" name = "ISAUDIT" value="1" <? if($ISAUDIT==1) echo "checked"?>><?=_("��")?>
         <input type="radio" name = "ISAUDIT" value="0" <? if($ISAUDIT==0) echo "checked"?>><?=_("��")?>
      </td>
    </tr>    
    <tr class="TableData">
      <td nowrap><?=_("�����ĵ���")?></td>
      <td nowrap colspan="3"><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,0,0)?></td>
    </tr>
    <tr height="25">
      <td nowrap class="TableData"><?=_("����ѡ��")?></td>
      <td class="TableData"  colspan="3">
         <script>ShowAddFile();</script>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
        <input type="hidden" name="OP" value="">
        <input type="hidden" value="<?=$ATTACHMENT_ID?>" name="ATTACHMENT_ID_OLD">
        <input type="hidden" value="<?=$ATTACHMENT_NAME?>" name="ATTACHMENT_NAME_OLD">
        <input type="hidden" value="<?=$FILE_ID?>" name="FILE_ID">
        <input type="hidden" value="<?=$CUR_PAGE?>" name="CUR_PAGE">
        <input type="hidden" name="ATTACHMENT_COUNT" value="">        
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="sendForm('0');">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index1.php?CUR_PAGE=<?=$CUR_PAGE?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>