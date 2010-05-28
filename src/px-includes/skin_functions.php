<?php

/******************************************************************************

 Copyright (c) 2009, Perfiliate Technologies Ltd
 All rights reserved.
 
 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions are met:
   * Redistributions of source code must retain the above copyright notice, this
     list of conditions and the following disclaimer.
   * Redistributions in binary form must reproduce the above copyright notice,
     this list of conditions and the following disclaimer in the documentation
     and/or other materials provided with the distribution.
   * Neither the name of Perfiliate Technologies Ltd nor the names of its
     contributors may be used to endorse or promote products derived from this
     software without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 
 *****************************************************************************/

/**
 * Include a named template.
 *
 * @param string $name The name of the template.
 * @param array $vars An associative array of variables to pass to the template.
 *
 * @return void
 */

function px_include_template($name, $vars = array())
{
  echo px_get_template($name, $vars);
}

/**
 * Fetch and parse a named template.
 *
 * @param string $name The name of the template.
 * @param array $vars An associative array of variables to pass to the template.
 *
 * @return string The parsed template output.
 */

function px_get_template($name, $vars = array())
{
  $path = PX_TEMPLATE_DIR.$name.'.php';
  if(!file_exists($path))
  {
    $path = PX_COMMON_TEMPLATE_DIR.$name.'.php';
  }
  if(file_exists($path))
  {
    ob_start();
    include($path);
    return ob_get_clean();
  }
  else
  {
    px_stop_message("Template $name not found!");
  }
}

/**
 * Construct a HTML image tag.
 *
 * @param string $url The URL of the image.
 * @param string $alt Alt text for the image.
 * @param string $class Optional class to apply to the a tag.
 *
 * @return string A HTML image tag.
 */

function px_image_tag($url, $alt = null, $class=null)
{
  return '<img src="'.$url.'" alt="'.$alt.'" class="'.$class.'">';
}

/**
 * Construct an internal URL.
 *
 * @param string $page The page within the application to link to.
 * @param array $params Associative array of query string parameters.
 *
 * @return string An internal URL.
 */

function px_get_url($page, $params = array())
{
  $url = PX_RELATIVE_ROOT.PX_SCRIPT_NAME."?page=$page";
  if(is_array($params) && count($params) > 0)
  {
    foreach($params AS $name => $value)
    {
      $url .= "&amp;$name=$value";
    }
  }
  return $url;
}

/**
 * Construct a HTML anchor that is compatible with this application's routing system.
 *
 * @param string $anchor The anchor text.
 * @param string $page The page within the application to link to.
 * @param array $params Associative array of query string parameters.
 * @param string $class Optional class to apply to the a tag.
 * @param boolean $nofollow Adds rel=nofollow if true.
 * @param boolean $newwindow The anchor opens a new window.
 * @param string $onclick JavaScript to fire when the link is clicked.
 *
 * @return string A HTML anchor tag.
 */

function px_link_to($anchor, $page, $params = array(), $class = null, $nofollow = false, $newwindow = false, $onclick = null)
{
  $url = px_get_url($page, $params);
  return px_create_link($anchor, $url, $class, $nofollow, $newwindow, $onclick);
}

/**
 * Construct a HTML anchor to any URL.
 *
 * @param string $anchor The anchor text.
 * @param string $url The URL.
 * @param string $class Optional class to apply to the a tag.
 * @param boolean $nofollow Adds rel=nofollow if true.
 * @param boolean $newwindow The anchor opens a new window.
 * @param string $onclick JavaScript to fire when the link is clicked.
 *
 * @return string A HTML anchor tag.
 */

function px_create_link($anchor, $url, $class = null, $nofollow = false, $newwindow = false, $onclick = null)
{
  $tag = '<a href="'.$url.'"';
  if($class != null)
  {
    $tag .= ' class="'.$class.'"';
  }
  if($nofollow)
  {
    $tag .= ' rel="nofollow"';
  }
  if($newwindow)
  {
    $tag .= ' target="_blank"';
  }
  if($onclick)
  {
    $tag .= ' onclick="'.$onclick.'"';
  }
  $tag .= '>'.$anchor.'</a>';
  return $tag;
}

/**
 * Construct a HTML anchor containing an affiliate URL.
 *
 * @param string $anchor The anchor text.
 * @param string $url The affiliate URL.
 * @param array $params Associative array of query string parameters.
 * @param string $class Optional class to apply to the a tag.
 *
 * @return string A HTML anchor tag.
 */

function px_affiliate_link_to($anchor, $url, $params = array(), $class = null)
{
  global $px_prefs;
  $newwindow = ($px_prefs['links_new_window'] == 'y');
  if($px_prefs['cloaking'] == 'y')
  {
    $params['id'] = px_encode_affiliate_url($url);
    return px_link_to($anchor, PX_REDIRECTION_PAGE, $params, $class, true, $newwindow);
  }
  else
  {
    return px_create_link($anchor, htmlentities($url), $class, true, $newwindow);
  }
}

/**
 * Encode an affiliate URL for cloaking purposes.
 * Currently using base64 encoding.
 *
 * @param string $url The URL to encode.
 *
 * @return string The encoded URL.
 */

function px_encode_affiliate_url($url)
{
  return base64_encode($url);
}

/**
 * Decode an affiliate URL.
 * Currently using base64 encoding.
 *
 * @param string $encoded The encoded URL.
 *
 * @return string The decoded URL.
 */

function px_decode_affiliate_url($encoded)
{
  return base64_decode($encoded);
}

/**
 * Obtain a relative URL for the CSS directory.
 *
 * @return String
 */

function px_get_css_dir()
{
  return px_get_relative_root().'px-skins/skins/'.PX_SKIN.'/css';
}

/**
 * Obtain a relative URL for the common CSS directory.
 *
 * @return String
 */

function px_get_common_css_dir()
{
  return px_get_relative_root().'px-skins/common/css';
}

/**
 * Obtain a relative URL for the skin image directory.
 *
 * @return string Relative URL to the skin image directory.
 */

function px_get_image_dir()
{
  return px_get_relative_root().'px-skins/skins/'.PX_SKIN.'/images/';
}

/**
 * Obtain a relative URL for the common image directory.
 *
 * @return string Relative URL to the common image directory.
 */

function px_get_common_image_dir()
{
  return px_get_relative_root().'px-skins/common/images/';
}

/**
 * Obtain a relative URL for the application root.
 *
 * @return string Relative URL to the application root.
 */

function px_get_relative_root()
{
  $dirbits = explode('/',$_SERVER['SCRIPT_NAME']);
  $path = '/';
  $bitcount = count($dirbits);
  for($i = 0; $i < $bitcount - 1; $i++)
  {
    if($dirbits[$i])
    {
      $path .= $dirbits[$i].'/';
    }
  }
  return $path;
}

/**
 * Obtain the filename of the front controller.
 *
 * @return string Filename of the front controller.
 */

function px_get_front_controller_script_name()
{
  $script = explode('/',$_SERVER['SCRIPT_NAME']);
  return $script[count($script) - 1];
}

/**
 * Get page link numbers for use in pagination.
 *
 * @param integer $totalresults Total number of results/items.
 * @param integer $currentpage Current request page number.
 * @param integer $perpage Number of results per page.
 * @param integer $boundary How many page links appear either side of the current page.
 *
 * @return array
 */

function px_get_page_links($totalresults, $currentpage = 1, $perpage = 10, $boundary = 6)
{
  $lastpage = ceil($totalresults / $perpage);
  if(!$lastpage)
  {
    $lastpage = 1;
  }
  $links = array();
  $start = $currentpage - $boundary;
  if($start < 1)
  {
    $start = 1;
  }
  $end = $currentpage + $boundary;
  if($lastpage < $end)
  {
    $end = $lastpage;
  }
  while($start <= $end)
  {
    $links[] = $start++;
  }
  return array($links, $lastpage);
}

/**
 * Obtain a search query based on the refererring URL.
 *
 * @return string Keyword query string (or false on failure).
 */
function px_get_search_query()
{
  if(array_key_exists('HTTP_REFERER', $_SERVER) && strlen($_SERVER['HTTP_REFERER']))
  {
    $keywords = px_parse_search_keywords($_SERVER['HTTP_REFERER']);
    if(is_array($keywords) && count($keywords) > 0)
    {
      return implode(' ', $keywords);
    }
  }
  return false;
}

define('PX_RELATIVE_ROOT', px_get_relative_root());
define('PX_SCRIPT_NAME', px_get_front_controller_script_name());
define('PX_IMAGE_DIR', px_get_image_dir());
define('PX_CSS_DIR', px_get_css_dir());
define('PX_COMMON_CSS_DIR', px_get_common_css_dir());
define('PX_COMMON_IMAGE_DIR', px_get_common_image_dir());
define('PX_HOME', PX_RELATIVE_ROOT.PX_SCRIPT_NAME);