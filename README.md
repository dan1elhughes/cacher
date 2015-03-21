# Overview

Cacher provides a simple caching functionality, usable with two lines of code.

## Installation

Add `"xes/cacher": "dev-master"` to your composer.json file.

## Usage

Before any HTML is output:

```php
<?php
$cache = new xes\Cacher('/path/to/cache/folder');
$cache->start();
?>
```

The path must already exist and be writable by the web server.

At the end of your script:

```php
<?php
$cache->finish();
?>
```

## String replacements

For content such as page title and meta descriptions, you may not know the values until later in the script. For this there is a replacement method in Cacher:

```php
<title><!--TITLE--></title>

<?php $cache->setReplacements( array(
	"<!--TITLE-->" => $pageTitleFromDatabase,
	"<!--SOMETHINGELSE-->" => $somethingElse
));
?>
```

This simply performs a find-and-replace on the HTML until the search text doesn't exist.

The replacements array can also be passed directly to `finish()`.

## Settings

setEnabled(true/false) - choose if the cache should run or not. Useful for development environments.

setFolder(path) - absolute path to the cache folder. Must be writable by web server.

setTimeout(numberOfSeconds) - how many seconds should elapse until a cached file should be considered invalid.

setSuffix(fileSuffix) - the file suffix appended to all cache files. `.cache.html` by default.

setReplacements(array) - associative array of replacements in the format `"find" -> "replace"`
