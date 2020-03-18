<?php
$sSingleViewSlug = get_option('plcarticle_singleview_slug');
$sListViewSlug = get_option('plcarticle_listview_slug');
$sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();
$aMarkupListItems = [];
$iListPos = 1;
?>
<ul class="plc-article-list">
    <?php foreach($aItems as $oItem) {
        $aLinksFinds = ['##ID##','##title##'];
        $aLinkReplaces = [$oItem->id,str_replace([' '],['-'],strtolower($oItem->label))];
        if(isset($oItem->custom_art_nr)) {
            $aLinksFinds[] = '##custom_art_nr##';
            $aLinkReplaces[] = $oItem->custom_art_nr;
        }
        $aMarkupListItems[] = (object)[
            '@type' => 'ListItem',
            'position' => $iListPos,
            'url' => site_url().'/'.$sSingleViewSlug.'/'.$oItem->id,
        ];
        ?>
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
                        $sHref = '#';
                        if(get_option('plcarticle_singleview_slug')) {
                            $sHref = home_url().'/'.get_option('plcarticle_singleview_slug');
                            if(defined('ICL_LANGUAGE_CODE')) {
                                $sHref = apply_filters( 'wpml_permalink', $sHref, ICL_LANGUAGE_CODE, true );
                            }
                            $sHref .= '/';
                            $sHref .= str_replace([' ','/'],['-','-'],strtolower($oItem->label));
                            if(isset($oItem->custom_art_nr)) {
                                $sHref .= '-vib-'.str_replace([' '],['-'],strtolower($oItem->custom_art_nr));
                            }
                            $sHref .= '/'.$oItem->id;
                            //$sHref = str_replace(['//'],['/'],$sHref);
                        }
                        ?>
                        <a href="<?=$sHref?>" title="<?=__('View Article','wp-plc-article')?>">
                            <h3><?=$sTitle?></h3>
                        </a>
                    <?php } else { ?>
                        <h3><?=$sTitle?></h3>
                    <?php } ?>
                </div>

                <?php
                $sSecondWidth = '100%';
                if(isset($oItem->featured_image)) {
                    $sSecondWidth = '50%';
                    $bPrintSingleImg = true;
                    ?>
                    <div style="width:50%; float:left; display: inline-block;">
                        <?php
                        if(isset($oItem->gallery)) {
                        if(count($oItem->gallery) > 0) {
                            $bPrintSingleImg = false;
                        ?>
                        <div style="width:100%; display:inline-block;">
                            <!-- Slider main container -->
                            <div class="plc-list-swiper-container swiper-container"
                                 style="width:400px; max-width:100%; height:250px; margin-left:0; margin-bottom:10px;">
                                <!-- Additional required wrapper -->
                                <div class="swiper-wrapper">
                                    <?php if ($oItem->featured_image != '') { ?>
                                        <div class="swiper-slide">
                                            <!-- data-elementor-open-lightbox="default" data-elementor-lightbox-slideshow="articlesingle-<?=$oItem->id?>" -->
                                            <a href="<?=$sHref?>" class="">
                                                <img src="<?= $sHost ?><?= $oItem->featured_image ?>" style="max-height:230px;" />
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
                                            <a href="<?=$sHref?>" class="">
                                                <img src="<?= $sHost ?>/data/article/<?=$oItem->id?>/<?= $sImg ?>" style="max-height:230px;" />
                                            </a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <!-- If we need pagination -->
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                        <?php }
                            }
                            if($bPrintSingleImg) {
                            ?>
                                <div style="width:100%; display: inline-block; margin-bottom:16px">
                                <a href="<?=$sHref?>" class="">
                                    <img src="<?= $sHost ?><?= $oItem->featured_image ?>" style="max-height:250px; max-width:100%;" />
                                </a>
                                </div>
                            <?php } ?>
                        <div style="margin-top:8px;">
                            <div style="width:50%; float:left;">
                                <!-- Button 1 -->
                                <a href="<?=$sHref?>" class="plc-list-button">
                                    <i class="<?=$aSettings['list_view_button_1_icon']['value']?>" aria-hidden="true"></i>
                                    &nbsp;<?=__($aSettings['list_view_button_1_text'], 'wp-plc-article')?>
                                </a>
                                <!-- Button 1 -->
                            </div>
                            <div style="width:50%; float:left;">
                                <!-- Button 2 -->
                                <a href="<?=str_replace($aLinksFinds,$aLinkReplaces,$aSettings['list_view_button_2_link']['url'])?>" class="plc-list-button">
                                    <i class="<?=$aSettings['list_view_button_2_icon']['value']?>" aria-hidden="true"></i>
                                    &nbsp;<?=__($aSettings['list_view_button_2_text'], 'wp-plc-article')?>
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
                            foreach($aSettings['listview_featured_fields'] as $sFieldKey) {
                                if($aSettings['listview_hide_emptyfields'] == 'yes') {
                                    if(!isset($oItem->$sFieldKey)) {
                                        continue;
                                    } else {
                                        if(! is_object($oItem->$sFieldKey)) {
                                            if($oItem->$sFieldKey == '') {
                                                continue;
                                            }
                                        }
                                    }
                                }
                                ?>
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
                            <a href="<?=$sHref?>" class="plc-list-button">
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
        <?php
        $iListPos++;
    } ?>
</ul>

<div>
    <ul style="list-style-type: none;">
        <?php
        $iStartPage = 1;
        $iEndPage = 10;
        if($iPage > 5) {
            $iStartPage = $iPage-4;
            $iEndPage = ($iPage-5)+10;
        }
        if($iEndPage > $iPages) {
            $iEndPage = $iPages;
        }
        for($i = $iStartPage;$i <= $iEndPage;$i++) { ?>
            <li style="float:left; position: relative; padding:4px; border:1px solid #000; margin:2px;">
                <?php if($iPage == $i) {
                    $sHref = '/'.$sListViewSlug.'/index/0/'.$i;
                    if(defined('ICL_LANGUAGE_CODE')) {
                        $sHref = apply_filters( 'wpml_permalink', $sHref, ICL_LANGUAGE_CODE, true );
                    }
                    ?>
                    <?=$i?>
                <?php } else { ?>
                    <a href="<?=$sHref?>">
                        <?=$i?>
                    </a>
                <?php } ?>
            </li>
            <?php
        }
        ?>
    </ul>
</div>
<script type="application/ld+json">
    {
        "@context":"https://schema.org",
        "@type":"ItemList",
        "itemListElement":<?=json_encode($aMarkupListItems)?>
    }
</script>

<style>
    .plc-article-list {
        list-style-type: none;
    }
</style>
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