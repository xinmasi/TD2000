<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
if($IS_SET==0)
	$query = "INSERT INTO CP_ASSET_REFLECT(CPTL_NO,CPTL_NAME,TYPE_ID,DEPT_ID,CPTL_VAL,CPTL_BAL,DPCT_YY, MON_DPCT,SUM_DPCT,CPTL_KIND,PRCS_ID,FINISH_FLAG,CREATE_DATE,DCR_DATE,FROM_YYMM,DCR_PRCS_ID,KEEPER,REMARK)
             VALUES ('$CPTL_NO','$CPTL_NAME','$TYPE_ID','$DEPT_ID','$CPTL_VAL','$CPTL_BAL','$DPCT_YY', '$MON_DPCT','$SUM_DPCT','$CPTL_KIND','$PRCS_ID','$FINISH_FLAG','$CREATE_DATE','$DCR_DATE','$FROM_YYMM','$DCR_PRCS_ID','$KEEPER', '$REMARK');";
else
   $query = "update CP_ASSET_REFLECT set CPTL_NO='$CPTL_NO',CPTL_NAME='$CPTL_NAME',TYPE_ID='$TYPE_ID',DEPT_ID='$DEPT_ID',CPTL_VAL='$CPTL_VAL',CPTL_BAL='$CPTL_BAL',DPCT_YY='$DPCT_YY',MON_DPCT='$MON_DPCT',SUM_DPCT='$SUM_DPCT',CPTL_KIND='$CPTL_KIND',PRCS_ID='$PRCS_ID',FINISH_FLAG='$FINISH_FLAG',CREATE_DATE='$CREATE_DATE',DCR_DATE='DCR_DATE',FROM_YYMM='$FROM_YYMM',DCR_PRCS_ID='$DCR_PRCS_ID',KEEPER='$KEEPER',REMARK='$REMARK' where ID='$CID'";
exequery(TD::conn(),$query);
Message("",_("保存成功！"));
?>
<div align="center">
<input type="button" class="BigButton" value="<?=_("返回")?>" onClick="location='index.php'">
</div>
