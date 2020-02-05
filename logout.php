<?php
    $params = session_get_cookie_params();
    setcookie("access_token","",0, $params["path"], $params["domain"], TRUE, TRUE);
    header("Location: login.php");
?>