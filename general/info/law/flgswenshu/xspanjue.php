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
      <div align="center"><?=_("刑事判决类")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="xspanjue/xinshipj00.php"><?=_("刑事判决书（一审公诉案件用）")?></a></li>
        <li><a href="xspanjue/xinshipj01.php"><?=_("刑事判决书（一审自诉案件用）")?></a></li>
        <li><a href="xspanjue/xinshipj02.php"><?=_("刑事附带民事判决书（一审自诉案件用）")?></a></li>
        <li><a href="xspanjue/xinshipj03.php"><?=_("刑事判决书（一审自诉、反诉并案审理用）")?></a></li>
        <li><a href="xspanjue/xinshipj04.php"><?=_("刑事判决书（二审改判用）")?></a></li>
        <li><a href="xspanjue/xinshipj05.php"><?=_("刑事附带民事判决书（二审改判用）")?></a></li>
        <li><a href="xspanjue/xinshipj06.php"><?=_("刑事判决书（复核死刑改判用）")?></a></li>
        <li><a href="xspanjue/xinshipj07.php"><?=_("刑事判决书（复核死刑缓期执行改判用）")?></a></li>
        <li><a href="xspanjue/xinshipj08.php"><?=_("刑事判决书（对严重扰乱法庭秩序和拒不执行判决、裁定的人用）")?></a></li>
        <li><a href="xspanjue/xinshipj09.php"><?=_("刑事判决书（再审后的上诉、抗诉案件二审改判用）")?></a></li>
        <li><a href="xspanjue/xinshipj10.php"><?=_("刑事判决书（复核死刑改判用）")?></a></li>
        <li><a href="xspanjue/xinshipj11.php"><?=_("刑事判决书（按一审程序再审改判用）")?></a></li>
        <li><a href="xspanjue/xinshipj12.php"><?=_("刑事判决书（按二审程序再审改判用）")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>