<?php
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$title	= _("��ͬ��Ϣ");
$dbName	= 'hr_staff_contract';


$conn	= array(
	
				_("�������û���") => array(
					"fieldName"		=> "CREATE_USER_ID",
					"fielddatatype"	=> "T~M",
				),
				_("�����߲��ű��") => array(
					"fieldName"		=> "CREATE_DEPT_ID",
					"fielddatatype"	=> "CB~M",
					
				),
				_("�û���") => array(
					"fieldName"		=> "STAFF_USER_NAME",	
					"fielddatatype"	=> "US~M",
					
				),							
				_("����") => array(
					"fieldName"		=> "STAFF_NAME",	
					"fielddatatype"	=> "U~M",
					
				),				
				_("��ͬ���") => array(
					"fieldName"		=> "STAFF_CONTRACT_NO",
					"fielddatatype"	=> "HB~M",
					
				),	
					
				_("��ͬ����") => array(
					"fieldName"		=> "CONTRACT_TYPE",
					"fielddatatype"	=> "L~M",
				),					
				_("��ͬ����") => array(
					"fieldName"		=> "CONTRACT_SPECIALIZATION",
					"fielddatatype"	=> "X~M",
				),
        		_("��ͬǩ������") => array(
					"fieldName"		=> "MAKE_CONTRACT",
					"fielddatatype"	=> "D~O",
				),
				_("������Ч����") => array(
					"fieldName"		=> "TRAIL_EFFECTIVE_TIME",
					"fielddatatype"	=> "D~O",
					
				),				

				_("��������") => array(
					"fieldName"		=> "PROBATIONARY_PERIOD",
					"fielddatatype"	=> "V~O",
				),
				_("���õ�������") => array(
					"fieldName"		=> "TRAIL_OVER_TIME",
					"fielddatatype"	=> "D~O",
				
				),
				_("��ͬ�Ƿ�ת��") => array(
					"fieldName"		=> "PASS_OR_NOT",
					"fielddatatype"	=> "S~O",
				
				),
				
				_("��ͬת������") => array(
					"fieldName"		=> "PROBATION_END_DATE",
					"fielddatatype"	=> "D~O",
				),
				
				_("��ͬ��Ч����") => array(
					"fieldName"		=> "PROBATION_EFFECTIVE_DATE",
					"fielddatatype"	=> "D~O",
				),		
				_("��ͬ����") => array(
					"fieldName"		=> "ACTIVE_PERIOD",
					"fielddatatype"	=> "N~O",
					
				),				
				_("��ͬ��������") => array(
					"fieldName"		=> "CONTRACT_END_TIME",
					"fielddatatype"	=> "D~O",
					
				),				
				_("ע��") => array(
					"fieldName"		=> "REMARK",
					"fielddatatype"	=> "V~O",
					
				),				
				_("��ͬ�Ƿ���") => array(
					"fieldName"		=> "REMOVE_OR_NOT",
					"fielddatatype"	=> "S~O",
				),				
				_("��ͬ�������") => array(
					"fieldName"		=> "CONTRACT_REMOVE_TIME",
					"fielddatatype"	=> "D~O",
				),				
				_("��ͬ״̬") => array(
					"fieldName"		=> "STATUS",
					"fielddatatype"	=> "Z~O",
				),				
				_("ǩԼ����") => array(
					"fieldName"		=> "SIGN_TIMES",
					"fielddatatype"	=> "N~O",
				),				
				_("�������") => array(
					"fieldName"		=> "ATTACHMENT_ID",
					"fielddatatype"	=> "B~O",
				),				
				_("��������") => array(
					"fieldName"		=> "ATTACHMENT_NAME",
					"fielddatatype"	=> "B~O",
				),				
				_("�Ǽ�ʱ��") => array(
					"fieldName"		=> "ADD_TIME",
					"fielddatatype"	=> "D~O",
				),				
				_("����ʱ��") => array(
					"fieldName"		=> "REMIND_TIME",
					"fielddatatype"	=> "D~O",
				),				
				
			);
