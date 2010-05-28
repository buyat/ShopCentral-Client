<?php

if(!array_key_exists('categories', $vars) || !is_array($vars['categories']) || count($vars['categories']) == 0)
{
  $vars['categories'] = px_get_top_level_categories();
}
?>
      <ul id="navigation">
        <li><a href="<?php echo PX_HOME; ?>?lid=<?php echo $vars['lid']; ?>">Home</a></li>
<?php foreach($vars['categories'] AS $id => $name): ?>
        <li<?php if($vars['category_id'] == $id): ?> class="on"<?php endif; ?>><?php echo px_link_to($name, 'search', array('category_id' => $id, 'from' => 'category', 'lid' => $vars['lid'])); ?></li>
<?php endforeach; ?>
      </ul>
