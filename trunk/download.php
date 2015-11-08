<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$file = isset($_GET["file"]) ? $_GET["file"] : null;
if (!isset($file))
{
    echo json_encode(array(
        'code' => RETURN_ERROR,
        'message' => 'file not exist',
    ));
}

$filename = preg_replace('/^.+[\\\\\\/]/', '', $file);
//var_dump($filename);
//exit();

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $filename);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
ob_clean();
flush();
readfile($file);
exit;
