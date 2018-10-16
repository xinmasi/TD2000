<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("编辑卷库");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.ROOM_CODE.value=="")
   { alert("<?=_("卷库号不能为空！")?>");
     return (false);
   }

   if(document.form1.ROOM_NAME.value=="")
   { alert("<?=_("卷库名称不能为空！")?>");
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
</script>

<?
$ROOM_ID=intval($ROOM_ID);
$query="select * from RMS_ROLL_ROOM where ROOM_ID='$ROOM_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
    $DEPT_ID=$ROW["DEPT_ID"];
    $ROOM_CODE=$ROW["ROOM_CODE"];
    $ROOM_NAME=$ROW["ROOM_NAME"];
    $REMARK=$ROW["REMARK"];
    $MANAGE_USER=$ROW["MANAGE_USER"];
    $TO_ID=$ROW["VIEW_DEPT_ID"];
    if($TO_ID=="")
    	$TO_ID="ALL_DEPT";
    $TO_NAME="";
    if($TO_ID=="ALL_DEPT")
       $TO_NAME=_("全体部门");
    else
       $TO_NAME=GetDeptNameById($TO_ID);
}
?>
<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("编辑卷库")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="85%" align="center">
  <form action="update.php"  method="post" name="form1">
    <tr>
      <td nowrap class="TableData"> <?=_("卷库号：")?></td>
      <td class="TableData"> 
       <INPUT name="ROOM_CODE" id="ROOM_CODE" size=20 maxlength="100" class="BigInput" value="<?=$ROOM_CODE?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("卷库名称：")?></td>
      <td class="TableData"> 
       <INPUT name="ROOM_NAME" id="ROOM_NAME" size=30 maxlength="100" class="BigInput" value="<?=$ROOM_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("所属部门：")?></td>
      <td class="TableData"> 
	<select name="DEPT_ID" class="inputSelect">
	<option value="" ></option>
<?
      echo my_dept_tree(0,$DEPT_ID,1);
?>
	</select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("卷库内文件的借阅范围(按部门)：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <textarea cols=40 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('6')"><?=_("添加")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
    </tr>
      <tr>
      <td nowrap class="TableData"><?=_("卷库管理员：")?></TD>
      <td class="TableData" colspan="3">
        <input type="hidden" name="USER_ID" value="<?=$MANAGE_USER?>"> 	
        <input type="text" name="USER_NAME" size="13" class="BigStatic" readonly value="<?=td_trim(GetUserNameById($MANAGE_USER))?>">&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('200','','USER_ID', 'USER_NAME')"><?=_("选择")?></a> 
        <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?=_("清空")?></a>  
      </td>
  </tr> 
    <tr>
      <td nowrap class="TableData"> <?=_("备注：")?></td>
      <td class="TableData"> 
        <input type="text" name="REMARK" size="40" maxlength="100" class="BigInput" value="<?=$REMARK?>">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="hidden" name="OP" value="">
        <input type="hidden" value="<?=$ROOM_ID?>" name="ROOM_ID">
        <input type="hidden" value="<?=$CUR_PAGE?>" name="CUR_PAGE">
        <input type="button" value="<?=_("保存")?>" class="BigButton" onclick="sendForm('0');">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?CUR_PAGE=<?=$CUR_PAGE?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>