<?php // ***************************************************************// *// *		Title		:	Corporate Phone Directory// *// *		Author		:	Barry Keal// *// *		Date		:	6 May 2004// *// ***************************************************************require_once("../../class2.php");if (e_LANGUAGE !="English" && file_exists(e_PLUGIN . "phonedir/languages/admin/" . e_LANGUAGE . ".php")){    include_once(e_PLUGIN . "phonedir/languages/admin/" . e_LANGUAGE . ".php");} else{    include_once(e_PLUGIN . "phonedir/languages/admin/English.php");} if (!getperms("P")){    header("location:" . e_HTTP . "index.php");    exit;} require_once(e_ADMIN . "auth.php");require_once(e_HANDLER . "userclass_class.php");$pdir_db=new DB;$pdir_catid=$_POST['pdir_catid'];// # Are we saving the records$pd_text .="<form id='edcat' method='post' action='".e_SELF."'><table class='fborder' width='97%'><tr><td colspan='2' class='fcaption'>" . phonedir_ADLAN_96 . "</td></tr>";if (isset($_POST['pdir_catsave'])){    $pdir_rec=$_POST['pd_rec'];    $pdir_catorder=$_POST['pd_catorder'];    $pdcount=0;    foreach ($pdir_rec as $pd_row)    {        $pdir_db->db_Update("pd_categories", "pd_cat_order='$pdir_catorder[$pdcount]' where pd_cat_id='$pd_row'");        $pdcount ++;    }     $pd_text .="<tr><td colspan='2' class='forumheader3'><strong>" . phonedir_ADLAN_97 . "</strong></td></tr>";} if ($pdir_db->db_Select("pd_categories", "pd_cat_id,pd_cat_desc", " order by pdcat_order", "nowhere")){    while ($pdir_row=$pdir_db->db_Fetch())    {        extract($pdir_row);        $pdir_opts .="<option value='$pd_cat_id'" .        ($pdcat_id==$pdir_catid?"selected='selected'":" ") . ">$pd_cat_desc</option>";    } } else{    $pdir_opts="<option value='0'>" . phonedir_ADLAN_37 . "</option>";} $pd_text .="";// Count the categories// Get the categories$pd_count=$pdir_db->db_Select("pd_categories", "pd_cat_id");if ($pdir_db->db_Select("pd_categories", "pd_cat_id,pd_cat_desc,pd_cat_order", " order by pd_cat_order", "nowhere")){    while ($pdir_row=$pdir_db->db_Fetch())    {        extract($pdir_row);        $pd_text .="		<tr>		<td style='width:40%;' class='forumheader3'>" . $pd_cat_desc . "</td>        <td style='width:60%;' class='forumheader3'>		<input type='hidden' name='pd_rec[]' value='$pd_cat_id' />	    <select name='pd_catorder[]' class='tbox'>";        for ($pd_i=1 ; $pd_i <=$pd_count ; $pd_i ++)        {            $pd_text .="<option value='$pd_i'" .            ($pd_cat_order==$pd_i ? " selected='selected' " : "") . ">$pd_i</option>";        }         $pd_text .="</select></td></tr>";    } } else{    $pdir_opts="<option value='0'>" . phonedir_ADLAN_37 . "</option>";} $pd_text .="  <tr>  <td colspan='2' class='forumheader'><input type='submit' name='pdir_catsave' class='button' value='" . phonedir_ADLAN_95 . "' /></td>  </tr>  </table></form>";$ns->tablerender(phonedir_ADLAN_52, $pd_text);require_once (e_ADMIN . "footer.php");?>