<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

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
if(MYOA_IS_UN == 1){
   $title=array("FILE_CODE"=>"FILE_CODE","FILE_SUBJECT"=>"FILE_SUBJECT","TITLE"=>"FILE_TITLE","TITLE0"=>"FILE_TITLE0","SEND_UNIT"=>"SEND_UNIT","SEND_DATE"=>"SEND_DATE","SECRET"=>"SECRET","URGENCY"=>"URGENCY","TYPE"=>"FILE_TYPE","KIND"=>"FILE_KIND","FILE_PAGE"=>"FILE_PAGE","PRINT_PAGE"=>"PRINT_PAGE","MEMO"=>"REMARK","ROLL_ID"=>"ROLL_ID");
   $fieldAttr = array("SEND_DATE" => "date");
}
else{
   $title=array(_("文件号")=>"FILE_CODE",_("文件主题词")=>"FILE_SUBJECT",_("文件标题")=>"FILE_TITLE",_("文件辅标题")=>"FILE_TITLE0",_("发文单位")=>"SEND_UNIT",_("发文日期")=>"SEND_DATE",_("密级")=>"SECRET",_("紧急等级")=>"URGENCY",_("文件分类")=>"FILE_TYPE",_("公文类别")=>"FILE_KIND",_("文件页数")=>"FILE_PAGE",_("打印页数")=>"PRINT_PAGE",_("备注")=>"REMARK",_("所属案卷名称")=>"ROLL_ID");
   $fieldAttr = array("发文日期" => "date");
}

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];
$data=file_get_contents($EXCEL_FILE);
//$lines=CSV2Array($data, $title);
if(!$data)
{
   Message(_("错误"),_("打开文件错误!"));
   Button_Back();
   exit;
}
$ROW_COUNT=0;  
$SUCCESS_COUNT =0;  
$CUR_TIME = Date('Y-m-d H:i:s');

require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE, $title, $fieldAttr);
$lines = array();
$MSG_ERROR = array();
while($line = $objExcel->getNextRow())
{
   $lines[] = $line;
   $DATA_NUM = count ($line);
   $STR_VALUE="";
   $STR_KEY="";
   $STR_UPDATE="";
   $success = 1;
   
   foreach ($line as $key => $value) 
   {
	   $STR_KEY.= "`$key`".",";
	  
	   if($key=="FILE_CODE")
	   {
		   if($value =="")
		   {
			   $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("文件号不能为空")."</font>";
			   $ROW_COUNT++;
			   continue 2; 
		   }
	   }
	   if($key=="FILE_TITLE")
	   {
		   if($value =="")
		   {
			   $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("文件标题不能为空")."</font>";
			   $ROW_COUNT++;
			   continue 2; 
		   }else{
               $STR_UPDATE .= $key."='".$value."',";
           }
	   }
	   if($key=="ROLL_ID")
	   {
		   if($value =="")
		   {
			   $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("所属案卷名称不能为空")."</font>";
			   $ROW_COUNT++;
			   continue 2; 
		   }
		   else
		   {
			   $sql="SELECT STATUS,ROLL_ID FROM rms_roll WHERE ROLL_NAME = '$value'";
			   $cur= exequery(TD::conn(),$sql);
			   if($arr=mysql_fetch_array($cur))
			   {
				   $STATUS = $arr['STATUS'];
				   $ROLL_ID = $arr['ROLL_ID'];
			   }
			   if($ROLL_ID =="")
			   {
				   $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("所属案卷不存在")."</font>";
				   $ROW_COUNT++;
				   continue 2; 
			   }
			   elseif($STATUS ==1)
			   {
				   $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("所属案卷已封卷")."</font>";
				   $ROW_COUNT++;
				   continue 2; 
			   }
			   $query_str = '';
			   $DEPT_ID   = '';
			   $DEPT_ID   = get_dept_parent_all($_SESSION['LOGIN_DEPT_ID']);
			   if($_SESSION["LOGIN_USER_PRIV"]!=1)
			   {
				   $query_str.=' AND DEPT_ID = 0 or DEPT_ID in ('.$DEPT_ID.$_SESSION['LOGIN_DEPT_ID'].')';
			   }
			   if($_SESSION["LOGIN_USER_PRIV"]!=1 && $_SESSION['LOGIN_DEPT_ID_OTHER']!="")
			   {
				   $query_str.= 'or FIND_IN_SET (DEPT_ID,"'.($_SESSION['LOGIN_DEPT_ID_OTHER']).'") ';
			   }
			   $query = 'SELECT * from RMS_ROLL where STATUS = 0 '.$query_str.'order by ROLL_CODE asc';
			   $cursor= exequery(TD::conn(),$query);
			   while($ROW=mysql_fetch_array($cursor))
			   {
				   $ROLL_NAME .= $ROW["ROLL_NAME"].",";
			   }
			   if(substr($ROLL_NAME,-1)==",")
			   {
				   $ROLL_NAME=substr($ROLL_NAME,0,-1);
			   }
			   //echo $ROLL_NAME."|";
			   if(!strstr($ROLL_NAME,$value))
			   {
				   $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("所属案卷不在你所管辖的范围")."</font>";
				   $ROW_COUNT++;
				   continue 2; 
			   }
			   /*else
			   {
				   $ROLL_ID="";
				   $query = "SELECT ROLL_ID from RMS_ROLL where ROLL_NAME ='$value'";
				   $cursor = exequery(TD::conn(),$query);
				   if($ROW = mysql_fetch_array($cursor))
				   {
					   $ROLL_ID = $ROW['ROLL_ID'];
				   }
				   //$STR_VALUE.="'$ROLL_ID'".",";
			   }*/
			      
		   }
	   }
	   if($key=="FILE_CODE")
	   {
		   $FILE_CODE = $value;
		   $AUTO_COVER = $_POST['AUTO_COVER'];
		   $sql1="SELECT * FROM rms_file WHERE FILE_CODE = '$value'";
		   $cur= exequery(TD::conn(),$sql1);
		   if(mysql_affected_rows()>0 && $AUTO_COVER==1){
			   $success = 0;
		   }
		   if($success)
		   {
			   //$sql1="SELECT * FROM rms_file WHERE ROLL_ID = '$ROLL_ID' AND FILE_CODE = '$value'";
			   $sql1="SELECT * FROM rms_file WHERE FILE_CODE = '$value'";
			   $cur= exequery(TD::conn(),$sql1);
			   if(mysql_affected_rows())
			   {
				   $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("所属案卷中文件号必须唯一")."</font>";
				   $ROW_COUNT++;
				   continue 2;
			   }
		   }
	   }
	   //========================
	   if($key!="FILE_CODE" && $key!="FILE_TITLE"){
		   if($key=="SECRET"){
			   if($value == '普密'){
				   $value = 1;
			   }else if($value == '绝密'){
				   $value = 2;
			   }else if($value == '机密'){
				   $value = 3;
			   }else if($value == '秘密'){
				   $value = 4;
			   }
		   }
		   if($key=="URGENCY"){
			   if($value == '员工类型'){
				   $value = 1;
			   }else if($value == '普级'){
				   $value = 2;
			   }
		   }
		   if($key=="FILE_TYPE"){
			   if($value == '公文'){
				   $value = 1;
			   }else if($value == '资料'){
				   $value = 2;
			   }
		   }
		   if($key=="ROLL_ID"){
			   if($value == '项目案卷'){
				   $value = 1;
			   }else if($value == '行政案卷'){
				   $value = 2;
			   }else if($value == '人事案卷'){
				   $value = 3;
			   }else if($value == '保密资料案卷'){
				   $value = 4;
			   }else if($value == '测试规则案卷'){
				   $value = 5;
			   }else if($value == '合同样本案卷'){
				   $value = 6;
			   }else if($value == '公司资产案卷'){
				   $value = 7;
			   }else if($value == '员工联系方式案卷'){
				   $value = 8;
			   }else if($value == '开发文档案卷'){
				   $value = 9;
			   }
		   }
		   $STR_UPDATE .= $key."='".$value."',";
	   }
	   //========================
	   if($key=="FILE_TITLE")
	   {
		   if($success)
		   {
			   $FILE_TITLE = $value;
			   //$sql1="SELECT * FROM rms_file WHERE ROLL_ID = '$ROLL_ID' AND FILE_TITLE = '$value'";
			   $sql1="SELECT * FROM rms_file WHERE FILE_TITLE = '$value'";
			   $cur= exequery(TD::conn(),$sql1);
			   if(mysql_affected_rows()>0)
			   {
				   $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("所属案卷中文件标题必须唯一")."</font>";
				   $ROW_COUNT++;
				   continue 2; 
			   }
		   }
	   } 
      /*if (($key!="SECRET")&&($key!="URGENCY")&&($key!="FILE_TYPE")&&($key!="FILE_KIND")&&($key!="ROLL_ID"))
      {
         $STR_VALUE.="'$value'".",";
      }*/
      /*else
      {*/
         if ($key=="SECRET")
         {
            $CODE_NO1="";
            $query = "SELECT CODE_NO from SYS_CODE where PARENT_NO='RMS_SECRET' and CODE_NAME='$value'";
            $cursor = exequery(TD::conn(),$query);
            if($ROW = mysql_fetch_array($cursor))
               //$CODE_NO1 = $ROW['CODE_NO'];
				$value = $ROW['CODE_NO'];

            //$STR_VALUE.="'$CODE_NO1'".",";
         }
         if ($key=="URGENCY")
         {
            $CODE_NO2="";
            $query = "SELECT CODE_NO from SYS_CODE where PARENT_NO='RMS_URGENCY' and CODE_NAME='$value'";
            $cursor = exequery(TD::conn(),$query);
            if($ROW = mysql_fetch_array($cursor))
               //$CODE_NO2 = $ROW['CODE_NO'];
				$value = $ROW['CODE_NO'];

            //$STR_VALUE.="'$CODE_NO2'".",";
        }
        if ($key=="FILE_TYPE")
        {
            $CODE_NO3="";
            $query = "SELECT CODE_NO from SYS_CODE where PARENT_NO='RMS_FILE_TYPE' and CODE_NAME='$value'";
            $cursor = exequery(TD::conn(),$query);
            if($ROW = mysql_fetch_array($cursor))
               //$CODE_NO3 = $ROW['CODE_NO'];
				$value = $ROW['CODE_NO'];

            //$STR_VALUE.="'$CODE_NO3'".",";
        }
        if ($key=="FILE_KIND")
        {
            $CODE_NO4="";
            $query = "SELECT CODE_NO from SYS_CODE where PARENT_NO='RMS_FILE_KIND' and CODE_NAME='$value'";
            $cursor = exequery(TD::conn(),$query);
            if($ROW = mysql_fetch_array($cursor))
               //$CODE_NO4 = $ROW['CODE_NO'];
				$value = $ROW['CODE_NO'];

            //$STR_VALUE.="'$CODE_NO4'".",";
        }
	    $STR_VALUE.="'$value'".",";
      /*}*/
   }
   
   if (substr($STR_KEY,-1)==",")
      $STR_KEY=substr($STR_KEY,0,-1);
   if (substr($STR_VALUE,-1)==",")
      $STR_VALUE=substr($STR_VALUE,0,-1);
      
   $array = explode(",",$STR_VALUE);
   if($array[0]=="''") continue;
   
   if($success)
   {
      $query="insert into RMS_FILE (".$STR_KEY.",`BORROW`,`BORROW_STATUS`,`ADD_TIME`,`ADD_USER`) values (".$STR_VALUE.",'','','".$CUR_TIME."','".$_SESSION["LOGIN_USER_ID"]."')";
      exequery(TD::conn(),$query);
      $MSG_ERROR[$ROW_COUNT] = _("导入成功");
      $ROW_COUNT++;
	  $SUCCESS_COUNT++;
   }
   if($success == 0){
	  $STR_UPDATE = td_trim($STR_UPDATE);
	  $query="update RMS_FILE set ".$STR_UPDATE." where FILE_CODE= '$FILE_CODE'";
	  exequery(TD::conn(),$query);
	  $MSG_ERROR[$ROW_COUNT] = _("已覆盖");
	  $ROW_COUNT++;
	  $SUCCESS_COUNT++;
   }
}
if(file_exists($EXCEL_FILE))
   @unlink($EXCEL_FILE);
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script> 
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<br />
<table class="table table-bordered table-hover" style="width:95%;" align="center">
<thead style="background-color:#EBEBEB;">
	<th nowrap style="text-align: center;width:10%;"><?=_("文件号")?></th>
	<th nowrap style="text-align: center;width:15%;"><?=_("文件标题")?></th>
	<th nowrap style="text-align: center;width:5%;"><?=_("密级")?></th>
   	<th nowrap style="text-align: center;width:20%;"><?=_("发文单位")?></th>
	<th nowrap style="text-align: center;width:10%;"><?=_("发文时间")?></th>
	<th nowrap style="text-align: center;width:10%;"><?=_("所属案卷")?></th>
    <th nowrap style="text-align: center;width:30%;"><?=_("状态")?></th>
</thead>
<?
    for($I=0;$I< count($lines);$I++)
{
?>
<tr class="TableData" style="background:#fff">
  <td style="text-align: center;"><?=$lines[$I]["FILE_CODE"]?></td>
  <td style="text-align: center;"><?=$lines[$I]["FILE_TITLE"]?></td>
  <td style="text-align: center;"><?=$lines[$I]["SECRET"]?></td>
  <td style="text-align: center;"><?=$lines[$I]["SEND_UNIT"]?></td>
  <td style="text-align: center;"><?=$lines[$I]["SEND_DATE"]?></td>
  <td style="text-align: center;"><?=$lines[$I]["ROLL_ID"]?></td>
  <td style="text-align: center;"><?=$MSG_ERROR[$I]?></td>
</tr>
<?
}
?>
</table>
<?
Message("",sprintf(_("共%d条数据导入成功！"), $SUCCESS_COUNT));
?>
<div align="center">
<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='import_prepare.php';" title="<?=_("返回")?>">
</div>
</body>
</html>
