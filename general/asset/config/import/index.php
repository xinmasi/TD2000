<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("�̶��ʲ������ֶ�ƥ������");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"><?=_("�̶��ʲ������ֶ�ƥ������")?></span>
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
			<td><?=_("���ݿ��ֶ�����")?></td>
			<td>excel<?=_("�ֶ�����")?></td>
		</tr>
  		<tr class="TableContent" align="center">
			<td><?=_("�ʲ����")?></td>
			<td><input type="text" name="CPTL_NO" class="SmallInput" value="<?=$CPTL_NO?>" size="40"></td>
		</tr>
		<tr class="TableData" align="center">
    		<td><?=_("�ʲ�����")?></td>
			<td><input type="text" name="CPTL_NAME" class="SmallInput" value="<?=$CPTL_NAME?>" size="40"></td>
		</tr>
		<tr class="TableData" align="center">
			<td><?=_("�ʲ����")?></td>
			<td><input type="text" name="TYPE_ID" class="SmallInput" value="<?=$TYPE_ID?>" size="40"></td>
		</tr>
		<tr class="TableData" align="center">
			<td><?=_("��������")?></td>
			<td><input type="text" name="DEPT_ID" class="SmallInput" value="<?=$DEPT_ID?>" size="40"></td>
		</tr>
		<tr class="TableData" align="center">
			<td><?=_("�ʲ�����")?></td>
    		<td><input type="text" name="CPTL_KIND" class="SmallInput" value="<?=$CPTL_KIND?>" size="40"></td>
  		</tr>
  		<tr class="TableData" align="center">
   		<td><?=_("��������")?></td>
    		<td><input type="text" name="PRCS_ID" class="SmallInput" value="<?=$PRCS_ID?>" size="40"></td>
 		</tr>
  		<tr class="TableData" align="center">
   		<td><?=_("�ʲ�ԭֵ")?></td>
    		<td><input type="text" name="CPTL_VAL" class="SmallInput" value="<?=$CPTL_VAL?>" size="40"></td>
  		</tr>
  		<tr class="TableData" align="center">
    		<td><?=_("��ֵ��")?></td>
    		<td><input type="text" name="CPTL_BAL" class="SmallInput" value="<?=$CPTL_BAL?>" size="40"></td>
 		</tr>
	  <tr class="TableData" align="center">
	  		<td><?=_("�۾�����")?></td>
	    	<td><input type="text" name="DPCT_YY" class="SmallInput" value="<?=$DPCT_YY?>" size="40"></td>
	  </tr>
	  <tr class="TableData" align="center">
	   	<td><?=_("�ۼ��۾�")?></td>
	    	<td><input type="text" name="SUM_DPCT" class="SmallInput" value="<?=$SUM_DPCT?>" size="40"></td>
	  </tr>
  	  <tr class="TableData" align="center">
		   <td><?=_("���۾ɶ�")?></td>
		   <td><input type="text" name="MON_DPCT" class="SmallInput" value="<?=$MON_DPCT?>" size="40"></td>
	 </tr>
    <tr class="TableData" align="center">
	     <td><?=_("�۾�")?></td>
	     <td><input type="text" name="FINISH_FLAG" class="SmallInput" value="<?=$FINISH_FLAG?>" size="40"></td>
    </tr>
    <tr class="TableData" align="center">
       <td><?=_("��������")?></td>
       <td><input type="text" name="FROM_YYMM" class="SmallInput" value="<?=$FROM_YYMM?>" size="40"></td>
    </tr>
    <tr class="TableData" align="center">
       <td><?=_("������")?></td>
       <td><input type="text" name="KEEPER" class="SmallInput" value="<?=$KEEPER?>" size="40"></td>
    </tr>
    <tr class="TableData" align="center">
		 <td><?=_("��ע")?></td>
		 <td><input type="text" name="REMARK" class="SmallInput" value="<?=$REMARK?>" size="40"></td>
	 </tr>
    <tr class="TableData" align="center">
    	<td colspan="3">
    		<input type="hidden" name = "IS_SET" value="<?=$IS_SET?>" >
  			<input type="hidden" name = "CID" value="<?=$ID?>" >
    		<input type="submit" name="submit" value="<?=_("����")?>" class="SmallButton" >
  		</td>
  	</tr>
</form>
</table>
</body>
</html>