<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("н����Ŀ");
include_once("inc/header.inc.php");
?>

<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.ITEM_NAME.value=="")
   { alert("<?=_("н����Ŀ���Ʋ���Ϊ�գ�")?>");
     return (false);
   }
   var r = /^\+?[1-9][0-9]*$/;
   if(!r.test(document.form1.ITEM_NUM.value) && document.form1.ITEM_NUM.value!='0')
   {
        alert("��Ŀ��ű���Ϊ�Ǹ�������");
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
 msg='<?=_("ȷ��Ҫɾ��ȫ��н����Ŀ��")?>';
 if(window.confirm(msg))
 {
  URL="delete_all.php";
  window.location=URL;
 }
}
function delete_one(ITEM_ID)
{
	 msg='<?=_("ȷ��Ҫɾ����н����Ŀ��")?>';
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("���н����Ŀ")?></span>
    </td>
  </tr>
</table>

<div align="center" class="big1">
<b>
	<table width="450" class="TableBlock" align="center" style="text-align: left;">
  <form action="add.php"  method="post" name="form1" onSubmit="return CheckForm();">
  	<tr>
    <td nowrap class="TableContent"><?=_("��Ŀ��ţ�")?></td>
    <td nowrap class="TableData">
       <input type="text" name="ITEM_NUM" class="BigInput" size="10" maxlength="100">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableContent" title="�����Ŀ��˰ǰ���ʡ������������ġ�����ʾ��λÿ��н��֧��"><?=_("н����Ŀ���ƣ�")?></td>
    <td nowrap class="TableData">
       <input type="text" name="ITEM_NAME" class="BigInput" size="30" maxlength="100" <? if($ITEM_COUNT>=50) echo disabled;?>>
       <span style="font-size: 12px; font-weight: normal;"></span>
       <input type="hidden" name="ITEM_ID" value="<?=$ITEM_COUNT+1?>">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableContent"><?=_("��Ŀ���ͣ�")?> </td>
    <td nowrap class="TableData">
    	 <select name="ITEM_TYPE" class="SmallSelect" onChange="sel_change()">
       <option value="0"><?=_("����¼����")?></option>
       <option value="1"><?=_("�����ϱ���")?></option>
       <option value="2"><?=_("������")?></option>
       </select>
    </td>
   </tr>

   <tr id="FORMU" style="display:none">
    <td nowrap class="TableContent"><?=_("���㹫ʽ��")?></td>
    <td nowrap class="TableData">
    	<input type="hidden" name="FORMULA">
    	<textarea cols=37 name="FORMULANAME" rows="4" class="BigStatic" readonly  wrap="yes"></textarea>&nbsp;
    	<input type="button" value="<?=_("�༭��ʽ")?>" class="SmallButton" onClick="LoadWindow2()" title="<?=_("�༭��ʽ")?>" name="button">
    </td>
   </tr>
     <tfoot align="center" class="TableFooter">
      <td colspan="2" nowrap>
      	<input type="submit" value="<?=_("���")?>" class="BigButton" title="<?=_("���н����Ŀ")?>" <? if($ITEM_COUNT>=50) echo disabled;?> name="button">
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("�Ѷ����н����Ŀ�����50����")?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

<?
 //============================ ��ʾ�Ѷ���н����Ŀ =======================================
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
	  $ITEMSET=_("�����ϱ���");
   }
   else
   {
	  if($ISCOMPUTER=="1") $ITEMSET=_("������");else $ITEMSET=_("����¼����");
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
      &nbsp;<a href="edit.php?ITEM_ID=<?=$ITEM_ID?>"> <?=_("�༭")?></a>
<?
    if($ITEM_COUNT1==$ITEM_COUNT)
    {
?>
      &nbsp;<a href="javascript:void;" onclick="delete_one('<?=$ITEM_ID?>')" > <?=_("ɾ��")?></a>
<?
    }
     if($ISCOMPUTER=="1")
     {
?>
    	&nbsp;<a href="javascript:show_formul('<?=$ITEM_ID?>');"><?=_("��ʽ�༭")?></a>
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
        <input type="button" value="<?=_("ȫ��ɾ��")?>" class="SmallButton" onClick="delete_all();">
      </td>
    </tfoot>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("���")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("���Ʊ��")?></td>
      <td nowrap align="center"><?=_("��Ŀ����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("��δ����"));
?>
</body>
</html>