tMobileSDK = window.tMobileSDK || {};

tMobileSDK.ready = function(func){
    wx.ready(func);

};

//�������Ͻǰ�ť
tMobileSDK.hideOptionMenu = function() {
    wx.hideOptionMenu();
};

//��ʾ���Ͻǰ�ť
tMobileSDK.showOptionMenu = function() {
    wx.showOptionMenu();
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

    wx.previewImage({
        current: current,
        urls: urls
    });
};

tMobileSDK.previewFile = function(opts) {
	var url = location.origin+opts.url
	wx.previewFile({
		url: url || '', // ��ҪԤ���ļ��ĵ�ַ(�������ʹ�����·��)
		name: opts.name || '', // ��ҪԤ���ļ����ļ���(����Ļ�ȡurl����󲿷�)
		size: opts.size // ��ҪԤ���ļ����ֽڴ�С(����)
	});
}

/*
 * ѡ��ͼƬ�ϴ�
 * @param obj ��Ӧ�Ĳ���
 */
tMobileSDK.chooseImage = function(options) {
    var sdk = this;
    var defaultOpts = {
        multiple: true,
        count: 9,
        sizeType: ['original', 'compressed'],
        sourceType: ['album', 'camera'],
        imgTpl: '<li><div class="ui-grid-trisect-img"><img src="%s" /></div></li>',
        success: function (res) {
            if(options.hasCallback == true){
                sdk.uploadServerSuccess = false;
                sdk.imgLocalIds = [];
                var localIds = res.localIds;
                sdk.imgLocalIds = localIds;
                sdk.imgServerId = [];
                this.chooseCb && this.chooseCb();
            }else{
                var that = this;
                var html = '';
                // ���´򿪵�ʱ���滻֮ǰ��ѡ��
                sdk.uploadServerSuccess = false;
                sdk.imgLocalIds = [];

                var localIds = res.localIds;
                if (typeof localIds == 'object') {
                    localIds.forEach(function(v, k){
                        html += that.imgTpl.replace(/%s/g, v);
                    });
                }
                that.imgList.append(html).show();
                sdk.imgLocalIds = localIds;
                sdk.imgServerId = [];
                this.chooseCb && this.chooseCb();
            }
        },
        chooseCb : function(){
            var that = this;
            that.upSuccessCb && that.upSuccessCb();
        }
    };
    var opts = $.extend(true, {}, defaultOpts, options);
    wx.chooseImage(opts);
};


tMobileSDK.uploadImage = function(options) {
    var defaultOpts = {
        multiple: true,
        chooseCb : function (){
            var that = this;
            tMobileSDK.saveToServer({
                qyappName : that.qyappName,
                upErrorCb : that.upErrorCb,
                upSuccessCb : that.upSuccessCb
            })
        },
        upErrorCb : function (){},
        upSuccessCb : function (){}
    };
    var opts = $.extend(true, {}, defaultOpts, options);
    this.chooseImage(opts);
};

tMobileSDK.saveToServer = function(options){
    var sdk = this;
    var count = 0;
    function upload() {
        wx.uploadImage({
            localId: sdk.imgLocalIds[count],
            isShowProgressTips: 1,
            success: function(res){
                if (res.errMsg != 'uploadImage:ok') {
                    sdk.uploadServerSuccess = false;
                    options.upErrorCb && options.upErrorCb();
                    return false;
                }

                sdk.imgServerId.push(res.serverId);

                count++;

                if(count < sdk.imgLocalIds.length) {
                    upload();
                }
                if(count == sdk.imgLocalIds.length) {
                    sdk.uploadServerSuccess = true;
                    //����΢���ϴ���ץȡ��oa�������Ĺ���
                    var imgServerId = sdk.imgServerId.join(',');
                    $.ajax({
                        type: 'GET',
                	    url: '/pda/workflow/img_download.php',
                	    cache: false,
                	    data: {
        					'ATTACHMENTS': imgServerId,
        					"PLATFORM": "qywx",
                            'appName': options.qyappName
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
                            options.upSuccessCb && options.upSuccessCb(result);
                            return;
                        }
                    });
                    //return;
                }
            }
        });
    }
    upload();
};

/*
 * �ϴ�����
 * @param obj ��Ӧ�Ĳ���
 */
tMobileSDK.selectFile = function(options) {
    tMobileSDK.uploadImage(options);
}

/*
ѡ�� opts { users:[], onSuccess: function(){} }
*/
tMobileSDK.selectUser = function(opts) {
    if(window.userselect != undefined || window.userselect != null){
        userselect = null;
    }
    window.userselect = {
        params: {
            searchUserUrl: '/mobile/inc/get_contactlist.php',
            $el: null,
            tpl: {
                'container': '<section class="ui-container userselect-ui-container deptselect-ui-container"> <footer class="ui-footer ui-footer-btn"> <div class="ui-footer ui-footer-stable ui-btn-group ui-border-t"> <button node-type="confirm" class="ui-btn-lg ui-btn-primary">ȷ��</button> <button node-type="cancel" class="ui-btn-lg">ȡ��</button> </div> </footer> <section class="ui-container"> <div class="ui-searchbar-wrap ui-border-b focus"> <div class="ui-searchbar ui-border-radius"> <i class="ui-icon-search"></i> <div class="ui-searchbar-text">����</div> <div class="ui-searchbar-input"> <input value="" type="text" placeholder="����" autocapitalize="off"> </div></div> <button type="button" class="ui-btn ui-btn-s search-confirm">����</button><button type="button" class="ui-btn ui-btn-s search-close">ȡ��</button></div><ul class="ui-list ui-border-tb"></ul> </section></section>',
                'user': '<li class="ui-border-t" data-user-id="<%=user_id%>" data-user-name="<%user_name%>"><label class="ui-checkbox"><input type="checkbox"><p><%=user_name%></p></label></li>'
            },
            allusers: []
        },
        init: function(options){
            var self = this;
            this.config = {
                users: [],
                wrapper: 'body',//��װ����
                confirmCallback: function(){},
                cancelCallback: function(){}
            };
            this.config = $.extend(true,{}, this.config, options);
            this.initDom();
            this.initUsers();
            this.bindEvent();
        },
        initDom: function(){
            $(this.config.wrapper).append(gmu.$.tpl(this.params.tpl.container,{}));
            this.$el = $('.userselect-ui-container');
        },
        initUsers: function(){
            var self = this;
            var opts = {
                A:'get_ALLUSER',
                P : P,
                KWORD : ''
            };
            $.getJSON(this.params.searchUserUrl, opts, function(data){
                self.params.allusers = data;
                self.render();
            });
        },
        render: function(){
            var self = this;
            var allusers = this.params.allusers;
            var users = this.config.users;
            var html = '';
            $.each(allusers, function(k, user){
                html += '<li class="ui-border-t" data-user-id="'+user.user_id+'" data-user-name="'+user.user_name+'"><label class="ui-checkbox"><input type="checkbox"><p>'+user.user_name+'</p></label></li>';
            })
            this.$el.find('.ui-list').html(html);
            //ѡ����ѡ
            this.config.users.forEach(function(user_id){
                $('[data-user-id="'+ user_id +'"]').find('input').prop('checked',true);
            });
        },
        renderSearch: function(keyword){
            this.$el.find('li').removeClass('hide');
            var hides = $.map(this.params.allusers,function(user){
                if(user.user_name.indexOf(keyword) == -1){
                    return user.user_id;
                }
            });
            $.each(hides, function(k, user_id){
                $('[data-user-id="'+user_id+'"]').addClass('hide');
            })
        },
        bindEvent: function(){
            var self = this;
            this.$el.delegate('input:checkbox','change', function(){
                var user_id = $(this).parents('li').attr('data-user-id');
                if($(this).prop('checked')){
                    self.config.users.push(user_id);
                }else{
                    self.config.users.splice($.inArray(user_id,self.config.users),1);
                }
            })
            this.$el.delegate('[node-type="confirm"]','touchstart', function(){
				$('.userselect-ui-container input:checked').each(function(){
					var $me = $(this)
					var uid = $me.parents('li.ui-border-t').attr('data-user-id')
					if(self.config.users.indexOf(uid) != -1){
						
					}else{
						self.config.users.push(uid);
					}
				})
                var ret = [];
                $.each(self.params.allusers, function(k, user){
                    $.each(self.config.users, function(i, user1){
                        if(user.user_id == user1){
                            ret.push(
                                {
                                    'uid': user.user_id,
                                    'userName': user.user_name
                                }
                            );
                        }
                    })
                })
                self.$el.remove()
                typeof self.config.confirmCallback == 'function' && self.config.confirmCallback(ret);
				return false
            })
            this.$el.delegate('[node-type="cancel"]','touchstart', function(){
                self.$el.remove();
                typeof self.config.cancelCallback == 'function' && self.config.cancelCallback();
				return false
            })
            this.$el.delegate('.search-confirm','tap', function(){
                var keyword = self.$el.find('.ui-searchbar-input input').val();
                self.renderSearch(keyword);
            })
            this.$el.delegate('.search-close','tap', function(){
                self.$el.find('.ui-searchbar-input input').val('');
                var keyword = '';
                self.renderSearch(keyword);
            })

        }
    }
    userselect.init({
        users: opts.users,
        confirmCallback: function(result){
            typeof opts.onSuccess == "function" && opts.onSuccess(result);
        }
    });
}

/*
ѡ���� opts { depts:[], onSuccess: function(){} }
*/
tMobileSDK.selectDept = function(opts) {
    if(window.deptselect != undefined || window.deptselect != null){
        deptselect = null;
    }
    window.deptselect = {
        params: {
            searchDeptUrl: '/mobile/inc/get_contactlist.php',
            $el: null,
            tpl: {
                'container': '<section class="ui-container userselect-ui-container deptselect-ui-container"> <footer class="ui-footer ui-footer-btn"> <div class="ui-footer ui-footer-stable ui-btn-group ui-border-t"> <button node-type="confirm" class="ui-btn-lg ui-btn-primary">ȷ��</button> <button node-type="cancel" class="ui-btn-lg">ȡ��</button> </div> </footer> <section class="ui-container"> <div class="ui-searchbar-wrap ui-border-b focus"> <div class="ui-searchbar ui-border-radius"> <i class="ui-icon-search"></i> <div class="ui-searchbar-text">��������</div> <div class="ui-searchbar-input"> <input value="" type="text" placeholder="��������" autocapitalize="off"> </div></div> <button type="button" class="ui-btn ui-btn-s search-confirm">����</button><button type="button" class="ui-btn ui-btn-s search-close">ȡ��</button></div><ul class="ui-list ui-border-tb"></ul> </section></section>',
                'dept': '<li class="ui-border-t" data-dept-id="<%=dept_id%>" data-dept-name="<%=dept_name%>"><label class="ui-checkbox"><input type="checkbox"><p><%=dept_name%></p></label></li>'
            },
            alldepts: []
        },
        init: function(options){
            var self = this;
            this.config = {
                depts: [],
                wrapper: 'body',//��װ����
                confirmCallback: function(){},
                cancelCallback: function(){}
            };
            this.config = $.extend(true,{}, this.config, options);
            this.initDom();
            this.initDepts();
            this.bindEvent();
        },
        initDom: function(){
            $(this.config.wrapper).append(gmu.$.tpl(this.params.tpl.container,{}));
            this.$el = $('.deptselect-ui-container');
        },
        initDepts: function(){
            var self = this;
            var opts = {
                A:'get_Dept',
                P : P,
                KWORD : ''
            };
            $.getJSON(this.params.searchDeptUrl, opts, function(data){
                self.params.alldepts = data;
                self.render();
            });
        },
        render: function(){
            //�������в���
            var self = this;
            var alldepts = this.params.alldepts;
            var depts = this.config.depts;
            var html = '';
            $.each(alldepts, function(k, dept){
                var dest = gmu.$.tpl(self.params.tpl.dept,dept);
                html += dest;
            })
            this.$el.find('.ui-list').html(html);
            //ѡ����ѡ
            this.config.depts.forEach(function(dept_id){
                $('[data-dept-id="'+ dept_id +'"]').find('input').prop('checked',true);
            });
        },
        renderSearch: function(keyword){
            this.$el.find('li').removeClass('hide');
            var hides = $.map(this.params.alldepts,function(dept){
                //console.log(dept.dept_name.indexOf(keyword));
                if(dept.dept_name.indexOf(keyword) == -1){
                    return dept.dept_id;
                }
            });
            $.each(hides, function(k, dept_id){
                $('[data-dept-id="'+dept_id+'"]').addClass('hide');
            })
        },
        bindEvent: function(){
            var self = this;
            this.$el.delegate('input:checkbox','change', function(){
                var dept_id = $(this).parents('li').attr('data-dept-id');
                if($(this).prop('checked')){
                    self.config.depts.push(dept_id);
                }else{
                    self.config.depts.splice($.inArray(dept_id,self.config.depts),1);
                }
            })
            this.$el.delegate('[node-type="confirm"]','touchstart', function(){
				$('.deptselect-ui-container input:checked').each(function(){
					var $me = $(this)
					var deptId = $me.parents('li.ui-border-t').attr('data-dept-id')
					if(self.config.depts.indexOf(deptId) != -1){
						
					}else{
						self.config.depts.push(deptId);
					}
				})
                var ret = [];
                $.each(self.params.alldepts, function(k, dept){
                    $.each(self.config.depts, function(i, dept1){
                        if(dept.dept_id == dept1){
                            ret.push(
                                {
                                    'deptId': dept.dept_id,
                                    'deptName': dept.dept_name
                                }
                            );
                        }
                    })
                })
                self.$el.remove()
                typeof self.config.confirmCallback == 'function' && self.config.confirmCallback(ret);
				return false
            })
            this.$el.delegate('[node-type="cancel"]','touchstart', function(){
                self.$el.remove();
                typeof self.config.cancelCallback == 'function' && self.config.cancelCallback();
				return false
            })
            this.$el.delegate('.search-confirm','tap', function(){
                var keyword = self.$el.find('.ui-searchbar-input input').val();
                self.renderSearch(keyword);
            })
            this.$el.delegate('.search-close','tap', function(){
                self.$el.find('.ui-searchbar-input input').val('');
                var keyword = '';
                self.renderSearch(keyword);
            })

        }
    }
    deptselect.init({
        depts: opts.depts,
        confirmCallback: function(result){
            typeof opts.onSuccess == "function" && opts.onSuccess(result);
        }
    });
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
    //return url.indexOf(baseUrl) != -1 ? (url.indexOf(state) != -1 ? url : (url + "&WXState=" + state)) : (url.indexOf(state) != -1 ? (baseUrl + url) : (baseUrl + url + "&WXState=" + state));
}
