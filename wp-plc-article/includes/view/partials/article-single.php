<?php

?>
<div id="plc-article-single-view-<?=$sSliderID?>">
    <div style="width:100%; float:left;">
        <h3><?=$oItem->label?></h3>
    </div>
    <div style="width:100%; display: inline-block;">
        <div style="width:50%; float:left;">
            <img src="<?=$sHost?><?=$oItem->featured_image?>" style="width:100%;" />
            <?php if(isset($oItem->gallery)) {
                foreach ($oItem->gallery as $sGalleryImg) {
                    if($sGalleryImg == basename($oItem->featured_image)) {
                        continue;
                    }
                    ?>
                    <img src="<?=$sHost?>/data/article/<?=$oItem->id?>/<?=$sGalleryImg?>" style="width:100%;" />
                    <?php
                }
            }
            ?>
        </div>
        <div style="width:50%; float:left;">
            <div style="width:100%; display: inline-block;">
                <?php
                if(count($aSettings['singleview_featured_fields']) > 0) {
                    foreach($aSettings['singleview_featured_fields'] as $sFieldKey) { ?>
                        <div style="width:100%; display: inline-block;">
                            <div style="float:left; width:49%;" class="plc-slider-attribute-label"><?=$aFields[$sFieldKey]?></div>
                            <div style="float:left; width:49%; text-align:right;" class="plc-slider-attribute-value">
                                <?php
                                if(isset($oItem->$sFieldKey)) {
                                    if(is_object($oItem->$sFieldKey)) {
                                        echo $oItem->$sFieldKey->label;
                                    } else {
                                        echo $oItem->$sFieldKey;
                                    }
                                } else {
                                    echo '-';
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div style="width:100%; display: inline-block"><?=$oItem->description?></div>
</div>
