<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("用户查找");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script Language="JavaScript">
function CheckForm(form_action)
{
   document.form1.action=form_action;
   document.form1.submit();
}

function show_item_type(item_type){
   if(item_type==3)
	   document.getElementById("item_type").style.display="";
   else
	   document.getElementById("item_type").style.display="none";
}
</script>
<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("积分人员查询")?></span>
    </td>
  </tr>
</table>
<form action="search.php?ispirit_export=1" method="post" name="form1">
<table class="TableBlock" width="50%" align="center">
  	<tr>
      <td nowrap class="TableData" width="80"><?=_("积分人：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" id="TO_ID" value="<?=$TO_ID?>">
        <textarea  name="TO_NAME" id="TO_NAME" rows="2" cols="30"  class="SmallStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="#" class="orgAdd" onClick="SelectUser('27','2','TO_ID', 'TO_NAME')" title="<?=_("添加要查询的积分人")?>"><?=_("添加")?></a>
        <a href="#" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')" title="<?=_("清空要查询的积分人")?>"><?=_("清空")?></a>
    </tr>
   <tr>
    <td nowrap class="TableData"><?=_("性别：")?></td>
    <td nowrap class="TableData">
        <select name="SEX" class="BigSelect" >
        <option value=""></option>
        <option value="0"><?=_("男")?></option>
        <option value="1"><?=_("女")?></option>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("部门：")?></td>
    <td nowrap class="TableData">
        <select name="DEPT_ID" class="BigSelect">
       	<option value=""></option>
        <option value=""><?=_("全体部门")?></option>
<?
      echo my_dept_tree(0,$DEPT_ID,1);
      if($DEPT_ID==0)
      {
?>
          <option value="0"><?=_("离职人员/外部人员")?></option>

<?
      }
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("角色：")?></td>
    <td nowrap class="TableData">
        <select name="USER_PRIV" class="BigSelect">
        <option value=""></option>
<?
      $query = "SELECT * from USER_PRIV order by PRIV_NO desc";
      $cursor= exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))
      {
         $USER_PRIV1=$ROW["USER_PRIV"];
         $PRIV_NAME=$ROW["PRIV_NAME"];

?>
          <option value="<?=$USER_PRIV1?>"><?=$PRIV_NAME?></option>
<?
      }
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("积分类型：")?></td>
    <td nowrap class="TableData">
        <select name="INTEGRAL_TYPE" class="BigSelect" onchange="show_item_type(this.value)">
        <option value=""><?=_("请选择积分类型")?></option>
        <option value="0"><?=_("未定义积分")?></option>
        <option value="1"><?=_("OA使用积分")?></option>
        <option value="3"><?=_("自定义积分")?></option>
        </select>
    </td>
   </tr>
   <tr id="item_type" style="display:none;">
    <td nowrap class="TableData"><?=_("自定义积分类型：")?></td>
    <td nowrap class="TableData">
    		<select name="ITEM_TYPE" >
    			<option value=""><?=_("请选择自定义项分类")?></option>
<?
$query="select * from HR_INTEGRAL_ITEM_TYPE where TYPE_FROM=3";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$TYPE_ID=$ROW["TYPE_ID"];
	$TYPE_NO=$ROW["TYPE_NO"];
	$TYPE_NAME=$ROW["TYPE_NAME"];
?>
		     <option value="<?=$TYPE_ID?>"><?=$TYPE_NAME?></option>
<?
}
?>
    		</select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("时间：")?></td>
    <td nowrap class="TableData">
           <input type="text" name="begin" size="12" maxlength="10" class="BigInput" value="<?=$begin?>" id="start_time" onClick="WdatePicker()" /> <?=_("至")?>&nbsp;
        <input type="text" name="end" size="12" maxlength="10" class="BigInput" value="<?=$end?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})" />
    </td>
   </tr>

   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="button" value="<?=_("查询")?>" class="BigButton" onclick="CheckForm('index1.php');" title="<?=_("查询积分")?>" name="button">&nbsp;&nbsp;
        <input type="button" value="<?=_("导出总分记录")?>" class="BigButton" onclick="CheckForm('export.php');" title="<?=_("导出积分")?>" name="button">
        <input type="button" value="<?=_("导出详细记录")?>" class="BigButton" onclick="CheckForm('export_all.php');" title="<?=_("导出详细积分")?>" name="button">
    </td>

</table>
</form>
</body>
</html>