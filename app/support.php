<?php
/* function auth_id(): ?int { return isset($_SESSION['uid']) ? (int)$_SESSION['uid'] : null; } */

//si user co return son id, sinon null
function auth_id(): ?int {
    $flag = isset($_SESSION['uid']);
    if ($flag){
        return (int)$_SESSION['uid'];
    }else {
        return null;
    }
}

var_dump(auth_id()); 

$_SESSION['uid'] = 42;
var_dump(auth_id());

$_SESSION['uid'] = "123";
var_dump(auth_id());


/* function require_auth(): void { if (!auth_id()) { header('Location: /login'); exit; } } */



/* function flash(string $k, ?string $v=null) { */
/*   if ($v === null) { $x = $_SESSION['_flash'][$k] ?? null; unset($_SESSION['_flash'][$k]); return $x; } */
/*   $_SESSION['_flash'][$k] = $v; */
/* } */
