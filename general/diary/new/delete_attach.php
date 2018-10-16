<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

$s_attachment_name = $_GET['ATTACHMENT_NAME'];
$s_attachment_id = $_GET['ATTACHMENT_ID'];
$dia_id = $_GET['DIA_ID'];
$dia_id = intval($dia_id);

$query = "SELECT ATTACHMENT_ID,ATTACHMENT_NAME FROM diary WHERE DIA_ID='$dia_id'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
{
    $s_attachment_id_old    = $ROW["ATTACHMENT_ID"];
    $s_attachment_name_old  = $ROW["ATTACHMENT_NAME"];
}

if($s_attachment_name != "")
{
    delete_attach($s_attachment_id, $s_attachment_name);
    $a_attachment_id_array = explode(",", $s_attachment_id_old);
    $a_attachment_name_array = explode("*", $s_attachment_name_old);
    
    $i_array_count = sizeof($a_attachment_id_array);
    for($i = 0; $i < $i_array_count; $i++)
    {
        if($a_attachment_id_array[$i] == $s_attachment_id || $a_attachment_id_array[$i] == "")
        {
            continue;
        }
        
        $s_attachment_id1 .= $a_attachment_id_array[$i].",";
        $s_attachment_name1 .= $a_attachment_name_array[$i]."*";
    }
    $s_attachment_id_new = $s_attachment_id1;
    $s_attachment_name_new = $s_attachment_name1;
    
    $query="UPDATE diary SET ATTACHMENT_ID='$s_attachment_id_new',ATTACHMENT_NAME='$s_attachment_name_new' WHERE DIA_ID='$dia_id'";
    exequery(TD::conn(),$query);
}

header("location: edit.php?dia_id=$dia_id&IS_MAIN=1");
?>
