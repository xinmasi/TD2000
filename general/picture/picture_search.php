<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

if(!isset($start)||$start=="")
   $start=0;

if(strrchr($PIC_DIR,"/")=="/") 
  $PIC_DIR=substr($PIC_DIR,0,-1);

$LOCATION = str_replace('\\\\', '\\', $LOCATION);

//$FILE_COUNT-当前图片数量
//$SORT_COUNT-当前目录文件夹数量
$FILE_COUNT=0;


function pic_cycle($PIC_DIR,$file_name,$file_type,$FILE_COUNT,$LOCATION,$SUB_DIR,$PIC_PATH,$FILE_COUNT)
{
   static $FILE_ATTR_ARRAY;
   $dh = @opendir(iconv2os($PIC_DIR));
   while(false !== ($FILE_NAME = @readdir($dh)))
   {
      $FILE_NAME=iconv2oa($FILE_NAME);
      $File_Type="false";
      $File_Name="false";
      $FILE_PATH = $PIC_DIR."/".$FILE_NAME;
      if($FILE_NAME=='.' || $FILE_NAME=='..')
         continue;

      if(is_dir(iconv2os($FILE_PATH)) && $FILE_NAME=='tdoa_cache')
         continue;
      
      if(is_dir(iconv2os($FILE_PATH)))
      {
         pic_cycle($FILE_PATH,$file_name,$file_type,$FILE_COUNT,$LOCATION,$SUB_DIR,$PIC_PATH,$FILE_COUNT);
         continue;
      }

     //遍历文件
      $FILE_URL = iconv2os($PIC_DIR."/".$FILE_NAME);
      if(is_file($FILE_URL))
      {
        $TEP_TYPE = substr(strrchr($FILE_NAME,"."),1);
        if($TEP_TYPE=="db")
          continue;
       
       //查询条件类型 
        $temp_file_type=strtolower(substr(strrchr($FILE_NAME,"."),1));
        if($file_type=="") 
           $File_Type="true";
        else
           if($temp_file_type==$file_type)
              $File_Type="true"; 
        //查询条件名称
        if($file_name=="") 
           $File_Name="true";
        else
           if(strchr(strtolower(substr($FILE_NAME,0,strrpos($FILE_NAME,"."))),strtolower($file_name)))
              $File_Name="true";          
        if($File_Name=="true" && $File_Type=="true")
        {
           $FILE_ATTR_ARRAY[$FILE_COUNT]["SUB_DIR"]=substr($PIC_DIR,strlen($PIC_PATH)+1);
           $FILE_ATTR_ARRAY[$FILE_COUNT]["URL"]="/".substr($PIC_DIR,strlen($PIC_PATH)+1);
           $FILE_ATTR_ARRAY[$FILE_COUNT]["TYPE"]=substr(strrchr($FILE_NAME,"."),1);
           $FILE_ATTR_ARRAY[$FILE_COUNT]["NAME"]=$FILE_NAME;
           $FILE_ATTR_ARRAY[$FILE_COUNT]["TIME"]=date("Y-m-d H:i:s", filemtime(iconv2os($PIC_DIR."/".$FILE_NAME)));
           $FILE_ATTR_ARRAY[$FILE_COUNT]["SIZE"]=sprintf("%u", filesize(iconv2os($PIC_DIR."/".$FILE_NAME)));
           $FILE_COUNT++;  //文件计数
        }
        else
          continue;
      }
   } //目录遍历结束
 
    $arr = array(
  	'FILE_ATTR_ARRAY' => $FILE_ATTR_ARRAY,
	'FILE_COUNT'    => $FILE_COUNT
  );
  return $arr;
   
}

$arr2=array();
$arr2 = pic_cycle($PIC_DIR,$file_name,$file_type,$FILE_COUNT,$LOCATION,$SUB_DIR,$PIC_PATH,$FILE_COUNT);
$FILE_ATTR_ARRAY = $arr2['FILE_ATTR_ARRAY'];
$FILE_COUNT      = $arr2['FILE_COUNT'];


if(isset($_GET['page']) && isset($_GET['total']))
{
    $page = intval($_GET['page']);   //页码
    $total = intval($_GET['total']);   //总页数
    $start = $page*$ONE_PAGE_PICS;
}
else
{
    $page = 0;
    $start = 0;
    if($ONE_PAGE_PICS=="" || $ONE_PAGE_PICS==0)
    {
        $total = 1;
        $ONE_PAGE_PICS = $FILE_COUNT;
    }
    else
       $total = ceil($FILE_COUNT/$ONE_PAGE_PICS);
}

//分页地址
$url = 'picture_search.php?file_name='.$file_name.'&file_type='.$file_type.'&PIC_ID='.$PIC_ID.'&PIC_DIR='.$PIC_DIR.'&SUB_DIR='.$SUB_DIR.'&PIC_PATH='.$PIC_PATH.'&LOCATION='.$LOCATION.'&DLL_PRIV='.$DLL_PRIV.'&ONE_PAGE_PICS='.$ONE_PAGE_PICS;

$HTML_PAGE_TITLE = _("图片检索");
include_once("inc/header.inc.php");

?>
<body class="body">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.5.1/jscrollpane/jscrollpane.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/picture/style.css" />
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jscrollpane/jquery.jscrollpane.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jscrollpane/jquery.mousewheel.js<?=$GZIP_POSTFIX?>"></script>
<script>
jQuery.noConflict();
(function($){

    function resizeInit(){
        $("[node-type='imagesContainer']").height($(window).height() - $("[node-type='imagesNavbar']").outerHeight(true));  
    }

    $(window).resize(function(){
        resizeInit();  
    });

    $(document).ready(function(){

        resizeInit();
        
        $("[node-type='imagesContainer']").jScrollPane({
          "autoReinitialise": true,
          "mouseWheelSpeed" : 50
        });

        //图片滑动
        $("[node-type='imagesContainer']").delegate("[node-type='imageCell']", "mouseenter mouseleave", function(event){
            event.stopPropagation();
            if(event.type == "mouseenter")
              $(this).addClass("hover");
            else if(event.type == "mouseleave")
              $(this).removeClass("hover");
        });

        //选中图片
        $("[node-type='imageCell']").on('click', 'a' ,function(){
            if($(this).hasClass("selected"))
            {
                $(this).removeClass("selected");
                imageSelect.tips(-1);
            }
            else
            {
                $(this).addClass("selected");
                imageSelect.tips(1);
            }
        })

        //按钮事件绑定
        $("[node-type='btnOpts']").click(function(){
            var action = $(this).attr("node-data");
            switch(action)
            {
                case "back": 
                    window.location.href = "picture_view.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&CUR_DIR=<?=$CUR_DIR?>&VIEW_TYPE=<?=$VIEW_TYPE?>&ASC_DESC=<?=$ASC_DESC?>";
                    break;
                case "batchDown":
                    var file = imageSelect.getSelect();
                    if(file == "")
                    {
                        alert('<?=_("请至少选择一张图片")?>');
                        return;
                    }
                    else
                    {
                        file+= "@~@";
                        //加入精灵判断
                        var url = "batch_down.php?TmpFileNameStr="+file+"&SUB_DIR=<?=$SUB_DIR?>&PIC_PATH=<?=$PIC_PATH?>";
                        if(typeof(window.external.OA_SMS) == 'undefined')
                        {
                            window.location.href = url;
                        }else{
                            var $a = $("<a>&nbsp;</a>");
                            $a.attr({
                                'href' : "/general/picture/" + url
                            }).hide();
                            $('body').append($a);
                            $a[0].click();
                        }
                    }
                    break;
                case "selectAll":
                    if(!$(this).attr("state") || $(this).attr("state") == "")
                    {
                        $(this).html('<i class="icon-ok"></i><?=_("反选本页")?>').attr("state", "active");
                        $("[node-type='imageCell'] a").addClass("selected");
                        imageSelect.tips($("[node-type='imageCell'] a").length);
                    }
                    else{
                        $(this).html('<i class="icon-ok"></i><?=_("全选本页")?>').attr("state", "");
                        $("[node-type='imageCell'] a").removeClass("selected");
                        imageSelect.tips(0);
                    }
                    break;
            }
        });

        imageSelect = 
        {
              tipsDom: $("[node-type='selectTips']"),
              select: function(t){
                  this.tips(t);
              },
              selectAll: function(){
                  this.tips(this.els.length);
              },
              unselectAll: function(){
                  this.tips(0);
              },
              tips: function(n){
                  var count = this.tipsDom.find("[node-type='selectCount']");
                  switch(n)
                  {
                      case 0 : 
                          this.tipsDom.html('<?=_("点击下方照片进行多选！")?>');
                          break;
                      case 1 :
                          if(count.length > 0)
                              count.text(parseInt(count.text()) + 1);
                          else
                              this.tipsDom.html('<?=sprintf(_("您已选中%s张照片"), "<span class=\"selectcount\" node-type=\"selectCount\">1</span>")?>');
                          break;
                      case -1 :
                          if(count.length > 0 && parseInt(count.text()) > 1)
                              count.text(parseInt(count.text()) - 1);
                          else
                              this.tips(0);
                          break;
                      default : 
                          this.tipsDom.html('<?=sprintf(_("您已选中%s张照片"), "<span class=\"selectcount\" node-type=\"selectCount\"></span>")?>').find("[node-type='selectCount']").text(n);
                  }

                  //如果不是全选的
                  if(parseInt(this.tipsDom.find("[node-type='selectCount']").text()) != $("[node-type='imageCell']").length)
                  {
                      $("[node-type='btnOpts'][node-data='selectAll']").html('<i class="icon-ok"></i><?=_("全选本页")?>').attr("state", "");        
                  }
                  else
                  {
                      $("[node-type='btnOpts'][node-data='selectAll']").html('<i class="icon-ok"></i><?=_("反选本页")?>').attr("state", "active");       
                  }
              },
              getSelect: function(){
                  var a = [];
                  $("[node-type='imageCell'] a").each(function(){
                      if($(this).hasClass("selected")){
                          a.push($(this).attr("data-file-name") + "|" + $(this).attr("data-file-path"));      
                      }
                  });
                  return a.join("@~@");
              }
          }

    });

})(jQuery);
</script>

<div class="ga-hd" node-type="imagesNavbar">
    <div class="ga-hd-title">
        <h3 class="yahei"><?=$LOCATION?>-<?=_("检索结果")?></h3>
    </div>
    <div class="ga-hd-optbar">
        <button type="button" class="btn btn-default" node-type="btnOpts" node-data="back"><i class="icon-chevron-left"></i><?=_("返回")?></button>
        <div class="pagination pull-right">
            <?=get_pagination_html($url, $page, $total);?>
        </div>
    </div>
</div>

<div node-type="imagesContainer">

<? 
  if($FILE_COUNT == 0)
  { 
?>
  <div class="ga-alert-message">
      <div class="alert alert-block">
        <h4><?=_("提示")?></h4>
        <?=_("没有匹配的图片！")?>
      </div>
  </div>
<?  
  }
  else
  {
?>
    <div class="ga-search-bd-optbar clearfix">
        <div class="opt">
            <button type="button" class="btn btn-default" node-type="btnOpts" node-data="selectAll" data-toggle="button"><i class="icon-ok"></i><?=_("全选本页")?></button>
        </div>
        <div class="opt">
            <div class="alert alert-success" node-type="selectTips">
              <?=_("点击下方照片进行多选！")?>
            </div>
        </div> 
        <div class="opt">
            <button type="button" class="btn btn-default" node-type="btnOpts" node-data="batchDown"><i class="icon-download-alt"></i><?=_("开始下载")?></button>
        </div> 
    </div>

    <div class="ga-bd clearfix">
    <?
      for($i=$page* $ONE_PAGE_PICS ; $i< $FILE_COUNT && $i< ($page + 1)*$ONE_PAGE_PICS; $i++)
      {
          if(floor($FILE_ATTR_ARRAY[$i]["SIZE"]/1024/1024)>0)
            $FILE_SIZE=round($FILE_ATTR_ARRAY[$i]["SIZE"]/1024/1024,1)."M";
          else if(floor($FILE_ATTR_ARRAY[$i]["SIZE"]/1024)>0)
            $FILE_SIZE=round($FILE_ATTR_ARRAY[$i]["SIZE"]/1024,1)."K";
          else
            $FILE_SIZE=round($FILE_ATTR_ARRAY[$i]["SIZE"])."B";

          $TEMP = $PIC_ID."#".$FILE_ATTR_ARRAY[$i]["SUB_DIR"]."#".$FILE_ATTR_ARRAY[$i]["NAME"];
          $CHECK_ID = $i."_CHECK_ID";
          $TEMP_NAME_SUB=$FILE_ATTR_ARRAY[$i]["NAME"]."|".$FILE_ATTR_ARRAY[$i]["SUB_DIR"];
        
    ?>
          <div class="image-cell image-cell-wh10" node-type="imageCell">
              <a href="javascript:;" class="image-opt" data-file-name="<?=$FILE_ATTR_ARRAY[$i]["NAME"]?>" data-file-path="<?=$SUB_DIR?>">
                  <img src="header.php?PIC_ID=<?=$PIC_ID?>&Is_Thumb=1&SUB_DIR=<?=urlencode($SUB_DIR)?>&FILE_NAME=<?=urlencode($FILE_ATTR_ARRAY[$i]["NAME"])?>" />
                  <div class="select-mask"></div>
              </a>
          </div>
    <?
      }
    ?>
    </div>
<?
  }  
?>
</div>
</body>
</html>