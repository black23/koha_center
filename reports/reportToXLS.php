<?php

    session_start();

    if (!headers_sent()) {
	header('Content-Type: text/html; charset=utf-8');
    }
	
    require_once "../system/config.php";
    
    require_once "KrizovatkaDenik.class.php";
    
    use Tracy\Debugger;

    displayErrors(true);
    
    $from = $_GET["from"];
    $to = $_GET["to"];
    
    $KrizovatkaDenik = new KrizovatkaDenik($db, $from, $to, $categoryCode, $doc_types, $ccodes, $cat_prints, $doc_collection);
    $KrizovatkaDenik->createFile();
    $KrizovatkaDenik->saveAsExcel();
