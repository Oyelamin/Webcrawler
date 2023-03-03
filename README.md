# Webcrawler


## **`Challenge`**
In a language of your choice, implement a simple web crawler that gets a news website as input (e.g. http://www.spiegel.de) and crawls the HTML content of up to 100 pages of that site with a breadth-first approach. 
The downloaded pages should be stored as HTML in a folder in the file system.
The crawler needs to be able to work with up to 50 parallel processes. 
The number of processes can be passed as a parameter. 
If no input is given the default value shall be 5 processes.

## **`Language Used`**
- PHP


## **`Solution Installation`**
- Clone this repository: `git clone https://github.com/Oyelamin/Webcrawler.git`
- Install the dependencies: `composer install`

<br>
Now You can run the code for use

## **`Solution Usage`**

1. Firstly, you can either choose to run the solution in the *index.php* file or inject in your 
php application as long as composer is installed, you are good to go!.<br><br>
2. You can initialise the Crawl class but you need to import it into your file or controller class. e.g:
<br>
    ###   *`use WebCrawler\Crawl;`*
<br>
3. Declare your basic inputs. e.g:<br>

        $websiteUrl = 'http://www.spiegel.de'; // Any url of your choice - Required<br>
        $maxPages = 10; // This can increase/decrease - Optional<br>
        $maxProcesses = 5; // Can increase/decrease - Optional<br>
        $folderName = "MyCustompages"; // - Optional<br>
        $fileExtension = "html"; // txt,htm,css, etc... - Optional
   <br>

4. Feed your declared inputs in the Crawl class as initialization:<br><br>
   `$crawl = new Crawl($websiteUrl, $maxPages, $maxProcesses, $folderName, $fileExtension);
   `
<br><br>
5. Execute the program:
<br><br>
`return $crawl->execute(); // run { php index.php }`


## **`Example`**

This is example of how you can run it:

    <?php
    
    require 'vendor/autoload.php';
    
    use WebCrawler\Crawl;
    
    $websiteUrl = 'http://www.spiegel.de'; // Any url of your choice - Required
    $maxPages = 10; // This can increase/decrease - Optional
    $maxProcesses = 5; // Can increase/decrease - Optional
    $folderName = "MyCustompages"; // - Optional
    $fileExtension = "html"; // txt,htm,css, etc... - Optional
    $crawl = new Crawl($websiteUrl, $maxPages, $maxProcesses, $folderName, $fileExtension);
    
    return $crawl->execute(); // run { php index.php } to execute
    
    // THANK YOU and i hope you enjoyed the code ❤ 🤗!!!!!