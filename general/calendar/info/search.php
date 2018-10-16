<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("check_priv.inc.php");
//2013-4-11 主服务查询
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER=""; 

$HTML_PAGE_TITLE = _("日程安排查询");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/calendar/css/calendar_person.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script>
function delete_cal(CAL_ID,DEPT_ID,SEND_TIME_MIN,SEND_TIME_MAX,CAL_LEVEL,OVER_STATUS,CONTENT)
{
	msg='<?=_("确认要删除该日程吗？")?>';
	if(window.confirm(msg))
	{
		URL="delete_search.php?CAL_ID="+CAL_ID+"&DEPT_ID="+DEPT_ID+"&SEND_TIME_MIN="+SEND_TIME_MIN+"&SEND_TIME_MAX="+SEND_TIME_MAX+"&CAL_LEVEL="+CAL_LEVEL+"&OVER_STATUS="+OVER_STATUS+"&CONTENT="+CONTENT+"";
		window.location=URL;
	}
}
function delete_affair(AFF_ID,DEPT_ID,SEND_TIME_MIN,SEND_TIME_MAX,CONTENT)
{
	msg='<?=_("确认要删除该事务吗？")?>';
	if(window.confirm(msg))
	{
		URL="delete_search.php?AFF_ID="+AFF_ID+"&DEPT_ID="+DEPT_ID+"&SEND_TIME_MIN="+SEND_TIME_MIN+"&SEND_TIME_MAX="+SEND_TIME_MAX+"&CONTENT="+CONTENT+"";
		window.location=URL;
	}
}
function delete_task(TASK_ID,DEPT_ID,SEND_TIME_MIN,SEND_TIME_MAX,IMPORTANT,TASK_STATUS,CONTENT)
{  
	msg='<?=_("确认要删除该任务吗？")?>';
	if(window.confirm(msg))
	{
		URL="delete_search.php?TASK_ID="+TASK_ID+"&DEPT_ID="+DEPT_ID+"&SEND_TIME_MIN="+SEND_TIME_MIN+"&SEND_TIME_MAX="+SEND_TIME_MAX+"&TASK_STATUS="+TASK_STATUS+"&IMPORTANT="+IMPORTANT+"&CONTENT="+CONTENT+"";
		window.location=URL;
	}
}
jQuery(function(){
    jQuery("#allbox_for").click(function(){
        if(jQuery("#allbox_for").is(":checked"))
        {
            jQuery("[name='cal_select']").prop("checked",true);
        }
        else
        {
            jQuery("[name='cal_select']").prop("checked",false);
        }
    })
    
    jQuery("[name='cal_select']").click(function(){
        jQuery("#allbox_for").prop("checked",false);
    })
});

function get_checked()
{
    checked_str="";
    
    jQuery("input[name='cal_select']:checkbox").each(function(){ 
        if(jQuery(this).is(":checked")){
            checked_str += jQuery(this).val()+","
        }
    })
    
    return checked_str;
}

function del_cal_this(DEPT_ID,SEND_TIME_MIN,SEND_TIME_MAX,CAL_LEVEL,OVER_STATUS,CONTENT)
{
   delete_str=get_checked();
  
   if(delete_str=="")
   {
      alert("<?=_("要删除日程，请至少选择其中一条。")?>");
      return;
   }
   msg='<?=_("确认要删除所选日程吗？")?>';
   if(window.confirm(msg))
   {
      url="delete_search.php?DEPT_ID="+DEPT_ID+"&SEND_TIME_MIN="+SEND_TIME_MIN+"&SEND_TIME_MAX="+SEND_TIME_MAX+"&CAL_LEVEL="+CAL_LEVEL+"&OVER_STATUS="+OVER_STATUS+"&CONTENT="+CONTENT+"&CAL_ID="+ delete_str;
      location=url;
   }
}
function del_affair_this(DEPT_ID,SEND_TIME_MIN,SEND_TIME_MAX,CONTENT)
{
   delete_str=get_checked();
  
   if(delete_str=="")
   {
      alert("<?=_("要删除事务，请至少选择其中一条。")?>");
      return;
   }
   msg='<?=_("确认要删除所选事务吗？")?>';
   if(window.confirm(msg))
   {
      url="delete_search.php?DEPT_ID="+DEPT_ID+"&SEND_TIME_MIN="+SEND_TIME_MIN+"&SEND_TIME_MAX="+SEND_TIME_MAX+"&CONTENT="+CONTENT+"&AFF_ID="+ delete_str;
      location=url;
   }
}
function del_task_this(DEPT_ID,SEND_TIME_MIN,SEND_TIME_MAX,IMPORTANT,TASK_STATUS,CONTENT)
{
   delete_str=get_checked();
   if(delete_str=="")
   {
      alert("<?=_("要删除任务，请至少选择其中一条。")?>");
      return;
   }
   msg='<?=_("确认要删除所选任务吗？")?>';
   if(window.confirm(msg))
   {
      url="delete_search.php?DEPT_ID="+DEPT_ID+"&SEND_TIME_MIN="+SEND_TIME_MIN+"&SEND_TIME_MAX="+SEND_TIME_MAX+"&IMPORTANT="+IMPORTANT+"&TASK_STATUS="+TASK_STATUS+"CONTENT="+CONTENT+"&TASK_ID="+ delete_str;
      location=url;
   }
}
function ShowDialog(id,vTopOffset)
{
   if(typeof arguments[1] == "undefined")
     vTopOffset = 90;
     
   var bb=(document.compatMode && document.compatMode!="BackCompat") ? document.documentElement : document.body;
   $("overlay").style.width = Math.max(parseInt(bb.scrollWidth),parseInt(bb.offsetWidth))+"px";
   $("overlay").style.height = Math.max(parseInt(bb.scrollHeight),parseInt(bb.offsetHeight))+"px";

   $("overlay").style.display = 'block';
   $(id).style.display = 'block';

   $(id).style.left = ((bb.offsetWidth - $(id).offsetWidth)/2)+"px";
   $(id).style.top  = (vTopOffset)+"px";//(vTopOffset + bb.scrollTop)+
}
</script>

<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_TIME_U=time();
//-----------合法性校验---------
if($SEND_TIME_MIN!="")
{
    $TIME_OK=is_date($SEND_TIME_MIN);
    if(!$TIME_OK)
    { 
        $MSG1 = sprintf(_("日期的格式不对，应形如 %s"), $CUR_DATE);
        Message(_("错误"),$MSG1);
        Button_Back();
        exit;
    }
    $SEND_TIME_MIN_PASS=$SEND_TIME_MIN;
    $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
    $SEND_TIME_MIN_U=strtotime($SEND_TIME_MIN);
}
if($SEND_TIME_MAX!="")
{
    $TIME_OK=is_date($SEND_TIME_MAX);
    if(!$TIME_OK)
    { 
        $MSG2 = sprintf(_("日期的格式不对，应形如 %s"), $CUR_DATE);
        Message(_("错误"),$MSG2);
        Button_Back();
        exit;
    }
    $SEND_TIME_MAX_PASS=$SEND_TIME_MAX;
    $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
    $SEND_TIME_MAX_U=strtotime($SEND_TIME_MAX);
}
$USER_ID_STR="";
if($DEPT_ID!="")
{
    $DEPT_ID_CHILD = td_trim(GetChildDeptId($DEPT_ID));
    
    $WHERE_STR =" and USER.DEPT_ID in ($DEPT_ID_CHILD) ";
}
//添加角色判断
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
    $priv_where = "";
}
else
{
    $sql = "SELECT PRIV_NO from USER_PRIV where USER_PRIV='".$_SESSION["LOGIN_USER_PRIV"]."'";
    $result= exequery(TD::conn(),$sql);
    if($row=mysql_fetch_array($result))
    {
        $PRIV_NO = $row["PRIV_NO"];
    }
    $priv_where = " and USER.USER_PRIV_NO>='".$PRIV_NO."' AND USER.USER_PRIV_NO!='999'";
}
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
        $query = "SELECT USER_ID,USER_NAME,DEPT_ID from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and (USER.NOT_LOGIN='0' or USER.NOT_MOBILE_LOGIN='0') ".$WHERE_STR." and find_in_set(USER.USER_ID,'".$user_id."') order by PRIV_NO,USER_NO,USER_NAME";
    }else{
        $query = "SELECT USER_ID,USER_NAME,DEPT_ID from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and (USER.NOT_LOGIN='0' or USER.NOT_MOBILE_LOGIN='0') ".$WHERE_STR." order by PRIV_NO,USER_NO,USER_NAME";
    }
}else{
    $query = "SELECT USER_ID,USER_NAME,DEPT_ID from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and (USER.NOT_LOGIN='0' or USER.NOT_MOBILE_LOGIN='0') ".$WHERE_STR." order by PRIV_NO,USER_NO,USER_NAME";
}
$cursor1= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor1))
{
    $USERS[$ROW["USER_ID"]]["DEPT"]=dept_long_name($ROW["DEPT_ID"]);
    $USERS[$ROW["USER_ID"]]["NAME"]=$ROW["USER_NAME"];
    $USER_ID_STR.=$ROW["USER_ID"].",";
}
$USER_ID_STR_ARRAY=explode(",",td_trim($USER_ID_STR));
$USER_ID_STR_ARRAY=array_unique($USER_ID_STR_ARRAY);
 //------------------------ 生成条件字符串 ------------------
if($CAL_TYPE==0)  //如果是日程
{
    $CONDITION_STR="";
    if($CAL_LEVEL=="0")
        $CONDITION_STR.=" and CALENDAR.CAL_LEVEL=''";
    else if($CAL_LEVEL=="1")
        $CONDITION_STR.=" and CALENDAR.CAL_LEVEL='1'";
    else if($CAL_LEVEL=="2")
        $CONDITION_STR.=" and CALENDAR.CAL_LEVEL='2'";
    else if($CAL_LEVEL=="3")
        $CONDITION_STR.=" and CALENDAR.CAL_LEVEL='3'";
    else if($CAL_LEVEL=="4")
        $CONDITION_STR.=" and CALENDAR.CAL_LEVEL='4'";
    if($CONTENT!="")
        $CONDITION_STR.=" and CALENDAR.CONTENT like '%".$CONTENT."%'";
    if($SEND_TIME_MIN!="")
        $CONDITION_STR.=" and CALENDAR.CAL_TIME>='$SEND_TIME_MIN_U'";
    if($SEND_TIME_MAX!="")
        $CONDITION_STR.=" and CALENDAR.END_TIME<='$SEND_TIME_MAX_U'";
    if($OVER_STATUS=="1")
        $CONDITION_STR.=" and CALENDAR.OVER_STATUS='0' and CALENDAR.CAL_TIME>'$CUR_TIME_U'";
    else if($OVER_STATUS=="2")
        $CONDITION_STR.=" and CALENDAR.OVER_STATUS='0' and CALENDAR.CAL_TIME<='$CUR_TIME_U' and CALENDAR.END_TIME>='$CUR_TIME_U'";
    else if($OVER_STATUS=="3")
        $CONDITION_STR.=" and CALENDAR.OVER_STATUS='0' and CALENDAR.END_TIME<'$CUR_TIME_U'";
    else if($OVER_STATUS=="4")
        $CONDITION_STR.=" and CALENDAR.OVER_STATUS='1'";
    
   ?>
   <div style="width:90%; margin:0 auto; height:50px; line-height:50px;"><span class="big3"> <?=_("日程安排查询结果")?></span>
   </div>
   <?
   $CAL_COUNT=0;
   $DEL_COUNT=0;
   $CODE_NAME=array();
   $MANAGER=array();
   //参与或者所属的日程(find_in_set('$USER_ID',TAKER) or find_in_set('$USER_ID',OWNER))
   $CAL_ID = '';
   for($I=0;$I<count($USER_ID_STR_ARRAY);$I++)
   { 
        if($USER_ID_STR_ARRAY[$I]=="")
            continue;
        $sql = "SELECT * from CALENDAR,USER where USER.USER_ID=CALENDAR.USER_ID AND (CALENDAR.USER_ID='$USER_ID_STR_ARRAY[$I]' or find_in_set('$USER_ID_STR_ARRAY[$I]',CALENDAR.TAKER) or find_in_set('$USER_ID_STR_ARRAY[$I]',CALENDAR.OWNER))".$priv_where." and CALENDAR.CAL_TYPE!='2'".$CONDITION_STR." order by CAL_TIME,END_TIME";
        $result=exequery(TD::conn(),$sql);
        while($row=mysql_fetch_array($result))
        {
            $CAL_ID .= $row["CAL_ID"].",";
        }
    }
    $CAL_ID_ARRAY=explode(",",td_trim($CAL_ID));
    $CAL_ID_STR_ARRAY=array_unique($CAL_ID_ARRAY);
    $CAL_ID_STR = implode(",",$CAL_ID_STR_ARRAY); 
if($CAL_ID_STR!='')
{    
    $query = "SELECT * from CALENDAR where CAL_ID in($CAL_ID_STR) order by CAL_TIME,END_TIME";
    $cursor=exequery(TD::conn(),$query,$QUERY_MASTER);
        while($ROW=mysql_fetch_array($cursor))
        {
            $USER_ID_C=$ROW["USER_ID"];
            //$USER_ID=$USER_ID_STR_ARRAY[$I];
            $CAL_ID=$ROW["CAL_ID"];
            $CAL_TIME=$ROW["CAL_TIME"];
            $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
            $END_TIME=$ROW["END_TIME"];
            $END_TIME=date("Y-m-d H:i:s",$END_TIME);
            $CAL_TYPE=$ROW["CAL_TYPE"];
            $CAL_LEVELS=$ROW["CAL_LEVEL"];
            $CONTENTS=$ROW["CONTENT"];
            $MANAGER_ID=$ROW["MANAGER_ID"];
            $OVER_STATUSS=$ROW["OVER_STATUS"];
            $OWNER=$ROW["OWNER"];
            $USER_NAME=td_trim(GetUserNameByUserId($USER_ID_C));
            $DEPT_NAME=$USERS[$USER_ID_C]["DEPT"];
            $MANAGER_NAME="";
            if($MANAGER_ID!="")
            {
                if(!array_key_exists($CAL_TYPE, $MANAGER))
                {
                    $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
                    $cursor1= exequery(TD::conn(),$query);
                    if($ROW1=mysql_fetch_array($cursor1))
                    $MANAGER[$MANAGER_ID]=$ROW1["USER_NAME"];
                }
                $MANAGER_NAME=$MANAGER[$MANAGER_ID];
            }
            $CONTENTS=csubstr(strip_tags($CONTENTS), 0, 100);  
            if(!array_key_exists($CAL_TYPE, $MANAGER))
                $CODE_NAME[$CAL_TYPE]=get_code_name($CAL_TYPE,"CAL_TYPE");
            $CAL_TYPE=$CODE_NAME[$CAL_TYPE];
            if($OVER_STATUSS=="0")
            {
                if(compare_time($CUR_TIME,$END_TIME)>0)
                {
                    $STATUS_COLOR="#FF0000";
                    $CAL_TITLE=_("状态：已超时");
                }
                else if(compare_time($CUR_TIME,$CAL_TIME)<0)
                {
                    $STATUS_COLOR="#0000FF";
                    $CAL_TITLE=_("状态：未开始");
                }
                else
                {
                    $STATUS_COLOR="#0000FF";
                    $CAL_TITLE=_("状态：进行中");
                }
            }
            else
            {
                $STATUS_COLOR="#00AA00";
                $CAL_TITLE=_("状态：已完成");
            }
            $CAL_COUNT++;
            if($CAL_COUNT==1)
            {
         ?>
         <table class="table table-bordered table-hover" style="width:90%;margin:0 auto" align="center">
             <thead style="background-color:#EBEBEB;">
               <th nowrap style="width: 43px;"><?=_("选择")?></th>
               <th nowrap align="center"><?=_("用户")?></th>
               <!--<td nowrap align="center"><?=_("开始时间")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_up.gif" width="11" height="10"></td>-->
               <th nowrap align="center"><?=_("开始时间")?><i class="icon-arrow-up"></i></th>
               <th nowrap align="center"><?=_("结束时间")?></th>
               <th nowrap align="center"><?=_("事务类型")?></th>
               <th nowrap align="center"><?=_("事务内容")?></th>
               <th nowrap align="center"><?=_("安排人")?></th>
               <th nowrap align="center"><?=_("操作")?></th>
            </thead>
      
      <?
            }
      ?>
       <tr class="TableData">
       		<td nowrap align="center">
   <?
   
      	if($MANAGER_ID==$_SESSION["LOGIN_USER_ID"] or find_id($OWNER,$_SESSION["LOGIN_USER_ID"]) or ($USER_ID_C==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") or $_SESSION["LOGIN_USER_PRIV"]==1 or ($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]=="2"))
        {
            $DEL_COUNT++;
      ?>
           <input type="checkbox" name="cal_select" id="cal_select_<?=$CAL_ID?>" value="<?=$CAL_ID?>" >
      <?
        }
   ?>
         </td>
         <td nowrap align="center"><a style="text-decoration:none;color:#000" title="<?=$DEPT_NAME?>"><?=$USER_NAME?></a></td>
         <td nowrap align="center"><?=substr($CAL_TIME,0,-3)?></td>
         <td nowrap align="center"><?=substr($END_TIME,0,-3)?></td>
         <td nowrap align="center"><?=$CAL_TYPE?></td>
         <td title="<?=$CAL_TITLE?>"><span class="CalLevel<?=$CAL_LEVELS?>" title="<?=cal_level_desc($CAL_LEVELS)?>">&nbsp</span><a href="javascript:my_note_serach(<?=$CAL_ID?>);" style="color:<?=$STATUS_COLOR?>;"><?=$CONTENTS?></a></td>
         <td nowrap align="center"><?=$MANAGER_NAME?></td>
         <td nowrap align="center"><? if($MANAGER_ID==$_SESSION["LOGIN_USER_ID"] or find_id($OWNER,$_SESSION["LOGIN_USER_ID"]) or($USER_ID_C==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") or $_SESSION["LOGIN_USER_PRIV"]==1 or ($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]=="2")){?> <a href="javascript:delete_cal('<?=$CAL_ID?>','<?=$DEPT_ID?>','<?=$SEND_TIME_MIN_PASS?>','<?=$SEND_TIME_MAX_PASS?>','<?=$CAL_LEVEL?>','<?=$OVER_STATUS?>','<?=$CONTENT?>');"> <?=_("删除")?></a><? }?></td>
       </tr>
   <?
       }
}
   if($CAL_COUNT==0)
   {
      Message("",_("无符合条件的日程安排"));
   ?>
   <div align="center">
    <button type="button" class="btn" onClick="location='query.php?DEPT_ID=<?=$DEPT_ID?>';"><?=_("返回")?></button>
   </div>
   <?
      exit;
   }
   else
   {
   
   ?>
   <tr style="background:#fff">
        <td colspan="12" class="form-inline">
      <?
      if($DEL_COUNT > 0)
      {
      ?>     
             <label class="checkbox" for="allbox_for" style="float:left;"><input type="checkbox" name="allbox" id="allbox_for" ><?=_("全选")?></label> &nbsp;
             <button type="button" class="btn" style="margin-left: 10px;" onClick="del_cal_this('<?=$DEPT_ID?>','<?=$SEND_TIME_MIN_PASS?>','<?=$SEND_TIME_MAX_PASS?>','<?=$CAL_LEVEL?>','<?=$OVER_STATUS?>','<?=$CONTENT?>');" title="<?=_("删除所选日程")?>"><?=_("删除")?></button>
      <?
      }
      ?>
        </td>
      </tr>

</table>
    <?
   }
}
if($CAL_TYPE==1)//........周期性事务
{
    $CONDITION_STR=""; 
    if($CONTENT!="")
        $CONDITION_STR.=" and AFFAIR.CONTENT like '%".$CONTENT."%'";
    if($SEND_TIME_MIN!="")
        $CONDITION_STR.=" and AFFAIR.BEGIN_TIME>='$SEND_TIME_MIN'";
    if($SEND_TIME_MAX!="")
        $CONDITION_STR.=" and AFFAIR.END_TIME<='$SEND_TIME_MAX'";
    $AFF_COUNT=0;
    $DEL_COUNT=0;
    $CODE_NAME=array();
    $MANAGER=array();
    $AFF_ID = '';
    for($I=0;$I<count($USER_ID_STR_ARRAY);$I++)
    {
        $sql = "SELECT * from AFFAIR,USER where USER.USER_ID=AFFAIR.USER_ID and (AFFAIR.USER_ID='$USER_ID_STR_ARRAY[$I]' or find_in_set('$USER_ID_STR_ARRAY[$I]',AFFAIR.TAKER)) ".$priv_where." and AFFAIR.CAL_TYPE<>'2'".$CONDITION_STR;
        $result=exequery(TD::conn(),$sql);
        while($row=mysql_fetch_array($result))
        {
            $AFF_ID .= $row["AFF_ID"].",";
        }
    }
    $AFF_ID_ARRAY=explode(",",td_trim($AFF_ID));
    $AFF_ID_STR_ARRAY=array_unique($AFF_ID_ARRAY);
    $AFF_ID_STR = implode(",",$AFF_ID_STR_ARRAY);
    if($AFF_ID_STR!='')
    {   
        $Tquery = "SELECT * from AFFAIR where AFF_ID in($AFF_ID_STR) order by BEGIN_TIME,END_TIME ";   
        $Tcursor= exequery(TD::conn(),$Tquery,$QUERY_MASTER);
        while($ROW=mysql_fetch_array($Tcursor))
        {
            $AFF_COUNT++;
            $AFF_ID =$ROW["AFF_ID"];
            $BEGIN_TIME=$ROW["BEGIN_TIME"];
            $BEGIN_TIME=date("Y-m-d",$BEGIN_TIME);
            $END_TIME=$ROW["END_TIME"];
            if($END_TIME!=0)
            {
                $END_TIME=date("Y-m-d",$END_TIME);
            }
            $BEGIN_TIME_TIME=$ROW["BEGIN_TIME_TIME"];
            $END_TIME_TIME=$ROW["END_TIME_TIME"];
         
            $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
        // $END_TIME=date("Y-m-d H:i:s",$END_TIME);
            $TYPE=$ROW["TYPE"];
            $REMIND_DATE  =$ROW["REMIND_DATE"];
            $REMIND_TIME  =$ROW["REMIND_TIME"];
            $CONTENTS=$ROW["CONTENT"];
            $MANAGER_ID=$ROW["MANAGER_ID"];
            $CONTENTS=csubstr(strip_tags($CONTENTS), 0, 100);
            $USER_ID_C=$ROW["USER_ID"];
            $USER_ID=$USER_ID_STR_ARRAY[$I];
            $MANAGER_ID=$ROW["MANAGER_ID"];
            $OVER_STATUS=$ROW["OVER_STATUS"];
            $OWNER=$ROW["OWNER"];
            $USER_NAME=td_trim(GetUserNameByUserId($USER_ID_C));
            $DEPT_NAME=$USERS[$USER_ID]["DEPT"];
            $MANAGER_NAME="";
            if($MANAGER_ID!="")
            {           
                $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
                $cursor1= exequery(TD::conn(),$query);
                if($ROW1=mysql_fetch_array($cursor1))
                    $MANAGER_NAME=$ROW1["USER_NAME"];
            }
            switch($TYPE)
            {
                case "2":
                $TYPE_DESC=_("按日提醒");
                break;
                case "3":
                $TYPE_DESC=_("按周提醒");
                if($REMIND_DATE=="1")
                   $REMIND_DATE=_("周一");
                elseif($REMIND_DATE=="2")
                   $REMIND_DATE=_("周二");
                elseif($REMIND_DATE=="3")
                   $REMIND_DATE=_("周三");
                elseif($REMIND_DATE=="4")
                   $REMIND_DATE=_("周四");
                elseif($REMIND_DATE=="5")
                   $REMIND_DATE=_("周五");
                elseif($REMIND_DATE=="6")
                   $REMIND_DATE=_("周六");
                elseif($REMIND_DATE=="0")
                   $REMIND_DATE=_("周日");
                break;
                case "4":
                $TYPE_DESC=_("按月提醒");
                $REMIND_DATE.=_("日");
                break;
                case "5":
                $TYPE_DESC=_("按年提醒");
                $REMIND_DATE=str_replace("-",_("月"),$REMIND_DATE)._("日");
                break;
            }
            if($AFF_COUNT==1)
            {
?>

         <table class="table table-bordered table-hover" style="width:90%;margin:0 auto; margin-top:10px" align="center">
            <thead style="background-color:#EBEBEB;">
    	         <th width="40"><?=_("选择")?></th>
    	         <th nowrap align="center"><?=_("用户")?></th>
    	         <th nowrap align="center"><?=_("起始日期")?><i class="icon-arrow-up"></i></th>
               <th nowrap align="center"><?=_("结束日期")?></th>
		         <th nowrap align="center"><?=_("开始时间")?></th>
               <th nowrap align="center"><?=_("结束时间")?></th>
               <th nowrap align="center"><?=_("提醒类型")?></th>
               <th nowrap align="center"><?=_("提醒日期")?></th>
               <th nowrap align="center"><?=_("提醒时间")?></th>
               <th nowrap align="center"><?=_("事务内容")?></th>
               <th nowrap align="center"><?=_("安排人")?></th>
               <th nowrap align="center" width="70"><?=_("操作")?></th>
           </thead>

<?
            }
?>
         <tr class="TableData">
    	         <td>&nbsp;<?if($MANAGER_ID==$_SESSION["LOGIN_USER_ID"]  or($USER_ID_C==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") or $_SESSION["LOGIN_USER_PRIV"]==1 or ($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]=="2"))
         {$DEL_COUNT++;
            ?><input type="checkbox" name="cal_select" value="<?=$AFF_ID?>" >
         	<?}?>
         	   <td nowrap align="center"><a style="text-decoration:none;color:#000" title="<?=$DEPT_NAME?>"><?=$USER_NAME?></a></td>
               <td nowrap align="center"><?=$BEGIN_TIME?></td>
               <td nowrap align="center"><?=$END_TIME?></td>
               <td align="center" width=120><?=$BEGIN_TIME_TIME?></td>
               <td align="center" width=120><?=$END_TIME_TIME?></td>
               <td nowrap align="center"><?if($TYPE==2)echo _("按日提醒");else if($TYPE==3)echo _("按周提醒");else if($TYPE==4)echo _("按月提醒");else if($TYPE==5)echo _("按年提醒");?></td>
               <td nowrap align="center"><?=substr($REMIND_DATE,0)?></td>
               <td nowrap align="center"><?=substr($REMIND_TIME,0)?></td>
               <td title="<?=$AFF_TITLE?>"><span class="type<?=$TYPE?>">&nbsp</span><a href="javascript:my_affair_serach(<?=$AFF_ID?>);"><?=csubstr(strip_tags($CONTENTS),0,100);?></a></td>
                <td nowrap align="center"><?=$MANAGER_NAME?></td>
               <td nowrap align="center">
      <?
               if($MANAGER_ID==$_SESSION["LOGIN_USER_ID"]  or($USER_ID_C==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") or $_SESSION["LOGIN_USER_PRIV"]==1 or ($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]=="2"))
               {
      ?>
               <a href="javascript:delete_affair('<?=$AFF_ID?>','<?=$DEPT_ID?>','<?=$SEND_TIME_MIN_PASS?>','<?=$SEND_TIME_MAX_PASS?>','<?=$CONTENT?>');"> <?=_("删除")?></a>
      <?
               }  
      ?>    
                </td>
        </tr>
<?
        }
}
        if($AFF_COUNT==0)
        {
            Message("",_("无符合条件的事务安排"));
         ?>
   <div align="center">
    <button type="button" class="btn" onClick="location='query.php?DEPT_ID=<?=$DEPT_ID?>';"><?=_("返回")?></button>
   </div>
   <?
      exit;
        }
        else
        {
      
?>
 <tr class="TableControl">
   <?
   if($DEL_COUNT>0){
   ?>
    <td colspan="12" class="form-inline"><label class="checkbox" for="allbox_for" style="float:left;"><input type="checkbox" name="allbox" id="allbox_for" ><?=_("全选")?></label> &nbsp;
       <button type="button" class="btn" onClick="del_affair_this('<?=$DEPT_ID?>','<?=$SEND_TIME_MIN_PASS?>','<?=$SEND_TIME_MAX_PASS?>','<?=$CONTENT?>');" title="<?=_("删除所选事务")?>"><?=_("删除")?></button> &nbsp;
      
    </td>
   <?
}
   ?>
  </tr>
</table>
<?
        }
   
}
//.....................如果是任务.............. 
if($CAL_TYPE=="2") 
{
    //------------------------ 生成条件字符串 ------------------
    $CONDITION_STR="";
    if($IMPORTANT =="0")
        $CONDITION_STR.=" and TASK.IMPORTANT =''";
    else if($IMPORTANT =="1")
        $CONDITION_STR.=" and TASK.IMPORTANT ='1'";
    else if($IMPORTANT =="2")
        $CONDITION_STR.=" and TASK.IMPORTANT ='2'";
    else if($IMPORTANT =="3")
        $CONDITION_STR.=" and TASK.IMPORTANT ='3'";
    else if($IMPORTANT =="4")
        $CONDITION_STR.=" and TASK.IMPORTANT ='4'";
     
    if($CONTENT!="")
        $CONDITION_STR.=" and (TASK.CONTENT like '%".$CONTENT."%' or TASK.SUBJECT like '%".$CONTENT."%')";
    if($SEND_TIME_MIN!="")
        $CONDITION_STR.=" and TASK.BEGIN_DATE >='$SEND_TIME_MIN'";
    if($SEND_TIME_MAX!="")
        $CONDITION_STR.=" and TASK.END_DATE<='$SEND_TIME_MAX'";
    if($TASK_STATUS=="1")
        $CONDITION_STR.=" and TASK.TASK_STATUS='1' and TASK.BEGIN_DATE>'$CUR_DATE'";
    else if($TASK_STATUS=="2")
        $CONDITION_STR.=" and TASK.TASK_STATUS='2' and TASK.BEGIN_DATE<='$CUR_DATE' and TASK.END_DATE>='$CUR_DATE'";
    else if($TASK_STATUS=="3")
        $CONDITION_STR.=" and TASK.TASK_STATUS='3' and TASK.END_DATE<'$CUR_DATE'";
    else if($TASK_STATUS=="4")
        $CONDITION_STR.=" and TASK.TASK_STATUS='4'";
    else if($TASK_STATUS=="5")
        $CONDITION_STR.=" and TASK.TASK_STATUS='5'";
    $TASK_COUNT=0;
    $TASK_ID = '';
    for($I=0;$I<count($USER_ID_STR_ARRAY);$I++)
    {
        $sql = "SELECT * from  TASK,USER where TASK.USER_ID='$USER_ID_STR_ARRAY[$I]'".$priv_where." and TASK.TASK_TYPE<>'2'".$CONDITION_STR;
        $result=exequery(TD::conn(),$sql);
        while($row=mysql_fetch_array($result))
        {
            $TASK_ID .= $row["TASK_ID"].",";
        }
    }
    $TASK_ID_ARRAY=explode(",",td_trim($TASK_ID));
    $TASK_ID_STR_ARRAY=array_unique($TASK_ID_ARRAY);
    $TASK_ID_STR = implode(",",$TASK_ID_STR_ARRAY);
    if($TASK_ID_STR!='')
    {
        $query = "SELECT * from TASK where TASK_ID in($TASK_ID_STR) order by BEGIN_DATE,END_DATE ";
        $cursor=exequery(TD::conn(),$query,$QUERY_MASTER);
      
        while($ROW=mysql_fetch_array($cursor))
        {
            $TASK_COUNT++;
            $TASK_ID =$ROW["TASK_ID"];
            $BEGIN_DATE=$ROW["BEGIN_DATE"];
            $END_DATE=$ROW["END_DATE"];
            $TASK_TYPE=$ROW["TASK_TYPE"];
            $SUBJECT =$ROW["SUBJECT"];
            $CONTENTS=$ROW["CONTENT"];
            $IMPORTANTS=$ROW["IMPORTANT"];
            $TASK_STATUSS=$ROW["TASK_STATUS"];
            $TASK_NO =$ROW["TASK_NO"];
            $USER_ID_C=$ROW["USER_ID"];
            $USER_ID=$USER_ID_STR_ARRAY[$I];
            $MANAGER_ID=$ROW["MANAGER_ID"];
            $USER_NAME=td_trim(GetUserNameByUserId($USER_ID_C));
            $DEPT_NAME=$USERS[$USER_ID]["DEPT"];
            $MANAGER_NAME="";
            if($MANAGER_ID!="")
            {           
                $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
                $cursor1= exequery(TD::conn(),$query);
                if($ROW1=mysql_fetch_array($cursor1))
                    $MANAGER_NAME=$ROW1["USER_NAME"];
            }
            $CONTENTS=csubstr(strip_tags($CONTENTS), 0, 100);
      
            if($TASK_COUNT==1)
            {
   ?>
   
   <table class="table table-bordered table-hover" style="width:90%;margin:0 auto; margin-top:10px" align="center">
       <thead style="background-color:#EBEBEB;">
         <th width="40"><?=_("选择")?></th>
         <th nowrap align="center"><?=_("用户")?></th>
         <th nowrap align="center"><?=_("序号")?></th>
         <th nowrap align="center"><?=_("开始时间")?></th>
         <!--<td nowrap align="center"><?=_("开始时间")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_up.gif" width="11" height="10"></td>-->
         <th nowrap align="center"><?=_("结束时间")?></th>
         <th nowrap align="center"><?=_("任务类型")?></th>
         <th nowrap align="center"><?=_("任务标题")?></th>
         <th nowrap align="center"><?=_("状态")?></th>
         <th nowrap align="center"><?=_("任务内容")?></th>
         <th nowrap align="center"><?=_("安排人")?></th>
         <th width="70"><?=_("操作")?></th>
       </thead>
   
   <?
            }
   ?>
       <tr class="TableData">
         <td>&nbsp;
            <?
            if($MANAGER_ID==$_SESSION["LOGIN_USER_ID"] or ($USER_ID_C==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") or $_SESSION["LOGIN_USER_PRIV"]==1 or ($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]=="2")){
            ?>
            <input type="checkbox" name="cal_select" value="<?=$TASK_ID?>" >
          <?
            }
          ?>
         <td nowrap align="center"><a style="text-decoration:none; color:#000" title="<?=$DEPT_NAME?>"><?=$USER_NAME?></a></td>
         <td nowrap align="center"><?=$TASK_NO?></td>	
         <td nowrap align="center"><?=$BEGIN_DATE?></td>
         <td nowrap align="center"><?=$END_DATE?></td>
         <td nowrap align="center"><?if($TASK_TYPE==1)echo _("工作");else echo _("个人");?></td>
         <td nowrap align="center"><?=$SUBJECT?></td>
         <td nowrap align="center"><?if($TASK_STATUSS==1)echo _("未开始");else if($TASK_STATUSS==2)echo _("进行中");else if($TASK_STATUSS==3)echo _("已完成");else if($TASK_STATUSS==4)echo _("等待其他人");else if($TASK_STATUSS==5)echo _("已推辞");?></td>
         <td nowrap align="center" title="<?=$TASK_TITLE?>"><span class="important<?=$IMPORTANTS?>" title="<?=cal_level_desc($IMPORTANTS)?>">&nbsp</span><a href="javascript:my_task_serach(<?=$TASK_ID?>);"><?=$CONTENTS?></a></td>
          <td nowrap align="center"><?=$MANAGER_NAME?></td>
         <td nowrap align="center">
            <?
            if($MANAGER_ID==$_SESSION["LOGIN_USER_ID"] or ($USER_ID_C==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") or $_SESSION["LOGIN_USER_PRIV"]==1 or ($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]=="2")){
            ?>
             <a href="javascript:delete_task('<?=$TASK_ID?>','<?=$DEPT_ID?>','<?=$SEND_TIME_MIN_PASS?>','<?=$SEND_TIME_MAX_PASS?>','<?=$IMPORTANT?>','<?=$TASK_STATUS?>','<?=$CONTENT?>');"> <?=_("删除")?></a>
           <?
         }
           ?>
         </td>
       </tr>
   <?
        }
    }
if($TASK_COUNT==0)
{
   Message("",_("无符合条件的任务安排"));
  ?>
   <div align="center">
    <button type="button" class="btn" onClick="location='query.php?DEPT_ID=<?=$DEPT_ID?>';"><?=_("返回")?></button>
   </div>
   <?
      exit;
}
else
{
?>
 <tr class="TableControl">
    <td colspan="12" class="form-inline"><label class="checkbox" for="allbox_for" style="float:left;"><input type="checkbox" name="allbox" id="allbox_for" ><?=_("全选")?></label> &nbsp;
       <button type="button"  class="btn" onClick="del_task_this('<?=$DEPT_ID?>','<?=$SEND_TIME_MIN_PASS?>','<?=$SEND_TIME_MAX_PASS?>','<?=$IMPORTANT?>','<?=$TASK_STATUS?>','<?=$CONTENT?>');" title="<?=_("删除所选任务")?>"><?=_("删除")?></button> &nbsp;
    </td>
  </tr>
</table>&nbsp
   
  <? 
 }
}

 ?>
 <br>
<div align="center">
 <button type="button" class="btn" onClick="location='query.php?DEPT_ID=<?=$DEPT_ID?>';"><?=_("返回")?></button>
</div>
<div id="overlay"></div>
<div id="form_div" class="ModalDialog1">
  <div class="modal-header"><a class="operation" href="javascript:;"><button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="HideDialog('form_div');">&times;</button></a>
  <h3><span id="title" class="title"><?=_("新建日程")?></span></h3>
  </div>
  <div id="form_body" class="modal-body">
     
  </div>
</div>
</body>

</html>
