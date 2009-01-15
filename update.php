<?php

// TODO: sparklines in place of count
// TODO: increase score if user is in my network

$username = 'hublicious'; // edit this

/*****************/

cleanup();

global $urls;
global $meta;
global $users;

bookmarks($username, 20);
$my_bookmarks = $urls;

array_walk(array_keys($urls), 'users', 20);
//$urls = array(); // reset
unset($users[$username]);

array_walk(array_keys($users), 'bookmarks', 100);

foreach (array_keys($my_bookmarks) as $url)
  unset($urls[$url]);
  
arsort($urls);

require 'index.tpl.php';

function bookmarks($username, $key, $count = 20){
  global $urls;
  global $meta;
  
  $url = sprintf('http://feeds.delicious.com/v2/rss/%s?count=%d', $username, $count);

  foreach (items($url) as $item){
    $link = (string) $item->link;
    $title = (string) $item->title;
    $description = (string) $item->description;
    
    $urls[$link]++;
    $meta[$link]['title'] = $title;
    
    if ($description)
      $meta[$link]['descriptions'][$username] = filter_var($description, FILTER_SANITIZE_STRING);
  }
}

function users($url, $key, $count = 20){
  global $users;
  
  $url = sprintf('http://feeds.delicious.com/v2/rss/url/%s?count=%d', md5($url), $count);

  foreach (items($url) as $item){
    $user = (string) current($item->xpath('dc:creator'));
    $users[$user]++;
  } 
}

function items($url){
  $data = cget($url);
  $xml = simplexml_load_string($data);
  
  $xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');
  return $xml->channel->item;
}

function cget($url){
  //print $url . "\n";
  
  $key = md5($url);
  $file = 'cache/' . $key;
  
  if (file_exists($file) && filesize($file))
    return file_get_contents($file);
    
  $data = file_get_contents($url);
  if ($data){
     file_put_contents($file, $data);
     sleep(1);
     return $data;
  }
}

function cleanup(){
  $expire = time() - 60*60*24*7; // 7 days
  
  $files = scandir('cache');
  foreach ($files as $file)
    if (!strstr($file, '.') && filemtime('cache/' . $file) < $expire)
      unlink('cache/' . $file);
}