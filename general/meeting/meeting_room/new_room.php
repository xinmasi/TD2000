<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("����������");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script Language="JavaScript">
function CheckForm()
{
    if(document.form1.MR_NAME.value=="")
    { alert("<?=_("��ָ�����������ƣ�")?>");
        return (false);
    }
    form1.submit();
}
</script>
<body class="bodycolor">

<?
$APPLY_WEEKDAYS_ARR=array();
if($MR_ID!="")
{
    $query = "SELECT * from MEETING_ROOM where MR_ID='$MR_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $MR_NAME        = $ROW["MR_NAME"];
        $MR_CAPACITY    = $ROW["MR_CAPACITY"];
        $MR_DEVICE      = $ROW["MR_DEVICE"];
        $MR_DESC        = $ROW["MR_DESC"];
        $MR_PLACE       = $ROW["MR_PLACE"];
        $OPERATOR       = $ROW['OPERATOR'];
        $TO_ID          = $ROW["TO_ID"];
        $SECRET_TO_ID   = $ROW["SECRET_TO_ID"];
        $VIDEO_TYPE     = $ROW["VIDEO_TYPE"];

        $APPLY_WEEKDAYS = $ROW["APPLY_WEEKDAYS"];
        if($APPLY_WEEKDAYS!="")
            $APPLY_WEEKDAYS_ARR=explode(",",$APPLY_WEEKDAYS);
        $query = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$OPERATOR."')";
        $cursor= exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
            $OPERATOR_NAME.=$ROW["USER_NAME"].",";

        $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$SECRET_TO_ID."')";
        $cursor1= exequery(TD::conn(),$query1);
        while($ROW1=mysql_fetch_array($cursor1))
        {
            $SECRET_TO_NAME.=$ROW1["USER_NAME"].",";
        }
        $TOK=strtok($TO_ID,",");
        while($TOK!="")
        {
            $query2 = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID='$TOK'";
            $cursor2= exequery(TD::conn(),$query2);
            if($ROW=mysql_fetch_array($cursor2))
                $DEPT_NAME.=$ROW["DEPT_NAME"].",";
            $TOK=strtok(",");
        }

        if($TO_ID=="ALL_DEPT")
            $DEPT_NAME=_("ȫ�岿��");
    }
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�������")?></span>
        </td>
    </tr>
</table><br>
<table class="TableBlock" align="center" width="70%">
    <form enctype="multipart/form-data" action="add.php" method="post" name="form1">
        <tr>
            <td nowrap class="TableData" width="80"><?=_("���������ƣ�")?></td>
            <td class="TableData" colspan="3">
                <input type="text" name="MR_NAME" size="40" maxlength="100" class="BigInput" value="<?=$MR_NAME?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("������������")?></td>
            <td class="TableData" colspan="3">
                <textarea name="MR_DESC" class="BigInput" cols="45" rows="3"><?=$MR_DESC?></textarea>
            </td>
        </tr>
        <tr bgcolor="#CCCCCC">
            <td class="TableData"><?=_("�����ҹ���Ա��")?></td>
            <td class="TableData">
                <input type="hidden" name="COPY_TO_ID" value="<?=$OPERATOR?>">
                <textarea cols=45 name="COPY_TO_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$OPERATOR_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('89','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
            </td>
        </tr>
        <tr>
        <tr bgcolor="#CCCCCC">
            <td class="TableData"><?=_("����Ȩ��(����)��")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                <textarea cols=45 name="TO_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$DEPT_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
            </td>
        </tr>
        <tr>
        <tr bgcolor="#CCCCCC">
            <td class="TableData"><?=_("����Ȩ��(��Ա)��")?></td>
            <td class="TableData">
                <input type="hidden" name="SECRET_TO_ID" value="<?=$SECRET_TO_ID?>">
                <textarea cols=45 name="SECRET_TO_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$SECRET_TO_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('89','','SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("���")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("������������")?></td>
            <td class="TableData" colspan="3">
                <input type="text" name="MR_CAPACITY" size="5" maxlength="100" class="BigInput" value="<?=$MR_CAPACITY?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("�Ƿ�֧����Ƶ���飺")?></td>
            <td class="TableData" colspan="3">
                <input type="radio" name="VIDEO_TYPE" id="VIDEO_TYPE1" value="1" <?if($VIDEO_TYPE==1) echo "checked";?>><label for="VIDEO_TYPE1"><?=_("��")?></label>
                <input type="radio" name="VIDEO_TYPE" id="VIDEO_TYPE2" value="0" <?if($VIDEO_TYPE==0) echo "checked";?>><label for="VIDEO_TYPE2"><?=_("��")?></label>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("������ʱ�䣺")?></td>
            <td class="TableData" colspan="3">
                <input type="checkbox" name="sun" <?=in_array('0',$APPLY_WEEKDAYS_ARR)?"checked":"";?> value="0" id="sun" /><label for="sun"><?=_("������")?></label>
                <input type="checkbox" name="mon" <?=in_array('1',$APPLY_WEEKDAYS_ARR)||empty($APPLY_WEEKDAYS_ARR)?"checked":"";?> value="1" id="mon" /><label for="mon"><?=_("����һ")?></label>
                <input type="checkbox" name="tue" <?=in_array('2',$APPLY_WEEKDAYS_ARR)||empty($APPLY_WEEKDAYS_ARR)?"checked":"";?> value="2" id="tue" /><label for="tue"><?=_("���ڶ�")?></label>
                <input type="checkbox" name="wed" <?=in_array('3',$APPLY_WEEKDAYS_ARR)||empty($APPLY_WEEKDAYS_ARR)?"checked":"";?> value="3" id="wed" /><label for="wed"><?=_("������")?></label>
                <input type="checkbox" name="thu" <?=in_array('4',$APPLY_WEEKDAYS_ARR)||empty($APPLY_WEEKDAYS_ARR)?"checked":"";?> value="4" id="thu" /><label for="thu"><?=_("������")?></label>
                <input type="checkbox" name="fri" <?=in_array('5',$APPLY_WEEKDAYS_ARR)||empty($APPLY_WEEKDAYS_ARR)?"checked":"";?> value="5" id="fri" /><label for="fri"><?=_("������")?></label>
                <input type="checkbox" name="sat" <?=in_array('6',$APPLY_WEEKDAYS_ARR)?"checked":"";?> value="6" id="sat" /><label for="sat"><?=_("������")?></label>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("�豸�����")?></td>
            <td class="TableData" colspan="3">
                <textarea name="MR_DEVICE" class="BigInput" cols="45" rows="3"><?=$MR_DEVICE?></textarea><br>
                <?=_("˵�������˸ô��ġ��豸������⣬���л������豸����ģ�飬���ԶԻ���������豸�������������ʱ�򣬿���ѡ����Ҫʹ�õ��豸��")?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("��ַ��")?></td>
            <td class="TableData" colspan="3">
                <input type="text" name="MR_PLACE" size="40" maxlength="100" class="BigInput" value="<?=$MR_PLACE?>">
            </td>
        </tr>
        <tr class="TableControl">
            <td nowrap colspan="4" align="center">
                <input type="hidden" value="<?=$MR_ID?>" name="MR_ID">
                <input type="button" value="<?=_("����")?>" class="BigButton" onclick="CheckForm();">&nbsp;&nbsp;
                <?
                if($MR_ID!="")
                {
                    ?>
                    <input type="button" class="BigButton" value="<?=_("����")?>" onclick="location='index.php';">
                    <?
                }
                else
                {
                    ?>
                    <input type="button" class="BigButton" value="<?=_("����")?>" onclick="location='index.php';">
                    <?
                }
                ?>
            </td>
        </tr>
    </form>
</table>

</body>
</html>