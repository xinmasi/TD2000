define('AttachmentCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var AttachmentCtrl = Base.extend({
        initialize: function(config) {
            AttachmentCtrl.superclass.initialize.call(this, config);
            this._config = config
            this._render()
            this.$el = $('#f-field-'+this._config.field_id)
            this.bindEvent()
        },
        
        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config))
        },
        
        getValue: function() {

        },
        
        bindEvent: function() {
           var self = this;
            this.$el.find('button').on('click', function(){
               // var loader = $.loading({ content:'加载中...',});
                tMobileSDK.selectFile({          
                    onSuccess: function(data){
                        for(var i=0; i<data.length; i++ ){
                            var attch_html = '<div class="ui-attch-file-wrap"><a href="javascript:;"  class="ui-attch-file-name" data-url="'+data[i].url+'" data-id="'+data[i].id+'">'+ data[i].name.replace("*","") +'</a><i class="ui-icon-close-progress"></i></div>';
                            self.$el.find('.ui-attach-file').append(attch_html);
                        } 
                       // loader.loading("hide"); 
                    },
                    onFail: function(data){
                        alert("上传文件失败：" + data);
                        //loader.loading("hide"); 
                    }
                }); 
                
            })
            
            this.$el.delegate('i.ui-icon-close-progress', 'click', function(e) {
                e.stopPropagation();
                $(this).parent('div').remove();
            })
        }
    });
    exports.AttachmentCtrl = window.AttachmentCtrl = AttachmentCtrl;
});
