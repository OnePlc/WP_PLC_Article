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
$aImages = [];
if($oItem->featured_image != '') {
    $aImages[] = $sHost.$oItem->featured_image;
}
$sTitle = str_replace($aFindPlaceholders,$aReplacePlaceholders,$aSettings['singleview_title_template']);
$sArticleNr = (isset($oItem->custom_art_nr)) ? $oItem->custom_art_nr : $oItem->id;
$sBrand = (isset($oItem->manufacturer_idfs)) ? $oItem->manufacturer_idfs->label : '-';
?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "<?=$sTitle?>",
        "offers": {},
        "mpn": "<?=$sArticleNr?>",
        "image": <?=json_encode($aImages)?>,
        "description": "<?=strip_tags($oItem->description)?>",
        "sku": "<?=$sArticleNr?>",
        "brand": {
            "@type": "Thing",
            "name": "<?=$sBrand?>"
        }
    }
</script>
<div id="plc-article-single-view-<?=$sSliderID?>" class="plc-article-single-view">
    <div style="width:100%; float:left;">
        <h3><?=$sTitle?></h3>
    </div>
    <div style="width:100%; display: inline-block;">
        <div style="width:50%; float:left;">
            <div style="width:100%; display:inline-block;">
                <?php
                $bPrintSingleImg = true;
                if(isset($oItem->gallery)) {
                if (count($oItem->gallery) > 0) {
                    $bPrintSingleImg = false;
                    ?>
                <!-- Slider main container -->
                <div class="plc-list-swiper-container swiper-container"
                     style="width:400px; height:300px; margin-left:0;" data-swiper-autoplay="2000">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <?php if ($oItem->featured_image != '') { ?>
                            <div class="swiper-slide">
                                <a href="<?= $sHost ?><?= $oItem->featured_image ?>" class="" data-elementor-open-lightbox="default" data-elementor-lightbox-slideshow="articlesingle-<?=$oItem->id?>">
                                    <img src="<?= $sHost ?><?= $oItem->featured_image ?>" style="max-height:235px;" />
                                </a>
                            </div>
                        <?php } ?>
                        <!-- Slides -->
                        <?php
                        foreach ($oItem->gallery as $sImg) {
                            if ($sImg == basename($oItem->featured_image)) {
                                continue;
                            }
                            ?>
                            <div class="swiper-slide">
                                <a href="<?= $sHost ?>/data/article/<?=$oItem->id?>/<?= $sImg ?>" class="" data-elementor-open-lightbox="default" data-elementor-lightbox-slideshow="articlesingle-<?=$oItem->id?>">
                                    <img src="<?= $sHost ?>/data/article/<?=$oItem->id?>/<?= $sImg ?>" style="max-height:235px;" />
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                </div>
                <?php }
                }

                if($bPrintSingleImg) {
                ?>
                    <a href="<?= $sHost ?><?= $oItem->featured_image ?>" class="" data-elementor-open-lightbox="default" data-elementor-lightbox-slideshow="articlesingle-<?=$oItem->id?>">
                        <img src="<?= $sHost ?><?= $oItem->featured_image ?>" style="max-height:250px; max-width:100%;" />
                    </a>
                <?php } ?>
            </div>
        </div>
        <div style="width:50%; float:left; margin-top:20px;">
            <div style="width:100%; display: inline-block; margin-top:20px;">
                <?php
                if(count($aSettings['singleview_featured_fields']) > 0) {
                    foreach($aSettings['singleview_featured_fields'] as $sFieldKey) { ?>
                        <div style="width:100%; display: inline-block;">
                            <div style="float:left; width:49%;" class="plc-single-attribute-label"><?=$aFields[$sFieldKey]?></div>
                            <div style="float:left; width:49%; text-align:right;" class="plc-single-attribute-value">
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
            <div style="width:50%; float:left; margin-top:20px;">
                <!-- Button 1 -->
                <a href="<?=str_replace(['##ID##','##title##'],[$oItem->id,$oItem->label],$aSettings['single_view_button_1_link']['url'])?>" class="plc-single-button" <?=($aSettings['single_view_button_1_link']['is_external']) ? 'target="_blank"' : ''?>>
                    <i class="<?=$aSettings['single_view_button_1_icon']['value']?>" aria-hidden="true"></i>
                    &nbsp;<?=$aSettings['single_view_button_1_text']?>
                </a>
                <!-- Button 1 -->
            </div>
            <?php if(isset($oItem->weblink_youtube)) {
                if ($oItem->weblink_youtube != '') {
                    $sHref = str_replace($aFindPlaceholders, $aReplacePlaceholders, $aSettings['single_view_button_2_link']['url']);
                    ?>
                    <div style="width:50%; float:left;  margin-top:20px;">
                        <!-- Button YT -->
                        <a href="<?= $sHref ?>"
                           class="plc-single-button" <?= ($aSettings['single_view_button_2_link']['is_external']) ? 'target="_blank"' : '' ?>>
                            <i class="<?= $aSettings['single_view_button_2_icon']['value'] ?>" aria-hidden="true"></i>
                            &nbsp;<?= $aSettings['single_view_button_2_text'] ?>
                        </a>
                        <!-- Button YT -->
                    </div>
                <?php }
            }?>
        </div>
    </div>
    <?php if($aSettings['singleview_show_description'] == 'yes') { ?>
       <div style="width:100%; display: inline-block; margin-top:20px;">
           <?php if($aSettings['singleview_description_title'] != '') { ?>
               <hr style="margin:0;" />
               <h4 style="margin:0;" class="plc-single-description-title"><?=$aSettings['singleview_description_title']?></h4>
               <hr style="margin:0;"/>
           <?php } ?>
           <div style="width:100%; display: inline-block" class="plc-single-description">
               <?php if(defined('ICL_LANGUAGE_CODE')) {
                   $sLang = '';
                   switch(ICL_LANGUAGE_CODE) {
                       case 'en':
                           $sLang = '_en_us';
                           break;
                       default:
                           break;
                   }
                   if(property_exists($oItem,'description'.$sLang)) {
                       $sDescName = 'description'.$sLang;
                       echo $oItem->$sDescName;
                   }
               } else {
                   echo $oItem->description;
               }?>
           </div>
       </div>
    <?php } ?>
</div>
<script>
    jQuery(function() {
        jQuery('.plc-list-swiper-container').each(function () {
            var mySwiper = new Swiper(jQuery(this), {
                direction: 'horizontal',
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                },
                autoplay: {
                    delay: 5000,
                }
            });
        });
    });
</script>