<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$query="update MEETING set M_END='$M_END',M_ATTENDEE_NOT='$M_ATTENDEE_NOT' where M_ID='$M_ID'";
exequery(TD::conn(),$query);
?>

<script>
window.close();
</script>
</body>

</html>
