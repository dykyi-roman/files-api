<?php

require_once '../vendor/autoload.php';

use Dykyi\FileClient;
use Dykyi\ResponseDataExtractor;
use GuzzleHttp\Client;

$client = new FileClient(new Client(),new ResponseDataExtractor(), 'test','dev');

$id = $client->write(['name' => 'test_name', 'filePath' => '/path/fileName.doc']);
$client->get($id);
$client->delete($id);