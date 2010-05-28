<?php
if(strlen($vars['product']['description']) > 350)
{
  $vars['product']['description'] = htmlentities(substr($vars['product']['description'], 0, 347)).'&#8230;';
}
else
{
  $vars['product']['description'] = htmlentities($vars['product']['description']);
}
?><?php if($vars['showbacklink']): ?>
      <?php echo px_link_to('Back to search results', $vars['from_page'], array('category_id' => $vars['category_id'], 'query' => $vars['query'], 'offset' => $vars['offset'], 'sort' => $vars['sort'], 'lid' => $vars['lid']), 'back'); ?>
<?php endif; ?>       
      <div id="productdetail">
        <?php echo px_affiliate_link_to(px_image_tag(htmlentities($vars['product']['image_url']), $vars['product']['product_name'], 'product'), $vars['product']['product_url'], array(), null); ?>    
        <h3><?php echo px_affiliate_link_to($vars['product']['product_name'], $vars['product']['product_url'], array(), 'productname'); ?></h3>
        <p><?php echo $vars['product']['description']; ?></p>
        <p class="price"><?php echo $vars['product']['currency_symbol']; ?><?php echo htmlentities($vars['product']['online_price']); ?></p>
        <p><?php echo px_affiliate_link_to('Buy now from '.$vars['product']['merchant_name'], $vars['product']['product_url'], array(), null); ?></p>
      </div>
