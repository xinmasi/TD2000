/**
 * User: Jinqn
 * Date: 14-04-08
 * Time: ����16:34
 * �ϴ�ͼƬ�Ի����߼�����,����tab: Զ��ͼƬ/�ϴ�ͼƬ/����ͼƬ/����ͼƬ
 */

(function () {

    var remoteImage,
        uploadImage;

    window.onload = function () {
        initTabs();
        initAlign();
        initButtons();
        initAttachField();
        
        //�ı�ͼƬ�Ի����С--by ����
        var parent = window.parent;
        var height = parent.document.documentElement.clientHeight;
        var dialog = parent.$EDITORUI[window.frameElement.id.replace( /_iframe$/, '' )];
        if(height < 415){
            document.getElementById("imgContainer").className="ministyle";
            parent.document.getElementById(dialog.id+"_content").style.height = "235px";
            parent.document.getElementById(dialog.id+"_body").style.height = "302px";
        }
    };

    //����
    function initAttachField(){
        var fieldId = editor.options['attachmentId'],
            fieldName = editor.options['attachmentName'];
        this.$fieldId = window.parent.document.getElementsByName(fieldId)[0];
        if(this.$fieldId){
            this.$fieldName = window.parent.document.getElementsByName(fieldName)[0];
            this.originalFieldId = this.$fieldId.value;
            this.originalFieldName = this.$fieldName.value; 
        }    
    }
    function setAttachField(list)
    {        
        var idStore = [], nameStore = [];
        $.each(list, function(k,v){
            idStore.push(v.title);
            nameStore.push(v.alt);
        });
        if(this.$fieldId){
            this.$fieldId.value = this.originalFieldId + idStore.join('');
            this.$fieldName.value = this.originalFieldName + nameStore.join('');
        }
    }

    /* ��ʼ��tab��ǩ */
    function initTabs() {
        var tabs = $G('tabhead').children;
        for (var i = 0; i < tabs.length; i++) {
            if(tabs[i].nodeType !==1 )
                continue;
            domUtils.on(tabs[i], "click", function (e) {
                var target = e.target || e.srcElement;
                setTabFocus(target.getAttribute('data-content-id'));
            });
        }

        var img = editor.selection.getRange().getClosedNode();
        if (img && img.tagName && img.tagName.toLowerCase() == 'img') {
            setTabFocus('remote');
        } else {
            setTabFocus('upload');
        }
    }

    /* ��ʼ��tabbody */
    function setTabFocus(id) {
        if(!id) return;
        var i, bodyId, tabs = $G('tabhead').children;
        for (i = 0; i < tabs.length; i++) {
            bodyId = tabs[i].getAttribute('data-content-id');
            if (bodyId == id) {
                domUtils.addClass(tabs[i], 'focus');
                domUtils.addClass($G(bodyId), 'focus');
            } else {
                domUtils.removeClasses(tabs[i], 'focus');
                domUtils.removeClasses($G(bodyId), 'focus');
            }
        }
        switch (id) {
            case 'remote':
                remoteImage = remoteImage || new RemoteImage();
                break;
            case 'upload':
                setAlign(editor.getOpt('imageInsertAlign'));
                uploadImage = uploadImage || new UploadImage('queueList');
                break;
        }
    }

    /* ��ʼ��onok�¼� */
    function initButtons() {

        dialog.onok = function () {
            var remote = false, list = [], id, tabs = $G('tabhead').children;
            for (var i = 0; i < tabs.length; i++) {
                if (domUtils.hasClass(tabs[i], 'focus')) {
                    id = tabs[i].getAttribute('data-content-id');
                    break;
                }
            }

            switch (id) {
                case 'remote':
                    list = remoteImage.getInsertList();
                    break;
                case 'upload':
                    list = uploadImage.getInsertList();
                    var count = uploadImage.getQueueCount();
                    if (count) {
                        $('.info', '#queueList').html('<span style="color:red;">' + '����2��δ�ϴ��ļ�'.replace(/[\d]/, count) + '</span>');
                        return false;
                    }
                    if(list) {
                        setAttachField(list);
                    }
                    break;
            }

            if(list) {
                editor.execCommand('insertimage', list);
                remote && editor.fireEvent("catchRemoteImage");
            }
        };
    }


    /* ��ʼ�����䷽ʽ�ĵ���¼� */
    function initAlign(){
        /* ���alignͼ�� */
        domUtils.on($G("alignIcon"), 'click', function(e){
            var target = e.target || e.srcElement;
            if(target.className && target.className.indexOf('-align') != -1) {
                setAlign(target.getAttribute('data-align'));
            }
        });
    }

    /* ���ö��뷽ʽ */
    function setAlign(align){
        align = align || 'none';
        var aligns = $G("alignIcon").children;
        for(i = 0; i < aligns.length; i++){
            if(aligns[i].getAttribute('data-align') == align) {
                domUtils.addClass(aligns[i], 'focus');
                $G("align").value = aligns[i].getAttribute('data-align');
            } else {
                domUtils.removeClasses(aligns[i], 'focus');
            }
        }
    }
    /* ��ȡ���뷽ʽ */
    function getAlign(){
        var align = $G("align").value || 'none';
        return align == 'none' ? '':align;
    }


    /* ����ͼƬ */
    function RemoteImage(target) {
        this.container = utils.isString(target) ? document.getElementById(target) : target;
        this.init();
    }
    RemoteImage.prototype = {
        init: function () {
            this.initContainer();
            this.initEvents();
        },
        initContainer: function () {
            this.dom = {
                'url': $G('url'),
                'width': $G('width'),
                'height': $G('height'),
                'border': $G('border'),
                'vhSpace': $G('vhSpace'),
                'title': $G('title'),
                'align': $G('align')
            };
            var img = editor.selection.getRange().getClosedNode();
            if (img) {
                this.setImage(img);
            }
        },
        initEvents: function () {
            var _this = this,
                locker = $G('lock');

            /* �ı�url */
            domUtils.on($G("url"), 'keyup', updatePreview);
            domUtils.on($G("border"), 'keyup', updatePreview);
            domUtils.on($G("title"), 'keyup', updatePreview);

            domUtils.on($G("width"), 'keyup', function(){
                updatePreview();
                if(locker.checked) {
                    var proportion =locker.getAttribute('data-proportion');
                    $G('height').value = Math.round(this.value / proportion);
                } else {
                    _this.updateLocker();
                }
            });
            domUtils.on($G("height"), 'keyup', function(){
                updatePreview();
                if(locker.checked) {
                    var proportion =locker.getAttribute('data-proportion');
                    $G('width').value = Math.round(this.value * proportion);
                } else {
                    _this.updateLocker();
                }
            });
            domUtils.on($G("lock"), 'change', function(){
                var proportion = parseInt($G("width").value) /parseInt($G("height").value);
                locker.setAttribute('data-proportion', proportion);
            });

            function updatePreview(){
                _this.setPreview();
            }
        },
        updateLocker: function(){
            var width = $G('width').value,
                height = $G('height').value,
                locker = $G('lock');
            if(width && height && width == parseInt(width) && height == parseInt(height)) {
                locker.disabled = false;
                locker.title = '';
            } else {
                locker.checked = false;
                locker.disabled = 'disabled';
                locker.title = lang.remoteLockError;
            }
        },
        setImage: function(img){
            /* ����������ͼƬ */
            if (!img.tagName || img.tagName.toLowerCase() != 'img' && !img.getAttribute("src") || !img.src) return;

            var wordImgFlag = img.getAttribute("word_img"),
                src = wordImgFlag ? wordImgFlag.replace("&amp;", "&") : (img.getAttribute('_src') || img.getAttribute("src", 2).replace("&amp;", "&")),
                align = editor.queryCommandValue("imageFloat");

            /* ��ֹonchange�¼�ѭ������ */
            if (src !== $G("url").value) $G("url").value = src;
            if(src) {
                /* ���ñ������� */
                $G("width").value = img.width || '';
                $G("height").value = img.height || '';
                $G("border").value = img.getAttribute("border") || '0';
                $G("vhSpace").value = img.getAttribute("vspace") || '0';
                $G("title").value = img.title || img.alt || '';
                setAlign(align);
                this.setPreview();
                this.updateLocker();
            }
        },
        getData: function(){
            var data = {};
            for(var k in this.dom){
                data[k] = this.dom[k].value;
            }
            return data;
        },
        setPreview: function(){
            var url = $G('url').value,
                ow = $G('width').value,
                oh = $G('height').value,
                border = $G('border').value,
                title = $G('title').value,
                preview = $G('preview'),
                width,
                height;

            width = ((!ow || !oh) ? preview.offsetWidth:Math.min(ow, preview.offsetWidth));
            width = width+(border*2) > preview.offsetWidth ? width:(preview.offsetWidth - (border*2));
            height = (!ow || !oh) ? '':width*oh/ow;

            if(url) {
                preview.innerHTML = '<img src="' + url + '" style="width:' + width + 'px; height=' + height + 'px; border=' + border + 'px solid #000" title="' + title + '" />';
            }
        },
        getInsertList: function () {
            var data = this.getData();
            if(data['url']) {
                return [{
                    src: data['url'],
                    _src: data['url'],
                    width: data['width'] || '',
                    height: data['height'] || '',
                    border: data['border'] || '',
                    floatStyle: data['align'] || '',
                    vspace: data['vhSpace'] || '',
                    title: data['title'] || '',
                    alt: data['title'] || '',
                    style: "width:" + data['width'] + "px;height:" + data['height'] + "px;"
                }];
            } else {
                return [];
            }
        }
    };



    /* �ϴ�ͼƬ */
    function UploadImage(target) {
        this.$wrap = target.constructor == String ? $('#' + target) : $(target);
        this.init();
    }
    UploadImage.prototype = {
        init: function () {
            this.imageList = [];
            this.initContainer();
            this.initUploader();
        },
        initContainer: function () {
            this.$queue = this.$wrap.find('.filelist');
        },
        
        /* ��ʼ������ */
        initUploader: function () {
            var _this = this,
                $ = jQuery,    // just in case. Make sure it's not an other libaray.
                $wrap = _this.$wrap,
            // ͼƬ����
                $queue = $wrap.find('.filelist'),
            // ״̬�����������ȺͿ��ư�ť
                $statusBar = $wrap.find('.statusBar'),
            // �ļ�����ѡ����Ϣ��
                $info = $statusBar.find('.info'),
            // �ϴ���ť
                $upload = $wrap.find('.uploadBtn'),
            // �ϴ���ť
                $filePickerBtn = $wrap.find('.filePickerBtn'),
            // �ϴ���ť
                $filePickerBlock = $wrap.find('.filePickerBlock'),
            // ûѡ���ļ�֮ǰ�����ݡ�
                $placeHolder = $wrap.find('.placeholder'),
            // ���������
                $progress = $statusBar.find('.progress').hide(),
            // ���ӵ��ļ�����
                fileCount = 0,
            // ���ӵ��ļ��ܴ�С
                fileSize = 0,
            // �Ż�retina, ��retina�����ֵ��2
                ratio = window.devicePixelRatio || 1,
            // ����ͼ��С
                thumbnailWidth = 113 * ratio,
                thumbnailHeight = 113 * ratio,
            // ������pedding, ready, uploading, confirm, done.
                state = '',
            // �����ļ��Ľ�����Ϣ��keyΪfile id
                percentages = {},
                supportTransition = (function () {
                    var s = document.createElement('p').style,
                        r = 'transition' in s ||
                            'WebkitTransition' in s ||
                            'MozTransition' in s ||
                            'msTransition' in s ||
                            'OTransition' in s;
                    s = null;
                    return r;
                })(),
            // WebUploaderʵ��
                uploader,
                actionUrl = "/module/upload/upload.php",//editor.getActionUrl(editor.getOpt('imageActionName')),
                acceptExtensions = (editor.getOpt('imageAllowFiles') || []).join('').replace(/\./g, ',').replace(/^[,]/, ''),
                imageMaxSize = editor.getOpt('imageMaxSize'),
                imageCompressBorder = editor.getOpt('imageCompressBorder');

            if (!WebUploader.Uploader.support()) {
                $('#filePickerReady').after($('<div>').html(lang.errorNotSupport)).hide();
                return;
            } else if (!editor.getOpt('imageActionName')) {
                $('#filePickerReady').after($('<div>').html(lang.errorLoadConfig)).hide();
                return;
            }

            uploader = _this.uploader = WebUploader.create({
                pick: {
                    id: '#filePickerReady',
                    label: lang.uploadSelectFile
                },
                accept: {
                    title: 'Images',
                    extensions: acceptExtensions,
                    mimeTypes: 'image/jpg,image/jpeg,image/png'
                },
                swf: '../../third-party/webuploader/Uploader.swf',
                server: actionUrl,
                fileVal: editor.getOpt('imageFieldName'),
                duplicate: true,
                fileSingleSizeLimit: imageMaxSize,    // Ĭ�� 2 M
                compress: editor.getOpt('imageCompressEnable') ? {
                    width: imageCompressBorder,
                    height: imageCompressBorder,
                    // ͼƬ������ֻ��typeΪ`image/jpeg`��ʱ�����Ч��
                    quality: 90,
                    // �Ƿ������Ŵ������Ҫ����Сͼ��ʱ��ʧ�棬��ѡ��Ӧ������Ϊfalse.
                    allowMagnify: false,
                    // �Ƿ������ü���
                    crop: false,
                    // �Ƿ���ͷ��meta��Ϣ��
                    preserveHeaders: true
                }:false
            });
            uploader.addButton({
                id: '#filePickerBlock'
            });
            uploader.addButton({
                id: '#filePickerBtn',
                label: lang.uploadAddFile
            });

            setState('pedding');

            // �����ļ����ӽ���ʱִ�У�����view�Ĵ���
            function addFile(file) {
                var $li = $('<li id="' + file.id + '">' +
                        '<p class="title">' + file.name + '</p>' +
                        '<p class="imgWrap"></p>' +
                        '<p class="progress"><span></span></p>' +
                        '</li>'),

                    $btns = $('<div class="file-panel">' +
                        '<span class="cancel">' + lang.uploadDelete + '</span>' +
                        '<span class="rotateRight">' + lang.uploadTurnRight + '</span>' +
                        '<span class="rotateLeft">' + lang.uploadTurnLeft + '</span></div>').appendTo($li),
                    $prgress = $li.find('p.progress span'),
                    $wrap = $li.find('p.imgWrap'),
                    $info = $('<p class="error"></p>').hide().appendTo($li),

                    showError = function (code) {
                        switch (code) {
                            case 'exceed_size':
                                text = lang.errorExceedSize;
                                break;
                            case 'interrupt':
                                text = lang.errorInterrupt;
                                break;
                            case 'http':
                                text = lang.errorHttp;
                                break;
                            case 'not_allow_type':
                                text = lang.errorFileType;
                                break;
                            default:
                                text = lang.errorUploadRetry;
                                break;
                        }
                        $info.text(text).show();
                    };

                if (file.getStatus() === 'invalid') {
                    showError(file.statusText);
                } else {
                    $wrap.text(lang.uploadPreview);
                    if (browser.ie && browser.version <= 7) {
                        $wrap.text(lang.uploadNoPreview);
                    } else {
                        uploader.makeThumb(file, function (error, src) {
                            if (error || !src) {
                                $wrap.text(lang.uploadNoPreview);
                            } else {
                                var $img = $('<img src="' + src + '">');
                                $wrap.empty().append($img);
                                $img.on('error', function () {
                                    $wrap.text(lang.uploadNoPreview);
                                });
                            }
                        }, thumbnailWidth, thumbnailHeight);
                    }
                    percentages[ file.id ] = [ file.size, 0 ];
                    file.rotation = 0;

                    /* ����ļ���ʽ */
                    if (!file.ext || acceptExtensions.indexOf(file.ext.toLowerCase()) == -1) {
                        showError('not_allow_type');
                        uploader.removeFile(file);
                    }
                }

                file.on('statuschange', function (cur, prev) {
                    if (prev === 'progress') {
                        $prgress.hide().width(0);
                    } else if (prev === 'queued') {
                        $li.off('mouseenter mouseleave');
                        $btns.remove();
                    }
                    // �ɹ�
                    if (cur === 'error' || cur === 'invalid') {
                        showError(file.statusText);
                        percentages[ file.id ][ 1 ] = 1;
                    } else if (cur === 'interrupt') {
                        showError('interrupt');
                    } else if (cur === 'queued') {
                        percentages[ file.id ][ 1 ] = 0;
                    } else if (cur === 'progress') {
                        $info.hide();
                        $prgress.css('display', 'block');
                    } else if (cur === 'complete') {
                    }

                    $li.removeClass('state-' + prev).addClass('state-' + cur);
                });

                $li.on('mouseenter', function () {
                    $btns.stop().animate({height: 30});
                });
                $li.on('mouseleave', function () {
                    $btns.stop().animate({height: 0});
                });

                $btns.on('click', 'span', function () {
                    var index = $(this).index(),
                        deg;

                    switch (index) {
                        case 0:
                            uploader.removeFile(file);
                            return;
                        case 1:
                            file.rotation += 90;
                            break;
                        case 2:
                            file.rotation -= 90;
                            break;
                    }

                    if (supportTransition) {
                        deg = 'rotate(' + file.rotation + 'deg)';
                        $wrap.css({
                            '-webkit-transform': deg,
                            '-mos-transform': deg,
                            '-o-transform': deg,
                            'transform': deg
                        });
                    } else {
                        $wrap.css('filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation=' + (~~((file.rotation / 90) % 4 + 4) % 4) + ')');
                    }

                });

                $li.insertBefore($filePickerBlock);
            }

            // ����view������
            function removeFile(file) {
                var $li = $('#' + file.id);
                delete percentages[ file.id ];
                updateTotalProgress();
                $li.off().find('.file-panel').off().end().remove();
            }

            function updateTotalProgress() {
                var loaded = 0,
                    total = 0,
                    spans = $progress.children(),
                    percent;

                $.each(percentages, function (k, v) {
                    total += v[ 0 ];
                    loaded += v[ 0 ] * v[ 1 ];
                });

                percent = total ? loaded / total : 0;

                spans.eq(0).text(Math.round(percent * 100) + '%');
                spans.eq(1).css('width', Math.round(percent * 100) + '%');
                updateStatus();
            }

            function setState(val, files) {

                if (val != state) {

                    var stats = uploader.getStats();

                    $upload.removeClass('state-' + state);
                    $upload.addClass('state-' + val);

                    switch (val) {

                        /* δѡ���ļ� */
                        case 'pedding':
                            $queue.addClass('element-invisible');
                            $statusBar.addClass('element-invisible');
                            $placeHolder.removeClass('element-invisible');
                            $progress.hide(); $info.hide();
                            uploader.refresh();
                            break;

                        /* ���Կ�ʼ�ϴ� */
                        case 'ready':
                            $placeHolder.addClass('element-invisible');
                            $queue.removeClass('element-invisible');
                            $statusBar.removeClass('element-invisible');
                            $progress.hide(); $info.show();
                            $upload.text(lang.uploadStart);
                            uploader.refresh();
                            break;

                        /* �ϴ��� */
                        case 'uploading':
                            $progress.show(); $info.hide();
                            $upload.text(lang.uploadPause);
                            break;

                        /* ��ͣ�ϴ� */
                        case 'paused':
                            $progress.show(); $info.hide();
                            $upload.text(lang.uploadContinue);
                            break;

                        case 'confirm':
                            $progress.show(); $info.hide();
                            $upload.text(lang.uploadStart);

                            stats = uploader.getStats();
                            if (stats.successNum && !stats.uploadFailNum) {
                                setState('finish');
                                return;
                            }
                            break;

                        case 'finish':
                            $progress.hide(); $info.show();
                            if (stats.uploadFailNum) {
                                $upload.text(lang.uploadRetry);
                            } else {
                                $upload.text(lang.uploadStart);
                            }
                            break;
                    }

                    state = val;
                    updateStatus();

                }

                if (!_this.getQueueCount()) {
                    $upload.addClass('disabled')
                } else {
                    $upload.removeClass('disabled')
                }

            }

            function updateStatus() {
                var text = '', stats;

                if (state === 'ready') {
                    text = lang.updateStatusReady.replace('_', fileCount).replace('_KB', WebUploader.formatSize(fileSize));
                } else if (state === 'confirm') {
                    stats = uploader.getStats();
                    if (stats.uploadFailNum) {
                        text = lang.updateStatusConfirm.replace('_', stats.successNum).replace('_', stats.successNum);
                    }
                } else {
                    stats = uploader.getStats();
                    text = lang.updateStatusFinish.replace('_', fileCount).
                        replace('_KB', WebUploader.formatSize(fileSize)).
                        replace('_', stats.successNum);

                    if (stats.uploadFailNum) {
                        text += lang.updateStatusError.replace('_', stats.uploadFailNum);
                    }
                }

                $info.html(text);
            }

            uploader.on('fileQueued', function (file) {
                fileCount++;
                fileSize += file.size;

                if (fileCount === 1) {
                    $placeHolder.addClass('element-invisible');
                    $statusBar.show();
                }

                addFile(file);
            });

            uploader.on('fileDequeued', function (file) {
                fileCount--;
                fileSize -= file.size;

                removeFile(file);
                updateTotalProgress();
            });

            uploader.on('filesQueued', function (file) {
                if (!uploader.isInProgress() && (state == 'pedding' || state == 'finish' || state == 'confirm' || state == 'ready')) {
                    setState('ready');
                }
                updateTotalProgress();
            });

            uploader.on('all', function (type, files) {
                switch (type) {
                    case 'uploadFinished':
                        setState('confirm', files);
                        break;
                    case 'startUpload':
                        /* ���Ӷ����GET���� */
                        var params = utils.serializeParam(editor.queryCommandValue('serverparam')) || '',
                            url = utils.formatUrl(actionUrl + (actionUrl.indexOf('?') == -1 ? '?':'&') + 'encode=utf-8&' + params);
                        uploader.option('server', url);
                        setState('uploading', files);
                        break;
                    case 'stopUpload':
                        setState('paused', files);
                        break;
                }
            });

            uploader.on('uploadBeforeSend', function (file, data, header) {
                //�������ͨ��data��������POST����
                header['X_Requested_With'] = 'XMLHttpRequest';
            });

            uploader.on('uploadProgress', function (file, percentage) {
                var $li = $('#' + file.id),
                    $percent = $li.find('.progress span');

                $percent.css('width', percentage * 100 + '%');
                percentages[ file.id ][ 1 ] = percentage;
                updateTotalProgress();
            });

            uploader.on('uploadSuccess', function (file, ret) {
                var $file = $('#' + file.id);
                try {
                    var responseText = (ret._raw || ret),
                        json = utils.str2json(responseText);
                    if (json.state == 'SUCCESS') {
                        _this.imageList.push(json);
                        $file.append('<span class="success"></span>');
                    } else {
                        $file.find('.error').text(json.state).show();
                    }
                } catch (e) {
                    $file.find('.error').text(lang.errorServerUpload).show();
                }
            });

            uploader.on('uploadError', function (file, code) {
            });
            uploader.on('error', function (code, file) {
                if (code == 'Q_TYPE_DENIED' || code == 'F_EXCEED_SIZE') {
                    addFile(file);
                }
            });
            uploader.on('uploadComplete', function (file, ret) {
            });

            $upload.on('click', function () {
                if ($(this).hasClass('disabled')) {
                    return false;
                }

                if (state === 'ready') {
                    uploader.upload();
                } else if (state === 'paused') {
                    uploader.upload();
                } else if (state === 'uploading') {
                    uploader.stop();
                }
            });

            $upload.addClass('state-' + state);
            updateTotalProgress();
        },
        getQueueCount: function () {
            var file, i, status, readyFile = 0, files = this.uploader.getFiles();
            for (i = 0; file = files[i++]; ) {
                status = file.getStatus();
                if (status == 'queued' || status == 'uploading' || status == 'progress') readyFile++;
            }
            return readyFile;
        },
        destroy: function () {
            this.$wrap.remove();
        },
        getInsertList: function () {
            var i, data, list = [],
                align = getAlign(),
                prefix = editor.getOpt('imageUrlPrefix');
            for (i = 0; i < this.imageList.length; i++) {
                data = this.imageList[i];
                list.push({
                    src: prefix + data.url,
                    _src: prefix + data.url,
                    title: data.id,
                    alt: data.name,
                    floatStyle: align
                });
            }
            return list;
        }
    };

})();