<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("发布案卷");
include_once("inc/header.inc.php");

?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
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
   if(document.form1.SECRET.value == ""){
   		alert("<?=_("案卷密级不能为空！")?>");
   		return (false);
   }
   if(document.form1.ROOM_ID.value == ""){
   		alert("<?=_("所属卷库不能为空！")?>");
   		return (false);
   }
   if(document.form1.DEPT_ID.value == ""){
   		alert("<?=_("所属部门不能为空！")?>");
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


<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建案卷")?></span>
    </td>
  </tr>
</table>


  <form enctype="multipart/form-data" action="add.php"  method="post" name="form1">
  <table class="TableBlock" width="95%" align="center"><TR>
      <TD class="TableData"><?=_("案卷号：")?></TD>
      <TD class="TableData">
       <INPUT name="ROLL_CODE" id="ROLL_CODE" size=20 maxlength="100" dataType="Require" require="true" msg="<?=_("文件号不能为空！")?>" class="BigInput"><span style="color:red;">&nbsp;*</span>
      </TD>
      <TD class="TableData"><?=_("案卷名称：")?></TD>
      <TD class="TableData">
       <INPUT name="ROLL_NAME" id="ROLL_NAME" size=30 maxlength="100" class="BigInput"><span style="color:red;">&nbsp;*</span>
      </TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("所属卷库：")?></TD>
      <TD class="TableData">
	<select name="ROOM_ID" class="BigSelect">
	<option value="" ></option>
<?
$query_str = '';
$DEPT_ID   = '';
$DEPT_ID   = get_dept_parent_all($_SESSION['LOGIN_DEPT_ID']);
if($_SESSION["LOGIN_USER_PRIV"]!=1)
{
	$query_str.=' where DEPT_ID =0 or DEPT_ID in ('.$DEPT_ID.$_SESSION['LOGIN_DEPT_ID'].')';
}
if($_SESSION["LOGIN_USER_PRIV"]!=1 && $_SESSION['LOGIN_DEPT_ID_OTHER']!="")
{
	$query_str.= 'or FIND_IN_SET (DEPT_ID,"'.($_SESSION['LOGIN_DEPT_ID_OTHER']).'") ';
}
$query  = 'SELECT * FROM RMS_ROLL_ROOM '.$query_str.'order by ROOM_CODE desc';
$cursor = exequery(TD::conn(),$query);
$RMS_ROLL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
	$ROOM_ID   = $ROW["ROOM_ID"];
	$ROOM_NAME = $ROW["ROOM_NAME"];
?>	
	<option value="<?=$ROOM_ID?>" ><?=$ROOM_NAME?></option>
<?
 }
?>
	</select><span style="color:red;">&nbsp;*</span>
      </TD>
      <TD class="TableData"><?=_("归卷年代：")?></TD>
      <TD class="TableData">
        <input type="text" name="YEARS" value="" size="10" maxlength="50" class="BigInput">
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
	</select><span style="color:red;">&nbsp;*</span>
      </TD>
      <TD class="TableData"><?=_("编制机构：")?></TD>
      <TD class="TableData">
        <input type="text" name="EDIT_DEPT" value="" size="20" maxlength="50" class="BigInput">
      </TD>
  </TR>
   <TR>
      <TD class="TableData"><?=_("保管期限：")?></TD>
      <TD class="TableData">
       <INPUT name="DEADLINE" id="DEADLINE" size=10 class="BigInput">
      </TD>
      <TD class="TableData"><?=_("案卷密级：")?></TD>
      <TD class="TableData">
	<select name="SECRET" class="BigSelect">
	  <option value=""<?if($SECRET=="") echo " selected";?>></option>
	  <?=code_list("RMS_SECRET",$SECRET, "D", "", "")?>
	</select><span style="color:red;">&nbsp;*</span>
      </TD>
  </TR>
   <TR>
      <TD class="TableData"><?=_("全 宗 号：")?></TD>
      <TD class="TableData">
        <input type="text" name="CATEGORY_NO" value="" size="10" maxlength="50" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("目 录 号：")?></TD>
      <TD class="TableData">
        <input type="text" name="CATALOG_NO" value="" size="10" maxlength="50" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("档案馆号：")?></TD>
      <TD class="TableData">
        <input type="text" name="ARCHIVE_NO" value="" size="10" maxlength="50" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("保险箱号：")?></TD>
      <TD class="TableData">
        <input type="text" name="BOX_NO" value="" size="10" maxlength="50" class="BigInput">
     </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("缩 微 号：")?></TD>
      <TD class="TableData">
        <input type="text" name="MICRO_NO" value="" size="10" maxlength="50" class="BigInput">
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
        <input type="text" name="CERTIFICATE_START" value="" size="10" maxlength="50" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("凭证编号(止)：")?></TD>
      <TD class="TableData">
        <input type="text" name="CERTIFICATE_END" value="" size="10" maxlength="50" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("页    数：")?></TD>
      <TD class="TableData">
        <input type="text" name="ROLL_PAGE" value="" size="10" maxlength="50" class="BigInput" dataType="Number" require="false" msg="<?=_("页数必须是数字！")?>">
      </TD>
      <TD nowrap class="TableData"><?=_("备注：")?></TD>
      <TD class="TableData"><input type="text" name="REMARK" value="" size="30" maxlength="100" class="BigInput"></TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("案卷管理员：")?></TD>
      <TD class="TableData" colspan="3">
        <input type="hidden" name="TO_ID"> 	
        <input type="text" name="TO_NAME" size="13" class="BigStatic" readonly>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('200','','TO_ID', 'TO_NAME')"><?=_("选择")?></a> 
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>  
      </TD>
  </TR>    
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
        <input type="hidden" name="OP" value="">
        <input type="button" value="<?=_("保存")?>" class="BigButton" onClick="sendForm('0');">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重置")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>