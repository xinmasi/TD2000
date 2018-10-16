tMobileSDK = window.tMobileSDK || {};

tMobileSDK.ready = function(func){
	func();

};

//隐藏右上角按钮
tMobileSDK.hideOptionMenu = function() {
    wx.hideOptionMenu();
};

//显示右上角按钮
tMobileSDK.showOptionMenu = function() {
    wx.showOptionMenu();
};


tMobileSDK.previewImage = function() {

};

/*
 * 选择图片上传
 * @param obj 对应的参数
 */
tMobileSDK.chooseImage = function(options) {
	alert("请使用客户端进行操作。");
};

tMobileSDK.uploadImage = function(options) {
	alert("请使用客户端进行操作。");
};

tMobileSDK.saveToServer = function(options){
	alert("请使用客户端进行操作。");
};

tMobileSDK.selectFile = function(options) {
	alert("请使用客户端进行操作。");
};

/*
选部门 opts { depts:[], onSuccess: function(){} }
*/
tMobileSDK.selectDept = function(opts) {
    if(window.deptselect != undefined || window.deptselect != null){
        deptselect == null;
    }
    window.deptselect = {
        params: {
            searchDeptUrl: '/mobile/inc/get_contactlist.php',
            $el: null,
            tpl: {
                'container': '<section class="ui-container userselect-ui-container deptselect-ui-container"> <footer class="ui-footer ui-footer-btn"> <div class="ui-footer ui-footer-stable ui-btn-group ui-border-t"> <button node-type="confirm" class="ui-btn-lg ui-btn-primary">确认</button> <button node-type="cancel" class="ui-btn-lg">取消</button> </div> </footer> <section class="ui-container"> <div class="ui-searchbar-wrap ui-border-b focus"> <div class="ui-searchbar ui-border-radius"> <i class="ui-icon-search"></i> <div class="ui-searchbar-text">部门名称</div> <div class="ui-searchbar-input"> <input value="" type="text" placeholder="部门名称" autocapitalize="off"> </div> <i class="ui-icon-close"></i> </div> </div> <ul class="ui-list ui-border-tb"></ul> </section></section>',
                'dept': '<li class="ui-border-t" data-dept-id="<%=dept_id%>" data-dept-name="<%=dept_name%>"><label class="ui-checkbox"><input type="checkbox"><p><%=dept_name%></p></label></li>'
            },
            alldepts: []
        },
        init: function(options){
            var self = this;
            this.config = {
                depts: [],
                wrapper: 'body',//包装容器
                confirmCallback: function(){},
                cancelCallback: function(){}
            };
            this.config = $.extend(true,{}, this.config, options);
            this.initDom();
            this.initDepts();
            this.bindEvent();
        },
        initDom: function(){
            $(this.config.wrapper).html($.tpl(this.params.tpl.container,{}));
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
            //生成所有部门
            var self = this;
            var alldepts = this.params.alldepts;
            var depts = this.config.depts;
            var html = '';
            $.each(alldepts, function(k, dept){
                var dest = $.tpl(self.params.tpl.dept,dept);
                html += dest;
            })
            this.$el.find('.ui-list').html(html);
            //选中已选
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
            this.$el.delegate('input','change', function(){
                var dept_id = $(this).parents('li').attr('data-dept-id');
                if($(this).prop('checked')){
                    self.config.depts.push(dept_id);
                }else{
                    self.config.depts.splice($.inArray(dept_id,self.config.depts),1);
                }
            })
            this.$el.delegate('[node-type="confirm"]','click', function(){
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
                typeof self.config.confirmCallback == 'function' && self.config.confirmCallback(ret);
            })
            this.$el.delegate('[node-type="cancel"]','click', function(){
                self.$el.remove();
                typeof self.config.cancelCallback == 'function' && self.config.cancelCallback();
            })
            this.$el.delegate('.ui-searchbar-input input','keyup', function(){
                var keyword = $(this).val();
                self.renderSearch(keyword);
            })
            this.$el.delegate('.ui-icon-close','click', function(){
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
 * 增加授权地址
 * @param url 地址
 */

tMobileSDK.addAuthCode = function(url){

}
