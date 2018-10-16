<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("图片管理");
include_once("inc/header.inc.php");
?>


<style type="text/css">
	
body {   
     font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;    
     color: #4f6b72;    
     }
span {    
    padding: 0 0 5px 0;             
    font:  18px Bold "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;    
    text-align: center;    
    }  
td {    
    border-right: 1px solid #C1DAD7;    
    border-bottom: 1px solid #C1DAD7;    
    background: #fff;    
    font-size:11px;    
    padding: 6px 6px 6px 12px;    
    color: #4f6b72;
    }   
td.alt {    
    background: #F5FAFA;    
    color: #797268;    
    }
</style>



<body>
<table border="1" width=100% margin=0>
<tr>
	<td colspan=2  class="alt"><span><?=_("图片EXIF信息")?></span></td>
</tr>

<!--图片EXIF信息-->
<tr>
	<td>
<?
$FILE_NAME = iconv('utf-8', MYOA_CHARSET, $FILE_NAME);

if(strtolower(substr($FILE_NAME,-3))!="jpg" && strtolower(substr($FILE_NAME,-4))!="jpeg" && strtolower(substr($FILE_NAME,-3))!="tif" && strtolower(substr($FILE_NAME,-4))!="tiff") 
{
	echo _("图片名称：")."</td><td>".$FILE_NAME."</td></tr>";
  echo "<tr><td colspan=2 class=\"alt\">"._("没有图片EXIF信息");
}
else
{
    $imgtype = array("", "GIF", "JPG", "PNG", "SWF", "PSD", "BMP", "TIFF(intel byte order)", "TIFF(motorola byte order)", "JPC", "JP2", "JPX", "JB2", "SWC", "IFF", "WBMP", "XBM");
    $query = "SELECT PIC_PATH from PICTURE where PIC_ID='$PIC_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $CUR_DIR=$ROW["PIC_PATH"];
    }
    if($SUB_DIR=="")
        $CUR_DIR = $CUR_DIR."/".$FILE_NAME;
    else
        $CUR_DIR = $CUR_DIR."/".$SUB_DIR."/".$FILE_NAME;
    
    $EXIF = exif_read_data (iconv2os($CUR_DIR),"IFD0");
    if ($EXIF===false) 
    {
    	  echo _("图片名称：")."</td><td>".$FILE_NAME."</td></tr>";
        echo "<tr><td colspan=2 class=\"alt\">"._("没有图片EXIF信息");
    }
    else
    {
        $EXIF = exif_read_data (iconv2os($CUR_DIR),0,true);
        echo _("图片名称：")."</td><td>".$FILE_NAME."</td></tr>";
        
        $TEMP = $EXIF[EXIF][DateTimeDigitized];
        $TEMP = explode(" ",$TEMP);
        $TEMP[0] = str_ireplace(":","-",$TEMP[0]);
        
        $GQZ = $EXIF[EXIF][MaxApertureValue]==""?"":"F";
        $PGSJ = $EXIF[EXIF][ExposureTime]==""?"":"s";
        $ISO = $EXIF[EXIF][ISOSpeedRatings]==""?"":"ISO-";
        $JJ = $EXIF[EXIF][FocalLength]==""?"":"mm";
        echo "<tr><td class=\"alt\">"._("拍摄日期：")."</td><td class=\"alt\">".$TEMP[0]." ".$TEMP[1]."</td></tr>";
        echo "<tr><td>"._("文件类型：")."</td><td>".$imgtype[$EXIF[FILE][FileType]]."</td></tr>";
        
        if(floor($EXIF[FILE][FileSize]/1024/1024)>0)
          $FILE_SIZE=round($EXIF[FILE][FileSize]/1024/1024,1)."M";
        else if(floor($EXIF[FILE][FileSize]/1024)>0)
          $FILE_SIZE=round($EXIF[FILE][FileSize]/1024,1)."K";
        else
          $FILE_SIZE=round($EXIF[FILE][FileSize])."B";
        
        echo "<tr><td class=\"alt\">"._("大小：")."</td><td class=\"alt\">".$FILE_SIZE."</td></tr>";
        echo "<tr><td>"._("宽度：")."</td><td>".$EXIF[COMPUTED][Width]._(" 像素")."</td></tr>";
        echo "<tr><td class=\"alt\">"._("高度：")."</td><td class=\"alt\">".$EXIF[COMPUTED][Height]._(" 像素")."</td></tr>";
        echo "<tr><td>"._("相机制造商：")."</td><td>".$EXIF[IFD0][Make]."</td></tr>";
        echo "<tr><td class=\"alt\">"._("相机型号：")."</td><td class=\"alt\">".$EXIF[IFD0][Model]."</td></tr>";
        echo "<tr><td>"._("光圈：")."</td><td>".$EXIF[EXIF][ApertureValue]."</td></tr>";
        echo "<tr><td class=\"alt\">"._("光圈值：")."</td><td class=\"alt\">".$GQZ.$EXIF[EXIF][MaxApertureValue]."</td></tr>";
        echo "<tr><td>"._("曝光时间：")."</td><td>".$EXIF[EXIF][ExposureTime]." ".$PGSJ."</td></tr>";
        echo "<tr><td class=\"alt\">"._("ISO速度：")."</td><td class=\"alt\">".$ISO.$EXIF[EXIF][ISOSpeedRatings]."</td></tr>";
        echo "<tr><td>"._("焦距：")."</td><td>".$EXIF[EXIF][FocalLength]." ".$JJ."</td></tr>";
        echo "<tr><td class=\"alt\">"._("闪光模式：")."</td><td class=\"alt\">".($EXIF[EXIF][ExposureMode]==1?_("手动"):_("自动"))."</td></tr>";
        echo "<tr><td>"._("备注：")."</td><td>".$EXIF[COMPUTED][UserComment];
    }
}
?>
  </td>
</tr>
</table>
</body>
</html>