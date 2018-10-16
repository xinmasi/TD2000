<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("薪酬项目");
include_once("inc/header.inc.php");
?>

<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.ITEM_NAME.value=="")
   { alert("<?=_("薪酬项目名称不能为空！")?>");
     return (false);
   }
   var r = /^\+?[1-9][0-9]*$/;
   if(!r.test(document.form1.ITEM_NUM.value) && document.form1.ITEM_NUM.value!='0')
   {
        alert("项目编号必须为非负整数！");
      form1.ITEM_NUM.focus()
    return (false);
   }
   var NEWFORMULANAME=document.form1.FORMULANAME.value.replace('<','%');
   NEWFORMULANAME=NEWFORMULANAME.replace('>','`');
   document.form1.FORMULANAME.value=NEWFORMULANAME;


   var NEWFORMULA=document.form1.FORMULA.value.replace('<','%');
   NEWFORMULA=NEWFORMULA.replace('>','`');
   document.form1.FORMULA.value=NEWFORMULA;
   return (true);
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

function LoadWindow2()
{
  URL="formula_edit.php";
  myleft=(screen.availWidth-650)/2;
  window.open(URL,"formul_edit","height=350,width=650,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function delete_all()
{
 msg='<?=_("确认要删除全部薪酬项目吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete_all.php";
  window.location=URL;
 }
}
function delete_one(ITEM_ID)
{
	 msg='<?=_("确认要删除此薪酬项目吗？")?>';
	 if(window.confirm(msg))
	 {
	  URL="delete.php?ITEM_ID="+ITEM_ID;
	  window.location=URL;
	 }
}
function show_formul(ITEM_ID)
{
  URL="formula_edit.php?ITEM_ID="+ITEM_ID;
  myleft=(screen.availWidth-650)/2;
  window.open(URL,"formul_edit","height=350,width=650,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
 }
</script>
<?
 $query = "SELECT count(*) from SAL_ITEM";

 $ITEM_COUNT=0;
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $ITEM_COUNT=$ROW[0];
?>

<body class="bodycolor" <? if($ITEM_COUNT<50) echo"onload='document.form1.ITEM_NAME.focus();'"?>>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("添加薪酬项目")?></span>
    </td>
  </tr>
</table>

<div align="center" class="big1">
<b>
	<table width="450" class="TableBlock" align="center" style="text-align: left;">
  <form action="add.php"  method="post" name="form1" onSubmit="return CheckForm();">
  	<tr>
    <td nowrap class="TableContent"><?=_("项目编号：")?></td>
    <td nowrap class="TableData">
       <input type="text" name="ITEM_NUM" class="BigInput" size="10" maxlength="100">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableContent" title="添加项目“税前工资”，“管理中心”将显示单位每月薪酬支出"><?=_("薪酬项目名称：")?></td>
    <td nowrap class="TableData">
       <input type="text" name="ITEM_NAME" class="BigInput" size="30" maxlength="100" <? if($ITEM_COUNT>=50) echo disabled;?>>
       <span style="font-size: 12px; font-weight: normal;"></span>
       <input type="hidden" name="ITEM_ID" value="<?=$ITEM_COUNT+1?>">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableContent"><?=_("项目类型：")?> </td>
    <td nowrap class="TableData">
    	 <select name="ITEM_TYPE" class="SmallSelect" onChange="sel_change()">
       <option value="0"><?=_("财务录入项")?></option>
       <option value="1"><?=_("部门上报项")?></option>
       <option value="2"><?=_("计算项")?></option>
       </select>
    </td>
   </tr>

   <tr id="FORMU" style="display:none">
    <td nowrap class="TableContent"><?=_("计算公式：")?></td>
    <td nowrap class="TableData">
    	<input type="hidden" name="FORMULA">
    	<textarea cols=37 name="FORMULANAME" rows="4" class="BigStatic" readonly  wrap="yes"></textarea>&nbsp;
    	<input type="button" value="<?=_("编辑公式")?>" class="SmallButton" onClick="LoadWindow2()" title="<?=_("编辑公式")?>" name="button">
    </td>
   </tr>
     <tfoot align="center" class="TableFooter">
      <td colspan="2" nowrap>
      	<input type="submit" value="<?=_("添加")?>" class="BigButton" title="<?=_("添加薪酬项目")?>" <? if($ITEM_COUNT>=50) echo disabled;?> name="button">
      </td>
    </tfoot>
  </form>
  </table>
</b>
</div>

<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("已定义的薪酬项目（最多50条）")?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

<?
 //============================ 显示已定义薪酬项目 =======================================
 $query = "SELECT * from SAL_ITEM order by ITEM_NUM,ITEM_ID";
 $cursor= exequery(TD::conn(),$query);
 $ITEM_COUNT1=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $ITEM_COUNT1++;
    $ITEM_ID=$ROW["ITEM_ID"];
    $ITEM_NAME=$ROW["ITEM_NAME"];
    $ISPRINT=$ROW["ISPRINT"];
    $ISCOMPUTER=$ROW["ISCOMPUTER"];

    $FORMULA=$ROW["FORMULA"];
    $FORMULANAME=$ROW["FORMULANAME"];
    $ISREPORT=$ROW["ISREPORT"];
	$ITEM_NUM = $ROW["ITEM_NUM"];

    if($ISREPORT=="1")
   {
	  $ITEMSET=_("部门上报项");
   }
   else
   {
	  if($ISCOMPUTER=="1") $ITEMSET=_("计算项");else $ITEMSET=_("财务录入项");
   }
    if($ITEM_COUNT1==1)
    {
?>

    <table width="450" class="TableList">

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center" width="50"><?=$ITEM_ID?></td>
      <td nowrap align="center"><?=$ITEM_NAME?></td>
      <td nowrap align="center"><?=$ITEM_NUM?></td>
       <td nowrap align="center">
      	<?=$ITEMSET?>
       </td>
      <td nowrap align="center">
      &nbsp;<a href="edit.php?ITEM_ID=<?=$ITEM_ID?>"> <?=_("编辑")?></a>
<?
    if($ITEM_COUNT1==$ITEM_COUNT)
    {
?>
      &nbsp;<a href="javascript:void;" onclick="delete_one('<?=$ITEM_ID?>')" > <?=_("删除")?></a>
<?
    }
     if($ISCOMPUTER=="1")
     {
?>
    	&nbsp;<a href="javascript:show_formul('<?=$ITEM_ID?>');"><?=_("公式编辑")?></a>
 <?
      }
?>
      </td>
    </tr>
<?
 }

 if($ITEM_COUNT>0)
 {
?>
    <tfoot class="TableFooter">
      <td colspan="6" align="center">
        <input type="button" value="<?=_("全部删除")?>" class="SmallButton" onClick="delete_all();">
      </td>
    </tfoot>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("编号")?></td>
      <td nowrap align="center"><?=_("名称")?></td>
      <td nowrap align="center"><?=_("名称编号")?></td>
      <td nowrap align="center"><?=_("项目类型")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("尚未定义"));
?>
</body>
</html>