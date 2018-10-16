<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("会议查询");
include_once("inc/header.inc.php");
?>

<script>
function check_all()
{
    if(!document.getElementsByName("meeting_select"))
    {
        return;
    }
    
    for (i=0;i<document.getElementsByName("meeting_select").length;i++)
    {
        if(document.getElementsByName("allbox")[0].checked)
        {
            document.getElementsByName("meeting_select").item(i).checked=true;
        }
        else
        {
            document.getElementsByName("meeting_select").item(i).checked=false;
        }
    }
    
    if(i==0)
    {
        if(document.getElementsByName("allbox")[0].checked)
        {
            document.getElementsByName("meeting_select").checked=true;
        }
        else
        {
            document.getElementsByName("meeting_select").checked=false;
        }
    }
}

function check_one(el)
{
    if(!el.checked)
    {
        document.getElementsByName("allbox")[0].checked=false;
    }
}

function delete_meeting()
{
    delete_str="";
    for(i=0;i<document.getElementsByName("meeting_select").length;i++)
    {
        el=document.getElementsByName("meeting_select").item(i);
        if(el.checked)
        {
            val=el.value;
            delete_str+=val + ",";
        }
    }
    
    if(i==0)
    {
        el=document.getElementsByName("meeting_select");
        if(el.checked)
        {
            val=el.value;
            delete_str+=val + ",";
        }
    }
    
    if(delete_str=="")
    {
        alert("<?=_("要删除会议，请至少选择其中一条。")?>");
        return;
    }
    
    msg='<?=_("确认要删除所选会议吗？")?>';
    if(window.confirm(msg))
    {
        url="delete.php?DELETE_STR="+ delete_str +"&PAGE_START=<?=$PAGE_START?>&BOX_ID=<?=$BOX_ID?>";
        location=url;
    }
}	

</script>

<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());

//----------- 合法性校验 ---------
if($M_START_B!="")
{
    $TIME_OK=is_date($M_START_B);
    if(!$TIME_OK)
    {
        $msg=sprintf(_("开始时间的格式不对，应形如 %s"),$CUR_DATE);
        Message(_("错误"),$msg);
        Button_Back();
        exit;
    }
}

if($M_END_B!="")
{
    $TIME_OK=is_date($M_END_B);
    if(!$TIME_OK)
    {
        $msg=sprintf(_("开始时间的格式不对，应形如 %s"),$CUR_DATE);
        Message(_("错误"),$msg);
        Button_Back();
        exit;
    }
}

if (!$PAGE_SIZE)
{
    $PAGE_SIZE=20;
}
if (!isset($start) || $start=="")
{
    $start=0;
}
//------------------------ 生成条件字符串 ------------------
if($M_START_B!="")
{
    $M_START_B=$M_START_B." 00:00:00";
}
else
{
    $M_START_B="";
}

if($M_END_B!="")
{
    $M_END_B=$M_END_B." 23:59:59";
}
else
{
    $M_START_B="";
}

$CONDITION_STR="";
$QSTRING = "1=1";
if($M_ID!="")
{
    $CONDITION_STR.=" and M_ID='$M_ID'";
	$QSTRING .= "&M_ID"."=".$M_ID;
}
if($M_NAME!="")
{
    $CONDITION_STR.=" and M_NAME like '%".$M_NAME."%'";
	$QSTRING .= "&M_NAME"."=".$M_NAME;
}
if($TO_ID!="")
{
    $CONDITION_STR.=" and M_PROPOSER='$TO_ID'";
	$QSTRING .= "&TO_ID"."=".$TO_ID;
}
if($M_START_B!="")
{
    $CONDITION_STR.=" and M_START>='$M_START_B'";
	$QSTRING .= "&M_START_B"."=".$M_START_B;
}
if($M_END_B!="")
{
    $CONDITION_STR.=" and M_START<='$M_END_B'";
	$QSTRING .= "&M_END_B"."=".$M_END_B;
}
if($M_ROOM!="")
{
    $CONDITION_STR.=" and M_ROOM='$M_ROOM'";
	$QSTRING .= "&M_ROOM"."=".$M_ROOM;
}
if($M_STATUS!="")
{
    $CONDITION_STR.=" and M_STATUS='$M_STATUS'";
	$QSTRING .= "&M_STATUS"."=".$M_STATUS;
}
//增加按照部门出席 查询
if($DEPT_TO_ID!="")
{
    $DEPT_USER="SELECT USER.USER_ID FROM DEPARTMENT,USER WHERE 1 and USER.DEPT_ID<>0 and DEPARTMENT.DEPT_ID=USER.DEPT_ID and find_in_set(USER.DEPT_ID,'$DEPT_TO_ID')";
    $cursor_dept_user=exequery(TD::conn(),$DEPT_USER);
    while($ROW_DEPT_USER=mysql_fetch_array($cursor_dept_user))
    {
        $COPY_TO_ID.=$ROW_DEPT_USER["USER_ID"].",";
    }
}

if($COPY_TO_ID!="")
{
    $COPY_TO_ID_ARR = array_filter(explode(",",$COPY_TO_ID));
    foreach ($COPY_TO_ID_ARR as $v)
    {
        $CONDITION_STR.=" and find_in_set('$v',M_ATTENDEE)";
    }
}
$where = "";
//生成管理员管理会议室条件
if($M_ROOM!="")
{
	$sql="SELECT OPERATOR FROM meeting_room WHERE MR_ID = '$M_ROOM'";
	$mr=exequery(TD::conn(),$sql);
	if($arr=mysql_fetch_array($mr))
	{
		$OPERATOR = $arr['OPERATOR'];
	}
	if($OPERATOR)
	{
		$OPERATOR = substr($OPERATOR,0,strlen($OPERATOR)-1);
		$where = " or find_in_set('".$_SESSION["LOGIN_USER_ID"]."','".$OPERATOR."')";
	}
}
else
{
	$sql="SELECT MR_ID FROM meeting_room WHERE find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OPERATOR)";
	$mr=exequery(TD::conn(),$sql);
	while($arr=mysql_fetch_array($mr))
	{
		$MR_ID .=$arr['MR_ID'].",";
	}
	if($MR_ID)
	{
		$MR_ID = substr($MR_ID,0,strlen($MR_ID)-1);
		$where = " or find_in_set(M_ROOM,'".$MR_ID."')";
	}
}
//当前登录人员有查看会议权限的会议判断
if($_SESSION["LOGIN_USER_ID"] != 'admin')
{
    if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1"){
        $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
        if($dept_id)
        {
            $dept_str = $dept_id;
        }
        else
        {
            $dept_str = $_SESSION["LOGIN_DEPT_ID"];
        }
        $UID = rtrim(GetUidByOther('','',$dept_str),",");
        $user_id = rtrim(GetUserIDByUid($UID),",");
        if($user_id != "") {
            $CONDITION_STR.=" and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SECRET_TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',M_ATTENDEE) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',RECORDER) or find_in_set(M_PROPOSER,'".$user_id."') or find_in_set(M_MANAGER,'".$user_id."')".$where.")";
        }else{
            $CONDITION_STR.=" and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SECRET_TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',M_ATTENDEE) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',RECORDER) or M_PROPOSER='".$_SESSION["LOGIN_USER_ID"]."' or M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."'".$where.")";
        }
    }
    else
    {
        $CONDITION_STR.=" and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SECRET_TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',M_ATTENDEE) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',RECORDER) or M_PROPOSER='".$_SESSION["LOGIN_USER_ID"]."' or M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."'".$where.")";
    }
}
$query3="SELECT COUNT(M_ID) FROM MEETING WHERE 1=1".$CONDITION_STR;
$cursor3=exequery(TD::conn(),$query3);
if ($ROW3=mysql_fetch_array($cursor3))
{
    $TOTAL_ITEMS=$ROW3[0];
}

if($_SERVER['QUERY_STRING'] == "")
{
	$_SERVER['QUERY_STRING'] = $QSTRING;
}

?>

<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("查询结果")?></span><br>
        </td>
<?
        $MSG = sprintf(_("共%s条会议记录"),"<span class='big4'>&nbsp;".$TOTAL_ITEMS."</span>&nbsp;");
?>
        <td valign="bottom" class="small1"><?=$MSG?>
        </td>
        <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
    </tr>
</table>

<?
$query = "SELECT * from MEETING where 1=1 ";
$query.=$CONDITION_STR." order by M_START desc, M_ROOM desc limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$MEETING_COUNT=0;
$DEL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $MEETING_COUNT++;
    $M_ID=$ROW["M_ID"];
    $M_NAME=$ROW["M_NAME"];
    $M_PROPOSER=$ROW["M_PROPOSER"];// 申请人
    $M_ATTENDEE=$ROW["M_ATTENDEE"];
    $M_START=$ROW["M_START"];
    $RECORDER=$ROW["RECORDER"];//会议纪要员
    $M_END=$ROW["M_END"];
    $M_ROOM=$ROW["M_ROOM"];
    $M_STATUS=$M_STAT=$ROW["M_STATUS"];
    $M_ATTENDEE_OUT=$ROW["M_ATTENDEE_OUT"];
    $READ_PEOPLE_ID=$ROW["READ_PEOPLE_ID"];
    $SUMMARY_STATUS = $ROW["SUMMARY_STATUS"];
    $M_TYPE = $ROW["M_TYPE"];
    $USER_NAME2="";
    $TOK=strtok($M_ATTENDEE,",");
    while($TOK!="")
    {
        $query2 = "SELECT USER_NAME from USER where USER_ID='$TOK'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW=mysql_fetch_array($cursor2))
        {
            $USER_NAME2.=$ROW["USER_NAME"].",";
        }
        $TOK=strtok(",");
    }
    
    $USER_NAME2=substr($USER_NAME2,0,-1);
    $M_ATTENDEE_NAME=_("内部：").$USER_NAME2."<br>"._("外部：").$M_ATTENDEE_OUT;
    
    $M_NAME=str_replace("<","&lt",$M_NAME);
    $M_NAME=str_replace(">","&gt",$M_NAME);
    $M_NAME=stripslashes($M_NAME);
    
    $SUMMARY=str_replace("<","&lt",$SUMMARY);
    $SUMMARY=str_replace(">","&gt",$SUMMARY);
    $SUMMARY=stripslashes($SUMMARY);
    
    if($M_START=="0000-00-00 00:00:00")
    {
        $M_START="";
    }
    if($M_END=="0000-00-00 00:00:00")
    {
        $M_END="";
    }
    $M_START=substr($M_START,0,16);
    $M_END=substr($M_END,0,16);
    
    $query1="select USER_NAME,DEPT_ID from USER where USER_ID='$M_PROPOSER'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
    {
        $M_PROPOSER_NAME=$ROW1["USER_NAME"];
        $DEPT_ID=$ROW1["DEPT_ID"];
    }
    
    $DEPT_NAME=dept_long_name($DEPT_ID);
    $M_ROOM_NAME="";
    if($M_TYPE == "1" && $M_ROOM == "0"){
        $M_ROOM_NAME = "-";
    }else{
        $query2 = "SELECT MR_NAME from MEETING_ROOM where MR_ID='$M_ROOM'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW2=mysql_fetch_array($cursor2))
        {
            $M_ROOM_NAME=$ROW2["MR_NAME"];
        }
    }
    //对会议名称截取显示
    if(strlen($M_NAME) > 30)
    {
        $M_NAME = csubstr($M_NAME,0,30)."...";
    }
    if($M_TYPE == "1" && $M_ROOM != ""){
        $M_NAME = $M_NAME."<image src='/static/images/metting.png'></image>";
    }
    if($MEETING_COUNT==1)
    {
?>
<table class="TableList" width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("会议名称")?></td>
        <td nowrap align="center"><?=_("申请人")?></td>
        <td nowrap align="center"><?=_("出席人员")?></td>
        <td nowrap align="center"><?=_("开始时间")?></td>
        <td nowrap align="center"><?=_("会议状态")?></td>
        <td nowrap align="center"><?=_("链接")?></td>
        <td nowrap align="center"><?=_("会议室")?></td>
        <td nowrap align="center"><?=_("会议纪要")?></td>     
    </tr>
<?
        if($_SESSION["LOGIN_USER_ID"] <= 0)
        {
            $DEL_COUNT++;
        }
    }
    
    if($MEETING_COUNT%2==1)
    {
        $TableLine="TableLine1";
    }
    else
    {
        $TableLine="TableLine2";
    }
?>
<tr class="<?=$TableLine?>">
    <td nowrap align="center"><?=$M_NAME?></td>
    <td nowrap align="center"><u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$M_PROPOSER_NAME?></u></td>
    <td align="left"><?=$M_ATTENDEE_NAME?></td>
    <td align="center"><?=$M_START?></td>
    <td nowrap align="center">
<?
    if($M_STATUS==0)
    {
        $M_STATUS=_("待批");
    }
    if($M_STATUS==1)
    {
        $M_STATUS=_("已批准");
    }
    if($M_STATUS==2)
    {
        $M_STATUS=_("进行中");
    }
    if($M_STATUS==3)
    {
        $M_STATUS=_("未批准");
    }
    if($M_STATUS==4)
    {
        $M_STATUS=_("已结束");
    }
            
        echo $M_STATUS;
?>
    </td>
    <td nowrap align="center"><a href="#" onClick="window.open('../query/meeting_detail.php?M_ID=<?=$M_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200 top=100,resizable=yes');"><?=_("详细信息")?></a></td>
    <td nowrap align="center"><?=$M_ROOM_NAME?></a></td>
    <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_ID"]==$M_PROPOSER || find_id($RECORDER,$_SESSION["LOGIN_USER_ID"]) || find_id($READ_PEOPLE_ID,$_SESSION["LOGIN_USER_ID"]) || find_id($M_ATTENDEE,$_SESSION["LOGIN_USER_ID"]))
{
    if($M_STAT!=0 && $M_STAT!=3 && $SUMMARY_STATUS == 2)
    {
?>
        <a href="#" onClick="window.open('view_summary.php?M_ID=<?=$M_ID?>','','height=540,width=740,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("查看")?></a>&nbsp;
<?
    }
}
?>
   
    
<?
if($_SESSION["LOGIN_USER_ID"]==$M_PROPOSER || find_id($RECORDER,$_SESSION["LOGIN_USER_ID"]) || find_id($M_ATTENDEE,$_SESSION["LOGIN_USER_ID"]) || find_id($READ_PEOPLE_ID,$_SESSION["LOGIN_USER_ID"]))
{
    switch($SUMMARY_STATUS)
    {
        case 0:
            if($M_STAT!=0 && $M_STAT!=3)
            {
                if($_SESSION["LOGIN_USER_ID"] == $M_PROPOSER || $_SESSION["LOGIN_USER_ID"] == $RECORDER)
                {
                    ?>
                    <a href="#" onClick="window.open('summary.php?M_ID=<?=$M_ID?>','','height=540,width=740,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=150,top=100,resizable=yes');"><?=_("添加")?></a><br>
                    <?
                }else
                {
                    echo "未提交";
                }
            }
        break;
        
        case 1:
            echo "待审批";
        break;
        
        case 2:
            echo "<font color='green'>已发布</font>";
        break;
        
        case 3:
            if($_SESSION["LOGIN_USER_ID"] == $M_PROPOSER || $_SESSION["LOGIN_USER_ID"] == $RECORDER)
            {
                ?>
                <a href="#" onClick="window.open('summary.php?M_ID=<?=$M_ID?>','','height=540,width=740,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=150,top=100,resizable=yes');"><?=_("添加")?></a><br>
                <?
                echo "<font  size='1px' color='#FF0000'>(驳回)</font>";
            }else
            {
                echo "驳回";
            }
        break;
    }
}
?> 
    </td>
</tr>

<?
}//while ROW

if($MEETING_COUNT==0)
{
    Message("",_("无符合条件的会议"));
    Button_Back();
    exit;
}
else
{
?>
</table>
<center>
<button style="width:50px;height:25px;margin-top:5px;"type="button" onClick="javascript:window.location='index.php'" class="btn"><?=_("返回")?></button>
</center>
<?
}
?>

</body>
</html>