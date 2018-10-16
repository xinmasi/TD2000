<?php
/*
 *
 *目前用于选择机会或报价单时获得相应的产品信息
 *
 */
	include_once("general/crm/inc/header.php");
	ob_end_clean();
	switch($entity){
		case "opportunity_select":
			$table="CRM_OPPORTUNITY_PRODUCTS_LIST";
			break;
		case "quotation_select":
			$table="CRM_QUOTATION_PRODUCTS_LIST";
			break;
		case "order_select":
			$table="CRM_ORDER_PRODUCTS_LIST";
			break;
		case "purchase_order_select":
			$table="CRM_PURCHASE_ORDER_PRODUCTS_LIST";
			break;
		default:
			$id="";
			exit;
	}
	echo "pList.deleteAll();";
	echo "pList.addRow();";
	if($id!=""){
		$list_query="SELECT 
				".$table.".number,
				".$table.".product_id,
				".$table.".qty,
				".$table.".price,
				".$table.".total,
				CRM_PRODUCT_".$table.".product_code,
				CRM_PRODUCT_".$table.".product_name,
				CRM_PRODUCT_".$table.".product_code,
				CRM_PRODUCT_".$table.".product_specification,
				CRM_SYS_CODE_".$table."_MEASURE_ID.code_name AS measure_id
			FROM ".$table." 
			LEFT OUTER JOIN CRM_PRODUCT AS CRM_PRODUCT_".$table." 
				ON CRM_PRODUCT_".$table.".id=".$table.".product_id  
			LEFT OUTER JOIN CRM_SYS_CODE AS CRM_SYS_CODE_".$table."_MEASURE_ID 
				ON CRM_SYS_CODE_".$table."_MEASURE_ID.code_type='MEASURE_ID' 
				AND CRM_SYS_CODE_".$table."_MEASURE_ID.code_no=CRM_PRODUCT_".$table.".measure_id 
			WHERE ".$table.".main_id='$id' AND ".$table.".deleted=0 ORDER BY ".$table.".number ASC";
			$cursor=exequery(TD::conn(),$list_query);
			$number=0;
			echo "document.getElementById('selectedIds').value='';\n";
			echo "document.getElementById('selectedRowIds').value='';\n";
			while($row=mysql_fetch_object($cursor)){
				$number++;
				foreach($row as $key=>$value){
					if($value==="0" || $value==="0.00"){
						$value="";
					}
					$$key=$value;
				}
				echo "pList.addRow();";
				echo "pList.fillDatas($number, {'product_id':'$product_id','product_code': '$product_code','product_name': '$product_name','product_specification':'$product_specification','product_measure':'$measure_id','qty':'$qty','price':'$price'});\n";
				echo "document.getElementById('selectedIds').value+=".$product_id."+',';\n";
				echo "document.getElementById('selectedRowIds').value+=".$number."+',';\n";
			}
	
	}
 ?>