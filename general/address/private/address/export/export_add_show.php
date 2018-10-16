<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");
include_once("inc/utility_email.php");
ob_end_clean();

$show_id_str2 = "";
$show_name_str2 = "";
$count = 0;
if($show_id_str != "")
{
    if(find_id($show_id_str,$el))
    {
        $query = "SELECT ADD_ID,PSN_NAME FROM address  WHERE ADD_ID!='$el' and find_in_set(ADD_ID,'$show_id_str')";
    }
    else
    {
        $query = "SELECT ADD_ID,PSN_NAME FROM address  WHERE ADD_ID='$el' or find_in_set(ADD_ID,'$show_id_str')";
    }
}
else
{
    $query = "SELECT ADD_ID,PSN_NAME FROM address  WHERE ADD_ID='$el' ";
}
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $count++;
        
        $show_id_str2 .= $row[0].",";
        
        if($count == '1')
        {
            $show_name_str2 .= $row[1];
        }
        else
        {
            $show_name_str2 .= "¡¢".$row[1];
        }
    }
    $show_str = $show_id_str2."*".$show_name_str2;
echo $show_str;
?>

