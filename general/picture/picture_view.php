<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
$HTML_PAGE_TITLE = _("ͼƬ���");
include_once("inc/header.inc.php");

/*
$VIEW_TYPE=="NAME" ���������� 
$VIEW_TYPE=="TYPE" ����������
$VIEW_TYPE=="TIME" ���޸�ʱ�� 
$VIEW_TYPE=="SIZE" ����С����
*/

//�޸���������״̬--yc
update_sms_status('67',$PIC_ID);

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
       $PIC_NAME        = $ROW["PIC_NAME"];
       $PIC_PATH        = $ROW["PIC_PATH"];
       $TD_COUNT        = $ROW["ROW_PIC_NUM"]==0?7:$ROW["ROW_PIC_NUM"];         //ÿ��ͼƬ����
       $ROW_PIC         = $ROW["ROW_PIC"]==0?5:$ROW["ROW_PIC"];
       $ONE_PAGE_PICS   = $TD_COUNT * $ROW_PIC;                                 // ÿҳ�ļ����ļ�������
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

if($dh === FALSE)
{
    Message(_("����"), _("Ŀ¼·������"));
    exit;
}

while(false !== ($FILE_NAME = @readdir($dh)))
{
    $FILE_NAME = iconv2oa($FILE_NAME);
    
    if($FILE_NAME=='.' || $FILE_NAME=='..')
        continue;
    
    //�����ļ�
    $tmp_file_url = iconv2os($CUR_DIR."/".$FILE_NAME);
    if(is_file($tmp_file_url))
    {
        $TEP_TYPE = strtolower(substr(strrchr($FILE_NAME,"."),1));
        
        if($TEP_TYPE=="db" || !find_id($IMG_TYPE_STR,$TEP_TYPE))
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

$url = "picture_view.php?PIC_ID=".$PIC_ID."&SUB_DIR=".$SUB_DIR."&ASC_DESC=".$ASC_DESC."&VIEW_TYPE=".$VIEW_TYPE."&PAGE_NO=".$PAGE_NO;

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
$login_user_prive_other_array = explode(",",$_SESSION["LOGIN_USER_PRIV_OTHER"]);
for($i=0;$i<count($login_user_prive_other_array);$i++){
    if(find_id($PRIV_ROLE,$login_user_prive_other_array[$i])){
        $bool_user_prive_other = true;
    }
    if(find_id($PRIV_ROLE1,$login_user_prive_other_array[$i])){
        $bool_user_prive_other1 = true;
    }
}
$UPLOAD_PRIV = 0;
if(find_id($PRIV_DEPT,"ALL_DEPT") or $PRIV_DEPT=="ALL_DEPT," or find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) or check_dept_other_priv($PRIV_DEPT) or find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"]) or $bool_user_prive_other or find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]))
   $UPLOAD_PRIV = 1;
$DLL_PRIV = 0;
if(find_id($PRIV_DEPT1,"ALL_DEPT") or $PRIV_DEPT1=="ALL_DEPT," or find_id($PRIV_DEPT1,$_SESSION["LOGIN_DEPT_ID"]) or check_dept_other_priv($PRIV_DEPT1) or find_id($PRIV_ROLE1,$_SESSION["LOGIN_USER_PRIV"]) or $bool_user_prive_other1 or find_id($PRIV_USER1,$_SESSION["LOGIN_USER_ID"]))
   $DLL_PRIV = 1;


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

        //��������
        // $("[node-type='btnSort']").click(function(){
        //     imageSort.run('type', $(this).attr("node-data"));
        // });

        // //����ʽ
        // $("[node-type='btnOrder']").click(function(){
        //     imageSort.run('order', $(this).attr("node-data"));
        // });

        //��ť�¼���
        $("[node-type='btnOpts']").click(function(){
            var action = $(this).attr("node-data");
            switch(action)
            {
                case "refresh": 
                    window.location.reload();
                    break;
                case "search":
                    $('#searchModal').modal();
                    break;
                case "batchDown":
                    window.location.href = "picture_batch_down.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=urlencode($SUB_DIR)?>&CUR_DIR=<?=urlencode($CUR_DIR)?>&VIEW_TYPE=<?=$VIEW_TYPE?>&ASC_DESC=<?=$ASC_DESC?>&page=<?=($PAGE_NO-1)?>";
                    break;
                case "manage":
                    window.location.href = "picture.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=urlencode($SUB_DIR)?>&ASC_DESC=<?=$ASC_DESC?>&VIEW_TYPE=<?=$VIEW_TYPE?>&PAGE_NO=<?=$PAGE_NO?>";
            }
        });

        //ͼƬ����
        $("[node-type='imageCellImg']").click(function(){
            var filename = $(this).attr("data-file-name");
            var URL="open_slide.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&URL_FILE_NAME=" + filename + "&VIEW_TYPE=<?=$VIEW_TYPE?>&ASC_DESC=<?=$ASC_DESC?>";
            if(typeof(window.external.OA_SMS) == 'undefined')
            {
                window_width = '950';
                window_height = '664';
                window_top = (screen.availHeight - window_height)/2 - 30;
                window_left = (screen.availWidth - window_width)/2 - 10;
                window.open(URL,"<?=_("ͼƬ���")?>","toolbar=0,status=0,menubar=0,scrollbars=no,resizable=1,width="+window_width+",height="+window_height+",top="+window_top+",left="+window_left);
            }else{
                URL = '/general/picture/'+URL; 
                window.external.OA_SMS(URL, '', 'OPEN_URL');   
            }
        });

    });

    imageBase = {
        PIC_DIR   : '<?=$CUR_DIR?>',
        PIC_PATH  : '<?=$PIC_PATH?>',
        SUB_DIR   : '<?=$SUB_DIR?>',
        LOCATION  : '<?=$LOCATION?>',
        DLL_PRIV  : '<?=$DLL_PRIV?>'
    };

    //����
    imageSort = {
        sort:
        {
            by : '<?=$VIEW_TYPE?>',
            order : '<?=$ASC_DESC?>'
        },
        baseUrl: "picture_view.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=urlencode($SUB_DIR)?>&total=<?=$total?>&page=<?=$page?>&PAGE_NO=<?=$PAGE_NO?>",
        run: function(t, d)
        {
            var directUrl = "";
            if(t == "type")
            {
                directUrl += "&ASC_DESC="+ this.sort.order + "&VIEW_TYPE=" + d;    
            }
            else if(t == "order")
            {
                directUrl += "&ASC_DESC=" + d + "&VIEW_TYPE=" + this.sort.by;
            }
            location.href = this.baseUrl + directUrl;
            
        }
    };

})(jQuery);
</script>

<!-- ��ѯ�ļ��Ի���ʼ -->
<div id="searchModal" class="modal hide fade">
  <form class="form-horizontal" action="picture_search.php" method="get">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3><?=_("ͼƬ�ļ�����")?></h3>
    </div>
    <div class="modal-body">
            <div class="control-group">
                <label class="control-label"><?=_("����·��")?></label>
                <div class="controls">
                    <span class="help-inline"><?=$LOCATION?></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputWord"><?=_("�ļ�����������")?></label>
                <div class="controls">
                    <input type="text" id="inputWord" placeholder="<?=_("�ؼ���")?>" name="file_name">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="selectType"><?=_("�ļ�����")?></label>
                <div class="controls">
                    <select id="selectType" name="file_type">
                        <option value=""><?=_("��ѡ��ͼƬ����")?></option>
                        <option value="gif">*.gif</option>
                        <option value="jpg">*.jpg</option>
                        <option value="png">*.png</option>
                        <option value="swf">*.swf</option>
                        <option value="swc">*.swc</option>
                        <option value="tiff">*.tiff</option>
                        <option value="iff">*.iff</option>
                        <option value="jp2">*.jp2</option>
                        <option value="jpx">*.jpx</option>
                        <option value="jb2">*.jb2</option>
                        <option value="jpc">*.jpc</option>
                        <option value="xbm">*.xbm</option>
                        <option value="wbmp">*.wbmp</option>
                        <option value="">*.*</option>
                    </select>
                </div>
            </div>
            <input type="hidden" name="PIC_ID" value="<?=$PIC_ID?>"><!--�ļ���ID-->
            <input type="hidden" name="PIC_DIR" value="<?=$CUR_DIR?>"><!--�ļ�������·��-->
            <input type="hidden" name="SUB_DIR" value="<?=$SUB_DIR?>"><!--�ļ����ڵ��ļ���Ŀ¼-->
            <input type="hidden" name="PIC_PATH" value="<?=$PIC_PATH?>">
            <input type="hidden" name="LOCATION" value="<?=$LOCATION?>">
            <input type="hidden" name="DLL_PRIV" value="<?=$DLL_PRIV?>">
            <input type="hidden" name="ONE_PAGE_PICS" value="<?=$ONE_PAGE_PICS?>"><!--ÿҳ������-->
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit" node-type="searchImage"><?=_("��ѯ")?></button>
        <button class="btn btn-default" data-dismiss="modal"><?=_("�ر�")?></button>
    </div>
    </form>
</div>
<!-- ��ѯ�ļ��Ի������ -->

<div class="ga-hd clearfix" node-type="imagesNavbar">
    <div class="ga-hd-title">
        <h3 class="yahei"><?=$FOLDER?><small>��<?=$FILE_COUNT > 0 ? $FILE_COUNT : 0 ?><?=_("��")?>��</small></h3>
    </div>
    <div class="ga-hd-optbar">

    <? 
        if($Z > 0)
        {
    ?>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><?=_("����")?><span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                <li><a href="picture_view.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=urlencode($SUB_DIR)?>&total=<?=$total?>&page=<?=$page?>&PAGE_NO=<?=$PAGE_NO?>&VIEW_TYPE=NAME&ASC_DESC=<?=$ASC_DESC?>" node-type="btnSort" node-data="NAME" <?=$VIEW_TYPE == "NAME" ? "class=\"active\"" : ""?>><?=_("������")?></a></li>
                <li><a href="picture_view.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=urlencode($SUB_DIR)?>&total=<?=$total?>&page=<?=$page?>&PAGE_NO=<?=$PAGE_NO?>&VIEW_TYPE=TYPE&ASC_DESC=<?=$ASC_DESC?>" node-type="btnSort" node-data="TYPE" <?=$VIEW_TYPE == "TYPE" ? "class=\"active\"" : ""?>><?=_("������")?></a></li>
                <li><a href="picture_view.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=urlencode($SUB_DIR)?>&total=<?=$total?>&page=<?=$page?>&PAGE_NO=<?=$PAGE_NO?>&VIEW_TYPE=SIZE&ASC_DESC=<?=$ASC_DESC?>" node-type="btnSort" node-data="SIZE" <?=$VIEW_TYPE == "SIZE" ? "class=\"active\"" : ""?>><?=_("����С")?></a></li>
                <li><a href="picture_view.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=urlencode($SUB_DIR)?>&total=<?=$total?>&page=<?=$page?>&PAGE_NO=<?=$PAGE_NO?>&VIEW_TYPE=TIME&ASC_DESC=<?=$ASC_DESC?>" node-type="btnSort" node-data="TIME" <?=$VIEW_TYPE == "TIME" ? "class=\"active\"" : ""?>><?=_("���޸�ʱ��")?></a></li>
                <li class="divider"></li>
                <li><a href="picture_view.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=urlencode($SUB_DIR)?>&total=<?=$total?>&page=<?=$page?>&PAGE_NO=<?=$PAGE_NO?>&VIEW_TYPE=<?=$VIEW_TYPE?>&ASC_DESC=4" node-type="btnOrder" node-data="4" <?=$ASC_DESC == 4 ? "class=\"active\"" : ""?>><?=_("����")?></a></li>
                <li><a href="picture_view.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=urlencode($SUB_DIR)?>&total=<?=$total?>&page=<?=$page?>&PAGE_NO=<?=$PAGE_NO?>&VIEW_TYPE=<?=$VIEW_TYPE?>&ASC_DESC=3" node-type="btnOrder" node-data="3" <?=$ASC_DESC == 3 ? "class=\"active\"" : ""?>><?=_("����")?></a></li>
            </ul>
        </div>
        <button type="button" class="btn btn-default" node-type="btnOpts" node-data="refresh"><i class="icon-refresh"></i><?=_("ˢ��")?></button>
        <button type="button" class="btn btn-default" node-type="btnOpts" node-data="search"><i class="icon-search"></i><?=_("����")?></button>
        <button type="button" class="btn btn-default" node-type="btnOpts" node-data="batchDown"><i class="icon-arrow-down"></i><?=_("��������")?></button>
    <?  } 
        if($DLL_PRIV == 1)
        {
    ?>
            <button type="button" class="btn btn-primary" node-type="btnOpts" node-data="manage"><?=_("��������")?></button>
    <? 
        }
        else if($UPLOAD_PRIV == 1)
        { 
    ?>
            <button type="button" class="btn btn-primary" node-type="btnOpts" node-data="manage"><?=_("�ϴ�")?></button>
    <?
        }
    ?>
        <div class="pagination pull-right">
            <?=get_pagination_html($url, $page, $total);?>
        </div>
    </div>
</div>

<div node-type="imagesContainer">

<?
  if($Z==0)
  {
?>
    <div class="ga-alert-message">
      <div class="alert alert-block">
        <h4><?=_("��ʾ")?></h4>
        <?=_("Ŀ¼Ϊ��")?>
      </div>
    </div>
<?
  }
  else
  {
      $width = ($TD_COUNT*165)."px";
?>
    <div class="ga-bd clearfix" style="width: <?=$width;?>">
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
          <div class="image-cell image-cell-wh50" node-type="imageCell">
              <a href="javascript:;" node-type="imageCellImg" data-file-name="<?=urlencode($SORT_FILE_ARRAY[$I]['NAME'])?>">
                  <img src="header.php?PIC_ID=<?=$PIC_ID?>&Is_Thumb=1&SUB_DIR=<?=urlencode($SUB_DIR)?>&FILE_NAME=<?=urlencode($SORT_FILE_ARRAY[$I]['NAME'])?>" title="<?=$SORT_FILE_ARRAY[$I]["NAME"]?>" alt="<?=$SORT_FILE_ARRAY[$I]["NAME"]?>" node-image-tips="<?=$SORT_FILE_ARRAY[$I]["NAME"]?>"/>
                  <div class="opacity-mask"></div>
              </a>
              <div class="image-cell-tips-wrapper">
                  <div class="image-cell-tips-bg"></div>
                  <em><?=$SORT_FILE_ARRAY[$I]["NAME"]?></em>
              </div>
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