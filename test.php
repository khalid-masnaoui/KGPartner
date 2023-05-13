<?php


if (password_verify("qwer1234!@", '$2y$10$sSwl5Ni2In1z56lznSr9mu6pm2bpB7.GQWOZwwLSjV.aSxlXDivmK')) {
    echo "success";
    die;
}
echo "failed";

?>
