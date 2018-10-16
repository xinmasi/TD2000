<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("编辑案卷");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.ROLL_CODE.value=="")
   { alert("<?=_("案卷号不能为空！")?>");
     return (false);
   }

   if(document.form1.ROLL_NAME.value=="")
   { alert("<?=_("案卷名称不能为空！")?>");
     return (false);
   }
    
   return (true);
}

function sendForm(publish)
{
 document.form1.OP.value="1";
 if(CheckForm())
   document.form1.submit();
}

</script>

<?
$ROLL_ID=intval($ROLL_ID);
$query="select * from RMS_ROLL where ROLL_ID='$ROLL_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $ROLL_CODE=$ROW["ROLL_CODE"];
    $ROLL_NAME=$ROW["ROLL_NAME"];
    $ROOM_ID0=$ROW["ROOM_ID"];
    $YEARS=$ROW["YEARS"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $DEPT_ID=$ROW["DEPT_ID"];
    $CATEGORY_NO=$ROW["CATEGORY_NO"];
    $DEADLINE=$ROW["DEADLINE"];
    $SECRET=$ROW["SECRET"];
    $CATALOG_NO=$ROW["CATALOG_NO"];
    $ARCHIVE_NO=$ROW["ARCHIVE_NO"];
    $BOX_NO=$ROW["BOX_NO"];
    $MICRO_NO=$ROW["MICRO_NO"];
    $CERTIFICATE_KIND=$ROW["CERTIFICATE_KIND"];
    $CERTIFICATE_START=$ROW["CERTIFICATE_START"];
    $CERTIFICATE_END=$ROW["CERTIFICATE_END"];
    $ROLL_PAGE=$ROW["ROLL_PAGE"];
    $BORROW=$ROW["BORROW"];
    $REMARK=$ROW["REMARK"];    
    $EDIT_DEPT=$ROW["EDIT_DEPT"];    
}
?>
<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("编辑案卷")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="85%" align="center">
  <form enctype="multipart/form-data" action="update.php"  method="post" name="form1">
  <TR>
      <TD class="TableData"><?=_("案 卷 号：")?></TD>
      <TD class="TableData">
       <INPUT name="ROLL_CODE" id="ROLL_CODE" size=20 maxlength="100" class="BigInput" value="<?=$ROLL_CODE?>">
      </TD>
      <TD class="TableData"><?=_("案卷名称：")?></TD>
      <TD class="TableData">
       <INPUT name="ROLL_NAME" id="ROLL_NAME" size=30 maxlength="100" class="BigInput" value="<?=$ROLL_NAME?>">
      </TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("所属卷库：")?></TD>
      <TD class="TableData">
	<select name="ROOM_ID" class="BigSelect">
	<option value="" ></option>
<?
 $query='SELECT * FROM RMS_ROLL_ROOM';
 $cursor= exequery(TD::conn(),$query);
 $RMS_ROLL_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $ROOM_ID=$ROW["ROOM_ID"];
    $ROOM_NAME=$ROW["ROOM_NAME"];
?>	
	<option value="<?=$ROOM_ID?>" <? if ($ROOM_ID==$ROOM_ID0) echo 'selected';?>><?=$ROOM_NAME?></option>
<?
 }
?>
	</select>
      </TD>
      <TD class="TableData"><?=_("归卷年代：")?></TD>
      <TD class="TableData">
        <input type="text" name="YEARS" value="<?=$YEARS?>" size="10" maxlength="50" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("起始日期：")?></TD>
      <TD class="TableData">
        <input type="text" name="BEGIN_DATE" size="10" maxlength="10" class="BigInput" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()">
      
      </TD>
      <TD class="TableData"><?=_("终止日期：")?></TD>
      <TD class="TableData">
        <input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker()">

      </TD>
  </TR>
   <TR>
      <TD class="TableData"><?=_("所属部门：")?></TD>
      <TD class="TableData">
	<select name="DEPT_ID" class="BigSelect">
	<option value="" ></option>
<?
      echo my_dept_tree(0,$DEPT_ID,1);
?>
	</select>
      </TD>
      <TD class="TableData"><?=_("编制机构：")?></TD>
      <TD class="TableData">
        <input type="text" name="EDIT_DEPT" value="<?=$EDIT_DEPT?>" size="20" maxlength="50" class="BigInput">
      </TD>
  </TR>
   <TR>
      <TD class="TableData"><?=_("保管期限：")?></TD>
      <TD class="TableData">
       <INPUT name="DEADLINE" id="DEADLINE" size=10 class="BigInput" value="<?=$DEADLINE?>">
      </TD>
      <TD class="TableData"><?=_("案卷密级：")?></TD>
      <TD class="TableData">
	<select name="SECRET" class="BigSelect">
	  <option value=""<?if($SECRET=="") echo " selected";?>></option>
	  <?=code_list("RMS_SECRET",$SECRET)?>
	</select>
      </TD>
  </TR>
   <TR>
      <TD class="TableData"><?=_("全 宗 号：")?></TD>
      <TD class="TableData">
        <input type="text" name="CATEGORY_NO" value="<?=$CATEGORY_NO?>" size="10" maxlength="50" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("目 录 号：")?></TD>
      <TD class="TableData">
        <input type="text" name="CATALOG_NO" value="<?=$CATALOG_NO?>" size="10" maxlength="50" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("档案馆号：")?></TD>
      <TD class="TableData">
        <input type="text" name="ARCHIVE_NO" value="<?=$ARCHIVE_NO?>" size="10" maxlength="50" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("保险箱号：")?></TD>
      <TD class="TableData">
        <input type="text" name="BOX_NO" value="<?=$BOX_NO?>" size="10" maxlength="50" class="BigInput">
     </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("缩 微 号：")?></TD>
      <TD class="TableData">
        <input type="text" name="MICRO_NO" value="<?=$MICRO_NO?>" size="10" maxlength="50" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("凭证类别：")?></TD>
      <TD class="TableData">
	<select name="CERTIFICATE_KIND" class="BigSelect">
	  <option value=""<?if($CERTIFICATE_KIND=="") echo " selected";?>></option>
	  <?=code_list("RMS_CERTIFICATE_KIND",$CERTIFICATE_KIND)?>
	</select>
     </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("凭证编号(起)：")?></TD>
      <TD class="TableData">
        <input type="text" name="CERTIFICATE_START" value="<?=$CERTIFICATE_START?>" size="10" maxlength="50" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("凭证编号(止)：")?></TD>
      <TD class="TableData">
        <input type="text" name="CERTIFICATE_END" value="<?=$CERTIFICATE_END?>" size="10" maxlength="50" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("页    数：")?></TD>
      <TD class="TableData">
        <input type="text" name="ROLL_PAGE" value="<?=$ROLL_PAGE?>" size="10" maxlength="50" class="BigInput" dataType="Number" require="false" msg="<?=_("页数必须是数字！")?>">
      </TD>
      <TD class="TableData"><?=_("借阅审批：")?></TD>
      <TD class="TableData">
	<select name="BORROW" class="BigSelect">
	<option value="0" ><?=_("否")?></option>
	<option value="1" ><?=_("是")?></option>
	</select>
      </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("备注：")?></TD>
      <TD class="TableData" colSpan=3><input type="text" name="REMARK" value="<?=$REMARK?>" size="50" maxlength="100" class="BigInput"></TD>
   </TR>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
        <input type="hidden" name="OP" value="">
        <input type="hidden" value="<?=$ROLL_ID?>" name="ROLL_ID">
        <input type="hidden" value="<?=$CUR_PAGE?>" name="CUR_PAGE">
        <input type="button" value="<?=_("保存")?>" class="BigButton" onclick="sendForm('0');">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?CUR_PAGE=<?=$CUR_PAGE?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>