<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("编辑分组");
include_once("inc/header.inc.php");

while(list($KEY, $VALUE) = each($_POST))
{
   $$KEY = trim($VALUE);
}
?>
<body class="bodycolor">
<?
//批量处理分组人员
$query="UPDATE address set GROUP_ID='$GROUP_ID' where  1=1 and find_in_set(ADD_ID,'$FLD_STR')";
exequery(TD::conn(), $query);

//删除本组中成员直接掉到公共默认组中
$query = "select ADD_ID from address where GROUP_ID='$GROUP_ID' and !find_in_set(ADD_ID,'$FLD_STR')";
$cursor = exequery(TD::conn(), $query);
if($row = mysql_fetch_array($cursor))
{
    $ADD_ID = $row[0];
    $query="UPDATE address SET GROUP_ID='0' where ADD_ID='$ADD_ID'";
    exequery(TD::conn(), $query);
}
?>

<script>
    alert('<?=_("修改成功！")?>');
    parent.location.reload();
</script>
</body>
</html>
