<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
//2013-04-11 ����������ѯ
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("�ǳ���ͷ��");
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
        //�����̨����
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

        //Ϊ.filePicker����uploader
        (function () {
            var pickBtns = $('.filePicker');
            pickBtns.each(function(index,item) {
                //console.log(index,item);
                makeUploader(item.id);
            });

        })();

        //uploader������
        function makeUploader(pick) {
            // ��ʼ��Web Uploader
            var uploader = WebUploader.create({
                // ѡ���ļ����Ƿ��Զ��ϴ���
                auto: true,
                // swf�ļ�·��
                swf:  '<?=MYOA_JS_SERVER?>/static/js/webuploader/Uploader.swf',
                // �ļ����շ���ˡ�
                server: '/module/upload/upload.php',
                // ѡ���ļ��İ�ť��
                // �ڲ����ݵ�ǰ���д�����������inputԪ�أ�Ҳ������flash.
                pick: '#'+ pick,
                // ֻ����ѡ��ͼƬ�ļ���
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,png',
                    mimeTypes: 'image/*'
                },
                //ѹ��ͼƬ�ϴ�
                compress: {
                    width: 420,
                    height: 420,
                    // ͼƬ������ֻ��typeΪ`image/jpeg`��ʱ�����Ч��
                    quality: 90,
                    // �Ƿ�����Ŵ������Ҫ����Сͼ��ʱ��ʧ�棬��ѡ��Ӧ������Ϊfalse.
                    allowMagnify: false,
                    // �Ƿ�����ü���
                    crop: false,
                    // �Ƿ���ͷ��meta��Ϣ��
                    preserveHeaders: true,
                    // �������ѹ�����ļ���С��ԭ��������ʹ��ԭ��ͼƬ
                    // �����Կ��ܻ�Ӱ��ͼƬ�Զ���������
                    noCompressIfLarger: false,
                    // ��λ�ֽڣ����ͼƬ��СС�ڴ�ֵ���������ѹ����
                    compressSize: 0
                }
            });

            //�ļ���ӵ�����֮ǰ
            uploader.on('beforeFileQueued',function(file,data) {
                //console.log(this.options);
            });

            // �ļ��ϴ��ɹ�����item��ӳɹ�class, ����ʽ����ϴ��ɹ���
            uploader.on( 'uploadSuccess', function(file,data) {
                //��ȡͼƬ��id��name
                cropData.id = data.id;
                cropData.name = data.name;

                //console.info(arguments,'uploadSuccess');

                //�����������ϴ�ͼƬ
                var _img = new Image();
                var shrunkW;//���ź���
                var shrunkH;//���ź�߶�
                _img.src = data.url;
                _img.id = "theImg";
                _img.onload = function() {

                    //��ȡ�ϴ�ͼƬ��ԭʼ�ߴ�
                    cropData.originW = _img.width;
                    cropData.originH = _img.height;

                    //��jpgͼƬ�ϴ�ǰ��ѹ�����˴��жϳ����������С
                    if(_img.width > 420 || _img.height > 420) {
                        if(_img.width > _img.height) {//��ͼ
                            shrunkW = 420;
                            shrunkH = parseInt(420*_img.height/_img.width);
                        } else {//��ͼ
                            shrunkH = 420;
                            shrunkW = parseInt(420*_img.width/_img.height);
                        }
                    } else {
                        shrunkW = _img.width;
                        shrunkH = _img.height;
                    }
                    cropData.compressW = shrunkW;//ѹ�����
                    //console.log(_img.width,_img.height);

                    //����ͼƬ��ʾʱ�Ŀ��
                    this.width = shrunkW;
                    this.height = shrunkH;
                    $(this).width(shrunkW);
                    $(this).height(shrunkH);

                    if(uploader.options.pick == '#imgPick' && file.ext === 'gif') {//�˵���Ƭ��gifͼƬ
                        $(uploader.options.pick+' + .upload-hints').text('��Ƭ��֧��gif��ʽ��');
                        $(uploader.options.pick+' + .upload-hints').show();
                        uploader.removeFile(file,true);//�Ӷ�����ɾ���ļ�
                        return;
                    } else if(uploader.options.pick == '#avatarPick' && file.ext === 'gif') {
                        if(_img.width>100 || _img.height>100) {//�˵�����ߴ��gifͼƬ
                            $(uploader.options.pick + ' + .upload-hints').text('gifͼƬ�ߴ�����������ϴ�');
                            $(uploader.options.pick + ' + .upload-hints').show();
                            uploader.removeFile(file,true);//�Ӷ�����ɾ���ļ�
                            return;
                        } else {//���Ϻ��ʵ�gifͼƬ
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
                            //�Ӷ�����ɾ���ļ�
                            uploader.removeFile(file,true);
                            return;
                        }
                    }

                    //���˵�С�ߴ��ͼƬ
                    if(uploader.options.pick == '#imgPick') {
                        if(_img.width<200 || _img.height<200) {
                            $(uploader.options.pick+' + .upload-hints').text('ͼƬ�ߴ��С���������ϴ�');
                            $(uploader.options.pick+' + .upload-hints').show();
                            uploader.removeFile(file,true);//�Ӷ�����ɾ���ļ�
                            return;
                        }
                    } else {
                        if(_img.width<100 || _img.height<100) {
                            $(uploader.options.pick+' + .upload-hints').text('ͼƬ�ߴ��С���������ϴ�');
                            $(uploader.options.pick+' + .upload-hints').show();
                            uploader.removeFile(file,true);//�Ӷ�����ɾ���ļ�
                            return;
                        }
                    }


                    $('.portrait-slide-bar img').attr('src',data.url);//���÷���ͼ���url
                    $(this).appendTo('.portrait-content');

                    //�����ͷ����ȡ����ͷ��Ԥ��
                    if(uploader.options.pick == '#avatarPick') {
                        $('.portrait-slide-bar').children().not($('.portrait-slide-bar').children().last()).hide();
                        cropData.geneAvatar = 'only';
                    } else if(uploader.options.pick == '#imgPick') {
                        $('.portrait-slide-bar').children().not($('.portrait-slide-bar').children().last()).show();
                        cropData.geneAvatar = true;
                    }
                    $('body.bodycolor table').hide();//����tbody
                    $('#new-portrait-wrapper').show(function() {//��ʾ�ü����
                        initCrop(data.url,shrunkW,shrunkH);//��ʼ���ü�����
                    });


                    uploader.removeFile(file,true);//�Ӷ�����ɾ���ļ�
                }
            });

            // �ļ��ϴ�ʧ�ܣ���ʾ�ϴ�����
            uploader.on( 'uploadError', function( file ) {
//                console.info('�ϴ�ʧ��');
            });
        }


        var myCropper;//ͷ��ü�������
        //��ʼ���ü�����
        function initCrop(url,imgW,imgH) {
            if(myCropper) {
                myCropper.setImage(url,function() { });
            }
            var coords;//ѡ��������

            //����ͷ��ؼ�
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

        //�ύ�ü�
        $('.btn-save').click(function() {
            $('#avatarSaving').show();//��ʾ���ڱ���ͷ��
            $('.upload-hints').hide();//ȡ����ʾ����ʾ
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

        //ȡ���ü�
        $('.btn-cancel, .btn-close').click(function() {
            $('body.bodycolor table').show();//��ʾtbody
            $('#new-portrait-wrapper').hide();//���زü����
            $('.upload-hints').hide();//ȡ����ʾ����ʾ

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

        //�ж��Ƿ�����ͷ��
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
 //============================ �������� =======================================
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
      <td class="center" colspan="4"><?=_("�ǳ���ͷ��")?></td>
    </tr>
    </thead>
    <tr>
      <td nowrap class="TableData" style="width: 210px;"><i class="iconfont">&#xe630;</i><?=_("�ǳƣ����������������ȣ���")?></td>
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
      <td valign="top" nowrap class="TableData"><i class="iconfont">&#xe636;</i><?=_("��Ƭ��")?></td>
      <td class="TableData">
        <img src="<?=$ATTACH_URL['view']?>" align="absmiddle">&nbsp;&nbsp;&nbsp;
        <input type="button" value="<?=_("ɾ��")?>" onClick="location='delete.php?TYPE=PHOTO'" class="SmallButton">
      </td>
    </tr>
<?
}
else
{
?>
    <tr>
      <td valign="top" nowrap class="TableData"><i class="iconfont">&#xe636;</i><?=_("�ϴ���Ƭ��")?></td>
      <td class="TableData">
        <!-- <input type="file" name="PHOTO" size="50" class="BigInput" title="<?=_("ѡ�񸽼��ļ�")?>" value=""><br> -->
        <div id="imgPick" class="filePicker">ѡ��ͼƬ</div>
        <div class="upload-hints">ͼƬ�ߴ��С���������ϴ�</div>
        <span><?=_("��Ƭ�ļ�Ҫ����jpg��jpeg��png��ʽ���ߴ粻�ܵ���200*200���ء�")?></span><br>
        <span><?=_("��Ƭ��Ҫ�����û���Ƭ����ʾ��")?></span>
      </td>
    </tr>
<?
}
if(strpos($_SESSION["LOGIN_AVATAR"], "."))
{
?>
    <tr>
      <td valign="top" nowrap class="TableData"><i class="iconfont">&#xe63f;</i><?=_("ͷ��")?></td>
      <td class="TableData">
        <img src="<?=avatar_path($_SESSION["LOGIN_AVATAR"])?>" align="absmiddle">&nbsp;&nbsp;&nbsp;
        <input type="button" value="<?=_("ɾ��")?>" onClick="location='delete.php'" class="SmallButton">
      </td>
    </tr>
<?
}
else
{
	if($AVATAR_UPLOAD==1){
?>
    <tr>
      <td valign="top" nowrap class="TableData"><i class="iconfont">&#xe63f;</i><?=_("�ϴ�ͷ��")?></td>
      <td class="TableData">
        <!-- <input type="file" name="AVATAR" size="50" class="BigInput" title="<?=_("ѡ�񸽼��ļ�")?>" value=""><br> -->
        <div id="avatarPick" class="filePicker">ѡ��ͼƬ</div>
        <div class="upload-hints">ͼƬ�ߴ��С���������ϴ�</div>
        <span><?=sprintf(_("ͷ���ļ�Ҫ����gif��jpg��jpeg��png��ʽ��gif��ʽ��ͼƬ�ߴ粻�ܸ���100*100���ء�"),$AVATAR_WIDTH,$AVATAR_HEIGHT)?></span>
        <br><span><?=_("ͷ����Ҫ�����ʼ��б�΢Ѷ�б�ȵ���ʾ��")?></span>
      </td>
    </tr>
<?
}}
?>
    <tr>
      <td nowrap class="TableData"><i class="iconfont">&#xe626;</i><?=_("������ǩ������")?></td>
      <td class="TableData">
        <textarea style="overflow:auto;word-break:break-all;word-wrap:break-word;" cols=80 name="BBS_SIGNATURE" rows=2 class="" wrap="off"><?=$BBS_SIGNATURE?></textarea>
      </td>
    </tr>
    <tr align="center" class="TableData" >
      <td colspan="2" nowrap style="text-align:center;background-color:#fff;">
        <input type="hidden" name="PHOTO_OLD" value="<?=$PHOTO?>">
        <input type="hidden" name="AVATAR_OLD" value="<?=$AVATAR?>">
        <input id="saveBtn" type="button" value="<?=_("�����޸�")?>" class="btn btn-primary" onClick="CheckForm();">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

<br>

<!-- lijun add this in 14/12/29 -->
<div id="new-portrait-wrapper">
    <!-- ͷ��������� -->
    <div class="portrait-header">
        <!-- ͷ�������ͷ�� -->
        <h3>�޸�ͼƬ</h3>
    </div>
    <div class="portrait-body">
        <!-- ͷ����������� -->
        <div class="portrait-main">
            <div class="portrait-content">
                <!--<img id="theImg" src="">-->
            </div>
            <div class="portrait-op-btns">
                <input class="btn btn-info btn-save" type="button" value="ȷ��">
                <input class="btn btn-cancel" type="button" value="ȡ��">
            </div>
        </div>
        <div class="portrait-slide-bar">
            <h3>Ԥ��</h3>
            <div>
                <div class="avatar-big-wrapper">
                    <img class="avatar-big" src="">
                </div>
                <p>��Ƭ200*200</p>
            </div>
            <div id="generateAvatar"><input type="checkbox" checked><span>Ĭ���滻ͷ��</span></div>
            <div>
                <div class="avatar-small-wrapper">
                    <img class="avatar-small" src="">
                </div>
                <p>ͷ��100*100</p>
            </div>
        </div>
    </div>

</div>
<div id="avatarSaving">���ڱ���ͷ��...</div>
</body>
</html>