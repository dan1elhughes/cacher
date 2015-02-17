<?php

namespace xes;
class Cacher {

	private $cacheFolder;
	private $enabled;
	private $timeout = 5;
	private $suffix = '.cache.html';
	private $replacements;
	private $currentFile;

	public function setEnabled($enabled) { $this->enabled = $enabled; }
	public function setFolder($cacheFolder) { $this->cacheFolder = $cacheFolder; }
	public function setTimeout($timeout) { $this->timeout = $timeout; }
	public function setSuffix($suffix) { $this->suffix = $suffix; }
	public function setReplacements($replacements) { $this->replacements = $replacements; }

	public function __construct($cacheFolder, $enabled = true) {
	
		if (!is_dir($cacheFolder)) {
			die("ERROR: Unable to access folder " . $cacheFolder);
		}

		$this->cacheFolder = $cacheFolder;
		$this->enabled = $enabled;
	}

	public function start($url = null) {
		
		$url = isset($url) ? $url : $_SERVER['REQUEST_URI'];
		
		$this->currentFile = $this->cacheFolder.md5($url).$this->suffix; // Location to lookup or store cached file
		$cache_created  = (file_exists($this->currentFile)) ? filemtime($this->currentFile) : 0; //Check to see if this file has already been cached, if it has then get and store the file creation time
		if ((time() - $cache_created) < $this->timeout && $this->enabled) {
			readfile($this->currentFile); // The cached copy is still valid, read it into the output buffer and exit
			die();
		} else {
			ob_start(); //Start storing HTML rather than outputting directly, allows to replace variables
		}
	}

	public function finish($replacements = null) {
	
		if ($replacements != null) {
			$this->replacements = $replacements;
		}
	
		$output = ob_get_contents (); // Get all the page's HTML into a string
		ob_end_clean (); // Wipe the buffer

		if ($this->replacements != null) {
			foreach ($this->replacements as $old => $new) {
				$output = str_replace($old, $new, $output);
			}
		}

		if ($this->enabled) {
			file_put_contents($this->currentFile, $output);
		}

		echo $output;
	}
}
?>
