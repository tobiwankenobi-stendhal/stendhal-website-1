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
}

class APCCacheImpl implements Cache {
	function store($key, $value, $ttl = 0) {
		return apc_store($key, $value, $ttl);
	}

	function fetch($key, &$success = false) {
		return apc_fetch($key, &$success);
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
}

if (function_exists('apc_store')) {
	$cache = new APCCacheImpl();
} else {
	$cache = new NoCacheImpl();
}

$cache->store("a", "b");
echo 'A'.$cache->fetch("a").'B';
?>