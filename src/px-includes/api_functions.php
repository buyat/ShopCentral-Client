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
 * Get a random set of products.
 *
 * @param integer $limit Maximum number of products to retrieve. Should be between 1 and 100 inclusive.
 * @param integer $CTY Creative type, for reporting purposes.
 * @param integer $CID Creative ID i.e. the ID of the shop, for reporting purposes.
 * @param string $lid Link ID, for customer tracking purposes.
 *
 * @return array An associative array of products.
 */

function px_get_random_products($limit, $CTY = PX_CTY_DEFAULT, $CID = 0, $lid = '')
{
  if($limit > 100)
  {
    $limit = 100;
  }
  else if($limit <= 0)
  {
    $limit = 10;
  }
  return px_api_call('buyat.affiliate.content.products.randomitems', PX_API_KEY, array('limit' => $limit, 'CTY' => $CTY, 'CID' => $CID, 'version' => PX_VERSION, 'lid' => $lid));
}

/**
 * Get information about a single product.
 *
 * @param integer $id Unique identifer of the product.
 * @param integer $CTY Creative type, for reporting purposes.
 * @param integer $CID Creative ID i.e. the ID of the shop, for reporting purposes.
 * @param string $lid Link ID, for customer tracking purposes.
 *
 * @return array An associative array of product details.
 */

function px_get_product_info($id, $CTY = PX_CTY_DEFAULT, $CID = 0, $lid = '')
{
  return px_api_call('buyat.affiliate.content.products.productinfo', PX_API_KEY, array('item_id' => $id, 'CTY' => $CTY, 'CID' => $CID, 'version' => PX_VERSION, 'lid' => $lid));
}

 /**
  * Get the shop preferences for the current shop.
  *
  * @return array An associative array of preferences.
  */

function px_get_preferences()
{
  return px_normalise_preferences(px_api_call('buyat.affiliate.productx.getpreferences', PX_API_KEY, array('shop_id' => PX_SHOP_ID, 'version' => PX_VERSION)));
}

/**
 * Get products from the API based on search criteria.
 *
 * @param string $query Free text query.
 * @param integer $category_id Top level category ID.
 * @param integer $page Number of the page to fetch.
 * @param integer $perpage Number of products to fetch per page (1 to 100).
 * @param integer $CTY Creative type ID, for reporting purposes.
 * @param integer $CID Creative ID i.e. the ID of the shop, for reporting purposes.
 * @param string $sort Criteria to order search results.
 * @param string $lid Link ID, for customer tracking purposes.
 *
 * @return array An associative array of results.
 */

function px_product_search($query, $category_id = null, $page = 1, $perpage = 10, $CTY = PX_CTY_DEFAULT, $CID = 0, $sort = null, $lid = '')
{
  if($perpage > 100)
  {
    $perpage = 100;
  }
  else if($perpage <= 0)
  {
    $perpage = 10;
  }

  switch($sort)
  {
    case 'price_high':
    {
      $sortBy = 'price';
      $sortOrder = 'desc';
      break;
    }
    case 'price_low':
    {
      $sortBy = 'price';
      $sortOrder = 'asc';
      break;
    }
    case 'relevance':
    default:
    {
      $sortBy = 'relevance';
      $sortOrder = 'desc';
      break;
    }
  }

  $criteria = array(
    'query'       => urlencode($query),
    'category_id' => $category_id,
    'page'        => $page,
    'perpage'     => $perpage,
    'shop_id'     => PX_SHOP_ID,
    'CTY'         => $CTY,
    'CID'         => $CID,
    'version'     => PX_VERSION,
    'sort'        => $sortBy,
    'sortorder'   => $sortOrder,
    'lid'         => $lid,
  );

  $search_results = px_api_call('buyat.affiliate.productx.productsearch', PX_API_KEY, $criteria);

  $products = array(
    'results'  => array(),
    'products' => array(),
  );

  $products['results']['current_results'] = $search_results['current_results'];
  $products['results']['total_results'] = $search_results['total_results'];
  $products['results']['start'] = $search_results['start'];
  $products['results']['limit'] = $search_results['limit'];

  if($products['results']['current_results'] > 0)
  {
    foreach($search_results['products'] AS $index => $product_info)
    {
      $products['products'][] = $product_info['value'];
    }
  }

  return $products;
}

/**
 * Get the top level of categories from the API.
 *
 * @return array An associative array of categories in the format <category ID> => <category name>.
 */

function px_get_top_level_categories()
{
  $categories = array();

  $response = px_api_call('buyat.affiliate.content.categories.listtoplevel', PX_API_KEY, array('version' => PX_VERSION));

  if(array_key_exists('categories', $response))
  {
    foreach($response['categories'] AS $index => $category)
    {
      if(!is_array($category) || !(array_key_exists('value', $category)) ||
         !is_array($category['value']) ||(!array_key_exists('category_id', $category['value']) ||
         !array_key_exists('category_name', $category['value'])))
      {
        continue;
      }

      $categories[$category['value']['category_id']]=$category['value']['category_name'];
    }
  }

  return $categories;
}

/**
 * The API formats some nested data strangely thanks to the XML output handler which we aren't even using here!
 * Reformat the preferences to be more natural to use.
 *
 * @param array $px_prefs An associative array of preferences as retrieved directly from the API.
 *
 * @return array An associative array of normalised preferences.
 */

function px_normalise_preferences($px_prefs)
{
  if(array_key_exists('categories', $px_prefs))
  {
    $categories = $px_prefs['categories'];
    $normalised_categories = array();
    foreach($categories['value'] AS $id => $category)
    {
      $normalised_categories[$category['value']['category_id']] = $category['value']['category_name'];
    }
    $px_prefs['categories'] = $normalised_categories;
  }

  if(array_key_exists('programmes', $px_prefs))
  {
    $programmes = $px_prefs['programmes'];
    $normalised_programmes = array();
    foreach($programmes['value'] AS $id => $programme)
    {
      $normalised_programmes[$programme['value']['programme_id']] = $programme['value']['programme_name'];
    }
    $px_prefs['programmes'] = $normalised_programmes;
  }

  return $px_prefs;
}
