<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
$HTML_PAGE_TITLE = _("查看投票");
include_once("inc/header.inc.php");
include_once("show_functions.php");//显示vote信息的一些公用函数
$CUR_DATE=date("Y-m-d H:i:s",time());

function ParseItemName($ITEM_NAME,$ITEM_ID,$COUNT=1)
{
    $POS=strpos($ITEM_NAME, "{");
    if($POS===false)
        return $ITEM_NAME;

    if(substr($ITEM_NAME, $POS, 6)=="{text}")
        return substr($ITEM_NAME, 0, $POS)."<input name=VOTE_DATA_".$ITEM_ID."_".$COUNT++." type=text size=20 class=SmallInput>".ParseItemName(substr($ITEM_NAME, $POS+6),$ITEM_ID,$COUNT);
    if(substr($ITEM_NAME, $POS, 8)=="{number}")
        return substr($ITEM_NAME, 0, $POS)."<input name=VOTE_DATA_".$ITEM_ID."_".$COUNT++." type=text size=5 class=SmallInput number=true>".ParseItemName(substr($ITEM_NAME, $POS+8),$ITEM_ID,$COUNT);
    if(substr($ITEM_NAME, $POS, 10)=="{textarea}")
        return substr($ITEM_NAME, 0, $POS)."<textarea name=VOTE_DATA_".$ITEM_ID."_".$COUNT++." cols=45 rows=5 class=SmallInput></textarea>".ParseItemName(substr($ITEM_NAME, $POS+10),$ITEM_ID,$COUNT);

    return substr($ITEM_NAME, 0, $POS+1).ParseItemName(substr($ITEM_NAME, $POS+1), $ITEM_ID);
}

//修改事务提醒状态--yc
update_sms_status('11',$VOTE_ID);

$query = "SELECT * from VOTE_TITLE where VOTE_ID='$VOTE_ID' and PUBLISH='1' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID").") and BEGIN_DATE<='$CUR_DATE' and (END_DATE>='$CUR_DATE' or END_DATE = '0000-00-00 00:00:00')";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $FROM_ID=$ROW["FROM_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $CONTENT=$ROW["CONTENT"];
    $TYPE=$ROW["TYPE"];
    $ANONYMITY=$ROW["ANONYMITY"];
    $VIEW_PRIV=$ROW["VIEW_PRIV"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $READERS=$ROW["READERS"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    $VIEW_RESULT_PRIV_ID=$ROW["VIEW_RESULT_PRIV_ID"];//该投票在数据库中保存的"查看权限范围（角色）"信息  宋阳添加
    $VIEW_RESULT_USER_ID=$ROW["VIEW_RESULT_USER_ID"];//该投票在数据库中保存的"查看权限范围（人员）"信息  宋阳添加

    if($END_DATE=="0000-00-00 00:00:00")
        $END_DATE="";

    $SUBJECT=td_htmlspecialchars($SUBJECT);
    $CONTENT=td_htmlspecialchars($CONTENT);
    $CONTENT=nl2br($CONTENT);

    $query1 = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$FROM_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
    {
        $FROM_NAME=$ROW["USER_NAME"];
        $DEPT_ID=$ROW["DEPT_ID"];
        $DEPT_NAME=dept_long_name($DEPT_ID);
    }
}
else
{
    Message("",_("该投票暂不可用"));
    exit;
}
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script Language="JavaScript">
function IsNumber(str)
{
    return str.match(/^[0-9]*$/)!=null;
}

var checkbox_array=new Array();
var min_num_array=new Array();

function CheckForm()
{
    var voteArray=document.form1.VOTE_IDS.value.split(",");
    for(i=0; i<voteArray.length;i++)
    {
        var obj=document.getElementsByName("VOTE"+voteArray[i]);
        var obj2=document.getElementsByTagName("textarea");  //文本对象
        if(obj.length <= 0)
            continue;

        for(j=0;j< obj.length;j++)
        {
            if(obj.item(j).checked)
                break;
        }
        if(j==obj.length)
        {
            alert("<?=_("请选择投票选项！")?>");
            return (false);
        }
        if(<?=$TYPE?>==2)
        {
            for(k=0;k< obj2.length;k++)
            {
                if(obj2[k].value.replace(/^ +| +$/g,'')=="")
                {
                    alert("<?=_("请填写投票内容！")?>");
                    obj2[k].focus();
                    return (false);
                }
            }
        }
    }

    for(i=0; i<voteArray.length;i++)
    {
        var obj=document.getElementsByName("VOTE"+voteArray[i]);
        var obj2=document.getElementsByTagName("textarea");  //文本对象
        if(obj.length > 0)
            continue;
        if(<?=$TYPE?>==2)
        {
            for(k=0;k< obj2.length;k++)
            {
                if(obj2[k].value.replace(/^ +| +$/g,'')=="")
                {
                    alert("<?=_("请填写投票内容！")?>");
                    obj2[k].focus();
                    return (false);
                }
            }
        }
    }
    for(i=0; i<checkbox_array.length;i++)
    {
        var checked_count=0;
        var vote_id=checkbox_array[i];
        for (j=0; j< document.getElementsByName("VOTE"+vote_id).length; j++)
        {
            if(document.getElementsByName("VOTE"+vote_id).item(j).checked)
                checked_count++;
        }

        if(checked_count< min_num_array[i])//////////////////////////////
        {
            alert(sprintf("<?=_("第%s个多选子投票 最少要选择%s项！")?>", (i+1), min_num_array[i]));
            return (false);
        }
    }

    for(i=0; i< document.form1.elements.length; i++)
    {
        if(document.form1.elements[i].name.substr(0,10)!="VOTE_DATA_");
        continue;

        var name_array=document.form1.elements[i].name.split("_");
        var item = document.getElementById("ITEM"+name_array[2]);
        if(item && (item.type=="radio" || item.type=="checkbox") && !item.checked)
            continue;

        if(document.form1.elements(i).value=="")
        {
            alert("<?=_("所有项目都必填！")?>");
            document.form1.elements(i).focus();
            return false;
        }

        if(document.form1.elements(i).number && !IsNumber(document.form1.elements(i).value))
        {
            alert("<?=_("请输入数字！")?>");
            document.form1.elements(i).focus();
            return false;
        }
    }

    document.form1.submit();
}

function AddValue(vote_id,item_id,max_num)
{
    var item_id_str=document.form1.ITEM_ID.value;
    if(document.getElementById("ITEM"+item_id).type=="checkbox" && max_num>0)
    {
        checked_count=0;
        for (i=0; i< document.getElementsByName("VOTE"+vote_id).length; i++)
        {
            if(document.getElementsByName("VOTE"+vote_id).item(i).checked)
                checked_count++;
            if(checked_count>max_num)
            {
                alert(sprintf("<?=_("最多只能选择%s项！")?>", max_num));
                document.getElementById("ITEM"+item_id).checked=false;
                return;
            }
        }
    }
    else if(document.getElementById("ITEM"+item_id).type=="radio")
    {
        for (i=0; i< document.getElementsByName("VOTE"+vote_id).length; i++)
        {
            var radio_id = document.getElementsByName("VOTE"+vote_id).item(i).value;
            if(item_id_str.indexOf(radio_id+",")==0)
                item_id_str=item_id_str.replace(radio_id+",","");
            else if(item_id_str.indexOf(","+radio_id+",")>0)
                item_id_str=item_id_str.replace(","+radio_id+",",",");
        }
    }

    if(item_id_str.indexOf(item_id+",")==0)
        item_id_str=item_id_str.replace(item_id+",","");
    else if(item_id_str.indexOf(","+item_id+",")>0)
        item_id_str=item_id_str.replace(","+item_id+",",",");
    else
        item_id_str+=item_id+",";

    document.form1.ITEM_ID.value=item_id_str;
}
</script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>

<body class="bodycolor">
<?
if($FROM_ID=="")
{
    Message("",_("尚未定义投票项目"));
    exit;
}
?>
<script>
function view_result()
{
    <?
    if(checkUserLookToResultPriv($VIEW_RESULT_PRIV_ID, $VIEW_RESULT_USER_ID))
    {
        echo "location=\"show_reader.php?VOTE_ID=$VOTE_ID\";";
    }
    elseif($VIEW_PRIV=="0" && !find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
    {
        echo "alert(\""._("投票后才能查看投票结果！")."\");";
    }
    else
        echo "location=\"show_reader.php?VOTE_ID=$VOTE_ID\";";?>
}
</script>

<table border="0" width="95%"  align="center" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big3" align="center"><b class="big1">&nbsp;&nbsp;<?=$SUBJECT?></b></td>
        </td>
    </tr>
    <tr>
        <td class="small1"><?=$CONTENT?></td>
    </tr>
    <tr>
        <td align="right" class="small1"><?=_("发布人：")?><u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$FROM_NAME?></u>
            <?
            if($END_DATE!="")
            {
                ?>
                <?=_("有效期：")?><?=substr($BEGIN_DATE,0,11)?><?=_("至")?><?=substr($END_DATE,0,11)?>
                <?
            }
            else
            {
                ?>
                <?=_("发布日期：")?><?=substr($BEGIN_DATE,0,11)?>
                <?
            }
            ?></td>
    </tr>
</table>
<form name="form1" method="post" action="vote.php">
    <table class="TableBlock" width="95%" align="center">
        <?
        $query = "SELECT count(*) from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
        $cursor= exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
            $ITEM_COUNT=$ROW[0];

        if($ITEM_COUNT>0)
        {
            $query = "SELECT * from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
            $cursor2= exequery(TD::conn(),$query);

            $ITEM_COUNT=0;
            if($ROW2=mysql_fetch_array($cursor2))
            {
                $ITEM_COUNT++;
                $VOTE_ID1=$ROW2["VOTE_ID"];
                $SUBJECT=$ITEM_COUNT._("、").$ROW2["SUBJECT"];
                $TYPE=$ROW2["TYPE"];
                $MAX_NUM=$ROW2["MAX_NUM"];
                $MIN_NUM=$ROW2["MIN_NUM"];

                $VOTE_IDS.=$VOTE_ID1.",";

                $SUBJECT=td_htmlspecialchars($SUBJECT);

                if($TYPE=="0")
                    $TYPE_DESC="radio";
                else if($TYPE=="1")
                    $TYPE_DESC="checkbox";
                else
                    $TYPE_DESC="text";

                if($TYPE=="1")
                {
                    $CHECKBOX_IDS.="'".$VOTE_ID1."',";
                    $CHECKBOX_MIN_NUM.="'".intval($MIN_NUM)."',";
                }
                ?>
                <tr class="TableHeader">
                    <td><?=$SUBJECT?></td>
                </tr>
                <tr class="TableData">
                    <td>
                        <?
                        if($TYPE=="0" || $TYPE=="1")
                        {
                            $query = "SELECT * from VOTE_ITEM where VOTE_ID='$VOTE_ID1' order by ITEM_ID";
                            $cursor= exequery(TD::conn(),$query);
                            $NO=0;
                            while($ROW=mysql_fetch_array($cursor))
                            {
                                $ITEM_ID=$ROW["ITEM_ID"];
                                if($NO>=26)
                                    $ITEM_NAME=chr($NO%26+65).floor($NO/26)._("、").$ROW["ITEM_NAME"];
                                else
                                    $ITEM_NAME=chr($NO%26+65)._("、").$ROW["ITEM_NAME"];
                                $ITEM_NAME=ParseItemName($ITEM_NAME,$ITEM_ID);
                                $NO++;
                                ?>
                                <input name="VOTE<?=$VOTE_ID1?>" id="ITEM<?=$ITEM_ID?>" type="<?=$TYPE_DESC?>" value="<?=$ITEM_ID?>" onClick="AddValue('<?=$VOTE_ID1?>','<?=$ITEM_ID?>',<?=$MAX_NUM?>);"><label for="ITEM<?=$ITEM_ID?>"> <?=$ITEM_NAME?></label><br>
                                <?
                            }
                        }
                        else
                        {
                            ?>
                            <textarea name="VOTE_DATA_<?=$VOTE_ID1?>_0" cols="45" rows="5"></textarea>
                            <?
                        }
                        ?>
                    </td>
                </tr>
                <?
            }
        }

        $query = "SELECT * from VOTE_TITLE where PARENT_ID='$VOTE_ID' order by VOTE_NO,SEND_TIME";
        $cursor2= exequery(TD::conn(),$query);
        while($ROW2=mysql_fetch_array($cursor2))
        {
            $ITEM_COUNT++;
            $VOTE_ID1=$ROW2["VOTE_ID"];
            $SUBJECT=$ITEM_COUNT._("、").$ROW2["SUBJECT"];
            $CONTENT=$ROW2["CONTENT"];
            $TYPE=$ROW2["TYPE"];
            $MAX_NUM=$ROW2["MAX_NUM"];
            $MIN_NUM=$ROW2["MIN_NUM"];

            $VOTE_IDS.=$VOTE_ID1.",";

            $SUBJECT=td_htmlspecialchars($SUBJECT);
            $CONTENT=td_htmlspecialchars($CONTENT);
            $CONTENT=nl2br($CONTENT);

            if($TYPE=="0")
                $TYPE_DESC="radio";
            else if($TYPE=="1")
                $TYPE_DESC="checkbox";
            else
                $TYPE_DESC="text";

            if($TYPE=="1")
            {
                $CHECKBOX_IDS.="'".$VOTE_ID1."',";
                $CHECKBOX_MIN_NUM.="'".intval($MIN_NUM)."',";
            }
            ?>
            <tr class="TableHeader">
                <td><?=$SUBJECT?></td>
            </tr>
            <tr class="TableData">
                <td><?=$CONTENT?><br>
                    <?
                    if($TYPE=="0" || $TYPE=="1")
                    {
                        $query = "SELECT * from VOTE_ITEM where VOTE_ID='$VOTE_ID1' order by ITEM_ID";
                        $cursor= exequery(TD::conn(),$query);
                        $NO=0;
                        while($ROW=mysql_fetch_array($cursor))
                        {
                            $ITEM_ID=$ROW["ITEM_ID"];
                            if($NO>=26)
                                $ITEM_NAME=chr($NO%26+65).floor($NO/26)._("、").$ROW["ITEM_NAME"];
                            else
                                $ITEM_NAME=chr($NO%26+65)._("、").$ROW["ITEM_NAME"];
                            $ITEM_NAME=ParseItemName($ITEM_NAME,$ITEM_ID);
                            $NO++;
                            ?>
                            <input name="VOTE<?=$VOTE_ID1?>" id="ITEM<?=$ITEM_ID?>" type="<?=$TYPE_DESC?>" value="<?=$ITEM_ID?>" onClick="AddValue('<?=$VOTE_ID1?>','<?=$ITEM_ID?>',<?=$MAX_NUM?>);"><label for="ITEM<?=$ITEM_ID?>"> <?=$ITEM_NAME?></label><br>
                            <?
                        }
                    }
                    else
                    {
                        ?>
                        <textarea name="VOTE_DATA_<?=$VOTE_ID1?>_0" cols="45" rows="5"></textarea>
                        <?
                    }
                    ?>
                </td>
            </tr>
            <?
        }
        ?>

        <?
        $ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
        if($ATTACH_ARRAY["NAME"]!="")
        {
            ?>
            <tr>
                <td class="TableData"><?=_("附件文件")?>:<br><?=attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],1,1,1)?></td>
            </tr>
            <?
        }

        if($ATTACH_ARRAY["IMAGE_COUNT"]>0)
        {
            ?>
            <tr class="TableData">
                <td colspan="2">
                    <img src="<?=MYOA_STATIC_SERVER?>/static/images/image.gif" align="absmiddle" border="0">&nbsp;<?=_("附件图片")?>: <br><br>

                    <?
                    $MODULE=attach_sub_dir();
                    $ATTACHMENT_ID_ARRAY=explode(",",$ATTACH_ARRAY["ID"]);
                    $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACH_ARRAY["NAME"]);
                    for($I=0;$I<count($ATTACHMENT_ID_ARRAY);$I++)
                    {
                        if($ATTACHMENT_ID_ARRAY[$I]=="")
                            continue;

                        $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
                        if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
                        {
                            //$WIDTH=floor($IMG_ATTR[0]*100/$IMG_ATTR[1]);
                            $WIDTH=$IMG_ATTR[0];
                            $HEIGHT=$IMG_ATTR[1];
                        }
                        else
                        {
                            $WIDTH=100;
                            $HEIGHT=100;
                        }
                        $ATTACHMENT_ID=$ATTACHMENT_ID_ARRAY[$I];
                        $YM=substr($ATTACHMENT_ID,0,strpos($ATTACHMENT_ID,"_"));
                        if($YM)
                            $ATTACHMENT_ID=substr($ATTACHMENT_ID,strpos($ATTACHMENT_ID,"_")+1);
                        $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID,$ATTACHMENT_NAME_ARRAY[$I]);

                        if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
                        {
                            ?>
                        <a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="1"  width="<?=$WIDTH?>" height="<?=$HEIGHT?> alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a><?=_("　")?>
                            <?
                        }
                    }
                    ?>
                </td>
            </tr>
            <?
        }
        ?>
        <tr align="center" class="TableControl">
            <td colspan="2">
                <?
                if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]) && ($END_DATE=="" || $END_DATE>= date("Y-m-d H:i:s",time())))
                {
                    ?>
                    <input type="button" value="<?=_("投票")?>" class="BigButton" onClick="CheckForm();">&nbsp;&nbsp;
                    <?
                }
                if(checkUserLookToResultPriv($VIEW_RESULT_PRIV_ID, $VIEW_RESULT_USER_ID))
                {
                    ?>
                    <input type="button" value="<?=_("查看结果")?>" class="BigButton" onClick="view_result();">&nbsp;&nbsp;
                    <?
                }
                elseif($VIEW_PRIV!="2")
                {
                    ?>
                    <input type="button" value="<?=_("查看结果")?>" class="BigButton" onClick="view_result();">&nbsp;&nbsp;
                    <?
                }

                ?>
                <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="TJF_window_close();">
            </td>
        </tr>
    </table>

    <input name="ITEM_ID" type="hidden" value="">
    <input name="FROM" type="hidden" value="<?=$FROM?>">
    <input name="VOTE_ID" type="hidden" value="<?=$VOTE_ID?>">
    <input name="VOTE_IDS" type="hidden" value="<?=$VOTE_IDS?>">
    <input name="ANONYMITY" type="hidden" value="<?=$ANONYMITY?>">
</form>
<script>
    checkbox_array=Array(<?=trim($CHECKBOX_IDS, ",")?>);
    min_num_array=Array(<?=trim($CHECKBOX_MIN_NUM, ",")?>);
</script>
</body>
</html>