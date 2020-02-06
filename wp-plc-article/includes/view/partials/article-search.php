<?php
?>
<div style="width:100%;" class="plc-article-search-widget">
    <form action="<?=$aSettings['search_button_link']['url']?>" method="GET">
        <?php if($aSettings['search_title'] != '') { ?>
            <h3><?=$aSettings['search_title']?></h3>
        <?php }?>
        <div>
            <input type="text" name="q" placeholder="<?=$aSettings['search_button_text']?>" />
        </div>
        <button type="submit" class="plc-search-button">
            <i class="<?=$aSettings['search_button_icon']['value']?>" aria-hidden="true"></i>
            <?=$aSettings['search_button_text']?>
        </button>
    </form>
</div>