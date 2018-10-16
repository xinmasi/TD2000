<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("项目代码设置—");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script>
function del(id,type_name,type_no)
{   
    var msg = sprintf("<?=_("确认要删除'%s'吗？")?>",type_name);
    if(window.confirm(msg))
    {
        URL = "budget_delete.php?id="+id+"&type_no="+type_no;
        location=URL;
    }
    
}
</script>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("增加费用科目—")?><?=_("项目费用科目");?></span>
    </td>
  </tr>
</table>

<div align="center">
    <input type="button"  value="<?=_("添加费用科目")?>" class="BigButton" onClick="location='budget_new.php';" >
</div>

<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("费用科目管理")?></span>
    </td>
  </tr>
</table>

<table class="TableList" align="center">
    <tr class="TableHeader" align="center">
        <td nowrap align="center"><b>&nbsp;&nbsp;<?=_("项目费用科目")?>&nbsp;&nbsp;</b></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?php
    //查询TYPE_NO的长度为3的
    $i_type_length = "select type_name, type_no, id from proj_budget_type where CHAR_LENGTH(TYPE_NO) = 3";
    $s_type_length = exequery(TD::conn(), $i_type_length);
        $i = 0;
        while($a_type_row = mysql_fetch_array($s_type_length))
        {	
            $type_name = $a_type_row['type_name'];
            $type_no = $a_type_row['type_no'];
            $id = $a_type_row['id'];
            
?>
		<tr class="TableData">
			<td >&nbsp;<strong><? echo $type_name; ?></strong></td>
            <td>
                &nbsp;&nbsp;<a href="budget_edit.php?id=<?=$id?>"><?=_("编辑")?></a>&nbsp;&nbsp;
                <a href="javascript:del('<?=$id?>','<?=$type_name?>','<?=$type_no?>');"><?=_("删除")?></a>&nbsp;&nbsp
            </td>
        </tr>
        
<?
    //查询TYPE_NO的长度为6的同时前3为要跟$TYPE_NO一样的
    $i_type_next = "select * from proj_budget_type where TYPE_NO like '".$type_no."%' and CHAR_LENGTH(TYPE_NO) = 6";
    $s_type_next = exequery(TD::conn(), $i_type_next);
        while($a_next_row = mysql_fetch_array($s_type_next))
        {   
            $type_no = $a_next_row['type_no'];
            $type_name = $a_next_row['type_name'];
            $id = $a_next_row['id'];
?>
        <tr class="TableData" >
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?=$type_name?></strong></td>
            <td>
             &nbsp;&nbsp;<a href="budget_edit.php?id=<?=$id?>"><?=_("编辑")?></a>&nbsp;&nbsp
                <a href="javascript:del('<?=$id?>','<?=$type_name?>','<?=$type_no?>');"><?=_("删除")?></a>&nbsp;&nbsp
            </td>
        </tr>
<?
        }//while
       
    }
?>
    </table>
</body>
</html>