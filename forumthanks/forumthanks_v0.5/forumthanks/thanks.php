<?php


   require_once("../../class2.php");
   require_once(HEADERF);
   global $tp;

   include_lan(e_PLUGIN.'forumthanks/languages/'.e_LANGUAGE.'/lan_thanks.php');
   include_lan(e_LANGUAGEDIR.e_LANGUAGE.'/lan_search.php');


   if (!defined("USER_WIDTH")){ define("USER_WIDTH","width:95%"); }

   $con = new convert;
   $tmp             = explode(".", e_QUERY);
   $action          = preg_replace('#\W#', '', $tmp[0]);
   $id1             =  intval($tmp[1]);
   $id2             =  intval($tmp[2]);
   $from            = (isset($tmp[3]) ? intval($tmp[3]) : 0);
   $view            = 10 ;


//----------------------------------------------

if ($action == "thank"){

   if ($pref['thanks_limit']>0)
       {
        //get pst cnt for usr for day
        $time = time()-86000;
        $cnt = $sql->db_count("forum_thanks","(*)","where Thanks_datestamp > $time");

         if ($cnt>=$pref['thanks_limit']){
                   $text = LAN_T33.$pref['thanks_limit'].LAN_T34."
                   <p>
                   <a href = '".e_PLUGIN."forum/forum_viewtopic.php?".$id1.".post'>Back to forum</a>
                   ";
                   $ns->tablerender(LAN_T35, $text);
                   require_once(FOOTERF);
         }

       }

   if (!USER){header("location:".e_PLUGIN."forum/forum_viewtopic.php?".$post_id.".post");
              exit;}
   if ($id2 == USERID){              //cant thank self!!
      header("location:".e_PLUGIN."forum/forum_viewtopic.php?".$id1.".post");
      exit;
      }
   $post_id        = $id1;
   $thankeduserid  = $id2;

   if (!$sql->db_select("forum_thanks","1","Thanks_ToUserID =".$thankeduserid." AND Thanks_FromUserID=".USERID." AND Thanks_PostID=".$post_id) )
      {$time = time();
       $insertstring = "null,".$thankeduserid.",".USERID.",".$post_id.",".$time;
       $sql->db_insert ("forum_thanks","$insertstring");
       }
      //Thanks_ID  Thanks_ToUserID  Thanks_FromUserID  Thanks_PostID
   header("location:".e_PLUGIN."forum/forum_viewtopic.php?".$post_id.".post");
   exit;
   }
//----------------------------------------------

if   ($action == "posts"){


    if ($id1==0){
           $where    = "AND ft.Thanks_ToUserID = ".$id2;
           $userinfo   = get_user_data($id2);
           $user_name  = $userinfo["user_name"];
           $pagehead = LAN_T17.$user_name.LAN_T18;
           $order      = "thread_datestamp DESC";}
    if ($id1==1){
           $where    = "AND ft.Thanks_FromUserID = ".$id2;
           $userinfo   = get_user_data($id2);
           $user_name  = $userinfo["user_name"];
           $pagehead = $user_name.LAN_T19;
           $order      = "thread_datestamp DESC";}
    if ($id1==2){

           $order     = "thankcount DESC";

           if ($id2==1)
               {$time     = time();
                $time     = $time - 31556926;
                $where    = "AND ft.Thanks_datestamp > $time";
                $pagehead = LAN_T7;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.4'>".LAN_T15." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.3'>".LAN_T14." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.2'>".LAN_T13." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2'>".LAN_T16."</a>" ;
                }
           elseif ($id2==2)
               {$time     = time();
                $time     = $time - 2629743;
                $where    = "AND ft.Thanks_datestamp > $time";
                $pagehead = LAN_T8;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.4'>".LAN_T15." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.3'>".LAN_T14." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.1'>".LAN_T12." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2'>".LAN_T16."</a>" ;
                }
           elseif ($id2==3)
               {$time     = time();
                $time     = $time - 604800;
                $where    = "AND ft.Thanks_datestamp > $time";
                $pagehead = LAN_T9;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.4'>".LAN_T15." |</a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.2'>".LAN_T13." |</a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.1'>".LAN_T12." |</a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2'>".LAN_T16."</a>" ;
                }
           elseif ($id2==4)
               {$time     = time();
                $time     = $time - 86400;
                $where    = "AND ft.Thanks_datestamp > $time";
                $pagehead = LAN_T10;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.3'>".LAN_T14." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.2'>".LAN_T13." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.1'>".LAN_T12." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2'>".LAN_T16."</a>" ;
                }
           else
               {$pagehead = LAN_T11;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.4'>".LAN_T15." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.3'>".LAN_T14." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.2'>".LAN_T13." | </a>" ;
                $text .= "<a href='".e_PLUGIN."forumthanks/thanks.php?posts.2.1'>".LAN_T12."</a>" ;
                }

           }


//----------------------------------------------
    $query = "SELECT
               count(*) as thankcount, tp.thread_name AS parent_name, t.thread_id, t.thread_name, t.thread_thread, t.thread_forum_id, t.thread_parent, t.thread_datestamp, t.thread_user, u.user_id, u.user_name, f.forum_class, f.forum_id, f.forum_name
              FROM
               #forum_t AS t
                LEFT JOIN #user         AS u ON SUBSTRING_INDEX(t.thread_user,'.',1) = u.user_id
		LEFT JOIN #forum        AS f ON t.thread_forum_id = f.forum_id
		LEFT JOIN #forum        AS fp ON f.forum_parent   = fp.forum_id
		LEFT JOIN #forum_t      AS tp ON t.thread_parent  = tp.thread_id
		     JOIN #forum_thanks AS ft ON ft.Thanks_PostID = t.thread_id
              WHERE
                f.forum_class REGEXP '".e_CLASS_REGEXP."' AND fp.forum_class REGEXP '".e_CLASS_REGEXP."'  
                ".$where."
              GROUP BY
               tp.thread_name, t.thread_id, t.thread_name, t.thread_thread, t.thread_forum_id, t.thread_parent, t.thread_datestamp, t.thread_user, u.user_id, u.user_name, f.forum_class, f.forum_id, f.forum_name
              ORDER BY
               ".$order."
              LIMIT ".$from.",".$view."
             ";
//----------------------------------------------


    //echo "<p>$query<p>";

    $sql2 = new db;     //tp call seems to not like using $sql?!
    if ($sql2->db_select_gen($query))
       { $text .= "<p>";
         while ($postrow = $sql2->db_Fetch())
              {
                $text .= forum_parse($postrow);
               }

       $countquery = "
              SELECT
                count(distinct ft.Thanks_PostID) as cnt
              FROM
               #forum_t AS t
		LEFT JOIN #forum        AS f ON t.thread_forum_id = f.forum_id
		LEFT JOIN #forum        AS fp ON f.forum_parent   = fp.forum_id
		     JOIN #forum_thanks AS ft ON ft.Thanks_PostID = t.thread_id
              WHERE
                f.forum_class REGEXP '".e_CLASS_REGEXP."' AND fp.forum_class REGEXP '".e_CLASS_REGEXP."'
                ".$where;

        }


   else { $pagehead = LAN_T20;
          $text = LAN_T21;
          }




}
        
//----------------------------------------------

if   ($action == "user"){


     if ($id1==0){$pagehead = LAN_T22;
                  $field    = "Thanks_ToUserID";
                 }

     if ($id1==1){$pagehead = LAN_T23;
                  $field    = "Thanks_FromUserID";
                 }


                  $query    = "SELECT
                                ".$field." as thankuser, count(*) as cnt
                               FROM
                                #forum_thanks
                               GROUP BY
                                ".$field."
                               ORDER BY
                                cnt Desc
                               LIMIT ".$from.",".$view."";
                $countquery = "SELECT
                                count(distinct ".$field.") as cnt
                               FROM
                                #forum_thanks";
                                
         //echo "<p>$query<p>";

     $text = "
           <div style='text-align:center'>
           <table style='".USER_WIDTH."' class='fborder'>
     	    <td style='width:30%' class='forumheader'>User</td>
	    <td style='width:70%' class='forumheader'>Thanks Count</td>
           <td></td>
           </tr>";
    $sql2 = new db;
    if ($sql2->db_select_gen($query))
       {$text .= "<p>";
         while ($row = $sql2->db_Fetch())
              {
               
               $userinfo   = get_user_data($row['thankuser']);
               $user_name  = $userinfo["user_name"];
               $text      .= "  <tr>
                                 <td style='width:30%' class='forumheader3'><a href='".e_BASE."user.php?id.".$row['thankuser']."'>".$user_name."</a></td>
                                 <td style='width:70%' class='forumheader3'><a href='".e_PLUGIN."forumthanks/thanks.php?posts.".$id1.".".$row['thankuser']."'>".$row['cnt']."</a></td>
                                 </tr>";
              }

        $text .= "</table></div><p><br>";

        }
        else {$pagehead =LAN_T24;
              $text = LAN_T25;}

}


//----------------------------------------------


if  ($action<> "user" and $action<> "thank" and $action<> "posts" )
        {$pagehead =LAN_T26;
         $text = LAN_T27;
    }


//----------------------------------------------

       $sql->db_Select_gen("$countquery");
       $tot = $sql->db_fetch();
       $total =$tot['cnt'];

       //build up nextprev parameter
       $url       = e_SELF.'?'.$action.".".$id1.".".$id2.'.[FROM]';
       $parms = $total.",".$view.",".$from.",".$url;

       $text .= "<center>";
       $text .= $tp->parseTemplate("{NEXTPREV={$parms}}");
       $text .= "</center>";



//----------------------------------------------

if ($action=='posts'){
       $text .= "<p>
        <center><a href='http://www.jezza101.co.uk'>e107 Forum Thanks Plugin</a></center>";
} else {
       $text .= "<p>
        <center><a href='http://www.wistop.com/blog'>Forum Thanks</a></center>";
}




   $ns->tablerender($pagehead, $text);
   require_once(FOOTERF);
   


   
//----------------------------------------------
//forum parse, part borrowed from search script



function forum_parse($row) {
                        global $con,$tp,$postrow;

                        $datestamp = $con -> convert_date($row['thread_datestamp'], "long");

	                if ($row['thread_parent']) {
                        $title = $row['parent_name'];
                       	} else {
              		$title = $row['thread_name'];
                      	}
                       	$link_id = $row['thread_id'];

                        $res['link']        = e_PLUGIN."forum/forum_viewtopic.php?".$link_id.".post";
                        $res['pre_title']   = $title ? FOR_SCH_LAN_5.": " : "";
                        $res['title']       = $title ? $title : LAN_SEARCH_9;
                        $res['pre_summary'] = "<div class='smalltext' style='padding: 2px 0px'><a href='".e_PLUGIN."forum/forum.php'>Forum</a> -> <a href='".e_PLUGIN."forum/forum_viewforum.php?".$row['forum_id']."'>".$row['forum_name']."</a></div>"; // forum = FOR_SCH_LAN_1
                        $res['summary']     = $row['thread_thread'];
                        $res['detail']      = LAN_SEARCH_7."<a href='".e_BASE."user.php?id.".$row['user_id']."'>".$row['user_name']."</a>".LAN_SEARCH_8.$datestamp;

                        echo "<p>";
                        $TITLE      = "<a href = '".e_BASE.$res['link']."'>".$res['title']."</a>";
                        $PRETITLE   = $res['pretitle'];                                           //echo "PRETITLE<p>$PRETITLE";
                        $PRESUMMARY = $res['pre_summary'];                                        //echo "PRESUMMARY<p>$PRESUMMARY";
                        $SUMMARY    = $res['summary'];
                        $SUMMARY    = $tp->toHTML($SUMMARY, TRUE, 'constants');
                        $SUMMARY    = $tp->html_truncate($SUMMARY,200,"...");   //echo "SUMMARY<p>$SUMMARY";
                        $DETAIL     = $res['detail'];

                        if($postrow['thankcount']<>1) {$plural='s';}
                        
                        $res ="
                        <table style='".USER_WIDTH."' class='fborder' >
                        <tr> <td class='forumheader'> ".$TITLE." ".$PRETITLE." ".$PRESUMMARY."</td> </tr>
                        <tr> <td class='forumheader3'> ".$SUMMARY." </td> </tr>
                        <tr> <td class='forumheader3'> Thanked ".$postrow['thankcount']." time".$plural."<br>".$DETAIL." </td> </tr>
                        </table>  <br>
                        ";     //ADD:back button
	return $res;
}


?>

