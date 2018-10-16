tMobileSDK = window.tMobileSDK || {};
tMobileSDK.ua = tMobileSDK.ua || {};
tMobileSDK.ua.ios = !!navigator.userAgent.match(/(iphone)|(ipad)/ig);
tMobileSDK.ready = function(func){
    dd.ready(func);
};
//������api�ж�����
tMobileSDK.runFx = function(func){
    tMobileSDK.ua.ios ? dd.ready(func) : func();
}
tMobileSDK.config = function(config){
    this._config = config;
    dd.config(config);
};
//�������Ͻǰ�ť
tMobileSDK.hideOptionMenu = function() {
    var me = this;
	setTimeout(function(){
		if(me._dingBtn){
			return;
		}
		dd.biz.navigation.setRight({
			show: false,//���ư�ť��ʾ�� true ��ʾ�� false ���أ� Ĭ��true
			control: !true,//�Ƿ���Ƶ���¼���true ���ƣ�false �����ƣ� Ĭ��false
			showIcon: !true,//�Ƿ���ʾicon��true ��ʾ�� false ����ʾ��Ĭ��true�� ע������UI�Կͻ���Ϊ׼
			text: '',//������ʾ�ı������ַ�����ʾ��ʾĬ���ı���icon
			onSuccess : function(result) {
				/*
				{}
				*/
				tMobileSDK.share();
			},
			onFail : function(err) {}
		});
	}, 100);

};

//��ʾ���Ͻǰ�ť
tMobileSDK.showOptionMenu = function() {
    //wx.showOptionMenu();
};

tMobileSDK.share = function(){
    dd.biz.util.share({
        type: 2,//�������ͣ�0:ȫ����� Ĭ�ϣ� 1:ֻ�ܷ�����������2:���ܷ�����ֻ��ˢ�°�ť
        onSuccess : function() {
            /**/
        },
        onFail : function(err) {}
    })
};

tMobileSDK.dingBtn = function(opts){
    dd.biz.navigation.setRight({
        show: true,//���ư�ť��ʾ�� true ��ʾ�� false ���أ� Ĭ��true
        control: true,//�Ƿ���Ƶ���¼���true ���ƣ�false �����ƣ� Ĭ��false
        showIcon: true,//�Ƿ���ʾicon��true ��ʾ�� false ����ʾ��Ĭ��true�� ע������UI�Կͻ���Ϊ׼
        text: 'DING',//������ʾ�ı������ַ�����ʾ��ʾĬ���ı���icon
        onSuccess : function(result) {
            tMobileSDK.ding(opts);
        },
        onFail : function(err) {}
    });
	this._dingBtn = true;
};

tMobileSDK.ding = function(opts){
    var options = $.extend(true, {
        users : [],//�û��б�������
        corpId: this._config.corpId || '', //��ҵid
        type: 1, //������ 1��image  2��link
        attachment: {
            images: [''],
        }, //������Ϣ
        text: '', //��Ϣ
        onSuccess : function() {},
        onFail : function() {}

    }, opts);

    dd.ready(function(){
        dd.biz.ding.post(options);
    });
};



/**
* ����΢����ͼƬԤ��scrollview
* @param array urls ͼƬurl����
* @param string current ��ǰͼƬurl
*/
tMobileSDK.previewImage = function() {
    var args = arguments;
    var current = '';
    var urls = [];
    if (args.length == 1)
    {
        args[0].find("img").each(function(){
            urls.push($(this).attr("src"));
        });

        if(urls.length > 0)
        {
            args[0].delegate("img", "click", function(){
                tMobileSDK.previewImage($(this).attr("src"), urls);
            });
        }
    } else {
        current = args[0];
        urls = args[1];
    }

    urls.forEach(function(v, k){
        urls[k] = tMobileSDK.addAuthCode(v);
    });
    current = tMobileSDK.addAuthCode(current);

    tMobileSDK.runFx(function(){
        dd.biz.util.previewImage({
            current: current,
            urls: urls,
            onSuccess: function(){},
            onFail: function(e){}
        });
    });

};

/*
 * ѡ��ͼƬ�ϴ�
 * @param obj ��Ӧ�Ĳ���
 */
tMobileSDK.chooseImage = function(options) {
    var defaultOpts = {
        multiple: true,
        count: 9,
        hasCallback: false,//�Ƿ񷵻ز���dom�ṹ�Ĵ�����
        sizeType: ['original', 'compressed'],
        sourceType: ['album', 'camera'],
        success: function (res) {
            this.cb && this.cb(res);
        },
        cb: function(res){
            var that = this;
            var html = '';
            var localIds = res;
            if (typeof localIds == 'object') {
                localIds.forEach(function(v, k){
                    html += that.imgTpl.replace('%s', v);
                });
            }
            that.imgList.html(html);
            pageConfig.imgLocalIds = localIds;
        }
    };
    var opts = $.extend(true, {}, defaultOpts, options);
    tMobileSDK.runFx(function(){
        dd.biz.util.uploadImage({
            multiple: opts.multiple || true, //�Ƿ��ѡ��Ĭ��false
            max: opts.count || 5, //����ѡ����
            onSuccess : function(result) {
                if(opts.hasCallback){
                    tMobileSDK.imgServerId = result;
                    var imgServerId = tMobileSDK.imgServerId.join(',');
                    $.ajax({
                        type: 'GET',
                	    url: '/pda/workflow/img_download.php',
                	    cache: false,
                	    data: {
        					'ATTACHMENTS': imgServerId,
        					"PLATFORM": "dd"
        				},
                	    success: function(ret)
                        {
                            ret = JSON.parse(ret);
                            var result = [];
                            $.each(ret, function(k, image){
                                result.push({
                                    id: image['attach_id'],
                                    name: image['attach_name'],
                                    url: image['attach_url']
                                });
                            })
                            opts.onSuccess && opts.onSuccess(result);
                            return;
                        }
                    });
                }else{
                    opts.cb && opts.cb(result);
                }
            },
            onFail : function() {},
            cb: function(res){
            }
        })
    });
};

tMobileSDK.uploadImage = function(opts) {
    opts.imgTpl = opts.imgTpl || '<li><div class="ui-grid-trisect-img"><img src="%s" /></div></li>';
    tMobileSDK.runFx(function(){
        dd.biz.util.uploadImage({
            multiple: opts.multiple || true, //�Ƿ��ѡ��Ĭ��false
            max: opts.count || 9, //����ѡ����
            onSuccess : function(result) {

                var html = '';
                tMobileSDK.uploadServerSuccess = true;
                tMobileSDK.imgServerId = result;
                if (typeof result == 'object') {
                    result.forEach(function(v, k){
                        html += opts.imgTpl.replace(/%s/g, v);
                    });
                }
                opts.imgList.append(html).show();
                opts.upSuccessCb && opts.upSuccessCb(result);
            },
            onFail : function(e) {
                opts.upErrorCb && opts.upErrorCb(e);
                tMobileSDK.uploadServerSuccess = false;
            }
        })
    })
};

tMobileSDK.saveToServer = function(options){
    var count = 0;
    function upload() {
        tMobileSDK.uploadImage({
            localId: pageConfig.imgLocalIds[count],
            cb: function(res){
                if (res.errMsg != 'uploadImage:ok') {
                    pageConfig.uploadWXserver = false;
                    return false;
                }
                pageConfig.imgServerId.push(res.serverId);
                count++;
                if(count < pageConfig.imgLocalIds.length) {
                    upload();
                }

                if(count == pageConfig.imgLocalIds.length) {
                    pageConfig.uploadServerSuccess = true;

                    //�ϴ����и����ɹ�֮��Ĳ���
                    options.cb && options.cb();
                }
            }
        });
    }
    upload();
};

/*
 * ѡ����Ա
 * @param obj ��Ӧ�Ĳ���
 */
tMobileSDK.selectUser = function(options) {
    var defaultOpts = {
        startWithDepartmentId: 0,
		multiple: true,
		users: [],
        onSuccess: function () {
        }
    };
    var opts = $.extend(true, {}, defaultOpts, options);
    tMobileSDK.runFx(function(){
        dd.biz.contact.choose({
            startWithDepartmentId: 0,
            multiple: true, //�Ƿ��ѡ��Ĭ��false
            users: opts.users || [],
            corpId: tMobileSDK._config.corpId || '',
            onSuccess : function(result) {
                var ret = [];
                $.each(result, function(k, user){
                    ret.push({
                       uid: user.emplId,
                       userName: user.name
                    });
                })
                opts.onSuccess && opts.onSuccess(ret);
            },
            onFail : function() {},
            cb: function(res){
            }
        })
    });
};

/*
 * ѡ����
 * @param obj ��Ӧ�Ĳ���
 */
tMobileSDK.selectDept = function(options) {
    var defaultOpts = {
        startWithDepartmentId: 0,
		selectedUsers: [],
        onSuccess: function (res) {
        }
    };
    var opts = $.extend(true, {}, defaultOpts, options);
    tMobileSDK.runFx(function(){
        dd.biz.contact.complexChoose({
            startWithDepartmentId: 0,
            selectedUsers: opts.depts || [],
            corpId: tMobileSDK._config.corpId || '',
            onSuccess : function(result) {
                //alert(JSON.stringify(result))
                var ret = [];
                $.each(result.departments,function(k, dept){
                    ret.push({
                        deptId: dept.id,
                        deptName: dept.name
                    })
                })
                opts.onSuccess && opts.onSuccess(ret);
            },
            onFail : function() {}
        })
    });
};

/*
 * �ϴ�����
 * @param obj ��Ӧ�Ĳ���
 */
tMobileSDK.selectFile = function(options) {
    tMobileSDK.chooseImage(options);
}
/*
 * ������Ȩ��ַ
 * @param url ��ַ
 */

tMobileSDK.addAuthCode = function(url){
    var state = WXS.WXState;
    var baseUrl = WXS.URI;
    if(url.match(/^\/inc\/attach.php/)){
        url = url + "&PHPSESSID="+WXS.P.split(';')[1];
    }
    if(url.match(/^\//)){
        url = window.location.protocol + "//" + window.location.host + url;
    }
    return url;
}