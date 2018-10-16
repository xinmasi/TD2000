<?
include_once("inc/auth.inc.php");
include_once("./proj_list_config.php");

$HTML_PAGE_TITLE = _("列表显示编辑");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("更新列表显示")?></span>
    </td>
  </tr>
</table>

<br>
<table class="TableBlock" width="300" align="center">
  <form action="insert.php"  method="post" name="form1">
	<tr>
		<td class="TableHeader" colspan="2" align="center"><?=("可设置字段")?></td>
   </tr>
   <tr>
		<td nowrap class="TableData" width="100"><?=_("项目编号：")?></td>
		<td nowrap class="TableData">
			<input name="PROJ_NUM" id="PROJ_NUM1"  type="radio" <? echo $LIST_ARRAY['PROJ_NUM']==1 ? "checked" : "";?> value="1"/><label for="PROJ_NUM1"><?=_("是")?></label>
			<input name="PROJ_NUM" id="PROJ_NUM2"  type="radio" <? echo $LIST_ARRAY['PROJ_NUM']==0 ? "checked" : "";?> value="0"/><label for="PROJ_NUM2"><?=_("否")?></label>
		</td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="100"><?=_("项目创建人：")?></td>
    <td nowrap class="TableData">
        <input name="PROJ_OWNER_NAME" id="PROJ_OWNER_NAME1"  type="radio" <? echo $LIST_ARRAY['PROJ_OWNER_NAME']==1 ? "checked" : "";?> value="1"/><label for="PROJ_OWNER_NAME1"><?=_("是")?></label>
		<input name="PROJ_OWNER_NAME" id="PROJ_OWNER_NAME2"  type="radio" <? echo $LIST_ARRAY['PROJ_OWNER_NAME']==0 ? "checked" : "";?> value="0"/><label for="PROJ_OWNER_NAME1"><?=_("否")?></label>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("开始时间：")?></td>
    <td nowrap class="TableData">
        <input name="PROJ_START_TIME" id="PROJ_START_TIME1"  type="radio" <? echo $LIST_ARRAY['PROJ_START_TIME']==1 ? "checked" : "";?> value="1"/><label for="PROJ_START_TIME1"><?=_("是")?></label>
		<input name="PROJ_START_TIME" id="PROJ_START_TIME2"  type="radio" <? echo $LIST_ARRAY['PROJ_START_TIME']==0 ? "checked" : "";?> value="0"/><label for="PROJ_START_TIME2"><?=_("否")?></label>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("计划结束时间：")?></td>
    <td nowrap class="TableData">
        <input name="PROJ_END_TIME" id="PROJ_END_TIME1"  type="radio" <? echo $LIST_ARRAY['PROJ_END_TIME']==1 ? "checked" : "";?> value="1"/><label for="PROJ_END_TIME1"><?=_("是")?></label>
		<input name="PROJ_END_TIME" id="PROJ_END_TIME2"  type="radio" <? echo $LIST_ARRAY['PROJ_END_TIME']==0 ? "checked" : "";?> value="0"/><label for="PROJ_END_TIME2"><?=_("否")?></label>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("实际结束时间：")?></td>
    <td nowrap class="TableData">
        <input name="PROJ_ACT_END_TIME" id="PROJ_ACT_END_TIME1"  type="radio" <? echo $LIST_ARRAY['PROJ_ACT_END_TIME']==1 ? "checked" : "";?> value="1"/><label for="PROJ_ACT_END_TIME1"><?=_("是")?></label>
		<input name="PROJ_ACT_END_TIME" id="PROJ_ACT_END_TIME2"  type="radio" <? echo $LIST_ARRAY['PROJ_ACT_END_TIME']==0 ? "checked" : "";?> value="0"/><label for="PROJ_ACT_END_TIME2"><?=_("否")?></label>
    </td>
   </tr>
    <tr>
    <td nowrap class="TableData"><?=_("全局变量")?></td>
    <td nowrap class="TableData">
        <input name="PROJ_GLOBAL_VAL" id="PROJ_GLOBAL_VAL1"  type="radio" <? echo $LIST_ARRAY['PROJ_GLOBAL_VAL']==1 ? "checked" : "";?> value="1"/><label for="PROJ_GLOBAL_VAL1"><?=_("是")?></label>
		<input name="PROJ_GLOBAL_VAL" id="PROJ_GLOBAL_VAL2"  type="radio" <? echo $LIST_ARRAY['PROJ_GLOBAL_VAL']==0 ? "checked" : "";?> value="0"/><label for="PROJ_GLOBAL_VAL2"><?=_("否")?></label>
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
    </td>
  </form>
</table>
</body>
</html>