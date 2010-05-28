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
 * CTY defines.
 *
 * I wouldn't change these unless you want your creative performance report to be inaccurate.
 */

define('PX_CTY_DEFAULT', 46);
define('PX_CTY_HOMEPAGE', 56);
define('PX_CTY_TEXT_SEARCH', 57);
define('PX_CTY_CATEGORY_SEARCH', 58);
define('PX_CTY_ITEM_PAGE', 59);
define('PX_CTY_ITEM_LIGHTBOX', 60);

/**
 * Call the Buyat API.
 *
 * @param string $action The Buyat API function name.
 * @param string $api_key Affiliate's API key.
 * @param array $args An associative array of parameters to pass to the API function.
 * @param boolean $allow_errors Whether or not to pass the response back even if it was an error.
 *
 * @return array The API's response as an associative array.
 */

function px_api_call($action, $api_key, $args, $allow_errors = false)
{
  if(($response = px_perform_post(PX_API_HOST, PX_API_PATH, px_construct_api_request($action, $api_key, $args))) === false)
  {
    px_stop_message('Unable to contact API');
  }

  $response = unserialize($response);

  if(!is_array($response))
  {
    px_stop_message('Invalid response from API');
  }

  if(!$allow_errors && array_key_exists('error_code', $response))
  {
    if(array_key_exists('error_message', $response))
    {
      px_stop_message("Error from API: $response[error_message]");
    }
    else
    {
      px_stop_message("Error code from API: $response[error_code]");
    }
  }

  return $response;
}

/**
 * Construct a request that can be POSTed to the Buyat API.
 * 
 * @param string $action Buyat API function name.
 * @param string $api_key Affiliate's API key.
 * @param array $args An associative array of parameters to pass to the API function.
 *
 * @return string A fragment of XML that can be POSTed to the Buyat API.
 */

function px_construct_api_request($action, $api_key, $args)
{
  $request = "<request><action>$action</action><parameters><api_key>$api_key</api_key>";

  foreach($args AS $key => $value)
  {
    $request .= "<$key>$value</$key>";
  }

  $request .= '</parameters></request>';
  return $request;
}

/** 
 * POST a payload to a host/path.
 *
 * @param string $host The hostname of the remote server to POST to.
 * @param string $path The request path on the remote server.
 * @param string $payload The content to POST.
 *
 * @return mixed The server's response (minus HTTP gubbins) on success; boolean false on failure.
 */

function px_perform_post($host, $path, $payload)
{
  $request = "POST $path HTTP/1.0\r\nHost: $host\r\n";
  $request .= "Content-Type: application/x-www-form-urlencoded; charset=iso-8859-1\r\n";
	$request .= 'Content-Length: '.strlen($payload)."\r\nUser-Agent: ProductX\r\n\r\n$payload";

  $response = '';

	if(($socket = @fsockopen($host, 80, $errno, $errstr, 30)) === false)
  {
    return false;
  }

  fwrite($socket, $request);

  while(!feof($socket))
  {
    $response .= fgets($socket, 1160);
  }

  fclose($socket);
  $response = explode("\r\n\r\n", $response, 2);
  return $response[1];
}
