<?php
/*
 *
 *目前用于选择报价单时同时改变相应的机会
 *
 */
	include_once("general/crm/inc/header.php");
	ob_end_clean();
	switch($entity){
		case "quotation_select"://包含 机会 客户
			$query="SELECT 
				CRM_QUOTATION.opportunities_id,
				CRM_OPPORTUNITY_CRM_QUOTATION_OPPORTUNITIES_ID.opportunity_name,
				CRM_QUOTATION.account_id,
				CRM_ACCOUNT_CRM_QUOTATION_ACCOUNT_ID.account_name ,
				CRM_QUOTATION.contact_id,
				CRM_ACCOUNT_CONTACT_CRM_QUOTATION_CONTACT_ID.contact_name 
				FROM CRM_QUOTATION 
				LEFT OUTER JOIN CRM_OPPORTUNITY AS CRM_OPPORTUNITY_CRM_QUOTATION_OPPORTUNITIES_ID ON CRM_OPPORTUNITY_CRM_QUOTATION_OPPORTUNITIES_ID.id=CRM_QUOTATION.opportunities_id 
				LEFT OUTER JOIN CRM_ACCOUNT AS CRM_ACCOUNT_CRM_QUOTATION_ACCOUNT_ID ON CRM_ACCOUNT_CRM_QUOTATION_ACCOUNT_ID.id=CRM_QUOTATION.account_id 
				LEFT OUTER JOIN CRM_ACCOUNT_CONTACT AS CRM_ACCOUNT_CONTACT_CRM_QUOTATION_CONTACT_ID ON CRM_ACCOUNT_CONTACT_CRM_QUOTATION_CONTACT_ID.id=CRM_QUOTATION.contact_id 
				WHERE CRM_QUOTATION.id='$id'";
			break;
		case "opportunity_select"://包含 客户
			$query="SELECT 
				CRM_OPPORTUNITY.account_id,
				CRM_ACCOUNT_CRM_OPPORTUNITY_ACCOUNT_ID.account_name ,
				CRM_OPPORTUNITY.contact_id,
				CRM_ACCOUNT_CONTACT_CRM_OPPORTUNITY_CONTACT_ID.contact_name 
				FROM CRM_OPPORTUNITY 
				LEFT OUTER JOIN CRM_ACCOUNT AS CRM_ACCOUNT_CRM_OPPORTUNITY_ACCOUNT_ID ON CRM_ACCOUNT_CRM_OPPORTUNITY_ACCOUNT_ID.id=CRM_OPPORTUNITY.account_id 
				LEFT OUTER JOIN CRM_ACCOUNT_CONTACT AS CRM_ACCOUNT_CONTACT_CRM_OPPORTUNITY_CONTACT_ID ON CRM_ACCOUNT_CONTACT_CRM_OPPORTUNITY_CONTACT_ID.id=CRM_OPPORTUNITY.contact_id 
				WHERE CRM_OPPORTUNITY.id='$id'";
			break;
		case "order_select"://包含 客户
			$query="SELECT 
				CRM_ORDER.account_id,
				CRM_ACCOUNT_CRM_ORDER_ACCOUNT_ID.account_name,
				CRM_ORDER.order_amount  
				FROM CRM_ORDER 
				LEFT OUTER JOIN CRM_ACCOUNT AS CRM_ACCOUNT_CRM_ORDER_ACCOUNT_ID ON CRM_ACCOUNT_CRM_ORDER_ACCOUNT_ID.id=CRM_ORDER.account_id 
				WHERE  CRM_ORDER.id='$id'";
				break;
		case "purchase_order_select":
			$query="select 
				CRM_PURCHASE_ORDER.purchase_name,
				CRM_PURCHASE_ORDER.purchase_date,
				CRM_PURCHASE_ORDER.depository_id,
				CRM_DEPOSITORY.depository_name,
				CRM_PURCHASE_ORDER.charge_man,
				USER.USER_NAME as charge_man_name 
				FROM CRM_PURCHASE_ORDER 
				LEFT JOIN CRM_DEPOSITORY on CRM_DEPOSITORY.id = CRM_PURCHASE_ORDER.depository_id 
				left join USER ON USER.USER_ID = CRM_PURCHASE_ORDER.charge_man
				where CRM_PURCHASE_ORDER.id='$id'";
			break;
		default:
			$id="";
			break;
	}
	$return_str="ok||||";
	if($id!="" && $entity!="purchase_order_select"){
		$cursor=exequery(TD::conn(),$query);
		if($row=mysql_fetch_array($cursor)){
			$opportunities_id=$row['opportunities_id'];
			if($opportunities_id=="0"){
				$opportunities_id="";
			}
			$opportunity_name=$row['opportunity_name'];
			$account_id=$row['account_id'];
			if($account_id=="0"){
				$account_id="";
			}
			$account_name=$row['account_name'];
			$contact_id=$row['contact_id'];
			if($contact_id=="0"){
				$contact_id="";
			}
			$order_amount = $row["order_amount"];
			$contact_name=$row['contact_name'];
			if($entity == "order_select"){
				$return_str="ok|".$account_id."|".$account_name."|".$order_amount."|";		
			}else{
				$return_str="ok|".$account_id."|".$account_name."|".$contact_id."|".$contact_name."|".$opportunities_id."|".$opportunity_name."|";
			}
		}
	}else if($id!="" && $entity=="purchase_order_select"){
		$cursor=exequery(TD::conn(),$query);
		if($row=mysql_fetch_array($cursor)){
			$depository_id=$row['depository_id'];
			if($depository_id==0){
				$depository_id="";
			}
			$depository_name=$row['depository_name'];
			$storage_date=$row['purchase_date'];
			if(strpos($storage_date,"0000-00-00")===0 || strpos($storage_date,"00:00:00")===0 ){
				$storage_date="";
			}
			$charge_man=$row['charge_man'];
			$charge_man_name=$row['charge_man_name'];
			$purchase_order_id=$parent_purchase_order_id;
			$purchase_name=$row['purchase_name'];
			$return_str="ok|".$depository_id."|".$depository_name."|".$storage_date."|".$charge_man."|".$charge_man_name."|"."null|";
		}
	}
	echo $return_str;
?>