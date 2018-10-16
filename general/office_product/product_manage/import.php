<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE =_("导入数据");
include_once ("inc/header.inc.php");

?>

<body class="bodycolor">

<?
if (strtolower ( substr ( $FILE_NAME, - 3 ) ) != "xls" && strtolower ( substr ( $FILE_NAME, - 4 ) ) !='xlsx') {
    Message(_("错误"), _("只能导入Excel文件!"));
    Button_Back ();
    exit ();
}
if (MYOA_IS_UN == 1)
    $title = array (
        "DEPOSITORY" => "OFFICE_DEPOSITORY",
        "TYPE" => "OFFICE_PROTYPE",
        "NAME" => "PRO_NAME",
        "PRODUCT_TYPE" => "OFFICE_PRODUCT_TYPE",
        "PRO_CODE" => "PRO_CODE",
        "PRICE" => "PRO_PRICE",
        "DESCRIBE" => "PRO_DESC",
        "MEASURE_UNIT" => "PRO_UNIT",
        "SUPPLIER" => "PRO_SUPPLIER",
        "LOWSTOCK" => "PRO_LOWSTOCK",
        "MAXSTOCK" => "PRO_MAXSTOCK",
        "STOCK" => "PRO_STOCK",
        "CREATOR" => "PRO_CREATOR",
        "MANAGER" => "PRO_MANAGER",
        "AUDITER" => "PRO_AUDITER"
    );
else
    $title = array (
            _("办公用品库") => "OFFICE_DEPOSITORY",
            _("办公用品类别") => "OFFICE_PROTYPE",
            _("办公用品名称") => "PRO_NAME",
            _("登记类型") => "OFFICE_PRODUCT_TYPE",
            _("编码") => "PRO_CODE",
            _("单价") => "PRO_PRICE",
            _("规格/型号") => "PRO_DESC",
            _("计量单位") => "PRO_UNIT",
            _("供应商") => "PRO_SUPPLIER",
            _("最低警戒库存") => "PRO_LOWSTOCK",
            _("最高警戒库存") => "PRO_MAXSTOCK",
            _("当前库存") => "PRO_STOCK",
            _("创建人") => "PRO_CREATOR",
            _("登记权限(用户)") => "PRO_MANAGER",
            _("审批权限(用户)") => "PRO_AUDITER"
    );

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];
$data = file_get_contents($EXCEL_FILE);
// $lines=CSV2Array($data, $title);
if (! $data) {
    Message(_("错误"), _("打开文件错误!"));
    Button_Back();
    exit();
}
$ROW_COUNT = 0;
$SUCC_COUNT = 0;

require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE,$title,$fieldAttr);

$OFFICE_DEPOSITORY = "";
$OFFICE_PROTYPE = "";
$MSG_ERROR = array ();
$lines = array ();

/*
 * 这里补全一个逻辑，如果用户导入的数据出现重复或者导入的数据与数据库的条目出现重复则该条目导入失败。 添加新数组，old_pro_array; author yzx（yzx.tongdya2000.com）
 */
$old_pro_array = array ();
$old_pro_array_item = array ();
$old_pro_query = "SELECT PRO_NAME, PRO_CODE, OFFICE_PROTYPE FROM office_products";
$old_pro_query_cursor = exequery ( TD::conn (), $old_pro_query );

while ( $ROW = mysql_fetch_array ( $old_pro_query_cursor ) ) {
    $old_pro_array_item ['pro_name'] = $ROW ['PRO_NAME'];
    $old_pro_array_item ['office_protype'] = $ROW ['OFFICE_PROTYPE'];
    $old_pro_array_item ['depository_name'] = '';
    $old_pro_array_item ['type_name'] = '';
    $old_pro_array [] = $old_pro_array_item;
}

$old_pro_type_des = array ();
$query_type_des = "SELECT OFFICE_TYPE.ID,TYPE_NAME, DEPOSITORY_NAME FROM OFFICE_TYPE LEFT OUTER JOIN OFFICE_DEPOSITORY ON OFFICE_TYPE.TYPE_DEPOSITORY = OFFICE_DEPOSITORY.ID";

$query_type_des_cursor = exequery ( TD::conn (), $query_type_des );
$i = 0;
while ( $ROW = mysql_fetch_array ( $query_type_des_cursor ) ) {
    $old_pro_type_des [$i] ['type_id'] = $ROW ['ID'];
    $old_pro_type_des [$i] ['type_name'] = $ROW ['TYPE_NAME'];
    $old_pro_type_des [$i] ['depository_name'] = $ROW ['DEPOSITORY_NAME'];
    $i ++;
}

$old_pro_array_all = array ();

foreach ( $old_pro_array as $old_pro_array_key => $old_pro_array_item ) {
    if ($old_pro_array_item ['office_protype'] == NULL || $old_pro_array_item ['office_protype'] == '') {
        $old_pro_array_item ['depository_name'] = '';
    } else {
        foreach ( $old_pro_type_des as $type_array_key => $type_array_item ) {
            if ($old_pro_array_item ['office_protype'] == $type_array_item ['type_id']) {
                $old_pro_array_item ['type_name'] = $type_array_item ['type_name'];
                $old_pro_array_item ['depository_name'] = $type_array_item ['depository_name'];
            }
        }
    }
    $old_pro_array_all [] = $old_pro_array_item;
}

/* -----end----- */
while($line = $objExcel->getNextRow())
{
    
    $lines [] = $line;
    $STR_VALUE = "";
    $STR_KEY = "";
    $STR_UPDATE = "";
    $success = 1;
    foreach ($line as $key => $value)
    {
        if($key == "OFFICE_DEPOSITORY")
        {
            $STR_KEY .= "";
        }else
        {
            $STR_KEY .= $key . ",";
            $STR_UPDATEKEY .= $key . ",";
        }
        if(($key != "OFFICE_PROTYPE") && ($key != "OFFICE_DEPOSITORY") && ($key != "PRO_NAME") && ($key != "PRO_DESC") && ($key != "PRO_PRICE"))
        {
            if($key == "OFFICE_PRODUCT_TYPE")
            {
                if($value == "")
                {
                    $MSG_ERROR [$ROW_COUNT] = "<font color=red>" . _ ("办公用品登记类型为空，未导入") . "</font>";
                    $ROW_COUNT ++;
                    $success = 0;
                    continue 2;
                }elseif($value!= _("领用") && $value!= _("借用"))
                {
                    $MSG_ERROR [$ROW_COUNT] = "<font color=red>" ._("办公用品登记类型错误，未导入") . "</font>";
                    $ROW_COUNT ++;
                    $success = 0;
                    continue 2;
                }else
                {
                    $value==_("领用")?$OFFICE_PRODUCT_TYPE=1:$OFFICE_PRODUCT_TYPE=2;
                    $STR_VALUE .= "'$OFFICE_PRODUCT_TYPE'" . ",";
                    $STR_UPDATE .= "$key='$OFFICE_PRODUCT_TYPE',";
                }
                
            }
            elseif($key == "PRO_STOCK")
            {
                if($value == "")
                {
                    $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("库存为空，未导入")."</font>";
                    $ROW_COUNT ++;
                    $success = 0;
                    continue 2;
                }elseif(!preg_match("/^[0-9]*$/i",$value))
                {
                    $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("库存数有误，未导入")."</font>";
                    $ROW_COUNT ++;
                    $success = 0;
                    continue 2;
                }
                $PRO_STOCK  = $value;
                $STR_VALUE .= "'$value'" . ",";
                $STR_UPDATE .= "$key='$value',";
            }elseif($key == "PRO_CREATOR")
            {
                if($value != "")
                {
                    $sql = "SELECT USER_ID FROM user WHERE USER_NAME = '$value'";
                    $cursor = exequery(TD::conn(),$sql);
                    if($arr = mysql_fetch_array($cursor))
                    {
                        $USER_ID = $arr['USER_ID'];
                        $STR_VALUE .= "'$USER_ID'" . ",";
                        $STR_UPDATE .= "$key='$USER_ID',";
                    }else
                    {
                        $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("创建人有误，未导入")."</font>";
                        $ROW_COUNT ++;
                        $success = 0;
                        continue 2;
                    }
                }else
                {
                    $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("创建人为空，未导入")."</font>";
                    $ROW_COUNT ++;
                    $success = 0;
                    continue 2;
                }
            }
            elseif($key == "PRO_MANAGER")
            {
                if($value != "")
                {   
                    $value = rtrim(str_replace("，",",",$value),',');
                    $count = count(explode(",",$value));
                    $sql2 = "SELECT USER_ID FROM user WHERE find_in_set(USER_NAME,'$value')";
                    $cursor2 = exequery(TD::conn(),$sql2);
                    if(mysql_num_rows($cursor2)==$count)
                    {
                        while($arr2 = mysql_fetch_array($cursor2))
                        {
                            $USER_ID2 .= $arr2['USER_ID'].",";    
                        }
                        $USER_ID2=rtrim($USER_ID2,',');
                        $STR_VALUE .= "'$USER_ID2'".",";
                        $STR_UPDATE .= "$key='$USER_ID2',";
                    }
                    else
                    {
                        $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("登记权限(用户)有误，未导入")."</font>";
                        $ROW_COUNT ++;
                        $success = 0;
                        continue 2;
                    }
                    
                    
                }else
                {
                    $STR_VALUE .= "''".",";
                    $STR_UPDATE .= "$key='',";
                }
            }
            elseif($key == "PRO_AUDITER")
            {
                if($value != "")
                {
                    $sql1 = "SELECT USER_ID FROM user WHERE USER_NAME = '$value'";
                    $cursor1 = exequery(TD::conn(),$sql1);
                    if($arr1 = mysql_fetch_array($cursor1))
                    {
                        $USER_ID1 = $arr1['USER_ID'];
                        $STR_VALUE .= "'$USER_ID1'".",";
                        $STR_UPDATE .= "$key='$USER_ID1',";
                    }else
                    {
                        $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("审批权限(用户)有误，未导入")."</font>";
                        $ROW_COUNT ++;
                        $success = 0;
                        continue 2;
                    }
                }else
                {
                    $STR_VALUE .= "''".",";
                    $STR_UPDATE .= "$key='',";
                }
            }
            else
            {
                $STR_VALUE .= "'$value'".",";
                $STR_UPDATE .= "$key='$value',";
            }    
        }
        else
        {
            if($key == "PRO_DESC")
            {
                if($value == "")
                {
                    $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("规格/型号为空，未导入")."</font>";
                    $ROW_COUNT ++;
                    $success = 0;
                    continue 2;
                }
                $PRO_DESC   = $value;
                $STR_VALUE .= "'$value'".",";
            }
            if($key == "PRO_PRICE")
            {
                if($value != "" && !preg_match("/^[0-9]+(.[0-9]{1,3})?$/i",$value))
                {
                    $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("单价输入有误，未导入")."</font>";
                    $ROW_COUNT ++;
                    $success = 0;
                    continue 2;
                }
                $PRO_PRICE  = $value;
                $STR_VALUE .= "'$value'".",";
            }
            if($key == "OFFICE_DEPOSITORY")
            {
                if($value == "")
                {
                    $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("办公用品库为空，未导入")."</font>";
                    $ROW_COUNT ++;
                    $success = 0;
                    continue 2;
                }
            }
            if($key == "OFFICE_PROTYPE"){
                if($value == "")
                {
                    $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("办公用品类别为空，未导入")."</font>";
                    $ROW_COUNT ++;
                    $success = 0;
                    continue 2;
                }
                $OFFICE_PROTYPE = $value;
                $temp = $line ['OFFICE_DEPOSITORY'];
                $Tquery = "SELECT a.ID FROM OFFICE_TYPE a left outer join OFFICE_DEPOSITORY b on a.TYPE_DEPOSITORY=b.ID WHERE a.TYPE_NAME='$value' and b.DEPOSITORY_NAME = '$temp'";
                $Tcursor = exequery(TD::conn(),$Tquery);
                
                if($TROW = mysql_fetch_array($Tcursor))
                {
                    $STR_VALUE .= "'$TROW[0]'".",";
                    $PROTYPE = $TROW [0];
                }else
                {
                    $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("办公用品类别:".$value."未匹配或不存在，未导入")."</font>";
                    $ROW_COUNT ++;
                    $success = 0;
                    continue 2;
                }
            }
            
            if($key == "PRO_NAME")
            {
                if($value == "")
                {
                    $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("办公用品名称为空，未导入")."</font>";
                    $ROW_COUNT ++;
                    $success = 0;
                    continue 2;
                }
                $PRO_NAME = $value;
                $STR_VALUE .= "'$value'".",";
            }
            if($key == "OFFICE_DEPOSITORY")
            {
                $OFFICE_DEPOSITORY = $value;
                continue;
            }
        }
    }
    // 判断用户导入的信息是否有重复，是否和数据库中的数据冲突 -----YZX
    /*
     * foreach($old_pro_array_all as $item => $item_array) { if($OFFICE_PROTYPE==NULL||$OFFICE_PROTYPE=='') { if($item_array['pro_name']==$PRO_NAME &&$item_array['pro_code']==$PRO_CODE) { $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("有相同名称相同编码的物品在未分类用品中，未导入")."</font><br/>"; $success = 0; break; } } else { if($item_array['pro_name']==$PRO_NAME &&$item_array['pro_code']==$PRO_CODE&&$item_array['type_name']==$OFFICE_PROTYPE&&$item_array['depository_name']==$OFFICE_DEPOSITORY) { $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("有相同名称相同编码的物品在当前的库当前类别下，未导入")."</font><br/>"; $success = 0; break; } } }
     */
    
    if(substr($STR_KEY, - 1) == ",")
    {
        $STR_KEY = substr($STR_KEY, 0, - 1);
    }
    
    if(substr($STR_VALUE, - 1 ) == ",")
    {
        $STR_VALUE = substr($STR_VALUE, 0, - 1);
    }
    
    /*
     * $array = explode(",",$STR_VALUE); if($array[0]=="''") { continue; }
     */
    // 更新
    $querys = "SELECT * FROM office_products WHERE PRO_NAME='".$PRO_NAME."' AND OFFICE_PROTYPE='$PROTYPE' AND PRO_PRICE='$PRO_PRICE'  AND PRO_DESC = '$PRO_DESC'";
    $cursors = exequery(TD::conn (),$querys);
    if($ROW = mysql_fetch_array($cursors))
    {
        $STR_UPDATE = td_trim($STR_UPDATE);
        $MSG_ERROR [$ROW_COUNT] = "<font color=red>"._("已有信息，资料已更新")."</font>";
        $query1 = "UPDATE office_products SET ".$STR_UPDATE." WHERE PRO_ID = '{$ROW['PRO_ID']}'";
        $cursor1 = exequery(TD::conn (), $query1);
        $SUCC_COUNT ++;
    }else
    {
        $sql_id = "SELECT OFFICE_TYPE_ID FROM office_depository WHERE DEPOSITORY_NAME = '$OFFICE_DEPOSITORY'";
        $cur_id = exequery(TD::conn(),$sql_id);
        if($arr = mysql_fetch_array($cur_id))
        {
            $OFFICE_TYPE_ID = $arr['OFFICE_TYPE_ID'];
        }
        
        $coding = "SELECT * FROM office_products WHERE OFFICE_PROTYPE in ($OFFICE_TYPE_ID) AND PRO_NAME = '$PRO_NAME' AND PRO_DESC = '$PRO_DESC' AND ALLOCATION = '0'";
        $code   = exequery(TD::conn(),$coding);
        $COUNT  = mysql_num_rows($code);
        if($COUNT)
        {
            $MSG_ERROR [$ROW_COUNT] = "<font color=red>" ._("同库下不允许出现同名称,规格/型号相同的办公用品，未导入"). "</font>";
            $success = 0;
            $ROW_COUNT ++;
        }else
        {
            $query = "INSERT INTO office_products (".$STR_KEY.") VALUES (".$STR_VALUE.")";
            $result = exequery(TD::conn (), $query);
            $MSG_ERROR[$ROW_COUNT] = _("导入成功");
            $SUCC_COUNT ++;
        }
    }
    if ($result) {
        $TRANS_DATE = date("Y-m-d",time());
        $product_insert_id = mysql_insert_id();
        $PRO_STOCK_ZX = $line["PRO_STOCK"];
        $PRO_PRICE_ZX = $line['PRO_PRICE'];
        $query = "INSERT INTO OFFICE_TRANSHISTORY (PRO_ID,BORROWER,TRANS_FLAG,TRANS_QTY,PRICE,TRANS_DATE,OPERATOR) VALUES ('$product_insert_id','','6','$PRO_STOCK_ZX','$PRO_PRICE_ZX','$TRANS_DATE','".$_SESSION ["LOGIN_USER_ID"]."')";
        exequery ( TD::conn (), $query );
        
        $old_pro_array_all [] = array ( // 将已经插入的信息增加到匹配表中 ---YZX
                "pro_name" => $line ['PRO_NAME'],
                "pro_code" => $line ['PRO_CODE'],
                "depository_name" => $line ['OFFICE_DEPOSITORY'],
                "type_name" => $line ['OFFICE_PROTYPE'] 
        );
    }
    $ROW_COUNT ++;
}
// END
if (file_exists ( $EXCEL_FILE ))
    @unlink ( $EXCEL_FILE );
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css">
<br>
    <table class="table table-bordered center table-hover">
            <thead style="background: #aadcf5;">
            <td><?=_("办公用品库")?></td>
            <td><?=_("办公用品类别")?></td>
            <td><?=_("办公用品名称")?></td>
            <td><?=_("登记类型")?></td>
            <td><?=_("单价")?></td>
            <td><?=_("规格/型号")?></td>
            <td><?=_("计量单位")?></td>
            <td><?=_("供应商")?></td>
            <td><?=_("最低警戒库存")?></td>
            <td><?=_("最高警戒库存")?></td>
            <td><?=_("当前库存")?></td>
            <td><?=_("创建人")?></td>
            <td><?=_("登记权限(用户)")?></td>
            <td><?=_("审批权限(用户)")?></td>
            <td><?=_("状态")?></td>
        </thead>
    <?
        for($I=0;$I<count($lines);$I ++)
        {
    ?>
          <tr align="center" style="<?=$TR_STYLE?>" class="TableData">
            <td><?=$lines[$I]["OFFICE_DEPOSITORY"]?></td>
            <td><?=$lines[$I]["OFFICE_PROTYPE"]?></td>
            <td><?=$lines[$I]["PRO_NAME"]?></td>
            <td><?=$lines[$I]["OFFICE_PRODUCT_TYPE"]?></td>
            <td><?=$lines[$I]["PRO_PRICE"]?></td>
            <td><?=$lines[$I]["PRO_DESC"]?></td>
            <td><?=$lines[$I]["PRO_UNIT"]?></td>
            <td><?=$lines[$I]["PRO_SUPPLIER"]?></td>
            <td><?=$lines[$I]["PRO_LOWSTOCK"]?></td>
            <td><?=$lines[$I]["PRO_MAXSTOCK"]?></td>
            <td><?=$lines[$I]["PRO_STOCK"]?></td>
            <td><?=$lines[$I]["PRO_CREATOR"]?></td>
            <td><?=$lines[$I]["PRO_MANAGER"]?></td>
            <td><?=$lines[$I]["PRO_AUDITER"]?></td>
            <td align="left"><?=$MSG_ERROR[$I]?></td>
        </tr>
    <?
        }
    ?>
</table>
<?
if($success)
{
    Message( "", sprintf(_("共%d条数据导入!"), $SUCC_COUNT)); // $ROW_COUNT
}
if($ROW_COUNT>1)
{
?>
<script language="javascript">
    parent.PRO_LIST.location="pro_list.php";
</script>
<?
}
?>

<div align="center">
    <input type="button" value="<?=_("返回")?>" class="btn" onClick="location='pro_import.php';" title="<?=_("返回")?>">
</div>
</body>
</html>