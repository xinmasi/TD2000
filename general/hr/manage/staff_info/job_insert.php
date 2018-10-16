<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("¸ÚÎ»Ö°Ôð±à¼­");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$CODE_ID=$_POST["code_id"];
$JOBS_STYLE=$_POST["JOBS_STYLE"];
$JOB_DESCRIPTION=$_POST["JOB_DESCRIPTION"];
$JOB_RESPONSIBILITIES=$_POST["JOB_RESPONSIBILITIES"];
$query = "SELECT * from hr_job_responsibilities where JOB_CODE_ID='$CODE_ID'";
$cursor = exequery(TD::conn(), $query);
if(mysql_fetch_array($cursor))
{
    $query1="update hr_job_responsibilities set JOB_CODE_STYLE_ID='$JOBS_STYLE',JOB_DESCRIPTION='$JOB_DESCRIPTION',JOB_RESPONSIBILITIES='$JOB_RESPONSIBILITIES' where JOB_CODE_ID='$CODE_ID';";
    exequery(TD::conn(), $query1);
}
else
{
    $query1="insert into hr_job_responsibilities(JOB_CODE_ID,JOB_CODE_STYLE_ID,JOB_DESCRIPTION,JOB_RESPONSIBILITIES) values ('$CODE_ID','$JOBS_STYLE','$JOB_DESCRIPTION','$JOB_RESPONSIBILITIES');";
    exequery(TD::conn(), $query1);
}

?>
<script>
    parent.location.reload();
    parent.document.getElementById('hide_edit').click();
</script>

</body>
</html>
