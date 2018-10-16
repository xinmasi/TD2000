<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
include_once("inc/utility_folder.php");
$PROJ_ID=intval($PROJ_ID);

$SUBJECT = splitStr($SUBJECT,"%");

$QUERY = "SELECT A.*,B.* FROM PROJ_FILE AS A,PROJ_FILE_SORT AS B WHERE A.PROJ_ID = '$PROJ_ID' AND A.SORT_ID = B.SORT_ID";
!empty($SUBJECT) ? $QUERY .= " AND SUBJECT like '%$SUBJECT%'" : $QUERY .= '';
!empty($UPLOAD_USER) ? $QUERY .= " AND UPLOAD_USER = '$UPLOAD_USER'" : $QUERY .= '';
!empty($SEND_TIME_MIN) ? $QUERY .= " AND UPDATE_TIME >= '$SEND_TIME_MIN'" : $QUERY .= '';
!empty($SEND_TIME_MAX) ? $QUERY .= " AND UPDATE_TIME <= '$SEND_TIME_MAX'" : $QUERY .= '';


//返回处理过的字符串   如: a%b%c%1 a,b,c,1,3 你,好
function splitStr($str,$c)
{
	$str=addslashes($str);
	preg_match_all("/[".chr(0xa1)."-".chr(0xff)."]+/",$str,$arr);
	$arr1=$arr[0];
	$strcn=join($c,str_split(implode('',$arr1),2));
	
	preg_match_all("/[a-zA-Z0-9]+/",$str,$arren);
	$arr2=$arren[0];
	$stren=join($c,str_split(implode('',$arr2),1));
	$str=$strcn.$stren;
	return $str;
}



$HTML_PAGE_TITLE = _("全局搜索");
include_once("inc/header.inc.php");
?>



<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript">
    function delete_content(FILE_ID1,SORT_ID){
        if(confirm("删除数据不可恢复确认删除?")){
            $.post("delete.php",{FILE_ID:FILE_ID1,SORT_ID:SORT_ID},function(data){
                if(data == "true")
                    $("#z_d_"+FILE_ID1).remove();
            })
        }
    }
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tbody><tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/folder_search.gif" align="absmiddle"><b><span class="Big1"> 全局搜索结果</span></b><br>
    </td>
  </tr>
</tbody></table>


<table class="TableList" width="100%">
  <tbody>

<?

$CUR = exequery(TD::conn(),$QUERY);
$i = 0;

WHILE($ROW = mysql_fetch_array($CUR)){  

    if($i == 0){
    
    ?>
    
<tr class="TableHeader">
      <td nowrap="" align="center">文件夹</td>
      <td nowrap="" align="center">文件名称</td>
      <td nowrap="" align="center">附件文件</td>
      <td nowrap="" align="center">附件说明</td>
      <td nowrap="" align="center">发布时间 <img border="0" src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap="" align="center">操作</td>
  </tr>
    
    <?
    
    }


   if($i%2 == 1){ 
       $TableLine = "TableLine1";
   }else{
       $TableLine = "TableLine2";
   }
?>

    <tr class="<?=$TableLine?>" id="z_d_<?=$ROW['FILE_ID']?>">
      <td>/<?=$ROW['SORT_NAME']?></td>
      <td align="center"><a href="read.php?SORT_ID=0&amp;CONTENT_ID=116&amp;start="><?=$ROW['SUBJECT']?></a></td>
      <td align="left"></td>
      <td align="center"></td>
      <td align="center" width="150" nowrap=""><?=$ROW['UPDATE_TIME']?></td>
      <td align="center" width="80" nowrap="">
         <a href="./new/?PROJ_ID=<?=$ROW['PROJ_ID']?>&SORT_ID=<?=$ROW['SORT_ID']?>&FILE_ID=<?=$ROW['FILE_ID']?>&start=0"><?=_("编辑")?></a>&nbsp;
          <a href="javascript:delete_content(<?=$ROW['FILE_ID']?>,<?=$ROW['SORT_ID']?>);"><?=_("删除")?></a>
        &nbsp;
      </td>
  </tr>

 <?

    $i++;
    }
    
    if($i == 0){
    
        Message(_(""),_("未找到匹配的文档信息"));
        Button_Back();
    
    }
 ?>
  
  </tbody>
</table>


    
</body>
</html>