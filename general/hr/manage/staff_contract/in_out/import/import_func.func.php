<?php
function is_money_crm($str){
	$money = $str;
	if(strpos($str, '.')){
		$interception = count($moneyArr = explode('.', $str));
		if($interception > 2){
			return false;
		}else{
			$money = $moneyArr[0];
		}
	}
	if(strpos($money, ',')){
		$strArr = explode(',', $money);
		foreach($strArr as $value){
			if(strlen($value) < 0 || strlen($value) > 3){
				return false;
			}elseif(!is_numeric($value)){
				return false;
			}
		}
	}elseif(!is_numeric($money)){
		return false;
	}
	return true;
}
function excelTime($days, $time=false){
	if(is_numeric($days)){
		//based on 1900-1-1
		$jd = GregorianToJD(1, 1, 1970);
		$gregorian = JDToGregorian($jd+intval($days)-25569);
		$myDate = explode('/',$gregorian);
		$myDateStr = str_pad($myDate[2],4,'0', STR_PAD_LEFT)
				."-".str_pad($myDate[0],2,'0', STR_PAD_LEFT)
				."-".str_pad($myDate[1],2,'0', STR_PAD_LEFT)
				.($time?" 00:00:00":'');
		return $myDateStr;
	}
	return $days;
}
function indexField(){
	$sqlIndex = 'show index from CRM_PRODUCT';
	$queryIndex = db_query($sqlIndex, TD::conn());
	while($rowIndex = mysql_fetch_assoc($queryIndex)){
	$arrIndex[] = $rowIndex['Column_name'];
	}
	return $arrIndex;
}
function foreignKeyArr($connArr){
	foreach($connArr as $connValue){
		$type = explode('~',$connValue['fielddatatype']);
		if($type[0] == 'F'){
			$sql 	=	"select id,".$connValue['FOREIGN_KEY']['field']." from ".$connValue['FOREIGN_KEY']['table'];
			$query  = db_query($sql, TD::conn());
			while($row = mysql_fetch_array($query)){
				$foreignKeyArr[$connValue['fieldName']][$row[$connValue['FOREIGN_KEY']['field']]] = $row['id'];
			}
		}
	}
	return $foreignKeyArr;
}
function codeArr($connArr){ 
	foreach($connArr as $connValue){
		$type = explode('~',$connValue['fielddatatype']);
		if($type[0] == 'C'){
			$sql =	"select code_no,code_name from crm_sys_code where code_type = '".$connValue['CODE_TYPE']."'";
			$query = db_query($sql, TD::conn());
			while($row = mysql_fetch_array($query)){
				$codeArr[$connValue['fieldName']][$row['code_name']] = $row['code_no'];
			}
			
		}
	}
	return $codeArr;
}	
function userArr($connArr){ 
	foreach($connArr as $connValue){
		$type = explode('~',$connValue['fielddatatype']);
		if($type[0] == 'U'){
			$sql =	"select USER_ID,USER_NAME from USER";
			$query = db_query($sql, TD::conn());
			while($row = mysql_fetch_array($query)){
				$userArr[$connValue['fieldName']][$row['USER_NAME']] = $row['USER_ID'];
			}
			
		}
	}
	return $userArr;
}	
function deptArr($connArr){ 
	foreach($connArr as $connValue){
		$type = explode('~',$connValue['fielddatatype']);
		if($type[0] == 'DEPT'){
			$sql =	"select DEPT_ID,DEPT_NAME from DEPARTMENT";
			$query = db_query($sql, TD::conn());
			while($row = mysql_fetch_array($query)){
				$deptArr[$connValue['fieldName']][$row['DEPT_NAME']] = $row['DEPT_ID'];
			}
			
		}
	}
	return $deptArr;
}
function fieldNameStr($connArr){
	foreach($connArr as $value){
		$fieldStr .= $value['fieldName'] . ', ';
	}
	$fieldStr = substr($fieldStr, 0, -2);

	return $fieldStr;
}

function fieldConnArr($thanArr,$conn){
	foreach($thanArr as $key => $value){
		$connArr[$key]['fieldName']		 = $conn[$value]['fieldName'];
		$connArr[$key]['title']			 = $value;
		$connArr[$key]['fielddatatype']  = $conn[$value]['fielddatatype'];
		$connArr[$key]['CODE_TYPE']		 = $conn[$value]['CODE_TYPE'];
		$connArr[$key]['FOREIGN_KEY']	 = $conn[$value]['FOREIGN_KEY'];
	}
	return $connArr;
}
function fieldAsTitle($fieldName){
	
	include("import_config.php");
	
	foreach($conn as $key => $value){
		$fieldArr[$value['fieldName']] = $key;
	}
	return $fieldArr[$fieldName];
	
}
function headThan($lines, $conn, $filePath){
	$isRequired = array('M' => _("是"), 'O' => _("否"));
	$dataTypes  = array('N' => _("数字"),'B'=>_("附件属性"),'L'=>_("合同类型"),'X'=>_("合同属性"),'Z'=>_("合同状态"),'V' => _("字符串"),'D' => _("日期"),'C' => _("系统代码"),'F' => _("外键"),'NN' => _("金额"), 'S' => _("是否"), 'U' => _("系统用户"), 'CB' => _("系统部门"),'T'=>_("创建者"),'HB'=>_("合同编号"));
	$headErrorArr .= "<table cellspacing='0' cellpadding='' width='90%' align='center' border='0' style='margin-top:4px;margin-bottom: 4px;'>";
	$headErrorArr .= "<tr class='blockHeader'>
		<td width='30%' align='center'>"._("表头名")."</td>
		<td width='18%' align='center'>"._("是否必需")."</td>
		<td width='20%' align='center'>"._("数据类型")."</td>
		<td width='20%' align='center'>"._("匹配结果")."</td></tr>";
	foreach($conn as $key => $value){
		$type = explode('~',$value['fielddatatype']);
		if($type[1] == 'M'){
			if($newkey = array_search($key, $lines[0])){
				$newArr[$newkey] 	   =  $key;
				$headErrorArr .= "<tr >
					<td class='efCellCtrl' >$key</td> 
					<td align='center' class='efCellCtrl'>".$isRequired[$type[1]]."</td>
					<td class='efCellCtrl'>".$dataTypes[$type[0]]."</td>
					<td align='center' class='efCellCtrl'>"._("√")."</td></tr>";
				
				
			}else{
				$headErrorArr .= "<tr >
					<td class='efCellCtrl'>$key</td> 
					<td align='center' class='efCellCtrl'>".$isRequired[$type[1]]."</td>
					<td class='efCellCtrl'>".$dataTypes[$type[0]]."</td>
					<td align='center' class='efCellCtrl'>X</td></tr>";
				$headErrorTest .= "$key "; 
				
			}
		}else{
		      
			if($newkey = array_search($key, $lines[0])){
			    
				$newArr[$newkey] 	   =  $key;
					$headErrorArr .= "<tr >
						<td class='efCellCtrl'>$key</td> 
						<td align='center' class='efCellCtrl'>".$isRequired[$type[1]]."</td>
						<td class='efCellCtrl'>".$dataTypes[$type[0]]."</td>
						<td align='center' class='efCellCtrl'>"._("√")."</td></tr>";
			}else{
					$headErrorArr .= "<tr >
						<td class='efCellCtrl'>$key</td> 
						<td align='center' class='efCellCtrl'>".$isRequired[$type[1]]."</td>
						<td class='efCellCtrl'>".$dataTypes[$type[0]]."</td>
						<td align='center' class='efCellCtrl'>X</td></tr>";
			}
		}
		
	}
	
	if($headErrorTest){
		echo $headErrorArr;
		$headTest .= "<tr ><td class='efCellCtrl' colspan = '4'>";
		$headTest .= sprintf(_("%s 为必填项，未找到,不能导入，请修改后再试"), $headErrorTest)." 
		<input type='button' value = '"._("返回")."' class ='crm_SmallButton' onClick = 'window.history.go (-1)'>";
		$headTest .= "</td></tr>";
		echo $headTest;
		echo "</table>";
		exit;

	}else{
		@ksort($newArr);
		$head_arr = serialize($newArr);
		echo $headErrorArr;
		$headTest .= "<tr ><td class='efCellCtrl' colspan = '4'>";
		
		$headTest .= _("数据表头匹配正确,请点击下一步进行导入")."<form name='form1' method='post' enctype='multipart/form-data' action='import.php'> <input type='hidden' name='head_arr' value='$head_arr'>
		<input type='hidden' name='file_path' value='$filePath'>
		<input type='button' value = '"._("下一步")."' class ='BigButton' onClick = 'submit();'>
		<input type='button' value = '"._("返回")."' class ='BigButton' onClick = 'window.history.go (-1)'></form>";
		$headTest .= "</td></tr>";
		echo $headTest;
		echo "</table>";
	}
}
function excel_str_num($str)
{
	$SUM = 0;
	for($i=0;$i < strlen($str);$i++)
	{
		$str_tem = substr($str,$i,$i+1);
		$SUM = $SUM* 26 + ord($str_tem)-ord("A") + 1;
	}
	return $SUM;
}

function excel_num_str($tem)
{
	$str="";
	$i=0;
	while($tem>26)
	{
		$yu=$tem%26;
		if($yu==0)
			$str="Z".$str;
		else
			$str=chr($yu+ord("A")-1).$str;
		$tem=floor(($tem-1)/26);
		$i++;
	}
	$str=chr($tem+ord("A")-1).$str;
	return $str;
}