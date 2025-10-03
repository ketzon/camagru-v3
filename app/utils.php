<?php
//si user co return son id, sinon null
function auth_id(): ?int {
    $flag = isset($_SESSION['uid']);
    if ($flag){
        return (int)$_SESSION['uid'];
    }else {
        return null;
    }
}

function getUserName(): string {
    return (string)$_SESSION['user'];
}

function getMail(): string {
    return (string)$_SESSION['mail'];
}

//si pas co, renvoyer au login
function require_auth(): void {
    if (!auth_id()) {
        header('Location: /login');
        exit;
    }
}

//stock un message cle->valeur en session (global)
function flash(string $keys, ?string $message=null) {
    if ($message === null) { 
        if (isset($_SESSION['flash'][$keys])){
            $value = $_SESSION['flash'][$keys];
        }
        else {
            $value = null;
        }
        unset($_SESSION['flash'][$keys]); 
        return $value; 
    }
    $_SESSION['flash'][$keys] = $message;
}
