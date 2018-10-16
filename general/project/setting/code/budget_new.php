<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("代码编辑");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{  
   if(document.form1.type_no.value=="")
   { 
    alert("<?=_("代码编号不能为空！")?>");
    return (false);
   }
   
   if(document.form1.type_name.value=="")
   { 
    alert("<?=_("排序号不能为空！")?>");
    return (false);
   }
}
</script>


<body class="bodycolor" onload="document.form1.type_no.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("增加代码项")?></span>
    </td>
  </tr>
</table>

<br>
<table class="TableBlock" width="450" align="center">
  <form action="budget_insert.php"  method="post" name="form1" onsubmit="return CheckForm();">

    <tr>
        <td nowrap class="TableData" width="120"><?=_("科目编号：")?></td>
        <td nowrap class="TableData">
            <input type="text" name="type_no" class="BigInput" size="20" maxlength="100">&nbsp;
            <span><?=_("科目编号为三位数字")?></span>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData" width="120"><?=_("科目名称：")?></td>
        <td nowrap class="TableData">
            <input type="text" name="type_name" class="BigInput" size="20" maxlength="100">&nbsp;
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="120"><?=_("一级科目：")?></td>
        <td nowrap class="TableData">
           <select name="parent_budget" id="parent_budget" style="width:158px; height:24px;">
			<option value=""><?=_("无")?></option>
<?
	$s_query = "SELECT * FROM proj_budget_type where LENGTH(type_no)=3 ";
	$s_cursor = exequery(TD::conn(),$s_query);
	while($t_row = mysql_fetch_array($s_cursor))
	{
?>
		<option value="<?=$t_row['type_no']?>"><?=td_htmlspecialchars($t_row['type_name'])?></option>
<?
	}
?>			
		</select>
        </td>
    </tr>
    
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="hidden" value="<?=$PARENT_NO?>" name="PARENT_NO">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="history.back();">
    </td>
  </form>
</table>

</body>
</html>