<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/flow_hook.php");
$connstatus = ($connstatus) ? true : false;
$query="select * from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $PARA_VALUE=$ROW["PARA_VALUE"];
//$SMS2_REMIND=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND_TMP=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND=substr($SMS2_REMIND_TMP,0,strpos($SMS2_REMIND_TMP,"|"));

$HTML_PAGE_TITLE = _("����Ǽ�");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script>
    function CheckForm()
    {

        if(document.form1.OUT_TIME1.value=="" || document.form1.OUT_TIME2.value=="")
        {
            alert("<?=_("�����ֹʱ�䲻��Ϊ�գ�")?>");
            return (false);
        }

        if(document.form1.OUT_TYPE.value=="")
        {
            alert("<?=_("���ԭ����Ϊ�գ�")?>");
            return (false);
        }

        return (true);
    }

    function out_back_edit(OUT_ID,SUBMIT_TIME,OUT_TIME2)
    {
        URL="out_back_edit.php?OUT_ID="+OUT_ID+"&SUBMIT_TIME="+SUBMIT_TIME+"&OUT_TIME2="+OUT_TIME2;
        myleft=(screen.availWidth-780)/2;
        mytop=100;
        mywidth=650;
        myheight=400;
        window.open(URL,"out_back_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
    }
    function form_view(RUN_ID)
    {
        window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
    }

    function delete_alert(OUT_ID)
    {
        msg='<?=_("ȷ��Ҫɾ���������Ϣ��")?>';

        if(window.confirm(msg))
        {
            URL="delete.php?OUT_ID=" + OUT_ID;
            window.location=URL;
        }
    }
</script>


<body class="bodycolor attendance">

<h5 class="attendance-title"><span class="big3"><?=_("����Ǽ�")?></span></h5>

<br>
<div align="center">
    <div align="center">
        <input type="button"  value="<?=_("����Ǽ�")?>" class="btn btn-primary" onClick="location='new/';" title="<?=_("�½�����Ǽ�")?>">&nbsp;&nbsp;
        <input type="button"  value="<?=_("�����ʷ��¼")?>" class="btn" onClick="location='history.php';" title="<?=_("�鿴�����������¼")?>">
        <br>

        <br>
        <table class="table  table-bordered" >
            <thead>
            <tr>
                <th nowrap align="center"><?=_("����ʱ��")?></th>
                <th nowrap align="center"><?=_("������Ա")?></th>
                <th nowrap align="center"><?=_("���ԭ��")?></th>
                <th nowrap align="center"><?=_("��ʼʱ��")?></th>
                <th nowrap align="center"><?=_("����ʱ��")?></th>
                <th nowrap align="center"><?=_("״̬")?></th>
                <th nowrap align="center"><?=_("����")?></th>
            </tr>
            </thead>
            <?
            //�޸���������״̬--yc
            update_sms_status('6',0);

            //---- �鿴����Ǽ���� -----
            $OUT_COUNT=0;
            $CUR_DATE=date("Y-m-d",time());
            $query = "SELECT * from ATTEND_OUT where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='0' order by SUBMIT_TIME";
            $cursor= exequery(TD::conn(),$query, $connstatus);
            while($ROW=mysql_fetch_array($cursor))
            {
                $OUT_COUNT++;

                $OUT_ID=$ROW["OUT_ID"];
                $CREATE_DATE=$ROW["CREATE_DATE"];
                $OUT_TIME1=$ROW["OUT_TIME1"];
                $OUT_TIME2=$ROW["OUT_TIME2"];
                $OUT_TYPE=$ROW["OUT_TYPE"];
                $ALLOW=$ROW["ALLOW"];
                $LEADER_ID=$ROW["LEADER_ID"];
                $REASON=$ROW["REASON"];

                $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
                $SUBMIT_TIME1=substr($SUBMIT_TIME,0,10);

                $USER_NAME="";
                $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
                $cursor1= exequery(TD::conn(),$query);
                if($ROW=mysql_fetch_array($cursor1))
                    $USER_NAME=$ROW["USER_NAME"];

                $OUT_TYPE=str_replace("<","&lt",$OUT_TYPE);
                $OUT_TYPE=str_replace(">","&gt",$OUT_TYPE);
                $OUT_TYPE=gbk_stripslashes($OUT_TYPE);

                if($ALLOW=="0")
                    $ALLOW_DESC=_("������");
                else if($ALLOW=="1")
                    $ALLOW_DESC="<font color=green>"._("����׼")."</font>";
                else if($ALLOW=="2")
                    $ALLOW_DESC="<font color=red>"._("δ��׼")."</font>";
                ?>
                <tr>
                    <td nowrap align="center"><?=$CREATE_DATE?></td>
                    <?
                    $is_run_hook=is_run_hook("OUT_ID",$OUT_ID);
                    if($is_run_hook!=0)
                    {
                        ?>
                        <td nowrap align="center"><a href="javascript:;" onClick="form_view('<?=$is_run_hook?>')"><?=_("�鿴����")?></a></td>
                        <?
                    }
                    else
                    {
                        ?>
                        <td  align="center"><?=$USER_NAME?></td>
                        <?
                    }
                    ?>
                    <td style="word-break:break-all" align="left"><?=$OUT_TYPE?></td>
                    <td nowrap align="center"><?=$SUBMIT_TIME1?> <?=$OUT_TIME1?></td>
                    <td nowrap align="center"><?=$SUBMIT_TIME1?> <?=$OUT_TIME2?></td>
                    <td nowrap align="center" title="<?if($ALLOW==2) echo _("ԭ��")."\n".$REASON?>"><?=$ALLOW_DESC?></td>
                    <td nowrap align="center">
                        <?
                        if($ALLOW=="0" || $ALLOW=="2"||($ALLOW=="1" && $SUBMIT_TIME1>$CUR_DATE))
                        {
                            if($is_run_hook!=0)
                            {
                                $query2 = "SELECT * from FLOW_RUN where RUN_ID='$is_run_hook' and DEL_FLAG='0'";
                                $cursor2= exequery(TD::conn(),$query2);
                                if(!$ROW2=mysql_fetch_array($cursor2))
                                {
                                    ?>
                                    <a href="delete.php?OUT_ID=<?=$OUT_ID?>"><?=_("ɾ��")?></a>
                                    <?
                                }
                            }
                            else
                            {
                                ?>
                                <a href="edit.php?OUT_ID=<?=$OUT_ID?>"><?=_("�޸�")?></a>
                                <a href="javascript:delete_alert('<?=$OUT_ID?>');"><?=_("ɾ��")?></a>
                                <?
                            }
                        }
                        else
                        {
                            ?>
                            <a href=javascript:out_back_edit('<?=$OUT_ID?>','<?=urlencode($SUBMIT_TIME)?>','<?=$OUT_TIME2?>');><?=_("�������")?></a>
                            <?
                        }
                        ?>
                    </td>
                </tr>
                <?
            }

            if($OUT_COUNT<=0)
            {
                ?>
                <tr><td colspan="7"><div class="emptyTip"><?=_("�������¼")?></div></td></tr>
                <?
            }
            ?>
        </table>
    </div>
</body>
</html>