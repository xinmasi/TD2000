<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("昵称与头像");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/person_info/index.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jCrop/jquery.Jcrop.min.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/webuploader/webuploader.min.js"></script>
<!--<script src="<?=MYOA_JS_SERVER?>/module/ueditor/third-party/webuploader/webuploader.min.js"></script> -->
<script src="<?=MYOA_JS_SERVER?>/static/js/jCrop/jquery.Jcrop.min.js"></script>
<script>
    $(function() {
        //定义后台数据
        var cropData = {
            x: undefined,
            y: undefined,
            w: 0,
            h: 0,
            originW: 0,
            originH: 0,
            compressW: 0,
            id: null,
            name: '',
            geneAvatar: true,
            isGif: false
        };

        //为.filePicker生成uploader
        (function () {
            var pickBtns = $('.filePicker');
            pickBtns.each(function(index,item) {
                //console.log(index,item);
                makeUploader(item.id);
            });

        })();

        //uploader生成器
        function makeUploader(pick) {
            // 初始化Web Uploader
            var uploader = WebUploader.create({
                // 选完文件后，是否自动上传。
                auto: true,
                // swf文件路径
                swf:  '<?=MYOA_JS_SERVER?>/static/js/webuploader/Uploader.swf',
                // 文件接收服务端。
                server: '/module/upload/upload.php',
                // 选择文件的按钮。
                // 内部根据当前运行创建，可能是input元素，也可能是flash.
                pick: '#'+ pick,
                // 只允许选择图片文件。
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,png',
                    mimeTypes: 'image/*'
                },
                //压缩图片上传
                compress: {
                    width: 420,
                    height: 420,
                    // 图片质量，只有type为`image/jpeg`的时候才有效。
                    quality: 90,
                    // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
                    allowMagnify: false,
                    // 是否允许裁剪。
                    crop: false,
                    // 是否保留头部meta信息。
                    preserveHeaders: true,
                    // 如果发现压缩后文件大小比原来还大，则使用原来图片
                    // 此属性可能会影响图片自动纠正功能
                    noCompressIfLarger: false,
                    // 单位字节，如果图片大小小于此值，不会采用压缩。
                    compressSize: 0
                }
            });

            //文件添加到队列之前
            uploader.on('beforeFileQueued',function(file,data) {
                //console.log(this.options);
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on( 'uploadSuccess', function(file,data) {
                //获取图片的id和name
                cropData.id = data.id;
                cropData.name = data.name;

                //console.info(arguments,'uploadSuccess');

                //按比例缩放上传图片
                var _img = new Image();
                var shrunkW;//缩放后宽度
                var shrunkH;//缩放后高度
                _img.src = data.url;
                _img.id = "theImg";
                _img.onload = function() {

                    //获取上传图片的原始尺寸
                    cropData.originW = _img.width;
                    cropData.originH = _img.height;

                    //非jpg图片上传前不压缩，此处判断长宽，并重设大小
                    if(_img.width > 420 || _img.height > 420) {
                        if(_img.width > _img.height) {//宽图
                            shrunkW = 420;
                            shrunkH = parseInt(420*_img.height/_img.width);
                        } else {//长图
                            shrunkH = 420;
                            shrunkW = parseInt(420*_img.width/_img.height);
                        }
                    } else {
                        shrunkW = _img.width;
                        shrunkH = _img.height;
                    }
                    cropData.compressW = shrunkW;//压缩后宽
                    //console.log(_img.width,_img.height);

                    //设置图片显示时的宽高
                    this.width = shrunkW;
                    this.height = shrunkH;
                    $(this).width(shrunkW);
                    $(this).height(shrunkH);

                    if(uploader.options.pick == '#imgPick' && file.ext === 'gif') {//滤掉照片的gif图片
                        $(uploader.options.pick+' + .upload-hints').text('照片不支持gif格式！');
                        $(uploader.options.pick+' + .upload-hints').show();
                        uploader.removeFile(file,true);//从队列中删除文件
                        return;
                    } else if(uploader.options.pick == '#avatarPick' && file.ext === 'gif') {
                        if(_img.width>100 || _img.height>100) {//滤掉过大尺寸的gif图片
                            $(uploader.options.pick + ' + .upload-hints').text('gif图片尺寸过大，请重新上传');
                            $(uploader.options.pick + ' + .upload-hints').show();
                            uploader.removeFile(file,true);//从队列中删除文件
                            return;
                        } else {//存上合适的gif图片
                            cropData.isGif = true;
                            cropData.geneAvatar = 'only';
                            $('#avatarSaving').show();
                            $.ajax({
                                type: "GET",
                                url: "avatar.upload.php",
                                data: cropData,
                                dataType: "text",
                                success: function(data) {
                                    if(data == "ok") {
                                        $('#saveBtn').trigger('click');
                                    } else {
                                        console.error(data);
                                    }
                                },
                                error: function(e) {
                                    //console.log(e);
                                }
                            });
                            //从队列中删除文件
                            uploader.removeFile(file,true);
                            return;
                        }
                    }

                    //过滤掉小尺寸的图片
                    if(uploader.options.pick == '#imgPick') {
                        if(_img.width<200 || _img.height<200) {
                            $(uploader.options.pick+' + .upload-hints').text('图片尺寸过小，请重新上传');
                            $(uploader.options.pick+' + .upload-hints').show();
                            uploader.removeFile(file,true);//从队列中删除文件
                            return;
                        }
                    } else {
                        if(_img.width<100 || _img.height<100) {
                            $(uploader.options.pick+' + .upload-hints').text('图片尺寸过小，请重新上传');
                            $(uploader.options.pick+' + .upload-hints').show();
                            uploader.removeFile(file,true);//从队列中删除文件
                            return;
                        }
                    }


                    $('.portrait-slide-bar img').attr('src',data.url);//设置返回图像的url
                    $(this).appendTo('.portrait-content');

                    //如果是头像，则取消大头像预览
                    if(uploader.options.pick == '#avatarPick') {
                        $('.portrait-slide-bar').children().not($('.portrait-slide-bar').children().last()).hide();
                        cropData.geneAvatar = 'only';
                    } else if(uploader.options.pick == '#imgPick') {
                        $('.portrait-slide-bar').children().not($('.portrait-slide-bar').children().last()).show();
                        cropData.geneAvatar = true;
                    }
                    $('body.bodycolor table').hide();//隐藏tbody
                    $('#new-portrait-wrapper').show(function() {//显示裁剪面板
                        initCrop(data.url,shrunkW,shrunkH);//初始化裁剪工具
                    });


                    uploader.removeFile(file,true);//从队列中删除文件
                }
            });

            // 文件上传失败，显示上传出错。
            uploader.on( 'uploadError', function( file ) {
//                console.info('上传失败');
            });
        }


        var myCropper;//头像裁剪对象本身
        //初始化裁剪工具
        function initCrop(url,imgW,imgH) {
            if(myCropper) {
                myCropper.setImage(url,function() { });
            }
            var coords;//选定的坐标

            //生成头像控件
            $('#theImg').Jcrop({
                onSelect: showCoords,
                onChange: showPreview,
                bgColor: 'black',
                bgOpacity:.5,
                addClass: 'jcrop-normal',
                aspectRatio: 1,
                minSize: [100,100],
                setSelect: [0,0,100,100]
            },function() {
                myCropper = this;
                myCropper.setOptions({ bgFade: true });
                myCropper.ui.selection.addClass('jcrop-selection');

            });

            function showCoords(c)
            {
                // variables can be accessed here as
                cropData.x = c.x;
                cropData.y = c.y;
                cropData.w = c.w;
                cropData.h = c.h;
            }

            function showPreview(coords)
            {
                var rx1 = 180 / coords.w;
                var ry1 = 180 / coords.h;
                var rx2 = 90 / coords.w;
                var ry2 = 90 / coords.h;

                $('.avatar-big').css({
                    width: Math.round(rx1 * imgW) + 'px',
                    height: Math.round(ry1 * imgH) + 'px',
                    marginLeft: '-' + Math.round(rx1 * coords.x) + 'px',
                    marginTop: '-' + Math.round(ry1 * coords.y) + 'px'
                });
                $('.avatar-small').css({
                    width: Math.round(rx2 * imgW) + 'px',
                    height: Math.round(ry2 * imgH) + 'px',
                    marginLeft: '-' + Math.round(rx2 * coords.x) + 'px',
                    marginTop: '-' + Math.round(ry2 * coords.y) + 'px'
                });
            }
        }

        //提交裁剪
        $('.btn-save').click(function() {
            $('#avatarSaving').show();//提示正在保存头像
            $('.upload-hints').hide();//取消提示条显示
            //console.info(cropData);
            $.ajax({
                type: "GET",
                url: "avatar.upload.php",
                data: cropData,
                dataType: "text",
                success: function(data) {
                    if(data == "ok") {
                        $('#saveBtn').trigger('click');
                    } else {
                        console.error(data);
                    }
                },
                error: function(e) {
                    //console.log(e);
                }
            });
        });

        //取消裁剪
        $('.btn-cancel, .btn-close').click(function() {
            $('body.bodycolor table').show();//显示tbody
            $('#new-portrait-wrapper').hide();//隐藏裁剪面板
            $('.upload-hints').hide();//取消提示条显示

            $.ajax({
                type: "GET",
                url: "avatar.upload.php",
                data: {
                    action: 'delete',
                    id: cropData.id,
                    name: cropData.name
                },
                dataType: "text",
                success: function(data) {
                    myCropper && myCropper.destroy();
                    $('#theImg').remove();
                },
                error: function(e) {
                    //console.error(e);
                }
            });
        });

        //判断是否生成头像
        $('#generateAvatar input').click(function() {
            var isChecked = $(this).attr('checked');
            if(isChecked) {
                $(this).attr('checked',false);
                cropData.geneAvatar = false;
            } else {
                $(this).attr('checked',true);
                cropData.geneAvatar = true;
            }
        });

    })
</script>
<script Language="JavaScript">
function CheckForm()
{
  form1.submit();
}
</script>


<body class="bodycolor" onLoad="document.form1.NICK_NAME.focus();">

<?
 //============================ 个性设置 =======================================
 $query = "SELECT USER.AVATAR as AVATAR,USER.PHOTO as PHOTO,USER_EXT.NICK_NAME as NICK_NAME,USER_EXT.BBS_SIGNATURE as BBS_SIGNATURE from USER,USER_EXT where USER.UID=USER_EXT.UID and USER.UID='".$_SESSION["LOGIN_UID"]."'";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);

 if($ROW=mysql_fetch_array($cursor))
 {
    $NICK_NAME=$ROW["NICK_NAME"];
    $AVATAR=$ROW["AVATAR"];
    $PHOTO=$ROW["PHOTO"];
    $BBS_SIGNATURE=$ROW["BBS_SIGNATURE"];
 }

 $SYS_INTERFACE = TD::get_cache("SYS_INTERFACE");
 $AVATAR_UPLOAD=$SYS_INTERFACE["AVATAR_UPLOAD"];
 $AVATAR_WIDTH=$SYS_INTERFACE["AVATAR_WIDTH"];
 $AVATAR_HEIGHT=$SYS_INTERFACE["AVATAR_HEIGHT"];
?>
  <form enctype="multipart/form-data" action="update.php"  method="post" name="form1">
  <table class="table table-bordered" width="650">  
    <thead>
    <tr>
      <td class="center" colspan="4"><?=_("昵称与头像")?></td>
    </tr>
    </thead>
    <tr>
      <td nowrap class="TableData" style="width: 210px;"><i class="iconfont">&#xe630;</i><?=_("昵称（用于讨论区交流等）：")?></td>
      <td class="TableData">
        <input type="text" name="NICK_NAME" size="25" maxlength="25" class="" value="<?=$NICK_NAME?>">
      </td>
    </tr>
<?

if($PHOTO != "")
{
   $ATTACH_URL = attach_url_old('photo', $PHOTO);
?>
    <tr>
      <td valign="top" nowrap class="TableData"><i class="iconfont">&#xe636;</i><?=_("照片：")?></td>
      <td class="TableData">
        <img src="<?=$ATTACH_URL['view']?>" align="absmiddle">&nbsp;&nbsp;&nbsp;
        <input type="button" value="<?=_("删除")?>" onClick="location='delete.php?TYPE=PHOTO'" class="SmallButton">
      </td>
    </tr>
<?
}
else
{
?>
    <tr>
      <td valign="top" nowrap class="TableData"><i class="iconfont">&#xe636;</i><?=_("上传照片：")?></td>
      <td class="TableData">
        <!-- <input type="file" name="PHOTO" size="50" class="BigInput" title="<?=_("选择附件文件")?>" value=""><br> -->
        <div id="imgPick" class="filePicker">选择图片</div>
        <div class="upload-hints">图片尺寸过小，请重新上传</div>
        <span><?=_("照片文件要求是jpg、jpeg、png格式，尺寸不能低于200*200像素。")?></span><br>
        <span><?=_("照片主要用于用户名片的显示。")?></span>
      </td>
    </tr>
<?
}
if(strpos($_SESSION["LOGIN_AVATAR"], "."))
{
?>
    <tr>
      <td valign="top" nowrap class="TableData"><i class="iconfont">&#xe63f;</i><?=_("头像：")?></td>
      <td class="TableData">
        <img src="<?=avatar_path($_SESSION["LOGIN_AVATAR"])?>" align="absmiddle">&nbsp;&nbsp;&nbsp;
        <input type="button" value="<?=_("删除")?>" onClick="location='delete.php'" class="SmallButton">
      </td>
    </tr>
<?
}
else
{
	if($AVATAR_UPLOAD==1){
?>
    <tr>
      <td valign="top" nowrap class="TableData"><i class="iconfont">&#xe63f;</i><?=_("上传头像：")?></td>
      <td class="TableData">
        <!-- <input type="file" name="AVATAR" size="50" class="BigInput" title="<?=_("选择附件文件")?>" value=""><br> -->
        <div id="avatarPick" class="filePicker">选择图片</div>
        <div class="upload-hints">图片尺寸过小，请重新上传</div>
        <span><?=sprintf(_("头像文件要求是gif、jpg、jpeg、png格式，gif格式下图片尺寸不能高于100*100像素。"),$AVATAR_WIDTH,$AVATAR_HEIGHT)?></span>
        <br><span><?=_("头像主要用于邮件列表、微讯列表等的显示。")?></span>
      </td>
    </tr>
<?
}}
?>
    <tr>
      <td nowrap class="TableData"><i class="iconfont">&#xe626;</i><?=_("讨论区签名档：")?></td>
      <td class="TableData">
        <textarea style="overflow:auto;word-break:break-all;word-wrap:break-word;" cols=80 name="BBS_SIGNATURE" rows=2 class="" wrap="off"><?=$BBS_SIGNATURE?></textarea>
      </td>
    </tr>
    <tr align="center" class="TableData" >
      <td colspan="2" nowrap style="text-align:center;background-color:#fff;">
        <input type="hidden" name="PHOTO_OLD" value="<?=$PHOTO?>">
        <input type="hidden" name="AVATAR_OLD" value="<?=$AVATAR?>">
        <input id="saveBtn" type="button" value="<?=_("保存修改")?>" class="btn btn-primary" onClick="CheckForm();">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

<br>

<!-- lijun add this in 14/12/29 -->
<div id="new-portrait-wrapper">
    <!-- 头像组件容器 -->
    <div class="portrait-header">
        <!-- 头像组件的头部 -->
        <h3>修改图片</h3>
    </div>
    <div class="portrait-body">
        <!-- 头像组件的主体 -->
        <div class="portrait-main">
            <div class="portrait-content">
                <!--<img id="theImg" src="">-->
            </div>
            <div class="portrait-op-btns">
                <input class="btn btn-info btn-save" type="button" value="确定">
                <input class="btn btn-cancel" type="button" value="取消">
            </div>
        </div>
        <div class="portrait-slide-bar">
            <h3>预览</h3>
            <div>
                <div class="avatar-big-wrapper">
                    <img class="avatar-big" src="">
                </div>
                <p>照片200*200</p>
            </div>
            <div id="generateAvatar"><input type="checkbox" checked><span>默认替换头像</span></div>
            <div>
                <div class="avatar-small-wrapper">
                    <img class="avatar-small" src="">
                </div>
                <p>头像100*100</p>
            </div>
        </div>
    </div>

</div>
<div id="avatarSaving">正在保存头像...</div>
</body>
</html>