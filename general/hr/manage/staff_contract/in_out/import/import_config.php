<?php
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$title	= _("合同信息");
$dbName	= 'hr_staff_contract';


$conn	= array(
	
				_("创建者用户名") => array(
					"fieldName"		=> "CREATE_USER_ID",
					"fielddatatype"	=> "T~M",
				),
				_("创建者部门编号") => array(
					"fieldName"		=> "CREATE_DEPT_ID",
					"fielddatatype"	=> "CB~M",
					
				),
				_("用户名") => array(
					"fieldName"		=> "STAFF_USER_NAME",	
					"fielddatatype"	=> "US~M",
					
				),							
				_("姓名") => array(
					"fieldName"		=> "STAFF_NAME",	
					"fielddatatype"	=> "U~M",
					
				),				
				_("合同编号") => array(
					"fieldName"		=> "STAFF_CONTRACT_NO",
					"fielddatatype"	=> "HB~M",
					
				),	
					
				_("合同类型") => array(
					"fieldName"		=> "CONTRACT_TYPE",
					"fielddatatype"	=> "L~M",
				),					
				_("合同属性") => array(
					"fieldName"		=> "CONTRACT_SPECIALIZATION",
					"fielddatatype"	=> "X~M",
				),
        		_("合同签订日期") => array(
					"fieldName"		=> "MAKE_CONTRACT",
					"fielddatatype"	=> "D~O",
				),
				_("试用生效日期") => array(
					"fieldName"		=> "TRAIL_EFFECTIVE_TIME",
					"fielddatatype"	=> "D~O",
					
				),				

				_("试用期限") => array(
					"fieldName"		=> "PROBATIONARY_PERIOD",
					"fielddatatype"	=> "V~O",
				),
				_("试用到期日期") => array(
					"fieldName"		=> "TRAIL_OVER_TIME",
					"fielddatatype"	=> "D~O",
				
				),
				_("合同是否转正") => array(
					"fieldName"		=> "PASS_OR_NOT",
					"fielddatatype"	=> "S~O",
				
				),
				
				_("合同转正日期") => array(
					"fieldName"		=> "PROBATION_END_DATE",
					"fielddatatype"	=> "D~O",
				),
				
				_("合同生效日期") => array(
					"fieldName"		=> "PROBATION_EFFECTIVE_DATE",
					"fielddatatype"	=> "D~O",
				),		
				_("合同期限") => array(
					"fieldName"		=> "ACTIVE_PERIOD",
					"fielddatatype"	=> "N~O",
					
				),				
				_("合同到期日期") => array(
					"fieldName"		=> "CONTRACT_END_TIME",
					"fielddatatype"	=> "D~O",
					
				),				
				_("注备") => array(
					"fieldName"		=> "REMARK",
					"fielddatatype"	=> "V~O",
					
				),				
				_("合同是否解除") => array(
					"fieldName"		=> "REMOVE_OR_NOT",
					"fielddatatype"	=> "S~O",
				),				
				_("合同解除日期") => array(
					"fieldName"		=> "CONTRACT_REMOVE_TIME",
					"fielddatatype"	=> "D~O",
				),				
				_("合同状态") => array(
					"fieldName"		=> "STATUS",
					"fielddatatype"	=> "Z~O",
				),				
				_("签约次数") => array(
					"fieldName"		=> "SIGN_TIMES",
					"fielddatatype"	=> "N~O",
				),				
				_("附件编号") => array(
					"fieldName"		=> "ATTACHMENT_ID",
					"fielddatatype"	=> "B~O",
				),				
				_("附件名称") => array(
					"fieldName"		=> "ATTACHMENT_NAME",
					"fielddatatype"	=> "B~O",
				),				
				_("登记时间") => array(
					"fieldName"		=> "ADD_TIME",
					"fielddatatype"	=> "D~O",
				),				
				_("提醒时间") => array(
					"fieldName"		=> "REMIND_TIME",
					"fielddatatype"	=> "D~O",
				),				
				
			);
