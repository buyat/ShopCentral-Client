<?php

$offset = (array_key_exists('offset',$_REQUEST) && intval($_REQUEST['offset'])) ? intval($_REQUEST['offset']) : 1;
$perpage = $px_prefs['products_per_page'];
$query = (array_key_exists('query', $_REQUEST) && strlen($_REQUEST['query'])) ? trim($_REQUEST['query']) : '';
$category_id = (array_key_exists('category_id', $_REQUEST) && intval($_REQUEST['category_id'])) ? intval($_REQUEST['category_id']) : null;
$sort = (array_key_exists('sort', $_REQUEST) && strlen($_REQUEST['sort'])) ? trim($_REQUEST['sort']) : '';
$lid = (array_key_exists('lid',$_REQUEST) && strlen($_REQUEST['lid'])) ? trim($_REQUEST['lid']) : '';

$CTY = (array_key_exists('from', $_REQUEST) && $_REQUEST['from'] == 'category')?PX_CTY_CATEGORY_SEARCH:PX_CTY_TEXT_SEARCH;
$px_search_results = px_product_search($query, $category_id, $offset, $perpage, $CTY, $px_prefs['CID'], $sort, $lid);
$base_url = px_get_url('search', array(
  'query' => $query,
  'category_id' => $category_id,
  'sort' => $sort,
  'lid' => $lid,
));
$px_prefs['from_page'] = 'search';
$px_prefs['search_results'] = $px_search_results;
$px_prefs['base_url'] = $base_url;
$px_prefs['offset'] = $offset;
$px_prefs['perpage'] = $perpage;
$px_prefs['query'] = $query;
$px_prefs['category_id'] = $category_id;
$px_prefs['random'] = false;
$px_prefs['sort'] = $sort;
$px_prefs['lid'] = $lid;

px_include_template('header', $px_prefs);
px_include_template('searchresults', $px_prefs);
px_include_template('footer', $px_prefs);