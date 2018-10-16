<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("积分项编辑");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet"type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script> 
jQuery(document).ready(function(){      
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});  
</script>


<script Language="JavaScript">
function CheckForm()
{
    var yanzheng=/^\d+$/;
   if(document.form1.PARENT_ITEM.value=="")
   { alert("<?=_("积分项主分类不能为空！")?>");
     return (false);
   }
   
   if(document.form1.ITEM_NO.value=="")
   { alert("<?=_("积分项编号不能为空！")?>");
     return (false);
   }
   
   if(document.form1.ITEM_ORDER.value=="")
   { alert("<?=_("排序号不能为空！")?>");
     return (false);
   }
   
   /*if(!yanzheng.test(document.form1.ITEM_NO.value))
   { 
       alert("<?=_("积分项编号只能是整数！")?>");
       document.form1.ITEM_NO.focus();
        return (false);
   }*/
   if(!yanzheng.test(document.form1.ITEM_ORDER.value))
   { 
       alert("<?=_("排序号只能是整数！")?>");
       document.form1.ITEM_ORDER.focus();
        return (false);
   }
   
  /* if(!yanzheng.test(document.form1.ITEM_VALUE.value) && document.form1.ITEM_VALUE.value !="")
   { 
       alert("<?=_("积分分值只能是整数！")?>");
       document.form1.ITEM_VALUE.focus();
        return (false);
   }*/
   
   if(!yanzheng.test(document.form1.WEIGHT.value) && document.form1.WEIGHT.value !="")
   { 
       alert("<?=_("权重只能是整数！")?>");
       document.form1.WEIGHT.focus();
        return (false);
   }

   for(var i=0; i< document.form1.length; i++)
   {
      if(document.form1[i].name.substr(0, 10) == "ITEM_NAME_" && document.form1[i].value=="")
      { alert("<?=_("积分项名称不能为空！")?>");
        return (false);
      }
   }
}
</script>


<?
 $query = "SELECT * from hr_integral_item where ITEM_ID='$ITEM_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $ITEM_NO=$ROW["ITEM_NO"];
    $ITEM_NAME=$ROW["ITEM_NAME"];
    $ITEM_ORDER=$ROW["ITEM_ORDER"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $ITEM_FLAG=$ROW["ITEM_FLAG"];
     $ITEM_BRIEF=$ROW["ITEM_BRIEF"];
    $ITEM_VALUE=$ROW["ITEM_VALUE"];
    $USED=$ROW["USED"];
    $WEIGHT=$ROW["WEIGHT"];    
 }
 $query = "SELECT * from HR_INTEGRAL_ITEM_TYPE where TYPE_ID='$TYPE_ID'";//TYPE_FROM=3";自定义分类
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
 	$TYPE_FROM=$ROW["TYPE_FROM"];
 }
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑积分项")?></span>
    </td>
  </tr>
</table>

<br>
<form action="update.php"  method="post" name="form1" id="form1" onSubmit="return CheckForm();">
  <table class="TableBlock" width="450" align="center">
   <tr height="30">
    <td nowrap class="TableData" width="120"><?=_("积分项分类：")?></td>
    <td nowrap class="TableData">
        <select name="PARENT_ITEM" class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-6">
<?
 $query = "SELECT * from HR_INTEGRAL_ITEM_TYPE where TYPE_ID='$TYPE_ID'";//TYPE_FROM=3";自定义分类
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
 	$TYPE_ID_X=$ROW["TYPE_ID"];
    $TYPE_NO=$ROW["TYPE_NO"];
    $TYPE_NAME=$ROW["TYPE_NAME"];
?>
          <option value="<?=$TYPE_NO?>"<? if($TYPE_ID_X==$TYPE_ID) echo " selected";?>><?=$TYPE_NAME?></option>
<?
 }
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("积分项编号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="ITEM_NO" class="<? if($TYPE_FROM==3) echo 'BigInput';else echo 'BigStatic';?> validate[required]"  data-prompt-position="centerRight:0,-6" size="20" maxlength="100" value="<?=$ITEM_NO?>" <? if($TYPE_FROM!=3) echo "readonly";?>>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("排序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="ITEM_ORDER" class="BigInput validate[required]" data-prompt-position="centerRight:0,-6" size="20" maxlength="100" value="<?=$ITEM_ORDER?>">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("积分项名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="ITEM_NAME" class="<? if($TYPE_FROM==3) echo 'BigInput';else echo 'BigStatic';?> " size="20" maxlength="100" value="<?=$ITEM_NAME?>" <? if($TYPE_FROM!=3) echo "readonly";?>>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("是否使用：")?></td>
    <td nowrap class="TableData">
    	<input type="radio" name="USED" <?=$USED==1?"checked":""?> value="1" id="is_used" /> <label for="is_used"><?=_("是")?></label>
    	<input type="radio" name="USED" <?=$USED==0?"checked":""?> value="0" id="not_used" /> <label for="not_used"><?=_("否")?></label>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("积分项描述：")?></td>
    <td nowrap class="TableData">
      <textarea name="ITEM_BRIEF" class="BigInput" rows="3" cols="30" ><?=$ITEM_BRIEF?></textarea>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("积分分值：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="ITEM_VALUE" class="BigInput" id="ITEM_VALUE"  size="20" maxlength="100" value="<?=$ITEM_VALUE?>">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("权重：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="WEIGHT" class="BigInput" size="20" maxlength="100" value="<?=$WEIGHT?>">
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="hidden" value="<?=$ITEM_ID?>" name="ITEM_ID">
        <input type="hidden" value="<?=$TYPE_ID?>" name="TYPE_ID">
        <input type="submit" value="<?=_("确定")?>" class="BigButton" onClick="return ITEM_VALUE_INT('ITEM_VALUE','积分')">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="history.back();">
    </td>
  </table>
</form>

</body>
<script type="text/javascript">
function ITEM_VALUE_INT(id,text)    
{   
	var s=document.getElementById(id).value;
	var patrn= /^[-]?\d*$/;  ; 
	if (!patrn.exec(s))
	{
		alert(text+"分值只能是整数！");
		return false
	}
	return true   
}  
</script>
</html>