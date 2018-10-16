<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
ob_end_clean();

function level_desc($level)
{
    switch ($level)
    {
        case "1": return _("�ǳ���");
        case "2": return _("��");
        case "3": return _("��ͨ");
        case "4": return _("��");
    }
}

if ($BUG_ID)
{
    $query = "select * from PROJ_BUG WHERE BUG_ID='$BUG_ID'";
    $cursor = exequery(TD::conn(), $query);
    if ($ROW = mysql_fetch_array($cursor))
    {
        $BUG_NAME = $ROW["BUG_NAME"];
        $BUG_DESC = $ROW["BUG_DESC"];
        $LEVEL = $ROW["LEVEL"];
        $BEGIN_USER = $ROW["BEGIN_USER"];
        $DEAD_LINE = $ROW["DEAD_LINE"];
        $STATUS = $ROW["STATUS"];
        $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
        $RESULT = $ROW["RESULT"];
        $RESULT_ARR = explode("|*|", $RESULT);
    }

    if ($STATUS == 1)
    {
        $query = "update PROJ_BUG SET STATUS=2 WHERE BUG_ID='$BUG_ID'";
        exequery(TD::conn(), $query);
    }
    
    $BEGIN_USER_NAME = td_trim(GetUserNameById($BEGIN_USER));
    ?>

	
    <table class="table table1 table-bordered" width="80%">
		<colgroup class="ck-tableresize-colgroup">
			<col style="width:150px; min-width:150px;">
			<col>
		</colgroup>	
        <tr>
            <td class="tright"><strong><?= _("�������ƣ�") ?></strong></td>
            <td class="tleft"><?= $BUG_NAME ?></td>  	  	
        </tr>
        <tr>
            <td class="tright"><strong><?= _("���ȼ���") ?></strong></td>
            <td class="tleft"><span class="CalLevel<?= $LEVEL ?>" title="<?= level_desc($LEVEL) ?>"><?= level_desc($LEVEL) ?></span></td>
            </td>  	  	
        </tr>
        <tr>
            <td class="tright"><strong><?= _("����������") ?></strong></td>
            <td class="tleft"><?= $BUG_DESC ?></td>  	  	
        </tr>
        <tr>
            <td class="tright"><strong><?= _("�ύ�ˣ�") ?></strong></td>
            <td class="tleft"><?= $BEGIN_USER_NAME ?></td>  	  	
        </tr>
        <tr>
            <td class="tright"><strong><?= _("��������ޣ�") ?></strong></td>
            <td class="tleft"> <?= $DEAD_LINE ?>
            </td>
        </tr>
        <tr>
            <td class="tright"><strong><?= _("�����ĵ���") ?></strong></td>
            <td class="tleft"><? if ($ATTACHMENT_ID) echo attach_link($ATTACHMENT_ID, $ATTACHMENT_NAME, 1, 1, 1, 1, 1, 1, 1, 1);
    else echo _("�޸���"); ?></td>
        </tr>
        <tr>
            <td class="tright"><strong><?= _("�����¼��") ?></strong></td>
            <td class="tleft">
                <?
                foreach ($RESULT_ARR as $content)
                {
                    echo $content . "<br>";
                }
                ?>      
            </td>
        </tr>
    </table>
    <?
}
?>