/**
 * body显示处理
 */
jQuery.noConflict();

jQuery(document).ready(function(){
    window.onresize=setBody;
    jQuery(".tab li").bind('click',function(){disp_form(this.id);});
    setBody();
    var tabId = jQuery(".tab_on").attr("id");
    disp_form(tabId);
    if(tabId =='tab_document'){
        jQuery('#OC').height(jQuery('#content').height());
    }
});     


function setBody()
{
    var height = jQuery(window).height() - jQuery('#content').offset().top - jQuery("#footer").height();
    
    var width = jQuery(window).width() - jQuery('#content').offset().left*2;
    jQuery('#content').width(width).height(height);
}
/**
 * 保存doc正文
 */
function save_doc()
{
    OC.TANGER_OCX_SaveDoc(0);
}
/**
 * 页面监听
 */

function NotifyCtrlReady()
{
    oAIP.OnCtrlReady();
    if(para.convert == 1)  //第一次转换完上传
    {
        save_aip();
    }
}
/**
 * 流转选择人员窗
 */
function turn()
{
    jQuery("#tab_form").trigger('click');
    if(OC.TANGER_OCX_bDocOpen){
       msg="是否保存正文？";
       if(window.confirm(msg))
         OC.TANGER_OCX_SaveDoc(0);
    }
    ShowDialog('turn');
}
/**
 * 选取主题词
 * @param x
 */
function pick(x)
{
    document.getElementById(x).style.display='';
}
function pickclose(x)
{
    document.getElementById(x).style.display='none';
}
function pickwords(x)
{   a=document.form1.keywords;
    a.value=a.value==''?x:a.value + ' ' + x;
}

function save_form(para)
{
    var thing_to_post = jQuery("form[name=form1]").serialize();
    _post("/general/document/index.php/send/"+ para +"/update",thing_to_post,function(req){
      if(OC.TANGER_OCX_bDocOpen){
         OC.TANGER_OCX_SaveDoc(5);
      }
      alert(req.responseText);
    }); 

}
var $ = function(id) {return document.getElementById(id);};
function turn_exec(para)
{
    var vuser = $("user_str").value;
    var vsid = $("sid").value;
    if(vuser=="")
    {
        alert("请选择核稿人员！");
        return false;
    }
    var thing_to_post = jQuery("form[name=form1]").serialize();
    _post("/general/document/index.php/send/"+ para +"/update",thing_to_post,function(){});
    _post("/general/document/index.php/send/"+ para +"/turn","sid="+vsid+"&user_str="+vuser,function(req){
         if(typeof(OC)!='undefined') {
            HideDialog('turn');
            OC.window.close(); 
         }
         alert(req.responseText);
         close_window();
         window.opener.location = "/general/document/index.php/send/"+ para +"/"+ para +"_for/?connstatus=1";
         //window.close();
    });
}
function disp_form(cur_tab)
{
   jQuery("#content>div").each(function(){jQuery(this).hide();});
   jQuery("#"+cur_tab.substr(4)).show();
   
   tab_change_btn(cur_tab);
   tab_change_obj(cur_tab);
   tab_change_on(cur_tab);
}
function tab_change_obj(cur_tab)
{
      switch(cur_tab)
      {
          case "tab_form":
              break;
          case "tab_document":
            if(!OC.TANGER_OCX_bDocOpen)
	            setTimeout("OC.myload()",1);
		      jQuery('#OC').height(jQuery('#content').height());
              OC.document.getElementById('TANGER_OCX').style.height = jQuery('#content').height() + 'px';
            break;
          case "tab_attach":
              break;
          case "tab_aip":
            if(!is_build)
            {
               build_aip();
            }
            jQuery('#aip').height(jQuery('#content').height());
            break;
      }
}
function tab_change_on(cur_tab)
{
    jQuery("#"+cur_tab).addClass("tab_on");
    jQuery(".tab li").each(function(){
        var tab = jQuery(this);

        if(tab.attr("id") != cur_tab)
        {
            tab.removeClass("tab_on");
            if(tab.index() > jQuery("#"+cur_tab).index())
            {
                tab.removeClass("left").addClass("right");
            }
            else
            {
                tab.removeClass("right").addClass("left");
            }
        }
    });
}

/**
 * js监听实现表单个性化功能
 */
function rich_form(){
	var rich_user = ".form_user";
	var rich_user_single = ".form_user_single";
	var rich_dept = ".form_dept";
	var rich_dept_single = ".form_dept_single";
	var rich_priv = ".form_priv";
	
	jQuery(rich_user).each(function(i){
		append("");
	});
	jQuery(rich_user_single).append("");
	jQuery(rich_dept).append("");
	jQuery(rich_dept_single).append("");
	jQuery(rich_priv).append("");
}