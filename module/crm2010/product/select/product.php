<?
include_once("general/crm/inc/header.php");
include_once("general/crm/utils/dataview/dataview.interface.php");
include_once("general/crm/utils/search/search.interface.php");
include_once("general/crm/platform/base/auth.func.php");
include_once("general/crm/apps/crm/include/interface/list.interface.php");
include_once("general/crm/studio/include/utility.ui.php");
include_once("general/crm/studio/include/entity.class.php");
include_once("general/crm/apps/crm/include/general.func.php");
include_once("general/crm/studio/include/classes/filter/filter_inter.php");
include_once("general/crm/studio/include/entityAction.php");
//echo $PROD_TYPE;
$MODULE      = "CRM_PRODUCT";
$MODULE_NAME = _("产品管理");
$COL_SIZE = 8;
$KEY_COLUMN = "CRM_PRODUCT.id";
$LIST_PAGE_SIZE = 20;
if($PROD_TYPE){
	$type = "AND CRM_PRODUCT.product_type_id = '$PROD_TYPE' ";
}
$LIST_CLAUSE = "CRM_PRODUCT.product_cost,CRM_PRODUCT.product_price,CRM_PRODUCT.id,CRM_PRODUCT.product_code,CRM_PRODUCT.product_name,CRM_PRODUCT.product_specification,CRM_PRODUCT.measure_id,crm_product_type.product_type_name,CRM_PRODUCT.product_band ";

$FROM_CLAUSE = " FROM CRM_PRODUCT ";

$JOIN_CLAUSE = " LEFT OUTER JOIN crm_sys_code as crm_sys_code_CRM_PRODUCT_measure_id ON  crm_sys_code_CRM_PRODUCT_measure_id.code_type='MEASURE_ID'  AND crm_sys_code_CRM_PRODUCT_measure_id.code_no=CRM_PRODUCT.measure_id 
left join crm_product_type on crm_product_type.Id = CRM_PRODUCT.product_type_id ";

$WHERE_CLAUSE = " WHERE CRM_PRODUCT.deleted = 0  $type ";
for($I = 0; $I < $cnt; $I++){
	$FIELD	= "field".$I;
	$VALUE	= "value".$I;
	$OP		= "op".$I;
	$WHERE_CLAUSE .= " AND ".$$FIELD;
	if($$OP == "is"){ // 等于
		$WHERE_CLAUSE .= " = '" . $$VALUE."' ";
	}else if($$OP == "cts"){ // 包含
		$WHERE_CLAUSE .= " like '%" . $$VALUE."%' ";
	}
}
$ENTITY = "crm_product";
if($_SESSION["LOGIN_USER_PRIV_OTHER"] != $_SESSION["LOGIN_USER_PRIV"]){
	$TEMP_LOGIN_USER_PRIV = explode(",",$_SESSION["LOGIN_USER_PRIV_OTHER"]);
	$VIEW_PRIV = getViewPrivOfModule($ENTITY, $TEMP_LOGIN_USER_PRIV);
}else{
	$VIEW_PRIV = getViewPrivOfModule($ENTITY, $_SESSION["LOGIN_USER_PRIV"]);
}

$TMP_OWNER_DEPT = ($_SESSION["LOGIN_DEPT_ID_OTHER"] == "") ? $_SESSION["LOGIN_DEPT_ID"] : ($_SESSION["LOGIN_DEPT_ID"].",".$_SESSION["LOGIN_DEPT_ID_OTHER"]);//拥有部门
//$TMP_LOGIN_DEPT_ID_JUNIOR  = ($_SESSION["LOGIN_DEPT_ID_OTHER"] != "") ? $_SESSION["LOGIN_DEPT_ID_JUNIOR"].$_SESSION["LOGIN_DEPT_ID_OTHER"] : $_SESSION["LOGIN_DEPT_ID_JUNIOR"];
$TMP_LOGIN_DEPT_ID_JUNIOR = $_SESSION["LOGIN_DEPT_ID_JUNIOR"];
if(substr($VIEW_PRIV, -1, 1) == "!"){
	$VIEW_PRIV = substr($VIEW_PRIV, 0, -1);
}
$VIEW_STR_ARR = explode("!", $VIEW_PRIV);
foreach($VIEW_STR_ARR as $tmp_VIEW_PRIV){
	$VIEW_PRIV_ARR = explode("|", $tmp_VIEW_PRIV);
	//print_r($VIEW_PRIV_ARR);
	$FINAL_VIEW_PRIV = $VIEW_PRIV_ARR[0];
	if($FINAL_VIEW_PRIV == 0){
		$WHERE_CLAUSE1 .= " 0 OR";
	}
	if($FINAL_VIEW_PRIV == 1){
		//本人拥有
		$WHERE_CLAUSE1 .= "  FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',".$ENTITY.".OWNER) OR";
	}
	if($FINAL_VIEW_PRIV == 2){
		//本部门拥有
		$TMP_REG_OWNER_DEPT = (substr($TMP_OWNER_DEPT,-1, 1) == ",") ? substr($TMP_OWNER_DEPT,0, -1) : $TMP_OWNER_DEPT;
		$WHERE_CLAUSE1 .= " CONCAT(',',".$ENTITY.".owner_dept,',') REGEXP CONCAT(',(', REPLACE('".$TMP_REG_OWNER_DEPT."', ',', '|'), '),') OR CONCAT(',','$TMP_REG_OWNER_DEPT',',') regexp ',ALL_DEPT,' OR";
	}
	if($FINAL_VIEW_PRIV == 3){
		//本部门及下属部门拥有
		$TMP_REG_DEPT_ID_JUNIOR = (substr($TMP_LOGIN_DEPT_ID_JUNIOR,-1, 1) == ",") ? substr($TMP_LOGIN_DEPT_ID_JUNIOR,0, -1) : $_SESSION["LOGIN_DEPT_ID_JUNIOR"];
		//字串为空是否存在问题?
		$WHERE_CLAUSE1 .= "  CONCAT(',',".$ENTITY.".owner_dept,',') REGEXP CONCAT(',(', REPLACE('".$TMP_REG_DEPT_ID_JUNIOR."', ',', '|'), '),') OR CONCAT(',','$TMP_REG_DEPT_ID_JUNIOR',',') regexp ',ALL_DEPT,' OR";
	}
	if($FINAL_VIEW_PRIV == 5){
		//指定部门创建(拥有)
		$OTHER_DEPT = $VIEW_PRIV_ARR[1];
		if($OTHER_DEPT != ""){//is_int(strpos($OTHER_DEPT, $_SESSION["LOGIN_DEPT_ID"])) 
			if($OTHER_DEPT != "ALL_DEPT"){
				$TMP_OTHER_DEPT = (substr($OTHER_DEPT,-1, 1) == ",") ? substr($OTHER_DEPT,0, -1) : $OTHER_DEPT;
				$WHERE_CLAUSE1 .= " CONCAT(',',".$ENTITY.".owner_dept,',') REGEXP CONCAT(',(', REPLACE('".$TMP_OTHER_DEPT."', ',', '|'), '),') OR CONCAT(',','$TMP_OTHER_DEPT',',') regexp ',ALL_DEPT,' OR";
				//$WHERE_CLAUSE1 .= " find_in_set(".$ENTITY.".CREATE_DEPT,'".$OTHER_DEPT."') OR";
			}
		}
	}
	if($FINAL_VIEW_PRIV == 6 || $FINAL_VIEW_PRIV == 1){
		//本人创建
		$WHERE_CLAUSE1 .= " ".$ENTITY.".CREATE_MAN = '".$_SESSION["LOGIN_USER_ID"]."' OR";
	}
	if($FINAL_VIEW_PRIV == 7){
		///本部门创建
		$WHERE_CLAUSE1 .= " ".$ENTITY.".CREATE_DEPT = '".$_SESSION["LOGIN_DEPT_ID"]."' OR FIND_IN_SET(".$ENTITY.".CREATE_DEPT,'".$_SESSION["LOGIN_DEPT_ID_OTHER"]."') OR";
	}
	if($FINAL_VIEW_PRIV == 8){
		///本部门及下属部门创建
		$WHERE_CLAUSE1 .= " FIND_IN_SET(".$ENTITY.".CREATE_DEPT,'".$TMP_LOGIN_DEPT_ID_JUNIOR."') OR";
	}
}
if ($WHERE_CLAUSE1 != ""){
	$WHERE_CLAUSE1 = substr($WHERE_CLAUSE1, 0, -2);
	if(strtolower($AUTH_FLAG) != "lock"){
		$WHERE_CLAUSE1 .= " OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',".$ENTITY.".share_man)";
	}
	if($EXTENSION_AUTHORITY_CLAUSE != ""){
		$WHERE_CLAUSE1 .= " OR ".$EXTENSION_AUTHORITY_CLAUSE;
	}
	
	//$WHERE_CLAUSE1 .= " OR ".$ENTITY.".CREATE_MAN = '".$_SESSION["LOGIN_USER_ID"]."' ";
	
	$WHERE_CLAUSE1 = " ( " .$WHERE_CLAUSE1. " )";
	$WHERE_CLAUSE .= " AND ".$WHERE_CLAUSE1;
}

$ORDER_CLAUSE = "";
if($ORDERFIELD != ""){
	$ORDER_CLAUSE .= " ORDER BY ".$ORDERFIELD." ".$ORDERTYPE;
}else{
    $ORDER_CLAUSE .= " ORDER BY ".$KEY_COLUMN." DESC ";
}

$query="SELECT COUNT(*) ".$FROM_CLAUSE.$JOIN_CLAUSE.$WHERE_CLAUSE;


$cursor=exequery(TD::conn(),$query);	
if($row=mysql_fetch_array($cursor)){
	$TOTAL_COUNT =  $row[0];
}
if($TOTAL_COUNT == 0){
	$TOTAL_PAGE = 1;
}else{
	$TOTAL_PAGE = intval(($TOTAL_COUNT + $LIST_PAGE_SIZE - 1) /  $LIST_PAGE_SIZE);
}

if($CUR_PAGE == "" || $CUR_PAGE == 0){
	$CUR_PAGE = 1;
}else if($CUR_PAGE > $TOTAL_PAGE){
	$CUR_PAGE = $TOTAL_PAGE;
}

$START_POS = ($CUR_PAGE-1) * $LIST_PAGE_SIZE;
$LIMIT_CLAUSE = " limit ".$START_POS. ",".$LIST_PAGE_SIZE;
for($I=0; $I<$cnt; $I++){
	$FIELD="field".$I;
	$VALUE="value".$I;
	if($$FIELD == "CRM_PRODUCT.product_name"){
		$product_name=$$VALUE;
	}else if($$FIELD == "CRM_PRODUCT.product_code"){
		$product_code=$$VALUE;
	}else if($$FIELD == "CRM_PRODUCT.product_specification"){
		$product_specification=$$VALUE;
	}else if($$FIELD == "CRM_PRODUCT_TYPE.product_type_name"){
		$product_type_name=$$VALUE;
	}else if($$FIELD == "CRM_PRODUCT_TYPE.product_cost"){
		$product_cost = $$VALUE;
	}else if($$FIELD == "CRM_PRODUCT_TYPE.product_price"){
		$product_price = $$VALUE;
	}
}

?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=CRM_CONTEXT_LANGUAGE_PATH?>/ch_us.lang.js"></script>
<script src="<?=CRM_CONTEXT_JS_PATH?>/dataview.js"></script>
<script src="<?=CRM_CONTEXT_JS_PATH?>/general.js"></script>
<script src="<?=CRM_CONTEXT_JS_PATH?>/productlist/pickupprodlist.js"></script>
<script src="<?=CRM_CONTEXT_JS_PATH?>/operation.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body style="padding:0pt 2pt;margin:0pt 2pt">

<table width="98%">
<tr><td width="87%">

<!--start search field -->
<!--end search field-->
<script>
var fields=new Array('CRM_PRODUCT.product_name','CRM_PRODUCT.product_code','CRM_PRODUCT.product_specification'); 
var ctrls=new Array('product_name','product_code','product_specification');
var ops=new Array('cts','cts','cts');
</script>
<table width = "92%">
	<tr>
		<td width = "100%">
			<table cellpadding="2px" cellspacing="2px" width="100%">
				<tr>
					<td class="search_condition_field" width="20%">
						 <?php
							printSCtrlOfText(_("产品编号"),'product_code',"$product_code");
						?>
					</td>
					<td class="search_condition_field" width="20%">
						 <?php
							printSCtrlOfText(_("产品名称"),'product_name',"$product_name");
						?>	
					</td>
				</tr>
				<tr>
					<td class="search_condition_field" width="20%">
						<?php
							printSCtrlOfText(_("产品型号"),'product_specification',"$product_specification");
						?>
					</td>
					<td class="search_condition_field" width="20%">
						<?php
							printSCtrlOfText(_("产品类别"),'product_type_name',"$product_type_name");
						?>
					</td>
				</tr>
			</table>
		</td>
		<td align = "left" valign = "bottom">
			<script>
				var product_fields=new Array('CRM_PRODUCT.product_code','CRM_PRODUCT.product_name','CRM_PRODUCT.product_specification','CRM_PRODUCT_TYPE.product_type_name'); 
				var  product_ctrls=new Array('product_code','product_name','product_specification','product_type_name');
				var  product_ops=new Array('cts','cts','cts','cts');
			</script>
			<img src="<?=CRM_CONTEXT_IMG_PATH?>/search_new.png" onclick = "gotoViewPageBySearch(product_fields, product_ctrls, product_ops)" style="margin-left:25px;" />
		</td>
	</tr>
</table>
<!-- start page bar -->
<table cellpadding="0" cellspacing="0" class="page_bar" width="400px">
<tr>
<td class="page_bar_bg" > <?=sprintf(_("第%s/%s页"), $CUR_PAGE, $TOTAL_PAGE)?><a href="javascript:gotoViewPage(1)"><img src="<?=CRM_CONTEXT_IMG_PATH?>/page_bar/first_btn.png"/></a><a href="javascript:gotoViewPage(<?=($CUR_PAGE-1)?>)"><img src="<?=CRM_CONTEXT_IMG_PATH?>/page_bar/prev_btn.png"/></a><a href="javascript:gotoViewPage(<?=($CUR_PAGE+1)?>)"><img src="<?=CRM_CONTEXT_IMG_PATH?>/page_bar/next_btn.png"/></a><a href="javascript:gotoViewPage(<?=$TOTAL_PAGE?>)"><img src="<?=CRM_CONTEXT_IMG_PATH?>/page_bar/last_btn.png"/></a> &nbsp;<?=_("转到")?>&nbsp;<?=sprintf(_("第%s页"), '<input type="text" name="jumpPage" style="width:30px;height:20px;"/>')?>&nbsp;<img src = "<?=CRM_CONTEXT_IMG_PATH?>/page_bar/go_page.gif" title="<?=_("转到")?>" onclick="gotoViewPage(document.getElementById('jumpPage').value)" height = "16"> <?=sprintf(_("共%s条"), $TOTAL_COUNT)?>
</td>
</tr>
</table>
<!-- end page bar -->

<!-- start data field-->
<div id="dataContainer" style="height:expression(document.body.clientHeight - 
85);position:absolute;border-top:1 solid #b8d1e2;overflow-y:auto;overflow-x:auto;" >
<table style="table-layout:fixed;" class="CRM_TableList1" width="105%" cellpadding="3px" cellspacing="0px" align="center">
<tr style="position:relative;top:expression(getScrollTop(window.dataContainer));" class="TableHeader">
<?
if($ORDERFIELD == "CRM_PRODUCT.product_code"){
	printListLabel1(_("产品编号"), '',1, "CRM_PRODUCT.product_code", $ORDERTYPE);
}else{
	printListLabel1(_("产品编号"), '',1, "CRM_PRODUCT.product_code");
}
if($ORDERFIELD == "CRM_PRODUCT.product_name"){
	printListLabel1(_("产品名称"), "", 1, "CRM_PRODUCT.product_name", $ORDERTYPE);
}else{
	printListLabel1(_("产品名称"), "", 1, "CRM_PRODUCT.product_name");
}
if($ORDERFIELD == "CRM_PRODUCT.product_specification"){
	printListLabel1(_("产品型号"), "", 1, "CRM_PRODUCT.product_specification", $ORDERTYPE);
}else{
	printListLabel1(_("产品型号"), "", 1, "CRM_PRODUCT.product_specification");
}

printListLabel1(_("计量单位"));
printListLabel1(_("产品类别"));
printListLabel1(_("生产厂商"));
printListLabel1(_("成本价格"));
printListLabel1(_("销售价格"));

?>
</td>
</tr>
<?
$COUNT = 0;
$query = "SELECT ".$LIST_CLAUSE.$FROM_CLAUSE.$JOIN_CLAUSE.$WHERE_CLAUSE.$ORDER_CLAUSE.$LIMIT_CLAUSE;
$cursor=exequery(TD::conn(),$query);	
while($row=mysql_fetch_object($cursor)){
	foreach($row as $key=>$value){
		$$key=$value;
	}
	$COUNT++;
    $LINE_CLASS = ($COUNT % 2 == 0)  ? "CRM_TableLine1" : "CRM_TableLine2";
	echo "<tr class='$LINE_CLASS' id=\"tr_".$id."\" class=\"TableLine\" ";
	echo "onmouseover=\"setRowPointerRtnMutli(this, 'over')\" ";
	echo "onmouseout=\"setRowPointerRtnMutli(this, 'out')\" ";
	echo "onclick=\"setRowPointerRtnMutli(this, 'click')\" ";
	//echo "ondblclick=\"setRowPointerRtnMutli(this, 'dbclick')\" ");
	echo "value=\"".$id. "\">";

	printListTextData1("product_code",			$id,	"$product_code");
	printListTextData1("product_name",			$id,	"$product_name");
	printListTextData1("product_specification", $id,	"$product_specification");
	printListTextData1("product_measure",		$id,	"$measure_id");
	printListTextData1("product_type",			$id,	"$product_type_name");
	printListTextData1("product_band",			$id,	"$product_band");
	printListTextData1("cost",			$id,	"$product_cost");
	printListTextData1("price",			$id,	"$product_price<input type='hidden' value='$product_price,$product_cost'/>");


    echo "</tr>";
}
for($COUNT = $COUNT+1; $COUNT < $LIST_PAGE_SIZE; $COUNT++){
    $LINE_CLASS = ($COUNT % 2 == 0)  ? "CRM_TableLine1" : "CRM_TableLine2";
    echo "<tr class='$LINE_CLASS'>";
	for($J =0 ; $J < $COL_SIZE; $J++){
	    printListEmptyData();
	}
    echo "</tr>";
}
?>
</table>
<input type="hidden" id="selectIds" name="selectIds"  value="">
<input type="hidden" id="selectedID" name="selectedID"  value="">
<input type="hidden" id="PROD_TYPE" name="PROD_TYPE"  value="<?=$PROD_TYPE?>">
</div>
<!-- end data field-->


</body>



