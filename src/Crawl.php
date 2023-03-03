<?php

namespace WebCrawler;

use WebCrawler\Constants\Meta;
use WebCrawler\Helpers\HelperFunction;

class Crawl extends HelperFunction
{

    protected static $startURL;
    protected static $crawlPagesMax;
    protected static $crawlMaximumProcesses;
    protected static $customFolderName;
    protected static $customFileExtension;

    /**
     * @param string $startURL
     * @param int $crawlPagesMax
     * @param int $crawlMaximumProcesses
     * @param string $customFolderName
     * @param string $customFileExtension
     */
    public function __construct(string $startURL,
                                int $crawlPagesMax = Meta::CRAWL_MAX_PAGES,
                                int $crawlMaximumProcesses = Meta::CRAWL_MIN_PROCESSES,
                                string $customFolderName = Meta::CUSTOM_FOLDER_NAME,
                                string $customFileExtension = Meta::CUSTOM_FILE_EXTENSION)
    {
        self::$startURL = $startURL;
        self::$crawlPagesMax = $crawlPagesMax;
        self::$crawlMaximumProcesses = $crawlMaximumProcesses;
        self::$customFolderName = $customFolderName;
        self::$customFileExtension = $customFileExtension;

    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function execute(): bool
    {

        try{
            $visited = []; // Array of visited URLs.
            $to_visit = [self::$startURL]; // Queue of URLs to visit.
            $count = 0; // Counter for number of pages crawled.
            while (!empty($to_visit) && $count < self::$crawlPagesMax) {

                $tasks = array_chunk($to_visit, self::$crawlMaximumProcesses); // Divide the URLs to visit into chunks of $max_processes URLs each.
                foreach ($tasks as $urls) {
                    $pool = []; // Array of cURL handles.
                    foreach ($urls as $url) {
                        if (!in_array($url, $visited)) {
                            $pool[$url] = curl_init(); // Initialize a cURL handle for this URL.
                            curl_setopt($pool[$url], CURLOPT_URL, $url);
                            curl_setopt($pool[$url], CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($pool[$url], CURLOPT_FOLLOWLOCATION, true);
                            curl_setopt($pool[$url], CURLOPT_CONNECTTIMEOUT, 5);
                            curl_setopt($pool[$url], CURLOPT_TIMEOUT, 10);
                        }
                    }
                    $mh = curl_multi_init(); // Initialize a cURL multi handle.
                    foreach ($pool as $ch) {
                        curl_multi_add_handle($mh, $ch);
                    }
                    // execute cURL requests in parallel
                    $running = null;
                    do {
                        curl_multi_exec($mh, $running);
                    } while ($running > 0);

                    // loop through each completed cURL request
                    foreach ($pool as $url => $ch) {
                        $html = curl_multi_getcontent($ch);
                        curl_multi_remove_handle($mh, $ch);
                        if (!in_array($url, $visited) && $html !== '') {
                            // extract links from the HTML content
                            $links = $this->fetchLinks($url, $html);

                            // add new links to the to_visit array
                            foreach ($links as $link) {
                                if (!in_array($link, $visited) && !in_array($link, $to_visit)) {
                                    $to_visit[] = $link;
                                }
                            }
                            // mark current URL as visited
                            $visited[] = $url;
                            $count+=1;
                            if($count > self::$crawlPagesMax) return false;
                            echo "\n>> Page {$count} - saving...\n";
                            // save the HTML content to a file
                            $filename = str_replace(['://', '/'], '_', $url);
                            $folderName = self::$customFolderName;
                            $fileExtension = self::$customFileExtension;
                            $this->saveContentToDisk($folderName, $filename, $fileExtension, $html);
                        }
                    }
                    // close multi cURL handler
                    curl_multi_close($mh);
                }
                // remove visited URLs from the to_visit array
                $to_visit = array_diff($to_visit, $visited);
            }
        }catch (\Exception $e){
            throw new \Exception("Oops. Something went wrong. \n\n Error ::: ".$e->getMessage());
        }

        return true;

    }


}