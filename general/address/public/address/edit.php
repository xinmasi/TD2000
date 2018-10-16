<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("编辑联系人");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function IsNumber(str)
{
   return str.match(/^[0-9]*$/)!=null;
}

function IsValidEmail(str)
{
   var re = /@/;
   return str.match(re)!=null;
}

function CheckForm()
{
   if(document.form1.PSN_NAME.value=="")
   { alert("<?=_("联系人姓名不能为空！")?>");
     return (false);
   }

   if (document.form1.POST_NO_DEPT.value!=""&&!IsNumber(document.form1.POST_NO_DEPT.value))
   { alert("<?=_("单位邮编只能是数字！")?>");
     return (false);
   }

   if (document.form1.POST_NO_HOME.value!=""&&!IsNumber(document.form1.POST_NO_HOME.value))
   { alert("<?=_("家庭邮编只能是数字！")?>");
     return (false);
   }

   if (document.form1.MOBIL_NO.value!=""&&!IsNumber(document.form1.MOBIL_NO.value))
   { alert("<?=_("手机号码只能是数字！")?>");
     return (false);
   }

   if (document.form1.EMAIL.value!=""&&!IsValidEmail(document.form1.EMAIL.value))
   { alert("<?=_("请输入有效的电子信箱！")?>");
     return (false);
   }

   return (true);
}

</script>

<body class="bodycolor">
<?
 //============================ 显示联系人 =======================================
 $query = "SELECT * from ADDRESS where ADD_ID='$ADD_ID'";
 $cursor= exequery(TD::conn(),$query);

 if($ROW=mysql_fetch_array($cursor))
 {
    $GROUP_ID=$ROW["GROUP_ID"];
    $PSN_NAME=$ROW["PSN_NAME"];
    $SEX=$ROW["SEX"];
    $BIRTHDAY=$ROW["BIRTHDAY"];

    $NICK_NAME=$ROW["NICK_NAME"];
    $MINISTRATION=$ROW["MINISTRATION"];
    $MATE=$ROW["MATE"];
    $CHILD=$ROW["CHILD"];

    $DEPT_NAME=$ROW["DEPT_NAME"];
    $ADD_DEPT=$ROW["ADD_DEPT"];
    $POST_NO_DEPT=$ROW["POST_NO_DEPT"];
    $TEL_NO_DEPT=$ROW["TEL_NO_DEPT"];
    $FAX_NO_DEPT=$ROW["FAX_NO_DEPT"];

    $ADD_HOME=$ROW["ADD_HOME"];
    $POST_NO_HOME=$ROW["POST_NO_HOME"];
    $TEL_NO_HOME=$ROW["TEL_NO_HOME"];
    $MOBIL_NO=$ROW["MOBIL_NO"];
    $BP_NO=$ROW["BP_NO"];
    $EMAIL=$ROW["EMAIL"];
    $OICQ_NO=$ROW["OICQ_NO"];
    $ICQ_NO=$ROW["ICQ_NO"];
    $PSN_NO=$ROW["PSN_NO"];
    $NOTES=$ROW["NOTES"];
    
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
 }

?>
<div class="PageHeader">
   <div class="title"><?=_("编辑联系人")?></div>
</div>
<table class="TableTop" width="650">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <?=_("分组")?>
      </td>
      <td class="right"></td>
   </tr>
</table>
<form  enctype="multipart/form-data" action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
 <table class="TableBlock no-top-border"  width="650">
    <tr>
      <td nowrap class="TableData"> <?=_("分组：")?></td>
      <td class="TableData" colspan="2">
        <select name="GROUP_ID" class="BigSelect">
        <option value="0" <?if($GROUP_ID==0) echo "selected";?>><?=_("默认")?></option>
<?
$query = "select * from ADDRESS_GROUP where USER_ID='' order by GROUP_NAME asc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $GROUP_ID1=$ROW["GROUP_ID"];
   $GROUP_NAME=$ROW["GROUP_NAME"];
?>
        <option value="<?=$GROUP_ID1?>" <?if($GROUP_ID==$GROUP_ID1) echo "selected";?>><?=$GROUP_NAME?></option>
<?
}
?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("个人信息")?></b></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("排序号：")?></td>
      <td class="TableData">
        <input type="text" name="PSN_NO" size="12" maxlength="50" class="BigInput" value="<?=$PSN_NO?>">
      </td>
      <td class="TableData" width="250" rowspan="6">
<?
   if($ATTACHMENT_NAME=="")
      echo "<center>"._("暂无照片")."</center>";
   else
   {
	   $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
?>
       <a href="<?=$URL_ARRAY["view"]?>" title="<?=_("点击查看放大图片")?>" target="_blank"><img src="<?=$URL_ARRAY["view"]?>" width='250' border=1 alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME?>"></a>
<?
	}
?>	
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("姓名：")?></td>
      <td class="TableData">
        <input type="text" name="PSN_NAME" size="20" maxlength="50" class="BigInput" value="<?=$PSN_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("性别：")?></td>
      <td class="TableData">
        <select name="SEX" class="BigSelect">
          <option value="" <? if($SEX=="") echo "selected";?>></option>
          <option value="0" <? if($SEX=="0") echo "selected";?>><?=_("男")?></option>
          <option value="1" <? if($SEX=="1") echo "selected";?>><?=_("女")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("生日：")?></td>
      <td class="TableData">
        <input type="text" name="BIRTHDAY" size="10" maxlength="10" class="BigInput" value="<?=$BIRTHDAY?>" onClick="WdatePicker()">
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("昵称：")?></td>
      <td class="TableData">
        <input type="text" name="NICK_NAME" size="25" width="60" class="BigInput" value="<?=$NICK_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("职务：")?></td>
      <td class="TableData">
        <input type="text" name="MINISTRATION" size="25" width="60" class="BigInput" value="<?=$MINISTRATION?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("配偶：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="MATE" size="25" width="60" class="BigInput" value="<?=$MATE?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("子女：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="CHILD" size="25" width="60" class="BigInput" value="<?=$CHILD?>">
      </td>
    </tr>
<?
if($ATTACHMENT_NAME=="")
   $PHOTO_STR=_("联系人照片上传：");
else
   $PHOTO_STR=_("联系人照片更改：");
?>
    <tr>
      <td nowrap class="TableData"> <?=$PHOTO_STR?></td>
      <td class="TableData" colspan="2">
        <input type="file" name="ATTACHMENT" size="40" class="BigInput" title="<?=_("选择附件文件")?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("联系方式（单位）")?></b></td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("单位名称：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="DEPT_NAME" size="25" width="60" class="BigInput" value="<?=$DEPT_NAME?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"><?=_("单位地址：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="ADD_DEPT" size="40" maxlength="100" class="BigInput" value="<?=$ADD_DEPT?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("单位邮编：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="POST_NO_DEPT" size="25" width="60" class="BigInput" value="<?=$POST_NO_DEPT?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("工作电话：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="TEL_NO_DEPT" size="25" width="60" class="BigInput" value="<?=$TEL_NO_DEPT?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("工作传真：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="FAX_NO_DEPT" size="25" width="60" class="BigInput" value="<?=$FAX_NO_DEPT?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("联系方式（家庭）")?></b></td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("家庭住址：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="ADD_HOME" size="40" maxlength="100" class="BigInput" value="<?=$ADD_HOME?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("家庭邮编：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="POST_NO_HOME" size="25" width="60" class="BigInput" value="<?=$POST_NO_HOME?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("家庭电话：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="TEL_NO_HOME" size="25" width="60" class="BigInput" value="<?=$TEL_NO_HOME?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("手机：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="MOBIL_NO" size="25" width="60" class="BigInput" value="<?=$MOBIL_NO?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("小灵通：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="BP_NO" size="25" width="60" class="BigInput" value="<?=$BP_NO?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("电子邮件：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="EMAIL" size="25" maxlength="80" class="BigInput" value="<?=$EMAIL?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("QQ：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="OICQ_NO" size="25" width="60" class="BigInput" value="<?=$OICQ_NO?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("MSN：")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="ICQ_NO" size="25" width="60" class="BigInput" value="<?=$ICQ_NO?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("备注")?></b></td>
    </tr>
    <tr>
      <td class="TableData" colspan="3">
        <textarea cols="60" name="NOTES" rows="5" class="BigInput" wrap="on"><?=$NOTES?></textarea>
      </td>
    </tr>
    <tr>
      <td class="TableData" colspan="3">
<?
echo get_field_table(get_field_html("ADDRESS",$ADD_ID));
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="3" nowrap>
        <input type="hidden" value="<?=$ADD_ID?>" name="ADD_ID">
        <input type="hidden" value="<?=$ATTACHMENT_ID?>" name="ATTACHMENT_ID_OLD">
        <input type="hidden" value="<?=$ATTACHMENT_NAME?>" name="ATTACHMENT_NAME_OLD">
        <input type="hidden" value="<?=$start?>" name="start">
        <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?GROUP_ID=<?=$GROUP_ID?>&start=<?=$start?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>