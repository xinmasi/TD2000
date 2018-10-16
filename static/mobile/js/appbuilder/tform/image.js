define('ImageCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var ImageCtrl = Base.extend({
        initialize: function(config) {
            ImageCtrl.superclass.initialize.call(this, config);
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
                tMobileSDK.chooseImage({ 
                    max: 9,
                    fromCamera: true,
                    onSuccess: function(result){
                        //self.$el.find('.ui-images-list').empty();
                        for(var i=0;i<result.length; i++ ){
                            var img_html ='<div class="ui-attach-img-wrap" data-id="'+ result[i].id +'"><i class="ui-icon-close-progress"></i><img class="ui-attach-img" src="'+result[i].url +'" /><p class="ui-attch-img-name" data-url="'+ result[i].href +'data-id="' + result[i].id + '">'+ result[i].name.replace("*","")+'</p></div>';
                            self.$el.find('.ui-images-list').append(img_html);
                        } 
                    },
                    onFail: function(result){
                        alert("ÉÏ´«Í¼Æ¬Ê§°Ü£º" + result);
                    }
                }); 
            })

            this.$el.delegate('i.ui-icon-close-progress', 'click', function(e) {
                e.stopPropagation();
                $(this).parent('div.ui-attach-img-wrap').remove();
            })
        }
    });
    exports.ImageCtrl = window.ImageCtrl = ImageCtrl;
});
