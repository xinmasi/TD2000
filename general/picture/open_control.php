<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ͼƬ����");
include_once("inc/header.inc.php");
?>



<body topmargin=0 leftMargin=0 onLoad="inionload('<?=urlencode($FILE_NAME)?>', '<?=$FILE_NAME?>')" style="background-image:url(<?=MYOA_STATIC_SERVER?>/static/images/topbar.gif);">
<?
include_once("pic_array.php");
?>

<script language="javascript">

//ͼƬ�����α�
var cur_pic_no=0;

//�Ŵ�
var up_width,up_height;
	 
function blowup()
{
	
	 mywidth=parent.open_main.image.width;
	 myheight=parent.open_main.image.height;

	 up_width=mywidth * 1.1;
	 up_height=myheight * 1.1;

	 parent.open_main.image.width=up_width;
	 parent.open_main.image.height=up_height;
}
//��С
function reduce()
{
	
	 mywidth=parent.open_main.image.width;
	 myheight=parent.open_main.image.height;

	 up_width=mywidth * 0.9;
	 up_height=myheight * 0.9;

	 parent.open_main.image.width=up_width;
	 parent.open_main.image.height=up_height;
}

//���ʺ�  ʵ�ʴ�С
function adapt(flag)
{
	parent.open_main.pictable.style.zoom="100%";

	 true_width = FILE_ATTR_ARRAY[cur_pic_no][4];
	 true_height = FILE_ATTR_ARRAY[cur_pic_no][5]
	 clientWidth = parent.open_main.document.body.clientWidth;
	 clientHeight = parent.open_main.document.body.clientHeight;

	 if(flag==1) //ʵ�ʴ�С
	 {
	   up_width=true_width;
	   up_height=true_height;
   }
	 else if(flag==2) //���ʺ�
	 {
	 	 padbottom = 50;
	 	 if(true_width > clientWidth && true_height <= clientHeight)
	   {
	   	  up_width=clientWidth;
	   	  up_height=true_height*clientHeight/true_width - padbottom;
	   }
	   if(true_height > clientHeight && true_width <= clientWidth)
	   {
	   	  up_height=clientHeight - padbottom;
	   	  up_width=true_width*clientHeight/true_height;
	   }
	 	 if(true_width > clientWidth && true_height > clientHeight)
	   {
	   	  if(true_width >= true_height)
	   	  {
	   	     up_width=clientWidth;
	   	     up_height=true_height*clientWidth/true_width - padbottom;
	   	  }
	   	  else
	   	  {
	   	  	 up_height=clientHeight - padbottom;
	   	     up_width=true_width*clientHeight/true_height;
	   	  }
	   }
	 	 if(true_width < clientWidth && true_height < clientHeight)
	   {
	   	  up_height=true_height;
	   	  up_width=true_width;
	   }
	   if(true_width < clientWidth && true_height > clientHeight)
	   {
	   	  up_height=clientHeight - padbottom;
	   	  up_width=true_width*clientHeight/true_height;
	   }
   }
   parent.open_main.image.width =up_width;
   parent.open_main.image.height =up_height;
}

function inionload(encode_file_name, file_name)
{
   for(var i=0;i<FILE_ATTR_ARRAY.length;i++)
   {
   	 if(FILE_ATTR_ARRAY[i][1]==file_name)
   	    cur_pic_no = FILE_ATTR_ARRAY[i][0];
   }
   if(typeof(parent.open_main.div_image)=="object" || typeof(parent.open_main.div_image) == "object HTMLDivElement")
   {
		  parent.open_main.div_image.innerHTML="<img onload='parent.open_control.adapt(2);' src='header.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&FILE_NAME="+encode_file_name+"' alt='<?=_("���������ţ����ͼƬ��ҳ")?>' border=0 id='image'>";
		  parent.open_main.file_name.innerText= file_name;
		  parent.open_main.pictable.style.zoom="100%";
  }
}
function open_pic(op)
{
	
	cur_pic_no=parseInt(cur_pic_no)+op;
	if(parseInt(cur_pic_no) <= -1)
		cur_pic_no = FILE_ATTR_ARRAY.length - 1;
	else if(parseInt(cur_pic_no) >= FILE_ATTR_ARRAY.length)
		cur_pic_no = 0;
	
	file_name=FILE_ATTR_ARRAY[cur_pic_no][1];
	// parent.open_main.image.src="header.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&FILE_NAME="+escape(file_name);
	parent.open_main.image.src="header.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&FILE_NAME="+encodeURIComponent(file_name)+"&open_type=1";
	parent.open_main.file_name.innerText=file_name;
	parent.open_main.pictable.style.zoom="100%";
	parent.open_main.image.width="800";
	parent.open_main.image.height="600";
}

function down_pic()
{
	window.location="down.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&FILE_NAME="+ encodeURIComponent(FILE_ATTR_ARRAY[cur_pic_no][1])+"&open_type=1";
}

function exif_pic()
{
	window.open("photo_exif.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&FILE_NAME="+encodeURIComponent(FILE_ATTR_ARRAY[cur_pic_no][1])+"&VIEW_TYPE=<?=$VIEW_TYPE?>&ASC_DESC=<?=$ASC_DESC?>","<?=_("ͼƬ��Ϣ")?>","height=400, width=400, left=440, top=200, toolbar =no, menubar=no, scrollbars=yes, resizable=no, location=no, status=no");
}
</script>

<table align=center width=100% border=0 cellspacing=0 cellpadding=2 class=small>
  <tr><td align=center valign=center>
    <span style='padding-top:2px;'>
    <A href="javascript:open_pic(-1);"><img src=<?=MYOA_STATIC_SERVER?>/static/images/pre_pic.png width=48 height=48 title=<?=_("��һ��")?> border=0></A>&nbsp;
    <A href="javascript:open_pic(1);"><img src=<?=MYOA_STATIC_SERVER?>/static/images/next_pic.png width=48 height=48 title=<?=_("��һ��")?> border=0 id=a_id></A>&nbsp;
    <A href="javascript:adapt(2);"><img src=<?=MYOA_STATIC_SERVER?>/static/images/adapt.png width=48 height=48 title=<?=_("���ʺ�")?> border=0></A>&nbsp;
    <A href="javascript:adapt(1);"><img src=<?=MYOA_STATIC_SERVER?>/static/images/original.png width=48 height=48 title=<?=_("ʵ�ʴ�С")?> border=0></A>&nbsp;
    <A href="javascript:blowup()"><img src=<?=MYOA_STATIC_SERVER?>/static/images/plus.gif width=48 height=48 title=<?=_("�Ŵ�")?> border=0></A>&nbsp;
    <A href="javascript:reduce();"><img src=<?=MYOA_STATIC_SERVER?>/static/images/minus.gif width=48 height=48 title=<?=_("��С")?> border=0></A>&nbsp;
    <A href="javascript:down_pic();"><img src=<?=MYOA_STATIC_SERVER?>/static/images/save.gif width=48 height=48 title=<?=_("����ͼƬ")?> border=0></A>&nbsp;
    <A href="javascript:exif_pic();"><img src=<?=MYOA_STATIC_SERVER?>/static/images/sms_type40.gif width=48 height=48 title=<?=_("��Ƭ��Ϣ")?> border=0></A>
  </span></td>
</tr>
</table>
</body>
</html>
