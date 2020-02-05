<!-- WP PLC Article Slider -->
<div class="plc-article-swiper-container swiper-container" id="plc-slider-<?=$sSliderID?>" data-slides-per-view="<?=$aSettings['section_slider_slides_per_view']?>">
    <!-- Swiper Slider -->
    <div class="swiper-wrapper">
        <?php foreach($aItems as $oItem) {?>
            <!-- Slide -->
            <div class="swiper-slide">
                <div style="display: inline-block; width:100%;">
                    <?php if(isset($oItem->image)) { ?>
                    <!-- Slide Image -->
                    <figure class="plc-slider-image-box-img" style="width:100%; margin-bottom: <?=$aSettings['slider_item_spacer']['size'].$aSettings['slider_item_spacer']['unit']?>;">
                        <a href="#<?=$oItem->id?>" class="plc-article-popup" title="Mehr Informationen">
                            <div style="height:200px; width:100%; min-width:100%; background:url(<?=$sHost?>/data/article/<?=$oItem->id?>/avatar.png) no-repeat 100% 50%; background-size:cover;">
                                &nbsp;
                            </div>
                        </a>
                    </figure>
                    <!-- Slide Image -->
                    <?php } ?>
                    <!-- Slide Content -->
                    <div class="plc-article-slider-box-content" style="width:100%; text-align:center; margin-top:<?=$aSettings['slider_item_spacer']['size'].$aSettings['slider_item_spacer']['unit']?>; margin-bottom:<?=$aSettings['slider_item_spacer']['size'].$aSettings['slider_item_spacer']['unit']?>; min-height:200px;">
                        <!-- Title -->
                        <div style="height:90px; position: relative; overflow:hidden; vertical-align: middle;">
                            <?php $sHref = (get_option('plcarticle_singleview_slug')) ? '/'.get_option('plcarticle_singleview_slug').'/'.$oItem->id : '#'.$oItem->id; ?>
                            <?php $sClass = (get_option('plcarticle_singleview_slug')) ? '' : 'plc-article-popup'; ?>
                            <a href="<?=$sHref?>" class="<?=$sClass?>" title="Mehr Informationen">
                                <h3 class="plc-slider-title" style="display: inline-block; width:100%; vertical-align:middle; text-align:<?=$aSettings['event_title_align']?>;">
                                    <?= $oItem->text ?>
                                </h3>
                            </a>
                        </div>
                        <!-- Title -->
                    </div>
                    <!-- Slide Content -->
                </div>
            </div>
            <!-- Slide -->
        <?php } ?>
    </div>
    <!-- Swiper Slider -->

    <!-- Slider Controls -->
    <div class="plc-swiper-last" data-slider-id="<?=$sSliderID?>" style="position:absolute; z-index:2 !important; top:50%; float:left; font-size:24px; color:#575756; left:-32px;"><i class="fas fa-chevron-circle-left"></i></div>
    <div class="plc-swiper-next" data-slider-id="<?=$sSliderID?>" style="position:absolute; z-index:2 !important; top:50%; right:-32px; float:right; font-size:24px; color:#575756;"><i class="fas fa-chevron-circle-right"></i></div>
    <!-- Slider Controls -->
</div>
<!-- WP PLC Article Slider -->

