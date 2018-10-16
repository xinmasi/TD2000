<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("联系人详情");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/address/right.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
$(function(){
    $('#right').height($(window).height());
    
    $(window).resize(function(){
        $('#right').height($(window).height());
    });
})
function send_email(EMAIL)
{
    var URL = "/general/email/new/?TO_WEBMAIL="+EMAIL;
    var myleft = (screen.availWidth-500)/2;
    window.open(URL, "send_email", "height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function add_edit(add_id)
{
    location='edit.php?add_id='+add_id+"&from=1&where_str=<?=$where_str?>";
}
function delete_add(ADD_ID,GROUP_ID)
{
	msg='<?=_("确认要删除该联系人吗？")?>';
	if(window.confirm(msg))
	{
		URL="delete.php?SHARE_TYPE=<?=$SHARE_TYPE?> & DELETE_SORT=1 & GROUP_ID=" + GROUP_ID + "&ADD_ID=" + ADD_ID + "&from=1&where_str=<?=$where_str?>";
		window.location=URL;
	}
}
</script>
<?
//当前用户权限判断
$is_priv = 0;
if($PUBLIC_FLAG == '1')
{
    $query = "SELECT DEPT_ID FROM user where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
    $cursor = exequery(TD::conn(),$query);
    if($row = mysql_fetch_array($cursor))
    {
        $dept_id = $row[0];
    }
    
    $s_group_id_str = '';
    $query = "SELECT GROUP_ID FROM address_group where USER_ID='' and (find_in_set('$dept_id',SUPPORT_DEPT) or SUPPORT_DEPT='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SUPPORT_USER))";
    $cursor=exequery(TD::conn(),$query);
    while($row = mysql_fetch_array($cursor))
    {
        $s_group_id_str .= $row[0].",";
    }
}

if($SHARE_TYPE == '1')
{
    $is_priv = 0;
}

//=========================== 显示联系人详情 =====================================
$query = "SELECT * from ADDRESS where ADD_ID='$ADD_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $s_short_name = "";
    $s_short_stratioin = "";
    $s_short_dept = "";
    
    $GROUP_ID       = $ROW["GROUP_ID"];
    $USER_ID        = $ROW["USER_ID"];
    $PSN_NAME       = $ROW["PSN_NAME"];
    $SEX            = $ROW["SEX"];
    $BIRTHDAY       = $ROW["BIRTHDAY"];
                      
    $NICK_NAME      = $ROW["NICK_NAME"];
    $MINISTRATION   = $ROW["MINISTRATION"];
    $MATE           = $ROW["MATE"];
    $CHILD          = $ROW["CHILD"];
    
    $DEPT_NAME      = $ROW["DEPT_NAME"];
    $ADD_DEPT       = $ROW["ADD_DEPT"];
    $POST_NO_DEPT   = $ROW["POST_NO_DEPT"];
    $TEL_NO_DEPT    = $ROW["TEL_NO_DEPT"];
    $FAX_NO_DEPT    = $ROW["FAX_NO_DEPT"];
    
    $ADD_HOME       = $ROW["ADD_HOME"];
    $POST_NO_HOME   = $ROW["POST_NO_HOME"];
    $TEL_NO_HOME    = $ROW["TEL_NO_HOME"];
    $MOBIL_NO       = $ROW["MOBIL_NO"];
    $BP_NO          = $ROW["BP_NO"];
    $EMAIL          = $ROW["EMAIL"];
    $OICQ_NO        = $ROW["OICQ_NO"];
    $ICQ_NO         = $ROW["ICQ_NO"];
    $PSN_NO         = $ROW["PSN_NO"];
    $NOTES          = $ROW["NOTES"];
    $ATTACHMENT_ID  = $ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME= $ROW["ATTACHMENT_NAME"];
    $PER_WEB        = $ROW["PER_WEB"];
    
    $MANAGE_USER    = $ROW["MANAGE_USER"];
    $SHARE_USER     = $ROW["SHARE_USER"];
    
    $NOTES = str_replace("\n","<br>",$NOTES);
    $NOTES = str_replace(" ","&nbsp;",$NOTES);
    
    $s_short_name = (strlen($PSN_NAME) > 10) ? csubstr($PSN_NAME,0,10)."..." : $PSN_NAME;
    $s_short_stratioin = (strlen($MINISTRATION) > 10) ? csubstr($MINISTRATION,0,10)."..." : $MINISTRATION;
    $s_short_dept = (strlen($DEPT_NAME) > 80) ? csubstr($DEPT_NAME,0,80)."..." : $DEPT_NAME;
    
    $SEX = ($SEX == "") ? "0" : $SEX;
    
    if($ATTACHMENT_NAME=="" && $SEX==0)
    {
        $URL_PIC=MYOA_STATIC_SERVER."/static/modules/address/images/man_big.png";
    }
    else if($ATTACHMENT_NAME=="" && $SEX==1)
    {
        $URL_PIC=MYOA_STATIC_SERVER."/static/modules/address/images/w_big.png";
    }
    else
    {
        $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
        $URL_PIC=$URL_ARRAY["view"];
    }
    
    if($USER_ID == $_SESSION["LOGIN_USER_ID"] || find_id($s_group_id_str,$GROUP_ID))
    {
        $is_priv = 1;
    }
?>

<body topmargin="5" style="OVERFLOW-Y:auto;height: 100%">
<div class="content">
    <div class="right" id="right" style="height: 100%">
        <div class="pic"><a class="thumbnail" style="border-radius:500px; padding:0px;"><img class="img-circle" src="<?=$URL_PIC?>" width="120" height="120" /></a></div>
        <div class="jianjie">
            <p><span id="xm" title="<?=$PSN_NAME?>"><?=$s_short_name?></span><span id="zhiwu" title="<?=$MINISTRATION?>"><?=$s_short_stratioin?></span></p>
            <p id="gs" title="<?=$DEPT_NAME?>"><?=$s_short_dept?></p>
            <ul>
                <li style="cursor:pointer;"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/A1.png" width="31" height="29" title="<?=_("发送邮件")?>" onClick="send_email('<?=$EMAIL?>');" /></li>
                <li style="cursor:pointer;"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/A3.png" width="31" height="29" title="<?=_("发送短信")?>" onClick="window.open('../../../../mobile_sms/new/index.php?TO_ID1=<?=$MOBIL_NO?>','','height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=110,top=60,resizable=yes')" /></li>
                <?
                if($is_priv != '0')
                {
                ?>
                    <li style="cursor:pointer;"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/A4.png" width="31" height="29" title="<?=_("编辑")?>" onClick="add_edit('<?=$ADD_ID?>');" /></li>
                    <li style="cursor:pointer;"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/A2.jpg" width="31" height="29" title="<?=_("删除")?>" onClick="delete_add('<?=$ADD_ID?>','<?=$GROUP_ID?>');"/></li>
                <?
                }
                ?>
            </ul>
        </div>
        <div class="address" style="border-top:1px solid #f1f1f1; padding-top:15px;word-break:break-all;">
            <form>
            <?
            if($MOBIL_NO != "" || $TEL_NO_DEPT != "" || $TEL_NO_HOME != "" || $FAX_NO_DEPT != "")
            {
            ?>
                <div style="width:550px; margin-bottom:20px; float:left; ">
            <?
			}
			if($MOBIL_NO != "")
            {
            ?>
                <div style="float: left;width: 500px;">
                    <label class="lb"><?=_("移动电话")?></label><?=$MOBIL_NO?><br><br />
                </div>
            <?
            }
            if($TEL_NO_DEPT != "")
            {
            ?>
                <div style="float: left;width: 500px;">
                    <label class="lb"><?=_("办公电话")?></label><?=$TEL_NO_DEPT?><br><br />
                </div>
            <?
            }
            if($TEL_NO_HOME != "")
            {
            ?>
                <div style="float: left;width:500px;">
                    <label class="lb"><?=_("住宅电话")?></label><?=$TEL_NO_HOME?><br><br />
                </div>
            <?
            }
            if($FAX_NO_DEPT != "")
            {
            ?>
                <div style="float: left;width: 500px;">
                    <label class="lb"><?=_("工作传真")?></label><?=$FAX_NO_DEPT?><br><br />
                </div>
            <?
            }
            if($MOBIL_NO != "" || $TEL_NO_DEPT != "" || $TEL_NO_HOME != "" || $FAX_NO_DEPT != "")
            {
            ?>
                <div style="float:right; width:420px; height:1px; border-top:1px #f1f1f1 solid;"></div>
                </div>
            <?
			}
            if($EMAIL != "" || $ICQ_NO != "" || $OICQ_NO != "" || $BIRTHDAY != "0000-00-00" || $NICK_NAME != "" || $PER_WEB != "")
            {
            ?>
                <div style="width:550px; margin-bottom:20px; float:left; ">
            <?
			}
            if($EMAIL != "")
            {
            ?>
                <div style="float: left;width: 500px; ">
                    <label class="lb"><?=_("电子邮件")?></label><a href="#" style="float:left; text-decoration:none;" onClick="send_email('<?=$EMAIL?>');"><?=$EMAIL?></a><br><br />
                </div>
            <?
            }
            if($PER_WEB != "")
            {
            ?>
                <div style="float: left;width: 500px;">
                    <label class="lb"><?=_("个人主页")?></label><?=$PER_WEB?><br><br />
                </div>
            <?
            }
			if($ICQ_NO != "")
            {
            ?>
                <div style="float: left;width: 500px;">
                    <label class="lb">MSN</label><?=$ICQ_NO?><br><br />
                </div>
            <?
            }
            if($OICQ_NO != "")
            {
            ?>
                <div style="float: left;width: 500px;">
                    <label class="lb">QQ</label><?=$OICQ_NO?><br><br />
                </div>
            <?
            }
            if($BIRTHDAY != "0000-00-00")
            {
            ?>
                <div style="float: left;width: 500px;">
                    <label class="lb"><?=_("生日日期")?></label><?=$BIRTHDAY?><br><br />
                </div>
            <?
            }
            if($NICK_NAME != "")
            {
            ?>
                <div style="float: left;width: 500px;">
                    <label class="lb"><?=_("昵称")?></label><?=$NICK_NAME?><br><br />
                </div>
            <?
            }
            if($EMAIL != "" || $ICQ_NO != "" || $OICQ_NO != "" || $BIRTHDAY != "0000-00-00" || $NICK_NAME != "" || $PER_WEB != "")
            {
            ?>
                <div style="float:right; width:420px; height:1px; border-top:1px #f1f1f1 solid;"></div>
                </div>
            <?
			}
            if($ADD_DEPT != "" || $POST_NO_DEPT != "" || $ADD_HOME != "")
            {
            ?>
                <div style="width:550px; margin-bottom:20px; float:left;">
            <?
			}
            if($ADD_DEPT != "")
            {
            ?>
                <div style="float: left;width: 500px;">
                    <label class="lb"><?=_("办公地址")?></label><?=$ADD_DEPT?><br><br />
                </div>
            <?
            }
            if($ADD_HOME != "")
            {
            ?>
                <div style="float: left;width: 500px;">
                    <label class="lb"><?=_("住宅地址")?></label><?=$ADD_HOME?><br><br />
                </div>
            <?
            }
            if($POST_NO_DEPT != "")
            {
            ?>
                <div style="float: left;width: 500px;">
                    <label class="lb"><?=_("邮政编码")?></label><?=$POST_NO_DEPT?><br><br />
                </div>
            <?
            }
            if($ADD_DEPT != "" || $POST_NO_DEPT != "" || $ADD_HOME != "")
            {
            ?>
                <div style="float:right; width:420px; height:1px; border-top:1px #f1f1f1 solid;"></div>
                </div>
            <?
			}
            if($NOTES != "")
            {
            ?>
                <div style="clear:both;float: left;width: 535px;">
                    <label class="lb"><?=_("备注")?></label><div style="width:385px; float:left; line-height:25px;"><?=$NOTES?></div>
                </div>
                
            <?
            }
            ?>
            </form>
        </div>
    </div>
</div>
<?
}
else
{
?>
    <div style="margin-left: 100px;margin-top: 100px;width: 50%;padding: 60px;border: 1px solid #DDD;font-weight: bold;color: #555;font-size: 20px;text-align: center;">
        <?=_("未找到相应记录！")?>
    </div>
<?
}
?>
</body>
</html>