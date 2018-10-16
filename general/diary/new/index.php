<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("新建日志");
include_once("inc/header.inc.php");

$a_para_array = get_sys_para("LOCK_TIME,ALL_SHARE,SMS_REMIND");
$s_lock_time = $a_para_array["LOCK_TIME"];
$s_all_share = $a_para_array["ALL_SHARE"];
$s_sms_remind_str = $a_para_array["SMS_REMIND"];
$remind_array = explode("|", $s_sms_remind_str);
$sms_remind = $remind_array[0];
$sms2_remind = $remind_array[1];
$sms3_remind = $remind_array[2];
$sms4_remind = $remind_array[3];
$sms5_remind = $remind_array[4];

/****获取日志模板  songyang*****/
$a_wl_format=get_sys_para("DIARY_WORK_LOG_FORMAT");
$a_wl_format_id=$a_wl_format['DIARY_WORK_LOG_FORMAT']; 
$QUERY_MASTER=true;
$str_wl_format_value='';
if(isset($a_wl_format_id) && $a_wl_format_id!=NULL && $a_wl_format_id!="")
{
	$str_query_wl_format="SELECT CONTENT FROM html_model WHERE MODEL_ID='$a_wl_format_id'";
	$res_query_wl_format=exequery(TD::conn(),$str_query_wl_format,$QUERY_MASTER);
	if($row=mysql_fetch_array($res_query_wl_format))
   {
   	$str_wl_format_value = $row["CONTENT"];
   	$str_wl_format_value = @gzuncompress($str_wl_format_value);
   }
} 
/****获取日志模板  结束*****/
if($s_lock_time != "")
{
    $a_lock_time = explode(",", $s_lock_time);
    $s_start = $a_lock_time[0];
    $s_end = $a_lock_time[1];
    $s_days = intval($a_lock_time[2]);
}

if($s_cal_date == "" || $s_cal_date == "undefined")
{
    $s_cal_date = date("Y-m-d",time());
}
else
{
    $s_cal_date = (strlen($s_cal_date)==8) ? strtotime($s_cal_date) : date("Y-m-d", $s_cal_date);
    
    $s_msg = sprintf(_("%s 的日程安排"), $s_cal_date);
    $s_subject = $s_msg;
    $query = "SELECT * FROM calendar WHERE CAL_TYPE != '2' and USER_ID = '".$_SESSION["LOGIN_USER_ID"]."' and TO_DAYS(from_unixtime(CAL_TIME)) = (TO_DAYS('$s_cal_date')) order by CAL_TIME";
    $cursor = exequery(TD::conn(),$query);
    while($ROW = mysql_fetch_array($cursor))
    {
        $CAL_TIME       = $ROW["CAL_TIME"];
        $CAL_TIME       = date("Y-m-d H:i:s", $CAL_TIME);
        $END_TIME       = $ROW["END_TIME"];
        $END_TIME       = date("Y-m-d H:i:s", $END_TIME);
        $CAL_TYPE       = $ROW["CAL_TYPE"];
        $CONTENT        = $ROW["CONTENT"];
        $MANAGER_ID     = $ROW["MANAGER_ID"];
        
        $s_manager_name = "";
        if($MANAGER_ID != "")
        {
            $s_manager_name = "("._(" 安排人:").td_trim(GetUserNameById($MANAGER_ID));
        }
        
        $CAL_TIME = substr($CAL_TIME, 0, 16);
        $END_TIME = substr($END_TIME, 0, 16);
        if(substr($CAL_TIME, 0, 10) == substr($END_TIME, 0, 10))
        {
            $CAL_TIME = substr($CAL_TIME, 11, 5);
            $END_TIME = substr($END_TIME, 11, 5);
        }
        
        $s_dia_content .= $CAL_TIME." - ".$END_TIME.$s_manager_name."<br>".$CONTENT."<br><br>";
    }
}

if($s_subject == "")
{
    $a_weeknames = array(_("星期日"),_("星期一"),_("星期二"),_("星期三"),_("星期四"),_("星期五"),_("星期六"));
    $a_dateArr = explode("-", $s_cal_date);
    $i_week = date("w", mktime(0, 0, 0, $a_dateArr[1], $a_dateArr[2], $a_dateArr[0]));
    $s_weekname = $a_weeknames[$i_week];
    $s_subject = $s_cal_date." ".$s_weekname._(" 日志");
}

if(isset($FW_CONTENT))
{
    $s_dia_content = td_iconv($FW_CONTENT, "utf-8", MYOA_CHARSET);
}
else if($str_wl_format_value!='')
{
	$s_dia_content = $str_wl_format_value;
}
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/diary_new.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/mouse_mon.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/tabopt.js"></script>
<script Language="JavaScript">
confirmSaveBeforeCloseTab("", "", "");
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";

setInterval(div_change,500);
function div_change()
{
    var i_div_left=document.getElementById("div_left").scrollHeight - 20;
    document.getElementById("div_right").style.height=i_div_left+"px";
}

function CheckForm(s_start,s_end,LAST_DATE)
{
    var s_form_time=document.form1.dia_date.value;
    
    if(s_form_time <= LAST_DATE)
    {
        alert("<?=_("所填的日志日期在锁定范围内！")?>");
        return (false);
    }
    else if(s_start=="" && s_end!="" && s_form_time <= s_end)
    {
        alert("<?=_("所填的日志日期在锁定范围内！")?>");
        return (false);
    }
    else if(s_start!="" && s_end=="" && s_form_time >= s_start)
    {
        alert("<?=_("所填的日志日期在锁定范围内！")?>");
        return (false);
    }
    else if(s_start!="" && s_end!="" && s_form_time <= s_end && s_form_time >=s_start)
    {
        alert("<?=_("所填的日志日期在锁定范围内！")?>");
        return (false);
    }
    if(checkEditorDirty('CONTENT')=="")
    {
        alert("<?=_("日志内容不能为空！")?>");
        return (false);
    }
    document.form1.OP.value = "1";
    document.getElementById("submit_btn").disabled = "true";
    return (true);
}

function InsertImage(src)
{
    AddImage2Editor('CONTENT', src);
}
function upload_attach()
{
    if(CheckForm('<?=$s_start?>','<?=$s_end?>','<?=$s_days > 0 ? date("Y-m-d",strtotime("-".$s_days."day",time())) : ""?>'))
    {
        document.form1.OP.value="0";
        document.form1.submit();
    }
}

function mysubmit()
{
    if(CheckForm('<?=$s_start?>','<?=$s_end?>','<?=$s_days>0?date("Y-m-d",strtotime("-".$s_days."day",time())):""?>'))
    {
        document.form1.submit();
        document.form1.thesubmit.disabled = true;
    }
}

function check_unload()
{
    if(mouse_is_out && checkEditorDirty('CONTENT'))
    {
        return '<?=_("内容没有保存，确定不保存退出吗？")?>';
    }
}

function change_share(a)
{
    if(a == 2)
    {
        var obj = document.getElementById("dia_share");
        obj.style.display = 'none';
    }
    else
    {
        var obj = document.getElementById("dia_share");
        obj.style.display = '';
    }
}

function link_data()
{
    var cur_date = document.form1.dia_date.value;
    
    _get('get_linkdata.php?CUR_DATE='+cur_date, '', function(req){
        if(req.status==200)
        {
            if(req.responseText.length > 26)
            {
                setEditorHtml('CONTENT',req.responseText);
            }
            else
            {
                alert("<?=_("暂无相关数据")?>");
                setEditorHtml('CONTENT','');
            }
        }
        else
        {
            setEditorHtml('CONTENT','');
        }
    });
}

function ChangeTitle()
{
    var s_datetime = document.form1.dia_date.value;

    var a_weekday = ["<?=_('星期日')?>", "<?=_('星期一')?>", "<?=_('星期二')?>", "<?=_('星期三')?>", "<?=_('星期四')?>", "<?=_('星期五')?>", "<?=_('星期六')?>"];
    var date_new = new Date(Date.parse(s_datetime.replace(/\-/g,"/"))); 
    document.form1.subject.value=s_datetime+" "+a_weekday[date_new.getDay()]+" "+"<?=_('日志')?>";
}

function share()
{
    var obj = document.getElementById("share");
    if(obj.style.display == 'none')
    {
        obj.style.display = '';
    }
    else
    {
        obj.style.display = 'none';
    }
}

function go_back()
{
	if(checkEditorDirty('CONTENT'))
	{
	    msg='<?=_("内容没有保存，确定不保存退出吗？")?>';
    	if(window.confirm(msg))
    	{
            location='../index.php';
    	}
    }
    else
    {
        location='../index.php';
    }
}
function myload()
{
    var content = window.external.OA_SMS("","","FW_CONTENT");
    setEditorHtml('CONTENT',content); 
    //CKEDITOR.instances['TD_HTML_EDITOR_CONTENT'].setData(content);
}
</script>

<body <? if($FW_FLAG==1){?>onLoad="myload();"<? }?>>
<?
$s_user_name = "";
$s_manager = "";
$s_to_id = "";
$s_to_id2 = "";

$query = "SELECT DEPT_ID FROM user WHERE USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $DEPT_ID = $ROW["DEPT_ID"];
    
    $query2 = "SELECT MANAGER FROM department WHERE DEPT_ID = '$DEPT_ID'";
    $cursor2 = exequery(TD::conn(),$query2);
    if($ROW2= mysql_fetch_array($cursor2))
    {
        $s_to_id .= $ROW2["MANAGER"].",";
    }
    
    $s_to_id = rtrim($s_to_id,',');
}

$a_to_id = explode(",",$s_to_id);
$i_count = count($a_to_id);
for($i = 0; $i < $i_count; $i++)
{
	if(!$a_to_id[$i]) continue;
	for($j = $i+1; $j < $i_count; $j++)
	{
		if($a_to_id[$i] == $a_to_id[$j])
		{
			$a_to_id[$j] = NULL;
		}
	}
	$s_to_id2.= $a_to_id[$i].",";
}
$s_to_id=$s_to_id2;
//echo $s_to_id;
$query = "SELECT SHARE_NAME from diary_share where USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
	$SHARE_NAME = $ROW[0];
}

if($SHARE_NAME)
{
	$s_user_name = GetUserNameById($SHARE_NAME);
	$s_to_id = $SHARE_NAME;
}
else
{
	$s_user_name=GetUserNameById($s_to_id);
}

?>
<div class="wrapper">
    <form enctype="multipart/form-data" action="submit.php"  method="post" name="form1" class="form-horizontal" onSubmit="return CheckForm('<?=$s_start?>','<?=$s_end?>','<?=$s_days>0?date("Y-m-d",strtotime("-".$s_days."day",time())):""?>');">
        <div class="row">
            <div class="span9">
                <dl id="div_left" class="diary-new-list">
                    <ul class="diary-header">
                        <li class="diary-header-item">
                            <h2 class="diary-header-title"><?=_("新建日志")?></h2>
                            <a class="diary-auto" title=<?=_("关联公告通知、新闻、邮件、工作流、日程、任务相关模块数据")?> href="#" onClick="link_data()"><?=_("快速生成日志")?></a>
                        </li>
                    </ul>
                    
                    <div class="control-group">
                        <!-- Text input-->
                        <label class="control-label" for="input01"><?=_("标题：")?></label>
                        <div class="controls" align='left'>
                            <input type="text" name="subject" class="input-xxlarge" style="width:646px" value="<?=$s_subject?>">
                        </div>
                    </div>
                    
                    <div class="control-group" style="text-align:left">
                        <!-- Textarea -->
                        <label class="control-label"><?=_("内容：")?></label>
                        <div class="controls" style="width: 660px;">
                        <?
                        $editor = new Editor('CONTENT') ;
                        $editor->ToolbarSet = 'Default';
                        $editor->Height = '200' ;
                        $editor->AttachFileId = 'attachment_id_old';
                        $editor->AttachFileName = 'attachment_name_old';
                        $editor->Config = array("model_type" => "03");
                        $editor->Value = $s_dia_content ;
                        $editor->Create() ;
                        ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <!-- Label -->
                        <label class="control-label"><?=_("附件：")?></label>
                        <div class="controls" style="padding-top: 5px;width: 660px;">
                            <script>ShowAddFile();</script>
                            <script>ShowAddImage();</script>
                            <script>$("ATTACHMENT_upload_div").innerHTML='<a href="javascript:upload_attach();"><?=_("上传附件")?></a>'</script>
                            <input type="hidden" name="attachment_id_old" value>
                            <input type="hidden" name="attachment_name_old" value>
                        </div>
                    </div>
                    
                    <div align="right" style="width:720px;">
                        <input type="hidden" name="OP" value="1">
                        <input type="hidden" name="FROM" value="<?=$FROM?>">
                        <button type="submit" class="btn btn-primary" id="submit_btn"><?=_("保存")?></button>
                    <?
                    if($FROM_URL == "diary")
                    {
                    ?>
                        <button type="button" class="btn" onClick="go_back();"><?=_("返回")?></button>
                    <?
                    }
                    else
                    {
                    ?>
                        <button type="button" class="btn" onClick="top.window.closeTab();"><?=_("关闭")?></button>
                    <?
                    }
                    ?>
                    </div>
                </dl>
            </div>
            
            <div class="span3">
                <dl id="div_right" class="diary-info-list">
                    <div class="row-fluid" style="text-align:left;margin-top:15px;">
                        <input type="text" name="dia_date" class="input-medium" value="<?=$s_cal_date?>" onClick="WdatePicker();ChangeTitle()" >
                    </div>
                    
                    <hr class="diary-hr">
                    
                    <div>
                        <select class="input-medium" name="dia_type" onChange="change_share(this.value)">
                            <?=code_list("DIARY_TYPE","")?>
                        </select>
                    </div>
                    
                    <hr class="diary-hr">
                    
                    <div class="control-group" id="dia_share">
                    
                        <label class="control-label" for="share_type" style="*+width:60px"><?=_("共享")?> <input id="share_type" type="checkbox" name="share_type" value="1" onClick="share()" style="*+margin-top:-5px"/></label>
                        
                        <div id="share" style="display:none">
                        <!-- Textarea -->
                            <div class="textarea">
                                <input type="hidden" name="to_id" value="<?=$s_to_id ?>">
                                <textarea  class="SmallStatic" name="to_name" style="margin: 0px; width: 185px; height: 50px; " readonly><?=$s_user_name ?> </textarea>
                                <div align="right" style="margin-top:5px;">
                                    <a href="#" class="orgAdd" onClick="SelectUser('1', '2', 'to_id', 'to_name')"><?=_("添加")?></a>
                                    <a href="#" class="orgClear" onClick="ClearUser('to_id', 'to_name')"><?=_("清空")?></a>
                                </div>
                            </div>
                                
                                <label class="sms-remind-label" for="share_init">
                                    <input type="checkbox" name="share_init" value="1" /><?=_("设置为默认共享范围")?>
                                </label>
                                <?
                                sms_remind(13);
                                
                                if(find_id($sms4_remind, '13'))
                                {
                                    echo "<label class='sms-remind-label'><input type=\"checkbox\" name=\"SNS_REMIND\" id=\"SNS_REMIND\"";
                                    if(find_id($sms5_remind, '13'))
                                        echo " checked";
                                    echo ">"._("分享到企业社区")."</label>";
                                }
                                ?>
                        </div>
                    </div>
                </dl>
            </div>
        </div>
    </form>
</div>
</body>