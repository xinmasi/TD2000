<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_all.php");
include_once('inc/utility_org.php');
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("ͼƬ���");
include_once("inc/header.inc.php");

$TABLE_WIDTH=100;  //ͼƬ�����
$TABLE_HEIGHT=120;  //ͼƬ���߶�

//Ĭ�ϵ�ͼƬ���
$MEDIUM_IMAGE_SIZE = array(
    'w' => 1024,
    'h' => 768
);

//�޸���������״̬--yc
update_sms_status('67',$PIC_ID);

$IMG_TYPE_STR="gif,jpg,png,swf,swc,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp,tif"; //�����ļ�����
if($ASC_DESC=="") //4����  3����
    $ASC_DESC=4;
/*
$VIEW_TYPE=="NAME" ���������� $VIEW_TYPE=="TYPE" ����������
$VIEW_TYPE=="TIME" ���޸�ʱ�� $VIEW_TYPE=="SIZE" ����С����
*/
if($VIEW_TYPE=="")
    $VIEW_TYPE="NAME";

if($SUB_DIR!="")
{
    $SUB_DIR=urldecode($SUB_DIR);
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
//  echo $TD_COUNT.";". $ONE_PAGE_PICS;
//��ֹ�ϴ�php�ļ� $UP_LOAD_FLAG=1 ��ֹ�ϴ�
$UP_LOAD_FLAG=2;
if($ACTION=="upload")
{
    $UP_LOAD_FLAG=0;

    $MY_FILENAME = $_FILES["ATTACHMENT"]["name"];
    $EXT_NAME=substr(strrchr($_FILES["ATTACHMENT"]["name"], "."), 1);
    $EXT_NAME=strtolower($EXT_NAME);

    if(!find_id($IMG_TYPE_STR,$EXT_NAME))
    {
        $UP_LOAD_FLAG=1;
    }

    $dh1 = @opendir(iconv2os($CUR_DIR));
    while(false !== ($FILE_NAME1 = @readdir($dh1)))
    {
        $FILE_NAME1 = iconv2oa($FILE_NAME1);
        if($FILE_NAME1=='.' || $FILE_NAME1=='..')
            continue;
        if($MY_FILENAME==$FILE_NAME1)
        {
            $UP_LOAD_FLAG=5;
        }
    }
    if(stristr($EXT_NAME,".php"))
        $UP_LOAD_FLAG=1;

    if(strstr($MY_FILENAME,"/") || strstr($MY_FILENAME,"\\") || strstr($MY_FILENAME,"..") || strstr($MY_FILENAME,"!") || strstr($MY_FILENAME,"@")|| strstr($MY_FILENAME,"#") || strstr($MY_FILENAME,"$") || strstr($MY_FILENAME,"%") || strstr($MY_FILENAME,"^") || strstr($MY_FILENAME,"&") || strstr($MY_FILENAME,"*") || strstr($MY_FILENAME,"(") || strstr($MY_FILENAME,")") || strstr($MY_FILENAME,"{") || strstr($MY_FILENAME,"}") || strstr($MY_FILENAME,"[") || strstr($MY_FILENAME,"]"))
    {
        Message(_("����"),_("�������зǷ��ַ���"));
        Button_Back();
        exit;
    }
    $query="select PARA_VALUE from SYS_PARA where PARA_NAME='SMS_REMIND'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $PARA_VALUE=$ROW["PARA_VALUE"];
    $SMS_PRIV_ARRAY=explode("|",$PARA_VALUE);

    if(find_id($SMS_PRIV_ARRAY[2],"67") && $UP_LOAD_FLAG!=1 && $UP_LOAD_FLAG!=5)
    {
        $REMIND_URL="picture/picture_view.php?SUB_DIR=".urlencode($SUB_DIR)."&PIC_ID=".$PIC_ID."&CUR_DIR=".urlencode($CUR_DIR);
        $query="select TO_DEPT_ID,TO_PRIV_ID,TO_USER_ID from PICTURE where PIC_ID='$PIC_ID'";
        $cursor=exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $TO_DEPT_ID=$ROW["TO_DEPT_ID"];
            $TO_PRIV_ID=$ROW["TO_PRIV_ID"];
            $TO_USER_ID=$ROW["TO_USER_ID"];
        }

        $USER_ID_STR="";
        $query="select USER_ID from USER where NOT_LOGIN='0'";
        if($TO_DEPT_ID!="ALL_DEPT")
            $query.=" and (find_in_set(DEPT_ID,'$TO_DEPT_ID') or find_in_set(USER_PRIV,'$TO_PRIV_ID') or find_in_set(USER_ID,'$TO_USER_ID'))";
        $cursor=exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
            $USER_ID_STR.=$ROW["USER_ID"].",";

        $SMS_CONTENT=sprintf(_("%s�ϴ�ͼƬ��ͼƬ��Ϊ��%s"),$_SESSION["LOGIN_USER_NAME"],$_FILES["ATTACHMENT"]["name"]);
        if($USER_ID_STR!="")
            send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,67,$SMS_CONTENT,$REMIND_URL,$PIC_ID);
    }
    $ATTACHMENT = $_FILES["ATTACHMENT"]["tmp_name"];
    if($UP_LOAD_FLAG!=1)
    {
        $ATTACHMENT_DEST=str_replace("//","/",$CUR_DIR."/".$MY_FILENAME);
        if(!td_copy(iconv2os($ATTACHMENT),iconv2os($ATTACHMENT_DEST)));
    }
    echo "<script>location.href='picture.php?PIC_ID=".$PIC_ID."&SUB_DIR=".$SUB_DIR."&ASC_DESC=".$ASC_DESC."&VIEW_TYPE=".$VIEW_TYPE."&PAGE_NO=".$PAGE_NO."'</script>";
}


?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css"/>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="js/pic_control.js"></script>

<style>
    a.pic_border{height: 80px;width: 80px;display: block;}
    a.pic_border:link {border:3px solid #FFFFFF;}     /* δ���ʵ����� */
    a.pic_border:visited {border:3px solid #FFFFFF;}  /* �ѷ��ʵ����� */
    a.pic_border:hover {border:3px solid #4BAEE9;}    /* ���������ͣ�������� */
    a.pic_border:active {border:3px solid #4BAEE9;}   /* ��ѡ������� */
    .navbar{
        position:absolute;
        top:10px;
        right:5%;
        text-align:right;
        width:500px;
        word-wrap:break-word;
        font-size: 12px;
    }
</style>
<script>
    jQuery.noConflict();
    (function($){
        $(document).ready(function(){
            $('#tree_img').hover(
                function(){
                    var className = $(this).attr('class');
                    if(className.indexOf('-active') < 0)
                        $(this).attr('class', className + '-active');
                },
                function(){
                    var className = $(this).attr('class');
                    if(className.indexOf('-active') > 0)
                        $(this).attr('class', className.substr(0, className.length-7));
                }
            );
        });
    })(jQuery);
</script>

<div id="overlay"></div>
<!--//�½��ļ���-->
<div id="id_folder_new" class="ModalDialog" style="width:339px;">
    <div class="header"><span class="title"><?=_("�½��ļ���")?></span><a class="operation" href="javascript:HideDialog('id_folder_new');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
    <div style="padding: 1em 1em;">
        <table class="TableBlock" width="90%" align="center">

            <form action="add_folder.php"  method="post" name="form">
                <tr class="TableData">
                    <td width="100"><?=_("�ļ�������")?></td>
                    <td><input type="text" class="BigInput" size="20" name="FILE_NAME" value=""></td>
                </tr>
                <tr align="center" class="TableControl">
                    <td colspan="5" nowrap>
                        <input type="hidden" name="CUR_DIR_RELAT" value="<?=urlencode($SUB_DIR)?>">
                        <input type="hidden" name="PIC_ID" value="<?=$PIC_ID?>">
                        <input type="hidden" name="SUB_DIR" value="<?=urlencode($SUBDIR)?>">
                        <input class="BigButton" type="submit" value="<?=_("����")?>"/>
                        <input class="BigButton" onClick="HideDialog('id_folder_new')" type="button" value="<?=_("�ر�")?>"/>
                    </td>
                </tr>
            </form>

        </table>      </div>
</div>
<!--//�������ļ���-->
<div id="id_folder_rename" class="ModalDialog" style="width:339px;">
    <div class="header"><span class="title"><?=_("�������ļ���")?></span><a class="operation" href="javascript:HideDialog('id_folder_rename');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
    <div style="padding: 1em 1em;">
        <form action="update_name.php"  method="post" name="form3">
            <table class="TableBlock" width="90%" align="center">
                <tr class="TableData">
                    <td width="80"><?=_("���ļ�������")?></td>
                    <td><input type="text" class="BigInput" size="20" name="NEW_FOLDER_NAME"></td>
                </tr>
                <tr align="center" class="TableControl">
                    <td colspan="5" nowrap>
                        <input class="BigButton" type="submit" value="<?=_("����")?>"/>
                        <input class="BigButton" onClick="HideDialog('id_folder_rename')" type="button" value="<?=_("�ر�")?>"/>
                        <input type="hidden" name="CUR_DIR_RELAT" value="<?=urlencode($SUB_DIR)?>">
                        <input type="hidden" name="PIC_ID" value="<?=$PIC_ID?>">
                        <input type="hidden" name="SUB_DIR" value="<?=urlencode($SUBDIR)?>">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
</div>
<?
//ҳ�� ��ʼ��
if($PAGE_NO=="")
    $PAGE_NO=1;
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
                    else
                        td_copy($NOW_FILE_DIR1, $NOW_FILE_DIR2);
            }
        }

        //�����е�ͼƬmedium lp 2013/10/17 15:31:49
        $TEMP_MEDIUM_PATH = $CUR_DIR."/tdoa_cache/medium_".$FILE_NAME; //��ǰ�ļ��е�ͼƬ
        if(!file_exists($TEMP_MEDIUM_PATH))
        {
            $FILE_TYPE=substr(strrchr($FILE_NAME, "."), 1);
            $FILE_TYPE=strtolower($FILE_TYPE);

            if(find_id($IMG_TYPE_STR,$FILE_TYPE))
            {
                $DEFAULT_DIR=iconv2os($CUR_DIR);
                if(!file_exists($DEFAULT_DIR))
                    mkdir($DEFAULT_DIR);

                $NOW_FILE_DIR=$CUR_DIR."/".$FILE_NAME;
                $NOW_FILE_DIR=str_replace("//","/",$NOW_FILE_DIR);

                if ($FILE_TYPE!="bmp")
                    CreateThumb($NOW_FILE_DIR,$MEDIUM_IMAGE_SIZE['w'],$MEDIUM_IMAGE_SIZE['h'],iconv2oa($TEMP_MEDIUM_PATH));
                else
                    td_copy($NOW_FILE_DIR, $TEMP_MEDIUM_PATH);
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
//print_r($SORT_FILE_ARRAY);
?>

<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small" style="margin: 0 auto">
    <form action="picture.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=urlencode($SUB_DIR)?>" method="post" name="form1">
        <tr>
            <td class="Big">
                <a id="tree_img" href="javascript:hide_tree();" class="scroll-left" title="<?=_("����Ŀ¼��")?>"></a>
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/picture.gif" align="absBottom"><span class="big3"> <?=_("ͼƬ���")?></span>
                &nbsp;&nbsp;
                <?
                if($FILE_COUNT!=0){
                    ?>
                    <select class="BigSelect" name="VIEW_TYPE" id="VIEW_TYPE" onChange="set_view_type();">
                        <option value="NAME" <? if($VIEW_TYPE=="NAME") echo "selected";?>><?=_("����������")?></option>
                        <option value="TYPE" <? if($VIEW_TYPE=="TYPE") echo "selected";?>><?=_("����������")?></option>
                        <option value="TIME" <? if($VIEW_TYPE=="TIME") echo "selected";?>><?=_("���޸�ʱ��")?></option>
                        <option value="SIZE" <? if($VIEW_TYPE=="SIZE") echo "selected";?>><?=_("����С����")?></option>
                    </select>
                    <select class="BigSelect" name="ASC_DESC" id="ASC_DESC" onChange="set_view_type();">
                        <option value=4 <? if($ASC_DESC==4) echo "selected";?>><?=_("����")?></option>
                        <option value=3 <? if($ASC_DESC==3) echo "selected";?>><?=_("����")?></option>
                    </select>
                    <?
                }
                //��ȡ��ǰ·��
                if($SUB_DIR=="")
                    $LOCATION=$PIC_NAME;
                else
                    $LOCATION=$PIC_NAME."/".$SUB_DIR;
                $LOCATION1=str_replace("/","\\",$LOCATION);
                ?>

            </td>
        </tr>
    </form>
</table>

<table class="TableBlock no-top-border" align="center" width="90%">
    <tr>
        <td class="TableHeader" colspan="999" style="text-align:left;">
            <b><?=_("��ǰλ�ã�")?></b><?=$LOCATION1?>
        </td>
    </tr>
    <?
    //------------------------��ʾ-------------------------
    //����
    //print_r($SORT_FILE_ARRAY);
    $WRAP_COUNT=0;
    //��ҳ
    $B=$ONE_PAGE_PICS*($PAGE_NO-1) + 1;
    if($ONE_PAGE_PICS*$PAGE_NO < $Z)
        $E=$ONE_PAGE_PICS*$PAGE_NO;
    else if($Z%$TD_COUNT!=0)
        $E=$Z + $TD_COUNT - $Z%$TD_COUNT;
    else
        $E=$Z;

    if($Z==0)
    {
        $E = 0;
        Message(_("��ʾ"),_("Ŀ¼Ϊ��"));
    }

    //��ʾ���ļ�����
    for($I=$B;$I <= $E;$I++)
    {
        $WRAP_COUNT++;

        //�����ļ�������
        if(strlen(csubstr($SORT_FILE_ARRAY[$I]["NAME"],0,strrpos($SORT_FILE_ARRAY[$I]["NAME"], "."))) >= 8)
            $CSUB_SF_NAME = csubstr(csubstr($SORT_FILE_ARRAY[$I]["NAME"],0,strrpos($SORT_FILE_ARRAY[$I]["NAME"], ".")),0,8)."...";
        else
            $CSUB_SF_NAME = $SORT_FILE_ARRAY[$I]["NAME"];

        //����
        if(($WRAP_COUNT-1)%$TD_COUNT==0)
            echo "<tr align=\"center\" bgcolor=\"#FFFFFF\" valign=\"middle\" width=\"90%\"><div>";

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
                        //CreateThumb(iconv2os($NOW_FILE_DIR),80,80,iconv2os($TEMP_FILE_DIR));
                        crop(iconv2os($NOW_FILE_DIR),150,150,1,iconv2oa($TEMP_FILE_DIR));   //
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

                $width_every=100/$TD_COUNT;
                echo "<td align=\"center\" valign=\"middle\" width=\"$width_every%\" height=\"$TABLE_HEIGHT\">";
                //if($FILE_TYPE!="bmp")
                echo "<A class=\"pic_border\" href=\"javascript:open_pic($PIC_ID,'$SUB_DIR','".$SORT_FILE_ARRAY[$I]["NAME"]."');\">
               <img src=\"header.php?PIC_ID=$PIC_ID&Is_Thumb=1&SUB_DIR=".urlencode($SUB_DIR)."&FILE_NAME=".urlencode($SORT_FILE_ARRAY[$I]["NAME"])."\" width=\"80\" height=\"80\" border=\"0\" title=\""._("�ļ���:").$SORT_FILE_ARRAY[$I]["NAME"]."\n"._("��С:").number_format($SORT_FILE_ARRAY[$I]["SIZE"],0, ".",",")._("�ֽ�")."\n"._("�޸�����:").$SORT_FILE_ARRAY[$I]["TIME"]."\"></A>
               <span><A href=\"down.php?PIC_ID=$PIC_ID&SUB_DIR=".urlencode($SUB_DIR)."&FILE_NAME=".urlencode($SORT_FILE_ARRAY[$I]["NAME"])."\" title=\""._("�ļ���:").$SORT_FILE_ARRAY[$I]["NAME"]."\n"._("��С:").number_format($SORT_FILE_ARRAY[$I]["SIZE"],0, ".",",")._("�ֽ�")."\n"._("�޸�����:").$SORT_FILE_ARRAY[$I]["TIME"]."\">
               ".$CSUB_SF_NAME."</A>
               <input type=\"checkbox\" name=\"CHECK_$I\" id=\"CHECK_$I\" title=\"".$SORT_FILE_ARRAY[$I]["NAME"]."\" onclick=\"file_name_add('".$SORT_FILE_ARRAY[$I]["NAME"]."','CHECK_$I')\"></span>
               ";
                /*else
                   echo "<A class=\"pic_border\" href=\"javascript:open_pic($PIC_ID,'$SUB_DIR','".$SORT_FILE_ARRAY[$I]["NAME"]."');\">
                      <img src=\"<?=MYOA_STATIC_SERVER?>/static/images/unknown.gif\" border=\"0\" title=\""._("�ļ���:").$SORT_FILE_ARRAY[$I]["NAME"]."\n"._("��С:").number_format($SORT_FILE_ARRAY[$I]["SIZE"],0,".",",")._("�ֽ�")."\n"._("�޸�����:").$SORT_FILE_ARRAY[$I]["TIME"]."\"></A><br />
                      <span><A href=\"down.php?PIC_ID=$PIC_ID&SUB_DIR=".urlencode($SUB_DIR)."&FILE_NAME=".urlencode($SORT_FILE_ARRAY[$I]["NAME"])."\" title=\""._("�ļ���:").$SORT_FILE_ARRAY[$I]["NAME"]."\n"._("��С:").number_format($SORT_FILE_ARRAY[$I]["SIZE"],0, ".",",")._("�ֽ�")."\n"._("�޸�����:").$SORT_FILE_ARRAY[$I]["TIME"]."\">
                      ".$CSUB_SF_NAME."</A>
                      <input type=\"checkbox\" name=\"CHECK_$I\" id=\"CHECK_$I\" title=\"".$SORT_FILE_ARRAY[$I]["NAME"]."\" onclick=\"file_name_add('".$SORT_FILE_ARRAY[$I]["NAME"]."','CHECK_$I')\"></span>
                      ";*/
                echo "</td>";
                //�����ļ�
            }
            else
            {
                echo "<td align=\"center\" valign=\"middle\" width=\"$TABLE_WIDTH\" height=\"$TABLE_HEIGHT\">";
                echo "<A class=\"pic_border\" href=\"down.php?PIC_ID=".$PIC_ID."&SUB_DIR=".urlencode($SUB_DIR)."&FILE_NAME=".urlencode($SORT_FILE_ARRAY[$I]["NAME"])."\">
               <img src=\"<?=MYOA_STATIC_SERVER?>/static/images/unknown.gif\" border=\"0\" title=\""._("�ļ���:").$SORT_FILE_ARRAY[$I]["NAME"]."\n"._("��С:").number_format($SORT_FILE_ARRAY[$I]["SIZE"],0,".",",")._("�ֽ�")."\n"._("�޸�����:").$SORT_FILE_ARRAY[$I]["TIME"]."\"></A><br />
               <A href=\"down.php?PIC_ID=$PIC_ID&SUB_DIR=".urlencode($SUB_DIR)."&FILE_NAME=".urlencode($SORT_FILE_ARRAY[$I]["NAME"])."\" title=\""._("�ļ���:").$SORT_FILE_ARRAY[$I]["NAME"]."\n"._("��С:").number_format($SORT_FILE_ARRAY[$I]["SIZE"],0, ".",",")._("�ֽ�")."\n"._("�޸�����:").$SORT_FILE_ARRAY[$I]["TIME"]."\">
               ".$CSUB_SF_NAME."</A>
               <input type=\"checkbox\" name=\"CHECK_$I\" id=\"CHECK_$I\" title=\"".$SORT_FILE_ARRAY[$I]["NAME"]."\" onclick=\"file_name_add('".$SORT_FILE_ARRAY[$I]["NAME"]."','CHECK_$I')\">
               ";
                echo "</td>";
            }
            //Ϊ��
        }
        else
        {
            echo "<td align=\"center\" valign=\"top\" width=\"$TABLE_WIDTH\" height=\"$TABLE_HEIGHT\">&nbsp;</td>";
        }
        echo "</div>";
    }

    ?>
    </tr>
</table>
<?
if($ONE_PAGE_PICS=="" || $ONE_PAGE_PICS==0)
    $ALL_PAGES = 1;
else
    $ALL_PAGES = ceil($Z/$ONE_PAGE_PICS);
for($k=1;$k<=$ALL_PAGES;$k++)
{
    if($k!=$PAGE_NO)
    {
        if($k==1)
            $PAGE_HTML .= "<a href='picture.php?PIC_ID=$PIC_ID&SUB_DIR=$SUB_DIR&ASC_DESC=$ASC_DESC&VIEW_TYPE=$VIEW_TYPE'>[$k]</a>&nbsp;";
        else
            $PAGE_HTML .= "<a href='picture.php?PIC_ID=$PIC_ID&SUB_DIR=$SUB_DIR&ASC_DESC=$ASC_DESC&VIEW_TYPE=$VIEW_TYPE&PAGE_NO=$k&PAGE_ON=$k'>[$k]</a>&nbsp;";
    }
    else
        $PAGE_HTML .= "$k&nbsp;";
}
if($FILE_COUNT!=0)
    if($ALL_PAGES > 0)
        echo "<div class=\"navbar\">"._("ҳ��").$PAGE_HTML."</div>";

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
?>
<div id="fsUploadArea" class="flash" style="width:<?=$TD_COUNT*($TABLE_WIDTH+7)?>px;text-align:left;margin-left: 5%;">
    <div id="fsUploadProgress"></div>
    <div id="totalStatics"></div>
    <div>
        <input type="button" id="btnStart" class="SmallButton" value="<?=_("��ʼ�ϴ�")?>" onClick="swfupload.startUpload();" disabled="disabled">&nbsp;&nbsp;
        <input type="button" id="btnCancel" class="SmallButton" value="<?=_("ȫ��ȡ��")?>" onClick="swfupload.cancelQueue();" disabled="disabled">&nbsp;&nbsp;
        <input type="button" class="SmallButton" value="<?=_("ˢ��ҳ��")?>" onClick="window.location.reload();">
    </div>
</div>
<table class="TableBlock" width="90%" style="margin: 0 auto;margin-top:20px;">
    <tr>
        <td class="TableContent" nowrap align="right" width="100"><b><?=_("�ļ�������")?></b></td>
        <td class="TableData">
            <div style="display:inline;">
                <div style="float:left;padding-top:4px;">
                    &nbsp;<input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for" id="allboxlabel"><?=_("ȫѡ")?></label>
                    &nbsp;<a href="javascript:window.location='picture.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=urldecode($SUB_DIR)?>&PAGE_NO=<?=$PAGE_NO?>'"><img src="<?=MYOA_STATIC_SERVER?>/static/images/refresh.gif" align="absBottom" border="0">&nbsp;<?=_("ˢ��")?></a>
                    &nbsp;<a href="javascript:pic_search();"><img src="<?=MYOA_STATIC_SERVER?>/static/images/folder_search.gif" align="absBottom" border="0"><?=_("����")?></a>
                    <?
                    if($DLL_PRIV==1)
                    {
                        ?>
                        &nbsp;<a href="javascript:;pic_paste('','copy','<?=$PIC_ID?>','<?=$SUB_DIR?>','<?=$PIC_PATH?>','<?=$CUR_DIR?>')"><img src="<?=MYOA_STATIC_SERVER?>/static/images/copy.gif" align="absMiddle" border="0" title="<?=_("���ƴ��ļ�")?>"><?=_("����")?></a>&nbsp;&nbsp;<a href="javascript:;pic_paste('','cut','<?=$PIC_ID?>','<?=$SUB_DIR?>','<?=$PIC_PATH?>','<?=$CUR_DIR?>')"><img src="<?=MYOA_STATIC_SERVER?>/static/images/cut.gif" align="absMiddle" border="0" title="<?=_("���д��ļ�")?>"><?=_("����")?></a>
                        <span id="paste_file" style="display:<?=urldecode($_COOKIE['pic_filename'])==''?'none':'';?>;">&nbsp;<a href="pic_paste.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&PIC_PATH=<?=$PIC_PATH?>&PIC_DIR=<?=$CUR_DIR?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/paste.gif" align="absMiddle" border="0" title="<?=_("ճ��")?>"><?=_("ճ��")?></a></span>
                        &nbsp;<a href="javascript:picdelete('<?=$PIC_ID?>','<?=$SUB_DIR?>','<?=$PIC_PATH?>','<?=$LOCATION?>');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absBottom" border="0">&nbsp;<?=_("ɾ��")?></a>
                        &nbsp;<a href="javascript:do_rename('<?=$SUB_DIR?>','<?=$PIC_PATH?>');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/folder_edit.gif" align="absBottom" border="0" title="<?=_("��������ͼƬ")?>"><?=_("������ͼƬ")?></a>&nbsp;

                        <?
                    }
                    ?>
                    &nbsp;<a href="javascript:do_action('<?=$SUB_DIR?>','<?=$PIC_PATH?>');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/download.gif" align="absBottom" border="0" title="<?=_("����ѹ��������")?>"><span id="label_down"><?=_("��������")?></span></a></td></tr>
    <tr>
        <td class="TableContent" nowrap align="right" width="100"><b><?=_("�ļ��в�����")?></b></td>
        <td class="TableData">
            <div style="display:inline;">
                <div style="float:left;padding-top:4px;">
                    <?
                    if($UPLOAD_PRIV==1 || $DLL_PRIV==1)
                    {
                        ?>
                        &nbsp;<a href="javascript:void(0);" onClick="ShowDialog('id_folder_new');form.FILE_NAME.focus();"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absBottom" border="0"><?=_("�½��ļ���")?></a>
                        <?
                    }
                    if($SUB_DIR!="" && $DLL_PRIV==1)
                    {
                        ?>
                        &nbsp;<a href="javascript:void(0);" onClick="ShowDialog('id_folder_rename');form3.NEW_FOLDER_NAME.focus();"><img src="<?=MYOA_STATIC_SERVER?>/static/images/folder_edit.gif" align="absBottom" border="0"><?=_("���������ļ���")?></a>
                        &nbsp;<a href="javascript:DelFolder();"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absBottom" border="0"><?=_("ɾ�����ļ���")?></a>
                        <?
                    }
                    ?>
                </div>
            </div>
        </td>
    </tr>
    <?
    if($UPLOAD_PRIV==1)
    {
        ?>
        <tr>
            <td class="TableContent" nowrap align="right" width="100"><b><?=_("�ϴ�����ͼƬ��")?></b></td>
            <td class="TableData">
                <form name="form2" method="post" action="picture.php" enctype="multipart/form-data" onSubmit="return CheckForm()">
                    <input type="file" name="ATTACHMENT" size="30" class="BigInput">
                    <INPUT TYPE="hidden" name="ACTION" value="upload">
                    <INPUT TYPE="hidden" name="PIC_ID" value="<?=$PIC_ID?>">
                    <INPUT TYPE="hidden" name="SUB_DIR" value="<?=$SUB_DIR?>">
                    <input type="button" class="SmallButton" value="<?=_("�ϴ�����ͼƬ")?>" onClick="upload_pic();">
                </form>
            </td>
        </tr>
        <tr>
            <td class="TableContent" nowrap align="right" width="100"><b><?=_("�ϴ�����ͼƬ��")?></b></td>
            <td class="TableData">
                <span id="spanButtonUpload" title="<?=_("�����ϴ�")?>"></span>&nbsp;
            </td>
        </tr>
        <?
    }
    ?>
</table>
<?
if($UP_LOAD_FLAG==1)
    Message(_("����"),_("���ļ����ͱ���ֹ�ϴ���"));
else if($UP_LOAD_FLAG==0)
    Message(_("��ʾ"),_("�ϴ�����ͼƬ�ɹ���"));
else if($UP_LOAD_FLAG==5)
    Message(_("����"),_("�ļ����Ѵ��ڣ��������������ϴ���"));
?>
<br>
<br>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/swfupload.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/fileprogress.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/handlers.js"></script>
<script type="text/javascript">
    var upload_limit=oa_upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type=oa_limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
    var swfupload;
    <?
    if($UPLOAD_PRIV==1)
    {
    ?>
    window.onload = function() {
        var linkColor = document.linkColor;
        for(var i=0; i< document.styleSheets.length; i++)
        {
            var stylesheet = document.styleSheets[i];
            var rules=stylesheet.rules ? stylesheet.rules : stylesheet.cssRules;
            if(!rules) return;

            for(var j=0;j<rules.length;j++)
            {
                if(rules[j].selectorText.toLowerCase()=="a:link")
                {
                    linkColor = rules[j].style.color;
                    break;
                }
            }

            if(linkColor != document.linkColor)
                break;
        }

        var settings = {
            flash_url : "<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.swf",
            upload_url: "upload.php",
            post_params: {"PIC_ID": "<?=$PIC_ID?>","SUB_DIR":"<?=urlencode($SUB_DIR)?>","PHPSESSID": "<?=session_id();?>"},
            file_size_limit : "<?=intval(ini_get('upload_max_filesize'))?> MB",
            file_types : "*.gif;*.jpg;*.jpeg;*.png;*.bmp;*.iff;*.jp2;*.jpx;*.jb2;*.jpc;*.xbm;*.wbmp",
            file_types_description : "<?=_("����ͼƬ�ļ�")?>",
            file_upload_limit : 0,
            file_queue_limit : 0,
            custom_settings : {
                uploadArea : "fsUploadArea",
                progressTarget : "fsUploadProgress",
                startButtonId : "btnStart",
                cancelButtonId : "btnCancel"
            },
            debug: false,

            // Button Settings
            button_image_url : "<?=MYOA_STATIC_SERVER?>/static/images/uploadx4.gif",	// Relative to the SWF file
            button_text : "<span class=\"textUpload\"><?=_("�����ϴ�")?></span>",
            button_text_style : ".textUpload{color:"+linkColor+";}",
            button_text_top_padding : 1,
            button_text_left_padding : 18,
            button_placeholder_id : "spanButtonUpload",
            button_width: 70,
            button_height: 18,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,

            // The event handler functions are defined in handlers.js
            file_queued_handler : fileQueued,
            file_queue_error_handler : fileQueueError,
            file_dialog_complete_handler : fileDialogComplete,
            upload_start_handler : uploadStart,
            upload_progress_handler : uploadProgress,
            upload_error_handler : uploadError,
            upload_success_handler : uploadSuccess,
            upload_complete_handler : uploadComplete,
            //queue_complete_handler : queueComplete	// Queue plugin event
            queue_complete_handler : queueCompleteEventHandler

        };

        swfupload = new SWFUpload(settings);
    };
    function queueCompleteEventHandler() {
        window.location.reload();
    }
    <?
    }
    ?>

    function open_pic(pic_id,sub_dir,file_name)
    {
        aWidth=screen.availWidth-10;
        aHeight=screen.availHeight-100;

        window_top=0;
        window_left=0;
        window_width=aWidth;
        window_height=aHeight;

        var VIEW_TYPE = document.getElementById('VIEW_TYPE').value;
        var ASC_DESC = document.getElementById('ASC_DESC').value;
        file_name = encodeURIComponent(file_name);
        URL="open.php?PIC_ID="+pic_id+"&SUB_DIR="+sub_dir+"&URL_FILE_NAME=" + file_name +"&VIEW_TYPE="+VIEW_TYPE+"&ASC_DESC="+ASC_DESC;
        window.open(URL,"<?=_("ͼƬ���")?>","toolbar=0,status=0,menubar=0,scrollbars=no,resizable=1,width="+window_width+",height="+window_height+",top="+window_top+",left="+window_left);
    }

    function set_view_type()
    {
        document.form1.submit();
    }
    function CheckForm()
    {
        if(document.form2.ATTACHMENT.value!="")
        {
            var path=document.form2.ATTACHMENT.value;
            var filename=path.substring(path.lastIndexOf("\\")+1);
            var slide_filename=new Array(); //�ļ�������
            var max_counter=<?=$FILE_COUNT?>;
            <?

            for($TEMP_I = 0;$TEMP_I < $FILE_COUNT;$TEMP_I++)
            {
               echo "slide_filename[$TEMP_I] = '".urldecode($FILE_ATTR_ARRAY[$TEMP_I]['NAME'])."';\n\r";
            }
            ?>
            tag=0;
            for(i=0;i<slide_filename.length;i++)
            {
                if(filename==slide_filename[i])
                {
                    tag=1;
                }
            }
            if(tag==0)
            {
                var file_temp=document.form2.ATTACHMENT.value,file_temp_name;
                var Pos;
                Pos=file_temp.lastIndexOf("\\");
                file_temp_name=file_temp.substring(Pos+1,file_temp.length);
                return true;
            }
            else
            {
                alert("<?=_("���ļ����Ѵ��ڣ�")?>");
                return false;
            }

        }
        else
        {
            alert("<?=_("��ָ���ϴ�ͼƬ��")?>");
            return false;
        }
    }

    function upload_pic()
    {
        if(CheckForm())
            document.form2.submit();
    }

    function check_all()
    {
        var getCK = document.getElementsByTagName('input');
        var allCKbox = document.getElementById('allbox_for');
        var chk_status;
        var show_str;
        if(allCKbox.checked ===false)
        {
            chk_status = false;
            show_str = "<?=_("ȫѡ")?>";
            TmpFileNameStr="";
        }else{
            chk_status = true;
            show_str = "<?=_("ȫ��ѡ")?>";
        }

        for(var i=0;i < getCK.length;i++)
        {
            whichObj=getCK[i];

            if(whichObj.type=="checkbox")
            {
                whichObj.checked=chk_status;
                if(whichObj.name!="allbox" && whichObj.checked===true)
                {
                    // var str = whichObj.onclick.toString();
                    // //var isIE8 = (navigator.userAgent.indexOf("MSIE 8.")!= -1)
                    // //if(isIE8)
                    // var str1 = str.replace("function onclick()","");
                    // //else
                    // //var str1 = str.replace("function anonymous()","");
                    // var str2 = str1.replace("{","");
                    // var str3 = str2.replace("}","");

                    //eval(str3);
                    whichObj.onclick();
                }
            }
        }


        document.getElementById('allboxlabel').innerText=show_str;
    }
    function DelFolder()
    {
        msg="<?=_("ȷ��Ҫɾ�����ļ�����?���ļ����µ��ļ���ͬʱ��ɾ����")?>";
        if(window.confirm(msg))
        {
            URL="folder_delete.php?CUR_DIR_RELAT=<?=urlencode($SUB_DIR)?>&SUB_DIR=<?=urlencode($SUBDIR)?>&PIC_ID=<?=$PIC_ID?>";
            window.location=URL;
        }
    }

    //ͼƬ��ѯ
    function pic_search()
    {
        parent.list.location="pic_query.php?PIC_DIR=<?=$CUR_DIR?>&PIC_PATH=<?=$PIC_PATH?>&PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&LOCATION=<?=$LOCATION?>&DLL_PRIV=<?=$DLL_PRIV?>";
    }
</script>
</body>
</html>
