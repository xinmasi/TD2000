<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("积分项编辑");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
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
 $query = "SELECT * from integral_item where ITEM_ID='$ITEM_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $ITEM_NO=$ROW["ITEM_NO"];
    $ITEM_NAME=$ROW["ITEM_NAME"];
    $ITEM_EXT=unserialize($ROW["ITEM_EXT"]);
	  if(is_array($ITEM_EXT) && $ITEM_EXT[MYOA_LANG_COOKIE] != "")
		   $ITEM_NAME = $ITEM_EXT[MYOA_LANG_COOKIE];
    $ITEM_BRIEF=$ROW["ITEM_BRIEF"];
    $ITEM_VALUE=$ROW["ITEM_VALUE"];
    $USED=$ROW["USED"];
    $ITEM_ORDER=$ROW["ITEM_ORDER"];
    $PARENT_ITEM=$ROW["PARENT_ITEM"];
    $ITEM_FLAG=$ROW["ITEM_FLAG"];
 }
?>

<body class="bodycolor" onload="document.form1.ITEM_ORDER.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑积分项")?></span>
    </td>
  </tr>
</table>

<br>
<table class="TableBlock" width="450" align="center">
  <form action="update_no.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr height="30">
    <td nowrap class="TableData" width="120"><?=_("积分项主分类：")?></td>
    <td nowrap class="TableData">
        <select name="PARENT_ITEM" class="BigSelect">
          <option value="<?=$PARENT_ITEM?>"></option>
<?
 $query = "SELECT * from integral_item where PARENT_ITEM='' order by ITEM_ORDER";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $ITEM_NO1=$ROW["ITEM_NO"];
    $ITEM_NAME1=$ROW["ITEM_NAME"];
    $ITEM_EXT1=unserialize($ROW["ITEM_EXT"]);
	  if(is_array($ITEM_EXT1) && $ITEM_EXT1[MYOA_LANG_COOKIE] != "")
		   $ITEM_NAME1 = $ITEM_EXT1[MYOA_LANG_COOKIE];
?>
          <option value="<?=$ITEM_NO1?>"<? if($ITEM_NO1==$PARENT_ITEM) echo " selected";?>><?=$ITEM_NAME1?></option>
<?
 }
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("积分项编号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="ITEM_NO" class="<? if($ITEM_FLAG=="0") echo "BigStatic"; else echo "BigInput";?>" size="20" maxlength="100" value="<?=$ITEM_NO?>"<? if($ITEM_FLAG=="0") echo " readonly";?>>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("排序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="ITEM_ORDER" class="BigInput" size="10" maxlength="10" value="<?=$ITEM_ORDER?>">
    </td>
   </tr>
<?
$LANG_ARRAY = get_lang_array();
foreach($LANG_ARRAY as $LANG => $LANG_DESC)
{
   $ITEM_NAME_EXT = '';
   if($LANG == MYOA_DEFAULT_LANG)
      $ITEM_NAME_EXT = $ITEM_NAME;
   else if(is_array($ITEM_EXT) && $ITEM_EXT[$LANG] != "")
      $ITEM_NAME_EXT = $ITEM_EXT[$LANG];
?>
   <tr>
    <td nowrap class="TableData"><?=_("积分项名称：")?>(<?=$LANG_DESC?>)</td>
    <td nowrap class="TableData">
        <input type="text" name="ITEM_NAME_<?=bin2hex($LANG)?>" class="BigInput" size="20" maxlength="50" value="<?=$ITEM_NAME?>">&nbsp;
    </td>
   </tr>
<?
}
?>
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
        <input type="text" name="ITEM_VALUE" class="BigInput" size="20" maxlength="100" value="<?=$ITEM_VALUE?>">
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="hidden" value="<?=$ITEM_ID?>" name="ITEM_ID">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="history.back();">
    </td>
  </form>
</table>

</body>
</html>