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
</script>
<style type="text/css">
.experts_all_block{width:750px; margin: 0 auto;}
.experts_block,.experts_block_img{width: 750px; float: left; }
.experts_block{margin-top: 20px;}
.experts_block_content{float:left; font-size: 14px; line-height: 70px;}
li{margin-top:7px;list-style:none;}
.experts_block_content_info{width: 750px; float: left; font-size: 14px; margin-top: 5px; line-height: 25px;}
.experts_block_content_info_left{float:left; width: 84px;}
.experts_block_content_info_right{float:left; width: 666px;}
</style>
<body class="bodycolor">
<?
$USER_ID_EXPERTS=$_GET["USER_ID"];
$query = "SELECT * from HR_STAFF_INFO,USER,DEPARTMENT where HR_STAFF_INFO.USER_ID='$USER_ID_EXPERTS' and HR_STAFF_INFO.IS_EXPERTS='1' and  HR_STAFF_INFO.USER_ID=USER.USER_ID and HR_STAFF_INFO.DEPT_ID=DEPARTMENT.DEPT_ID";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $UID_EXPERTS=$ROW["UID"];
    $USER_ID_EXPERTS=$ROW["USER_ID"];
    $USER_NAME_EXPERTS=$ROW["USER_NAME"];
    $DEPT_NAME_EXPERTS=$ROW["DEPT_NAME"];
    $PRESENT_POSITION=$ROW["PRESENT_POSITION"];
    $EXPERTS_INFO=$ROW["EXPERTS_INFO"];
    $STAFF_MOBILE_EXPERTS=$ROW["STAFF_MOBILE"];
    $STAFF_PHONE_EXPERTS=$ROW["STAFF_PHONE"];
    $STAFF_EMAIL_EXPERTS=$ROW["STAFF_EMAIL"];
    $STAFF_QQ_EXPERTS=$ROW["STAFF_QQ"];
    $RESEARCH_RESULTS_EXPERTS=$ROW["RESEARCH_RESULTS"];
    $PART_TIME_EXPERTS=$ROW["PART_TIME"];
    $RESUME_EXPERTS=$ROW["RESUME"];
    $SEX=$ROW["SEX"];
    $PHOTO=$ROW["PHOTO"];
    $PHOTO_NAME=$ROW["PHOTO_NAME"];
    //获取岗位名称
    if($PRESENT_POSITION !="")
    $PRESENT_POSITION=get_hrms_code_name($PRESENT_POSITION,"PRESENT_POSITION");
    //获取图片信息
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
    <div class="experts_all_block">
        <div class="experts_block">
            <div class="experts_block_img">
                <span style="float:left;width:160px; height: 160px;"><img width="160" height="160" style="width:160px; height: 160px;" src="<?=$AVATAR_PATH?>" class="img-rounded img-polaroid" /></span>
                <span class="experts_block_content">
                    <ul>
                        <li><?=_("姓名：").$USER_NAME_EXPERTS; ?></li>
                        <li><?=_("部门：").$DEPT_NAME_EXPERTS; ?></li>
                        <li><?=_("职称：").$PRESENT_POSITION; ?></li>
                        <li><?=_("手机：").$STAFF_MOBILE_EXPERTS; ?></li>
                        <li><?=_("电话：").$STAFF_PHONE_EXPERTS; ?></li>
<!--                        <li><?=_("邮箱：").$STAFF_EMAIL_EXPERTS; ?></li>
                        <li><?=_("QQ：").$STAFF_QQ_EXPERTS; ?></li>-->
                        <li>
                            <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/A1.png" style="margin-right:10px;cursor:pointer;" title="发送邮件" onClick="send_email('<?=$USER_ID_EXPERTS?>','<?=$USER_NAME_EXPERTS?>');"/>
                            <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/A3.png" style=" margin-right:10px;cursor:pointer;" title="发送短信" onClick="send_sms('<?=$UID_EXPERTS?>','<?=$USER_NAME_EXPERTS?>');"/>
                        </li>
                    </ul>
                </span>
            </div>
            <!--研究领域-->
            <div class="experts_block_content_info">
                <b><?=_("研究领域：")?></b>
                <?=$EXPERTS_INFO;?>
            </div>
            <!--成果介绍-->
            <div class="experts_block_content_info">
                <b><?=_("成果介绍：")?></b>
                <?=$RESEARCH_RESULTS_EXPERTS;?>
            </div>
            <!--兼职-->
            <div class="experts_block_content_info">
                <b><?=_("社会兼职：")?></b>
                <?=$PART_TIME_EXPERTS;?>
            </div>
            <div class="experts_block_content_info">
                <span class="experts_block_content_info_left">
                    <b><?=_("个人简历：")?></b>
                </span>
                <span class="experts_block_content_info_right">
                    <?=$RESUME_EXPERTS;?>
                </span>
            </div>
        </div>
    </div>
<?
}
?>
</body>
</html>