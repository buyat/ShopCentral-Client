<?php
  if(!array_key_exists('query', $vars) || (array_key_exists('from_page', $vars) && $vars['from_page'] == 'main')) $vars['query'] = '';
  if(!array_key_exists('category_id', $vars) || (array_key_exists('from_page', $vars) && $vars['from_page'] == 'main')) $vars['category_id'] = '';
  if(!array_key_exists('categories', $vars)) $vars['categories'] = px_get_top_level_categories();
?>
      <form action="<?php echo PX_RELATIVE_ROOT.PX_SCRIPT_NAME; ?>" method="GET">
        <input type="hidden" name="page" value="search">
        <input type="hidden" name="lid" value="<?php echo $vars['lid']; ?>">
        <input type="text" name="query" value="<?php echo htmlentities($vars['query']); ?>" id="search" <?php if(strlen($vars['query'])): ?>style="background-image: none;"<?php endif; ?> onBlur="if(this.value.length > 0) { this.style.backgroundImage = 'none'; } " onClick="this.style.backgroundImage = 'none';">
        <select name="category_id" class="select">
          <option value="">All categories</option>
<?php foreach($vars['categories'] AS $id => $name): ?>
          <option value="<?php echo $id; ?>"<?php if($vars['category_id'] == $id): ?> selected<?php endif; ?>><?php echo $name; ?></option>
<?php endforeach; ?>
        </select>
        <input class="button" type="submit" value="Search">
      </form>
