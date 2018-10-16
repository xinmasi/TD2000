<?
    include_once("inc/auth.inc.php");
    include_once("inc/utility_all.php");
    include_once("inc/utility_file.php");
    include_once("inc/utility_org.php");
    include_once("inc/db/dbms.php");

    $V_NUM = $_POST["V_NUM"] ? $_POST["V_NUM"] : "";
    $V_NUM = iconv("UTF-8", "GBK", $V_NUM);

    $query = "SELECT V_ID,V_DEPART,V_ONWER,V_CARUSER,V_TYPE,V_NUM FROM vehicle WHERE V_NUM='$V_NUM'";
    $cursor = exequery(TD::conn(), $query);
    
    if( $row = mysql_fetch_array($cursor) )
    {
        $v_id = $row["V_ID"];
        $v_depart = $row["V_DEPART"];
        $v_onwer = $row["V_ONWER"];
        $v_caruser = $row["V_CARUSER"];
        $v_type = get_code_name($row["V_TYPE"], "VEHICLE_TYPE");
        $v_num = $row["V_NUM"];
        
        $result = array(
            v_id => $v_id,
            v_depart => $v_depart,
            v_onwer  => $v_onwer,
            v_caruser =>$v_caruser,
            v_type => $v_type,
            v_num => $v_num,
        );
    }
    ob_end_clean();
	header("Cache-Control: no-cache, must-revalidate" );
	header("Pragma: no-cache" );
	header("Content-type: text/x-json; charset=$MYOA_CHARSET");
	echo json_encode(td_iconv($result, MYOA_CHARSET, 'utf-8'));
?>

