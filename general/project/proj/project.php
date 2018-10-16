<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
if(!intval($PROJ_ID))
{
   Message("",_("未选中项目"));
   exit;
}

?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/ext/resources/css/ext-all.css"/>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/ext/ext-all.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/ext/locale/ext-lang-zh_CN.js"></script>
<script type="text/javascript" src="app/view/ProjDetail.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script>

var PROJ_ID = '<?=$PROJ_ID?>';
var PROJ_NAME = '<?=$PROJ_NAME?>';
Ext.onReady(function(){

   var tabs = Ext.create("Ext.TabPanel", {

      items: []
   });
   var viewport = Ext.create('Ext.Viewport', {
        layout: {
            type: 'border'
        },
        items: [{
            layout: 'fit',
            region: 'center',
            split: true,
            items: [tabs]
        }]});
        
      var Detail = Ext.getCmp('detail'+ PROJ_ID);
      if(!Detail){
         var tab = {
            id: 'detail'+ PROJ_ID,
            closable:false,
            title:  PROJ_NAME ? PROJ_NAME : '<?=_("项目详情")?>',
            xtype: 'ProjDetail' 
         };
         var newtab = tabs.add(tab);
         tabs.setActiveTab(newtab);
         Ext.getCmp('detail'+ PROJ_ID).query('tabpanel')[0].getDetail(PROJ_ID);
      }else{
         tabs.setActiveTab(Detail);
      }
});
   
</script>