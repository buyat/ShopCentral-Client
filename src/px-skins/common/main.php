<?php

  $offset = (array_key_exists('offset',$_REQUEST) && intval($_REQUEST['offset'])) ? intval($_REQUEST['offset']) : 1;
  $lid = (array_key_exists('lid',$_REQUEST) && intval($_REQUEST['lid'])) ? intval($_REQUEST['lid']) : 1;
  $perpage = $px_prefs['products_per_page'];
  $referer_query = (array_key_exists('domainer_mode', $px_prefs) && $px_prefs['domainer_mode'] == 'y') ? px_get_search_query() : false;
  $query = (strlen($px_prefs['default_search_keywords'])) ? trim($px_prefs['default_search_keywords']) : '';
  $category_id = (intval($px_prefs['default_search_category'])) ? intval($px_prefs['default_search_category']) : null;
  $lid = (array_key_exists('lid', $_REQUEST) && strlen($_REQUEST['lid'])) ? trim($_REQUEST['lid']) : '';
  if(array_key_exists('sort', $_REQUEST))
  {
    $sort = strtolower(trim($_REQUEST['sort']));
  }
  else
  {
    $sort_id = (intval($px_prefs['default_sort']))? intval($px_prefs['default_sort']):1;
    $sort = null;
    switch($sort_id)
    {
      case 2:
      {
        $sort = 'price_high';
        break;
      }
      case 3:
      {
        $sort = 'price_low';
        break;
      }
      default:
      case 1:
      {
        $sort = 'relevance';
        break;
      }
    }
  }
  $px_search_results = false;
  if($referer_query && !strlen($query))
  {
    $px_search_results = px_product_search($referer_query, $category_id, $lid, $perpage, PX_CTY_HOMEPAGE, $px_prefs['CID'], $sort, $lid);
    if($px_search_results['results']['current_results'])
    {
      $query = $referer_query;
    }
  }
  if(!$px_search_results || !$px_search_results['results']['current_results'])
  {
    $px_search_results = px_product_search($query, $category_id, $lid, $perpage, PX_CTY_HOMEPAGE, $px_prefs['CID'], $sort, $lid);
  }
  if(!$px_search_results['results']['current_results'])
  {
    $px_search_results['results']['current_results'] = px_get_random_products($px_prefs['products_per_page'], PX_CTY_HOMEPAGE, $px_prefs['CID'], $lid);
    $px_random = true;
  }
  else
  {
    $px_random = false;
  }

  $base_url = px_get_url('search', array(
    'query' => $query,
    'category_id' => $category_id,
    'lid' => $lid,
  ));
  $px_prefs['from_page'] = 'main';
  $px_prefs['search_results'] = $px_search_results;
  $px_prefs['base_url'] = $base_url;
  $px_prefs['lid'] = $lid;
  $px_prefs['query'] = '';
  $px_prefs['category_id'] = '';
  $px_prefs['random'] = $px_random;
  $px_prefs['sort'] = $sort;
  $px_prefs['lid'] = $lid;
  $px_prefs['offset'] = $offset;

  px_include_template('header', $px_prefs);
?>
      <h2>Welcome</h2>
      <p class="introduction"><?php echo htmlentities($px_prefs['welcome_text']); ?></p>
<?php px_include_template('searchresults', $px_prefs); ?>
<?php px_include_template('footer', $px_prefs); ?>