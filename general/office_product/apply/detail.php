<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("�칫��Ʒ�༭");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">

<body>
    <div class="row-fluid" align="center">
        <div class='span8' style='float:none;'>
        <h3><?=_("�칫��Ʒ��Ϣ")?></h3>
        <? 
        	$query="select * from office_products where pro_id='{$PRO_ID}'";
        	$cursor = exequery ( TD::conn (), $query );
        	while ( $ROW = mysql_fetch_array ( $cursor ) )
        	{
        ?>
            <form action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
                <table width="500" class="table table-bordered center">
                    <tr>
                    <td nowrap class="TableData" width="30%"><?=_("�칫��Ʒ���ƣ�")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_NAME']?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("���/�ͺţ�")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_DESC']?></td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("������")?> </td>
                    <td nowrap class="TableData"><?=attach_link($ROW['ATTACHMENT_ID'],$ROW['ATTACHMENT_NAME'],1,1,1)?></td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("�칫��Ʒ���")?> </td>
                    <td nowrap class="TableData"><?=$ROW['OFFICE_PROTYPE']?></td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("�칫��Ʒ���룺")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_CODE']?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("���ۣ�")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_PRICE']?><?=_("Ԫ")?><?=$ROW['PRO_UNIT']?'/'.$ROW['PRO_UNIT']:''?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("��Ӧ�̣�")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_SUPPLIER']?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("��;����棺")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_LOWSTOCK']?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("��߾����棺")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_MAXSTOCK']?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("��ǰ��棺")?> </td>
                    <td nowrap class="TableData">
                	<? 
                	//#��ɫ��ʾ �����
                		$red = "";
                		$is_apply = "";
                		if ($PRO_STOCK1 > $PRO_MAXSTOCK1)
                			$red = "<font color=red>&nbsp;&nbsp;"._("������߾�����")."</font>"; 
                		if ($PRO_STOCK1 < $PRO_LOWSTOCK1)
                		   $red = "<font color=red>&nbsp;&nbsp;"._("������;�����")."</font>";
                		
                		echo $ROW['PRO_STOCK'].$red;
                		$is_apply = (intval($ROW['PRO_STOCK']) ? "" : " disabled");
                    ?>&nbsp;</td>
                   </tr>
                   <tr>
                    <td nowrap class="TableData"><?=_("��ǰ����ܼۣ�")?> </td>
                    <td nowrap class="TableData"><?=$ROW['PRO_STOCK']*$ROW['PRO_PRICE']?><?=_("Ԫ")?>&nbsp;</td>
                   </tr>
                   <tr >
                    <td colspan="2" style='text-align:center;'>
                    	  <input type="button" value="<?=_("����")?>" <?=$is_apply?> class="btn btn-small btn-primary" onClick="location.href='../apply/apply_one.php?id=<?=$PRO_ID?>&type=2'">
                       <!--   <input type="button" value="<?=_("����")?>" class="btn btn-small btn-primary" title="<?=_("���ز�ѯҳ��")?>" name="button1" onClick="history.go(-1)">-->
                    </td>
                   </tr>
                </table>
            </form>
            <? } ?>
        </div>
    </div>
</body>
</html>