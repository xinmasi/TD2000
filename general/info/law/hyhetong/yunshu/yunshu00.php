<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?><style type="text/css">
<!--a            { text-decoration: none; font-size: 9pt; color: black; font-family: ËÎÌå }
.text        { font-size: 9pt; font-family: ËÎÌå }
.text1       { color: #0000A0; font-size: 11pt; font-family: ËÎÌå }
.text2       { color: #008080; font-size: 9pt; font-family: ËÎÌå }
.text3       { color: #0F8A91; font-size: 11pt; font-family: ËÎÌå }
.l100        { line-height: 14pt; font-size: 9pt }
td           { font-family: ËÎÌå; font-size: 9pt; line-height: 13pt }
input        { font-size: 9pt; font-family: ËÎÌå }
p            { font-size: 9pt; font-family: ËÎÌå }
--></style><BODY class="bodycolor">
<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader">
      <div align="center">Æû³µ»õÎïÔËµ¥</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> ¡¡¡¡<BR> 
¡¡¡¡Æû¡¡¡¡³µ¡¡¡¡»õ¡¡¡¡Îï¡¡¡¡ÔË¡¡¡¡µ¥<BR> 
¡¡¡¡¡Á¡ÁÊ¡Æû³µ»õÎïÔËµ¥<BR> 
¡¡¡¡©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤<BR> 
¡¡¡¡<BR> 
¡¡¡¡ÍÐÔËÈË£¨µ¥Î»£©£º¡¡¡¡¾­°ìÈË£º¡¡¡¡µç»°£º¡¡¡¡¡¡µØÖ·£º¡¡¡¡¡¡ÔËµ¥±àºÅ£º<BR> 
¡¡¡¡©°©¤©¤©¤©Ð©¤©Ð©¤©¤©Ð©¤©¤©¤©Ð©¤©¤©Ð©¤©Ð©¤©¤©¤©¤©¤©¤©Ð©¤©¤©Ð©¤©¤©¤©Ð©¤©´<BR> 
¡¡¡¡©¦·¢»õÈË©¦¡¡©¦µØÖ·©¦¡¡¡¡¡¡©¦µç»°©¦¡¡©¦¡¡×°»õµØµã¡¡©¦¡¡¡¡©¦³§ÐÝÈÕ©¦¡¡©¦<BR> 
¡¡¡¡©À©¤©¤©¤©à©¤©à©¤©¤©à©¤©¤©¤©à©¤©¤©à©¤©à©¤©¤©¤©¤©¤©¤©à©¤©¤©à©¤©¤©¤©à©¤©È<BR> 
¡¡¡¡©¦ÊÕ»õÈË©¦¡¡©¦µØÖ·©¦¡¡¡¡¡¡©¦µç»°©¦¡¡©¦¡¡Ð¶»õµØµã¡¡©¦¡¡¡¡©¦³§ÐÝÈÕ©¦¡¡©¦<BR> 
¡¡¡¡©À©¤©¤©¤©à©¤©à©¤©¤©à©¤©¤©¤©à©¤©¤©à©¤©à©¤©¤©¤©Ð©¤©Ð©Ø©¤©¤©à©¤©Ð©¤©Ø©Ð©È<BR> 
¡¡¡¡©¦¸¶¿îÈË©¦¡¡©¦µØÖ·©¦¡¡¡¡¡¡©¦µç»°©¦¡¡©¦Ô¼¶¨Æð©¦ÔÂ©¦Ô¼¶¨µ½©¦ÔÂ©¦ÐèÒª©¦©¦<BR> 
¡¡¡¡©¦¡¡¡¡¡¡©¦¡¡©¦¡¡¡¡©¦¡¡¡¡¡¡©¦¡¡¡¡©¦¡¡©¦ÔËÊ±¼ä©¦ÈÕ©¦´ïÊ±¼ä©¦ÈÕ©¦³µÖÖ©¦©¦<BR> 
¡¡¡¡©À©¤©¤©Ð©Ø©Ð©Ø©Ð©¤©Ø©¤©¤©¤©à©¤©¤©à©¤©à©¤©¤©Ð©Ø©¤©à©¤©¤©¤©Ø©Ð©Ø©¤©Ð©Ø©È<BR> 
¡¡¡¡©¦»õÎï©¦°ü©¦¡¡©¦¡¡Ìå¡¡»ý¡¡©¦¼þÖØ©¦ÖØ©¦±£ÏÕ©¦»õÎï©¦¡¡¡¡¡¡¡¡©¦¡¡¡¡©¦¡¡©¦<BR> 
¡¡¡¡©¦Ãû³Æ©¦×°©¦¼þ©¦³¤¡Á¿í¡Á¸ß©¦£¨Ç§©¦Á¿©¦±£¼Û©¦¡¡¡¡©¦¼Æ·ÑÏîÄ¿©¦¼Æ·Ñ©¦µ¥©¦<BR> 
¡¡¡¡©¦¼°¡¡©¦ÐÎ©¦Êý©¦£¨ÀåÃ×£©¡¡©¦¿Ë£©©¦¡¡©¦¼Û¸ñ©¦µÈ¼¶©¦¡¡¡¡¡¡¡¡©¦ÖØÁ¿©¦¼Û©¦<BR> 
¡¡¡¡©¦¹æ¸ñ©¦Ê½©¦¡¡©¦¡¡¡¡¡¡¡¡¡¡©¦¡¡¡¡©¦¶Ö©¦¡¡¡¡©¦¡¡¡¡©¦¡¡¡¡¡¡¡¡©¦¡¡¡¡©¦¡¡©¦<BR> 
¡¡¡¡©À©¤©¤©à©¤©à©¤©à©¤©¤©¤©¤©¤©à©¤©¤©à©¤©à©¤©¤©à©¤©¤©à©¤©¤©¤©¤©à©¤©¤©à©¤©È<BR> 
¡¡¡¡©¦¡¡¡¡©¦¡¡©¦¡¡©¦¡¡¡¡¡¡¡¡¡¡©¦¡¡¡¡©¦¡¡©¦¡¡¡¡©¦¡¡¡¡©¦ÔË¡¡·Ñ¡¡©¦¡¡¡¡©¦¡¡©¦<BR> 
¡¡¡¡©¦¡¡¡¡©¦¡¡©¦¡¡©¦¡¡¡¡¡¡¡¡¡¡©¦¡¡¡¡©¦¡¡©¦¡¡¡¡©¦¡¡¡¡©À©¤©¤©¤©¤©à©¤©¤©à©¤©È<BR> 
¡¡¡¡©À©¤©¤©à©¤©à©¤©à©¤©¤©¤©¤©¤©à©¤©¤©à©¤©à©¤©¤©à©¤©¤©È×°Ð¶·Ñ¡¡©¦¡¡¡¡©¦¡¡©¦<BR> 
¡¡¡¡©¦¡¡¡¡©¦¡¡©¦¡¡©¦¡¡¡¡¡¡¡¡¡¡©¦¡¡¡¡©¦¡¡©¦¡¡¡¡©¦¡¡¡¡©À©¤©¤©¤©¤©à©¤©¤©à©¤©È<BR> 
¡¡¡¡©¦¡¡¡¡©¦¡¡©¦¡¡©¦¡¡¡¡¡¡¡¡¡¡©¦¡¡¡¡©¦¡¡©¦¡¡¡¡©¦¡¡¡¡©¦¡¡¡¡¡¡¡¡©¦¡¡¡¡©¦¡¡©¦<BR> 
¡¡¡¡©À©¤©¤©Ø©¤©Ø©¤©Ø©¤©¤©¤©¤©¤©Ø©¤©¤©Ø©¤©Ø©¤©¤©Ø©¤©¤©à©¤©¤©¤©¤©à©¤©¤©à©¤©È<BR> 
¡¡¡¡©¦¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡©¦¡¡¡¡¡¡¡¡©¦¡¡¡¡©¦¡¡©¦<BR> 
¡¡¡¡©À©¤©¤©Ð©¤©Ð©¤©Ð©¤©¤©¤©¤©¤©Ð©¤©¤©Ð©¤©Ð©¤©¤©Ð©¤©¤©à©¤©¤©¤©¤©Ø©¤©¤©Ø©¤©È<BR> 
¡¡¡¡©¦ºÏ¡¡©¦¡¡©¦¡¡©¦¡¡¡¡¡¡¡¡¡¡©¦¡¡¡¡©¦¡¡©¦¡¡¡¡©¦¼Æ·Ñ©¦¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡©¦<BR> 
¡¡¡¡©¦¼Æ¡¡©¦¡¡©¦¡¡©¦¡¡¡¡¡¡¡¡¡¡©¦¡¡¡¡©¦¡¡©¦¡¡¡¡©¦Àï³Ì©¦¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡©¦<BR> 
¡¡¡¡©À©¤©¤©Ø©¤©à©¤©Ø©¤©Ð©¤©¤©¤©Ø©Ð©¤©Ø©Ð©Ø©¤©¤©Ø©Ð©¤©Ø©Ð©¤©¤©¤©¤©Ð©¤©¤©¤©È<BR> 
¡¡¡¡©¦ÍÐ¡¡ÔË¡¡©¦¡¡¡¡¡¡©¦¸¶¿îÈË¡¡©¦¡¡¡¡©¦³ÐÔËÈË¡¡©¦¡¡¡¡©¦³ÐÔËÈË¡¡©¦¡¡¡¡¡¡©¦<BR> 
¡¡¡¡©¦¼ÇÔØÊÂÏî©¦¡¡¡¡¡¡©¦ÒøÐÐÕÊºÅ©¦¡¡¡¡©¦¼ÇÔØÊÂÏî©¦¡¡¡¡©¦ÒøÐÐÕÊºÅ©¦¡¡¡¡¡¡©¦<BR> 
¡¡¡¡©À©¤©Ð©¤©¤©Ø©¤©¤©¤©Ø©¤©¤©¤©¤©Ø©¤©¤©Ø©¤©¤©¤©¤©Ø©¤©¤©Ø©Ð©¤©¤©¤©à©¤©¤©¤©È<BR> 
¡¡¡¡©¦×¢©¦£±£®ÍÐÔËÈËÇëÎðÌîÐ´´ÖÏßÀ¸ÄÚµÄÏîÄ¿¡£¡¡¡¡¡¡¡¡¡¡¡¡©¦ÍÐÔËÈË©¦³ÐÔËÈË©¦<BR> 
¡¡¡¡©¦Òâ©¦£²£®»õÎïÃû³ÆÓ¦ÌîÐ´¾ßÌåÆ·Ãû£¬Èç»õÎïÆ·Ãû¹ý¶à£¬¡¡©¦Ç©ÕÂ¡¡©¦Ç©ÕÂ¡¡©¦<BR> 
¡¡¡¡©¦ÊÂ©¦¡¡¡¡²»ÄÜÔÚÔËµ¥ÄÚÖðÒ»ÌîÐ´ÐëÁí¸½ÎïÆ·Çåµ¥¡£¡¡¡¡¡¡©¦¡¡¡¡¡¡©¦¡¡¡¡¡¡©¦<BR> 
¡¡¡¡©¦Ïî©¦£³£®±£ÏÕ»ò±£¼Û»õÎï£¬ÔÚÏàÓ¦¼Û¸ñÀ¸ÖÐÌîÐ´»õÎïÉùÃ÷©¦ÄêÔÂÈÕ©¦ÄêÔÂÈÕ©¦<BR> 
¡¡¡¡©¦¡¡©¦¡¡¡¡¼Û¸ñ¡£¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡©¦¡¡¡¡¡¡©¦¡¡¡¡¡¡©¦<BR> 
¡¡¡¡©¸©¤©Ø©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©¤©Ø©¤©¤©¤©Ø©¤©¤©¤©¼¡²ËµÃ÷¡³<BR> 
¡¡¡¡£±£®ÌîÔÚÒ»ÕÅ»õÎïÔËµ¥ÄÚµÄ»õÎï±ØÐëÊÇÊôÍ¬Ò»ÍÐÔËÈË¡£¶ÔÆ´×°·ÖÐ¶»õÎï£¬Ó¦½«Ã¿Ò»Æ´×°»ò·ÖÐ¶Çé¿öÔÚÔËµ¥¼ÇÊÂÀ¸ÄÚ×¢Ã÷¡£Ò×¸¯Ê´¡¢Ò×Ëé»õÎï¡¢Ò×ÒçÂ©µÄÒºÌå¡¢Î£ÏÕ»õÎïÓëÆÕÍ¨»õÎïÒÔ¼°ÐÔÖÊÏàµÖ´¥¡¢ÔËÊäÌõ¼þ²»Í¬µÄ»õÎï£¬²»µÃÓÃÍ¬Ò»ÕÅÔËµ¥ÍÐÔË¡£ÍÐÔËÈË¡¢³ÐÔËÈËÐÞ¸ÄÔËµ¥Ê±£¬ÐëÇ©×Ö¸ÇÕÂ¡£<BR> 
¡¡¡¡£²£®±¾ÔËµ¥Ò»Ê½£²·Ý£º¢ÙÊÜÀí´æ¸ù£»¢ÚÍÐÔË»ØÖ´¡£<BR> 
¡¡¡¡<BR> 
¡¡¡¡<BR> 
¡¡¡¡<BR> 
¡¡¡¡¡¡¡¡ </p>
    </td>
  </tr>
</table>
<br>
<TABLE align=center border=0 cellPadding=0 cel$*pacing=0 width=560>
  <TBODY>
  <TR>
    <TD>
      <DIV align=center>Copyright (C) 2000 ¡$*ww.lawyers.net.cn ¡¡All right %*served.</DIV>
    </TD></TR>
  <TR bgColor=#000000>
    <TD hight="1"></TD></TR>
  <TR>
    <TD>
      <DIV align=center>°æÈ¨ËùÓÐ.ÉîÛÚÊÐÐ­Í¼ÍøÂç¿ª·¢ÓÐÏÞ¹«Ë¾</DIV></TD></TR></TBODY></TABLE>¡¡¡¡ 
<?Button_Back_Law();?></body></html>