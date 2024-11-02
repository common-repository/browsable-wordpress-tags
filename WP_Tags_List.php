<?php
/*
Plugin Name: Browsable WP Tags
Version: 1.0
Plugin URI: http://www.thoughtsofjs.com
Description: Generates browsable pages of your WordPress tags
Author: Joseph Szenasi
Author URI: http://www.thoughtsofjs.com
*/
/* Usage: <? wp_get_tags();?> in your Theme file or <!--WP_Tags_List--> in a Page*/





if(!is_admin()){
add_filter('the_content','findtrigger');
} 



function findtrigger($content){
    
    // search pattern for old wp generated more link structure        
    $search_pattern="/<!--WP_Tags_List-->/";
    
    // search old more link and get the important parts
    
    if (preg_match($search_pattern,$content)){
       
wp_get_tags();

    }else{
        // no more-link found, do nothing and return $content
        return($content);
    }
}


function wp_get_tags() {
    global $wpdb, $tableposts, $post;
	

$t = mysql_query("SELECT * FROM `".$wpdb->terms."`");
  if(!$t) die(mysql_error());
   
$a                  = mysql_fetch_object($t);
$total_items      = mysql_num_rows($t);
$limit            = $_GET['limit'];
$type             = $_GET['type'];
$page             = $_GET['page'];

//set default if: $limit is empty, non numerical, less than 10, greater than 50
if((!$limit)  || (is_numeric($limit) == false) || ($limit < 100) || ($limit > 500)) {
     $limit = 100; //default
}
//set default if: $page is empty, non numerical, less than zero, greater than total available
if((!$page) || (is_numeric($page) == false) || ($page < 0) || ($page > $total_items)) {
      $page = 1; //default
}

//calcuate total pages
$total_pages     = ceil($total_items / $limit);
$set_limit          = $page * $limit - ($limit);

//query

$q = mysql_query("SELECT * FROM `".$wpdb->terms."`  ORDER BY `term_id` DESC LIMIT $set_limit, $limit");
  if(!$q) die(mysql_error());
     $err = mysql_num_rows($q);
       if($err == 0) die("No matches met your criteria.");

//Results per page
echo("Results per page:  
<a href=?cat=$cat&limit=100&page=1>100</a> |
<a href=?cat=$cat&limit=250&page=1>250</a> |
<a href=?cat=$cat&limit=500&page=1>500</a>

<br/><br/>");
 

//show data matching query:
while($code = mysql_fetch_object($q)) {
 echo ("<a href='./tag/".$code->slug."'>".$code->name."</a> - ");
}
echo '<br/><br/>';
$cat = urlencode($cat); //makes browser friendly

//prev. page

$prev_page = $page - 1;

if($prev_page >= 1) {
  echo("<b><<</b> <a href=?cat=$cat&limit=$limit&page=$prev_page><b>Prev.</b></a>");
}

//Display middle pages

for($a = 1; $a <= $total_pages; $a++)
{
   if($a == $page) {
      echo("<b> $a</b> | "); //no link
     } else {
  echo("  <a href=?cat=$cat&limit=$limit&page=$a> $a </a> | ");
     }
}

//next page

$next_page = $page + 1;
if($next_page <= $total_pages) {
   echo("<a href=?cat=$cat&limit=$limit&page=$next_page><b>Next</b></a> > >");
}

// DO NOT REMOVE THE COPYRIGHT!!! You can use thisc script for free only if you keep the copyright note intact. If you want to remove the copyright notice please send an e-mail to joseph.szenasi@gmail.com. Thanks.

echo ("<div align=\"right\"><small>Plugin created by <a href=\"http://www.thoughtsofjs.com/\" target=\"_blank\">Joseph Szenasi</a></small></div>");
}

//all done
?>