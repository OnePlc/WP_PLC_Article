<?php
?>
<div class="plc-admin">
    <div class="plc-settings-wrapper">
        <!-- Header START -->
        <div class="plc-settings-header">
            <div class="plc-settings-header-main">
                <div style="width:33%; text-align: left;">
                    <div class="plc-settings-header-main-title">
                        WP PLC Article <small>Version <?=WPPLC_ARTICLE_VERSION?></small>
                    </div>
                </div>
                <div style="width:33%; text-align: center;">
                    <img src="<?=plugins_url('assets/img/icon.png', WPPLC_ARTICLE_MAIN_FILE)?>" style="max-height:42px;"/>
                </div>
                <div style="width:33%; text-align: right;">
                    Need help?
                </div>
            </div>
        </div>
        <!-- Header END -->
        <main class="plc-admin-main">
            <!-- Menu START -->
            <div class="plc-admin-menu-container">
                <nav class="plc-admin-menu" style="width:70%; float:left;">
                    <ul class="plc-admin-menu-list">
                        <li class="plc-admin-menu-list-item">
                            <a href="#/general">
                                <?=__('Settings','wp-plc-article')?>
                            </a>
                        </li>
                        <?php if(get_option('plcarticle_elementor_active') == true) { ?>
                        <li class="plc-admin-menu-list-item">
                            <a href="#/elementor">
                                <?=__('Elementor','wp-plc-article')?>
                            </a>
                        </li>
                        <?php } ?>
                        <?php if(get_option('plcarticle_shortcodes_active') == true) { ?>
                            <li class="plc-admin-menu-list-item">
                                <a href="#/shortcodes">
                                    <?=__('Shortcodes','wp-plc-article')?>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if(get_option('plcarticle_singleview_active') == true) { ?>
                            <li class="plc-admin-menu-list-item">
                                <a href="#/singleview">
                                    <?=__('Single View','wp-plc-article')?>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if(get_option('plcarticle_listview_active') == true) { ?>
                            <li class="plc-admin-menu-list-item">
                                <a href="#/listview">
                                    <?=__('List View','wp-plc-article')?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
                <div class="plc-admin-alert-container" style="float:left; width:30%; padding:40px 0 40px 0;">

                </div>
            </div>
            <!-- Menu END -->

            <!-- Content START -->
            <div class="plc-admin-page-container" style="width:100%; display: inline-block; float: left;">
                <?php wp_nonce_field( 'oneplace-settings-update' ); ?>
                <?php
                // Include Settings Pages
                require_once __DIR__.'/settings/general.php';
                if(get_option('plcarticle_elementor_active') == 1) {
                    // Include Elementor Settings
                    require_once __DIR__.'/settings/elementor.php';
                }
                if(get_option('plcarticle_shortcodes_active') == 1) {
                    // Include Shortcodes Settings
                    require_once __DIR__.'/settings/shortcodes.php';
                }
                if(get_option('plcarticle_singleview_active') == 1) {
                    // Include Single View Settings
                    require_once __DIR__.'/settings/singleview.php';
                }
                if(get_option('plcarticle_listview_active') == 1) {
                    // Include Single View Settings
                    require_once __DIR__.'/settings/listview.php';
                }
                ?>
            </div>
            <!-- Content END -->
        </main>
    </div>
</div>