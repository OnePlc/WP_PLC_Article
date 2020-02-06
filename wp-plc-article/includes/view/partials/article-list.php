<?php
?>
<ul class="plc-article-list">
    <?php foreach($aItems as $oItem) { ?>
    <li>
        <div style="width:100%; display: inline-block">
            <div style="width:100%; display: inline-block">
                <?php
                $aFindPlaceholders = [];
                $aReplacePlaceholders = [];
                if(is_array($aFields)) {
                    foreach(array_keys($aFields) as $sFieldKey) {
                        if(property_exists($oItem,$sFieldKey)) {
                            $aFindPlaceholders[] = '##'.$sFieldKey.'##';
                            if(is_object($oItem->$sFieldKey)) {
                                $aReplacePlaceholders[] = $oItem->$sFieldKey->label;
                            } else {
                                $aReplacePlaceholders[] = $oItem->$sFieldKey;
                            }
                        }
                    }
                }
                $sTitle = str_replace($aFindPlaceholders,$aReplacePlaceholders,$aSettings['listview_title_template']);
                ?>
                <?php if(get_option('plcarticle_singleview_active') == 1) {
                    $sSingleViewSlug = get_option('plcarticle_singleview_slug');
                    ?>
                    <a href="/<?=$sSingleViewSlug?>/<?=$oItem->id?>" title="<?=__('View Article','wp-plc-article')?>">
                        <h3><?=$sTitle?></h3>
                    </a>
                <?php } else { ?>
                    <h3><?=$sTitle?></h3>
                <?php } ?>
            </div>

            <?php
            $sSecondWidth = '100%';
            if(isset($oItem->featured_image)) {
                $sSecondWidth = '50%'; ?>
                <div style="width:50%; float:left; display: inline-block;">
                    <div style="width:100%; display:inline-block;">
                        <!-- Slider main container -->
                        <div class="plc-list-swiper-container swiper-container" style="width:400px; height:300px; margin-left:0;">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                                <?php if($oItem->featured_image != '') { ?>
                                    <div class="swiper-slide" style="background-size:contain; background: url(<?=$sHost?><?=$oItem->featured_image?>) center;">
                                    </div>
                                <?php } ?>
                                <!-- Slides -->
                                <?php
                                if(isset($oItem->gallery)) {
                                    if(count($oItem->gallery) > 0) {
                                        foreach($oItem->gallery as $sImg) {
                                            if($sImg == basename($oItem->featured_image)) {
                                                continue;
                                            }
                                            ?>
                                            <div class="swiper-slide" style="background-size:contain; background: url(<?=$sHost?>/data/article/<?=$oItem->id?>/<?=$sImg?>) center;">
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                            <!-- If we need pagination -->
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <div>
                        <div style="width:50%; float:left;">
                            <!-- Button 1 -->
                            <a href="<?=str_replace(['##ID##','##title##'],[$oItem->id,$oItem->label],$aSettings['list_view_button_1_link']['url'])?>" class="plc-list-button">
                                <i class="<?=$aSettings['list_view_button_1_icon']['value']?>" aria-hidden="true"></i>
                                &nbsp;<?=$aSettings['list_view_button_1_text']?>
                            </a>
                            <!-- Button 1 -->
                        </div>
                        <div style="width:50%; float:left;">
                            <!-- Button 2 -->
                            <a href="<?=str_replace(['##ID##','##title##'],[$oItem->id,$oItem->label],$aSettings['list_view_button_2_link']['url'])?>" class="plc-list-button">
                                <i class="<?=$aSettings['list_view_button_2_icon']['value']?>" aria-hidden="true"></i>
                                &nbsp;<?=$aSettings['list_view_button_2_text']?>
                            </a>
                            <!-- Button 2 -->
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div style="width:<?=$sSecondWidth?>; float:left;">
                <?php
                if(is_array($aSettings['listview_featured_fields'])) {
                    if(count($aSettings['listview_featured_fields']) > 0) {
                        foreach($aSettings['listview_featured_fields'] as $sFieldKey) { ?>
                            <div style="width:100%; display: inline-block;">
                                <div style="float:left; width:49%;" class="plc-list-attribute-label"><?=$aFields[$sFieldKey]?></div>
                                <div style="float:left; width:49%; text-align:right;" class="plc-list-attribute-value">
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
                }

                if(!isset($oItem->featured_image)) { ?>
                    <div style="width:50%; float:left;">
                        <!-- Button 1 -->
                        <a href="<?=str_replace(['##ID##','##title##'],[$oItem->id,$oItem->label],$aSettings['list_view_button_1_link']['url'])?>" class="plc-list-button">
                            <i class="<?=$aSettings['list_view_button_1_icon']['value']?>" aria-hidden="true"></i>
                            &nbsp;<?=$aSettings['list_view_button_1_text']?>
                        </a>
                        <!-- Button 1 -->
                    </div>
                    <div style="width:50%; float:left;">
                        <!-- Button 2 -->
                        <a href="<?=str_replace(['##ID##','##title##'],[$oItem->id,$oItem->label],$aSettings['list_view_button_2_link']['url'])?>" class="plc-list-button">
                            <i class="<?=$aSettings['list_view_button_2_icon']['value']?>" aria-hidden="true"></i>
                            &nbsp;<?=$aSettings['list_view_button_2_text']?>
                        </a>
                        <!-- Button 2 -->
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </li>
    <?php } ?>
</ul>


<style>
    .plc-article-list {
        list-style-type: none;
    }
</style>