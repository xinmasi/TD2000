<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("修改日志");
include_once("inc/header.inc.php");

$dia_id = intval($_GET["dia_id"]);
$DIARY_FROM = preg_match_all("/[^a-zA-Z0-9\/]+/", $DIARY_FROM) ? '' : $DIARY_FROM;
$diary_copy_time = preg_match_all("/[^0-9_\/]+/", $diary_copy_time) ? '' : $diary_copy_time;

if($IS_MAIN == 1)
{
    $QUERY_MASTER = true;
}
else
{ 
    $QUERY_MASTER = "";
}
  
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

if($s_lock_time != "")
{
    $a_lock_time = explode(",", $s_lock_time);
    $s_start = $a_lock_time[0];
    $s_end = $a_lock_time[1];
    $s_days = intval($a_lock_time[2]);
}

$query = "SELECT * FROM diary WHERE DIA_ID='$dia_id'";
$cursor = exequery(TD::conn(), $query, $QUERY_MASTER);
if($ROW = mysql_fetch_array($cursor))
{
    $dia_date           = $ROW["DIA_DATE"];
    $dia_date           = strtok($dia_date," ");
    $dia_type           = $ROW["DIA_TYPE"];
    $user_id            = $ROW["USER_ID"];
    $subject            = $ROW["SUBJECT"];
    $notags_content     = $ROW["CONTENT"];
    $to_id              = $ROW["TO_ID"];
    $attachment_id      =$ROW["ATTACHMENT_ID"];
    $attachment_name    =$ROW["ATTACHMENT_NAME"];
    
    if($ROW["COMPRESS_CONTENT"] == "")
    {
        $content = $notags_content;
    }
    else
    {
        $content = @gzuncompress($ROW["COMPRESS_CONTENT"]);
        if($content===FALSE)
        {
            $content = $notags_content;
        }
    }
    
    $content=str_replace("\"","'",$content);    
    $a_weeknames=Array(_("星期日"),_("星期一"),_("星期二"),_("星期三"),_("星期四"),_("星期五"),_("星期六"));
    $a_dateArr = explode("-", $dia_date);
    $s_week = date("w",mktime(0,0,0,$a_dateArr[1],$a_dateArr[2],$a_dateArr[0]));
    $s_weekname=$a_weeknames[$s_week];
}
$s_user_name = "";
$s_tok = strtok($to_id,",");

while($s_tok != "")
{
    $query = "SELECT USER_NAME FROM user WHERE USER_ID='$s_tok'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $s_user_name .= $ROW["USER_NAME"].",";
    }
    $s_tok = strtok(",");
}


if($user_id != $_SESSION["LOGIN_USER_ID"])
{
    exit;
}

$s_display_type = ($dia_type == "2") ? "none" : "";
$s_display_type2 = ($to_id == "") ? "none" : "";
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
    FORM_TIME=document.form1.dia_date.value;
    
    if(FORM_TIME <= LAST_DATE)
    {
        alert("<?=_("所填的日志日期在锁定范围内！")?>");
        return (false);
    }
    else if(s_start=="" && s_end!="" && FORM_TIME <= s_end)
    {
        alert("<?=_("所填的日志日期在锁定范围内！")?>");
        return (false);
    }
    else if(s_start!="" && s_end=="" && FORM_TIME >= s_start)
    {
        alert("<?=_("所填的日志日期在锁定范围内！")?>");
        return (false);
    }
    else if(s_start!="" && s_end!="" && FORM_TIME <= s_end && FORM_TIME >=s_start)
    {
        alert("<?=_("所填的日志日期在锁定范围内！")?>");
        return (false);
    }
    if(getEditorText('CONTENT').length==0 && checkEditorDirty('CONTENT')=="")
    {
        alert("<?=_("日志内容不能为空！")?>");
        return (false);
    }
    
    document.form1.OP.value="1";
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
        document.form1.thesubmit.disabled=true;
    }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
    var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
    if(window.confirm(msg))
    {
        URL="delete_attach.php?DIA_ID=<?=$dia_id?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}
function check_unload()
{
    if(mouse_is_out && checkEditorDirty('CONTENT'))
    {
        return '<?=_("修改内容没有保存，确定不保存退出吗？")?>';
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
    var cur_date=document.form1.dia_date.value;

     _get('get_linkdata.php?CUR_DATE='+cur_date, '', function(req){
      if(req.status==200)
      {
          if(req.responseText.length>26)
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

function ChangeTitle(){
    var s_datetime=document.form1.dia_date.value;
    var a_weekday = ["<?=_('星期日')?>", "<?=_('星期一')?>", "<?=_('星期二')?>", "<?=_('星期三')?>", "<?=_('星期四')?>", "<?=_('星期五')?>", "<?=_('星期六')?>"];

    var s_date_new = new Date(Date.parse(s_datetime.replace(/\-/g,"/")));  
    document.form1.subject.value=s_datetime+" "+a_weekday[s_date_new.getDay()]+" "+"<?=_('日志')?>";
}

function share(){
	var obj = document.getElementById("share");
	if(obj.style.display=='none')
	{
	   obj.style.display='';
	}
	else
	{
	   obj.style.display='none';
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
</script>

<body>
<div class="wrapper">
    <form enctype="multipart/form-data" action="update.php"  method="post" name="form1" class="form-horizontal" onSubmit="return CheckForm('<?=$s_start?>','<?=$s_end?>','<?=$s_days>0?date("Y-m-d",strtotime("-".$s_days."day",time())):""?>');">
        <div class="row" style="text-align; center">
            <div class="span9">
                <dl id="div_left" class="diary-new-list">
                    <ul class="diary-header">
                        <li class="diary-header-item">
                            <h2 class="diary-header-title"><?=_("修改日志")?></h2>
                        </li>
                    </ul>
                    
                    <div class="control-group">
                        <!-- Text input-->
                        <label class="control-label" for="input01"><?=_("标题：")?></label>
                        <div class="controls" align='left'>
                            <input type="text" name="subject" class="input-xxlarge" style="width:646px" value="<?=$subject?>">
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
                        $editor->Value = $content ;
                        $editor->Create() ;
                        ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <!-- Label -->
                        <label class="control-label"><?=_("附件：")?></label>
                        <div class="controls" style="padding-top: 5px;width: 660px;">
                            <?=attach_link($attachment_id,$attachment_name,0,1,1,1,1,1,1,1)?>
                            <script>ShowAddFile();</script>
                            <script>ShowAddImage();</script>
                            <script>$("ATTACHMENT_upload_div").innerHTML='<a href="javascript:upload_attach();"><?=_("上传附件")?></a>'</script>
                            <input type="hidden" name="attachment_id_old" value="<?=$attachment_id?>">
                            <input type="hidden" name="attachment_name_old" value="<?=$attachment_name?>">
                        </div>
                    </div>
                    
                    <div align="right" style="width:720px;">
                        <input type="hidden" name="OP" value="1">
                        <input type="hidden" name="DIARY_FROM" value="<?=$DIARY_FROM?>">
                        <input type="hidden" name="diary_copy_time" value="<?=$diary_copy_time?>">
                        <input type="hidden" name="dia_id" value="<?=$dia_id?>">
                        <input type="hidden" name="FROM" value="<?=$FROM?>">
                        <button type="submit" class="btn btn-primary" id="submit_btn"><?=_("保存")?></button>
                        <button type="button" class="btn" onClick="go_back();"><?=_("返回")?></button>
                    </div>
                </dl>
            </div>
            
            <div class="span3">
                <dl id="div_right" class="diary-info-list">
                    <div class="row-fluid" style="text-align:left;margin-top:15px;">
                        <input type="text" name="dia_date" class="input-medium" value="<?=$dia_date?>" onClick="WdatePicker();ChangeTitle()" >
                    </div>
                    
                    <hr class="diary-hr">                    
                    <div>
                        <select class="input-medium" name="dia_type" onChange="change_share(this.value)">
                            <?=code_list("DIARY_TYPE",$dia_type)?>
                        </select>
                    </div>
                    
                    <hr class="diary-hr">                    
                    <div class="control-group" id="dia_share" style="display:<?=$s_display_type?>">                        
                        <label class="control-label" for="share_type"><?=_("共享")?> <input id="share_type" type="checkbox" name="share_type" value="1" onClick="share()" <?=($to_id == '' ? '' : ' checked')?>/></label>                        
                        <div id="share" style="display:<?=$s_display_type2?>">
                        <!-- Textarea -->
                            <div class="textarea">
                                <input type="hidden" name="to_id" value="<?=$to_id?>">
                                <textarea  class="SmallStatic" name="to_name" style="margin: 0px; width: 185px; height: 50px; " readonly> <?=$s_user_name?></textarea>
                                <div align="right" style="margin-top:5px;">
                                    <a href="#" class="orgAdd" onClick="SelectUser('9', '', 'to_id', 'to_name')"><?=_("添加")?></a>
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