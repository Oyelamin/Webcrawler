<?php

namespace WebCrawler\Helpers;

use MongoDB\Driver\Exception\WriteException;
use WebCrawler\Support\Interfaces\HelperFunctionInterface;

class HelperFunction implements HelperFunctionInterface
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
    public function filterSchemeAndJoin(string $baseUrl, string $resolvedUrl): array|string|null
    {
        try{
            if (parse_url($resolvedUrl, PHP_URL_SCHEME) != '') {
                return $resolvedUrl;
            }
            if ($resolvedUrl[0] == '#' || $resolvedUrl[0] == '?') {
                return $baseUrl . $resolvedUrl;
            }
            $parse_base = parse_url($baseUrl);
            $path = isset($parse_base['path']) ? $parse_base['path'] : '';
            if ($resolvedUrl[0] == '/') {
                $path = '';
            }
            $last_slash = strrpos($path, '/');
            if ($last_slash !== false) {
                $path = substr($path, 0, $last_slash + 1);
            }
            $abs = $parse_base['scheme'] . '://' . $parse_base['host'] . $path . $resolvedUrl;
            $regularExpressions = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
            for ($n = 1; $n > 0; $abs = preg_replace($regularExpressions, '/', $abs, -1, $n)) {
            }
            return $abs;
        }catch (\Exception $e) {
            throw new \Exception("Something went wrong.\n\n>> Error ::: ".$e->getMessage());
        }

    }

    /**
     * Retrieves all links (href attributes) from a given HTML string and returns them as an array.
     *
     * @param string $url The URL of the page being crawled.
     * @param string $html The HTML content of the page being crawled.
     * @return array An array of URLs found in the HTML.
     */
    public function fetchLinks(string $url, string $html): array
    {
        try{
            $links = [];
            $dom = new \DOMDocument;
            @$dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);
            foreach ($xpath->query('//a') as $link) {
                $href = $link->getAttribute('href');
                if ($href !== '') {
                    $href = $this->filterSchemeAndJoin($url, $href); // Resolve the URL against the base URL.
                    $parsed_href = parse_url($href);
                    if (isset($parsed_href['scheme']) && $parsed_href['scheme'] === 'http' || $parsed_href['scheme'] === 'https') {
                        $links[] = $href;
                    }
                }
            }

            return $links;
        }catch (\Exception $e) {
            throw new \Exception("Something went wrong.\n\n>> Error ::: ".$e->getMessage());
        }

    }

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
    public function saveContentToDisk($folderName, $fileName, $fileExtension, $htmlContent): bool
    {
        try{
            if (!file_exists($folderName)) {
                mkdir($folderName, 0777, true);
            }

            file_put_contents("{$folderName}/{$fileName}.{$fileExtension}", $htmlContent);

            return true;
        }catch (\Exception $e){
            throw new \Exception("Unable to write content into file {$fileName}.{$fileExtension}.");
        }

    }

}