<article class="plc-admin-page-elementor plc-admin-page" style="padding: 10px 40px 40px 40px;">
    <h1><?=__('Elementor Settings','wp-plc-article')?></h1>
    <p>Here you find the elementor settings for the article</p>

    <h3>Elementor Widgets</h3>
    <!-- Enable Article Slider -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bElementorArticleSliderActive = get_option( 'plcarticle_elementor_article_slider_active', false ); ?>
            <input name="plcarticle_elementor_article_slider_active" type="checkbox" <?=($bElementorArticleSliderActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span>Enable Article Slider</span>
    </div>
    <!--Enable Article Slider -->

    <!-- Enable Article Search -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bElementorArticleSearchActive = get_option( 'plcarticle_elementor_article_search_active', false ); ?>
            <input name="plcarticle_elementor_article_search_active" type="checkbox" <?=($bElementorArticleSearchActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span>Enable Article Search</span>
    </div>
    <!--Enable Article Search -->

    <hr/>
    <button class="plc-admin-settings-save plc-admin-btn plc-admin-btn-primary" plc-admin-page="page-elementor">Save Elementor Settings</button>
    <!-- Save Button -->
</article>