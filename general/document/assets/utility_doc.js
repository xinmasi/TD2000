/**
 * body��ʾ����
 */
jQuery.noConflict();

(function($) {
    $(document).ready(function(){
        window.onresize=setBody;
        $(".tab li").bind('click',function(){disp_form(this.id);});
        setBody();
    });     
})(jQuery);

function setBody()
{
    var height = jQuery(window).height() - jQuery('#content').offset().top - jQuery("#footer").height();
    
    var width = jQuery(window).width() - jQuery('#content').offset().left*2;
    jQuery('#content').width(width).height(height);
}
/**
 * ����doc����
 */
function save_doc()
{
    OC.TANGER_OCX_SaveDoc(0);
}
/**
 * ҳ�����
 */

function NotifyCtrlReady()
{
    oAIP.OnCtrlReady();
    if(para.convert == 1)  //��һ��ת�����ϴ�
    {
        save_aip();
    }
}
/**
 * ��תѡ����Ա��
 */
function turn()
{
    jQuery("#tab_form").trigger('click');
    ShowDialog('turn');
}
/**
 * ѡȡ�����
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
            alert(req.responseText);
    }); 
}

function turn_exec(para)
{
    var vuser = $("user_str").value;
    var vsid = $("sid").value;
    if(vuser=="")
    {
        alert("��ѡ��˸���Ա��");
        return false;
    }
    _post("/general/document/index.php/send/"+ para +"/turn","sid="+vsid+"&user_str="+vuser,function(req){
            alert(req.responseText);
            window.opener.location = "/general/document/index.php/send/"+ para +"/"+ para +"_for/?connstatus=1";
            window.close();
        });
}

/**
 * js����ʵ�ֱ����Ի�����
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