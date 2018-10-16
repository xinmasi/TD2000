<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("代码编辑");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.CODE_NO.value=="")
   { alert("<?=_("代码编号不能为空！")?>");
     return (false);
   }
   
   if(document.form1.CODE_ORDER.value=="")
   { alert("<?=_("排序号不能为空！")?>");
     return (false);
   }
   
   if(document.form1.CODE_NAME.value=="")
   { alert("<?=_("代码名称不能为空！")?>");
     return (false);
   }
   
   if(isNaN(document.form1.CODE_ORDER.value))
   { alert("<?=_(" 排序号必须为数字！")?>");
     return (false);
   }
}
</script>


<?
 $query = "SELECT * from HR_CODE where CODE_ID='$CODE_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $CODE_NO=$ROW["CODE_NO"];
    $CODE_NAME =$ROW["CODE_NAME"];
    $CODE_ORDER =$ROW["CODE_ORDER"];
    $CODE_FLAG=$ROW["CODE_FLAG"];
 }
?>

<body class="bodycolor" onload="document.form1.CODE_NO.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑代码主分类")?></span>
    </td>
  </tr>
</table>

<br>
<table class="TableBlock" width="450" align="center" >
  <form action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData" width="120"><?=_("代码编号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="CODE_NO" class="<? if($CODE_FLAG=="0") echo "BigStatic"; else echo "BigInput";?>" size="20" maxlength="100" value="<?=$CODE_NO?>"<? if($CODE_FLAG=="0") echo " readonly";?>>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("排序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="CODE_ORDER" class="BigInput" size="20" maxlength="100" value="<?=$CODE_ORDER?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("代码名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="CODE_NAME" class="BigInput" size="20" maxlength="100" value="<?=$CODE_NAME?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="hidden" name="CODE_ID" value="<?=$CODE_ID?>">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
    </td>
  </form>
</table>

</body>
</html>