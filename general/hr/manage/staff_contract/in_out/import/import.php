<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = $title._("导入");
include_once("inc/header.inc.php");
include_once("general/crm/inc/header.php");
include_once("inc/utility_sms1.php");
include_once("import_config.php");
include_once("import_func.func.php");
//$roodir=dirname(dirname(__FILE__));

require_once 'inc/PHPExcel/PHPExcel.php';//包含类
require_once 'inc/PHPExcel/PHPExcel/Reader/Excel2007.php';//包含读功能实现的2007格式的类
require_once 'inc/PHPExcel/PHPExcel/Reader/Excel5.php';//包含excel5读功能的类
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
/**************************
type类型说明
V普通
N数字
D日期
S是否
U系统用户
DEPT部门
L合同类型
X合同属性
Z合同状态
 **************************/
ini_set("memory_limit","2048M");

$filePath  		 = $file_path;
//Excel处理
$PHPExcel 		 = new PHPExcel();
$PHPReader 		 = new PHPExcel_Reader_Excel2007();    //新建excel2007读对象

if(!$PHPReader->canRead($filePath)){      //如果读对象格式不合，新建excel5读对象
    $PHPReader = new PHPExcel_Reader_Excel5();
    if(!$PHPReader->canRead($filePath)){      //如果还不对，输出没有excel
        echo _("格式不符合Excel");
        return ;
    }
}
$PHPExcel = $PHPReader->load($filePath);
$currentSheet = $PHPExcel->getSheet(0);  //取得excel工作“分页”

/**取得一共有多少列*/
$allColumn = $currentSheet->getHighestColumn();

/**取得一共有多少行*/
$allRow = $currentSheet->getHighestRow();

for($currentRow = 1;$currentRow<=$allRow;$currentRow++){//获取excel文件数据到数组
    for($currentColumn_num=1,$i=1; $i <= excel_str_num($allColumn); $currentColumn_num++,$i++){
        $currentColumn=excel_num_str($currentColumn_num);
        $address = $currentColumn.$currentRow;
        $abc=iconv("utf-8", MYOA_CHARSET, $currentSheet->getCell($address)->getValue());
        //echo $currentSheet->getCell($address)->getValue()."\t";
        //$lines[]=$currentSheet->getCell($address)->getValue();
        $arr[$currentColumn]=trim($abc);
    }
    $lines[]=$arr;
}
$head_arr		= stripslashes($head_arr);
$headArr	    = unserialize($head_arr);//表头处理

$connArr 	    = fieldConnArr($headArr,$conn);//控制数组与Excel关联
$codeArr    	= codeArr($connArr);//得到所有系统代码数组
$foreignKeyArr  = foreignKeyArr($connArr);//得到外键表的数组


function select($item,$table){
    $str="select ".$item." from ".$table;

    $x=0;
    $query=db_query($str, TD::conn());

    while($row=mysql_fetch_array($query)){
        $aa[$x]=$row[$item];
        $x+=1;
    }
    return $aa;

}

$userArr		= select("USER_NAME","USER");//得到系统用户数组


$deptArr		= select("DEPT_NAME","department");//得到系统部门数组


$time			= Date("Y-m-d H:i:s");
$insert_success = 0;
$insert_false 	= 0;
$indexField		= indexField();

array_shift($lines);
foreach($lines as $rowKey => $rowValue){
    $marked = 0;
    $sql  	= 'insert into '.$dbName.' (';
    $sql   .= fieldNameStr($connArr);
    $sql   .= ') values (';
    foreach($connArr as $connKey => $connValue){
        $rowValue[$connKey] = str_replace( "'", "",$rowValue[$connKey]);
        $type = explode('~',$connValue['fielddatatype']);
        if($type[1] == 'M'){
            if($rowValue[$connKey] == ''){
                $error_reason .= $connValue['title'] . _("列不能为空,");
                $error_line	  .= ($rowKey+2).',';
                $marked 	   = 1;
                break;
            }
        }


        if($rowValue[$connKey] != ""){
            $rowValue[$connKey]=trim($rowValue[$connKey]);
            if($type[0] == 'N'){
                if(!is_numeric($rowValue[$connKey])){
                    if(substr(trim($rowValue[$connKey]),0,1)=="#"){
                        $rowValue[$connKey]="";
                    }
                    else{

                        $error_reason .= $connValue['title'] . _("列数据类型错误要求只能是数字,");
                        $error_line	  .= ($rowKey+2).',';
                        $marked 	   = 1;
                        break;}

                }
            }elseif($type[0] == 'D'){

                $rowValue[$connKey] = excelTime($rowValue[$connKey]);
                $rowValue[$connKey]=trim($rowValue[$connKey]);
                if(!is_date($rowValue[$connKey])){
                    // echo substr($rowValue[$connKey],0,1);
                    if(substr(trim($rowValue[$connKey]),0,1)=="#"){
                        $rowValue[$connKey]="";
                    }
                    else{

                        $error_reason .= $connValue['title'] ._("列数据类型错误要求只能是日期格式为xxxx-xx-xx,");
                        $error_line	  .= ($rowKey+2).',';
                        $marked 	   = 1;
                        break;
                    }
                }
            }
            else if($type[0]=='B'){
                $rowValue[$connKey]="";



            }
            else if($type[0]=='V'){
                $rowValue[$connKey]=trim($rowValue[$connKey]);
                if(substr(trim($rowValue[$connKey]),0,1)=="#"){
                    $rowValue[$connKey]="";
                }



            }

            elseif($type[0] == 'Z'){
                $rowValue[$connKey]=trim($rowValue[$connKey]);
                if($rowValue[$connKey]==_("试用中")){$rowValue[$connKey]="1"; }
                else if($rowValue[$connKey]==_("已转正")){$rowValue[$connKey]="2"; }
                else if($rowValue[$connKey]==_("已解除")){$rowValue[$connKey]="3"; }
                else if(substr(trim($rowValue[$connKey]),0,1)=="#"){$rowValue[$connKey]="";}
                else{
                    $error_reason .= $connValue['title'] . _('列数据类型错误要求只能是_("试用中")或者_("已转正")和_("已解除"),');
                    $error_line	  .= ($rowKey+2).",";
                    $marked 	   = 1;
                    break;
                }

            }elseif($type[0] == 'X'){//外键判定
                $rowValue[$connKey]=trim($rowValue[$connKey]);
                if($rowValue[$connKey]==_("有固定期限")){$rowValue[$connKey]="1"; }
                else if($rowValue[$connKey]==_("无固定期限")){$rowValue[$connKey]="2"; }
                else if(substr(trim($rowValue[$connKey]),0,1)=="#"){$rowValue[$connKey]="";}
                else{
                    $error_reason .= $connValue['title'] . _('列数据类型错误要求只能是_("有固定期限")或者_("无固定期限"),');
                    $error_line	  .= ($rowKey+2).",";
                    $marked 	   = 1;
                    break;
                }

            }elseif($type[0] == 'L'){
                $rowValue[$connKey]=trim($rowValue[$connKey]);
                if($rowValue[$connKey]==_("劳动合同")){$rowValue[$connKey]="1"; }
                else if($rowValue[$connKey]==_("保密合同")){$rowValue[$connKey]="2"; }
                else if($rowValue[$connKey]==_("其他")){$rowValue[$connKey]="3"; }
                else if(substr(trim($rowValue[$connKey]),0,1)=="#"){$rowValue[$connKey]="";}
                else{
                    $error_reason .= $connValue['title'] . _('列数据类型错误要求只能是_("劳动合同")或者_("保密合同") 和 _("其他"),');
                    $error_line	  .= ($rowKey+2).",";
                    $marked 	   = 1;
                    break;
                }


            }elseif($type[0] == 'S'){
                $rowValue[$connKey]=trim($rowValue[$connKey]);
                if($rowValue[$connKey] == _("是") || $rowValue[$connKey] == _("否")){
                    if($rowValue[$connKey] == _("是")){
                        $rowValue[$connKey] = 1;
                    }elseif($rowValue[$connKey] == _("否")){
                        $rowValue[$connKey] = 0;
                    }
                }else{
                    $error_reason .= $connValue['title'] . _('列数据类型错误要求只能是_("是")或者_("否"),');
                    $error_line	  .= ($rowKey+2).",";
                    $marked 	   = 1;
                    break;
                }
            }elseif($type[0] == 'U'){
                $rowValue[$connKey]=trim($rowValue[$connKey]);
                if(!in_array($rowValue[$connKey],$userArr)){
                    echo $rowValue[$connKey];print_r($userArr);exit;
                    $error_reason .= $connValue['title'] . _("列数据已超出外键的外理范围,");
                    $error_line	  .=($rowKey+2).',';
                    $marked 	   = 1;
                    break;
                }else{
                    //$rowValue[$connKey] = $userArr[$connValue['fieldName']][$rowValue[$connKey]];
                    $ss="select USER_ID from USER where USER_NAME='".$rowValue[$connKey]."'";
                    //echo $ss;
                    $result=db_query($ss, TD::conn());
                    if($row=mysql_fetch_array($result)){
                        $rowValue[$connKey]=$row["USER_ID"];
                    }

                }
            }elseif($type[0] == 'CB'){

                $rowValue[$connKey]=$_SESSION["LOGIN_DEPT_ID"];


            }
            elseif($type[0] == 'T'){

                $rowValue[$connKey]=$_SESSION["LOGIN_USER_ID"];


            }
            elseif($type[0] == 'HB'){
                $ww="select * from  ".$dbName."  where STAFF_CONTRACT_NO ='".$rowValue[$connKey]."'";
                $result=db_query($ww, TD::conn());
                $row=mysql_numrows($result);
                if($row>0){
                    $error_reason .= $connValue['title'] . _("合同编号不能重复,");
                    $error_line	  .=($rowKey+2).',';
                    $marked 	   = 1;
                    break;
                }
            }
            elseif($type[0] == 'US'){
                $rowValue[$connKey]=$rowValue[$connKey];
            }

        }
        $sql .= "'".$rowValue[$connKey]."',";
    }
    if($marked == 1){
        $insert_false++;
        $marked = 0;
        continue;
    }

    $sql=substr($sql,0,-1);
    $sql.=")";





    if(db_query($sql, TD::conn())){
        $insert_success++;
        if($rowValue["AA"]!="" && is_date($rowValue["AA"]))
        {
            $contract_id=mysql_insert_id();
            $user_id=$rowValue["D"];
            if($user_id=="")
            {
                $user_id=$rowValue["E"];
            }
            $query1="SELECT USER_NAME FROM USER WHERE USER_ID='".$user_id."'";
            $CUR=exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($CUR))
            {
                $user_name=$ROW["USER_NAME"];
            }
            $content=$user_name._("，您的合同已到期");
            $url="hr/manage/staff_contract/contract_detail.php?CONTRACT_ID=".$contract_id;
            send_sms1($rowValue["AA"],$_SESSION["LOGIN_USER_ID"],$user_id,63,$content,$url,$contract_id);
        }
    }else{
        $errorArr = explode(' ', mysql_error());
        $fieldName = $indexField[($errorArr[5]-1)];
        if(mysql_errno() == 1062){
            $error_reason	.= fieldAsTitle($fieldName). _("为唯一字段不能重复,");
            $error_line	 	.= ($rowKey+2).',';
        }else{
            $error_reason	.= fieldAsTitle($fieldName). _("SQL语句错误:").mysql_error().',';
            $error_line	 	.= ($rowKey+2).',';
        }
        $insert_false++;
    }

}


?>
<br>
<div align = 'center'>
    <table cellspacing='0' cellpadding='' width="90%" align='center' border='0' style='margin-top:4px;margin-bottom: 4px;'>
        <tr>
            <td class='blockHeader'><?=_("成功插入条数")?></td>
            <td class='blockHeader'><?=_("插入失败条数")?></td>
        </tr>
        <tr>
            <td class='efCellCtrl'><?=$insert_success?></td>
            <td class='efCellCtrl'><?=$insert_false?></td>
        </tr>
        <tr >
            <td colspan = '2' align = 'right' class='efCellCtrl'>
                <form name='form1' method='post' enctype='multipart/form-data' action='export.php'>
                    <input type='hidden' name='error_reason' value='<?=$error_reason?>'>
                    <input type='hidden' name='error_line' value='<?=$error_line?>'>
                    <input type='hidden' name='file_path' value='<?=$filePath?>'>
                    <?
                    if($insert_false != 0){
                        ?>
                        <input type='button' class="SmallButton"  value="<?=_("导出错误报告")?>" onclick="submit();">
                        <?
                    }
                    ?>
                </form>
            </td>
        </tr>
    </table>
</div>
<br>
<div align = 'center' class = "small"><?=_("插入情况详细表")?></div>
<?
echo $TABLE;
?>

