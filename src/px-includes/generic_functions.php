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
 * Stop execution and display an error message.
 *
 * @param string $message The error message
 *
 * @return void
 */

function px_stop_message($message, $title = 'Error')
{
  if(headers_sent())
  {
    die('<pre>'.print_r($message,true).'</pre>');
  }

  px_send_nocache_headers();

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <title><?php echo $title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  </head>
  <body>
    <div class="error-message">
      <?php echo $message; ?>
    </div>
  </body>
</html><?php
  exit;
}

/**
 * Send headers that will tell the browser not to cache the response.
 *
 * @return void
 */

function px_send_nocache_headers()
{
  header('Expires: Sat, 1 Jan 2000 00:00:00 GMT' );
  header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
  header('Cache-Control: no-cache, must-revalidate, max-age=0');
  header('Pragma: no-cache');
}

/**
 * Redirect to a URL.
 * If the headers have already been sent, try a META redirect instead.
 *
 * @param string URL to redirect to.
 *
 * @return void
 */

function px_redirect($url)
{
  if(!headers_sent())
  {
    header("Location: $url");
    exit;
  }

  px_send_nocache_headers();

  ?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
  "http://www.w3.org/TR/html4/strict.dtd">
  <html>
    <head>
      <title>Redirecting</title>
      <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
      <meta http-equiv="refresh" content="0;URL=<?php echo $url; ?>">
    </head>
    <body>
      <div class="error-message">
        Redirecting...
      </div>
    </body>
  </html><?php
  exit;
}

/**
 * Parse the search keywords from a URL.
 *
 * @param string $url The URL to parse.
 *
 * @return array Array of parsed keywords.
 */
function px_parse_search_keywords($url)
{
  $engine_specs = array(
    'google' => 'q',
    'bing' => 'q',
    'yahoo' => 'p',
    'ask' => 'q',
  );
  $keywords = array();
  $query_params = array();
  $parsed_url = parse_url($url);
  if(!array_key_exists('host', $parsed_url))
  {
    return $keywords;
  }
  if(!array_key_exists('query', $parsed_url))
  {
    if(array_key_exists('fragment', $parsed_url))
    {
      $parsed_url['query'] = $parsed_url['fragment'];
    }
    else
    {
      return $keywords;
    }
  }
  if(array_key_exists('query', $parsed_url))
  {
    $exploded_query = explode('&', urldecode($parsed_url['query']));
    if(is_array($exploded_query) && count($exploded_query) > 0)
    {
      foreach($exploded_query AS $index => $value)
      {
        $exploded_value = explode('=', $value);
        if(is_array($exploded_value) && count($exploded_value) == 2)
        {
          $query_params[$exploded_value[0]] = $exploded_value[1];
        }
      }
    }
  }
  if(count($query_params) < 1)
  {
    return $keywords;
  }
  $host_parts = explode('.', $parsed_url['host']);
  if(is_array($host_parts) && count($host_parts) > 0)
  {
    foreach($host_parts AS $index => $value)
    {
      if(array_key_exists($value, $engine_specs) && array_key_exists($engine_specs[$value], $query_params))
      {
        $keywords = explode(' ', $query_params[$engine_specs[$value]]);
        break;
      }
    }
  }
  if(count($keywords) > 0)
  {
    foreach($keywords AS $index => $value)
    {
      if(!strlen($value))
      {
        unset($keywords[$index]);
      }
      $keywords[$index] = str_replace('\'', '', $keywords[$index]);
      $keywords[$index] = str_replace('"', '', $keywords[$index]);
    }    
  }
  return $keywords;
}
