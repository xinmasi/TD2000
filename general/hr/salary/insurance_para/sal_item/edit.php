<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("薪酬项目编辑");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{ 
   var pattern = new RegExp(/^\s+$/);
   var re=pattern.test(document.form1.ITEM_NAME.value);
   if(re)
   { alert("<?=_("薪酬项目名称不能为空！")?>");
     return (false);
   }
}

  function LoadWindow2()
{
  URL="formula_edit.php";
  myleft=(screen.availWidth-500)/2;
  window.open(URL,"formul_edit","height=400,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
  }
function sel_change(input)
  {

  	if(form1.ITEM_TYPE.value=="2")
     {
      	document.all("FORMU").style.display="";
      }
    else
    	{
    		document.all("FORMU").style.display="none";
    		document.form1.FORMULA.value="";
    		document.form1.FORMULANAME.value="";
    	}

  }
</script>


<?
 $ITEM_ID = intval($ITEM_ID);
 $query = "SELECT * from SAL_ITEM where ITEM_ID='$ITEM_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    {
     $ITEM_ID=$ROW["ITEM_ID"];
     $ITEM_NAME=$ROW["ITEM_NAME"];
     $ISPRINT=$ROW["ISPRINT"];
     $ISCOMPUTER=$ROW["ISCOMPUTER"];
     $FORMULA=$ROW["FORMULA"];
     $FORMULANAME=$ROW["FORMULANAME"];
     $ISREPORT=$ROW["ISREPORT"];
    }
?>

<body class="bodycolor" onLoad="document.form1.ITEM_NAME.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("薪酬项目编辑")?></span>
    </td>
  </tr>
</table>

<div align="center" class="big1">
<b>
	<table width="450" class="TableBlock" align="center" >
  <form action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableContent"><?=_("薪酬项目名称：")?> </td>
    <td nowrap class="TableData">
       <input type="text" name="ITEM_NAME" class="BigInput" size="30" maxlength="100" value="<?=$ITEM_NAME?>" <? if($ITEM_COUNT>=50) echo disabled;?>>
       <input type="hidden" name="ITEM_ID" value="<?=$ITEM_ID?>">
    </td>
   </tr>

   <tr>
    <td nowrap class="TableContent"><?=_("项目类型：")?> </td>
    <td nowrap class="TableData">
    	 <select name="ITEM_TYPE" class="SmallSelect" onChange="sel_change()">
       <option value="0" <? if($ISREPORT!="1"&&$ISCOMPUTER!="1") echo "selected";?>><?=_("财务录入项")?></option>
       <option value="1" <? if($ISREPORT=="1") echo "selected";?>><?=_("部门上报项")?></option>
       <option value="2" <? if($ISREPORT!="1"&&$ISCOMPUTER=="1") echo "selected";?>><?=_("计算项")?></option>
       </select>
    </td>
   </tr>
   <tr id="FORMU" style=<? if($ISCOMPUTER=="1") {echo "";}else {echo "display:none";}?>>
    <td nowrap class="TableContent"><?=_("计算公式：")?></td>
    <td nowrap class="TableData">
    	<input type="hidden" name="FORMULA" value="<?=$FORMULA?>">
    	<textarea cols=37 name="FORMULANAME" rows="4" class="BigStatic" readonly  wrap="yes"><?=$FORMULANAME?></textarea>&nbsp;
    	<input type="button" value="<?=_("编辑公式")?>" class="SmallButton" onClick="LoadWindow2()" title="<?=_("编辑公式")?>" name="button">
    </td>
   </tr>
     <tfoot align="center" class="TableFooter">
      <td colspan="2" nowrap>
      	<input type="submit" value="<?=_("修改")?>" class="BigButton" title="<?=_("修改薪酬项目")?>"  name="button">
      	 &nbsp;&nbsp;<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php'">
      </td>
    </tfoot>
  </form>
  </table>
</b>
</div>
</body>
</html>