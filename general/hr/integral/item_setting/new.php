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


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("增加积分项主分类")?></span>
    </td>
  </tr>
</table>

<br>
<form action="insert.php"  method="post" name="form1" onSubmit="return CheckForm();">
  <table class="TableBlock" width="450" align="center">
   <tr>
    <td nowrap class="TableData" width="120"><?=_("积分项编号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NO" class="BigInput" size="20" maxlength="100" value="<?=$TYPE_NO?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("排序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_ORDER" class="BigInput" size="20" maxlength="100" value="<?=$TYPE_ORDER?>">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("积分项名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NAME" class="BigInput" size="20" maxlength="100" value="<?=$TYPE_NAME?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("积分项描述：")?></td>
    <td nowrap class="TableData">
      <textarea name="TYPE_BRIEF" class="BigInput" rows="3" cols="30" ></textarea>
    </td>
   </tr>
   <!--<tr>
    <td nowrap class="TableData"><?=_("积分项类型：")?></td>
    <td nowrap class="TableData">
      <select name="TYPE_FROM" >
      	<option value="1"><?=_("OA使用项")	?></option>
      	<option value="2"><?=_("人事档案项")	?></option>
      	<option value="3"><?=_("自定义项")	?></option>
      </select>
    </td>
   </tr>-->
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="hidden" name="TYPE" value="<?=$TYPE?>" >
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;        
    </td> 
  </table>
</form>

</body>
</html>