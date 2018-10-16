<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<style type="text/css">
<!--a            { text-decoration: none; font-size: 9pt; color: black; font-family: 宋体 }
.text        { font-size: 9pt; font-family: 宋体 }
.text1       { color: #0000A0; font-size: 11pt; font-family: 宋体 }
.text2       { color: #008080; font-size: 9pt; font-family: 宋体 }
.text3       { color: #0F8A91; font-size: 11pt; font-family: 宋体 }
.l100        { line-height: 14pt; font-size: 9pt }
td           { font-family: 宋体; font-size: 9pt; line-height: 13pt }
input        { font-size: 9pt; font-family: 宋体 }
p            { font-size: 9pt; font-family: 宋体 }
--></style>

<BODY class="bodycolor">

<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader"> 
      <div align="center"><?=_("刑事裁定类")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="xscaiding/xinshicd00.php"><?=_("刑事裁定书（二审发回重审用）")?></a></li>
        <li><a href="xscaiding/xinshicd01.php"><?=_("刑事附带民事裁定书（二审维持原判决用）")?></a></li>
        <li><a href="xscaiding/xinshicd02.php"><?=_("刑事裁定书（二审维持原判决用）")?></a></li>
        <li><a href="xscaiding/xinshicd03.php"><?=_("刑事裁定书（驳回自诉用）")?></a></li>
        <li><a href="xscaiding/xinshicd04.php"><?=_("刑事裁定书（终止审理用）")?></a></li>
        <li><a href="xscaiding/xinshicd05.php"><?=_("刑事裁定书（核准死刑用）")?></a></li>
        <li><a href="xscaiding/xinshicd06.php"><?=_("刑事裁定书（根据授权核准死刑用）")?></a></li>
        <li><a href="xscaiding/xinshicd07.php"><?=_("刑事裁定书（核准死刑缓期执行用）")?></a></li>
        <li><a href="xscaiding/xinshicd08.php"><?=_("刑事裁定书（复核死刑发回重审用）")?></a></li>
        <li><a href="xscaiding/xinshicd09.php"><?=_("刑事裁定书（复核死刑缓期执行发回重审用）")?></a></li>
        <li><a href="xscaiding/xinshicd10.php"><?=_("刑事裁定书（复核类推案件发回重审用）")?></a></li>
        <li><a href="xscaiding/xinshicd11.php"><?=_("刑事裁定书（二审维持、变更、撤销原裁定用）")?></a></li>
        <li><a href="xscaiding/xinshicd12.php"><?=_("刑事裁定书（按一审程序再审维持原判用）")?></a></li>
        <li><a href="xscaiding/xinshicd13.php"><?=_("刑事裁定书（按二审程序再审维持原判用）")?></a></li>
        <li><a href="xscaiding/xinshicd14.php"><?=_("刑事裁定书（再审后的上诉、抗诉案件二审维持原判用）")?></a></li>
        <li><a href="xscaiding/xinshicd15.php"><?=_("刑事裁定书（本院决定提起再审用）")?></a></li>
        <li><a href="xscaiding/xinshicd16.php"><?=_("刑事裁定书（上级法院指令再审用）")?></a></li>
        <li><a href="xscaiding/xinshicd17.php"><?=_("刑事裁定书（上级法院决定提审用）")?></a></li>
        <li><a href="xscaiding/xinshicd18.php"><?=_("刑事裁定书（减刑、假释用）")?></a></li>
        <li><a href="xscaiding/xinshicd19.php"><?=_("刑事裁定书（补正裁判文书失误用）")?></a></li>
        <li><a href="xscaiding/xinshicd20.php"><?=_("刑事裁定书（减、免罚金用）")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>