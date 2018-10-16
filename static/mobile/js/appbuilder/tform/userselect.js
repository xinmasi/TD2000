define('UserselectCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var UserselectCtrl = Base.extend({
        initialize: function(config) {
            UserselectCtrl.superclass.initialize.call(this, config);
            this._config = config;
            this._render();
            this.$el = $('#f-field-'+this._config.field_id);
            this.bindEvent()
        },
        
        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config));
        },
        
        getValue: function() {

        },
        
        bindEvent: function() {
            var self = this;
            this.$el.find('button').on('click', function(){
                var ids = [];
                $('.ui-userselect-list a').each(function(){
                    var uid = $(this).attr('data-uid');
                    ids.push(uid);
                })
                tMobileSDK.selectUser({    
                    users: ids,
                    onSuccess: function(result){
                        self.$el.find('.ui-userselect-list').empty();
                        for(var i=0; i<result.length; i++ ){
                            var user_html = '<a href="javascript:;" class="ui-form-tag" data-uid="'+ result[i].uid+'">'+ result[i].userName+'</a>';   
                            self.$el.find('.ui-userselect-list').append(user_html);
                        }
                    },
                    onFail: function(data){
                        alert("用户选人失败：" + data);
                    }
                }); 
            })
        }

    });
    exports.UserselectCtrl = window.UserselectCtrl = UserselectCtrl;
});
