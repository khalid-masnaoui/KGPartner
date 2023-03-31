<?php


/**
 * Redirect
 * 
 * Redirect HTTP response.
 * 
 * @author khalid
 */
class redirect
{
    /**
     * to
     *
     * @param string $page_name
     * @return void
     */
    public static function to(string $page_name = ""): void
    {
        if ($page_name) {
            // the other cases ; errors pages
            if (is_numeric($page_name)) {
                switch ($page_name) {
                    case 404:
                        header("HTTP/1.0 404 Not Found");
                        include "/pages/errors/404.php";
                        exit();
                        break;
                }
            }
            header("location:" . $page_name);
            exit();
        }
    }
}
