<?
include_once("inc/auth.inc.php");

$ATTACHMENT_ID = $_POST['ATTACHMENT_ID'];
//$ATTACHMENT_NAME = iconv("UTF-8","gb2312",$_POST['ATTACHMENT_NAME']);
$PROJ_ID = $_POST['PROJ_ID'];

$QUERY = "SELECT ATTACHMENT_ID,ATTACHMENT_NAME FROM PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(), $QUERY);
$ARR = ARRAY();
$R = mysql_fetch_assoc($cursor);

$A_ID = explode(",",trim($R['ATTACHMENT_ID'],","));
$A_NAME = explode("*",trim($R['ATTACHMENT_NAME'],"*"));

$S_A_ID = "";
$S_A_NAME = "";

foreach($A_ID as $key => $val){
    if($val != $ATTACHMENT_ID){
        $S_A_ID .= $val .",";
        $S_A_NAME .= $A_NAME[$key] . "*";
    }
}

$S_A_ID = strlen($S_A_ID) > 1 ? $S_A_ID : "";
$S_A_NAME = strlen($S_A_NAME) > 1 ? $S_A_NAME : "";

$QUERY = "UPDATE PROJ_PROJECT SET ATTACHMENT_ID='$S_A_ID',ATTACHMENT_NAME='$S_A_NAME' WHERE PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(), $QUERY);
if(mysql_affected_rows()){
    echo "true";
}else{
echo "false";
}

//此方法简单但文件名相同删除会出错 
//----zfc----mysql replace---2013-12-5
// $QUERY = "UPDATE PROJ_PROJECT SET ATTACHMENT_ID = replace(ATTACHMENT_ID,'$ATTACHMENT_ID',''),ATTACHMENT_NAME = replace(ATTACHMENT_NAME,'$ATTACHMENT_NAME','') WHERE PROJ_ID='$PROJ_ID'";
// $cursor = exequery(TD::conn(), $QUERY);
// if(mysql_affected_rows()){
    // echo "true";
// }else{
// echo "false";
// }
?>