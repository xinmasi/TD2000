<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("专家信息");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
function send_email(TO_ID,TO_NAME)
{
    var URL = "/general/email/new/?TO_ID="+TO_ID+"&TO_NAME="+TO_NAME;
    var myleft = (screen.availWidth-500)/2;
    window.open(URL, "send_email", "height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function send_sms(TO_ID,TO_NAME)
{
    var URL = "/general/status_bar/sms_back.php?TO_UID="+TO_ID+"&TO_NAME="+TO_NAME;
    var myleft = (screen.availWidth-500)/2;
    window.open(URL, "send_email", "height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
//传递连接
$(function()
{
    $(".edit_add_href").click(function()
    {
        var show_add_id = $(this).attr('info');
        var url="experts_info.php?USER_ID="+show_add_id;
        $('#iframe_edit').attr("src",url);
    })
})
</script>
<style type="text/css">
.experts_all_block{width:960px; margin: 0 auto;}
.experts_block,.experts_block_img{width: 480px; float: left; }
.experts_block{margin-top: 20px;}
.experts_block_content{float:left; font-size: 14px; line-height: 70px; margin-top: 15px;}
li{margin-top:7px;list-style:none;}
.experts_block_content_info{width: 480px; height: 68px; float: left; font-size: 13px; margin-top: 5px; line-height: 25px;}
.experts_block_content_info_left{float:left; width: 70px;}
.experts_block_content_info_right{float:left; width: 390px;}
</style>
<body class="bodycolor">
    <div class="experts_all_block">
<?
$query = "SELECT * from HR_STAFF_INFO,USER,DEPARTMENT where HR_STAFF_INFO.IS_EXPERTS='1' and  HR_STAFF_INFO.USER_ID=USER.USER_ID and HR_STAFF_INFO.DEPT_ID=DEPARTMENT.DEPT_ID";
$cursor= exequery(TD::conn(),$query);
if(mysql_num_rows($cursor)>0){
while($ROW=mysql_fetch_array($cursor))
{
    $UID_EXPERTS=$ROW["UID"];
    $USER_ID_EXPERTS=$ROW["USER_ID"];
    $USER_NAME_EXPERTS=$ROW["USER_NAME"];
    $DEPT_NAME_EXPERTS=$ROW["DEPT_NAME"];
    $PRESENT_POSITION=$ROW["PRESENT_POSITION"];
    $EXPERTS_INFO=$ROW["EXPERTS_INFO"];
    $SEX=$ROW["SEX"];
    $PHOTO=$ROW["PHOTO"];
    $PHOTO_NAME=$ROW["PHOTO_NAME"];
    //获取岗位名称
    if($PRESENT_POSITION !="")
    $PRESENT_POSITION=get_hrms_code_name($PRESENT_POSITION,"PRESENT_POSITION");
    //获取图片信息
    $AVATAR_PATH="";
    $AVATAR_FILE="";
    if($PHOTO!=""){
       $URL_ARRAY = attach_url_old('photo', $PHOTO);
       $AVATAR_PATH = $URL_ARRAY['view'];
       $AVATAR_FILE = attach_real_path('photo', $PHOTO);
    }else{
       if($PHOTO_NAME!=""){
          $URL_ARRAY = attach_url_old('hrms_pic', $PHOTO_NAME);
          $AVATAR_PATH = $URL_ARRAY['view'];
          $AVATAR_FILE = MYOA_ATTACH_PATH."hrms_pic/".$PHOTO_NAME;
       }
    }
    //判断图片是否存在
    if(!file_exists($AVATAR_FILE)){
      $AVATAR_PATH = MYOA_STATIC_SERVER."/static/images/avatar/".$SEX.".png";
      $AVATAR_FILE = MYOA_ROOT_PATH."static/images/avatar/".$SEX.".png";
   }

?>
        <div class="experts_block">
            <div class="experts_block_img">
                <span style="float:left;width:140px;height: 140px;"><a role="button" data-toggle="modal" href="#edit_add" info="<?=$USER_ID_EXPERTS?>" class="edit_add_href" title="查看个人信息"><img src="<?=$AVATAR_PATH?>" class="img-rounded img-polaroid" width="140" height="140" style="width:140px;height: 140px;"  /></a></span>
                <span class="experts_block_content">
                    <ul>
                        <li><?=_("姓名：").$USER_NAME_EXPERTS; ?></li>
                        <li><?=_("部门：").$DEPT_NAME_EXPERTS; ?></li>
                        <li><?=_("职称：").$PRESENT_POSITION; ?></li>
                        <li style=" width: 190px; height: 29px;">
                            <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/A1.png" style="margin-right:10px;cursor:pointer;" title="发送邮件" onClick="send_email('<?=$USER_ID_EXPERTS?>','<?=$USER_NAME_EXPERTS?>');"/>
                            <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/A3.png" style=" margin-right:10px;cursor:pointer;" title="发送短信" onClick="send_sms('<?=$UID_EXPERTS?>','<?=$USER_NAME_EXPERTS?>');"/>
                            <a href="#edit_add" role="button" data-toggle="modal"  info="<?=$USER_ID_EXPERTS?>" title="详情" class="btn btn-info btn-small edit_add_href" style=" color: white;cursor:pointer;">详情</a>
                        </li>
                    </ul>
                </span>
            </div>
            <div class="experts_block_content_info">
                <span class="experts_block_content_info_left">
                    <?=_("研究领域：")?>
                </span>
                <span class="experts_block_content_info_right">
                    <?
                    if(strlen($EXPERTS_INFO)>174)
                    {
                        echo substr($EXPERTS_INFO,0,174).'...';
                    }
                    else
                    {
                        echo $EXPERTS_INFO;
                    }
                    ?>
                </span>
            </div>
        </div>
<?
}
}else{
 Message(_("提示"),_("无专家信息"));
 
?>
<br />
<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="history.back();">
</div>
<?}?>
    </div>
    <!--编辑岗位信息-->
    <div id="edit_add" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 50%;margin-top: -226px;left: 50%; margin-left: -400px;width: 800px;">
        <div class="modal-body" style="max-height: 403px; height: 403px;padding: 0px;overflow: hidden;">
            <iframe width="100%" height="100%" id="iframe_edit" name="iframe_edit" frameborder="0" src="">
            </iframe>
        </div>
        <div class="modal-footer" style="padding-bottom: 10px;padding-top: 10px;text-align:center;">
            <button class="btn" data-dismiss="modal" aria-hidden="true" id="hide_edit"><?=_("关闭")?></button>
        </div>
    </div>
</body>
</html>