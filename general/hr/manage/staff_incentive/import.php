<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$CUR_TIME=date("Y-m-d H:i:s",time());

$HTML_PAGE_TITLE = _("导入数据");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(strtolower(substr($FILE_NAME,-3))!="xls")
{
   Message(_("错误"),_("只能导入Excel文件!"));
   Button_Back();
   exit;
}
if(MYOA_IS_UN == 1)
{
   $title=array("ID"=>"USER_ID","NAME"=>"STAFF_NAME","ITEM"=>"INCENTIVE_ITEM","DATE"=>"INCENTIVE_TIME","SALARY_MONTH"=>"SALARY_MONTH","TYPE"=>"INCENTIVE_TYPE","AMOUNT"=>"INCENTIVE_AMOUNT","DESCRIPTION"=>"INCENTIVE_DESCRIPTION","MEMO"=>"REMARK");
   $fieldAttr = array("DATE" => "date","SALARY_MONTH"=> "date");
}
else
{
   $title=array(_("用户名")=>"USER_ID",_("单位员工")=>"STAFF_NAME",_("奖惩项目")=>"INCENTIVE_ITEM",_("奖惩日期")=>"INCENTIVE_TIME",_("工资月份")=>"SALARY_MONTH",_("奖惩属性")=>"INCENTIVE_TYPE",_("奖惩金额（元）")=>"INCENTIVE_AMOUNT",_("奖惩说明")=>"INCENTIVE_DESCRIPTION",_("备注")=>"REMARK");
   $fieldAttr = array(_("奖惩日期") => "date",_("工资月份")=> "date");
}

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE, $title, $fieldAttr);


$user_info_arr = array();
$query = "select UID,BYNAME,USER_ID from user where NOT_LOGIN='0' AND DEPT_ID!='0'";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
{
	$user_info_arr[$row['BYNAME']] = $row;
}

$ROW_COUNT = 0;
$MSG_ERROR = "";
$rows=0;
while($DATA = $objExcel->getNextRow())
{
	$rows++;

   if($DATA['USER_ID'] == "" && $DATA['STAFF_NAME'] == "")
   {
  		$MSG_ERROR.="<font color=red size=3px>".sprintf(_("第%s行导入失败，因为用户名、单位员工两项必须填写一项"), $rows)."</font><br/>";
		continue;
   }
   else if($DATA['USER_ID'] != "")
   {
	   if(!$user_info_arr[$DATA['USER_ID']]['USER_ID'])
	   {
		   $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为用户名不存在!"), $rows)."</font><br/>";
		   continue; 
	   }
	   else
	   {
		  $DATA['STAFF_NAME'] = $user_info_arr[$DATA['USER_ID']]['USER_ID']; 
	   }
      
   }
   else if($DATA['STAFF_NAME'] != "")
   {
      $query = "select USER_ID from USER where USER_NAME='" .$DATA['STAFF_NAME'] . "' limit 1";
      $cur = exequery(TD::conn(), $query);
      if($ROW = mysql_fetch_array($cur))
         $DATA['STAFF_NAME'] = $ROW['USER_ID'];
   }
   if($DATA["STAFF_NAME"]!="")
   {
       $query="select 1 from USER where USER_ID='".$DATA["STAFF_NAME"]."' ";
       $cursor=exequery(TD::conn(),$query);
       if(!$ROW=mysql_fetch_array($cursor))
       {
           $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为用户名或姓名不存在!"), $rows)."</font><br/>";
           continue;
       }
   }
   if($DATA['INCENTIVE_AMOUNT']=="" || $DATA['INCENTIVE_AMOUNT']<0)
   {
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为奖惩金额不能为空或不能为负数!"), $rows)."</font><br/>";
        continue;
   }        

   if($DATA['INCENTIVE_ITEM']=="")
   {
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为奖惩项目不能为空!"), $rows)."</font><br/>";
        continue;
   }else
   {
       $sql = "select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='HR_STAFF_INCENTIVE1' and CODE_NAME = '{$DATA['INCENTIVE_ITEM']}'";
       $cur = exequery(TD::conn(),$sql);
       if(!mysql_affected_rows()>0)
       {
           $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为奖惩项目不存在!"), $rows)."</font><br/>";
           continue;
       }
       
   }

   $ROW_COUNT++;
   $ID_STR="";
   $VALUE_STR="";
   $STR_UPDATE="";

   reset($title);
   foreach($title as $key)
   {
      if(find_id($ID_STR, $key))
         continue;
      if($key=="USER_ID")
         continue;
      if($key=="SALARY_MONTH")
         $DATA["SALARY_MONTH"] = substr($DATA["SALARY_MONTH"],0,-3);

      if($key=="INCENTIVE_ITEM" && $DATA['INCENTIVE_ITEM']!="")
      {
      	 $sql="select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='HR_STAFF_INCENTIVE1'";
      	 $cure=exequery(TD::conn(),$sql);
    		 while($ROW1=mysql_fetch_array($cure))
    			  if($DATA["INCENTIVE_ITEM"]==$ROW1['CODE_NAME'])
    				   $DATA['INCENTIVE_ITEM']=$ROW1['CODE_NO'];
    	}

      if($key=="INCENTIVE_TYPE" && $DATA['INCENTIVE_TYPE']!="")
      {
      	 $sql="select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='INCENTIVE_TYPE'";
      	 $cure=exequery(TD::conn(),$sql);
    		 while($ROW1=mysql_fetch_array($cure))
    			  if($DATA["INCENTIVE_TYPE"]==$ROW1['CODE_NAME'])
    				   $DATA['INCENTIVE_TYPE']=$ROW1['CODE_NO'];
      }

      $ID_STR.=$key.",";
      $VALUE_STR.="'".$DATA[$key]."',";
      $STR_UPDATE.="$key='$DATA[$key]',";
   }
    //echo "<br>++++++++++";
		//echo "<br>".$ID_STR;
		//echo "<br>".$STR_UPDATE;
    //echo "<br>==========";
   if (substr($STR_UPDATE,-1)==",")
       $STR_UPDATE=substr($STR_UPDATE,0,-1);

   $ID_STR=trim($ID_STR,",");
   $VALUE_STR=trim($VALUE_STR,",");
   $query="select INCENTIVE_ID from HR_STAFF_INCENTIVE where INCENTIVE_TYPE='".$DATA["INCENTIVE_TYPE"]."' and INCENTIVE_TIME='".$DATA["INCENTIVE_TIME"]."' and STAFF_NAME='".$DATA["STAFF_NAME"]."' and CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $INCENTIVE_ID = $ROW["INCENTIVE_ID"];
      $query1="update HR_STAFF_INCENTIVE SET ".$STR_UPDATE." where INCENTIVE_ID='".$INCENTIVE_ID."'";
      exequery(TD::conn(),$query1);
   }else{
      $query="insert into HR_STAFF_INCENTIVE (CREATE_USER_ID,CREATE_DEPT_ID,ADD_TIME,LAST_UPDATE_TIME,".$ID_STR.") values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$CUR_TIME','$CUR_TIME',".$VALUE_STR.");";
      exequery(TD::conn(),$query);
   }
}

if(file_exists($EXCEL_FILE))
   @unlink($EXCEL_FILE);
Message("",sprintf(_("共%d条数据导入!"), $ROW_COUNT));
echo $MSG_ERROR;
?>
<div align="center">
<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='pre_import.php';" title="<?=_("返回")?>">
</div>
</body>
</html>
