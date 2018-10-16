<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("积分项编辑");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.TYPE_NO.value=="")
   { alert("<?=_("积分项编号不能为空！")?>");
     return (false);
   }
   
   if(document.form1.TYPE_ORDER.value=="")
   { alert("<?=_("排序号不能为空！")?>");
     return (false);
   }
   
   if(isNaN(document.form1.TYPE_ORDER.value))
   { 
     alert("<?=_("排序号必须为数字！")?>");
     return (false);
   }
   
   if(document.form1.TYPE_NAME.value=="")
   { alert("<?=_("积分项名称不能为空！")?>");
     return (false);
   }
   return (true);
}
</script>

<?
$query = "SELECT * from hr_integral_item_type where TYPE_ID='$TYPE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$TYPE_ID=$ROW["TYPE_ID"];
	$TYPE_NO=$ROW["TYPE_NO"];
	$TYPE_NAME=$ROW["TYPE_NAME"];
	$TYPE_BRIEF=$ROW["TYPE_BRIEF"];
	$TYPE_FROM=$ROW["TYPE_FROM"];
	$TYPE_ORDER=$ROW["TYPE_ORDER"];
}
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑积分项主分类")?></span>
    </td>
  </tr>
</table>

<br>
<table class="TableBlock" width="450" align="center" >
  <form action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData" width="120"><?=_("积分项编号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NO" class="<? if($TYPE_FROM!="3") echo "BigStatic"; else echo "BigInput";?>" size="20" maxlength="100" value="<?=$TYPE_NO?>"<? if($TYPE_FROM!="3") echo " readonly";?>>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("排序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_ORDER" class="BigInput" size="20" maxlength="100" value="<?=$TYPE_ORDER?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("积分项名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NAME" class="<? if($TYPE_FROM!="3") echo "BigStatic"; else echo "BigInput";?>" size="20" maxlength="100" value="<?=$TYPE_NAME?>" <? if($TYPE_FROM!="3") echo " readonly";?>>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("积分项描述：")?></td>
    <td nowrap class="TableData">
      <textarea name="TYPE_BRIEF" class="BigInput" rows="3" cols="30" ><?=$TYPE_BRIEF?></textarea>
    </td>
   </tr>
   <!-- <tr>
    <td nowrap class="TableData"><?=_("积分项类型：")?></td>
    <td nowrap class="TableData">
      <select name="TYPE_FROM" >
      	<option value="1" <?=$TYPE_FROM==1?"selected":""?> ><?=_("OA使用项")	?></option>
      	<option value="2" <?=$TYPE_FROM==2?"selected":""?> ><?=_("人事档案项")	?></option>
      	<option value="3" <?=$TYPE_FROM==3?"selected":""?> ><?=_("自定义项")	?></option>
      </select>
    </td>
   </tr>-->
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="hidden" name="TYPE_ID" value="<?=$TYPE_ID?>">
        <input type="hidden" name="TYPE_FROM" value="<?=$TYPE_FROM?>">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
    </td>
  </form>
</table>

</body>
</html>