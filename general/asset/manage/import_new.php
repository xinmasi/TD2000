<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_org.php");
include_once("inc.php");
require_once 'inc/PHPExcel/PHPExcel.php';//������
require_once 'inc/PHPExcel/PHPExcel/Reader/Excel2007.php';//����������ʵ�ֵ�2007��ʽ����
require_once 'inc/PHPExcel/PHPExcel/Reader/Excel5.php';//����excel5�����ܵ���
$query  = "SELECT * from CP_ASSET_REFLECT ";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $CPTL_NO     = $ROW["CPTL_NO"];
    $CPTL_NAME   = $ROW["CPTL_NAME"];
    $TYPE_ID     = $ROW["TYPE_ID"];
    $DEPT_ID     = $ROW["DEPT_ID"];
    $CPTL_VAL    = $ROW["CPTL_VAL"];
    $CPTL_BAL    = $ROW["CPTL_BAL"];
    $DPCT_YY     = $ROW["DPCT_YY"];
    $MON_DPCT    = $ROW["MON_DPCT"];
    $SUM_DPCT    = $ROW["SUM_DPCT"];
    $CPTL_KIND   = $ROW["CPTL_KIND"];
    $PRCS_ID     = $ROW["PRCS_ID"];
    $FINISH_FLAG = $ROW["FINISH_FLAG"];
    $CREATE_DATE = $ROW["CREATE_DATE"];
    $DCR_DATE    = $ROW["DCR_DATE"];
    $FROM_YYMM   = $ROW["FROM_YYMM"];
    $DCR_PRCS_ID = $ROW["DCR_PRCS_ID"];
    $KEEPER      = $ROW["KEEPER"];
    $REMARK      = $ROW["REMARK"];

}
$collegelibrary=array(
    "$CPTL_NO"     => array("fieldName" =>"CPTL_NO"),
    "$CPTL_NAME"   => array("fieldName" =>"CPTL_NAME"),
    "$TYPE_ID"     => array("fieldName" =>"TYPE_ID"),
    "$DEPT_ID"     => array("fieldName" =>"DEPT_ID"),
    "$CPTL_VAL"    => array("fieldName" =>"CPTL_VAL"),
    "$CPTL_BAL"    => array("fieldName" =>"CPTL_BAL"),
    "$DPCT_YY"     => array("fieldName" =>"DPCT_YY"),
    "$MON_DPCT"    => array("fieldName" =>"MON_DPCT"),
    "$SUM_DPCT"    => array("fieldName" =>"SUM_DPCT"),
    "$CPTL_KIND"   => array("fieldName" =>"CPTL_KIND"),
    "$PRCS_ID"     => array("fieldName" =>"PRCS_ID"),
    "$FINISH_FLAG" => array("fieldName" =>"FINISH_FLAG"),
    "$CREATE_DATE" => array("fieldName" =>"CREATE_DATE"),
    "$DCR_DATE"    => array("fieldName" =>"DCR_DATE "),
    "$FROM_YYMM"   => array("fieldName" =>"FROM_YYMM"),
    "$DCR_PRCS_ID" => array("fieldName" =>"DCR_PRCS_ID "),
    "$KEEPER"      => array("fieldName" =>"KEEPER "),
    "$REMARK"      => array("fieldName" =>"REMARK ")
);

if(strtolower(substr($_FILES["EXCEL_FILE"]["name"],-3))!="xls" && strtolower(substr($_FILES["EXCEL_FILE"]["name"],-4))!='xlsx')
{
    Message(_("����"),_("ֻ�ܵ���Excel�ļ�!"));
    Button_Back();
    exit;
}
$cp_info = array();
$FIELDNAME = "";
//��ѯ�Զ�������
$sql = "SELECT FIELDNO,FIELDNAME,TYPEVALUE from FIELDSETTING where TABLENAME='CP_CPTL_INFO' order by ORDERNO";
$cursor= exequery(TD::conn(),$sql);
while($ROW=mysql_fetch_array($cursor))
{
    $cp_info[$ROW['FIELDNAME']] = $ROW;
    $FIELDNAME .= $ROW['FIELDNAME'].",";
}
$FIELDNAME = td_trim($FIELDNAME);

$filePath  = $_FILES['EXCEL_FILE']['tmp_name'];  //D:\myoa\tmp\phpBA97.tmp
$PHPExcel  = new PHPExcel();
$PHPReader = new PHPExcel_Reader_Excel2007();    //�½�excel2007������
if(!$PHPReader->canRead($filePath))
{   //����������ʽ���ϣ��½�excel5������
    $PHPReader = new PHPExcel_Reader_Excel5();
    if(!$PHPReader->canRead($filePath))
    {   //��������ԣ����û��excel
        echo _("��ʽ������Excel");
        return ;
    }
}
$PHPExcel     = $PHPReader->load($filePath);
$currentSheet = $PHPExcel->getSheet(0);  //ȡ��excel��������ҳ��
/**ȡ��һ���ж�����*/
$allColumn = $currentSheet->getHighestColumn();

/**ȡ��һ���ж�����*/
$allRow = $currentSheet->getHighestRow();
for($currentRow = 1;$currentRow<=$allRow;$currentRow++)
{	//��ȡexcel�ļ����ݵ�����
    for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++)
    {
        $address = $currentColumn.$currentRow;
        $abc=iconv("utf-8", MYOA_CHARSET, $currentSheet->getCell($address)->getValue());


        /*if($currentSheet->getCell($address)->getDataType()==PHPExcel_Cell_DataType::TYPE_NUMERIC){
            $cellstyleformat=$currentSheet->getCell($address)->getParent()->getStyle( $currentSheet->getCell($address)->getCoordinate() )->getNumberFormat();
            $formatcode=$cellstyleformat->getFormatCode();
            if (preg_match('/^(\[\$[A-Z]*-[0-9A-F]*\])*[hmsdy]/i', $formatcode)) {
                $abc=gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($currentSheet->getCell($address)->getValue()));
            }else{

            }
        }*/
        $arr[$currentColumn]=trim($abc);
    }
    $lines[]=$arr;
}
//echo "<pre>";print_r($lines);exit;
//echo "<pre>";print_r($collegelibrary);
$thanArr = headThan($lines, $collegelibrary);
//print_r($lines);exit;
$cooperation = array(0 => _("��"), 1=>_("��"));
$DATE_NOW=date('Y-m-d');
$liness=$lines[0];
if($liness[A]!='�ʲ����')
{
    Message("",_("�ֶ�δƥ�䣬����ʧ�ܣ�"));
    echo '<div align="center"><input type="button" class="BigButton" value='._("����").' onClick="location=\'import.php\'"></div>';
    exit;
}

$data = array();
foreach($lines as $rowkey => $value)
{
    if($rowkey == 0)
    {
        foreach($lines[0] as $k=>$v)
        {
            if($v=="������")
            {
                $key1 = $k;
            }
            if($v=="�ʲ����")
            {
                $key2 = $k;
            }
            if(find_id($FIELDNAME,$v) && $FIELDNAME!="")
            {
                $data[] = array(
                    'FIELDNO'   => $cp_info[$v]['FIELDNO'],
                    'ROWKEY'    => $k,
                    'ITEM_DATE' => ''
                );
            }
        }
        continue;
    }
//   $value1=array_merge($lines, $collegelibrary);
//   print_r($value);exit;
    $query1="select * from CP_CPTL_INFO where CPTL_NO='$value[A]'";
    $cursor1=exequery(TD::conn(),$query1);
    $count=mysql_num_rows($cursor1);

    if($FIELDNAME!="")
    {
        //�Զ���ѭ����ֵ
        foreach($data as $key =>$val)
        {
            if($value[$val['ROWKEY']]!="")
            {
                $data[$key]['ITEM_DATE'] = $value[$val['ROWKEY']];
            }
            else
            {
                unset($data[$key]);
            }
        }
    }

    if($count > 0)
    {
        Message("",_("�ʲ�����ظ�������ʧ�ܣ�"));
        echo '<div align="center"><input type="button" class="BigButton" value='._("����").' onClick="location=\'import.php\'"></div>';
        exit;
    }
    $query2 = "SELECT * FROM cp_asset_type WHERE TYPE_NAME='{$value[$key2]}'";
    $cursor2=exequery(TD::conn(),$query2);
    if(!mysql_affected_rows()>0)
    {
        Message("",_("�ʲ����δƥ�䣬����ʧ�ܣ�"));
        echo '<div align="center"><input type="button" class="BigButton" value='._("����").' onClick="location=\'import.php\'"></div>';
        exit;
    }
    $query3 = "SELECT * FROM user WHERE USER_NAME='{$value[$key1]}'";
    $cursor3=exequery(TD::conn(),$query3);
    if(!mysql_affected_rows()>0)
    {
        Message("",_("������δƥ�䣬����ʧ�ܣ�"));
        echo '<div align="center"><input type="button" class="BigButton" value='._("����").' onClick="location=\'import.php\'"></div>';
        exit;
    }


//	$query="SELECT * FROM CP_CPTL_INFO";
//	$cursor= exequery(TD::conn(),$query);
//	if($ROW=mysql_fetch_array($cursor))
//	{
//		$CPTL_NO_ALL.=$ROW['CPTL_NO'].",";
//	}
//	$CPTL_NO_ALL=substr($CPTL_NO_ALL,0,-1);
//
//	$query="SELECT * FROM CP_CPTL_INFO where !FIND_IN_SET(CPTL_NO, '$CPTL_NO_ALL')";
//	$cursor= exequery(TD::conn(),$query);
//	if($ROW=mysql_fetch_array($cursor))
//	{
    $sql = 'INSERT INTO CP_CPTL_INFO (CREATE_DATE,' ;
    $sql .= fieldNameStr($thanArr);
//	$sql .= ",CREATER_DATE";
    $sql .= ") values ('$DATE_NOW ',";

    foreach($thanArr as $keyThan => $valueThan)
    {
        if($keyThan == 'ch')
        {
            continue;
        }
        //���ڸ�ʽת��
        if($valueThan['chName'] == $FROM_YYMM)
        {
            $value[$keyThan] = excelTime($value[$keyThan]);
        }

        if($valueThan['chName'] == $DEPT_ID)
        {
            $value[$keyThan] = deptchange($value[$keyThan]);
        }
        if($valueThan['chName'] == $TYPE_ID)
        {
            $value[$keyThan] = typechange($value[$keyThan]);
        }
        if($valueThan['chName'] == $CPTL_KIND)
        {
            if($value[$keyThan]==_("�ʲ�"))
            {
                $value[$keyThan] = 01;
            }
            if($value[$keyThan]==_("����"))
            {
                $value[$keyThan] = 02;
            }
        }
        if($valueThan['chName'] == $PRCS_ID)
        {
            $value[$keyThan]=prcschange($value[$keyThan]);
        }

        $sql .= "'$value[$keyThan]',";

    }
    $sql = substr($sql,0,-1);

    $sql .= ")";
    exequery(TD::conn(),$sql);

    $fridid = mysql_insert_id();
    if($fridid && $data)
    {
        $sql1 = "INSERT INTO field_date (TABLENAME,FIELDNO,IDENTY_ID,ITEM_DATE) values";
        //����SQL
        foreach($data as $keys =>$vvl)
        {
            $sql1 .= " ('CP_CPTL_INFO','{$vvl["FIELDNO"]}','$fridid','{$vvl["ITEM_DATE"]}'),";
        }
        $sql1 = td_trim($sql1);
        exequery(TD::conn(),$sql1);
    }
//  }
}

function fieldNameStr($thanArr)
{
    foreach($thanArr as $v)
    {
        $str .= $v['fieldName'] . ',';
    }
    $str = substr($str, 0, -2);
    return $str;
}
function headThan($lines, $conn)
{
    foreach($conn as $key => $value)
    {
        if($newkey = array_search($key, $lines[0]))
        {
            $newArr[$newkey]['fieldName']           =  $conn[$key]['fieldName'];
            $newArr[$newkey]['chName']              =  $key;
            $newArr['ch'][$conn[$key]['fieldName']] =  $key;
        }
    }
    if(!is_array($newArr))
    {
        Message("",_("����ʧ�ܣ��ļ��������û�����ù����ֶΣ�"));
        echo '<div align="center"><input type="button" class="BigButton" value='._("����").' onClick="location=\'import.php\'"></div>';
        exit;
    }
    else
    {
        ksort($newArr);
        return $newArr;
    }

}

function excelTime($date, $time=false)
{
    if(is_numeric($date))
    {
        $jd = GregorianToJD(1, 1, 1970);
        $gregorian = JDToGregorian($jd+intval($date)-25569);
        $date = explode('/',$gregorian);
        $date_str = str_pad($date[2],4,'0', STR_PAD_LEFT)
            ."-".str_pad($date[0],2,'0', STR_PAD_LEFT)
            ."-".str_pad($date[1],2,'0', STR_PAD_LEFT)
            .($time?" 00:00:00":'');
        return $date_str;
    }
    return $date;
}

?>
<div align="center">
    <?
    Message("",_("����ɹ���"));
    ?>
    <input type="button" class="BigButton" value="<?=_("����")?>" onClick="location='index1.php'">
</div>
