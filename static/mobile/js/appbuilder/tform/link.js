define('LinkCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var LinkCtrl = Base.extend({
        initialize: function(config) {
            LinkCtrl.superclass.initialize.call(this, config);
            this._config = config
            this._config.required = false
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
            // this.$el.on('click', function(){
            //   //
            // })
        }
    });
    exports.LinkCtrl = window.LinkCtrl = LinkCtrl;
});
