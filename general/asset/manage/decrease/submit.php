<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$CUR_DATE=date("Y-m-d",time());
$query="update CP_CPTL_INFO set DCR_DATE='$CUR_DATE',DCR_PRCS_ID='$DCR_PRCS_ID' where CPTL_ID='$CPTL_ID'";
$cursor=exequery(TD::conn(),$query);
Message(_("提示"),_("操作成功"));
echo "<div align=\"center\"><input type=\"button\" class=\"BigButton\" value="._("关闭")." onClick=\"window.close();\" title="._("关闭窗口")."></div>";
?>