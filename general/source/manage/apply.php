<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("资源申请");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script language="javascript" src="rightclick.js"></script>
<script language=JavaScript>
function changeColor(obj,name,color)/*改变单元格的颜色*/
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
        obj.bgColor = '#ff33ff';//撤销已申请
        formColor.value='#ff33ff';
        return;
    }
    if(obj.bgColor == '#ff33ff' )
    {
        obj.bgColor = '#0000ff';	//撤销‘撤销已申请’
        formColor.value='#0000ff';
        return;
    }
    if(obj.bgColor=='#ff0000')//管理员撤销其他人的申请
    {
        obj.bgColor = '#ff33ff';	//撤销‘撤销已申请’
        formColor.value='#ff33ff';
        return;
    }
    if(obj.bgColor == '#00ff00')//绿色表示申请的
    {
        obj.bgColor = '';	//撤销申请
        formColor.value='';
        return;
    }

    obj.bgColor = '#00ff00';	//自己即将申请的
    formColor.value='#00ff00';
}
</script>

<body onselectstart="return false">

<?

//修改事务提醒状态--yc
update_sms_status('76',$SOURCEID);


//---- 资源参数 ----
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
    Message(_("提示"),_("未设定资源申请日期或时间段"));
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
        <td><b><?=_("图例说明：")?></b></td>
        <td width=20 bgColor="#00ff00"></td>
        <td width=40><?=_("申请")?></td>
        <td width=20 bgColor="#ff33ff""></td>
        <td width=40><?=_("撤销")?></td>
        <td width=20 bgColor="#ff0000"></td>
        <td width=80><?=_("他人已经申请")?></td>
        <td width=20 bgColor="#0000ff"></td>
        <td width=80><?=_("本人已经申请")?></td>
        <td width=20 bgColor="#ff9966"></td>
        <td width=90><?=_("管理员周期安排")?></td>
        <?
        if($MANAGE_PRIV==1)
        {
            ?>
            <td width=180><b><?=_("身份：管理员")?></b> <a href="history.php?SOURCEID=<?=$SOURCEID?>"><?=_("查看历史记录")?></a></td>
            <?
        }
        ?>
    </tr>
</table>

<form name=form1 action="submit.php" method=post>
    <h4><?=_("资源申请：")?><?=$SOURCENAME?></h4>
    <? if($REMARK!="")
    {
        ?>
        <font color="red"><?=_("资源备注：")?><?=$REMARK?></font>
        <?
    }
    ?>
    <table style="width:100%;border:2px;" class="small" cellspacing="1" cellpadding="1">
        <tr class=TableHeader>
            <td style="width:3%;"><?=_("日期")?></td>
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
            //查询该日的用户
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
                        $USER_ID_CYCLE=$ROW1['USER_ID']; //重复取值的话取申请时间比较早的为准
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
                        if(($USER_ARRAY[$J]==""||$USER_ARRAY[$J]==="0"))//没有申请
                        {
                            $APPLY_VALUE="0";
                            $COLOR="";
                        }
                        //....本身还有||$MANAGE_PRIV==1 这个
                        else if($USER_ARRAY[$J]==$_SESSION["LOGIN_USER_ID"] ||$MANAGE_PRIV==1)//自己申请
                        {
                            $APPLY_VALUE=$USER_ARRAY[$J];
                            $COLOR="#0000ff";
                        }
                        else //他人申请
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
                        <? if($APPLY_VALUE==="0"){?> title='<?=_("无人申请")?>' <? }else {?> title='<?=$USER_NAME?>' <?	}?> <? if(($COLOR!="#ff0000") && $IS_CYCLE==0){?>  onclick="changeColor(this,'<?=$APPLY_DATE?>_<?=$J?>','<?=$APPLY_DATE?>_<?=$J?>_COLOR')"  style="cursor:pointer"   <? } ?>bgcolor="<?=$COLOR?>"  >
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
                <input class="BigButton" type=submit disabled name="savedata" value="<?=_("提交")?>" >&nbsp;&nbsp;
                <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close()">
            </td>
        </tr>
    </table>
</form>
<?
MESSAGE(_("资源申请说明"),_("用户单击可撤销，双击可修改自己所申请的资源信息，管理员可撤销、修改所有用户的申请的资源信息。另外由管理员周期性安排的资源使用信息均由管理员在周期性资源安排下进行操作。如有冲突，由管理员周期性资源安排下使用信息优先。"));
exit;

?>
</body>
</HTML>
