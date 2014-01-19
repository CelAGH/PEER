<?php

add_filter("bp_deactivated_components","disable_blog_component");
 
function disable_blog_component($disabled_components){
 
$disabled_components['bp-blogs.php']=1;
 
return $disabled_components;
 
}

?>