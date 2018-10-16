<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_all.php");
ob_end_clean();

$FOLDER_IMG="endnode.gif";
$DEPOSITORY_ARRAY = array();
if($LIST_TREE=='')
{
    $DEPT_ID_STR = td_trim($_SESSION["LOGIN_DEPT_ID"].",".$_SESSION["LOGIN_DEPT_ID_OTHER"]);
    
    $id_array = explode(',',$DEPT_ID_STR); 
    $where = "";
    if(count($id_array)>1)
    {
        for($i=0;$i<count($id_array);$i++)
        {
            $where .= "FIND_IN_SET('{$id_array[$i]}',DEPT_ID) or ";
        }
    }
    else
    {
        $where = "FIND_IN_SET('$DEPT_ID_STR',DEPT_ID) or ";
    }
    if($_SESSION["LOGIN_USER_PRIV"]==1)
    {
        $query = "select * from OFFICE_DEPOSITORY ";
    }
    else
    {
        $query = "select * from OFFICE_DEPOSITORY where ".$where." DEPT_ID = '' or  DEPT_ID = 'ALL_DEPT'";
    }
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $DEPOSITORY_NAME = td_htmlspecialchars($ROW['DEPOSITORY_NAME']);
        $DEPOSITORY_NAME = str_replace("\"","&quot;",$DEPOSITORY_NAME);
        
        $DEPOSITORY_ID=$ROW['ID'];
        
        if($ROW['OFFICE_TYPE_ID'] == '')
        {
            $isLazy = true;
        }else
        {
            $isLazy = false;
        }
        
        $JSON = "tree.php?LIST_TREE=TYPE&TYPE_ID=".$ROW['ID']."&ID=".$ROW['OFFICE_TYPE_ID'];
        $DEPOSITORY_ARRAY[] = array(
        "title" => td_iconv($DEPOSITORY_NAME, MYOA_CHARSET, 'utf-8'),
        "isFolder" => true,
        "isLazy" => true,
        "icon" => $FOLDER_IMG,
        "json" => $JSON
        );
         
    }
}elseif($LIST_TREE=='TYPE'&&$ID!="")
{   
    $query_type = "select * from OFFICE_TYPE where ID in (".$ID.") ORDER BY TYPE_ORDER";
    $cursor_type= exequery(TD::conn(),$query_type);
    $isLazy = false;
    while($ROW_TYPE=mysql_fetch_array($cursor_type))
    {
        if($_SESSION["LOGIN_USER_PRIV"]!=1)
            $query1="((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PRO_MANAGER) or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',PRO_DEPT)) or (PRO_MANAGER='' and PRO_DEPT='') or PRO_DEPT='ALL_DEPT' or PRO_CREATOR='".$_SESSION["LOGIN_USER_ID"]."')";
        else
            $query1="1=1";
        $query_count_products = "select count(*) from OFFICE_PRODUCTS where OFFICE_PROTYPE = '".$ROW_TYPE['ID']."' and $query1";
        $count_products = exequery(TD::conn(),$query_count_products);
        $ROW_COUNT_PRODUCTS = mysql_fetch_array($count_products);
        
        $TYPE_NAME=td_htmlspecialchars($ROW_TYPE['TYPE_NAME']);
        $TYPE_NAME=str_replace("\"","&quot;",$TYPE_NAME);
        
        if($ROW_COUNT_PRODUCTS[0] > 0)
        {
            $json = "tree.php?LIST_TREE=PRODUCTS&DEPOSITORY_ID=".$TYPE_ID."&ID=".$ROW_TYPE['ID']."&DEPOSITORY=".$ID."&TYPE=".$ROW_TYPE['ID'];
            $url =  "apply_one.php?DEPOSITORY=".$ID."&TYPE=".$ROW_TYPE['ID']."&DEPOSITORY_ID=".$TYPE_ID;
            $isLazy = true; 
        }else
        {
            $json = "";
            $url = "apply_one.php?DEPOSITORY=".$ID."&TYPE=".$ROW_TYPE['ID']."&DEPOSITORY_ID=".$TYPE_ID;
        }
    
        $DEPOSITORY_ARRAY[] = array(
            "title" => td_iconv($TYPE_NAME, MYOA_CHARSET, 'utf-8'),
            "isFolder" => true,
            "isLazy" => $isLazy,
            "icon" => $FOLDER_IMG,
            "url" => $url,
            "json" => $json,
            "target" => "list"
        );         
    }
}
elseif($LIST_TREE=='PRODUCTS')
{
    $FOLDER_IMG="4.gif"; 
    if($_SESSION["LOGIN_USER_PRIV"]!=1)
        $query1="((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PRO_MANAGER) or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',PRO_DEPT)) or (PRO_MANAGER='' and PRO_DEPT='') or PRO_DEPT='ALL_DEPT' or PRO_CREATOR='".$_SESSION["LOGIN_USER_ID"]."')";
    else
        $query1="1=1";  
    
    $query_products  = "select PRO_ID,PRO_NAME,AVAILABLE from OFFICE_PRODUCTS where OFFICE_PROTYPE = '".$ID."' and $query1 order by PRO_ORDER,PRO_CODE";
    $cursor_products = exequery(TD::conn(),$query_products);
    
    $this_date = date('Y-m-d',time());
    $this_time = strtotime($this_date);
    
    while($ROW_PRODUCTS = mysql_fetch_array($cursor_products))
    {
        $AVAILABLE = $ROW_PRODUCTS['AVAILABLE'];
        if($AVAILABLE!="")
        {
            $time_array =  explode("|",$AVAILABLE);
            if($this_time>=$time_array[0] && $this_time<=$time_array[1])
            {
                continue;
            }
        }    
        $PRO_NAME=td_htmlspecialchars($ROW_PRODUCTS['PRO_NAME']);
        $PRO_NAME=str_replace("\"","&quot;",$PRO_NAME);
        
        $url = "detail.php?DEPOSITORY=".$DEPOSITORY."&DEPOSITORY_ID=".$DEPOSITORY_ID."&TYPE=".$TYPE."&PRO_ID=".$ROW_PRODUCTS['PRO_ID'];
        
        $DEPOSITORY_ARRAY[] = array(
            "title" => td_iconv($PRO_NAME, MYOA_CHARSET, 'utf-8'),
            "icon" => $FOLDER_IMG,
            "url" => $url,
            "target" => "list"
        );
    }
}
echo json_encode($DEPOSITORY_ARRAY);
?>
