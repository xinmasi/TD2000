/*
* name: ��ҳ�ˣ�����sdk���
* dependencies: zepto,gmu,frozen
* author: lijun
* description: Ϊ��ҳ�˺�΢�Ŷ��ṩͳһ�Ĺ���ģ��ӿڣ�����header��������Ⱦ�����˵���
* */

//���������ռ�
tMobileSDK = window.tMobileSDK || {};

//Ĭ�����ò���
tMobileSDK.configs = {
    headerType: 'wx-footer',
    hasBuildFunc: false
};

//helper ����
tMobileSDK.util = {
    eval: function(expression){
        try {
            eval(expression);
        } catch(error) {
            console.error(error.message);
        }
    }
};
//����header��������
tMobileSDK.buildHeader = function(headerData,options){
    options = options || {};
    options.type = options.type ? options.type : this.configs.headerType;

    //�Ƴ���һ�ι�����header
    if($('.sdk-' + options.type).length > 0){
        $('.sdk-' + options.type).remove();
        $('body .ui-actionsheet').remove();
        tMobileSDK.configs.hasBuildFunc = false;
    }
    
    //����δ�������ݺʹ�������Ϊ�ն�������
    if(!headerData){
        return false;
    } else {
        var isEmpty = true;
        for(var prop in headerData){
            isEmpty = false;
            break;
        }
        if(isEmpty){
            return false;
        }
    }

    //���ò���
    var opts = {};
    var wxhtml = '';
    opts.container = options.targetEl || 'body';
    opts.leftBtns = new Array('<i class="ui-icon-return" data-op="' + headerData.l.event + '"></i>');
    //ʵ���������ʱ����˫������ƴ���ַ����������õ����ţ���Ϊ��������data-op�а���˫����
    opts.rightBtns = $.isPlainObject(headerData.r) ? new Array("<button class='ui-btn " + headerData.r.class + "' data-op='" + headerData.r.event + "'>" + headerData.r.title + "</button>") : [];
    //���headerData.c������
    if($.isArray(headerData.c)){
        opts.title = '';
        $.each(headerData.c,function(index,item){
            opts.title += '<button class="ui-btn ' + item.active + '" data-op="' + item.event + '">' + item.title + '</button>';
            wxhtml += '<li class="ui-border-r" data-op="' + item.event + '">' + item.title + '</li>';
        });
    } else {
        opts.title = headerData.c.title || '';
        document.title = opts.title;
    }

    if($.isPlainObject(headerData.r)){
        wxhtml += '<li class="ui-border-r" data-op=\'' + headerData.r.event + '\'>' + headerData.r.title + '</li>';
    } 
    wxhtml = wxhtml ? '<ul class="ui-tiled ui-border-t">'+wxhtml : '';
    
    //ʵ����
    var _id = options.id || "J_toolbar", $el;//get the container id
    if(options.type === "header"){//header
        $el = $('<header class="sdk-header ui-header ui-header-stable" id="'+ _id +'"></header>').prependTo(opts.container).toolbar(opts);
    } else if(options.type === "footer"){//footer
        $el = $('<footer class="sdk-footer ui-footer ui-footer-stable" id="'+ _id +'"></footer>').prependTo(opts.container).toolbar(opts);
    } else if(options.type === 'wx-footer' && wxhtml) {
        $el = $('<footer class="sdk-wx-footer ui-footer ui-footer-btn" id="'+ _id +'"></footer>').prependTo(opts.container).html(wxhtml);
        $el.find('li').last().removeClass('ui-border-r').attr('data-op-dropdown', 1);
    }
   


    //���д���������
    //�����˰�ť
    $('#'+ _id +' .ui-icon-return').on('click',function(e){
        var cb = $(this).attr('data-op');
        if(cb){
            tMobileSDK.util.eval(cb);
        }
    });
    //�Ҳ������ť
    $('#'+ _id +' .ui-toolbar-right .ui-btn').on('click',function(e){
        var cb = $(this).attr('data-op');
        if(cb){
            tMobileSDK.util.eval(cb);
        }
    });
    //�м�Ĳ�����ť
    $('#'+ _id +' .ui-toolbar-title .ui-btn').on('click',function(e){
        $('#'+ _id +' .ui-toolbar-title .ui-btn').removeClass('active');
        $(this).addClass('active');
        var cb = $(this).attr('data-op');
        if(cb){
            tMobileSDK.util.eval(cb);
            document.title = $(this).text();
        }
    });
    //for wx
    $('#'+ _id +'.sdk-wx-footer [data-op]').on('click',function(e){
        var cb = $(this).attr('data-op');
        if(cb){
            tMobileSDK.util.eval(cb);
            document.title = $(this).text();
        }
    });
};



//��Ⱦ�����˵�
tMobileSDK.buildFunc = function(funcData,opts){//���ݴ�behav�д�������options�������ò���
    
    if(tMobileSDK.configs.hasBuildFunc){
        return false;
    }

    var type = opts ? opts.type : this.configs.headerType;
    

    //�������ҳ�˹���
    if(type === "header"){

        var content = [];
        var callbacks = {};
        //�������ݣ���ȡ���õ�content
        for(var i=0,len=funcData.length; i<len; i++){

            content.push(funcData[i].title);
            callbacks[funcData[i].title] = funcData[i].event;//��ȡ�ص�
            //��������һ����������治�ü�divider
            if(i < funcData.length-1){
                content.push('divider');
            }
        }

        //���ò���
        configs = {
            content: content,
            horizontal: false,
            align: 'right',
            placement: 'bottom',
            offset: {top: -5}
        };



        //��Ⱦ�����˵���Ϊע��item����¼�
        $('.sdk-header .ui-toolbar-right').dropmenu(configs).on('itemclick',function(e,li){
            var cb = $(li).text();
            e.preventDefault();
            if(cb){
                tMobileSDK.util.eval(callbacks[cb]);
            }
            $('.sdk-header .ui-toolbar-right').trigger('click');
            $('.op-menu-mask').hide();
        });

        //��������
        $('<div class="op-menu-mask"></div>').insertAfter('.sdk-header .ui-dropmenu').on('click',function(){
            $('.sdk-header .ui-toolbar-right').trigger('click');
            $(this).hide();
        });
        $('.sdk-header .ui-toolbar-right button').on('click',function(){
            $('.op-menu-mask').show();
        })



        tMobileSDK.configs.hasBuildFunc = true;
    }
   

    //�����΢�Ŷ˹���
    if(type == "footer" || type == 'wx-footer'){

        //����ģ��
        var tmpl = '<div class="ui-actionsheet">' +
                        '<div class="ui-actionsheet-cnt">' +
                            '<% for(var i=0; i<edit_opts.length; i++) { %>' +
                                '<button class="op-btns" data-op="<%= edit_opts[i].event %>"><%= edit_opts[i].title %></button>' +
                            '<% } %>' +
                            '<button class="hide-op-menu">'+td_lang.pda.cancel || 'ȡ��'+'</button>' +
                        '</div>' +
                    '</div>';

        //��ȾDOM
        var dest = $.tpl(tmpl,{edit_opts: funcData});
        $('body').append(dest);
        $('.ui-actionsheet').addClass('show');

        //���¼�
        $('.hide-op-menu').on('click',function(){
            $('.ui-actionsheet').removeClass('show');
        });
        $('.ui-actionsheet').on('click',function(){
            $('.ui-actionsheet').removeClass('show');
        });
        $('.sdk-footer .ui-toolbar-right button,.sdk-wx-footer [data-op-dropdown="1"]').on('click',function(){
            $('.ui-actionsheet').addClass('show');
        })
        $('.ui-actionsheet .op-btns').on('click',function(e){
            var cb = $(this).attr('data-op');
            if(cb){
                tMobileSDK.util.eval(cb);
            }
            $('.ui-actionsheet').removeClass('show');

        })



        tMobileSDK.configs.hasBuildFunc = true;
    }
};

//infomation collection function
tMobileSDK.collectInfo = function(url){
    if(!url){
        return;    
    }
    var params = {};
    
    if(navigator.userAgent){
        params.ua = navigator.userAgent;
    };
    if(window.screen){
        params.sw = window.screen.width;
        params.sh = window.screen.height;
    };
    if(window.location){
        params.host = window.location.host;
        params.url = window.location.href;
    }
    params.platform = this.platForm || '';
    
    
    try{
        var timing = performance.timing.toJSON();
        var result = {};
        for(var key in timing){
            if(timing[key]){
                result[key] = timing[key];
            }
        }
        params.timing = JSON.stringify(result);
    }catch(e){}
    
    
    var args = '';
    for(var i in params) {
        if(args != '') {
            args += '&';
        }   
        args += i + '=' + encodeURIComponent(params[i]);
    }

    var img = new Image(1,1);
    img.src = url + '?' + args;
}; 


tMobileSDK.module2icon = function(module){
    var host = location.origin;
    var url = host + "/static/images/mobile_app/{module}.png".replace(/{module}/i, module);
    return url;
}

tMobileSDK.ding = function(){};

tMobileSDK.dingBtn = function(){};
tMobileSDK.setLeft = function(){};