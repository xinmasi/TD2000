<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="delete from ADDRESS where GROUP_ID='$GROUP_ID' and USER_ID=''";
exequery(TD::conn(),$query);
?>
<script>
location="index.php";
</script>
</body>
</html>
