<?php

require_once __DIR__.'/../DB.php';
require_once __DIR__.'/../Csrf.php';
require_once __DIR__.'/../Services/ImageService.php';
require_once __DIR__.'/../Support.php';

class ImageController {
    public function compose(): void {
        Csrf::checkToken();
}
