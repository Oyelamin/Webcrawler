<?php

require 'vendor/autoload.php';

use WebCrawler\Crawl;

$websiteUrl = 'http://www.spiegel.de'; // Any url of your choice - Required
$maxPages = 10; // This can increase/decrease - Optional
$maxProcesses = 5; // Can increase/decrease - Optional
$folderName = "pages"; // - Optional
$fileExtension = "html"; // txt,htm,css, etc... - Optional
$crawl = new Crawl($websiteUrl, $maxPages, $maxProcesses, $folderName, $fileExtension);

return $crawl->execute(); // run { php index.php } to execute

// THANK YOU and i hope you enjoyed the code ❤ 🤗!!!!!