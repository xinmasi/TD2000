<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("�̶��ʲ�ά������");
include_once("inc/header.inc.php");

?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
    if(document.form1.KEEP_TYPE.value=="-1")
    {
        alert("<?=_("��ѡ��ά�����ͣ�")?>");
        form1.KEEP_TYPE.focus();
        return (false);
    }
    if(document.form1.KEEP_TIME.value=="")
    {
        alert( "<?=_("ά��ʱ�䲻��Ϊ�գ�")?>");
        form1.KEEP_TIME.focus();
        return (false);
    }
    document.form1.submit();
}
function delete_vote(KEEP_ID,CPTL_ID)
{
    msg='<?=_("ȷ��Ҫɾ��������¼��")?>';
    if(window.confirm(msg))
    {
        URL="delete_keep.php?KEEP_ID="+KEEP_ID+"&CPTL_ID="+CPTL_ID;
        window.location=URL;
    }
}

function closeWindow(){
    window.parent.opener.location.href=window.parent.opener.location.href;window.parent.close();
}
</script>

<body class="bodycolor">
<?
$query1="select CPTL_NAME from CP_CPTL_INFO where CPTL_ID='$CPTL_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
    $CPTL_NAME=$ROW["CPTL_NAME"];
if($KEEP_ID!="")  //ѡ�в����鿴�е��޸�
{
    $query = "SELECT * from CP_ASSET_KEEP where KEEP_ID='$KEEP_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $VOTE_COUNT++;
        $KEEP_ID=$ROW["KEEP_ID"];
        $CPTL_ID=$ROW["CPTL_ID"];
        $KEEP_TYPE=$ROW["KEEP_TYPE"];
        $KEEP_USER=$ROW["KEEP_USER"];
        $KEEP_TIME=$ROW["KEEP_TIME"];
        $REMIND_TIME=$ROW["REMIND_TIME"];
        $REMARK=$ROW["REMARK"];
        $query1="select USER_NAME from USER where USER_ID='$KEEP_USER'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
            $KEEP_USER_NAME=$ROW["USER_NAME"];
        else
            $KEEP_USER_NAME=$KEEP_USER;
    }
}
//�޸���������״̬--yc
update_sms_status('47',$CPTL_ID);

?>
<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <?
        if($TRANS_ID=="")
        {
            ?>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3">&nbsp;<?=_("�½�ά���Ǽ�")?></span></td>
            <?
        }
        else
        {
            ?>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" align="absmiddle" width="20" height="20"><span class="big3">&nbsp;<?=_("�޸�ά���Ǽ�")?></span></td>
            <?
        }
        ?>
    </tr>
</table>
<br>
<table width="80%" align="center" class="TableBlock">
    <form enctype="multipart/form-data" action="add_keep.php"  method="post" name="form1">
        <tr>
            <td nowrap class="TableData"><?=_("ά�����ͣ�")?></td>
            <td class="TableData">
                <select name="KEEP_TYPE" class="BigSelect" >
                    <option value="-1" ><?=_("��ѡ��ά������")?></option>
                    <?=code_list("ASSET_KEEP_TYPE","$KEEP_TYPE")?>
                </select><br>(<?=_("����ϵͳ����->ϵͳ����->�̶��ʲ�ά������������")?>)
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("ά�����ڣ�")?></td>
            <td class="TableData">
                <input type="text" name="KEEP_TIME" size="10" maxlength="10" class="BigInput" value="<?=$KEEP_TIME?>" onClick="WdatePicker()" >

            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ע��")?></td>
            <td class="TableData">
                <textarea name="REMARK" cols="35" rows="4" class="BigInput"><?=$REMARK?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("ά����Ա")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="<?=$KEEP_USER?>">
                <input type="text" name="TO_NAME"  size="20" class="BigInput" maxlength="20"  value="<?=$KEEP_USER_NAME?>">&nbsp;
                <input type="button" value="<?=_("ѡ��")?>" class="SmallButton" onClick="SelectUserSingle('108','','TO_ID','TO_NAME')" title="<?=_("ѡ��")?>" name="button">&nbsp;
                <input type="button" value="<?=_("���")?>" class="SmallButton" onClick="ClearUser()" title="<?=_("���")?>" name="button">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("���ѣ�")?></td>
            <td class="TableData"><?=sms_remind(47);?></td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("����ʱ��")?></td>
            <td class="TableData">
                <input type="text" class="BigStatic" name="REMIND_TIME" value="<?=$REMIND_TIME?>" size="19" maxlength="19" readonly  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">

                <br /><font color="red"><?=_("Ϊ������ά�����ڵ���8���������")?></font>
            </td>
        </tr>
        <tfoot align="center" class="TableFooter">
        <td colspan="2" nowrap>
            <input type="hidden" name="CPTL_ID" value="<?=$CPTL_ID?>">
            <input type="hidden" name="CPTL_NAME" value="<?=$CPTL_NAME?>">
            <input type="hidden" name="KEEP_ID" value="<?=$KEEP_ID?>">
            <input type="button" value="<?=_("ȷ��")?>" class="BigButton" onClick="CheckForm();">&nbsp;&nbsp;
            <?
            if($KEEP_ID!="")
            {
                ?>
                <input type="button" value="<?=_("ȡ��")?>" class="BigButton" onClick="location='keep.php?CPTL_ID=<?=$CPTL_ID?>&KEEP_ID=<?=$KEEP_ID?>'">
            <?  }?>
            <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();">
        </td>
        </tfoot>
</table>
</form>
<?
//============================��ʾ�������=======================================
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from CP_ASSET_KEEP where CPTL_ID='$CPTL_ID'  order by KEEP_ID desc";
$cursor= exequery(TD::conn(),$query);
$VOTE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $VOTE_COUNT++;
    $KEEP_ID=$ROW["KEEP_ID"];
    $CPTL_ID=$ROW["CPTL_ID"];
    $KEEP_TYPE=$ROW["KEEP_TYPE"];
    $KEEP_USER=$ROW["KEEP_USER"];
    $KEEP_TIME=$ROW["KEEP_TIME"];
    $REMIND_TIME=$ROW["REMIND_TIME"];
    $REMARK=$ROW["REMARK"];
    $KEEP_RESULT=$ROW["KEEP_RESULT"];
    $query1="select CPTL_NAME from CP_CPTL_INFO where CPTL_ID='$CPTL_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $CPTL_NAME=$ROW["CPTL_NAME"];


    $query1="select USER_NAME from USER where USER_ID='$KEEP_USER'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $KEEP_USER_NAME=$ROW["USER_NAME"];
    else
        $KEEP_USER_NAME=$KEEP_USER;

    if($VOTE_COUNT==1)
    {
?>
    <table width="80%" border="0" cellspacing="0" cellpadding="0" height="3">
        <tr>
            <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="80%"></td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" WIDTH="22" HEIGHT="22" align="absmiddle"><span class="big3">&nbsp;<?=_("ά����¼����")?></span></td>
        </tr>
    </table>
<table width="80%" class="TableList" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("�̶��ʲ�����")?></td>
        <td nowrap align="center"><?=_("ά������")?></td>
        <td nowrap align="center"><?=_("ά����Ա")?></td>
        <td nowrap align="center"><?=_("ά������")?></td>
        <td nowrap align="center"><?=_("����ʱ��")?></td>
        <td nowrap align="center"><?=_("ά�����")?></td>
        <td nowrap align="center"><?=_("����")?></td>
    </tr>
    <?
    }
    if($VOTE_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
    ?>
    <tr class="<?=$TableLine?>" title="<?=$TITLE?>">
        <td nowrap align="center"><?=$CPTL_NAME?></td>
        <td align="center"><?=get_code_name($KEEP_TYPE,"ASSET_KEEP_TYPE")?></td>
        <td align="center"><?=$KEEP_USER_NAME?></td>
        <td nowrap align="center"><?=$KEEP_TIME?></td>
        <td nowrap align="center"><?=$REMIND_TIME?></td>
        <td nowrap align="center"><?=$KEEP_RESULT?></td>
        <td nowrap align="center">&nbsp;
            <a href="keep.php?KEEP_ID=<?=$KEEP_ID?>&CPTL_ID=<?=$CPTL_ID?>"><?=_("�޸�")?></a>&nbsp;
            <a href="#" onclick=window.open("keep_result.php?KEEP_ID=<?=$KEEP_ID?>&CPTL_ID=<?=$CPTL_ID?>&KEEP_RESULT=<?=$KEEP_RESULT?>","<?=_("���ά�����")?>",'height=150,width=350,status=0,toolbar=no,menubar=no,location=no,left=300,top=200,scrollbars=yes,resizable=yes')><?=_("ά�����")?></a>&nbsp;
            <a href="javascript:delete_vote(<?=$KEEP_ID?>,<?=$CPTL_ID?>);"> <?=_("ɾ��")?></a>
        </td>
    </tr>
    <?
    }
    if($VOTE_COUNT>0)
    {
    ?>
</table>
<?
}
?>
</body>
</html>
