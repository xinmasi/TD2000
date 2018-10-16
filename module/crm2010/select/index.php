<?php
include_once("general/crm/inc/header.php");
include_once("inc/utility_all.php");
include_once("general/crm/utils/dataview/dataview.interface.php");
include_once("general/crm/utils/search/search.interface.php");
	
	$entity_array=array(
		"account_select"=>_("客户"),
		"contact_select"=>_("客户联系人"),
		"opportunity_select"=>_("机会"),
		"order_select"=>_("订单"),
		"supplier_select"=>_("供应商"),
		"supplier_contact_select"=>_("供应商联系人"),
		"quotation_select"=>_("报价单"),
		"depository_select"=>_("仓库"),
		"product_type_select"=>_("产品类别"),
		"purchase_order_select"=>_("采购订单")
	);
	$query="SELECT field_name FROM crm_sys_uv_field WHERE uv_id = (SELECT id FROM crm_sys_uv WHERE entity='$entity') ORDER BY field_no";
	$cursor=exequery(TD::conn(),$query);
	while($row=mysql_fetch_object($cursor)){
		$field_name_arr[]=$row->field_name;
	}
	
	if($field_name_arr==""){
		Message(_("提示"),_("创建实体数据失败"));
		exit;
	}

	$row_num=0;
	if($field_name_arr!=""){
		$main_table="";
		$query_select="";
		$query_join="";
		$page_fields="";//页面变量
		$len=count($field_name_arr);
		for($i=0;$i<$len;$i++){
			$field_name_str_arr=explode(":",$field_name_arr[$i]);
			$main_table=$field_name_str_arr[0];//主表
			$table_header.=$field_name_str_arr[6].":";//表头
			if($field_name_str_arr[2]=="F"&&$field_name_str_arr[3]!=""&&$field_name_str_arr[4]!=""&&$field_name_str_arr[5]!=""){
				//join
				$join_table=$field_name_str_arr[3];
				$join_table_alias=$field_name_str_arr[3]."_".$field_name_str_arr[0]."_".$field_name_str_arr[5];
				$join_on=$join_table_alias.".".$field_name_str_arr[4]."=".$field_name_str_arr[0].".".$field_name_str_arr[1];
				$join_and="";
				$query_join.=" LEFT OUTER JOIN ".$join_table." AS ".$join_table_alias." ON ".$join_on;
				//and
				if($join_and!=""){
					$query_join.=" AND ".$join_and;
				}
				//select
				$query_select.=",".$join_table_alias.".".$field_name_str_arr[5];
				//$page_fields.=$field_name_str_arr[5].":";
			}else{
				$query_select.=",".$field_name_str_arr[0].".".$field_name_str_arr[1];
				//$page_fields.=$field_name_str_arr[1].":";
			}
			$row_num++;
		}
	}
	if($query_select!=""){
		$query="SELECT ".$main_table.".id".$query_select;
		$query.=" FROM ".$main_table;
		$query_join.=" LEFT OUTER JOIN USER AS USER_CRM_ACCOUNT_LOGIN_USER_ID ON USER_CRM_ACCOUNT_LOGIN_USER_ID.user_id=$main_table.create_man 
				LEFT OUTER JOIN DEPARTMENT AS DEPARTMENT_CRM_ACCOUNT_DEPT_ID ON DEPARTMENT_CRM_ACCOUNT_DEPT_ID.dept_id=USER_CRM_ACCOUNT_LOGIN_USER_ID.dept_id";
		$query.=$query_join;
		$query_count="SELECT COUNT(*)";//统计总记录数
		$query_count.=" FROM ".$main_table;
		$query_count.=$query_join;
	}
	if($row_num!=0){
		$query_where=" WHERE ".$main_table.".deleted = 0 ";
		if($entity!="product_type_select"){
			$query_where.=" AND ('".$_SESSION["LOGIN_USER_ID"]."' = 'admin' OR $main_table.create_man='".$_SESSION["LOGIN_USER_ID"]."' OR FIND_IN_SET('".$_SESSION["LOGIN_DEPT_ID"]."',$main_table.to_id) OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',$main_table.copy_to_id) 
	OR FIND_IN_SET('".$_SESSION["LOGIN_USER_PRIV"]."',$main_table.priv_id) OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',DEPARTMENT_CRM_ACCOUNT_DEPT_ID.manager) OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',DEPARTMENT_CRM_ACCOUNT_DEPT_ID.leader1) OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',DEPARTMENT_CRM_ACCOUNT_DEPT_ID.leader2))";
		}
		if($where_cnt!=0 || $where_cnt!=""){
			for($i=0;$i<$where_cnt;$i++){
				$field_str="where_field".$i;
				$value_str="where_value".$i;;
				$where_field=$$field_str;
				$where_value=$$value_str;
				$query_where.="AND $main_table.$where_field='".$where_value."' ";
			}
		}
		for($I = 0; $I < $cnt; $I++){
			$FIELD	= "field".$I;
			$VALUE	= "value".$I;
			$OP		= "op".$I;
			$query_where .= " AND ".$$FIELD;
			if($$OP == "is"){ // 等于
				$query_where .= " = '" . $$VALUE."' ";
			}else if($$OP == "cts"){ // 包含
				$query_where .= " like '%" . $$VALUE."%' ";
			}
		}
		$query_count.=$query_where;
		$cursor=exequery(TD::conn(),$query_count);	
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
		$query_limit = " limit ".$START_POS. ",".$LIST_PAGE_SIZE;
		
		?>
		<style>
			td{white-space: nowrap;}
		</style>
		<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
		<script src="<?=CRM_CONTEXT_JS_PATH?>/dataview.js"></script>
		<script src="<?=CRM_CONTEXT_JS_PATH?>/operation.js"></script>
		<script src="<?=CRM_CONTEXT_LANGUAGE_PATH?>/general.js"></script>
		<script src="/module/DatePicker/WdatePicker.js"></script>

		<div style="margin:5px;"><img src='<?=CRM_CONTEXT_IMG_PATH?>/module_icon/small/search.png' align="absMiddle" width=32 height=32/><span class="crm_big20_bold"><?=_("选择")?><?=$entity_array[$entity]?></span></div>
		<table width="98%"  align="center">
		<tr><td width="100%">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" >
		<tr>
		<td><img src="<?=CRM_CONTEXT_IMG_PATH?>/search/top_left_conner.png" /></td>
		<td class="search_top_border" width="100%"></td>
		<td><img src="<?=CRM_CONTEXT_IMG_PATH?>/search/top_right_conner.png" /></td>
		</tr>
		<tr>
		<td class="search_left_border"></td>
		<td>

		<!--start search field -->
		<?
			for($I=0; $I<$cnt; $I++){
				$FIELD="field".$I;
				$VALUE="value".$I;
				if($$FIELD == "CRM_ACCOUNT.account_name"){
					$ACCOUNT_NAME=$$VALUE;
				}else if($$FIELD == "CRM_ACCOUNT.account_code"){
					$ACCOUNT_CODE=$$VALUE;
				}else if($$FIELD == "CRM_ACCOUNT.account_mobile"){
					$ACCOUNT_MOBILE=$$VALUE;
				}else if($$FIELD == "CRM_ACCOUNT.account_email"){
					$ACCOUNT_EMAIL=$$VALUE;
				}else if($$FIELD == "CRM_ACCOUNT_CONTACT.contact_name"){
					$CONTACT_NAME=$$VALUE;
				}else if($$FIELD == "CRM_ACCOUNT_CONTACT.contact_certificate_code"){
					$CONTACT_CERTIFICATE_CODE=$$VALUE;
				}else if($$FIELD == "CRM_ACCOUNT_CONTACT.contact_birthday"){
					$CONTACT_BIRTHDAY=$$VALUE;
				}else if($$FIELD == "CRM_ACCOUNT_CONTACT.contact_email"){
					$CONTACT_EMAIL=$$VALUE;
				}else if($$FIELD == "CRM_OPPORTUNITY.opportunity_name"){
					$OPPORTUNITY_NAME=$$VALUE;
				}else if($$FIELD == "CRM_ACCOUNT_CONTACT.opportunity_principal"){
					$OPPORTUNITY_PRINCIPAL=$$VALUE;
				}else if($$FIELD == "USER_CRM_OPPORTUNITY_user_name.USER_NAME"){
					$SHOW_OPPORTUNITY_PRINCIPAL=$$VALUE;
				}else if($$FIELD == "CRM_ORDER.order_code"){
					$ORDER_CODE=$$VALUE;
				}else if($$FIELD == "CRM_ORDER.order_name"){
					$ORDER_NAME=$$VALUE;
				}else if($$FIELD == "CRM_ORDER.order_sign_date"){
					$ORDER_SIGN_DATE=$$VALUE;
				}else if($$FIELD == "CRM_QUOTATION.quotation_code"){
					$QUOTATION_CODE=$$VALUE;
				}else if($$FIELD == "CRM_QUOTATION.quotation_title"){
					$QUOTATION_TITLE=$$VALUE;
				}else if($$FIELD == "CRM_QUOTATION.quotation_date"){
					$QUOTATION_DATE=$$VALUE;
				}else if($$FIELD == "CRM_QUOTATION.quotation_period"){
					$QUOTATION_PERIOD=$$VALUE;
				}else if($$FIELD == "CRM_DEPOSITORY.depository_code"){
					$DEPOSITORY_CODE=$$VALUE;
				}else if($$FIELD == "CRM_DEPOSITORY.depository_name"){
					$DEPOSITORY_NAME=$$VALUE;
				}else if($$FIELD == "CRM_DEPOSITORY.dept_id"){
					$DEPT_ID=$$VALUE;
				}else if($$FIELD == "DEPARTMENT_CRM_DEPOSITORY_dept_name.dept_name"){
					$SHOW_DEPT_ID=$$VALUE;
				}else if($$FIELD == "CRM_SUPPLIER.supplier_code"){
					$SUPPLIER_CODE=$$VALUE;
				}else if($$FIELD == "CRM_SUPPLIER.supplier_name"){
					$SUPPLIER_NAME=$$VALUE;
				}else if($$FIELD == "CRM_SUPPLIER.supplier_phone"){
					$SUPPLIER_PHONE=$$VALUE;
				}else if($$FIELD == "CRM_SUPPLIER.supplier_email"){
					$SUPPLIER_EMAIL=$$VALUE;
				}else if($$FIELD == "CRM_PRODUCT_TYPE.product_type_name"){
					$PRODUCT_TYPE_NAME=$$VALUE;
				}else if($$FIELD == "CRM_PRODUCT_TYPE.product_type_code"){
					$PRODUCT_TYPE_CODE=$$VALUE;
				}else if($$FIELD == "CRM_PURCHASE_ORDER.purchase_code"){
					$PURCHASE_CODE=$$VALUE;
				}else if($$FIELD == "CRM_PURCHASE_ORDER.purchase_name"){
					$PURCHASE_NAME=$$VALUE;
				}else if($$FIELD == "CRM_SUPPLIER.supplier_name"){
					$SUPPLIER_NAME=$$VALUE;
				}else if($$FIELD == "CRM_PURCHASE_ORDER.purchase_date"){
					$PURCHASE_DATE=$$VALUE;
				}else if($$FIELD == "CRM_SUPPLIER_CONTACT.supplier_contact_name"){
					$SUPPLIER_CONTACT_NAME=$$VALUE;
				}else if($$FIELD == "CRM_SUPPLIER_CRM_SUPPLIER_CONTACT_supplier_name.supplier_name"){
					$SUPPLIER_NAME=$$VALUE;
				}else if($$FIELD == "CRM_SUPPLIER_CONTACT.supplier_contact_mobile"){
					$SUPPLIER_CONTACT_MOBILE=$$VALUE;
				}else if($$FIELD == "CRM_SUPPLIER_CONTACT.supplier_contact_email"){
					$SUPPLIER_CONTACT_EMAIL=$$VALUE;
				}

			}
		?>
		<form name="form1" action="#" method="post">
		<?
			if($entity=="account_select"){//<!-- START 客户查询区 -->
		?>
			<table width = "100%">
				<tr>
					<td width = "100%">
						<table cellpadding="2px" cellspacing="2px" width="100%">
							<tr>
								<td class="search_condition_field" width="20%">
									 <?php
										printSCtrlOfText(_("客户名称："),'account_name',"$ACCOUNT_NAME");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText(_("客户编码："),'account_code',"$ACCOUNT_CODE");
									?>
								</td>
							</tr>
							<tr>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText(_("客户手机："),'account_mobile',"$ACCOUNT_MOBILE");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText('E-MAIL：','account_email',"$ACCOUNT_EMAIL");
									?>
								</td>
							</tr>
						</table>
					</td>
					<td align = "left" valign = "bottom">
						<script>
							var fields=new Array('CRM_ACCOUNT.account_name','CRM_ACCOUNT.account_code','CRM_ACCOUNT.account_mobile','CRM_ACCOUNT.account_email'); 
							var ctrls=new Array('account_name','account_code','account_mobile','account_email');
							var ops=new Array('cts','cts','cts','cts');
						</script>
						<img src="<?=CRM_CONTEXT_IMG_PATH?>/search/search_new.png" onclick = "gotoViewPageBySearch(fields, ctrls, ops)" style="margin-left:25px;" />
					</td>
				</tr>
			</table>
		<?
			}//<!-- END 客户查询区 -->
			if($entity=="contact_select"){//<!-- START 联系人查询区 -->
		?>
			<table width = "100%">
				<tr>
					<td width = "100%">
						<table cellpadding="2px" cellspacing="2px" width="100%">
							<tr>
								<td class="search_condition_field" width="20%">
									 <?php
										printSCtrlOfText(_("联系人姓名:"),'contact_name',"$CONTACT_NAME");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									 <?php
										printSCtrlOfText(_("证件号码:"),'contact_certificate_code',"$CONTACT_CERTIFICATE_CODE");
									?>	
								</td>
							</tr>
							<tr>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfDate(_("出生日期："),'contact_birthday',"$CONTACT_BIRTHDAY");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText('E-MAIL：','contact_email',"$CONTACT_EMAIL");
									?>
								</td>
							</tr>
						</table>
					</td>
					<td align = "left" valign = "bottom">
						<script>
							var contact_fields=new Array('CRM_ACCOUNT_CONTACT.contact_name','CRM_ACCOUNT_CONTACT.contact_certificate_code','CRM_ACCOUNT_CONTACT.contact_birthday','CRM_ACCOUNT_CONTACT.contact_email'); 
							var contact_ctrls=new Array('contact_name','contact_certificate_code','contact_birthday','contact_email');
							var contact_ops=new Array('cts','cts','is','cts');
						</script>
						<img src="<?=CRM_CONTEXT_IMG_PATH?>/search/search_new.png" onclick = "gotoViewPageBySearch(contact_fields, contact_ctrls, contact_ops)" style="margin-left:25px;" />
					</td>
				</tr>
			</table>
		<?
			}//<!-- END 联系人查询区 -->
			if($entity=="opportunity_select"){//<!-- START 机会查询区 -->
		?>
			<table width = "100%">
				<tr>
					<td width = "100%">
						<table cellpadding="2px" cellspacing="2px" width="100%">
							<tr>
								<td class="search_condition_field" width="20%">
									 <?php
										printSCtrlOfText(_("商机名称："),'opportunity_name',"$OPPORTUNITY_NAME");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfSingleUser(_("负责人:"),'opportunity_principal',$OPPORTUNITY_PRINCIPAL,$SHOW_OPPORTUNITY_PRINCIPAL);
									?>
								</td>
							</tr>
						</table>
					</td>
					<td align = "left" valign = "bottom">
						<script>
							var opportuniy_fields=new Array('CRM_OPPORTUNITY.opportunity_name','CRM_OPPORTUNITY.opportunity_principal','USER_CRM_OPPORTUNITY_user_name.USER_NAME'); 
							var opportuniy_ctrls=new Array('opportunity_name','hd_opportunity_principal','opportunity_principal');
							var opportuniy_ops=new Array('cts','is','is');
						</script>
						<img src="<?=CRM_CONTEXT_IMG_PATH?>/search/search_new.png" onclick = "gotoViewPageBySearch(opportuniy_fields, opportuniy_ctrls, opportuniy_ops)" style="margin-left:25px;" />
					</td>
				</tr>
			</table>
		<?
			}//<!-- END 机会查询区 -->
			if($entity=="order_select"){//<!-- START 订单查询区 -->
		?>
			<table width = "100%">
				<tr>
					<td width = "100%">
						<table cellpadding="2px" cellspacing="2px" width="100%">
							<tr>
								<td class="search_condition_field" width="20%">
									 <?php
										printSCtrlOfText(_("订单编号："),'order_code',"$ORDER_CODE");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText(_("订单名称："),'order_name',"$ORDER_NAME");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfDate(_("签订日期："),'order_sign_date',"$ORDER_SIGN_DATE");
									?>
								</td>
							</tr>
						</table>
					</td>
					<td align = "left" valign = "bottom">
						<script>
							var order_fields=new Array('CRM_ORDER.order_code','CRM_ORDER.order_name','CRM_ORDER.order_sign_date'); 
							var order_ctrls=new Array('order_code','order_name','order_sign_date');
							var order_ops=new Array('cts','cts','is');
						</script>
						<img src="<?=CRM_CONTEXT_IMG_PATH?>/search/search_new.png" onclick = "gotoViewPageBySearch(order_fields, order_ctrls, order_ops)" style="margin-left:25px;" />
					</td>
				</tr>
			</table>
		<?
			}//<!-- END 订单查询区 -->
			if($entity=="quotation_select"){//<!-- START 报价单查询区 -->
		?>
			<table width = "100%">
				<tr>
					<td width = "100%">
						<table cellpadding="2px" cellspacing="2px" width="100%">
							<tr>
								<td class="search_condition_field" width="20%">
									 <?php
										printSCtrlOfText(_("报价单编号："),'quotation_code',"$QUOTATION_CODE");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText(_("报价单标题："),'quotation_title',"$QUOTATION_TITLE");
									?>
								</td>
							</tr>
							<tr>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfDate(_("报价日期："),'quotation_date',"$QUOTATION_DATE");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfDate(_("报价有效期："),'quotation_period',"$QUOTATION_PERIOD");
									?>
								</td>
							</tr>
						</table>
					</td>
					<td align = "left" valign = "bottom">
						<script>
							var quotation_fields=new Array('CRM_QUOTATION.quotation_code','CRM_QUOTATION.quotation_title','CRM_QUOTATION.quotation_date','CRM_QUOTATION.quotation_period'); 
							var quotation_ctrls=new Array('quotation_code','quotation_title','quotation_date','quotation_period');
							var quotation_ops=new Array('cts','cts','is','is');
						</script>
						<img src="<?=CRM_CONTEXT_IMG_PATH?>/search/search_new.png" onclick = "gotoViewPageBySearch(quotation_fields, quotation_ctrls, quotation_ops)" style="margin-left:25px;" />
					</td>
				</tr>
			</table>
		<?
			}//<!-- END 报价单查询区 -->
			if($entity=="depository_select"){//<!-- START 仓库查询区 -->
		?>
			<table width = "100%">
				<tr>
					<td width = "100%">
						<table cellpadding="2px" cellspacing="2px" width="100%">
							<tr>
								<td class="search_condition_field" width="20%">
									 <?php
										printSCtrlOfText(_("仓库编号："),'depository_code',"$DEPOSITORY_CODE");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText(_("仓库名称："),'depository_name',"$DEPOSITORY_NAME");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfSingleDept(_("所属部门："),'dept_id',"$DEPT_ID","$SHOW_DEPT_ID");
									?>
								</td>
							</tr>
						</table>
					</td>
					<td align = "left" valign = "bottom">
						<script>
							var depository_fields=new Array('CRM_DEPOSITORY.depository_code','CRM_DEPOSITORY.depository_name','CRM_DEPOSITORY.dept_id','DEPARTMENT_CRM_DEPOSITORY_dept_name.dept_name'); 
							var depository_ctrls=new Array('depository_code','depository_name','hd_dept_id','dept_id');
							var depository_ops=new Array('cts','cts','is','is');
						</script>
						<img src="<?=CRM_CONTEXT_IMG_PATH?>/search/search_new.png" onclick = "gotoViewPageBySearch(depository_fields, depository_ctrls, depository_ops)" style="margin-left:25px;" />
					</td>
				</tr>
			</table>
		<?
			}//<!-- END 仓库查询区 -->
			if($entity=="supplier_select"){//<!-- START 供应商查询区 -->
		?>
			<table width = "100%">
				<tr>
					<td width = "100%">
						<table cellpadding="2px" cellspacing="2px" width="100%">
							<tr>
								<td class="search_condition_field" width="20%">
									 <?php
										printSCtrlOfText(_("供应商编号："),'supplier_code',"$SUPPLIER_CODE");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText(_("供应商名称："),'supplier_name',"$SUPPLIER_NAME");
									?>
								</td>
							</tr>
							<tr>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText(_("供应商电话："),'supplier_phone',"$SUPPLIER_PHONE");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText('E-MAIL：','supplier_email',"$SUPPLIER_EMAIL");
									?>
								</td>
							</tr>
						</table>
					</td>
					<td align = "left" valign = "bottom">
						<script>
							var supplier_fields=new Array('CRM_SUPPLIER.supplier_code','CRM_SUPPLIER.supplier_name','CRM_SUPPLIER.supplier_phone','CRM_SUPPLIER.supplier_email'); 
							var supplier_ctrls=new Array('supplier_code','supplier_name','supplier_phone','supplier_email');
							var supplier_ops=new Array('cts','cts','cts','cts');
						</script>
						<img src="<?=CRM_CONTEXT_IMG_PATH?>/search/search_new.png" onclick = "gotoViewPageBySearch(supplier_fields, supplier_ctrls, supplier_ops)" style="margin-left:25px;" />
					</td>
				</tr>
			</table>
		<?
			}//<!-- END 供应商查询区 -->
			if($entity=="product_type_select"){//<!-- START 产品类别查询区 -->
		?>
			<table width = "100%">
				<tr>
					<td width = "100%">
						<table cellpadding="2px" cellspacing="2px" width="100%">
							<tr>
								<td class="search_condition_field" width="20%">
									 <?php
										printSCtrlOfText(_("类别名称："),'product_type_name',"$PRODUCT_TYPE_NAME");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText(_("类别编号："),'product_type_code',"$PRODUCT_TYPE_CODE");
									?>
								</td>
							</tr>
						</table>
					</td>
					<td align = "left" valign = "bottom">
						<script>
							var crm_product_type_fields=new Array('CRM_PRODUCT_TYPE.product_type_name','CRM_PRODUCT_TYPE.product_type_code'); 
							var crm_product_type_ctrls=new Array('product_type_name','product_type_code');
							var crm_product_type_ops=new Array('cts','cts');
						</script>
						<img src="<?=CRM_CONTEXT_IMG_PATH?>/search/search_new.png" onclick = "gotoViewPageBySearch(crm_product_type_fields, crm_product_type_ctrls, crm_product_type_ops)" style="margin-left:25px;" />
					</td>
				</tr>
			</table>
		<?
			}//<!-- END 产品类别查询区 -->
			if($entity=="purchase_order_select"){//<!-- START 采购订单查询区 -->
		?>
			<table width = "100%">
				<tr>
					<td width = "100%">
						<table cellpadding="2px" cellspacing="2px" width="100%">
							<tr>
								<td class="search_condition_field" width="20%">
									 <?php
										printSCtrlOfText(_("采购单号："),'purchase_code',"$PURCHASE_CODE");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText(_("采购主题"),'purchase_name',"$PURCHASE_NAME");
									?>
								</td>
							</tr>
							<tr>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfText(_("所属供应商"),'supplier_name',"$SUPPLIER_NAME");
									?>
								</td>
								<td class="search_condition_field" width="20%">
									<?php
										printSCtrlOfDate(_("采购日期"),'purchase_date',"$purchase_date");
									?>
								</td>
							</tr>
						</table>
					</td>
					<td align = "left" valign = "bottom">
						<script>
							var purchase_order_fields=new Array('CRM_PURCHASE_ORDER.purchase_code','CRM_PURCHASE_ORDER.purchase_name','CRM_SUPPLIER.supplier_name','CRM_PURCHASE_ORDER.purchase_date'); 
							var purchase_order_ctrls=new Array('purchase_code','purchase_name','supplier_name','purchase_date');
							var purchase_order_ops=new Array('cts','cts','cts','is');
						</script>
						<img src="<?=CRM_CONTEXT_IMG_PATH?>/search/search_new.png" onclick = "gotoViewPageBySearch(purchase_order_fields, purchase_order_ctrls, purchase_order_ops)" style="margin-left:25px;" />
					</td>
				</tr>
			</table>
		<?php 
			}//<!-- END 采购订单查询区 -->
			if($entity=="supplier_contact_select"){ //<!-- START 供应商联系人查询区 -->
		?>
				<table width = "100%">
					<tr>
						<td width = "100%">
							<table cellpadding="2px" cellspacing="2px" width="100%">
								<tr>
									<td class="search_condition_field" width="20%">
										 <?php
											printSCtrlOfText(_("联系人姓名："),'supplier_contact_name',"$SUPPLIER_CONTACT_NAME");
										?>
									</td>
									<td class="search_condition_field" width="20%">
										<?php
											printSCtrlOfText(_("供应商名称："),'supplier_name',"$SUPPLIER_NAME");
										?>
									</td>
								</tr>
								<tr>
									<td class="search_condition_field" width="20%">
										<?php
											printSCtrlOfText(_("手机号码："),'supplier_contact_mobile',"$SUPPLIER_CONTACT_MOBILE");
										?>
									</td>
									<td class="search_condition_field" width="20%">
										<?php
											printSCtrlOfText('E-MAIL：','supplier_contact_email',"$SUPPLIER_CONTACT_EMAIL");
										?>
									</td>
								</tr>
							</table>
						</td>
						<td align = "left" valign = "bottom">
							<script>
								var supplier_contact_fields=new Array('CRM_SUPPLIER_CONTACT.supplier_contact_name','CRM_SUPPLIER_CRM_SUPPLIER_CONTACT_supplier_name.supplier_name','CRM_SUPPLIER_CONTACT.supplier_contact_mobile','CRM_SUPPLIER_CONTACT.supplier_contact_email'); 
								var supplier_contact_ctrls=new Array('supplier_contact_name','supplier_name','supplier_contact_mobile','supplier_contact_email');
								var supplier_contact_ops=new Array('cts','cts','cts','cts');
							</script>
							<img src="<?=CRM_CONTEXT_IMG_PATH?>/search/search_new.png" onclick = "gotoViewPageBySearch(supplier_contact_fields, supplier_contact_ctrls, supplier_contact_ops)" style="margin-left:25px;" />
						</td>
					</tr>
				</table>
		<?	
			} //<!-- END 供应商联系人查询区 -->
		?>
		</form>
		<!--end search field-->
		</td>
		<td class="search_right_border"></td>
		</tr>
		<tr>
		<td><img src="<?=CRM_CONTEXT_IMG_PATH?>/search/bottom_left_conner.png" /></td>
		<td class="search_bottom_border" width="100%"></td>
		<td><img src="<?=CRM_CONTEXT_IMG_PATH?>/search/bottom_right_conner.png" /></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>



		<!-- start page bar -->
		<div style="float:right;margin-top:3px;padding-top:5px;">
			<table cellpadding="0" cellspacing="0" class="page_bar" width="330px">
				<tr >
					<td class="page_bar_bg"> <?=sprintf(_("第%s/%s页"), $CUR_PAGE, $TOTAL_PAGE)?><a href="javascript:gotoViewPage(1)">
					<img src="<?=CRM_CONTEXT_IMG_PATH?>/page_bar/first_btn.png"/></a>
					<a href="javascript:gotoViewPage(<?=($CUR_PAGE-1)?>)"><img src="<?=CRM_CONTEXT_IMG_PATH?>/page_bar/prev_btn.png"/></a>
					<a href="javascript:gotoViewPage(<?=($CUR_PAGE+1)?>)"><img src="<?=CRM_CONTEXT_IMG_PATH?>/page_bar/next_btn.png"/></a>
					<a href="javascript:gotoViewPage(<?=$TOTAL_PAGE?>)"><img src="<?=CRM_CONTEXT_IMG_PATH?>/page_bar/last_btn.png"/></a>
					<?=sprintf(_("共%s条"), $TOTAL_COUNT)?>&nbsp;<?=_("转到")?>&nbsp;<?=sprintf(_("第%s页"), '&nbsp;<input type="text" name="jumpPage" style="width:30px;height:20px;" class="efViewTextBox" onKeyDown="jumpPage(this,event,'.$TOTAL_PAGE.');"/>&nbsp;')?>
					<img src="<?=CRM_CONTEXT_IMG_PATH?>/page_bar/go_page.gif" onClick="gotoViewPage(document.getElementById('jumpPage').value)" title="<?=_("跳转")?>"/>
					</td>
				</tr>
			</table>
		</div>
		<div style="float:left;height:35px;">
		</div>
		<!-- end page bar -->
		<!-- start data field-->
		<table cellpadding="0" cellspacing="0" width="98%" align="center">
		<tr>
		<td><img src="<?=CRM_CONTEXT_IMG_PATH?>/list/top_left_conner.png"/></td>
		<td class="list_top_border" width="100%"></td>
		<td><img src="<?=CRM_CONTEXT_IMG_PATH?>/list/top_right_conner.png"/></td>
		</tr>
		<tr>
		<td class="list_left_border"></td>
		<td>
		<!--start data list-->
		<div style="overflow-x:auto; overflow-y:auto;height:<?=$DIV_HEIGHT-25?>px; width:100%);">
		<table class="CRM_TableList" width="100%" cellpadding="1px" cellspacing="1px">
		<tr class="CRM_TableHeader">
		<?
		$header_arr=explode(":",$table_header);
		$header_len=count($header_arr)-1;
		for($i=0;$i<$header_len;$i++){
			printListLabel($header_arr[$i]);
		}
		?>
		</tr>
		<script>
			var select_obj="";
		</script>
		<?
		
		$query.=$query_where." ORDER BY $main_table.id DESC".$query_limit;
		//echo $query;
		$cursor=exequery(TD::conn(),$query);
		$COUNT = 0;
		while($row=mysql_fetch_object($cursor)){
			$COUNT++;
			$LINE_CLASS = ($COUNT % 2 == 0)  ? "CRM_TableLine1" : "CRM_TableLine2";
			echo "<tr class='$LINE_CLASS' id='$COUNT' value='$COUNT' onclick='select($COUNT,\"click\");' ondblclick='select($COUNT,\"dbclick\")' onmousemove='changestyle($COUNT);' height='25px'>";
			$right_count=0;
			foreach($row as $key=>$value){
				if(strpos($value,"0000-00-00")===0 || strpos($value,"00:00:00")===0 || $value=="" ){
							$value="";
				}
				$title_value = $value;
				if(strlen($value)>20){
					$value=csubstr($value,0,18)."...";
				}
				echo "<td name='$key' value='$value' title=\"".td_htmlspecialchars($title_value)."\"";
				if($key=="id"){
					echo "style='display:none;'";
				}
				echo ">";
				for($i=0;$i<$data_cnt;$i++){
					$selected_field="selected_field".$i;
					$selected_value="selected_value".$i;
					$page_selected_field=$$selected_field;
					$page_selected_value=iconv("UTF-8",MYOA_CHARSET,$$selected_value);
					if($key==$page_selected_field && $value==$page_selected_value){
						$right_count++;
					}
				}
				if($right_count==$data_cnt){
				?>
					<script>
						var select_obj=document.getElementById("<?=$COUNT?>");
						var	select_org_class="<?=$LINE_CLASS?>";
						select_obj.className="select_tr";
					</script>
				<?
				}
				echo "$value</td>";
			}
			echo "</tr>\n";
		}
		$COUNT--;
		for(; $COUNT < $LIST_PAGE_SIZE-1; $COUNT++){
			$LINE_CLASS = ($COUNT % 2 == 0)  ? "CRM_TableLine1" : "CRM_TableLine2";
			echo "<tr class='$LINE_CLASS'>";
			for($J =0 ; $J < $row_num; $J++){
				printListEmptyData();
			}
			echo "</tr>\n";
		}
		?>
		</table>
		</div>

		<!--end data list-->
		</td>
		<td class="list_right_border"></td>
		</tr>
		<tr>
		<td><img src="<?=CRM_CONTEXT_IMG_PATH?>/list/bottom_left_conner.png"/></td>
		<td class="list_bottom_border" width="100%"></td>
		<td><img src="<?=CRM_CONTEXT_IMG_PATH?>/list/bottom_right_conner.png"/></td>
		</tr>
		</table>
		<!-- end data field-->
		<?
	}
?>
<style>
	.select_tr td{background:#cbefcf;}
	.mouseover_tr td{background:#e6e8ed;}
</style>
<script>
	var last_row;
	function select(row_num,eve){
		org_className=(row_num%2==0) ? "CRM_TableLine1" : "CRM_TableLine2";
		var obj=document.getElementById(row_num);
		var td_arr=obj.getElementsByTagName("td");
		var len=td_arr.length;
		var data_cnt=getQueryString("data_cnt");
		if(obj.className!="select_tr"){
			if(select_obj!=""){
				select_obj.className=select_org_class;
			}
			if(last_row!=undefined){//为上一个选中的行单选恢复样式
				document.getElementById(last_row).className=(last_row%2==0) ? "CRM_TableLine1" : "CRM_TableLine2";
			}
			obj.className="select_tr";
			for(j=0;j<data_cnt;j++){
				var fields=getQueryString("ctrl_field"+j);
				var values=getQueryString("ctrl_value"+j);
				for(i=0;i<len;i++){
					if(obj.childNodes[i].name==values){
						window.opener.document.getElementById(fields).value=obj.childNodes[i].value;
					}
				}
			}	
		}else{
			if(eve=="click"){
				window.setTimeout(delay, 500)//单击加延时区分单双击事件
					function delay(){
						obj.className=org_className;
						for(j=0;j<data_cnt;j++){
							var fields=getQueryString("ctrl_field"+j);
							window.opener.document.getElementById(fields).value="";
						}
					}
			}else if(eve=="dbclick"){
				window.close();
			}
		}
		last_row=row_num;
	}
	
	var last_over_row="";
	function changestyle(row_num){
		org_className=(row_num%2==0) ? "CRM_TableLine1" : "CRM_TableLine2";
		var obj=document.getElementById(row_num);
		if(obj.className=="select_tr"){
			
		}else{
			if(last_over_row!=""){
				if(document.getElementById(last_over_row).className=="select_tr"){

				}else{
					document.getElementById(last_over_row).className=(last_over_row%2==0) ? "CRM_TableLine1" : "CRM_TableLine2";
				}
			}
			obj.className="mouseover_tr";
			last_over_row=row_num;
		}
	}
</script>