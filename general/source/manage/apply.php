<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("��Դ����");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script language="javascript" src="rightclick.js"></script>
<script language=JavaScript>
function changeColor(obj,name,color)/*�ı䵥Ԫ�����ɫ*/
{
    var formItem = document.getElementById(name);
    var formColor = document.getElementById(color);
    if(formItem.value == 0 || formItem.value == 1)
        formItem.value = 1-formItem.value;
    else if(formItem.value==-1)
        formItem.value = formItem.title;
    else
        formItem.value = -1;

    //document.getElementsByName("save").disabled = false;
    document.form1.savedata.disabled = false;

    if(obj.bgColor == '#0000ff')
    {
        obj.bgColor = '#ff33ff';//����������
        formColor.value='#ff33ff';
        return;
    }
    if(obj.bgColor == '#ff33ff' )
    {
        obj.bgColor = '#0000ff';	//���������������롯
        formColor.value='#0000ff';
        return;
    }
    if(obj.bgColor=='#ff0000')//����Ա���������˵�����
    {
        obj.bgColor = '#ff33ff';	//���������������롯
        formColor.value='#ff33ff';
        return;
    }
    if(obj.bgColor == '#00ff00')//��ɫ��ʾ�����
    {
        obj.bgColor = '';	//��������
        formColor.value='';
        return;
    }

    obj.bgColor = '#00ff00';	//�Լ����������
    formColor.value='#00ff00';
}
</script>

<body onselectstart="return false">

<?

//�޸���������״̬--yc
update_sms_status('76',$SOURCEID);


//---- ��Դ���� ----
$query = "select * from OA_SOURCE where SOURCEID='$SOURCEID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $SOURCENAME = $ROW["SOURCENAME"];
    $DAY_LIMIT = $ROW["DAY_LIMIT"];
    $WEEKDAY_SET = $ROW["WEEKDAY_SET"];
    $TIME_TITLE = $ROW["TIME_TITLE"];
    $MANAGE_USER = $ROW["MANAGE_USER"];
    $REMARK = $ROW["REMARK"];
}

if($WEEKDAY_SET==""||$DAY_LIMIT==""||$TIME_TITLE=="")
{
    Message(_("��ʾ"),_("δ�趨��Դ�������ڻ�ʱ���"));
    exit;
}

$TIME_ARRAY=explode(",",$TIME_TITLE);
$ARRAY_COUNT=sizeof($TIME_ARRAY);
if($TIME_ARRAY[$ARRAY_COUNT-1]=="")
    $ARRAY_COUNT--;

if(find_id($MANAGE_USER,$_SESSION["LOGIN_USER_ID"]))
    $MANAGE_PRIV=1;
?>

<table class=small>
    <tr>
        <td><b><?=_("ͼ��˵����")?></b></td>
        <td width=20 bgColor="#00ff00"></td>
        <td width=40><?=_("����")?></td>
        <td width=20 bgColor="#ff33ff""></td>
        <td width=40><?=_("����")?></td>
        <td width=20 bgColor="#ff0000"></td>
        <td width=80><?=_("�����Ѿ�����")?></td>
        <td width=20 bgColor="#0000ff"></td>
        <td width=80><?=_("�����Ѿ�����")?></td>
        <td width=20 bgColor="#ff9966"></td>
        <td width=90><?=_("����Ա���ڰ���")?></td>
        <?
        if($MANAGE_PRIV==1)
        {
            ?>
            <td width=180><b><?=_("��ݣ�����Ա")?></b> <a href="history.php?SOURCEID=<?=$SOURCEID?>"><?=_("�鿴��ʷ��¼")?></a></td>
            <?
        }
        ?>
    </tr>
</table>

<form name=form1 action="submit.php" method=post>
    <h4><?=_("��Դ���룺")?><?=$SOURCENAME?></h4>
    <? if($REMARK!="")
    {
        ?>
        <font color="red"><?=_("��Դ��ע��")?><?=$REMARK?></font>
        <?
    }
    ?>
    <table style="width:100%;border:2px;" class="small" cellspacing="1" cellpadding="1">
        <tr class=TableHeader>
            <td style="width:3%;"><?=_("����")?></td>
            <?
            for($M=0;$M<$ARRAY_COUNT;$M++)
            {
                ?>
                <td><?=$TIME_ARRAY[$M]?></td>
                <?
            }?>
        </tr>
        <?
        $CUR_DATE=date("Y-m-d",time());
        for($I=0;$I<$DAY_LIMIT;$I++)
        {
            if($I%2==1)
                $TableLine="TableContent";
            else
                $TableLine="TableData";
            $APPLY_DATE=time()+$I*24*3600;
            if(!find_id($WEEKDAY_SET,date("w", $APPLY_DATE)))
            {
                $DAY_LIMIT++;
                continue;
            }
            $APPLY_DATE=date("Y-m-d",$APPLY_DATE);
            $APPLY_DATE_DESC=substr($APPLY_DATE,5);
            $WEEK_DAY=get_week($APPLY_DATE);
            $WEEK_DAY_CYCLE=date("w", strtotime($APPLY_DATE));
            //��ѯ���յ��û�
            $query = "select * from OA_SOURCE_USED where SOURCEID='$SOURCEID' and APPLY_DATE='$APPLY_DATE'";
            $cursor = exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
                $USER_ID = $ROW["USER_ID"];
            else
                $USER_ID="";
            $USER_ARRAY=explode(",",$USER_ID);

            ?>

            <tr class="<?=$TableLine?>" height="35" <?if($_SESSION["LOGIN_THEME"]==9)echo "style=background:'white'";?>>
                <td nowrap style="width:3%;"><b><?=$APPLY_DATE_DESC?><br><?=$WEEK_DAY?></b></td>
                <?
                for($J=0;$J<$ARRAY_COUNT;$J++)
                {
                    //...................
                    $TIME_TITLE_CUR=$TIME_ARRAY[$J];
                    $query1 = "select USER_ID from OA_CYCLESOURCE_USED where SOURCEID='$SOURCEID' and E_APPLY_TIME>='$APPLY_DATE' and B_APPLY_TIME<='$APPLY_DATE' and find_in_set('$WEEK_DAY_CYCLE',WEEKDAY_SET) and find_in_set('$TIME_TITLE_CUR',TIME_TITLE) order by APPLY_TIME asc limit 0,1";
                    $cursor1 = exequery(TD::conn(),$query1);
                    if($ROW1=mysql_fetch_array($cursor1))
                        $USER_ID_CYCLE=$ROW1['USER_ID']; //�ظ�ȡֵ�Ļ�ȡ����ʱ��Ƚ����Ϊ׼
                    else
                        $USER_ID_CYCLE="";

                    //........................
                    if($USER_ID_CYCLE!="")
                    {
                        $APPLY_VALUE=$USER_ID_CYCLE;
                        $COLOR="#ff9966";
                        $IS_CYCLE=1;
                    }
                    else
                    {
                        $IS_CYCLE=0;
                        if(($USER_ARRAY[$J]==""||$USER_ARRAY[$J]==="0"))//û������
                        {
                            $APPLY_VALUE="0";
                            $COLOR="";
                        }
                        //....������||$MANAGE_PRIV==1 ���
                        else if($USER_ARRAY[$J]==$_SESSION["LOGIN_USER_ID"] ||$MANAGE_PRIV==1)//�Լ�����
                        {
                            $APPLY_VALUE=$USER_ARRAY[$J];
                            $COLOR="#0000ff";
                        }
                        else //��������
                        {

                            $APPLY_VALUE=$USER_ARRAY[$J];
                            $COLOR="#ff0000";
                        }
                    }
                    if(!($APPLY_VALUE==="0"))
                    {
                        $query = "select * from USER where USER_ID='$APPLY_VALUE'";
                        $cursor = exequery(TD::conn(),$query);
                        if($ROW=mysql_fetch_array($cursor))
                            $USER_NAME = $ROW["USER_NAME"];
                        else
                            $USER_NAME = $APPLY_VALUE;
                    }

                    ?>
                    <td style="width:5%;"
                        <? if($APPLY_VALUE==="0"){?> title='<?=_("��������")?>' <? }else {?> title='<?=$USER_NAME?>' <?	}?> <? if(($COLOR!="#ff0000") && $IS_CYCLE==0){?>  onclick="changeColor(this,'<?=$APPLY_DATE?>_<?=$J?>','<?=$APPLY_DATE?>_<?=$J?>_COLOR')"  style="cursor:pointer"   <? } ?>bgcolor="<?=$COLOR?>"  >
                        <? if($APPLY_VALUE==="0")echo $TIME_ARRAY[$J];else
                        {	 if($MANAGE_PRIV==1 || $APPLY_VALUE == $_SESSION["LOGIN_USER_ID"]){?> <a ondblclick=window.open('update.php?SOURCE_ID=<?=$SOURCEID?>&USER_NAME=<?=$USER_NAME?>&APPLY_VALUE=<?=$APPLY_VALUE?>&APPLY_DATE_CUR=<?=$APPLY_DATE?>&APPLY_J=<?=$J?>','oa_sub_window','height=350,width=500,status=0,toolbar=no,menubar=no,location=no,left=300,top=200,scrollbars=yes,resizable=yes')><? }?><font color=#FFFFFF> <?=$USER_NAME?></font><? if($MANAGE_PRIV==1){?></a> <?}}?>
                        <INPUT TYPE="hidden" NAME="<?=$APPLY_DATE?>_<?=$J?>" id="<?=$APPLY_DATE?>_<?=$J?>" value="<?=$APPLY_VALUE?>" title="<?=$APPLY_VALUE?>">
                        <INPUT TYPE="hidden" NAME="<?=$APPLY_DATE?>_<?=$J?>_COLOR"  id="<?=$APPLY_DATE?>_<?=$J?>_COLOR">
                    </td>
                    <?
                }
                ?>
            </tr>
            <?
        }
        ?>
        <tr class=TableControl>
            <INPUT TYPE="hidden" NAME="SOURCEID" value="<?=$SOURCEID?>">
            <td colspan=100 align=center>
                <input class="BigButton" type=submit disabled name="savedata" value="<?=_("�ύ")?>" >&nbsp;&nbsp;
                <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close()">
            </td>
        </tr>
    </table>
</form>
<?
MESSAGE(_("��Դ����˵��"),_("�û������ɳ�����˫�����޸��Լ����������Դ��Ϣ������Ա�ɳ������޸������û����������Դ��Ϣ�������ɹ���Ա�����԰��ŵ���Դʹ����Ϣ���ɹ���Ա����������Դ�����½��в��������г�ͻ���ɹ���Ա��������Դ������ʹ����Ϣ���ȡ�"));
exit;

?>
</body>
</HTML>
