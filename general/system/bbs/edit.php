<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 ���ӷ�������ѯ�ж�
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";


$HTML_PAGE_TITLE = _("�������½���༭");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet"type="text/css" href="<?=MYOA_ATATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script> 
jQuery(document).ready(function(){      
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});  
</script>
<style>
    input.BigStatic, textarea.BigStatic{
        width:280px;
    }
</style>


<script>
function check_form()
{
  if(document.form1.BOARD_NAME.value =="")
  {
     alert("<?=_('���������Ʋ���Ϊ��!')?>");
     return false;
  }

  if(document.form1.TO_ID.value=="" && document.form1.PRIV_ID.value=="" && document.form1.TO_ID3.value=="")
  {
  	 alert("<?=_('��ָ��������Χ��')?>");
     return false;
  }

  return true;
}
function make_selected(mbox,i)
{
	for(k=0;k<mbox.options.length;k++)
	{
		mbox.options[k].selected="";
		if(k==i)
			mbox.options[k].selected="selected";
	}
}
function move(fbox,tbox) 
{
   var i = 0;
   if(fbox.value != "") 
   {
      var no = new Option();
      var j = tbox.options.length;
      no.value = fbox.value;
      no.text = fbox.value;
      tbox.options[j] = no;
      make_selected(tbox,j);
      fbox.value = "";
   }
   else
   	{
   		alert('<?=_("��ӷ���������Ϊ�գ�")?>');
   		exit;
   	}
   GenCategory(tbox);
}

function remove(box) 
{
  for(var i=0; i<box.options.length; i++) 
  {
  	 if(box.options[i].selected)
  	 		k=i;
     if(box.options[i].selected && box.options[i] != "") 
     {
        box.options[i].value = "";
        box.options[i].text = "";
     }
  }
  BumpUp(box);
  if(k>1)
  	make_selected(box,k-1);
  GenCategory(box);
} 

function BumpUp(abox) 
{
   for(var i = 0; i < abox.options.length; i++) 
   {
      if(abox.options[i].value == "")  
      {
         for(var j = i; j < abox.options.length - 1; j++)  
         {
            abox.options[j].value = abox.options[j + 1].value;
            abox.options[j].text = abox.options[j + 1].text;
         }
         var ln = i;
         break;
      }
   }
   if(ln < abox.options.length)  
   {
      abox.options.length -= 1;
      BumpUp(abox);
   }
   GenCategory(abox);   
}

function Moveup(dbox) 
{
   for(var i = 0; i < dbox.options.length; i++) 
   {
   		if(dbox.options[i].selected)
   			k=i;
      if (dbox.options[i].selected && dbox.options[i] != "" && dbox.options[i] != dbox.options[0]) 
      {
         var tmpval = dbox.options[i].value;
         var tmpval2 = dbox.options[i].text;
         dbox.options[i].value = dbox.options[i - 1].value;
         dbox.options[i].text = dbox.options[i - 1].text;
         dbox.options[i-1].value = tmpval;
         dbox.options[i-1].text = tmpval2;
      }
   }
   if(k!=0)
   		make_selected(dbox,k-1);
   GenCategory(dbox);
}
function Movedown(ebox) 
{
   for(var i = 0; i < ebox.options.length; i++) 
   {
   	 if(ebox.options[i].selected)
   	 		var k=i;
     if (ebox.options[i].selected && ebox.options[i] != "" && ebox.options[i+1] != ebox.options[ebox.options.length]) 
     {
        var tmpval = ebox.options[i].value;
        var tmpval2 = ebox.options[i].text;
        ebox.options[i].value = ebox.options[i+1].value;
        ebox.options[i].text = ebox.options[i+1].text;
        ebox.options[i+1].value = tmpval;
        ebox.options[i+1].text = tmpval2;
      }
    }
    if(k!=ebox.options.length-1)
    	make_selected(ebox,k+1);
    GenCategory(ebox);
}
function GenCategory(box)
{
	var tmpval="";
	for(var i=0; i<box.options.length; i++) 
	{
		 tmpval += box.options[i].value+",";
	}
	document.form1.CATEGORY.value=tmpval;
	//return tmpval;
}
</SCRIPT>


<body class="bodycolor" onLoad="document.form1.BOARD_NAME.focus();">

<?
if($BOARD_ID!="")
   $TITLE = _("�༭������");
else
   $TITLE = _("�½�������");
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=$TITLE?></span><br>
    </td>
  </tr>
</table>

<?
if($BOARD_ID!="")
{
   $query = "SELECT * from BBS_BOARD where BOARD_ID='$BOARD_ID'";
   $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
   if($ROW=mysql_fetch_array($cursor))
   {
      $BOARD_NAME = $ROW["BOARD_NAME"];
      $BOARD_NO = $ROW["BOARD_NO"];
      $DEPT_ID1 = $ROW["DEPT_ID"];
      $PRIV_ID = $ROW["PRIV_ID"];
      $USER_ID = $ROW["USER_ID"];
	   
      if($DEPT_ID1[strlen($DEPT_ID1)-1]!=',' && $DEPT_ID1!='ALL_DEPT' && $DEPT_ID1!="")
         $DEPT_ID1=$DEPT_ID1.",";
      $ANONYMITY_YN = $ROW["ANONYMITY_YN"];
      $WELCOME_TEXT = $ROW["WELCOME_TEXT"];
      $BOARD_HOSTER = $ROW["BOARD_HOSTER"];
	    $CATEGORY = $ROW["CATEGORY"];
	    $LOCK_DAYS_BEFORE = $ROW["LOCK_DAYS_BEFORE"];
	    $NEED_CHECK= $ROW["NEED_CHECK"];
   }

}

$query="select * from DEPARTMENT";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   		$DEPT_ID=$ROW["DEPT_ID"];
  		$DEPT_NAME=$ROW["DEPT_NAME"];
   if(find_id($DEPT_ID1,$DEPT_ID))
      $TO_NAME.=$DEPT_NAME.",";
}
if($DEPT_ID1=="ALL_DEPT")
   $TO_NAME=_("ȫ�岿��");

$TOK=strtok($PRIV_ID,",");
while($TOK!="")
{
   $query1 = "SELECT * from USER_PRIV where USER_PRIV='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $PRIV_NAME.=$ROW["PRIV_NAME"].",";
   $TOK=strtok(",");
}

$TOK=strtok($USER_ID,",");
while($TOK!="")
{
   $query1 = "SELECT * from USER where USER_ID='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_NAME1.=$ROW["USER_NAME"].",";
   $TOK=strtok(",");
}

$TOK=strtok($BOARD_HOSTER,",");
while($TOK!="")
{
   $query1 = "SELECT * from USER where USER_ID='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_NAME.=$ROW["USER_NAME"].",";
   $TOK=strtok(",");
}
?>

<br>

  <form action='<? if($BOARD_ID!="") echo "update"; else echo "insert";?>.php' method="post" name="form1" id="form1" onSubmit="return check_form();">
     <table class="TableBlock"  width="508" align="center">
     <tr>
      <td width="105" nowrap class="TableData"><?=_("����ţ�")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="BOARD_NO" size="10" maxlength="50" class="BigInput validate[required,custom[number],maxSize[10]]" data-prompt-position="centerRight:0,-4" value="<?=$BOARD_NO?>">
     </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("���������ƣ�")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="BOARD_NAME" size="30" maxlength="50" class="id="form1" id="form1"" value="<?=$BOARD_NAME?>">
      </td>
    </tr>
    <tr>
      <td valign="top" nowrap class="TableData"><?=_("��������飺")?></td>
      <td class="TableData" colspan="2">
      	<textarea name="WELCOME_TEXT" rows=2 cols=36><?=$WELCOME_TEXT?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("Ȩ�����ƣ�")?></td>
      <td class="TableData" colspan="2">
      <?=sprintf(_("����%s��ǰ�����ӣ��޷��༭ɾ�� (˵����0��ձ�ʾ������)"),"<input type='text' name='LOCK_DAYS_BEFORE' size='3' maxlength='19' class='BigInput' value='".$LOCK_DAYS_BEFORE."' style='text-align:center;'>")?> 
    </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("���ŷ�Χ(����)��")?></td>
      <td class="TableData" colspan="2">
        <input type="hidden" name="TO_ID" value="<?=$DEPT_ID1?>">
        <textarea cols=36 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("���ŷ�Χ(��ɫ)��")?></td>
      <td class="TableData" colspan="2">
        <input type="hidden" name="PRIV_ID" value="<?=$PRIV_ID?>">
        <textarea cols=36 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("���ŷ�Χ(��Ա)��")?></td>
      <td class="TableData" colspan="2">
        <input type="hidden" name="TO_ID3" value="<?=$USER_ID?>">
        <textarea cols=36 name="TO_NAME3" rows=2 class="BigStatic" wrap="yes" readonly><?=$USER_NAME1?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('34','','TO_ID3', 'TO_NAME3')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID3', 'TO_NAME3')"><?=_("���")?></a>
      </td>
   </tr>
    <tr>
      <td nowrap class="TableData"><?=_("������")?></td>
      <td class="TableData" colspan="2">
        <input type="hidden" name="COPY_TO_ID" value="<?=$BOARD_HOSTER?>">
        <textarea cols=36 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$USER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('34','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�������ã�")?></td>
      <td class="TableData" colspan="2">
        <select name="ANONYMITY_YN" class="BigSelect">
          <option value="1" <?if($ANONYMITY_YN=="1") echo "selected";?>><?=_("��������")?></option>
          <option value="0" <?if($ANONYMITY_YN=="0") echo "selected";?>><?=_("��ֹ����")?></option>
          <option value="2" <?if($ANONYMITY_YN=="2") echo "selected";?>><?=_("��ֹ����")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�����Ƿ���Ҫ��ˣ�")?></td>
      <td class="TableData" colspan="2">
        <select name="NEED_CHECK" class="BigSelect">
          <option value="1" <?if($NEED_CHECK=="1") echo "selected";?>><?=_("��Ҫ���")?></option>
          <option value="0" <?if($NEED_CHECK=="0") echo "selected";?>><?=_("�������")?></option>
        </select>
      </td>
    </tr>
<? 
if($CATEGORY!="")
{
   if(substr($CATEGORY,-1)==",")
    	$CATEGORY=substr($CATEGORY,0,-1);
	 $TYPE_ARRAY=explode(",",$CATEGORY); 
}
	?>
    <tr>
      <td nowrap class="TableData"><?=_("���ӷ��ࣺ")?></td>
        <td width="107" align="center" bgcolor="#FFFFFF">
        <select  name="list2" style="width:100px">
<?
$index=0;
if($TYPE_ARRAY[0]!="")
{
	foreach($TYPE_ARRAY as $TYPE)
	{
?>
			<option value="<?=$TYPE_ARRAY[$index]?>"><?=$TYPE?></option>
<?
			$index++;
	}
}
?>
        </select>
        
        
        </td>
        
        <td width="280" bgcolor="#FFFFFF" valign="bottom">
        <input type="hidden" name="CATEGORY" value="<?=$CATEGORY?>">
        <input type="text" name="list1" value="" size="20"><br>
        <input type="button" value=<?=_("���")?> onClick="move(this.form.list1,this.form.list2)" name="B1">
        <input type="button" value=<?=_("����")?> onClick="Moveup(this.form.list2)" name="B3">
        <input type="button" value=<?=_("����")?> onClick="Movedown(this.form.list2)" name="B4">
        <input type="button" value=<?=_("ɾ��")?> onClick="remove(this.form.list2)" name="B2">
        

        
        
        </td>
    </tr>
<?
if($BOARD_ID=="")
{
?>
    <tr>
      <td nowrap class="TableData"><?=_("���ѣ�")?></td>
      <td class="TableData" colspan="2">
<?=sms_remind(18);?>
      </td>
    </tr>
<?
}
?>
    <tr align="center" class="TableControl">
      <td colspan="3" nowrap>
        <input type='hidden' value="<?=$BOARD_ID?>" name="BOARD_ID">
        <input type="submit"  value=<?=_("����")?> class="BigButton" name="submit">&nbsp;&nbsp;
        <input type="button"  value=<?=_("����")?> class="BigButton" name="back" onClick="history.back();";>
      </td>
    </tr>
  </form>
</table>
</body>
</html>