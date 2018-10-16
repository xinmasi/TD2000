<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("积分项新建");
include_once("inc/header.inc.php");
?>



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
   
   if(!yanzheng.test(document.form1.ITEM_ORDER.value))
   { 
       alert("<?=_("排序号只能是整数！")?>");
       document.form1.ITEM_ORDER.focus();
        return (false);
   }
   
   if(!yanzheng.test(document.form1.WEIGHT.value) && document.form1.WEIGHT.value !="")
   { 
       alert("<?=_("权重只能是整数！")?>");
       document.form1.WEIGHT.focus();
        return (false);
   }
   
   var s=document.getElementById("ITEM_VALUE").value;
   var patrn= /^[-]?\d*$/;  ; 
   if (!patrn.exec(s))
   {
		alert("积分分值只能是整数！");
		return false;
   } 
}
</script>


<body class="bodycolor" onLoad="document.form1.ITEM_NO.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("增加积分项")?></span>
    </td>
  </tr>
</table>

<br>
<table class="TableBlock" width="450" align="center">
  <form action="insert.php"  method="post" name="form1" onSubmit="return CheckForm();">
   <tr height="30">
    <td nowrap class="TableData" width="120"><?=_("积分项分类：")?></td>
    <td nowrap class="TableData">
        <select name="PARENT_ITEM" class="BigSelect">
<?
 $query = "SELECT * from HR_INTEGRAL_ITEM_TYPE where TYPE_ID='$TYPE_ID' order by TYPE_ORDER";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
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
        <input type="text" name="ITEM_NO" class="BigInput" size="20" maxlength="100" value="">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("排序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="ITEM_ORDER" class="BigInput" size="20" maxlength="100" value="">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("积分项名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="ITEM_NAME" class="BigInput" size="20" maxlength="100" value="">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("是否使用：")?></td>
    <td nowrap class="TableData">
    	<input type="radio" name="USED" value="1" id="is_used" /> <label for="is_used"><?=_("是")?></label>
    	<input type="radio" name="USED" checked value="0" id="not_used" /> <label for="not_used"><?=_("否")?></label>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("积分项描述：")?></td>
    <td nowrap class="TableData">
      <textarea name="ITEM_BRIEF" class="BigInput" rows="3" cols="30" ></textarea>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("积分分值：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="ITEM_VALUE" id="ITEM_VALUE" class="BigInput" size="20" maxlength="100" value="">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("权重：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="WEIGHT" class="BigInput" size="20" maxlength="100" value="">
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="hidden" name="TYPE_ID"  value="<?=$TYPE_ID?>">
        <input type="submit" value="<?=_("确定")?>" class="BigButton" onClick="return ITEM_VALUE_INT('ITEM_VALUE','积分')">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="history.back();">
    </td>
  </form>
</table>

</body>
<script type="text/javascript">
function ITEM_VALUE_INT(id,text)    
{   
	var s=document.getElementById(id).value;
	var patrn= /^(-?\d+)(\.\d+)?$/;  ; 
	if (!patrn.exec(s))
	{
		alert(text+"分值只能是数字！");
		return false
	}
	return true   
} 
</script>
</html>