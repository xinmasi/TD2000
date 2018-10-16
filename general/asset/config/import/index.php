<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("固定资产导入字段匹配设置");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"><?=_("固定资产导入字段匹配设置")?></span>
    </td>
  </tr>
</table>
<?
$query = "SELECT * from CP_ASSET_REFLECT ";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{  
	$ID=$ROW["ID"];
   $CPTL_NO=$ROW["CPTL_NO"];
   $CPTL_NAME=$ROW["CPTL_NAME"];
   $TYPE_ID=$ROW["TYPE_ID"];
	$DEPT_ID=$ROW["DEPT_ID"];
	$CPTL_VAL=$ROW["CPTL_VAL"];
	$CPTL_BAL=$ROW["CPTL_BAL"];
	$DPCT_YY=$ROW["DPCT_YY"];
	$MON_DPCT=$ROW["MON_DPCT"];
	$SUM_DPCT=$ROW["SUM_DPCT"];
	$CPTL_KIND=$ROW["CPTL_KIND"];
	$PRCS_ID=$ROW["PRCS_ID"];
	$FINISH_FLAG=$ROW["FINISH_FLAG"];
	$CREATE_DATE=$ROW["CREATE_DATE"];
	$DCR_DATE=$ROW["DCR_DATE"];
	$FROM_YYMM=$ROW["FROM_YYMM"];
	$DCR_PRCS_ID=$ROW["DCR_PRCS_ID"];
	$KEEPER=$ROW["KEEPER"];
	$REMARK=$ROW["REMARK"];
	$IS_SET = 1;
}
?>
<table class="TableBlock" width="50%" align="center">
	<form name="form1" method="post" action="add.php">
		<tr class="TableHeader" align="center">
			<td><?=_("数据库字段名称")?></td>
			<td>excel<?=_("字段名称")?></td>
		</tr>
  		<tr class="TableContent" align="center">
			<td><?=_("资产编号")?></td>
			<td><input type="text" name="CPTL_NO" class="SmallInput" value="<?=$CPTL_NO?>" size="40"></td>
		</tr>
		<tr class="TableData" align="center">
    		<td><?=_("资产名称")?></td>
			<td><input type="text" name="CPTL_NAME" class="SmallInput" value="<?=$CPTL_NAME?>" size="40"></td>
		</tr>
		<tr class="TableData" align="center">
			<td><?=_("资产类别")?></td>
			<td><input type="text" name="TYPE_ID" class="SmallInput" value="<?=$TYPE_ID?>" size="40"></td>
		</tr>
		<tr class="TableData" align="center">
			<td><?=_("所属部门")?></td>
			<td><input type="text" name="DEPT_ID" class="SmallInput" value="<?=$DEPT_ID?>" size="40"></td>
		</tr>
		<tr class="TableData" align="center">
			<td><?=_("资产性质")?></td>
    		<td><input type="text" name="CPTL_KIND" class="SmallInput" value="<?=$CPTL_KIND?>" size="40"></td>
  		</tr>
  		<tr class="TableData" align="center">
   		<td><?=_("增加类型")?></td>
    		<td><input type="text" name="PRCS_ID" class="SmallInput" value="<?=$PRCS_ID?>" size="40"></td>
 		</tr>
  		<tr class="TableData" align="center">
   		<td><?=_("资产原值")?></td>
    		<td><input type="text" name="CPTL_VAL" class="SmallInput" value="<?=$CPTL_VAL?>" size="40"></td>
  		</tr>
  		<tr class="TableData" align="center">
    		<td><?=_("残值率")?></td>
    		<td><input type="text" name="CPTL_BAL" class="SmallInput" value="<?=$CPTL_BAL?>" size="40"></td>
 		</tr>
	  <tr class="TableData" align="center">
	  		<td><?=_("折旧年限")?></td>
	    	<td><input type="text" name="DPCT_YY" class="SmallInput" value="<?=$DPCT_YY?>" size="40"></td>
	  </tr>
	  <tr class="TableData" align="center">
	   	<td><?=_("累计折旧")?></td>
	    	<td><input type="text" name="SUM_DPCT" class="SmallInput" value="<?=$SUM_DPCT?>" size="40"></td>
	  </tr>
  	  <tr class="TableData" align="center">
		   <td><?=_("月折旧额")?></td>
		   <td><input type="text" name="MON_DPCT" class="SmallInput" value="<?=$MON_DPCT?>" size="40"></td>
	 </tr>
    <tr class="TableData" align="center">
	     <td><?=_("折旧")?></td>
	     <td><input type="text" name="FINISH_FLAG" class="SmallInput" value="<?=$FINISH_FLAG?>" size="40"></td>
    </tr>
    <tr class="TableData" align="center">
       <td><?=_("启用日期")?></td>
       <td><input type="text" name="FROM_YYMM" class="SmallInput" value="<?=$FROM_YYMM?>" size="40"></td>
    </tr>
    <tr class="TableData" align="center">
       <td><?=_("保管人")?></td>
       <td><input type="text" name="KEEPER" class="SmallInput" value="<?=$KEEPER?>" size="40"></td>
    </tr>
    <tr class="TableData" align="center">
		 <td><?=_("备注")?></td>
		 <td><input type="text" name="REMARK" class="SmallInput" value="<?=$REMARK?>" size="40"></td>
	 </tr>
    <tr class="TableData" align="center">
    	<td colspan="3">
    		<input type="hidden" name = "IS_SET" value="<?=$IS_SET?>" >
  			<input type="hidden" name = "CID" value="<?=$ID?>" >
    		<input type="submit" name="submit" value="<?=_("保存")?>" class="SmallButton" >
  		</td>
  	</tr>
</form>
</table>
</body>
</html>