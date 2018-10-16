<?
include_once("inc/utility.php");
include_once("general/crm/inc/header.php");

ob_end_clean();
echo getProdTypeList($PARENT_ID);

function getProdTypeList($PARENT_ID){
	global  $PARA_TARGET, $PARA_URL;
    $query  = "SELECT id, product_type_code, product_type_name FROM CRM_PRODUCT_TYPE ";
    $query .= "WHERE parent_id=\"$PARENT_ID\" order by product_type_code";
    $cursor= exequery(TD::conn(),$query);
    while($ROW = mysql_fetch_array($cursor)){
        $ID                 = $ROW["id"];
        $PRODUCT_TYPE_CODE  = $ROW["product_type_code"];
        $PRODUCT_TYPE_NAME  = $ROW["product_type_name"];
        
        $PRODUCT_TYPE_CODE  = td_htmlspecialchars($PRODUCT_TYPE_CODE);
        $PRODUCT_TYPE_CODE  = stripslashes($PRODUCT_TYPE_CODE);
        $PRODUCT_TYPE_NAME  = td_htmlspecialchars($PRODUCT_TYPE_NAME);
        $PRODUCT_TYPE_NAME  = stripslashes($PRODUCT_TYPE_NAME);

       $CHILD_COUNT = 0;
       $query1  = "SELECT 1 from CRM_PRODUCT_TYPE where parent_id='$ID'";
       $cursor1 = exequery(TD::conn(),$query1);
       if($ROW1=mysql_fetch_array($cursor1)){
          $CHILD_COUNT++;
       }
       if($CHILD_COUNT > 0){
			$JSON="./type_tree.php?PARENT_ID=$ID&PARA_TARGET=$PARA_TARGET&PARA_URL=$PARA_URL";
			$IS_LAZY = true;
       }
	   $ONCHECK = ($showButton && $DEPT_PRIV1=="1") ? "click_node" : "";
	   $URL = "$PARA_URL?PROD_TYPE=".$ID;
	   $ORG_ARRAY[] = array(
         "title" => td_iconv($PRODUCT_TYPE_NAME, MYOA_CHARSET, 'utf-8'),
         "isFolder" => FALSE,
         "isLazy" => $IS_LAZY,
//         "expand" => true,
         "key" => $ID,
         "dept_id" => "pro_".$ID,
         "icon" => $IS_ORG==1 ? 'org.png' : false,
         "url" => td_iconv($URL, MYOA_CHARSET, 'utf-8'),
         "tooltip" => td_iconv($PRODUCT_TYPE_NAME, MYOA_CHARSET, 'utf-8'),
         "json" => td_iconv($JSON, MYOA_CHARSET, 'utf-8'),
         "target" => $PARA_TARGET,
         "onCheck" => td_iconv($ONCHECK, MYOA_CHARSET, 'utf-8'),
      );

    }
    
    return json_encode($ORG_ARRAY);
}


?>