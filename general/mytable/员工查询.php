<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("员工查询");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'info';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_BODY.='<form action="/general/ipanel/user/search.php" name="form1" >'.
    	 _("姓名：").'<input type="text" name="USER_NAME" class="SmallInput" size="20" maxlength="25"><br>'.
       _("部门：").'<select name="DEPT_ID" class="SmallSelect" style="width:128pt">
          <option value="" selected>'._("所有").'</option>';
$MODULE_BODY.= my_dept_tree(0,0,'2');
$MODULE_BODY.='</select><br>'.
        _("性别：").'<select name="SEX" class="SmallSelect">
          <option value="" selected>'._("所有").' </option>
          <option value="0">'._("男").' </option>
          <option value="1">'._("女").' </option>
        </select>&nbsp;';
$MODULE_BODY.='      <input type="submit" value="'._("查询").'" class="SmallButton" title="'._("模糊查询").'" name="button">
   </form>';
 }