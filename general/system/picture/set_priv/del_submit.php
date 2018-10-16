<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?

if($TO_ID!=""||$PRIV_ID!=""||$COPY_TO_ID!="")
{
    if(substr($TO_ID, -1, 1) != ","){
        $TO_ID = $TO_ID.",";
    }
    if(substr($PRIV_ID, -1, 1) != ","){
        $PRIV_ID = $PRIV_ID.",";
    }
    if(substr($PRIV_ID, -1, 1) != ","){
        $COPY_TO_ID = $COPY_TO_ID.",";
    }
    $DEL_PRIV_STR=$TO_ID."|".$PRIV_ID."|".$COPY_TO_ID;
}
$query="update PICTURE set DEL_PRIV_STR='$DEL_PRIV_STR' where PIC_ID='$PIC_ID'";
exequery(TD::conn(), $query);
Message("",_("权限设置完成"));

?>
<center><input type=button name="button" value="<?=_("返回")?>" class="BigButton"  align="center" onclick="window.location='/general/system/picture/set_priv/set_deluser.php?IS_MAIN=1&PIC_ID=<?=$PIC_ID?>'"></center>

</body>
</html>