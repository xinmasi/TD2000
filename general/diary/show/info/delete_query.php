<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor" topmargin="5">
<?
$DELETE_STR = td_trim($DELETE_STR);
if($DELETE_STR != "")
{
   $query="delete from diary where DIA_ID in ($DELETE_STR)";
   exequery(TD::conn(),$query);
}

Message("",mysql_affected_rows()._("篇工作日志已删除"));
?>
<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='user_search.php?DIARY_COPY_TIME=<?=$DIARY_COPY_TIME?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&SUBJECT=<?=$SUBJECT?>&TO_ID1=<?=$TO_ID1?>&TO_ID=<?=$TO_ID?>&PRIV_ID=<?=$PRIV_ID?>&COPYS_TO_ID=<?=$COPYS_TO_ID?>&DIA_TYPE=<?=$DIA_TYPE?>&IS_MAIN=1';">
</div>
</body>
</html>
