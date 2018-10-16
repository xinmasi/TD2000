<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("É¾³ýÁªÏµÈË");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
if($DELETE_STR!="")
{
    $TOK=strtok($DELETE_STR,",");
    $TOK=intval($TOK);
    while($TOK!="")
    {
        del_field_data("ADDRESS",$TOK);
        
        $query="delete from ADDRESS where ADD_ID='$TOK'";
        exequery(TD::conn(),$query);         
        $TOK=strtok(",");
    }
}
else
{
    $ADD_ID=intval($ADD_ID);
    del_field_data("ADDRESS",$ADD_ID);
    
    $query="delete from ADDRESS where ADD_ID='$ADD_ID'";
    exequery(TD::conn(),$query);
}
?>

<script>
    parent.talklist.location.reload();
    //parent.document.getElementById("talkList").src="talkList.php?SHARE_TYPE=<?=$SHARE_TYPE?>&GROUP_ID=<?=$GROUP_ID?>";
</script>

</body>
</html>
