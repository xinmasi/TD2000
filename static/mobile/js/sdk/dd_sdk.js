tMobileSDK = window.tMobileSDK || {};
tMobileSDK.ua = tMobileSDK.ua || {};
tMobileSDK.ua.ios = !!navigator.userAgent.match(/(iphone)|(ipad)/ig);
tMobileSDK.ready = function(func){
    dd.ready(func);
};
//钉钉的api有毒。。
tMobileSDK.runFx = function(func){
    tMobileSDK.ua.ios ? dd.ready(func) : func();
}
tMobileSDK.config = function(config){
    this._config = config;
    dd.config(config);
};
//隐藏右上角按钮
tMobileSDK.hideOptionMenu = function() {
    var me = this;
	setTimeout(function(){
		if(me._dingBtn){
			return;
		}
		dd.biz.navigation.setRight({
			show: false,//控制按钮显示， true 显示， false 隐藏， 默认true
			control: !true,//是否控制点击事件，true 控制，false 不控制， 默认false
			showIcon: !true,//是否显示icon，true 显示， false 不显示，默认true； 注：具体UI以客户端为准
			text: '',//控制显示文本，空字符串表示显示默认文本或icon
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

//显示右上角按钮
tMobileSDK.showOptionMenu = function() {
    //wx.showOptionMenu();
};

tMobileSDK.share = function(){
    dd.biz.util.share({
        type: 2,//分享类型，0:全部组件 默认； 1:只能分享到钉钉；2:不能分享，只有刷新按钮
        onSuccess : function() {
            /**/
        },
        onFail : function(err) {}
    })
};

tMobileSDK.dingBtn = function(opts){
    dd.biz.navigation.setRight({
        show: true,//控制按钮显示， true 显示， false 隐藏， 默认true
        control: true,//是否控制点击事件，true 控制，false 不控制， 默认false
        showIcon: true,//是否显示icon，true 显示， false 不显示，默认true； 注：具体UI以客户端为准
        text: 'DING',//控制显示文本，空字符串表示显示默认文本或icon
        onSuccess : function(result) {
            tMobileSDK.ding(opts);
        },
        onFail : function(err) {}
    });
	this._dingBtn = true;
};

tMobileSDK.ding = function(opts){
    var options = $.extend(true, {
        users : [],//用户列表，工号
        corpId: this._config.corpId || '', //企业id
        type: 1, //钉类型 1：image  2：link
        attachment: {
            images: [''],
        }, //附件信息
        text: '', //消息
        onSuccess : function() {},
        onFail : function() {}

    }, opts);

    dd.ready(function(){
        dd.biz.ding.post(options);
    });
};



/**
* 调出微信内图片预览scrollview
* @param array urls 图片url数组
* @param string current 当前图片url
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
 * 选择图片上传
 * @param obj 对应的参数
 */
tMobileSDK.chooseImage = function(options) {
    var defaultOpts = {
        multiple: true,
        count: 9,
        hasCallback: false,//是否返回不带dom结构的纯数据
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
            multiple: opts.multiple || true, //是否多选，默认false
            max: opts.count || 5, //最多可选个数
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
            multiple: opts.multiple || true, //是否多选，默认false
            max: opts.count || 9, //最多可选个数
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

                    //上传所有附件成功之后的操作
                    options.cb && options.cb();
                }
            }
        });
    }
    upload();
};

/*
 * 选择人员
 * @param obj 对应的参数
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
            multiple: true, //是否多选，默认false
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
 * 选择部门
 * @param obj 对应的参数
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
 * 上传附件
 * @param obj 对应的参数
 */
tMobileSDK.selectFile = function(options) {
    tMobileSDK.chooseImage(options);
}
/*
 * 增加授权地址
 * @param url 地址
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
