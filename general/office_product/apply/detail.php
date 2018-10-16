<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("办公用品编辑");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">

<body>
    <div class="row-fluid" align="center">
        <div class='span8' style='float:none;'>
        <h3><?=_("办公用品信息")?></h3>
        <? 
        	$query="select * from office_products where pro_id='{$PRO_ID}'";
        	$cursor = exequery ( TD::conn (), $query );
        	while ( $ROW = mysql_fetch_array ( $cursor ) )
        	{
        ?>
            <form action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
                <table width="500" class="table table-bordered center">
                    <tr>
                    <td nowrap class="TableData" width="30%"><?=_("办公用品名称：")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_NAME']?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("规格/型号：")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_DESC']?></td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("附件：")?> </td>
                    <td nowrap class="TableData"><?=attach_link($ROW['ATTACHMENT_ID'],$ROW['ATTACHMENT_NAME'],1,1,1)?></td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("办公用品类别：")?> </td>
                    <td nowrap class="TableData"><?=$ROW['OFFICE_PROTYPE']?></td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("办公用品编码：")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_CODE']?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("单价：")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_PRICE']?><?=_("元")?><?=$ROW['PRO_UNIT']?'/'.$ROW['PRO_UNIT']:''?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("供应商：")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_SUPPLIER']?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("最低警戒库存：")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_LOWSTOCK']?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("最高警戒库存：")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_MAXSTOCK']?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("当前库存：")?> </td>
                    <td nowrap class="TableData">
                	<? 
                	//#红色标示 王瑞杰
                		$red = "";
                		$is_apply = "";
                		if ($PRO_STOCK1 > $PRO_MAXSTOCK1)
                			$red = "<font color=red>&nbsp;&nbsp;"._("高于最高警戒库存")."</font>"; 
                		if ($PRO_STOCK1 < $PRO_LOWSTOCK1)
                		   $red = "<font color=red>&nbsp;&nbsp;"._("低于最低警戒库存")."</font>";
                		
                		echo $ROW['PRO_STOCK'].$red;
                		$is_apply = (intval($ROW['PRO_STOCK']) ? "" : " disabled");
                    ?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("当前库存总价：")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_STOCK']*$ROW['PRO_PRICE']?><?=_("元")?>&nbsp;</td>
                   </tr>
                   <tr >
                    <td colspan="2" style='text-align:center;'>
                    	  <input type="button" value="<?=_("申请")?>" <?=$is_apply?> class="btn btn-small btn-primary" onClick="location.href='../apply/apply_one.php?id=<?=$PRO_ID?>&type=2'">
                       <!--   <input type="button" value="<?=_("返回")?>" class="btn btn-small btn-primary" title="<?=_("返回查询页面")?>" name="button1" onClick="history.go(-1)">-->
                    </td>
                   </tr>
                </table>
            </form>
            <? } ?>
        </div>
    </div>
</body>
</html>