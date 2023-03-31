<?php

/**
 * Summary of escape
 * @param string $string
 * @return string
 */
function escape($string)
{

    return htmlspecialchars("$string", ENT_QUOTES, "UTF-8");
    // return htmlentities("$string",ENT_QUOTES,"UTF-8"); 

}


?>
