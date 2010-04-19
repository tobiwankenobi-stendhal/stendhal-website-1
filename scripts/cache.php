<?php
interface Cache {
	/**
	 * stores a value in the cache
	 *
	 * @param $key    key to access the value
	 * @param $value  value to be stored
	 * @param $ttl    optional time to live
	 * @return boolean caching successful?
	 */
	function store($key, $value, $ttl = 0);

	/**
	 * fetches a value from the cache
	 *
	 * @param $key     key to access the value
	 * @param $success true, if the fetch was succesful
	 * @return mixed   value
	 */
	function fetch($key, &$success = false);

	/**
	 * fetches an array value from the cache that was previously converted into an ArrayObject 
	 *
	 * @param $key     key to access the value
	 * @param $success true, if the fetch was succesful
	 * @return mixed   value
	 */
	function fetchAsArray($key, &$success = false);

	/**
	 * clears the cache after an update
	 */
	function clearCacheIfOutdate();
}

class APCCacheImpl implements Cache {
	private $keysToPurge = array('stendhal_creatures', 'stendhal_items', 'stendhal_npcs');

	function store($key, $value, $ttl = 0) {
		return apc_store($key, $value, $ttl);
	}

	function fetch($key, &$success = false) {
		return apc_fetch($key, &$success);
	}

	function fetchAsArray($key, &$success = false) {
		$temp = $this->fetch($key, &$success);
		if (isset($temp) && $temp !== false) {
			return $temp->getArrayCopy();
		}
		return null;
	}

	function clearCacheIfOutdate() {
		$version = $this->fetch('stendhal_version');
		if ($version != STENDHAL_VERSION) {
			foreach($this->keysToPurge as $key) {
				apc_delete($key);
			}
			$this->store('stendhal_version', STENDHAL_VERSION);
		}
	}
}

class NoCacheImpl implements Cache {
	function store($key, $value, $ttl = 0) {
		return false;
	}

	function fetch($key, &$success = false) {
		$success = false;
		return null;
	}

	function fetchAsArray($key, &$success = false) {
		return $this->fetch($key, $success);
	}

	function clearCacheIfOutdate() {
		// do nothing
	}
}

if (function_exists('apc_store')) {
	$cache = new APCCacheImpl();
} else {
	$cache = new NoCacheImpl();
}
$cache->clearCacheIfOutdate();
?>