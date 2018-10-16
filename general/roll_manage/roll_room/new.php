<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("新建卷库");
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


<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建卷库")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="60%" align="center">
  <form action="add.php"  method="post" name="form1">
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
        <textarea cols=40 name="TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('6', 'TO_ID', 'TO_NAME')"><?=_("添加")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("清空")?></a>
      </td>
    </tr>
      <tr>
      <td nowrap class="TableData"><?=_("卷库管理员：")?></TD>
      <td class="TableData">
        <input type="hidden" name="USER_ID" value="admin"> 	
        <input type="text" name="USER_NAME" size="13" class="BigStatic" readonly value="<?=td_trim(GetUserNameById('admin'))?>">&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('200','','USER_ID', 'USER_NAME')"><?=_("选择")?></a> 
        <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?=_("清空")?></a>  
      </td>
  </tr>    
    <tr>
      <td nowrap class="TableData"> <?=_("备注：")?></td>
      <td class="TableData"> 
        <input type="text" name="REMARK" value="" size="40" maxlength="100" class="BigInput" value="<?=$REMARK?>">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="hidden" name="OP" value="">
        <input type="button" value="<?=_("新建")?>" class="BigButton" onclick="sendForm('1');">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重置")?>" onClick="jQuery('.orgClear').click();jQuery('textarea[name=TO_NAME]').text('')" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>