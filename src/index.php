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

error_reporting(E_ALL);

define('PX_BASE_DIR',dirname(__FILE__).DIRECTORY_SEPARATOR);
define('PX_INCLUDE_DIR',PX_BASE_DIR.'px-includes'.DIRECTORY_SEPARATOR);

require_once(PX_INCLUDE_DIR.'config.php');
require_once(PX_INCLUDE_DIR.'generic_functions.php');
require_once(PX_INCLUDE_DIR.'api_core.php');
require_once(PX_INCLUDE_DIR.'api_functions.php');

$px_prefs = px_get_preferences();

define('PX_SKIN', $px_prefs['skin_directory']);
define('PX_SKIN_DIR',PX_BASE_DIR.'px-skins'.DIRECTORY_SEPARATOR.'skins'.DIRECTORY_SEPARATOR.PX_SKIN.DIRECTORY_SEPARATOR);
define('PX_TEMPLATE_DIR',PX_SKIN_DIR.'templates'.DIRECTORY_SEPARATOR);
define('PX_COMMON_SKIN_DIR', PX_BASE_DIR.'px-skins'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR);
define('PX_COMMON_TEMPLATE_DIR', PX_BASE_DIR.'px-skins'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR);

require_once(PX_INCLUDE_DIR.'skin_functions.php');

if(array_key_exists('error_message',$px_prefs))
{
  px_stop_message($px_prefs['error_message']);
}

if(!array_key_exists('page',$_REQUEST) || strpos('\\', $_REQUEST['page']) || strpos('/', $_REQUEST['page']))
{
  $page = 'main';
}
else
{
  $page = strtolower(trim($_REQUEST['page']));
}
if(file_exists(PX_SKIN_DIR.$page.'.php'))
{  include(PX_SKIN_DIR.$page.'.php');
}
else if(file_exists(PX_COMMON_SKIN_DIR.$page.'.php'))
{
  include(PX_COMMON_SKIN_DIR.$page.'.php');
}
else
{
  px_stop_message('Invalid page: '.$page);
}
