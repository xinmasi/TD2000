define('ListCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var ListView = require('ListView');
    var ListCtrl = Base.extend({
        initialize: function(config) {
            ListCtrl.superclass.initialize.call(this, config);
            this._config = config;
            this._config.required = false;
            this._config.hidden = false;
            this._render();
            this.$el = $('#f-field-'+this._config.field_id);
            this.$listcontent = $('#f-field-list-'+this._config.field_id);
            this.bindEvent();
            this.newListView();
        },
        
        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config))
        },
        
        getValue: function() {

        },
        
        newListView: function(){
            var self = this;
            var ListViewFieldManager = ListView.ListViewManager;

            self.list = new ListViewFieldManager({
                wrapper: self.$listcontent,
                fieldmanager: self._config.fieldmanager
            },  { detail:[self._config] }); 
        },
        
        configReset: function(){
            
        },
        
        bindEvent: function() {

        }
    });
    exports.ListCtrl = window.ListCtrl = ListCtrl;
});
