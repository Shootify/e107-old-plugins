<?php
require_once("../../class2.php");
if (!defined('e107_INIT'))
{
    exit;
}

if (!getperms("P"))
{
    header("location:" . e_BASE . "index.php");
    exit;
}

include_lan(e_PLUGIN . "creative_writer/languages/" . e_LANGUAGE . ".php");
require_once(e_HANDLER . "userclass_class.php");
require_once(e_ADMIN . "auth.php");
$cwriter_action = $_POST['cwriter_action'];
$cwriter_edit = false;
// * If we are updating then update or insert the record
if ($cwriter_action == 'update')
{
    $cwriter_id = $_POST['cwriter_id'];
    if ($cwriter_id == 0)
    {
        // New record so add it
        $cwriter_args = "
		'0',
		'" . $tp->toDB($_POST['cw_genre_name']) . "',
		'" . $tp->toDB($_POST['cw_genre_icon']) . "',
		'" . time() . "'";
        if ($sql->db_Insert("cw_genre", $cwriter_args,false))
        {
            $cwriter_msg .= "<tr><td class='forumheader3' colspan='2'><strong>" . CWRITER_A12 . "</strong></td></tr>";
        }
    }
    else
    {
        // Update existing
        $cwriter_args = "
		cw_genre_name='" . $tp->toDB($_POST['cw_genre_name']) . "',
		cw_genre_icon='" . $tp->toDB($_POST['cw_genre_icon']) . "',
		cw_genre_lastupdated='" . time() . "'
		where cw_genre_id='$cwriter_id'";
        if ($sql->db_Update("cw_genre", $cwriter_args))
        {
            // Changes saved
            $cwriter_msg .= "<tr><td class='forumheader3' colspan='2'><b>" . CWRITER_A12 . "</b></td></tr>";
        }
    }
}
// We are creating, editing or deleting a record
if ($cwriter_action == 'dothings')
{
    $cwriter_id = $_POST['cwriter_selcat'];
    $cwriter_do = $_POST['cwriter_recdel'];
    $cwriter_dodel = false;

    switch ($cwriter_do)
    {
        case '1': // Edit existing record
            {
                // We edit the record
                $sql->db_Select("cw_genre", "*", "cw_genre_id='$cwriter_id'");
                $cwriter_row = $sql->db_Fetch() ;
                extract($cwriter_row);
                $cwriter_edit = true;
                break;
            }
        case '2': // New category
            {
                // Create new record
                $cwriter_id = 0;
                // set all fields to zero/blank
                $cwriter_edit = true;
                break;
            }
        case '3':
            {
                // delete the record
                if ($_POST['cwriter_okdel'] == '1')
                {
                    if ($sql->db_Select("cw_book", "cw_book_id", " where cw_book_genre='$cwriter_id'", "nowhere"))
                    {
                        $cwriter_msg .= "<tr><td class='forumheader3' colspan='2'><strong>" . CWRITER_A13 . "</strong></td></tr>";
                    }
                    else
                    {
                        if ($sql->db_Delete("cw_genre", " cw_genre_id='$cwriter_id'"))
                        {
                            $cwriter_msg .= "<tr><td class='forumheader3' colspan='2'><strong>" . CWRITER_A14 . "</strong></td></tr>";
                        }
                        else
                        {
                            $cwriter_msg .= "<tr><td class='forumheader3' colspan='2'><strong>" . CWRITER_A16 . "</strong></td></tr>";
                        }
                    }
                }
                else
                {
                    $cwriter_msg .= "<tr><td class='forumheader3' colspan='2'><strong>" . CWRITER_A15 . "</strong></td></tr>";
                }

                $cwriter_dodel = true;
                $cwriter_edit = false;
            }
    }

    if (!$cwriter_dodel)
    {
        $cwriter_text .= "
		<form id='myclassupdate' method='post' action='" . e_SELF . "'>
<div>
		<input type='hidden' value='$cwriter_id' name='cwriter_id' />
		<input type='hidden' value='update' name='cwriter_action' />
		</div>
		<table style='width:97%;' class='fborder'>
		<tr><td colspan='2' class='fcaption'>" . CWRITER_A17 . "
</td></tr>
		<tr><td style='width:20%;vertical-align:top;' class='forumheader3'>" . CWRITER_A10 . "</td><td  class='forumheader3'><input type='text' class='tbox' style='width:50%' name='cw_genre_name' value='" . $tp->toFORM($cw_genre_name) . "' /></td></tr>
		<tr><td style='width:20%;vertical-align:top;' class='forumheader3'>" . CWRITER_A11 . "</td><td  class='forumheader3'>";
        // get file list
        require_once(e_HANDLER . "file_class.php");
        $cwriter_fl = new e_file;

        $thumblist = $cwriter_fl->get_files(e_PLUGIN . "creative_writer/images/gicons", '');

        $cwriter_text .= "<input class='tbox' type='text' id='cw_genre_icon' name='cw_genre_icon' size='60' value='" . $tp->toFORM($cw_genre_icon) . "' maxlength='100' /><br />";
        foreach($thumblist as $icon)
        {
            $cwriter_text .= "<a href=\"javascript:insertext('" . $icon['fname'] . "','cw_genre_icon','newsicn')\"><img src='" . $icon['path'] . $icon['fname'] . "' style='border:0' alt='' /></a> ";
        }

        $cwriter_text .= "</td></tr>
		<tr><td colspan='2' class='fcaption'><input type='submit' name='submits' value='" . CWRITER_A24 . "' class='tbox' /></td></tr>
		</table></form>";
    }
}
if (!$cwriter_edit)
{
    // Get the category names to display in combo box
    // then display actions available
    $cwriter_yes = false;
    if ($sql2->db_Select("cw_genre", "cw_genre_id,cw_genre_name", " order by cw_genre_name", "nowhere"))
    {
        $cwriter_yes = true;
        while ($cwriter_row = $sql2->db_Fetch())
        {
            // extract($cwriter_row);
            $cwriter_catopt .= "<option value='" . $cwriter_row['cw_genre_id'] . "'" .
            ($cwriter_id == $cwriter_row['cw_genre_id']?" selected='selected'":"") . ">" . $tp->toFORM($cwriter_row['cw_genre_name']) . "</option>";
        }
    }
    else
    {
        $cwriter_catopt .= "<option value='0'>" . CWRITER_A18 . "</option>";
    }

    $cwriter_text .= "
	<form id='myclassform' method='post' action='" . e_SELF . "'>
<div>
<input type='hidden' value='dothings' name='cwriter_action' />
</div>
	<table width='97%' class='fborder'>
	<tr><td colspan='2' class='fcaption'>" . CWRITER_A4 . "	</td></tr>
	$cwriter_msg
	<tr><td style='width:20%;' class='forumheader3'>" . CWRITER_A9 . "</td><td  class='forumheader3'>
	<select name='cwriter_selcat' class='tbox'>$cwriter_catopt</select></td></tr>
	<tr><td style='width:20%;' class='forumheader3'>" . CWRITER_A19 . "</td><td  class='forumheader3'>
	<input type='radio' name='cwriter_recdel' value='1' " . ($cwriter_yes?"checked='checked'":"disabled='disabled'") . " /> " . CWRITER_A20 . "<br />
	<input type='radio' name='cwriter_recdel' value='2' " . (!$cwriter_yes?"checked='checked'":"") . "/> " . CWRITER_A21 . "<br />
	<input type='radio' name='cwriter_recdel' value='3' /> " . CWRITER_A22 . "
	<input type='checkbox' name='cwriter_okdel' value='1' />" . CWRITER_A23 . "</td></tr>
	<tr><td colspan='2' class='fcaption'>
	<input type='submit' name='submits' value='" . CWRITER_A24 . "' class='tbox' /></td></tr>


	</table></form>";
}

$ns->tablerender(CWRITER_A1, $cwriter_text);

require_once(e_ADMIN . "footer.php");

?>