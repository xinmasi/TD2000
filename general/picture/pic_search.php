<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

if(!isset($start)||$start=="")
   $start=0;

if(strrchr($PIC_DIR,"/")=="/") 
  $PIC_DIR=substr($PIC_DIR,0,-1);
//echo $PIC_DIR."<br>".$PIC_ID."<br>".$file_name."<br>".$file_type;

//$FILE_COUNT-��ǰͼƬ����
//$SORT_COUNT-��ǰĿ¼�ļ�������
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

   	 //�����ļ�
   	  $FILE_URL = iconv2os($PIC_DIR."/".$FILE_NAME);
      if(is_file($FILE_URL))
      {
        $TEP_TYPE = substr(strrchr($FILE_NAME,"."),1);
   	    if($TEP_TYPE=="db")
   	      continue;
   	   
   	   //��ѯ�������� 
        $temp_file_type=strtolower(substr(strrchr($FILE_NAME,"."),1));
        if($file_type=="") 
           $File_Type="true";
        else
           if($temp_file_type==$file_type)
              $File_Type="true"; 
        //��ѯ��������
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
           $FILE_COUNT++;  //�ļ�����
        }
        else
          continue;
      }
   } //Ŀ¼��������
   
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

$HTML_PAGE_TITLE = _("ͼƬ����");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="js/pic_control.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big">
    	<a href="javascript:hide_tree();" id="tree_img"></a>
    	<img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle">&nbsp;<b><span class="Big1"><?=$LOCATION?>-<?=_("�������")?></span></b><br>
    </td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$FILE_COUNT,10)?></td>
  </tr>
</table>
<?
if($FILE_COUNT == 0)
{
   echo "<br><br>";
   Message("",_("û�������������������ļ�"));
}
else
{
?>
<script>
//���ṹͼƬ��ʼ��
if(parent.test.cols=="200,*")
{
	var temp = document.getElementById("tree_img")
	temp.className='scroll-left-active';
	temp.title="<?=_("����Ŀ¼��")?>";
}
else
{
	var temp = document.getElementById("tree_img")
	temp.className='scroll-right-active';
	temp.title="<?=_("��ʾĿ¼��")?>";
}
</script>

<table class="TableList" width="100%" border="1">
    <tr class="TableHeader" align="center">
      <td><input name="checkall" id="checkall" type="checkbox" onClick="all_check()" /></td>
      <td><?=_("���")?></td>
      <td><?=_("�ļ���")?></td>
      <td><?=_("·��")?></td>
      <td width="60"><?=_("��С")?></td>
      <td><?=_("����")?></td>
      <td><?=_("�޸�ʱ��")?></td>
    </tr>
<?
  for($i=$start; $i< $FILE_COUNT && $i< $start+10; $i++)
  {
  	if(floor($FILE_ATTR_ARRAY[$i]["SIZE"]/1024/1024)>0)
      $FILE_SIZE=round($FILE_ATTR_ARRAY[$i]["SIZE"]/1024/1024,1)."M";
    else if(floor($FILE_ATTR_ARRAY[$i]["SIZE"]/1024)>0)
      $FILE_SIZE=round($FILE_ATTR_ARRAY[$i]["SIZE"]/1024,1)."K";
    else
      $FILE_SIZE=round($FILE_ATTR_ARRAY[$i]["SIZE"])."B";
  
    $TEMP = $PIC_ID."#".$FILE_ATTR_ARRAY[$i]["SUB_DIR"]."#".$FILE_ATTR_ARRAY[$i]["NAME"];
    //$CHECK_ID = str_ireplace(".".$FILE_ATTR_ARRAY[$i]["TYPE"],"",$FILE_ATTR_ARRAY[$i]["NAME"]);
    $CHECK_ID = $i."_CHECK_ID";
    $TEMP_NAME_SUB=$FILE_ATTR_ARRAY[$i]["NAME"]."|".$FILE_ATTR_ARRAY[$i]["SUB_DIR"];
    
?>
	<tr class="" align="center">
      <td><input name="checkboxs" id="<?=$CHECK_ID?>" type="checkbox" onClick="file_name_add('<?=$TEMP_NAME_SUB?>','<?=$CHECK_ID?>');" title="<?=$FILE_ATTR_ARRAY[$i]['NAME']?>"/></td>
      <td><?=$i+1?></td>
      <td><a href="javascript:open_pic('<?=$TEMP?>');"><?=$FILE_ATTR_ARRAY[$i]["NAME"]?></a></td>
      <td id="id_url_<?=$i?>"><?=$FILE_ATTR_ARRAY[$i]["URL"]?></td>
      <td><?=$FILE_SIZE?></td>
      <td><?=$FILE_ATTR_ARRAY[$i]["TYPE"]?></td>
      <td><?=$FILE_ATTR_ARRAY[$i]["TIME"]?></td>
    </tr>
<?
  }
}

if($FILE_COUNT>0)
{
?>
    <tr class="TableControl">
      <td colspan="7">
      	<input name="allbox_for" id="allbox_for" type="checkbox" onClick="all_check()" /><label for="allbox_for"><?=_("ȫѡ")?></label> &nbsp;
<?
if($DLL_PRIV==1)
{
?>
      	<a href="javascript:;pic_paste('','copy','<?=$PIC_ID?>','<?=$SUB_DIR?>','<?=$PIC_PATH?>','<?=$PIC_DIR?>')"><img src="<?=MYOA_STATIC_SERVER?>/static/images/copy.gif" align="absMiddle" border="0" title="<?=_("���ƴ��ļ�")?>"><?=_("����")?></a>&nbsp;
      	<a href="javascript:;pic_paste('','cut','<?=$PIC_ID?>','<?=$SUB_DIR?>','<?=$PIC_PATH?>','<?=$PIC_DIR?>')"><img src="<?=MYOA_STATIC_SERVER?>/static/images/cut.gif" align="absMiddle" border="0" title="<?=_("���д��ļ�")?>"><?=_("����")?></a>&nbsp;
      	<a href="javascript:do_rename('<?=$SUB_DIR?>','<?=$PIC_PATH?>');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/folder_edit.gif" align="absMiddle" border="0" title="<?=_("���������ļ�")?>"><?=_("������")?></a>&nbsp;
        <a href="javascript:picdelete('<?=$PIC_ID?>','<?=$SUB_DIR?>','<?=$PIC_PATH?>','<?=$LOCATION?>');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle" border="0" title="<?=_("ɾ�����ļ�")?>"><?=_("ɾ��")?></a>&nbsp;
<?
}
?>
      	<a href="javascript:do_action('<?=$SUB_DIR?>','<?=$PIC_PATH?>');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/download.gif" align="absMiddle" border="0" title="<?=_("���ش��ļ�")?>"><?=_("����")?></a>&nbsp;
      </td>
    </tr>
<?
}//if($FILE_COUNT>0)
?>
</table>
<script>
	function all_check()
	{
		var checkboxs = document.getElementsByName("checkboxs");
		var allbox = document.getElementById("allbox_for");
		var tag=0;
		if(allbox.checked==true)
			tag=1;
		for(var i=0;i<checkboxs.length;i++)
		{
				if(tag==1)
				{
					checkboxs[i].checked=true;
					document.getElementById("checkall").checked=true;
					document.getElementById("allbox_for").checked=true;
					var str = checkboxs[i].onclick.toString();
          var str1 = str.replace("function onclick()","");
          var str2 = str1.replace("{","");
          var str3 = str2.replace("}","");
          eval(str3);
				}
				else
				{
					checkboxs[i].checked=false;
					document.getElementById("checkall").checked=false;
					document.getElementById("allbox_for").checked=false;
					file_name_clear();
				}
		}
	}
	function open_pic(temp)
	{
		temp=temp.split("#");
    aWidth=screen.availWidth-10;
    aHeight=screen.availHeight-40;

    window_top=0;
    window_left=0;
    window_width=aWidth;
    window_height=aHeight;

    URL="open.php?PIC_ID="+temp[0]+"&SUB_DIR="+temp[1]+"&URL_FILE_NAME="+temp[2];
    window.open(URL,"<?=_("ͼƬ���")?>","toolbar=0,status=0,menubar=0,scrollbars=no,resizable=1,width="+window_width+",height="+window_height+",top="+window_top+",left="+window_left);
	}
</script>
<div align="center"><br><input type="button" class="BigButton" value="<?=_("����")?>" onClick="location='pic_query.php?PIC_ID=<?=$PIC_ID?>&PIC_DIR=<?=$PIC_DIR?>&SUB_DIR=<?=$SUB_DIR?>&LOCATION=<?=$LOCATION?>'"></div>
</body>
</html>
