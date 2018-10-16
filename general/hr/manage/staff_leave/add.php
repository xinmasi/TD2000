<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_cache.php");

if($LEAVE_PERSON=="admin")
{
    Message("",_("不能对admin用户进行离职操作"));
    Button_Back();
    exit;
}

$HTML_PAGE_TITLE = _("员工离职信息");
include_once("inc/header.inc.php");

?>

<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($QUIT_TIME_PLAN!="" && !is_date($QUIT_TIME_PLAN))
{
    Message("",_("拟离职日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}
if($QUIT_TIME_FACT!="" && !is_date($QUIT_TIME_FACT))
{
    Message("",_("实际离职日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}
if($LAST_SALARY_TIME!="" && !is_date($LAST_SALARY_TIME))
{
    Message("",_("工资截止日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}
if($APPLICATION_DATE!="" && !is_date($APPLICATION_DATE))
{
    Message("",_("申请日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}
if($SALARY=="")
    $SALARY=0;
if($batch!="on")
    $BLACKLIST_INSTRUCTIONS="";
//--------- 上传附件 ----------
$ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
$ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();
    $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);

    $ATTACHMENT_ID.=$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME.=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$QUIT_REASON);
$QUIT_REASON = replace_attach_url($QUIT_REASON);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}

//------------------- 新增离职信息 -----------------------
$query="select UID, DEPT_ID,USER_NAME from USER where USER_ID='$LEAVE_PERSON'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $DEPT_ID=$ROW["DEPT_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $UID=$ROW["UID"];
}
if($LEAVE=="ack")
{
    //将临时讨论组中的该用户去掉
    //$query="UPDATE `im_discuss_group` SET `DISC_GROUP_UID` = REPLACE( `DISC_GROUP_UID` , '".$UID.",', '' )";
    //exequery(TD::conn(),$query);
    $query = "select DISC_GROUP_ID from im_discuss_group";
    $cursor = exequery(TD::conn(),$query);
    while($ROW = mysql_fetch_array($cursor))
    {  
       $DISC_GROUP_ID[]=$ROW['DISC_GROUP_ID'];
    }

    if(!empty($DISC_GROUP_ID))
    {
        foreach($DISC_GROUP_ID as $V=>$K)
        {
            $query = "select DISC_GROUP_UID from im_discuss_group where DISC_GROUP_ID = '$K'";
            $cursor = exequery(TD::conn(),$query);
            if($ROW = mysql_fetch_array($cursor))
            {
                $DISC_GROUP_UID = td_trim($ROW['DISC_GROUP_UID']);
                $DISC_GROUP_UID = explode(',',$DISC_GROUP_UID);
                foreach($DISC_GROUP_UID as $k=>$v) {
                    if($v==$UID) unset($DISC_GROUP_UID[$k]);
                }
                $DISC_GROUP_UID=implode(",",$DISC_GROUP_UID);
                $DISC_GROUP_UID = td_trim($DISC_GROUP_UID);
                $DISC_GROUP_UID = $DISC_GROUP_UID.',';
            }
            $query="UPDATE im_discuss_group SET DISC_GROUP_UID = '$DISC_GROUP_UID' where DISC_GROUP_ID='$K'";
            exequery(TD::conn(),$query);
        }
    }

    //将群组中的该用户去掉 
    // $query="UPDATE im_group SET GROUP_UID = REPLACE(GROUP_UID ,%$UID%,'') ";
    // exequery(TD::conn(),$query);
   
    $query = "select GROUP_ID from im_group";
    $cursor = exequery(TD::conn(),$query);
    while($ROW = mysql_fetch_array($cursor))
    {  
       $GROUP_ID[]=$ROW['GROUP_ID'];
    }

    if(!empty($GROUP_ID))
    {
        foreach($GROUP_ID as $V=>$K)
        {
            $query = "select GROUP_UID from im_group where GROUP_ID = '$K'";
            $cursor = exequery(TD::conn(),$query);
            if($ROW = mysql_fetch_array($cursor))
            {
                $GROUP_UID = td_trim($ROW['GROUP_UID']);
                $GROUP_UID = explode(',',$GROUP_UID);
                foreach($GROUP_UID as $k=>$v) {
                    if($v==$UID) unset($GROUP_UID[$k]);
                }
                $GROUP_UID=implode(",",$GROUP_UID);
                $GROUP_UID = td_trim($GROUP_UID);
                $GROUP_UID=$GROUP_UID.',';
            }
            $query="UPDATE im_group SET GROUP_UID = '$GROUP_UID' where GROUP_ID='$K'";
            exequery(TD::conn(),$query);
        }
    }

    //离职表中添加数据
    $query="select * from HR_STAFF_LEAVE where LEAVE_PERSON='$LEAVE_PERSON'";
    $cursor= exequery(TD::conn(),$query);
    if(!$ROW=mysql_fetch_array($cursor))
    {
        if($batch=="on")
        {
            $query1="select STAFF_CARD_NO from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
            $cursor1= exequery(TD::conn(),$query1);
            $ROW1=mysql_fetch_array($cursor1);
            $STAFF_CARD_NO=$ROW1["STAFF_CARD_NO"];
            $query="insert into HR_STAFF_LEAVE(CREATE_USER_ID,CREATE_DEPT_ID,QUIT_TIME_PLAN,QUIT_TYPE,QUIT_REASON,LAST_SALARY_TIME,TRACE,REMARK,QUIT_TIME_FACT,LEAVE_PERSON,MATERIALS_CONDITION,POSITION,ATTACHMENT_ID,ATTACHMENT_NAME,APPLICATION_DATE,LEAVE_DEPT,ADD_TIME,LAST_UPDATE_TIME,SALARY,STAFF_CARD_NO,IS_BLACKLIST,BLACKLIST_INSTRUCTIONS) values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$QUIT_TIME_PLAN','$QUIT_TYPE','$QUIT_REASON','$LAST_SALARY_TIME','$TRACE','$REMARK','$QUIT_TIME_FACT','$LEAVE_PERSON','$MATERIALS_CONDITION','$POSITION','$ATTACHMENT_ID','$ATTACHMENT_NAME','$APPLICATION_DATE','$LEAVE_DEPT','$CUR_TIME','$CUR_TIME','$SALARY','$STAFF_CARD_NO',1,'$BLACKLIST_INSTRUCTIONS')";
        }
        else
        {
            $query="insert into HR_STAFF_LEAVE(CREATE_USER_ID,CREATE_DEPT_ID,QUIT_TIME_PLAN,QUIT_TYPE,QUIT_REASON,LAST_SALARY_TIME,TRACE,REMARK,QUIT_TIME_FACT,LEAVE_PERSON,MATERIALS_CONDITION,POSITION,ATTACHMENT_ID,ATTACHMENT_NAME,APPLICATION_DATE,LEAVE_DEPT,ADD_TIME,LAST_UPDATE_TIME,SALARY) values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$QUIT_TIME_PLAN','$QUIT_TYPE','$QUIT_REASON','$LAST_SALARY_TIME','$TRACE','$REMARK','$QUIT_TIME_FACT','$LEAVE_PERSON','$MATERIALS_CONDITION','$POSITION','$ATTACHMENT_ID','$ATTACHMENT_NAME','$APPLICATION_DATE','$LEAVE_DEPT','$CUR_TIME','$CUR_TIME','$SALARY')";
        }
    }
    else
    {
        if($batch=="on")
        {
            $query1="select STAFF_CARD_NO from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
            $cursor1= exequery(TD::conn(),$query1);
            $ROW1=mysql_fetch_array($cursor1);
            $STAFF_CARD_NO=$ROW1["STAFF_CARD_NO"];
            $query="update HR_STAFF_LEAVE  set IS_REINSTATEMENT='0', ADD_TIME='$CUR_TIME',LAST_UPDATE_TIME='$CUR_TIME',STAFF_CARD_NO='$STAFF_CARD_NO',IS_BLACKLIST=1,BLACKLIST_INSTRUCTIONS='$BLACKLIST_INSTRUCTIONS' where LEAVE_PERSON='$LEAVE_PERSON'";
        }
        else
        {
            $query="update HR_STAFF_LEAVE  set IS_REINSTATEMENT='0', ADD_TIME='$CUR_TIME',LAST_UPDATE_TIME='$CUR_TIME' where LEAVE_PERSON='$LEAVE_PERSON'";
        }

    }

    exequery(TD::conn(),$query);
    //离职之后，本人合同改为已解除
    $query="update hr_staff_contract set REMOVE_OR_NOT='1',CONTRACT_REMOVE_TIME='$QUIT_TIME_FACT' where STAFF_NAME='$LEAVE_PERSON'";
    exequery(TD::conn(),$query);

    //更改user表中的信息 主要添加离职部门id 201601202  spz  
    $query="update USER set DEPT_ID='0',NOT_LOGIN='1',NOT_MOBILE_LOGIN='1',LEAVE_DEPT='$DEPT_ID' where USER_ID='$LEAVE_PERSON'";
    exequery(TD::conn(),$query);

    set_sys_para(array("ORG_UPDATE" => date("Y-m-d H:i:s")));

    cache_users();

    $WORK_STATUS=$QUIT_TYPE==""?"":'0'.($QUIT_TYPE+1);
    $query="select * from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
    $cursor= exequery(TD::conn(),$query);
    if(!$ROW=mysql_fetch_array($cursor))
        $query="insert into HR_STAFF_INFO(CREATE_USER_ID,CREATE_DEPT_ID,USER_ID,DEPT_ID,STAFF_NAME,WORK_STATUS) values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$LEAVE_PERSON','$LEAVE_DEPT','$LEAVE_PERSON_NAME','$WORK_STATUS')";
    else
        $query="update HR_STAFF_INFO  set DEPT_ID='$LEAVE_DEPT', WORK_STATUS='$WORK_STATUS' where USER_ID='$LEAVE_PERSON'";
    exequery(TD::conn(),$query);

    $SYS_PARA_ARRAY = get_sys_para('DINGDING_CORPID,DINGDING_SECRET,WEIXINQY_CORPID,WEIXINQY_SECRET,QYWEIXIN_CORPID');
    $DINGDING_CORPID = $SYS_PARA_ARRAY["DINGDING_CORPID"];
    $DINGDING_SECRET = $SYS_PARA_ARRAY["DINGDING_SECRET"];
    $WEIXINQY_CORPID = $SYS_PARA_ARRAY["WEIXINQY_CORPID"];
    $WEIXINQY_SECRET = $SYS_PARA_ARRAY["WEIXINQY_SECRET"];
    $QYWEIXIN_CORPID = $SYS_PARA_ARRAY["QYWEIXIN_CORPID"];
    $sql = "SELECT secret FROM qyweixin_app where app_name = 'department'";
    $cursor= exequery(TD::conn(),$sql);
    if($ROW=mysql_fetch_array($cursor))
    {
        $app_secret = $ROW["secret"];
    }
    if($DINGDING_CORPID!='' && $DINGDING_SECRET!='')
    {
		$open_id = '';
		$query = "select * from user_dingding where user_id='$LEAVE_PERSON'";
		$cursor = exequery(TD::conn(), $query);
		if($row = mysql_fetch_array($cursor))
		{
			$open_id = $row['open_id'];
		}
	    if($open_id!='')
		{
            $type = "delete_user";
		}
        $sync_info = array(
            'qy_type' => 'dd,',
            'type' => $type,
            'dd_user_id' => $LEAVE_PERSON
        );
        sync_oa2qy($sync_info);
    }
    if($WEIXINQY_CORPID!='' && $WEIXINQY_SECRET!='')
    {
		$open_id = '';
		$query = "select * from user_weixinqy where user_id='$LEAVE_PERSON'";
		$cursor = exequery(TD::conn(), $query);
		if($row = mysql_fetch_array($cursor))
		{
			$open_id = $row['open_id'];
		}
		if($open_id!='')
		{
			$type = "delete_user";
		}
        $sync_info = array(
            'qy_type' => 'wx,',
            'type' => $type,
            'wx_user_id' => $LEAVE_PERSON
        );
        sync_oa2qy($sync_info);
    }
    if($QYWEIXIN_CORPID!='' && $app_secret!='')
    {
		$open_id = '';
		$query = "select * from user_qyweixin where user_id='$LEAVE_PERSON'";
		$cursor = exequery(TD::conn(), $query);
		if($row = mysql_fetch_array($cursor))
		{
			$open_id = $row['open_id'];
		}
		if($open_id!='')
		{
            $type = "delete_user";
		}
        $sync_info = array(
            'qy_type' => 'qywx,',
            'type' => $type,
            'qywx_user_id' => $LEAVE_PERSON
        );
        sync_oa2qy($sync_info);
    }
   
   
   
   
    //记录系统日志
    add_log(23,$USER_NAME._("办理离职"),$_SESSION["LOGIN_USER_ID"]);
    //事务提醒相关用户
    if($NOTIFY=="on")
    {
        $SMS_CONTENT=_("员工").$USER_NAME._("(").$LEAVE_DEPT_NAME._(")已办理离职手续!");
        if($TO_ID!="")
            send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_ID,64,$SMS_CONTENT,"ipanel/user/user_info.php?USER_ID=".$LEAVE_PERSON,$LEAVE_PERSON);
    }

    Message("",_("用户").$USER_NAME._("(").$LEAVE_DEPT_NAME._(")创建的公共群组和临时讨论组更改创始人!"));
    ?>
    <form action="add_im.php"  method="post" name="form1" >
        <table class="TableBlock" width="80%" align="center">
            <tr>
                <td colspan=2 nowrap class="TableData"> <?=_("公共群组：")?></td>
            </tr>
            <?
            $LEAVE_PERSON_ID_ZHI=GetUidByUserID($LEAVE_PERSON);
            $LEAVE_PERSON_ID_ZHI1=  trim($LEAVE_PERSON_ID_ZHI,',');
            $query="select * from im_group where GROUP_CREATOR='$LEAVE_PERSON_ID_ZHI1'";
            $cursor= exequery(TD::conn(),$query);
            $count_im_group=0;
            while($ROW=mysql_fetch_array($cursor))
            {
                $namezhi="value".$ROW["GROUP_ID"];
                if($ROW["GROUP_UID"] =="")
                {
                    continue;
                }
                $count_im_group++;
                ?>
                <tr>
                    <td nowrap class="TableData"> <?=$ROW["GROUP_NAME"]?></td>
                    <td class="TableData" >
                        <select name="<?=$namezhi?>">
                            <?
                            $array_info=  explode(',',trim($ROW["GROUP_UID"],','));
                            for($i=0;$i<count($array_info);$i++)
                            {
                                echo "<option value=".$array_info[$i].">".trim(GetUserNameByUid($array_info[$i]),',')."</option>";
                            }
                            ?>
                        </select>

                </tr>
                <?
            }
            ?>
            <tr>
                <td colspan=2 nowrap class="TableData"> <?=_("临时讨论组：")?></td>
            </tr>

            <?
            $query="select * from im_discuss_group where DISC_GROUP_CREATOR='$LEAVE_PERSON_ID_ZHI1'";
            $cursor= exequery(TD::conn(),$query);
            $count_im_discuss_group=0;
            while($ROW=mysql_fetch_array($cursor))
            {
                $namezhi="value_dis".$ROW["DISC_GROUP_ID"];
                if($ROW["DISC_GROUP_UID"] =="")
                {
                    continue;
                }
                $count_im_discuss_group++;
                ?>
                <tr>
                    <td nowrap class="TableData"> <?=$ROW["DISC_GROUP_NAME"]?></td>
                    <td class="TableData" >
                        <select name="<?=$namezhi?>">
                            <?
                            $array_info=  explode(',',trim($ROW["DISC_GROUP_UID"],','));
                            for($i=0;$i<count($array_info);$i++)
                            {
                                echo "<option value=".$array_info[$i].">".trim(GetUserNameByUid($array_info[$i]),',')."</option>";
                            }
                            ?>
                        </select>

                </tr>
                <?
            }
            ?>
            <tr align="center" class="TableControl">
                <td colspan=2 nowrap>
                    <input type="text" style="display: none;" name="LEAVE_PERSON" value="<?=$LEAVE_PERSON?>" class="BigButton">
                    <?
                    if($count_im_discuss_group==0 && $count_im_group==0)
                    {
                        ?>
                        <input type="button" value="<?=_("返回")?>" onclick="location='new.php'" class="BigButton">
                        <?
                    }
                    else {
                        ?>
                        <input type="submit" value="<?=_("保存")?>" class="BigButton">
                        <?
                    }
                    ?>
                </td>
            </tr>
        </table>
    </form>

    <?
    if($USER_ID1!="")
        header("location: ../staff_info/user_list.php?DEPT_ID=$LEAVE_DEPT");
}
?>
</body>
</html>
