<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
$HTML_PAGE_TITLE = _("ͼƬ��������");
include_once("inc/header.inc.php");

/*
$VIEW_TYPE=="NAME" ���������� 
$VIEW_TYPE=="TYPE" ����������
$VIEW_TYPE=="TIME" ���޸�ʱ�� 
$VIEW_TYPE=="SIZE" ����С����
*/

$IMG_TYPE_STR="gif,jpg,png,swf,swc,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp,tif"; //�����ļ�����

if($ASC_DESC=="") //4����  3����
    $ASC_DESC=4;

if($VIEW_TYPE=="") //Ĭ�ϰ����ļ�������
    $VIEW_TYPE="NAME";

if(!in_array($ASC_DESC, array(3,4)) || !in_array($VIEW_TYPE, array('NAME','TYPE','TIME','SIZE'))){
    Message(_("����"), _("���ݲ�������"));
    exit;
}

if($SUB_DIR!="")
{
    $FOLDER = $SUB_DIR=urldecode($SUB_DIR);
    $SUBDIR=substr($SUB_DIR,0,strrpos($SUB_DIR,"/"));
}

//��ȡ�½�ͼƬĿ¼·��������
if($CUR_DIR=="")
{
    $query = "SELECT PIC_NAME,PIC_PATH,ROW_PIC_NUM,ROW_PIC from PICTURE where (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_DEPT_ID) OR TO_DEPT_ID='ALL_DEPT' or  find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',TO_PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_USER_ID)) and PIC_ID='$PIC_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $PIC_NAME=$ROW["PIC_NAME"];
       $PIC_PATH=$ROW["PIC_PATH"];
       $TD_COUNT = $ROW["ROW_PIC_NUM"]==0?7:$ROW["ROW_PIC_NUM"];//ÿ��ͼƬ����
       $ROW_PIC = $ROW["ROW_PIC"]==0?5:$ROW["ROW_PIC"];

       //�Զ���ҳ����ʾ����
       if(!isset($ONE_PAGE_PICS))
          $ONE_PAGE_PICS = $TD_COUNT * $ROW_PIC;// ÿҳ�ļ����ļ�������
    }
    else
       exit;

    if(strstr($SUB_DIR,"..") || strstr($SUB_DIR,"./"))
       exit;

    //��ǰĿ¼·��
    if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
       $CUR_DIR = $PIC_PATH.$SUB_DIR;
    else
       $CUR_DIR = $PIC_PATH."/".$SUB_DIR;

    if(stristr($CUR_DIR,".."))
    {
        Message(_("����"),_("�������зǷ��ַ���"));
        exit;
    }
}
else
{
    $query = "SELECT PIC_NAME,PIC_PATH,PIC_ID,ROW_PIC_NUM,ROW_PIC,TO_PRIV_ID from PICTURE where (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_DEPT_ID) OR TO_DEPT_ID='ALL_DEPT' or  find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',TO_PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_USER_ID)) and PIC_ID='$PIC_ID'";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $PIC_PATH = $ROW["PIC_PATH"];
        if(stristr($CUR_DIR,$PIC_PATH))
        {
            $PIC_ID = $ROW["PIC_ID"];
            $PIC_NAME = $ROW["PIC_NAME"];
            $TD_COUNT = $ROW["ROW_PIC_NUM"]==0?7:$ROW["ROW_PIC_NUM"];//ÿ��ͼƬ����
            $ROW_PIC = $ROW["ROW_PIC"]==0?5:$ROW["ROW_PIC"];
            $ONE_PAGE_PICS = $TD_COUNT * $ROW_PIC;// ÿҳ�ļ����ļ�������
            $TEMP = $CUR_DIR;
            $TEMP = str_ireplace($PIC_PATH,"",$TEMP);
            $TEMP = substr($TEMP,1);
            if(strlen($CUR_DIR)!=strlen($PIC_PATH))
               $SUB_DIR = $TEMP;
            $SUBDIR=substr($SUB_DIR,0,strrpos($SUB_DIR,"/"));
            break;
        }
    }
}

//ҳ�� ��ʼ��
if($page=="")
{
    $page = 0;
    $PAGE_NO = 1;
}
   

//------------------
$SORT_COUNT=0;  //��ǰĿ¼�ļ�������
$FILE_COUNT=0;  //��ǰ�ļ�������
$dh = @opendir(iconv2os($CUR_DIR));

while(false !== ($FILE_NAME = @readdir($dh)))
{
    $FILE_NAME = iconv2oa($FILE_NAME);

    if($FILE_NAME=='.' || $FILE_NAME=='..')
    continue;

    //�����ļ�
    $tmp_file_url = iconv2os($CUR_DIR."/".$FILE_NAME);
    if(is_file($tmp_file_url))
    {
        $TEP_TYPE = substr(strrchr($FILE_NAME,"."),1);

        if($TEP_TYPE=="db")
          continue;
        $FILE_ATTR_ARRAY[$FILE_COUNT]["TYPE"]=substr(strrchr($FILE_NAME,"."),1);
        $FILE_ATTR_ARRAY[$FILE_COUNT]["NAME"]=$FILE_NAME;
        $FILE_ATTR_ARRAY[$FILE_COUNT]["TIME"]=date("Y-m-d H:i:s", filemtime($tmp_file_url));
        $FILE_ATTR_ARRAY[$FILE_COUNT]["SIZE"]=sprintf("%u", filesize($tmp_file_url));

        $FILE_COUNT++;  //�ļ�����
        $TEMP_FILE_DIR=$CUR_DIR."/tdoa_cache/".$FILE_NAME; //��ǰ�ļ�����ͼ·��

        $NOW_FILE_DIR1=$CUR_DIR."/".$FILE_NAME;
        $NOW_FILE_DIR2=$CUR_DIR."/tdoa_cache/".$FILE_NAME;

        $TEMP_FILE_DIR = iconv2os($TEMP_FILE_DIR);
        if(!file_exists($TEMP_FILE_DIR)) //�Ƿ�������ͼ
        {
            $FILE_TYPE=substr(strrchr($FILE_NAME, "."), 1);
            $FILE_TYPE=strtolower($FILE_TYPE);

            if(find_id($IMG_TYPE_STR,$FILE_TYPE))
            {
                $DEFAULT_DIR=$CUR_DIR."/"."tdoa_cache";
                $DEFAULT_DIR=iconv2os($DEFAULT_DIR);
                if(!file_exists($DEFAULT_DIR))
                  mkdir($DEFAULT_DIR);

                $NOW_FILE_DIR=$CUR_DIR."/".$FILE_NAME;
                $NOW_FILE_DIR=str_replace("//","/",$NOW_FILE_DIR);
                if($FILE_COUNT >= $ONE_PAGE_PICS*($PAGE_NO-1) && $FILE_COUNT < $ONE_PAGE_PICS*$PAGE_NO)

                if ($FILE_TYPE!="bmp")
                  crop($NOW_FILE_DIR,150,150,1,iconv2oa($TEMP_FILE_DIR));
                  //CreateThumb($NOW_FILE_DIR,150,150,iconv2oa($TEMP_FILE_DIR));
                else
                  td_copy($NOW_FILE_DIR1, $NOW_FILE_DIR2);
            }
        }
    }
    else
    {
      if($FILE_NAME=='tdoa_cache')  //��������ͼĿ¼
        continue;

      //����Ŀ¼
      $SORT_ATTR_ARRAY[$SORT_COUNT]["NAME"]=$FILE_NAME;
      $SORT_ATTR_ARRAY[$SORT_COUNT]["TIME"]=date("Y-m-d H:i:s", filemtime($tmp_file_url));

      //Ŀ¼����
      $SORT_COUNT++;
    }
} //��ǰĿ¼��������

//�ļ�������
if($FILE_COUNT!=0)
{
    $SORT_ASC=4;
    $SORT_DESC=3;
    foreach($FILE_ATTR_ARRAY as $RES)
      $SORTAUX[]= strtolower($RES[$VIEW_TYPE])."<br>";
    if($ASC_DESC==4)
      array_multisort($SORTAUX,$SORT_ASC,SORT_NUMERIC,$SORTAUX,$SORT_ASC,SORT_STRING,$FILE_ATTR_ARRAY);
    else
      array_multisort($SORTAUX,$SORT_DESC,SORT_NUMERIC,$SORTAUX,$SORT_DESC,SORT_STRING,$FILE_ATTR_ARRAY);
}

//�ļ���������
if($SORT_COUNT!=0)
{
    if($VIEW_TYPE=="TYPE" || $VIEW_TYPE=="SIZE")
    {
      foreach($SORT_ATTR_ARRAY as $RES1)
        $SORTAUX1[]= strtolower($RES1["NAME"]);
    }
    if($VIEW_TYPE=="TIME" || $VIEW_TYPE=="NAME")
    {
      foreach($SORT_ATTR_ARRAY as $RES1)
        $SORTAUX1[]= strtolower($RES1[$VIEW_TYPE]);
    }

    $SORT_ASC=4;
    $SORT_DESC=3;

    if($ASC_DESC==4)
      array_multisort($SORTAUX1,$SORT_ASC,SORT_NUMERIC,$SORTAUX1,$SORT_ASC,SORT_STRING,$SORT_ATTR_ARRAY);
    else
      array_multisort($SORTAUX1,$SORT_DESC,SORT_NUMERIC,$SORTAUX1,$SORT_DESC,SORT_STRING,$SORT_ATTR_ARRAY);
}

//����ϲ�
$ALL_COUNT = $SORT_COUNT + $FILE_COUNT;
$Z=0;
for($H=0;$H < $FILE_COUNT; $H++)
{
    $Z++;
    $SORT_FILE_ARRAY[$Z]["TYPE"] = $FILE_ATTR_ARRAY[$H]["TYPE"];
    $SORT_FILE_ARRAY[$Z]["NAME"] = $FILE_ATTR_ARRAY[$H]["NAME"];
    $SORT_FILE_ARRAY[$Z]["TIME"] = $FILE_ATTR_ARRAY[$H]["TIME"];
    $SORT_FILE_ARRAY[$Z]["SIZE"] = $FILE_ATTR_ARRAY[$H]["SIZE"];
}

//��ȡ��ǰ·��
if($SUB_DIR=="")
{
    $LOCATION = $PIC_NAME;
    $FOLDER =   $PIC_NAME;
}
else
{
    $LOCATION=$PIC_NAME."/".$SUB_DIR;  
}
  $LOCATION=str_replace("/","\\",$LOCATION);

//�����ҳ

if($ONE_PAGE_PICS=="" || $ONE_PAGE_PICS==0)
    $page_size = $FILE_COUNT;
else
    $page_size = $ONE_PAGE_PICS;

if(isset($_GET['page']) && isset($_GET['total']))
{
    $page = intval($_GET['page']);   //ҳ��
    $total = intval($_GET['total']);   //��ҳ��
    $PAGE_NO = $page + 1;
    $start = $page*$page_size;
}
else
{
    $page = 0;
    $PAGE_NO = 1;
    $start = 0;
    if($ONE_PAGE_PICS=="" || $ONE_PAGE_PICS==0)
       $total = 1;
    else
       $total = ceil($FILE_COUNT/$ONE_PAGE_PICS);
}

$url = "picture_batch_down.php?PIC_ID=".$PIC_ID."&SUB_DIR=".$SUB_DIR."&ASC_DESC=".$ASC_DESC."&VIEW_TYPE=".$VIEW_TYPE."&PAGE_NO=".$PAGE_NO."&ONE_PAGE_PICS=".$ONE_PAGE_PICS;

//Ȩ�޲�ѯ
$query = "select PRIV_STR,DEL_PRIV_STR  from PICTURE where PIC_ID='$PIC_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PRIV_STR=$ROW["PRIV_STR"];
    $DEL_PRIV_STR =$ROW["DEL_PRIV_STR"];

    $PRIV_ARRAY=explode("|",$PRIV_STR);
    $PRIV_DEPT=$PRIV_ARRAY[0];
    $PRIV_ROLE=$PRIV_ARRAY[1];
    $PRIV_USER=$PRIV_ARRAY[2];

    $PRIV_ARRAY1=explode("|",$DEL_PRIV_STR);
    $PRIV_DEPT1=$PRIV_ARRAY1[0];
    $PRIV_ROLE1=$PRIV_ARRAY1[1];
    $PRIV_USER1=$PRIV_ARRAY1[2];
}
$UPLOAD_PRIV = 0;
if($PRIV_DEPT=="ALL_DEPT" or find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) or check_dept_other_priv($PRIV_DEPT) or find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"]) or find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]))
   $UPLOAD_PRIV = 1;
$DLL_PRIV = 0;
if($PRIV_DEPT1=="ALL_DEPT" or find_id($PRIV_DEPT1,$_SESSION["LOGIN_DEPT_ID"]) or check_dept_other_priv($PRIV_DEPT1) or find_id($PRIV_ROLE1,$_SESSION["LOGIN_USER_PRIV"]) or find_id($PRIV_USER1,$_SESSION["LOGIN_USER_ID"]))
   $DLL_PRIV = 1;

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

        //ͼƬ����
        $("[node-type='imagesContainer']").delegate("[node-type='imageCell']", "mouseenter mouseleave", function(event){
            event.stopPropagation();
            if(event.type == "mouseenter")
              $(this).addClass("hover");
            else if(event.type == "mouseleave")
              $(this).removeClass("hover");
        });

        $("[node-type='setPageSize']").click(function(){
            var count = $("[node-type='pageSize']").val();
            if(/^[0-9]*[1-9][0-9]*$/.test(count))
            {
                window.location.href = "picture_batch_down.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&ASC_DESC=<?=$ASC_DESC?>&VIEW_TYPE=<?=$VIEW_TYPE?>&ONE_PAGE_PICS=" + count;  
            }else{
                alert('<?=_("����������")?>');
                $("[node-type='pageSize']").val("<?=$ONE_PAGE_PICS?>");
                return;
            }
        });

        //ѡ��ͼƬ
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
        });

        //��ť�¼���
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
                        alert('<?=_("������ѡ��һ��ͼƬ")?>');
                        return;
                    }
                    else
                    {
                        file+= "@~@";
                        //���뾫���ж� lp 2013/10/17 2:16:48
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
                        $(this).html('<i class="icon-ok"></i><?=_("��ѡ��ҳ")?>').attr("state", "active");
                        $("[node-type='imageCell'] a").addClass("selected");
                        imageSelect.tips($("[node-type='imageCell'] a").length);
                    }
                    else{
                        $(this).html('<i class="icon-ok"></i><?=_("ȫѡ��ҳ")?>').attr("state", "");
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
                          this.tipsDom.html('<?=_("����·���Ƭ���ж�ѡ��")?>');
                          break;
                      case 1 :
                          if(count.length > 0)
                              count.text(parseInt(count.text()) + 1);
                          else
                              this.tipsDom.html('<?=sprintf(_("����ѡ��%s����Ƭ"), "<span class=\"selectcount\" node-type=\"selectCount\">1</span>")?>');
                          break;
                      case -1 :
                          if(count.length > 0 && parseInt(count.text()) > 1)
                              count.text(parseInt(count.text()) - 1);
                          else
                              this.tips(0);
                          break;
                      default : 
                          this.tipsDom.html('<?=sprintf(_("����ѡ��%s����Ƭ"), "<span class=\"selectcount\" node-type=\"selectCount\"></span>")?>').find("[node-type='selectCount']").text(n);
                  }

                  //�������ȫѡ��
                  if(parseInt(this.tipsDom.find("[node-type='selectCount']").text()) != $("[node-type='imageCell']").length)
                  {
                      $("[node-type='btnOpts'][node-data='selectAll']").html('<i class="icon-ok"></i><?=_("ȫѡ��ҳ")?>').attr("state", "");        
                  }else{
                      $("[node-type='btnOpts'][node-data='selectAll']").html('<i class="icon-ok"></i><?=_("��ѡ��ҳ")?>').attr("state", "active");
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

<div class="ga-hd clearfix" node-type="imagesNavbar">
    <div class="ga-hd-title">
        <h3 class="yahei"><?=$FOLDER?><small>��<?=$FILE_COUNT > 0 ? $FILE_COUNT : 0 ?><?=_("��")?>��</small></h3>
    </div>
    <div class="ga-hd-optbar">
        <div class="btn-group">
            <button type="button" class="btn btn-default" node-type="btnOpts" node-data="back"><i class="icon-chevron-left"></i><?=_("����")?></button>
            <div class="input-prepend input-append input-page-size">
                <span class="add-on"><?=_("ÿҳ��ʾ")?></span>
                <input type="text" class="search-query" value="<?=$ONE_PAGE_PICS?>" node-type="pageSize">
                <button type="button" class="btn btn-default" node-type="setPageSize"><?=_("����")?></button>
            </div>

        </div>
        <div class="pagination pull-right">
            <?=get_pagination_html($url, $page, $total);?>
        </div>
    </div>
</div>

<div node-type="imagesContainer">

<?
  $WRAP_COUNT=0;

  //��ҳ
  $B=$ONE_PAGE_PICS*($PAGE_NO-1) + 1;

  if($ONE_PAGE_PICS*$PAGE_NO < $Z)
     $E=$ONE_PAGE_PICS*$PAGE_NO;
  else if($Z%$TD_COUNT!=0)
     $E=$Z + $TD_COUNT - $Z%$TD_COUNT;
  else
     $E=$Z;
?>

<?
  if($Z==0)
  {
     $E = 0;
?>
  <div class="ga-alert-message">
      <div class="alert alert-block">
        <h4><?=_("��ʾ")?></h4>
        <?=_("û��ƥ���ͼƬ��")?>
      </div>
  </div>
<?  
  }
  else
  {
?>
    <div class="ga-search-bd-optbar clearfix">
        <div class="opt">
            <button type="button" class="btn btn-default" node-type="btnOpts" node-data="selectAll" data-toggle="button"><i class="icon-ok"></i><?=_("ȫѡ��ҳ")?></button>
        </div>
        <div class="opt">
            <div class="alert alert-success" node-type="selectTips">
              <?=_("����·���Ƭ���ж�ѡ��")?>
            </div>
        </div> 
        <div class="opt">
            <button type="button" class="btn btn-default" node-type="btnOpts" node-data="batchDown"><i class="icon-download-alt"></i><?=_("��ʼ����")?></button>
        </div> 
    </div>

    <div class="ga-bd clearfix">
    <?
        //��ʾ���ļ�����
        for($I=$B;$I <= $E;$I++)
        {
           $WRAP_COUNT++;

           //�����ļ�������
           if(strlen(csubstr($SORT_FILE_ARRAY[$I]["NAME"],0,strrpos($SORT_FILE_ARRAY[$I]["NAME"], "."))) >= 8)
              $CSUB_SF_NAME = csubstr(csubstr($SORT_FILE_ARRAY[$I]["NAME"],0,strrpos($SORT_FILE_ARRAY[$I]["NAME"], ".")),0,8)."...";
           else
              $CSUB_SF_NAME = $SORT_FILE_ARRAY[$I]["NAME"];

            $FILE_TYPE=substr(strrchr($SORT_FILE_ARRAY[$I]["NAME"], "."), 1);

            $FILE_TYPE=strtolower($FILE_TYPE);
            if($SORT_FILE_ARRAY[$I]["NAME"]!="")
            {
              //����������ļ�
              if(find_id($IMG_TYPE_STR,$FILE_TYPE))
              {

                  $TEMP_FILE_DIR=$CUR_DIR."/tdoa_cache/".$SORT_FILE_ARRAY[$I]["NAME"]; //��ǰ�ļ�����ͼ·��
                  if(!file_exists(iconv2os($TEMP_FILE_DIR)))
                  {
                    $FILE_TYPE=substr(strrchr($SORT_FILE_ARRAY[$I]["NAME"], "."), 1);
                    $FILE_TYPE=strtolower($FILE_TYPE);
                    
                    if(find_id($IMG_TYPE_STR,$FILE_TYPE))
                    {
                      $DEFAULT_DIR=$CUR_DIR."/"."tdoa_cache";
                      if(!file_exists(iconv2os($DEFAULT_DIR)))
                        mkdir(iconv2os($DEFAULT_DIR));

                      $NOW_FILE_DIR=$CUR_DIR."/".$SORT_FILE_ARRAY[$I]["NAME"];
                      $NOW_FILE_DIR=str_replace("//","/",$NOW_FILE_DIR);

                      crop($NOW_FILE_DIR,150,150,1,iconv2oa($TEMP_FILE_DIR));
                      //CreateThumb(iconv2os($NOW_FILE_DIR),150,150,iconv2os($TEMP_FILE_DIR));
                    }
                 }

                 if(file_exists(iconv2os($TEMP_FILE_DIR)))
                    $FILE_PATH=$CUR_DIR."/tdoa_cache/".$SORT_FILE_ARRAY[$I]["NAME"];
                 else
                    $FILE_PATH=$CUR_DIR."/".$SORT_FILE_ARRAY[$I]["NAME"];
                 $IMG_ATTR=@getimagesize(iconv2os($FILE_PATH));
                 if($IMG_ATTR[0]>150)
                     $IMG_ATTR[0]=150;
                 if($IMG_ATTR[1]>150)
                     $IMG_ATTR[1]=150;
        
    ?>
          <div class="image-cell image-cell-wh10" node-type="imageCell">
              <a href="javascript:;" class="image-opt" data-file-name="<?=$SORT_FILE_ARRAY[$I]['NAME']?>" data-file-path="<?=$SUB_DIR?>">
                  <img src="header.php?PIC_ID=<?=$PIC_ID?>&Is_Thumb=1&SUB_DIR=<?=urlencode($SUB_DIR)?>&FILE_NAME=<?=urlencode($SORT_FILE_ARRAY[$I]['NAME'])?>" title="<?=$SORT_FILE_ARRAY[$I]["NAME"]?>" alt="<?=$SORT_FILE_ARRAY[$I]["NAME"]?>" />
                  <div class="select-mask"></div>
              </a>
          </div>
    <?
          }
        }
      }
    ?>
    </div>
<?
  }  
?>
</div>
</body>
</html>