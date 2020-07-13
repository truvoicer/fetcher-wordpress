<?php
$category = get_category($block['data']['listing_block_category']);
?>
<div id="listing_block" data-category="<?php echo  $category->slug; ?>"></div>