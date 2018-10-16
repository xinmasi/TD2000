<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script Language="JavaScript">
var parent_window = getOpenner();
<?
if($TO_ID=="" || $TO_ID=="undefined")
{
    $TO_ID="TO_ID";
   $TO_NAME="TO_NAME";
}
if($FORM_NAME=="" || $FORM_NAME=="undefined")
   $FORM_NAME="form1";
?>

function clear_user()
{
    parent_window.<?=$FORM_NAME?>.<?=$TO_ID?>.value="";
    parent_window.<?=$FORM_NAME?>.<?=$TO_NAME?>.value="";
}

</script>


<body class="bodycolor">

<table border="0" cellspacing="1" width="100%" class="small"  cellpadding="2" align="center">
   <tr>
     <td nowrap align="right">
     	<input type="button" class="BigButton" value="<?=_("Çå¿Õ")?>" onclick="clear_user();">&nbsp;&nbsp;
     	<input type="button" class="BigButton" value="<?=_("È·¶¨")?>" onclick="top.close();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     </td>
   </tr>
</table>

</body>
</html>
