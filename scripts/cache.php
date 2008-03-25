<?php
class Cache {
  /*
   * This should be true to allow caching of the file.
   */
  private $caching = false;
  /*
   * The file that is going to store the cache or from
   * where the cache is going to be loaded.
   */
  private $file = '';

  private $avoidCachingList;

  function __construct($avoidcaching) {
    $this->avoidCachingList=$avoidcaching;
  }

  function mustAvoidCaching($resource) {
    $result=false;

    foreach($this->avoidCachingList as $item) {
      if(!(strpos($resource,$item)===false)) {
        $result=true;
        break;
      }
    }

    return $result;
  }

  /*
   * This get the cache file or open buffer to create a new one.
   * It returns true if the page is loaded from cache or false if it needs to be generated.
   */
  function start($resource){
    /*
     * If we don't want to cache ( i.e: login or admin pages ) we exit inmediately
     */
    if($this->mustAvoidCaching($resource)) {
      return false;
    }

    $result=false;

    /*
     * Using the MD5 to avoid strange characters in the file name.
     */
    $this->file = STENDHAL_PATH_TO_CACHE."/cache_".md5($resource);

    /*
     * We check that the file exists and didn't timeout.
     */
    if (file_exists($this->file) &&
    (fileatime($this->file)+STENDHAL_CACHE_TIMEOUT)>time()){
      /*
       * Yes? So load the cached page.
       */
      readfile($this->file);
      $this->caching = false;
      $result=true;
    } else {
      /*
       * No? Cache this page.
       */
      $this->caching = true;
      ob_start();
    }

    return $result;
  }

  function end(){
    if ($this->caching){
      /*
       * Get the text.
       */
      $data = ob_get_clean();

      /*
       * Show it.
       */
      echo $data;

      /*
       * Store it.
       */
      if(file_exists($this->file)){
        unlink($this->file);
      }

      $fp = fopen( $this->file , 'w' );
      fwrite ( $fp , $data );
      fclose ( $fp );
    }
  }
}
?>