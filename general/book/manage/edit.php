<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("图书编辑");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.BOOK_NAME.value=="")
   {
	   alert("<?=_("书名不能为空！")?>");
       return (false);
   }

   if(document.form1.BOOK_NO.value=="")
   {
	   alert("<?=_("编号不能为空！")?>");
       return (false);
   }
   if(document.form1.AMT.value=="")
   {
	   alert("<?=_("数量不能为空！")?>");
       return (false);
   }
   if(document.form1.TO_ID.value=="" && document.form1.COPY_TO_ID.value=="" && document.form1.PRIV_ID.value=="")
   {
	   alert("<?=_("借阅范围不能为空！")?>");
       return (false);
   }
   
   return (true);   
}
</script>

<?
$query = "SELECT * FROM book_info WHERE BOOK_ID='$BOOK_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $BOOK_NAME1         = $ROW["BOOK_NAME"];
  $TYPE_ID1           = $ROW["TYPE_ID"];
  $AUTHOR1            = $ROW["AUTHOR"];
  $ISBN1              = $ROW["ISBN"];
  $PUB_HOUSE1         = $ROW["PUB_HOUSE"];
  $PUB_DATE1          = $ROW["PUB_DATE"];
  $AREA1              = $ROW["AREA"];
  $AMT1               = $ROW["AMT"];
  $PRICE1             = $ROW["PRICE"];
  $BRIEF1             = $ROW["BRIEF"];
  $TEMP               = $ROW["OPEN"];
  $OPEN1              = explode(";",$TEMP);
  $LEND1              = $ROW["LEND"];
  $BORR_PERSON1       = $ROW["BORR_PERSON"];
  $MEMO1              = $ROW["MEMO"];
  $DEPT1              = $ROW["DEPT"];
  $BOOK_NO            = $ROW["BOOK_NO"];
  $ATTACHMENT_ID      = $ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME    = $ROW["ATTACHMENT_NAME"];
  
}
    $OPEN_DEPT=td_trim($OPEN1[0]);
	if($OPEN_DEPT=="ALL_DEPT" || $OPEN_DEPT=="1")
	   $TO_NAME=_("全体部门");
	else{
		$TO_NAME=GetDeptNameById($OPEN_DEPT);
		if($TO_NAME==","){
			$TO_NAME="";
		}
	}
	
	$COPY_TO_NAME=GetUserNameById($OPEN1[1]);

	if($COPY_TO_NAME==","){
		$COPY_TO_NAME="";
	}
	$PRIV_NAME=GetPrivNameById($OPEN1[2]);
	if($PRIV_NAME==","){
		$PRIV_NAME="";

}
?>

<body class="bodycolor" onLoad="document.form1.BOOK_NAME.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("图书信息编辑")?> </span>
    </td>
  </tr>
</table>

<table class="TableBlock"  width="80%"  align="center" >
  <form enctype="multipart/form-data" action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData"><?=_("部门：")?></td>
    <td nowrap class="TableData">
        <select name="DEPT_ID" class="BigSelect">
<?
      echo my_dept_tree(0,$DEPT1,0);
?>
        </select>
    </td>
    <td nowrap class="TableData" rowspan="6" width="150">
<?      
   if($ATTACHMENT_NAME=="")
      echo "<center>"._("暂无封面")."</center>";
   else{
   	  $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
?>
      <a href="<?=$URL_ARRAY["view"]?>" title="<?=_("点击查看放大图片")?>" target="_blank"><img src="<?=$URL_ARRAY["view"]?>" width='150' border=1 alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME?>"></a>
<?
   }
?>    	
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("书名：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="BOOK_NAME" class="BigInput" size="33" maxlength="100" value="<?=$BOOK_NAME1?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("编号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="BOOK_NO" class="BigInput" size="33" maxlength="100" value="<?=$BOOK_NO?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("图书类别：")?></td>
    <td nowrap class="TableData">
        <select name="TYPE_ID" class="BigSelect">
<?
      $query = "SELECT * FROM book_type ORDER BY TYPE_ID";
      $cursor= exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))
      {
         $TYPE_ID2    = $ROW["TYPE_ID"];
         $TYPE_NAME   = $ROW["TYPE_NAME"];

?>
          <option value="<?=$TYPE_ID2?>" <? if($TYPE_ID2==$TYPE_ID1) echo "selected";?>><?=$TYPE_NAME?></option>
<?
      }
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("作者：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="AUTHOR" class="BigInput" size="33" maxlength="100" value="<?=$AUTHOR1?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("ISBN号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="ISBN" class="BigInput" size="33" maxlength="100" value="<?=$ISBN1?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("出版社：")?></td>
    <td nowrap class="TableData" colspan="2">
        <input type="text" name="PUB_HOUSE" class="BigInput" size="33" maxlength="100" value="<?=$PUB_HOUSE1?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("出版日期：")?></td>
    <td nowrap class="TableData" colspan="2">
        <input type="text" name="PUB_DATE" class="BigInput" size="30" maxlength="10" value="<?=$PUB_DATE1?>" onClick="WdatePicker()">&nbsp;
      
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("存放地点：")?></td>
    <td nowrap class="TableData" colspan="2">
        <input type="text" name="AREA" class="BigInput" size="33" maxlength="100" value="<?=$AREA1?>">&nbsp
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("数量：")?></td>
    <td nowrap class="TableData" colspan="2">
        <input type="text" name="AMT" class="BigInput" size="25" maxlength="11" value="<?=$AMT1?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("价格：")?></td>
    <td nowrap class="TableData" colspan="2">
        <input type="text" name="PRICE" class="BigInput" size="25" maxlength="10" value="<?=$PRICE1?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("内容简介：")?></td>
    <td nowrap class="TableData" colspan="2">
      	<textarea cols=37 rows=3 name="BRIEF" class="BigInput" wrap="yes"><?=$BRIEF1?></textarea>
    </td>
   </tr>
   <tr>
     <td nowrap class="TableData"><?=_("借阅范围(部门)：")?></td>
     <td class="TableData" colspan="2">
       <input type="hidden" name="TO_ID" value="<?=$OPEN1[0]?>">
       <textarea cols=37 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
       <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
     </td>
   </tr>
   <tr>
     <td nowrap class="TableData"><?=_("选择人员：")?></td>
     <td class="TableData" colspan="2">
       <input type="hidden" name="COPY_TO_ID" value="<?=$OPEN1[1]?>">
       <textarea cols=37 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$COPY_TO_NAME?></textarea>&nbsp;
       <a href="javascript:;" class="orgAdd" onClick="SelectUser('53','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("选择")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
     </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("选择角色")?></td>
      <td nowrap class="TableData" colspan="2">
      	<input type="hidden" name="PRIV_ID" value="<?=$OPEN1[2]?>">
      	<textarea cols=37 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME?></textarea>
      	<a href="javascript:;" class="orgAdd" onClick="SelectPriv('7','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
      	<a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <!--
   <tr>
    <td nowrap class="TableData" width="120"><?=_("借阅状态：")?></td>
    <td nowrap class="TableData" colspan="2">
      <select name="LEND" class="BigSelect">
        <option value="0" <?if($LEND1=="0") echo "selected";?>><?=_("未借出")?> </option>
        <option value="1" <?if($LEND1=="1") echo "selected";?>><?=_("已借出")?> </option>
      </select>
    </td>
   </tr>-->
   <tr>
    <td nowrap class="TableData" width="120"><?=_("录入人：")?></td>
    <td nowrap class="TableData" colspan="2">
        <input type="text" name="BORR_PERSON" class="BigStatic" size="33" maxlength="100" value="<?=$BORR_PERSON1?>" readonly>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("备注：")?></td>
    <td nowrap class="TableData" colspan="2">
        <input type="text" name="MEMO" class="BigInput" size="33" maxlength="100" value="<?=$MEMO1?>">&nbsp;
    </td>
   </tr> 
   <tr>
     <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("上传封面：")?></span></td>
     <td class="TableData" colspan="2">
       <input type="file" name="ATTACHMENT" size="40" class="BigInput" title="<?=_("选择附件文件")?>">
     </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="3" align="center">
        <input type="hidden" value="<?=$BOOK_ID?>" name="BOOK_ID">
        <input type="hidden" value="<?=$ATTACHMENT_ID?>" name="ATTACHMENT_ID_OLD">
        <input type="hidden" value="<?=$ATTACHMENT_NAME?>" name="ATTACHMENT_NAME_OLD">        
        <input type="hidden" value="TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&ORDER_FIELD=<?=$ORDER_FIELD?>&PAGE_START=<?=$PAGE_START?>" name="QUERY_LIST">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='list.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&ORDER_FIELD=<?=$ORDER_FIELD?>&PAGE_START=<?=$PAGE_START?>'">
    </td>
  </form>
</table>

</body>
</html>