<?php
// **************************************************************************
// * Get requires
// **************************************************************************
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

require_once(e_ADMIN . "auth.php");
require_once(e_HANDLER . "userclass_class.php");

if (e_LANGUAGE != "English" && file_exists(e_PLUGIN . "phonedir/languages/admin/" . e_LANGUAGE . ".php"))
{
    include_once(e_PLUGIN . "phonedir/languages/admin/" . e_LANGUAGE . ".php");
}
else
{
    include_once(e_PLUGIN . "phonedir/languages/admin/English.php");
}
if (e_LANGUAGE != "English" && file_exists(e_PLUGIN . "phonedir/languages/" . e_LANGUAGE . ".php"))
{
    include_once(e_PLUGIN . "phonedir/languages/" . e_LANGUAGE . ".php");
}
else
{
    include_once(e_PLUGIN . "phonedir/languages/English.php");
}
// **************************************************************************
// Create objects
// **************************************************************************
$pdir_action = $_REQUEST['pdir_action'];
$pdir_convert = new convert;
// **************************************************************************
// * If we are updating then update or insert the record
// **************************************************************************
if ($pdir_action == 'update')
{
    if (empty($_REQUEST['pd_last_name']))
    {
        // no name specified
        $pdir_text .= "<table style='width:97%' class='fborder'><tr><td  class='fcaption' style='text-align: left;' colspan='2'><strong>" . phonedir_ADLAN_71 . "</strong></td></tr></table>";
    }
    else
    {
        // Handle photos
        if ($_POST['pdir_delpic'] == "1")
        {
            unlink(e_PLUGIN . "phonedir/photos/" . $_POST['pd_picture']);
            $_POST['pd_picture'] = "";
        }

        if (!empty($_FILES['file_userfile']['name']))
        {
            $userid = $_POST['pdir_cuserid'] . "_";

            require_once("upload_pic.php");
            $pdir_up = pdir_fileup("file_userfile", e_PLUGIN . "phonedir/photos/", $userid);
            switch ($pdir_up['result'])
            {
                case "0":
                default:
                    $pdir_upmess = phonedir_ADLAN_129;
                    $cpic = "";
                    $file = "";
                    break;
                case "1":
                    $pdir_upmess = "";
                    $cpic = $pdir_up['filename'];
                    $file = $pdir_up['filename'];
                    $cmod = "./photos/" . $cpic;
                    $res = chmod($cmod, 0644);
                    break;
                case "2":
                    $pdir_upmess = phonedir_ADLAN_130;
                    $cpic = "";
                    $file = "";
                    break;
                case "3":
                    $pdir_upmess = phonedir_ADLAN_131;
                    $cpic = "";
                    $file = "";
                    break;
            }
        }
        else
        {
            $cpic = "";
        }
        // end photos
        $pd_id = $_REQUEST['pd_id'];
        if ($pd_id == 0)
        {
            // New record so add it
            $pdir_args = "
			'0',
			'" . $tp->toDB($_REQUEST['pd_last_name']) . "',
			'" . $tp->toDB($_REQUEST['pd_first_name']) . "',
			'" . $tp->toDB($_REQUEST['pd_work_phone']) . "',
			'" . $tp->toDB($_REQUEST['pd_fax']) . "',
			'" . $tp->toDB($_REQUEST['pd_mobile']) . "',
			'" . $tp->toDB($_REQUEST['pd_department']) . "',
			'" . $tp->toDB($_REQUEST['pd_site']) . "',
			'" . $tp->toDB($_REQUEST['pd_email']) . "',
			'" . $tp->toDB($_REQUEST['pd_comments']) . "',
			'" . $tp->toDB($_REQUEST['pd_centrex']) . "',
			'" . $tp->toDB($_REQUEST['pd_dir_cat']) . "',
			'" . $tp->toDB($_REQUEST['pd_officed']) . "',
			'" . $tp->toDB($_REQUEST['pd_jobtitle']) . "',
			'" . time() . "',
			'" . USERID . "." . $tp->toDB(USERNAME) . "',
			'" . $tp->toDB($cpic) . "',
			'" . $tp->toDB($_REQUEST['pd_address1']) . "',
			'" . $tp->toDB($_REQUEST['pd_address2']) . "',
			'" . $tp->toDB($_REQUEST['pd_town']) . "',
			'" . $tp->toDB($_REQUEST['pd_county']) . "',
			'" . $tp->toDB($_REQUEST['pd_postcode']) . "',
			'" . $tp->toDB($_REQUEST['pd_country']) . "'";
            // New person created
            if ($sql->db_Insert("pd_directory", $pdir_args))
            {
                $pdir_msg .= "<tr><td  class='forumheader3' style='text-align: left;' colspan='2'><b>" . phonedir_ADLAN_44 . "</b></td></tr>";
            }
            else // New person could not be created
                {
                    $pdir_msg .= "<tr><td  class='forumheader3' style='text-align: left;' colspan='2'><b>" . phonedir_ADLAN_89 . "</b></td></tr>";
            }
        }
        else
        {
            // Update existing
            $pdir_args .= "
		    pd_last_name='" . $tp->toDB($_REQUEST['pd_last_name']) . "',
			pd_first_name='" . $tp->toDB($_REQUEST['pd_first_name']) . "',
			pd_address1='" . $tp->toDB($_REQUEST['pd_address1']) . "',
			pd_address2='" . $tp->toDB($_REQUEST['pd_address2']) . "',
			pd_town='" . $tp->toDB($_REQUEST['pd_town']) . "',
			pd_county='" . $tp->toDB($_REQUEST['pd_county']) . "',
			pd_postcode='" . $tp->toDB($_REQUEST['pd_postcode']) . "',
			pd_country='" . $tp->toDB($_REQUEST['pd_country']) . "',
			pd_work_phone='" . $tp->toDB($_REQUEST['pd_work_phone']) . "',
			pd_fax='" . $tp->toDB($_REQUEST['pd_fax']) . "',
			pd_mobile='" . $tp->toDB($_REQUEST['pd_mobile']) . "',
			pd_department='" . $tp->toDB($_REQUEST['pd_department']) . "',
			pd_site='" . $tp->toDB($_REQUEST['pd_site']) . "',
			pd_email='" . $tp->toDB($_REQUEST['pd_email']) . "',
			pd_comments='" . $tp->toDB($_REQUEST['pd_comments']) . "',
			pd_centrex='" . $tp->toDB($_REQUEST['pd_centrex']) . "',
			pd_dir_cat='" . $tp->toDB($_REQUEST['pd_dir_cat']) . "',
			pd_officed='" . $tp->toDB($_REQUEST['pd_officed']) . "', ";
            if (!empty($cpic))
            {
                $pdir_args .= "pd_picture='" . $tp->toDB($cpic) . "', ";
            }

            $pdir_args .= "pd_jobtitle='" . $tp->toDB($_REQUEST['pd_jobtitle']) . "',
			pd_updated='" . time() . "',
			pd_updatedby='" . USERID . "." . $tp->toDB(USERNAME) . "'
		    where pd_id='$pd_id'";
            if ($sql->db_Update("pd_directory", $pdir_args))
            {
                // Changes saved
                $pdir_msg .= "<tr><td  class='forumheader3' style='text-align: left;' colspan='2'><b>" . phonedir_ADLAN_43 . "</b></td></tr>";
            }
            else
            {
                // changes not saved
                $pdir_msg .= "<tr><td  class='forumheader3' style='text-align: left;' colspan='2'><b>" . phonedir_ADLAN_90 . "</b></td></tr>";
            }
        }
    }
}
// We are creating, editing or deleting a record
if ($pdir_action == 'dothings')
{
    $pd_id = $_REQUEST['pdir_selperson'];
    $pdir_do = $_REQUEST['pdir_recdel'];
    $pdir_dodel = false;
    switch ($pdir_do)
    {
        case '1': // Edit existing record
            {
                // We edit the record
                $sql->db_Select("pd_directory", "*", "pd_id='" . intval($pd_id) . "'");
                $pdir_row = $sql->db_Fetch() ;
                extract($pdir_row);
                $pdir_cap1 = phonedir_ADLAN_10 . " " . phonedir_ADLAN_91;
                break;
            }

        case '2': // New pd_department
            {
                // Create new record
                $pd_id = 0;
                $pd_dir_catid = $_REQUEST['pd_dir_catid'];
                // print $pd_dir_catid;
                // set all fields to zero/blank
                $pdir_cap1 = phonedir_ADLAN_11 . " " . phonedir_ADLAN_91;
                break;
            }

        case '3':
            {
                // delete the record
                if ($_REQUEST['pdir_okdel'] == '1')
                {
                    $sql->db_Select("pd_directory", "*", "pd_id='" . intval($pd_id) . "'");
                    $pdir_row = $sql->db_Fetch() ;
                    unlink("./photos/" . $tp->toFORM($pdir_row['pd_picture']));
                    $pdir_msg .= phonedir_ADLAN_54 . "<br />";
                    if ($sql->db_Delete("pd_directory", " pd_id='" . intval($pd_id) . "'"))
                    {
                        $pdir_text .= "<table style='width:97%' class='fborder'><tr>
						<td  class='fcaption' style='text-align: left;' colspan='2'>" . phonedir_ADLAN_12 . "</td></tr>
						<tr><td  class='forumheader3' style='text-align: left;' colspan='2'><strong>" . phonedir_ADLAN_28 . "</strong></td></tr>
						<tr><td  class='fcaption' style='text-align: left;' colspan='2'><a href=''>" . phonedir_ADLAN_69 . "</a></td></tr>
						</table>";
                    }
                    else
                    {
                        $pdir_text .= "<table style='width:97%' class='fborder'><tr>
						<td  class='fcaption' style='text-align: left;' colspan='2'>" . phonedir_ADLAN_12 . "</td></tr>
						<tr><td  class='forumheader3' style='text-align: left;' colspan='2'><strong>" . phonedir_ADLAN_103 . "</strong></td></tr>
						<tr><td  class='fcaption' style='text-align: left;' colspan='2'><a href=''>" . phonedir_ADLAN_69 . "</a></td>
						</tr></table>";
                    }
                }
                else
                {
                    $pdir_text .= "<table style='width:97%' class='fborder'><tr>
					<td  class='fcaption' style='text-align: left;' colspan='2'>" . phonedir_ADLAN_12 . "</td></tr>
					<tr><td  class='forumheader3' style='text-align: left;' colspan='2'><strong>" . phonedir_ADLAN_27 . "</strong></td></tr>
					<tr><td  class='fcaption' style='text-align: left;' colspan='2'><a href=''>" . phonedir_ADLAN_69 . "</a></td>
					</tr></table>";
                }

                $pdir_dodel = true;
            }
    } // end switch
    if (!$pdir_dodel)
    {
        if ($pd_id > 0)
        {
            $sql->db_Select("pd_directory", "*", "pd_id='$pd_id'");
            $sqlrow = $sql->db_Fetch();
            extract($sqlrow);
        }

        $pdir_text .= "<form enctype='multipart/form-data' id='pduser' method='post' action='" . e_SELF . "'>
		<div>
		<input type='hidden' name='pd_id' value='" . intval($pd_id) . "' />
		<input type='hidden' name='pdir_action' value='update' />

		</div>
		<table style='width:97%;' class='fborder'>
		<tr>
	    <td colspan='2' align='center' class='fcaption'>" . phonedir_ADLAN_23 . "</td>
		</tr>
		<tr>
	    <td width='25%' class='forumheader3'>" . LAN_phonedir_41 . "</td>
	    <td width='75%' class='forumheader3'><input type='text' class='tbox' size='30' name='pd_last_name' value='" . $tp->toFORM($pd_last_name) . "' /></td>
		</tr>
		<tr>
		<td width='25%' class='forumheader3'>" . LAN_phonedir_42 . "</td>
	   <td width='75%' class='forumheader3'><input type='text' class='tbox' size='30' name='pd_first_name' value='" . $tp->toFORM($pd_first_name) . "' /></td>
  </tr>";
        if ($pref['phonedir_usesite'] != 1)
        {
            // use address fields
            $pdir_text .= "
	<tr>
	    <td width='25%' class='forumheader3'>" . phonedir_ADLAN_132 . "</td>
	    <td width='75%' class='forumheader3'><input type='text' class='tbox' style='width:40%' name='pd_address1' value='" . $tp->toFORM($pd_address1) . "' /></td>
  	</tr>
  	<tr>
	    <td width='25%' class='forumheader3'>" . phonedir_ADLAN_133 . "</td>
	    <td width='75%' class='forumheader3'><input type='text' class='tbox' style='width:40%' name='pd_address2' value='" . $tp->toFORM($pd_address2) . "' /></td>
  	</tr>
	<tr>
    	<td width='25%' class='forumheader3'>" . phonedir_ADLAN_134 . "</td>
    	<td width='75%' class='forumheader3'><input type='text' class='tbox' style='width:40%' name='pd_town' value='" . $tp->toFORM($pd_town) . "' /></td>
  	</tr>
	<tr>
    	<td width='25%' class='forumheader3'>" . phonedir_ADLAN_135 . "</td>
    	<td width='75%' class='forumheader3'><input type='text' class='tbox' style='width:40%' name='pd_county' value='" . $tp->toFORM($pd_county) . "' /></td>
  	</tr>
	<tr>
    	<td width='25%' class='forumheader3'>" . phonedir_ADLAN_136 . "</td>
    	<td width='75%' class='forumheader3'><input type='text' class='tbox' style='width:30%' name='pd_postcode' value='" . $tp->toFORM($pd_postcode) . "' /></td>
  	</tr>
	<tr>
    	<td width='25%' class='forumheader3'>" . phonedir_ADLAN_137 . "</td>
    	<td width='75%' class='forumheader3'><input type='text' class='tbox' style='width:40%' name='pd_country' value='" . $tp->toFORM($pd_country) . "' /></td>
  	</tr>
	  ";
        }

        $pdir_text .= "<tr>
    <td width='25%' class='forumheader3'>" . LAN_phonedir_30 . "</td>
    <td width='75%' class='forumheader3'><input type='text' class='tbox' name='pd_work_phone' value='" . $tp->toFORM($pd_work_phone) . "' /></td>
  </tr>
  <tr>
    <td width='25%' class='forumheader3'>" . LAN_phonedir_20 . "</td>
    <td width='75%' class='forumheader3'><input type='text' class='tbox' name='pd_centrex' value='" . $tp->toFORM($pd_centrex) . "' /></td>
  </tr>
  <tr>
    <td width='25%' class='forumheader3'>" . LAN_phonedir_4 . "</td>
    <td width='75%' class='forumheader3'><input type='text' class='tbox' name='pd_mobile' value='" . $tp->toFORM($pd_mobile) . "' /></td>
  </tr>
  <tr>
    <td width='25%' class='forumheader3'>" . LAN_phonedir_5 . "</td>
    <td width='75%' class='forumheader3'><input type='text' class='tbox' name='pd_email' style='width:70%'  value='" . $tp->toFORM($pd_email) . "' /></td>
  </tr>
  <tr>
    <td width='25%' class='forumheader3'>" . LAN_phonedir_6 . "</td>
    <td width='75%' class='forumheader3'><textarea rows='4' cols='50' style='width:80%' class='tbox' name='pd_comments'>" . $tp->toFORM($pd_comments) . "</textarea></td>
  </tr>";
        // Photo
        if (empty($pd_picture) || !file_exists("./photos/" . $pd_picture))
        {
            $pdir_text .= "<tr><td class=\"forumheader3\" style='vertical-align:top;' >" . phonedir_ADLAN_113 . ":</td><td class=\"forumheader3\" style='width:80%;text-align:left;vertical-align:top;'>
				<input class='tbox' name='file_userfile' type='file' size='47' />&nbsp;<br /><i>" . phonedir_ADLAN_112 . "</i></td></tr>";
        }
        else
        {
            $pdir_text .= "<tr>
				<td class=\"forumheader3\" style='vertical-align:top;' >" . phonedir_ADLAN_113 . ":</td>
				<td class=\"forumheader3\" style='width:80%;text-align:left;vertical-align:top;'>" . $pd_picture . "<br /><i>" . phonedir_ADLAN_120 . "</i>
<br />" . phonedir_ADLAN_114 . "<input type='checkbox' name='pdir_delpic' value='1' />
				<input type='hidden' name='pd_picture' value='$pd_picture' /></td>
				</tr>";
        }
        // end photo
        if ($pref['phonedir_usesite'])
        {
            $pdir_text .= "
  <tr>
    <td width='25%' class='forumheader3'>" . LAN_phonedir_3 . "</td>
	<td width='75%' class='forumheader3'><select class='tbox' name='pd_site'><option value='0'>" . phonedir_ADLAN_37 . "</option>";
            $sql->db_Select("pd_sites", "pd_site_id,pd_site_name", " order by pd_site_name", "nowhere");
            while ($sqloptrow = $sql->db_Fetch())
            {
                extract($sqloptrow);
                $pdir_text .= "<option ";
                if ($pd_site == $pd_site_id) $pdir_text .= " selected='selected' ";
                $pdir_text .= " value='$pd_site_id'>" . $tp->toFORM($pd_site_name) . "</option>";
            }

            $pdir_text .= "</select></td>
   </tr>";
        }
        if ($pref['phonedir_usedept'])
        {
            $pdir_text .= "
			<tr>
    <td width='25%' class='forumheader3'>" . LAN_phonedir_7 . "</td>
	<td width='75%' class='forumheader3'><select class='tbox' name='pd_department'><option value='0'>" . phonedir_ADLAN_37 . "</option>";
            $sql->db_Select("pd_department", "pd_dept_id,pd_dept_name", " order by pd_dept_name", "nowhere");
            while ($sqloptrow = $sql->db_Fetch())
            {
                extract($sqloptrow);
                $pdir_text .= "<option ";
                if ($pd_department == $pd_dept_id) $pdir_text .= " selected='selected' ";
                $pdir_text .= " value='$pd_dept_id'>" . $tp->toFORM($pd_dept_name) . "</option>";
            }
            $pdir_text .= "</select></td>
  </tr>";
        }

        if ($pref['phonedir_usejob'] == 1)
        {
            $pdir_text .= "<tr>
    <td width='25%' class='forumheader3'>" . phonedir_ADLAN_39 . "</td>
	<td width='75%' class='forumheader3'><select class='tbox' name='pd_jobtitle'><option value='0'>" . phonedir_ADLAN_37 . "</option>";
            $sql->db_Select("pd_jobtitle", "pd_job_id,pd_job_title", " order by pd_job_title", "nowhere");
            while ($sqloptrow = $sql->db_Fetch())
            {
                extract($sqloptrow);
                $pdir_text .= "<option ";
                if ($pd_job_id == $pd_jobtitle) $pdir_text .= " selected='selected' ";
                $pdir_text .= " value='$pd_job_id'>" . $tp->toFORM($pd_job_title) . "</option>";
            }

            $pdir_text .= "</select></td></tr>";
        }

        $pdir_text .= "
   <tr>
    <td width='25%' class='forumheader3'>" . LAN_phonedir_48 . "</td>
	<td width='75%' class='forumheader3'>
	<select class='tbox' name='pd_dir_cat'><option value='0'>" . phonedir_ADLAN_37 . "</option>";
        $sql->db_Select("pd_categories", "pd_cat_id,pd_cat_desc", "order by pd_cat_desc", "nowhere");
        if ($pd_id == 0)
        {
            $pd_dir_cat = $pd_dir_catid;
        }

        while ($sqloptrow = $sql->db_Fetch())
        {
            extract($sqloptrow);
            // print "-" . $pd_dir_cat . "-" . $pd_cat_id . "-<br>";
            $pdir_text .= "<option ";
            if ($pd_dir_cat == $pd_cat_id)
            {
                $pdir_text .= " selected='selected' ";
            }
            $pdir_text .= " value='$pd_cat_id'>" . $tp->toFORM($pd_cat_desc) . "</option>";
        }

        $pdir_text .= "</select></td>
   </tr>";
        if ($pref['phonedir_useoffice'])
        {
            $pdir_text .= "
   <tr>
   <td width='25%' class='forumheader3'>" . LAN_phonedir_12 . "</td>
	<td width='75%' class='forumheader3'><input type='checkbox' class='tbox' name='pd_officed' value='1'";
            if ($pd_officed == 1) $pdir_text .= " checked ";
            $pdir_text .= " /></td>
  </tr>    <tr>";
        }

        $pdir_text .= "
    <td width='40%' class='forumheader3'>" . phonedir_ADLAN_55 . "</td>
    <td width='60%' class='forumheader3'>" . ($pd_updated <> 0?$pdir_convert->convert_date($pd_updated, "long"):"&nbsp;") . "</td>
  </tr>";
        $pdir_posterarray = explode(".", $pd_updatedby,2);
        $pdir_userid = $pdir_posterarray[0];
        $pdir_username = $pdir_posterarray[1];
        $pdir_text .= "<tr>
   <td width='40%' class='forumheader3'>" . phonedir_ADLAN_56 . "</td>
    <td width='60%' class='forumheader3'><a href='../../user.php?id.$pdir_userid'>" . $tp->toFORM($pdir_username) . "</a></td>
  </tr>
  <tr>
    <td colspan='2' class='forumheader3'>
		<input type='hidden' value='" . $_REQUEST['pd_dir_catid'] . "' name='pd_dir_catid' />
	<input type='submit' name='pdir_usrsave' class='tbox' value='" . phonedir_ADLAN_14 . "' /></td>
  </tr></table></form>";
    }
}
else
{
    // Get the category names to display in combo box
    // then a list of people in that category
    // then display actions available
    $pd_dir_catid = $_REQUEST['pd_dir_catid'];
    if ($sql->db_Select("pd_categories", "pd_cat_id, pd_cat_desc", " order by pd_cat_order ", "nowhere"))
    {
        // *
        // Get a list of all the categories
        // *
        while ($pdir_row = $sql->db_Fetch())
        {
            extract($pdir_row);
            if ($pd_dir_catid == 0)
            {
                // *
                // If there isnt a category they are already using then make it the first in the list
                // *
                $pd_dir_catid = $pd_cat_id;
            }

            $pdir_opts .= "<option value='$pd_cat_id' " .
            ($pd_dir_catid == $pd_cat_id?" selected='selected'":"") . ">" . $tp->toFORM($pd_cat_desc) . "</option>";
        } // while;
        $pdir_opts .= "<option value='-1' " .
        ($pd_dir_catid == -1?" selected='selected'":"") . ">" . phonedir_ADLAN_102 . "</option>";
    }
    else
    {
        // *
        // No categories defined
        // *
        $pdir_opts .= "<option value='' >" . phonedir_ADLAN_93 . "</option>";
    }
    // *
    // Build up select of users in this category
    // *
    if ($pd_dir_catid < 0)
    {
        $pd_arg = " order by pd_last_name";
    }
    else
    {
        $pd_arg = " where pd_dir_cat='$pd_dir_catid' order by pd_last_name";
    }
    $pdir_yes = false;
    if ($sql->db_Select("pd_directory", "pd_id,pd_last_name,pd_first_name", $pd_arg, "nowhere"))
    {
        $pdir_yes = true;
        // *
        // Get all the users in this category
        // *
        while ($pdir_row = $sql->db_Fetch())
        {
            extract($pdir_row);
            $pd_dir_catopt .= "<option value='$pd_id'>" . $tp->toFORM($pd_last_name) . ", " . $tp->toFORM($pd_first_name) . "</option>";
        }
    }
    else
    {
        // *
        // Nobody defined in this category
        // *
        $pd_dir_catopt .= "<option value='0'>" . phonedir_ADLAN_94 . "</option>";
    }

    $pdir_text .= "<form id='edcat' action='" . e_SELF . "' method='post'>
	<table style='width:97%' class='fborder'>
	<tr><td colspan='2' class='fcaption'>" . phonedir_ADLAN_92 . "</td></tr>
	<tr>
	<td style='width:20%; vertical-align:top;' class='forumheader3'>" . phonedir_ADLAN_16 . "</td>
	<td style='width:80%; vertical-align:top;' class='forumheader3'>

	<select class='tbox' name='pd_dir_catid' onchange='this.form.submit()'>" . $pdir_opts . "</select>

	</td></tr>
	</table></form>

	<form id='edperson' method='post' action='" . e_SELF . "'>
	<table style='width:97%' class='fborder'>
	<tr><td colspan='2' class='fcaption'>" . phonedir_ADLAN_23 . "</td></tr>$pdir_msg
	<tr><td style='width:20%' class='forumheader3'>" . phonedir_ADLAN_91 . "</td><td  class='forumheader3'>
		<select name='pdir_selperson' class='tbox'>$pd_dir_catopt</select>
	</td></tr>
	<tr><td style='width:20%' class='forumheader3'>" . phonedir_ADLAN_65 . "</td><td  class='forumheader3'>
	<input type='radio' name='pdir_recdel' value='1' " . ($pdir_yes?"checked='checked'":"disabled='disaabled'") . "/> " . phonedir_ADLAN_10 . "<br />
	<input type='radio' name='pdir_recdel' value='2' " . (!$pdir_yes?"checked='checked'":"") . "/> " . phonedir_ADLAN_11 . "<br />
	<input type='radio' name='pdir_recdel' value='3' /> " . phonedir_ADLAN_12 . "
	<input type='checkbox' name='pdir_okdel' value='1' />" . phonedir_ADLAN_13 . "</td></tr>
	<tr><td colspan='2' class='fcaption'  style='text-align: left;'>
	<input type='hidden' value='dothings' name='pdir_action' />
	<input type='hidden' value='$pd_dir_catid' name='pd_dir_catid' />
	<input type='submit' name='submits' value='" . phonedir_ADLAN_14 . "' class='button' /></td></tr>
	</table></form>";
}

$ns->tablerender(phonedir_ADLAN_23, $pdir_text);
require_once(e_ADMIN . "footer.php");

?>