<?
include_once ("inc/auth.inc.php");
include_once ("inc/header.inc.php");
if($ID != "")
{
    while(list($KEY,$VALUE) = each($_POST))
    {
        $$KEY = trim ($VALUE);
        if(substr($KEY, 0, 10) == "TYPE_NAME_")
        {
            $IS_TYPE[] = $$KEY;
        }
    }
    $IS_TYPE_COUNT = count($IS_TYPE);
    if($IS_TYPE_COUNT>0)
    {
        for($I=0;$I<$IS_TYPE_COUNT;$I++)
        {
            $OFFICE_TYPE_ID .= $IS_TYPE[$I] . ",";
        }
        $OFFICE_TYPE_ID = td_trim($OFFICE_TYPE_ID);
        $where_str = "OFFICE_TYPE_ID='$OFFICE_TYPE_ID',"; // office_type_id 物品类id
    }
    else
    {
        $where_str = "";
    }
    $query = "UPDATE office_depository SET DEPOSITORY_NAME='$DEPOSITORY_NAME',DEPT_ID='$TO_ID',MANAGER='$MANAGER',APPROVE='$APPROVE',$where_str PRIV_ID='$PRIV_ID', PRO_KEEPER='$PRO_KEEPER' WHERE ID='$ID'";
    $cursor = exequery(TD::conn(),$query);
}
else
{
    $query = "INSERT INTO office_depository (ID,DEPOSITORY_NAME,OFFICE_TYPE_ID,DEPT_ID,MANAGER,PRO_KEEPER,PRIV_ID,APPROVE) VALUES (NULL,'$DEPOSITORY_NAME','$OFFICE_TYPE_ID','$TO_ID','$MANAGER','$PRO_KEEPER','$PRIV_ID','$APPROVE')";
    exequery(TD::conn(),$query);
}
header("Location:index.php"); // php 跳转页面
?>