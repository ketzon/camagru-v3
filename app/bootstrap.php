<?php
function ensure_storage(): void {
    $root = dirname(__DIR__);
    $dirs = [
        "$root/storage",
        "$root/storage/uploads",
        "$root/storage/renders",
    ];
    foreach ($dirs as $d) {
        if (!is_dir($d)) {
            mkdir($d, 0775, true);
        }
    }
    $db = "$root/storage/db.sqlite";
    if (!file_exists($db)) {
        touch($db);
        chmod($db, 0664);
    }
    @chmod("$root/storage", 0775);
    @chmod("$root/storage/uploads", 0775);
    @chmod("$root/storage/renders", 0775);
}
