<?php

function use_cache_on_format($params)
{
  return !isset($params["format"]) || 
    (substr($params["format"], 0, 5) != "image");
}

$cacheDir = "cache";
$url = $_GET["__url"];
$proto = $_GET["__proto"];

$_GET["__url"] = null;
$_GET["__proto"] = null;

try
{
  $url = $url . "?" . http_build_query($_GET);

  list($domain, $sub) = explode("/", $url, 2);
  
  $cacheDir .= "/" . $domain;
  $cacheFilename = $cacheDir . "/" . md5($sub);
  
  $url = $proto . "://" . $url;

  if(file_exists($cacheFilename))
    {
      echo file_get_contents($cacheFilename);    
    }
  else if(use_cache_on_format($_GET))
    {
      if(!is_dir($cacheDir))
	{
	  if(!mkdir($cacheDir))
	    {
	      throw new Exception("could not create cache directory for $domain");
	    }
	}
      $data = file_get_contents($url);
      if($data === FALSE)
	{
	  throw new Exception("source not available for $domain");
	}
      if(file_put_contents($cacheFilename, $data) === FALSE)
	{
	  throw new Exception("cache file creation failed for $domain on $cacheFilename");
	}
      echo $data;
    }
  else
    {
      echo file_get_contents($url);
    }
}
catch(Exception $e)
{
  die("Error: " . $e->getMessage() . "\n");
}

?>
