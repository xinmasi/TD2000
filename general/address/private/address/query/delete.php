<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
if($DELETE_STR!="")
{
    $TOK = strtok($DELETE_STR,",");
    while($TOK!="")
    {
        del_field_data("ADDRESS",$TOK);
        
        $query = "DELETE FROM address WHERE ADD_ID='$TOK'";
        exequery(TD::conn(),$query);         
        $TOK = strtok(",");
    }
}
else
{
    del_field_data("ADDRESS",$ADD_ID);
    
    $query = "DELETE FROM address WHERE ADD_ID='$ADD_ID'";
    exequery(TD::conn(),$query);
}
if($_GET['type']=='repeat')
{	
	$where_repeat = str_replace("@","'",$_GET['where_repeat']);
	$query = "DELETE FROM address WHERE ".$where_repeat." AND ADD_ID not in(select ADD_ID FROM (select PSN_NAME,MOBIL_NO,EMAIL,max(ADD_ID) as ADD_ID FROM address group by PSN_NAME,MOBIL_NO,EMAIL) as t1)";
	exequery(TD::conn(),$query);
}


$where_str2 = '';
if($where_str)
{
    $where_str2 = str_replace('``','&',$where_str);
    $where_str2 = str_replace('`','',$where_str2);
    $where_str2 = "&".$where_str2;
}

if($from=='1')
{
?>
    <script Language="JavaScript">
        window.close();
        window.opener.location.href="search_submit.php?start=<?=$start?><?=$where_str2?>";
    </script>
<?
}
else
{
    header("location: search_submit.php?start=".$start.$where_str2);
}
?>

</body>
</html>
