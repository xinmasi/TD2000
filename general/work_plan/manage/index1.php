<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
if($WORK_TYPE=="")
   $WORK_TYPE=0;

if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("WORK_PLAN", 10);   
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("�����ƻ�����");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function plan_detail(PLAN_ID)
{
   URL="../show/plan_detail.php?PLAN_ID="+PLAN_ID;
   myleft=(screen.availWidth-700)/2;
   window.open(URL,"plan_detail","height=500,width=720,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=120,left="+myleft+",resizable=yes");
}

function delete_work_plan(PLAN_ID)
{
   msg='<?=_("ȷ��Ҫɾ��������ƻ���")?>';
   if(window.confirm(msg))
   {
       URL="delete.php?PLAN_ID=" + PLAN_ID + "&start=" + <?=$start?>;
       window.location=URL;
   }
}

function delete_all()
{
   msg='<?=_("ȷ��Ҫɾ�����й����ƻ���")?>';
   if(window.confirm(msg))
   {
      URL="delete_all.php";
      window.location=URL;
   }
}

function change_type(WORK_TYPE,SELECT_STATUS)
{
   window.location="index1.php?WORK_TYPE="+WORK_TYPE+"&SELECT_STATUS="+SELECT_STATUS+"&start="+<?=$start?>;
}

function order_by(field,asc_desc)
{
   window.location="index1.php?WORK_TYPE=<?=$WORK_TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc+"&start="+<?=$start?>;
}
jQuery(function(){
    jQuery("#allbox_for").click(function(){
        if(jQuery("#allbox_for").is(":checked"))
        {
            jQuery("[name='email_select']").attr('checked','true');
        }
        else
        {
            jQuery("[name='email_select']").removeAttr('checked');
        }    
    });
    jQuery("[name='emmail_select']").click(function(){
        jQuery("#allbox_for").removeAttr('checked');
    });    
})
function get_checked()
{
    checked_str = "";
    jQuery("input[name='email_select']:checkbox").each(function(){
        if(jQuery(this).attr("checked"))
        {
            checked_str +=jQuery(this).val()+',';
        }        
    })
    return checked_str;
}
function delete_plan()
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("Ҫɾ�������ƻ���������ѡ������һ����")?>");
     return;
  }
  msg='<?=_("ȷ��Ҫɾ����ѡ�Ĺ����ƻ���")?>';
  if(window.confirm(msg))
  {
    url="delete_all.php?DELETE_STR="+ delete_str +"&start=<?=$start?>";
    location=url;
  }
}

</script>


<body class="bodycolor">
<?
//���ɸѡ
$CUR_DATE=date("Y-m-d",time());
if($WORK_TYPE!=0)
   $RANGE_STR=" and TYPE='$WORK_TYPE'";
   
//״̬ת��
if($SELECT_STATUS=="")
   $SELECT_STATUS=0;
   
if($SELECT_STATUS==1)
   $RANGE_STR.=" and END_DATE <= '$CUR_DATE' and END_DATE!='0000-00-00'";

if($SELECT_STATUS==2)
   $RANGE_STR.=" and (END_DATE > '$CUR_DATE' or END_DATE='0000-00-00')";

   
if($_SESSION["LOGIN_USER_PRIV"]!="1")  //oa����Ա�ܹ������еĹ����ƻ�,�����ߺ͸����˹����Լ���������Ĺ���
   $query = "SELECT count(*) from WORK_PLAN where (CREATOR='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PARTICIPATOR))".$RANGE_STR;
else
   $query = "SELECT count(*) from WORK_PLAN where 1=1".$RANGE_STR;
$cursor= exequery(TD::conn(),$query);
$WORK_PLAN_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $WORK_PLAN_COUNT=$ROW[0];

$TOTAL_ITEMS = $WORK_PLAN_COUNT;
if($WORK_PLAN_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="20" align="absmiddle"><span class="big3"> <?=_("�����ƻ�����")?> </span>&nbsp;
      <select name="WORK_TYPE" class="BigSelect" onChange="change_type(this.value,'<?=$SELECT_STATUS?>');">
          <option value="0" <?if($WORK_TYPE=="0") echo " selected";?>><?=_("�������")?></option>
<?
$query = "SELECT TYPE_ID,TYPE_NAME from PLAN_TYPE order by TYPE_NO";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $TYPE_ID=$ROW["TYPE_ID"];
   $TYPE_NAME=$ROW["TYPE_NAME"];
?>
         <option value="<?=$TYPE_ID?>" <?if($WORK_TYPE==$TYPE_ID) echo "selected";?>><?=$TYPE_NAME?></option>
<?
}
?>
       </select>
       <select name="SELECT_STATUS" class="BigSelect" onChange="change_type('<?=$WORK_TYPE?>',this.value);">
          <option value="0"<?if($SELECT_STATUS=="0") echo " selected";?>><?=_("���мƻ�")?></option>
          <option value="1"<?if($SELECT_STATUS=="1") echo "selected";?>><?=_("�����ƻ�")?></option>
          <option value="2"<?if($SELECT_STATUS=="2") echo "selected";?>><?=_("δ�����ƻ�")?></option>
       </select>       
    </td>
  </tr>
  <tr> 
  	<td colspan="2"> 
      <?=_("˵����OA����Ա�������еĹ����ƻ��������ˡ������˹����Լ���������Ĺ����ƻ�;�����˿��Բ鿴�Լ�����Ĺ����ƻ���")?>
    </td>
  </tr>  
</table>
<br>
<?
   Message("",_("�޹����ƻ�"));
   exit;
}

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" >
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="20"><span class="big3"> <?=_("�����ƻ�����")?> </span>&nbsp;
      <select name="WORK_TYPE" class="BigSelect" onChange="change_type(this.value,'<?=$SELECT_STATUS?>');">
          <option value="0"<?if($WORK_TYPE=="0") echo " selected";?>><?=_("�������")?></option>
<?
$query = "SELECT TYPE_ID,TYPE_NAME from PLAN_TYPE order by TYPE_NO";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $TYPE_ID=$ROW["TYPE_ID"];
   $TYPE_NAME=$ROW["TYPE_NAME"];
?>
         <option value="<?=$TYPE_ID?>" <?if($WORK_TYPE==$TYPE_ID) echo "selected";?>><?=$TYPE_NAME?></option>
<?
}
?>
       </select>
       <select name="SELECT_STATUS" class="BigSelect" onChange="change_type('<?=$WORK_TYPE?>',this.value);">
          <option value="0"<?if($SELECT_STATUS=="0") echo " selected";?>><?=_("���мƻ�")?></option>
          <option value="1"<?if($SELECT_STATUS=="1") echo "selected";?>><?=_("�����ƻ�")?></option>
          <option value="2"<?if($SELECT_STATUS=="2") echo "selected";?>><?=_("δ�����ƻ�")?></option>
       </select> 
    </td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
    </td>
  </tr>  
  <tr> 
  	<td colspan="2"> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=_("˵����OA����Ա�������еĹ����ƻ��������ˡ������˹����Լ���������Ĺ����ƻ��������˿��Բ鿴�Լ�����Ĺ����ƻ���")?>
    </td>
  </tr>
</table>
<?
if($ASC_DESC=="")
   $ASC_DESC="1";
if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
?>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
   <td nowrap align="center"><?=_("ѡ��")?></td>
   <td nowrap align="center"><?=_("���")?></td>
   <td nowrap align="center" onClick="order_by('NAME','<?if($FIELD=="NAME") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("�ƻ�����")?></u><?if($FIELD=="NAME") echo $ORDER_IMG;?></td>     
   <td nowrap align="center" onClick="order_by('BEGIN_DATE','<?if($FIELD=="BEGIN_DATE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("��ʼʱ��")?></u><?if($FIELD=="BEGIN_DATE"||$FIELD=="") echo $ORDER_IMG;?></td>
   <td nowrap align="center"><?=_("����ʱ��")?></td>
   <td nowrap align="center" onClick="order_by('TYPE','<?if($FIELD=="TYPE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("�ƻ����")?></u><?if($FIELD=="TYPE") echo $ORDER_IMG;?></td>
   <td nowrap align="center"><?=_("������")?></td>
   <td nowrap align="center"><?=_("������")?></td>   
   <td nowrap align="center"><?=_("����")?></td>
   <td nowrap align="center"><?=_("״̬")?></td>
   <td nowrap align="center"><?=_("����")?></td>
  </tr>

<?
//============================ ��ʾ =======================================
$POSTFIX = _("��");
$CUR_DATE=date("Y-m-d",time());

if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query = "SELECT PLAN_ID,NAME,BEGIN_DATE,END_DATE,TYPE,TO_ID,MANAGER,PARTICIPATOR,ATTACHMENT_ID,ATTACHMENT_NAME,TO_PERSON_ID,SUSPEND_FLAG,CREATOR,PUBLISH,OPINION_LEADER from WORK_PLAN where (CREATOR='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PARTICIPATOR))";
else
   $query = "SELECT PLAN_ID,NAME,BEGIN_DATE,END_DATE,TYPE,TO_ID,MANAGER,PARTICIPATOR,ATTACHMENT_ID,ATTACHMENT_NAME,TO_PERSON_ID,SUSPEND_FLAG,CREATOR,PUBLISH,OPINION_LEADER from WORK_PLAN where 1=1";

$query.=$RANGE_STR;

if($FIELD=="")
   $query .= " order by PLAN_ID desc,BEGIN_DATE desc";
else
{
   $query .= " order by ".$FIELD;
   if($ASC_DESC=="1")
      $query .= " desc";
   else
      $query .= " asc";
}

$query .= " limit $start,$PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$WORK_PLAN_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $WORK_PLAN_COUNT++;

   $PLAN_ID=$ROW["PLAN_ID"];
   $NAME=$ROW["NAME"];
   $BEGIN_DATE=$ROW["BEGIN_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $TYPE=$ROW["TYPE"];
   $TO_ID=$ROW["TO_ID"];
   $MANAGER=$ROW["MANAGER"];
   $PARTICIPATOR=$ROW["PARTICIPATOR"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $TO_PERSON_ID=$ROW["TO_PERSON_ID"];
   $SUSPEND_FLAG=$ROW["SUSPEND_FLAG"];   
   $CREATOR=$ROW["CREATOR"];   
   $PUBLISH=$ROW["PUBLISH"];
   $OPINION_LEADER=$ROW["OPINION_LEADER"]; 
   
   $MANAGER_STR.=td_trim($ROW["MANAGER"]).",";
   $CREATOR_STR.=$ROW["CREATOR"].",";
   
   $query = "SELECT TYPE_NAME from PLAN_TYPE where TYPE_ID='$TYPE'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW1=mysql_fetch_array($cursor1))
      $TYPE_DESC=$ROW1["TYPE_NAME"];
   else
      $TYPE_DESC="";

   $MANAGE_NAME="";
   $TOK=strtok($MANAGER,",");
   while($TOK!="")
   {
     $query1="select USER_ID,DEPT_ID,USER_NAME from USER where USER_ID='$TOK'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW1=mysql_fetch_array($cursor1))
     {
        $USER_ID1=$ROW1["USER_ID"];     	
        $DEPT_ID=$ROW1["DEPT_ID"];
        $DEPT_NAME=dept_long_name($DEPT_ID);
        $MANAGE_NAME.="<a href=\"#\" onClick=\"window.open('../../calendar/info/month.php?DEPT_ID=$DEPT_ID&USER_ID=$USER_ID1','','height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');\"><u title=\""._("���ţ�").$DEPT_NAME."\" style=\"cursor:hand\">".$ROW1["USER_NAME"]."</u></a>".$POSTFIX;
     }
     $TOK=strtok(",");
   }
   $MANAGE_NAME=substr($MANAGE_NAME,0,-strlen($POSTFIX));
   
   $PARTICIPATOR_NAME="";
   $TOK=strtok($PARTICIPATOR,",");
   while($TOK!="")
   {
     $query1="select USER_ID,USER_NAME,DEPT_ID from USER where USER_ID='$TOK'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
        $USER_ID1=$ROW["USER_ID"];  
        $DEPT_ID=$ROW["DEPT_ID"];
        $DEPT_NAME=dept_long_name($DEPT_ID);
        $PARTICIPATOR_NAME.="<a href=\"#\" onClick=\"window.open('../../calendar/info/month.php?DEPT_ID=$DEPT_ID&USER_ID=$USER_ID1','','height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');\"><u title=\""._("���ţ�").$DEPT_NAME."\" style=\"cursor:hand\">".$ROW["USER_NAME"]."</u></a>".$POSTFIX;
     }
     $TOK=strtok(",");
   }
   $PARTICIPATOR_NAME=substr($PARTICIPATOR_NAME,0,-strlen($POSTFIX));   
   
   
if($PUBLISH==1)  //����
{
   if($SUSPEND_FLAG==1)
   {
      if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
      {
         $STATUS=1;
         $STATUS_DESC=_("δ��ʼ");
      }
      else
      {
         $STATUS=2;
         $STATUS_DESC= "<font color='#00AA00'><b>"._("������")."</b></font>";
      }
      
      if($END_DATE!="0000-00-00")
      {
        if(compare_date($CUR_DATE,$END_DATE)>=0)
        {
           $STATUS=3;
           $STATUS_DESC= "<font color='#FF0000'><b>"._("�ѽ���")."</b></font>";
        }
      }
   }
   else 
   {
      $STATUS=2;
      $STATUS_DESC= "<font color='#FF0000'><b>"._("��ͣ")."</b></font>";
   }   
}
else //δ����
{
      $STATUS=1;
      $STATUS_DESC= "<font color='#FF0000'><b>"._("δ����")."</b></font>";	
}
   if($WORK_PLAN_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";

   if($END_DATE=="0000-00-00")
      $END_DATE="";
    $PUBLISH_STR.=$PUBLISH.",";  
    $STATUS_STR.=$STATUS.",";
?>
   <tr class="<?=$TableLine?>">
     <td><?if (find_id($OPINION_LEADER.$MANAGER.$CREATOR,$_SESSION["LOGIN_USER_ID"])|| $_SESSION["LOGIN_USER_PRIV"]==1){if($STATUS==1|| $CREATOR==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1){?><input type="checkbox" name="email_select" value="<?=$PLAN_ID?>" ><?}}?></td>
     <td align="center"><?=$WORK_PLAN_COUNT?></td>   
     <td align="center"><a href="javascript:plan_detail('<?=$PLAN_ID?>');"><?=$NAME?></a>
     <input type="button"  value="<?=_("����ͼ")?>" class="SmallButton" onClick="window.open('../show/progress_map.php?PLAN_ID=<?=$PLAN_ID?>','','status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=0,top=0,resizable=yes');" title="<?=_("�鿴����ͼ")?>">
     </td>
     <td nowrap align="center"><?=$BEGIN_DATE?></td>
     <td nowrap align="center"><?=$END_DATE?></a></td>
     <td nowrap align="center"><?=$TYPE_DESC?></td>
     <td align="center"><?=$MANAGE_NAME?></td>
     <td align="center"><?=$PARTICIPATOR_NAME?></td>
     <td align="left">
<?
     if($ATTACHMENT_NAME=="")
        echo _("��");
     else
        echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1,0,0,1,0,0); 

?>
     </td>
     <td nowrap align="center"><?=$STATUS_DESC?></td>
     <td nowrap align="center">
<?
if ( $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($OPINION_LEADER.$MANAGER.$CREATOR,$_SESSION["LOGIN_USER_ID"]))
{
     if($STATUS==1 || $CREATOR==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1) 
     {
?>
    <!--<a href="new/?PLAN_ID=<?=$PLAN_ID?>&start=<?=$start?>"> <?=_("�޸�")?></a> -->
    <a href="new/index.php?PLAN_ID=<?=$PLAN_ID?>&start=<?=$start?>"> <?=_("�޸�")?></a>       
<?
     }
     if(find_id($OPINION_LEADER.$MANAGER.$CREATOR,$_SESSION["LOGIN_USER_ID"]))//�����ˣ������˺��쵼��ע������ע
     {
?>
     <a href="#" onClick="window.open('add_opinion.php?PLAN_ID=<?=$PLAN_ID?>','','height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=110,top=60,resizable=yes');"><?=_("��ע")?></a><br>
     
<?
   }
     if($STATUS==1 || $STATUS==3)
     {
?>
     <a href="manage.php?PLAN_ID=<?=$PLAN_ID?>&OPERATION=<?=$STATUS?>&WORK_TYPE=<?=$WORK_TYPE?>&start=<?=$start?>"> <?=_("��Ч")?></a>
<?
     }    
     if($STATUS==2)
     {
     	  if($SUSPEND_FLAG==1)    	  
           echo "<a href=\"manage.php?PLAN_ID=$PLAN_ID&OPERATION=4&WORK_TYPE=$WORK_TYPE&start=$start\"> "._("��ͣ")."</a>";       
        else
           echo "<a href=\"manage.php?PLAN_ID=$PLAN_ID&OPERATION=5&WORK_TYPE=$WORK_TYPE&start=$start\"> "._("����")."</a>";       
?>         
     <a href="manage.php?PLAN_ID=<?=$PLAN_ID?>&OPERATION=2&WORK_TYPE=<?=$WORK_TYPE?>&start=<?=$start?>"> <?=_("����")?></a>
     <?
     }
}     
     ?>
     </td>
   </tr>
<?
}
if ($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($MANAGER_STR.$CREATOR_STR, $_SESSION["LOGIN_USER_ID"]))
{  
   if ($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($PUBLISH_STR, '0')|| (find_id($PUBLISH_STR, '1')&& find_id($STATUS_STR, '1')) || find_id($CREATOR_STR, $_SESSION["LOGIN_USER_ID"]))
   {
?>

<tr class="TableControl">
 <td colspan="11" >
<input type="checkbox" name="allbox" id="allbox_for" ><label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;&nbsp;
<a href="javascript:delete_plan();" title="<?=_("ɾ����ѡ�����ƻ�")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp;
</td>
</tr>
<?  }
}?>

</table>
</body>

</html>
