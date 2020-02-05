<article class="plc-admin-page-general plc-admin-page" style="padding: 10px 40px 40px 40px;">
    <h1><?=__('General Settings','wp-plc-article')?></h1>
    <p>Here you find the core settings for the article</p>

    <!-- Check Elementor Support -->
    <?php if(!defined('ELEMENTOR_VERSION')) { ?>
        <p style="color:red;">Elementor is not active! You cannot use Elementor Widgets.<br/><i>Download and install Elementor (Free Version, Pro is not required)</i></p>
    <?php } elseif(ELEMENTOR_VERSION >= '2.7.0') { ?>
        <p style="color:green;">Elementor Version <?=ELEMENTOR_VERSION?> found.</p>

        <!-- Enable Elementor Support -->
        <div class="plc-admin-settings-field">
            <label class="plc-settings-switch">
                <?php $bElementorActive = get_option( 'plcarticle_elementor_active', false ); ?>
                <input name="plcarticle_elementor_active" type="checkbox" <?=($bElementorActive == 1)?'checked':''?> class="plc-settings-value" />
                <span class="plc-settings-slider"></span>
            </label>
            <span>Enable Elementor Integration</span>
        </div>
        <!--Enable Elementor Support -->

        <!-- Enable Shortcodes Support -->
        <div class="plc-admin-settings-field">
            <label class="plc-settings-switch">
                <?php $bShortcodesActive = get_option( 'plcarticle_shortcodes_active', false ); ?>
                <input name="plcarticle_shortcodes_active" type="checkbox" <?=($bShortcodesActive == 1)?'checked':''?> class="plc-settings-value" />
                <span class="plc-settings-slider"></span>
            </label>
            <span>Enable Shortcodes Integration</span>
        </div>
        <!--Enable Shortcodes Support -->

        <!-- Enable Article Single View -->
        <div class="plc-admin-settings-field">
            <label class="plc-settings-switch">
                <?php $bSingleViewActive = get_option( 'plcarticle_singleview_active', false ); ?>
                <input name="plcarticle_singleview_active" type="checkbox" <?=($bSingleViewActive == 1)?'checked':''?> class="plc-settings-value" />
                <span class="plc-settings-slider"></span>
            </label>
            <span>Enable Article Single View</span>
        </div>
        <!-- Enable Article Single View -->

    <?php } else { ?>
        <p style="color:red;">Elementor Version <?=ELEMENTOR_VERSION?> found. <br><i>Please Update to at least 2.7.0</i></p>
    <?php } ?>
    <!-- Check Elementor Support -->
    <!-- Save Button -->
    <hr/>
    <button class="plc-admin-settings-save plc-admin-btn plc-admin-btn-primary" plc-admin-page="page-general">Save General Settings</button>
    <!-- Save Button -->
</article>