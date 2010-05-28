<?php if(!$vars['search_results']['results']['current_results']): ?>
      <p>No results found</p>
<?php else: ?>
<?php
if($vars['from_page'] == 'search')
{
  px_include_template('searchfilter', $vars);
}
?>
      <ol>
<?php foreach($vars['search_results']['products'] AS $product): ?>
        <li>
          <?php echo px_affiliate_link_to(px_image_tag(htmlentities($product['image_url']), $product['product_name'], 'product'), $product['product_url'], array(), null)."\n"; ?>
          <h3><?php echo px_affiliate_link_to($product['product_name'], $product['product_url'], array(), 'productname'); ?></h3>
          <p class="price"><?php echo $product['currency_symbol'].$product['online_price']; ?></p>
<?php
if(strlen($product['description']) > 100)
{
  $product['description'] = htmlentities(substr($product['description'], 0, 97)).'&#8230;';
}
else
{
  $product['description'] = htmlentities($product['description']);
}
?>
          <p class="description"><?php echo $product['description']; ?></p>
          <ul>
            <li><?php echo px_affiliate_link_to($product['merchant_name'], $product['merchant_url'], array(), null); ?></li>
<?php if($vars['interstitial_product_page'] == '2'): ?>
            <li><?php echo px_link_to('More', 'product', array('id' => $product['item_id'], 'category_id' => $vars['category_id'], 'query' => $vars['query'], 'offset' => $vars['offset'], 'from_page' => $vars['from_page'], 'sort' => $vars['sort'], 'lid' => $vars['lid'])); ?></li>
<?php elseif($vars['interstitial_product_page'] == '3'): ?>
            <li><?php echo px_link_to('More', 'productlightbox', array('id' => $product['item_id'], 'category_id' => $vars['category_id'], 'query' => $vars['query'], 'offset' => $vars['offset'], 'from_page' => $vars['from_page'], 'sort' => $vars['sort'], 'lid' => $vars['lid']), 'lbOn', false, false, 'return false;'); ?></li>
<?php else: ?>
            <li><?php echo px_affiliate_link_to('More', $product['product_url'], array(), null); ?></li>
<?php endif; ?>
          </ul>
<?php endforeach; ?>
<?php endif; ?>
        </li>
      </ol>
<?php if(!$vars['random']): ?><?php px_include_template('pagination', $vars + array('search_results' => $vars['search_results'], 'offset' => $vars['offset'], 'baseurl' => $vars['base_url'],)); ?><?php endif; ?>