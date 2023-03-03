<?php

namespace WebCrawler\Support\Interfaces;

interface HelperFunctionInterface
{

    /**
     * ####### Filter Scheme and Join #######################
     *
     * ###########################################
     * First checks if the relative URL has a scheme, in which case it is already an absolute URL and is returned as is.
     *
     * If the relative URL starts with a "#" or "?", it is appended to the base URL.
     *
     * If the relative URL starts with "/", the path is set to the root, otherwise the path is set to the directory of the base URL.
     * Finally, it removes any ".." segments in the path and returns the absolute URL.
     * @param string $baseUrl
     * @param string $resolvedUrl
     * @return array|string|string[]|null
     */
    public function filterSchemeAndJoin(string $baseUrl, string $resolvedUrl): array|string|null;


    /**
     * Retrieves all links (href attributes) from a given HTML string and returns them as an array.
     *
     * @param string $url The URL of the page being crawled.
     * @param string $html The HTML content of the page being crawled.
     * @return array An array of URLs found in the HTML.
     */
    public function fetchLinks(string $url, string $html): array;


    /**============================================
     * Save crawled HTML contents to file
     * =========================
     * @param $folderName
     * @param $fileName
     * @param $fileExtension
     * @param $htmlContent
     * @return bool
     * @throws \Exception
     */
    public function saveContentToDisk($folderName, $fileName, $fileExtension, $htmlContent): bool;

}