define('FieldManager', ['FieldLoader',"base"], function(require, exports, module){
    var $ = window.Zepto;
    var Base = require('base');
    var Fields = require('FieldLoader');
    var FieldManager = Base.extend({
        attrs: {
            runId: null,
            flowId: null,
            container: 'body'
        },
        initialize: function(config, store) {
            FieldManager.superclass.initialize.call(this, config);
            this.$container = $(this.get('container'))
            this.store = store
            this.fieldsMap = store.fieldsMap
            this.layout = store.layout
            this.instances = {}
            this.initInstances()
        },
        initInstances: function() {
            var me = this;
            var Group = Fields['GroupCtrl']
            $.each(me.layout, function(key, groupCfg){
                groupCfg.id = key
                groupCfg.container = me.$container
                groupCfg.template  = $('#f-'+groupCfg.type+'-tmpl').html()
                me.instances['group-'+key] = new Group(groupCfg)
                $.each(groupCfg.fields, function(index, field_id){
                    var fieldCfg = me.fieldsMap[field_id]
                    var className = me.getFieldClassName(fieldCfg.type)//text => TextCtrl
                    fieldCfg.fieldmanager = me
                    var FieldClass = Fields[className]//TextCtrl//
                    fieldCfg.container = $('#f-group-'+key+' .f-group-bd')
                    fieldCfg.template  = $('#f-'+fieldCfg.type+'-tmpl').html()
                    me.instances['field-'+field_id] = new FieldClass(fieldCfg)
                })
            })
        },
        getFieldClassName: function(type){
            type = type.replace(/-/g, "")
            type = type.firstUpperCase()
            return type+'Ctrl'
        }
    });
    exports.FieldManager = window.FieldManager = FieldManager;
});
