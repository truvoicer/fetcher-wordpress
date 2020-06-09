<?php
$category = get_category($block['data']['listing_category']);
?>
<div id="listing_block" data-category="<?php echo  $category->slug; ?>"></div>