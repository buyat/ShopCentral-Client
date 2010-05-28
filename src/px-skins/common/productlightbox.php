<?php
  $id = (array_key_exists('id', $_REQUEST) && intval($_REQUEST['id'])) ? intval($_REQUEST['id']) : null;
  $lid = (array_key_exists('lid',$_REQUEST) && strlen($_REQUEST['lid'])) ? trim($_REQUEST['lid']) : '';
  if($id !== null)
  {
    $product = px_get_product_info($id, PX_CTY_ITEM_LIGHTBOX, $px_prefs['CID'], $lid);
    if(array_key_exists('value', $product))
    {
      $product_info = $product['value'];
    }
    else
    {
      $product_info = null;
    }
  }
  $offset = (array_key_exists('offset',$_REQUEST) && intval($_REQUEST['offset'])) ? intval($_REQUEST['offset']) : 1;
  $query = (array_key_exists('query',$_REQUEST) && strlen(trim($_REQUEST['query']))) ? trim($_REQUEST['query']) : '';
  $category_id = (array_key_exists('category_id',$_REQUEST) && intval($_REQUEST['category_id'])) ? intval($_REQUEST['category_id']) : 1;
  $from_page = (array_key_exists('from_page', $_REQUEST) && strlen(trim($_REQUEST['from_page']))) ? trim($_REQUEST['from_page']) : 'main';
  $sort = (array_key_exists('sort', $_REQUEST) && strlen($_REQUEST['sort'])) ? trim($_REQUEST['sort']) : '';
  $px_prefs['offset'] = $offset;
  $px_prefs['query'] = $query;
  $px_prefs['category_id'] = $category_id;
  $px_prefs['sort'] = $sort;
  $px_prefs['lid'] = $lid;
?>
<div id="product">
  <a href="#" class="lbAction close" rel="deactivate">Close</a>
  <?php if($id === null): ?>
    <p>No product specified!</p>
  <?php elseif($product_info !== null): ?>
    <?php px_include_template('productinfo', array('product' => $product_info, 'offset' => $offset, 'query' => $query, 'category_id' => $category_id, 'lid' => $lid, 'from_page' => $from_page, 'showbacklink' => false, 'sort' => $sort)); ?>
  <?php else: ?>
    <p>Sorry! An error occurred.</p>
  <?php endif; ?>

</div>