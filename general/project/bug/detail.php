<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
ob_end_clean();

function level_desc($level)
{
    switch ($level)
    {
        case "1": return _("非常高");
        case "2": return _("高");
        case "3": return _("普通");
        case "4": return _("低");
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
            <td class="tright"><strong><?= _("问题名称：") ?></strong></td>
            <td class="tleft"><?= $BUG_NAME ?></td>  	  	
        </tr>
        <tr>
            <td class="tright"><strong><?= _("优先级：") ?></strong></td>
            <td class="tleft"><span class="CalLevel<?= $LEVEL ?>" title="<?= level_desc($LEVEL) ?>"><?= level_desc($LEVEL) ?></span></td>
            </td>  	  	
        </tr>
        <tr>
            <td class="tright"><strong><?= _("问题描述：") ?></strong></td>
            <td class="tleft"><?= $BUG_DESC ?></td>  	  	
        </tr>
        <tr>
            <td class="tright"><strong><?= _("提交人：") ?></strong></td>
            <td class="tleft"><?= $BEGIN_USER_NAME ?></td>  	  	
        </tr>
        <tr>
            <td class="tright"><strong><?= _("最后处理期限：") ?></strong></td>
            <td class="tleft"> <?= $DEAD_LINE ?>
            </td>
        </tr>
        <tr>
            <td class="tright"><strong><?= _("附件文档：") ?></strong></td>
            <td class="tleft"><? if ($ATTACHMENT_ID) echo attach_link($ATTACHMENT_ID, $ATTACHMENT_NAME, 1, 1, 1, 1, 1, 1, 1, 1);
    else echo _("无附件"); ?></td>
        </tr>
        <tr>
            <td class="tright"><strong><?= _("处理记录：") ?></strong></td>
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