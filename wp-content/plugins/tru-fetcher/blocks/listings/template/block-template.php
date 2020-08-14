<?php
if (!array_key_exists("data", $block)) {
    echo "Listing block data is invalid.";
    return false;
}
if (!array_key_exists("listing_block_category", $block["data"]) || $block['data']['listing_block_category'] === "") {
    echo "Listings block category is invalid.";
    return false;
}
?>
<div id="listing_block" data-category="<?php echo  $block['data']['listing_block_category']; ?>"></div>
