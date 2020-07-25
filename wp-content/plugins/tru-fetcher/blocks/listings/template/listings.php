<?php
$category = get_category_by_slug($block['data']['listing_block_category']);
if (isset($category->slug)) {
?>
    <div id="listing_block" data-category="<?php echo  $category->slug; ?>"></div>
<?php
}
?>