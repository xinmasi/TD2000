<?
include_once ("inc/auth.inc.php");
include_once ("inc/header.inc.php");
include_once ("inc/utility_org.php");
// ------处理编辑页面的数据显示开始------
$id = $_GET ['id'];
$query  = "SELECT * FROM OFFICE_DEPOSITORY WHERE ID='$id'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $DEPOSITORY_NAME = $ROW['DEPOSITORY_NAME'];
    $OFFICE_TYPE_ID  = $ROW['OFFICE_TYPE_ID'];
    $MANAGER         = $ROW['MANAGER'];
    $MANAGER_NAME    = GetUserNameById ( $MANAGER );
    $PRIV_ID         = $ROW['PRIV_ID']; //获得角色中文内容
    $PRIV_NAME       = GetPrivNameById($ROW ['PRIV_ID']); //获得角色中文内容
//     $PRIV_NAME       = GetUserNameById ( $PRIV_ID );
    $PRO_KEEPER      = $ROW['PRO_KEEPER'];
    $PRO_KEEPER_NAME = GetUserNameById ( $PRO_KEEPER );
    $DEPT_ID         = $ROW['DEPT_ID'];
    $APPROVE         = $ROW['APPROVE'];
    if($DEPT_ID == "ALL_DEPT")
    {
        $DEPT_NAME = _("全体部门");
    }else
    {
        $DEPT_NAME = GetDeptNameById($DEPT_ID);
    }
}
// -------处理编辑页面的数据显示结束
?>
<!--link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css"-->

<body>
<form action="update.php" method="post" name="form1" id="form1">
    <div class="defin_main_body">
        <!-- 页面主体左侧部分开始 -->
        <div class="main_left">
            <div class="f_field_block">
                <div class="f_field_label">
                    <span class="f_field_title"><?=_("办公用品库名称")?><font style="color: red;padding-left: 1px;">*</font></span>
                </div>
                <div class="f_field_ctrl clear">
                    <input type="text" size="30" name="DEPOSITORY_NAME" maxlength="50"
                           id="DEPOSITORY_NAME" value="<?=$DEPOSITORY_NAME?>" />
                </div>
            </div>
            <div class="f_field_block">
                <div class="f_field_label">
                    <span class="f_field_title"><?=_("所属部门")?><font style="color: red;padding-left: 1px;">*</font></span>
                </div>
                <div class="f_field_ctrl clear">
                    <input type="hidden" name="TO_ID" value="<?=$DEPT_ID?>" />
                        <textarea cols=29 name="TO_NAME" rows=3 class="BigStatic"
                                  wrap="yes" disabled id="DEPT_NAME"><?=$DEPT_NAME?></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
                </div>
            </div>
            <div class="f_field_block">
                <div class="f_field_label">
                    <span class="f_field_title"><?=_("部门审批人是否必选")?></span>
                </div>
                <div class="f_field_ctrl clear">
                    <span>是<input type="radio" name="APPROVE" value="1" <?=$APPROVE==1?"checked":"";?> /></span>
                    <span>否<input type="radio" name="APPROVE" value="0" <?=$APPROVE==0?"checked":"";?>/></span>
                </div>
            </div>
        </div>
        <!-- 页面主体左侧部分结束 -->
        <!-- 页面主体右侧部分开始 -->
        <div class="main_right">
            <div class="f_field_block">
                <div class="f_field_label">
                    <span class="f_field_title"><?=_("库管理员")?><font style="color: red;padding-left: 1px;">*</font></span>
                </div>
                <div class="f_field_ctrl clear">
                    <input type="hidden" name="MANAGER" value="<?=$MANAGER?>" />
                        <textarea cols=29 name="MANAGER_NAME" rows=2 class="BigStatic" wrap="yes" id="MANAGER_NAME" disabled><?=$MANAGER_NAME?></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectUser('126','','MANAGER', 'MANAGER_NAME')"><?=_("选择")?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('MANAGER', 'MANAGER_NAME')"><?=_("清空")?></a>
                </div>
            </div>
            <div class="f_field_block">
                <div class="f_field_label">
                    <span class="f_field_title"><?=_("库管理员角色")?></span>
                </div>
                <div class="f_field_ctrl clear">
                        <span id="td_role" style="display: block" align="left" nowrap>
                        <input type="hidden" name="PRIV_ID" id="PRIV_ID" value="<?=$PRIV_ID?>">
                        <textarea cols=29 rows=2 name="PRIV_NAME"id="PRIV_NAME" class="BigStatic" wrap="yes" disabled><?=$PRIV_NAME?></textarea>
                        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('', 'PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
                        <a href="javascript:;" class="orgClear"onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
                        </span>
                </div>
            </div>
            <div class="f_field_block">
                <div class="f_field_label">
                    <span class="f_field_title"><?=_("物品调度员")?><font style="color: red;padding-left: 1px;">*</font></span>
                </div>
                <div class="f_field_ctrl clear">
                    <input type="hidden" name="PRO_KEEPER" value="<?=$PRO_KEEPER?>">
                    <textarea cols=29 rows=2 id="PRO_KEEPER_NAME" name="PRO_KEEPER_NAME" class="BigStatic" wrap="yes" disabled><?=$PRO_KEEPER_NAME?></textarea><!--SelectUserSingle-->
                    <a href="javascript:;" class="orgAdd" onClick="SelectUser('126','','PRO_KEEPER', 'PRO_KEEPER_NAME')"><?=_("选择")?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('PRO_KEEPER', 'PRO_KEEPER_NAME')"><?=_("清空")?></a>
                </div>
            </div>
        </div>
        <!-- 页面主体右侧部分结束 -->
        <!-- 页面说明提示部分开始 -->
        <div class="clear" style="margin-left: 30px;">
            <?
            $query = "SELECT * FROM OFFICE_TYPE WHERE TYPE_DEPOSITORY='$id'";
            $cursor = exequery ( TD::conn (), $query );
            if(mysql_num_rows ( $cursor )!=0){
                ?>
                <div class="f_field_block">
                    <div class="f_field_label">
                        <span class="f_field_title"><?=_("专属类别")?></span>
                    </div>
                    <div class="f_field_ctrl clear">
                        <?
                        $COUNT = 0;
                        while ( $ROW = mysql_fetch_array ( $cursor ) ) {
                            $TYPE_ID = $ROW ['ID'];
                            $TYPE_NAME = $ROW ['TYPE_NAME'];
                            ?>
                            <label class="checkbox inline"> <input type="checkbox"
                                                                   name="TYPE_NAME_<?=$TYPE_ID ?>" value="<?=$TYPE_ID?>"
                                    <?if(find_id($OFFICE_TYPE_ID,$TYPE_ID)) echo "checked";?>><?=$TYPE_NAME?>
                            </label>
                        <? }?>
                    </div>
                </div>
            <? }?>
        </div>
        <div class="alert clear" style="margin-left: 30px; margin-top: 15px;">
            <button type="button" class="close" data-dismiss="alert"
                    title='<?=_('关闭')?>'>&times;</button>
            <strong style="color: blue; font-size: 16px; font-weight: bold;"><?=_("说明")?></strong><br />
            <p style="color: green;"> <?=_("1、所属部门：指可以使用该库的部门；")?></p>
            <p style="color: green;"> <?=_("2、库管理员：有审批权限的用户，设置后在办公用品信息管理中审批权限列表中显示；")?></p>
            <p style="color: green;"> <?=_("3、物品调度员：负责物品采购和发放的用户，只在审批通过后收到事务提醒，不参与审批；")?></p>
        </div>
        <!-- 页面说明提示部分结束 -->
    </div>
    <input type="hidden" name="ID" id="id" value="<?=$id?>" />
</form>
</body>
<script type="text/javascript">
    document.onkeydown=function()
    {//屏蔽js回车键
        if (event.keyCode == 13)
        {
            return false;
        }
    }
</script>