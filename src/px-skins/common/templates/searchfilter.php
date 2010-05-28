      <div id="searchfilter">
        <label for="sortby">Sort by:
          <select id="sortby" name="order" onchange="document.location.href = '<?php echo px_get_url($vars['from_page'], array('offset' => '1', 'query' => $vars['query'], 'category_id' => $vars['category_id'], 'lid' => $vars['lid'])); ?>'+'&amp;sort='+document.getElementById('sortby').value">
            <option value="relevance"<?php if($vars['sort'] == 'relevance' || !$vars['sort']):?> selected<?php endif; ?>>Relevance</option>
            <option value="price_high"<?php if($vars['sort'] == 'price_high'):?> selected<?php endif; ?>>Price (high to low)</option>
            <option value="price_low"<?php if($vars['sort'] == 'price_low'):?> selected<?php endif; ?>>Price (low to high)</option>
          </select>
        </label>
      </div>
