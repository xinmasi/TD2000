<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
ob_end_clean();
function DeptNameChange($OPEN)
{

    $OPEN1=explode(";", $OPEN);
    $OPEN_DEPT=td_trim($OPEN1[0]);
    if($OPEN_DEPT=="ALL_DEPT" || $OPEN_DEPT=="1")
        $TO_NAME=_("全体部门");
    else{
        $TO_NAME=GetDeptNameById($OPEN_DEPT);
        if($TO_NAME==","){
            $TO_NAME="";
        }
    }
    $COPY_TO_NAME=GetUserNameById($OPEN1[1]);
    if($COPY_TO_NAME==","){
        $COPY_TO_NAME="";
    }
    $PRIV_NAME=GetPrivNameById($OPEN1[2]);
    if($PRIV_NAME==","){
        $PRIV_NAME="";
    }

    $TO_NAME=td_trim($TO_NAME);
    $DEPT_STR=$TO_NAME.",".td_trim($COPY_TO_NAME).",".$PRIV_NAME;
    return $DEPT_STR;
}

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="DEPARTMENT,BOOK_NAME,AUTHOR,BOOK_NO,BOOK_TYPE,ISBN,PUBLISH_HOUSE,PUBLISH_DATE,PLACE,AMOUNT,PRICE,BRIEF,OPEN,LEND,BORR_PERSON,MEMO";
else
    $EXCEL_OUT=array(_("部门"),_("书名"),_("作者"),_("图书编号"),_("图书类别"),_("ISBN号"),_("出版社"),_("出版日期"),_("存放地点"),_("数量"),_("价格"),_("内容简介"),_("借阅情况"),_("借阅范围"),_("录入人"),_("备注"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("图书信息导出"));
$objExcel->addHead($EXCEL_OUT);

$WHERE_STR=" where 1=1";
if($BOOK_NAME!="")
    $WHERE_STR.=" and BOOK_NAME like '%".$BOOK_NAME."%'";

if($BOOK_NO!="")
    $WHERE_STR.=" and BOOK_NO like '%".$BOOK_NO."%'";

/*if($LEND!="")
  $WHERE_STR.=" and LEND='$LEND'";*/

if($AUTHOR!="")
    $WHERE_STR.=" and AUTHOR like '%".$AUTHOR."%'";

if($ISBN!="")
    $WHERE_STR.=" and ISBN like '%".$ISBN."%'";

if($PUB_HOUSE!="")
    $WHERE_STR.=" and PUB_HOUSE like '%".$PUB_HOUSE."%'";

if($AREA!="")
    $WHERE_STR.=" and AREA like '%".$AREA."%'";

if($TYPE_ID!="all")
    $WHERE_STR.=" and TYPE_ID='$TYPE_ID'";

if($LEND!=""){
    if($LEND == "1"){
        $query="select DISTINCT b.* from BOOK_MANAGE as a , BOOK_INFO as b ".$WHERE_STR." and  b.BOOK_NO=a.BOOK_NO and a.BOOK_STATUS='0' ";
        $ty="true";
    }else if($LEND == "0"){
        $query="select * from BOOK_INFO ".$WHERE_STR." order by ".$ORDER_FIELD;
        $ty = "fales";
    }
}else{
    $query="SELECT DEPT,OPEN,BORR_PERSON from BOOK_INFO ".$WHERE_STR." order by ".$ORDER_FIELD;
}

//if($_SESSION["LOGIN_USER_PRIV"]!=1)
//$WHERE_STR.=" and (OPEN='0' or OPEN='' or OPEN='1' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',OPEN) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OPEN) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',OPEN) or OPEN='ALL_DEPT' or OPEN='ALL_DEPT;,,;,,' or BORR_PERSON='".$_SESSION["LOGIN_USER_NAME"]."')";

$query="SELECT * from BOOK_INFO ".$WHERE_STR." order by '$ORDER_FIELD'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $DEPT1=$ROW["DEPT"];
    $OPEN1=$ROW["OPEN"];
    $BORR_PERSON1=$ROW["BORR_PERSON"];
    $OPEN_ARR=explode(";", $OPEN1);
    if ($_SESSION["LOGIN_USER_PRIV"]!=1)
    {
        if (!find_id($OPEN_ARR[0], $_SESSION["LOGIN_DEPT_ID"]) && !find_id($OPEN_ARR[1], $_SESSION["LOGIN_USER_ID"]) && !find_id($OPEN_ARR[2], $_SESSION["LOGIN_USER_PRIV"]) && $OPEN_ARR[0]!="ALL_DEPT")
            continue;
    }

    if($OPEN1=="0" && $DEPT1!=$_SESSION["LOGIN_DEPT_ID"] && $_SESSION["LOGIN_USER_NAME"]!=$BORR_PERSON1)
        continue;

    $BOOK_ID=$ROW["BOOK_ID"];
    $BOOK_NAME1=$ROW["BOOK_NAME"];
    $TYPE_ID1=$ROW["TYPE_ID"];
    $AUTHOR1=$ROW["AUTHOR"];
    $ISBN1=$ROW["ISBN"];
    $PUB_HOUSE1=$ROW["PUB_HOUSE"];
    $PUB_DATE1=$ROW["PUB_DATE"];
    $AREA1=$ROW["AREA"];
    $AMT1=$ROW["AMT"];
    $PRICE1=$ROW["PRICE"];
    $BRIEF1=$ROW["BRIEF"];
    $LEND1=$ROW["LEND"];
    $BORR_PERSON1=$ROW["BORR_PERSON"];
    $MEMO1=$ROW["MEMO"];
    $DEPT1=$ROW["DEPT"];
    $BOOK_NO=$ROW["BOOK_NO"];
    $OPEN1=$ROW["OPEN"];

    $OPEN_STR="";
    if ($OPEN1!="")
        $OPEN_STR =  str_replace(",",_("，"),substr((td_trim(DeptNameChange($OPEN1)).","),0,-1));
    else
        $OPEN_STR = _("全体部门");

    /*if($OPEN1!="" && $OPEN1!="ALL_DEPT" && $OPEN1!="0" && $OPEN1!="1")
       $OPEN_STR = str_replace(",",_("，"),substr(GetDeptNameById($OPEN1),0,-1));
    else
       $OPEN_STR = _("全体部门");*/

    /*if($LEND1=="1")
         $LEND_DESC=_("已借出");
    else
         $LEND_DESC=_("未借出");*/
    if($ty == "true"){
        $sql = "select COUNT(BOOK_NO) from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' and BOOK_STATUS = '0'";

        $cursor4 = exequery(TD::conn(),$sql);
        while($row1 = mysql_fetch_assoc($cursor4) ){

            $LEND_DESC =_("已借出").$row1['COUNT(BOOK_NO)']._("册");
            $t = "N";
        }
    }else if($ty == "fales"){
        $sql = "select COUNT(BOOK_NO),BOOK_NO from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' and BOOK_STATUS = '0'";
        $cursor4 = exequery(TD::conn(),$sql);
        while($row1 = mysql_fetch_assoc($cursor4) ){
            if($BOOK_NO == $row1["BOOK_NO"]){
                $sum = $ROW["AMT"]-$row1["COUNT(BOOK_NO)"];
                if($sum == 0){
                    $LEND_DESC =_("已全部借出");
                    $t = "N";
                }else{
                    $LEND_DESC =$sum._("册未借出");
                    $t = "Y";
                }
            }else{
                $LEND_DESC=$ROW["AMT"]._("册未借出");
                $t = "Y";
            }
        }
    }else{
        if($LEND1=="1"){
            $sql = "select COUNT(BOOK_NO),BOOK_NO from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' AND BOOK_STATUS ='0'";
            $cursor4 = exequery(TD::conn(),$sql);
            while($row1 = mysql_fetch_assoc($cursor4) ){
                if($BOOK_NO == $row1["BOOK_NO"]){
                    $sum = $ROW["AMT"]-$row1["COUNT(BOOK_NO)"];
                    if($sum == 0){
                        $LEND_DESC =_("已全部借出");
                        $t = "N";
                    }else{
                        $LEND_DESC =$sum._("册未借出");
                        $t = "Y";
                    }
                }else{
                    $LEND_DESC=$ROW["AMT"]._("册未借出");
                    $t = "Y";
                }
            }
            //$LEND_DESC=_("已借出");
        }else{
            $sql = "select COUNT(BOOK_NO),BOOK_NO from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' and BOOK_STATUS = '0'";

            $cursor4 = exequery(TD::conn(),$sql);
            while($row1 = mysql_fetch_assoc($cursor4) ){
                if($BOOK_NO == $row1["BOOK_NO"]){
                    $sum = $ROW["AMT"]-$row1["COUNT(BOOK_NO)"];
                    if($sum == 0){
                        $LEND_DESC =_("已全部借出");
                        $t = "N";
                    }else{
                        $LEND_DESC =_("已借出").$row1["COUNT(BOOK_NO)"]._("册，剩余").$sum._("册");
                        $t = "Y";
                    }
                }else{
                    $LEND_DESC=_("已借出0册，剩余").$ROW["AMT"]._("册");
                    $t = "Y";
                }
            }
            //$LEND_DESC=_("未借出");
        }
    }

    $query2 = "SELECT TYPE_NAME from BOOK_TYPE where TYPE_ID='$TYPE_ID1'";
    $cursor2=exequery(TD::conn(),$query2);
    if($ROW=mysql_fetch_array($cursor2))
        $TYPE_NAME=$ROW["TYPE_NAME"];

    $query3 = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID='$DEPT1'";
    $cursor3=exequery(TD::conn(),$query3);
    if($ROW=mysql_fetch_array($cursor3))
        $DEPT_NAME=$ROW["DEPT_NAME"];

    $BRIEF1 = str_replace("\r\n","",str_replace(",",_("，"),$BRIEF1));

    $EXCEL_OUT="$DEPT_NAME,$BOOK_NAME1,$AUTHOR1,$BOOK_NO,$TYPE_NAME,$ISBN1,$PUB_HOUSE1,$PUB_DATE1,$AREA1,$AMT1,$PRICE1,$BRIEF1,$LEND_DESC,$OPEN_STR,$BORR_PERSON1,$MEMO1\n";
    $objExcel->addRow($EXCEL_OUT);
}

$objExcel->Save();
?>