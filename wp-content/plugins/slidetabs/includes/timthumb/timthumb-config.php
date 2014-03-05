<?php
/*
 * --- TimThumb CONFIGURATION ---
*/
//Image fetching and caching
/*define ('ALLOW_EXTERNAL', TRUE);					// Allow image fetching from external websites. Will check against ALLOWED_SITES if ALLOW_ALL_EXTERNAL_SITES is false
define ('ALLOW_ALL_EXTERNAL_SITES', false);			// Less secure. 
define ('FILE_CACHE_ENABLED', TRUE);				// Should we store resized/modified images on disk to speed things up?
define ('FILE_CACHE_TIME_BETWEEN_CLEANS', 86400);	// How often the cache is cleaned 
define ('FILE_CACHE_MAX_FILE_AGE', 86400);			// How old does a file have to be to be deleted from the cache
define ('FILE_CACHE_SUFFIX', '.timthumb.txt');		// What to put at the end of all files in the cache directory so we can identify them
define ('FILE_CACHE_DIRECTORY', './cache');			// Directory where images are cached. Left blank it will use the system temporary directory (which is better for security)
define ('MAX_FILE_SIZE', 10485760);					// 10 Megs is 10485760. This is the max internal or external file size that we'll process.  
define ('CURL_TIMEOUT', 20);						// Timeout duration for Curl. This only applies if you have Curl installed and aren't using PHP's default URL fetching mechanism.
define ('WAIT_BETWEEN_FETCH_ERRORS', 3600);			// Time to wait between errors fetching remote file
//Browser caching
define ('BROWSER_CACHE_MAX_AGE', 864000);			// Time to cache in the browser
define ('BROWSER_CACHE_DISABLE', false);			// Use for testing if you want to disable all browser caching*/