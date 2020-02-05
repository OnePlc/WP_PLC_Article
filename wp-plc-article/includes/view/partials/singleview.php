<article class="plc-admin-page-singleview plc-admin-page" style="padding: 10px 40px 40px 40px;">
    <h1><?=__('Article Single View','wp-plc-article')?></h1>
    <p>Here you find the Article Single View settings</p>

    <h3>Basic Settings</h3>

    <!-- Single View Slug -->
    <div class="plc-admin-settings-field">
        <input type="text" class="plc-settings-value" name="plcarticle_singleview_slug" value="<?=(!empty(get_option('plcarticle_singleview_slug'))) ? get_option('plcarticle_singleview_slug') : 'article'?>" />
        <span>Single View Slug</span>
    </div>
    <!-- Single View Slug -->

    <!-- Enable Article Single View Rewrite -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bSingleViewRewriteActive = get_option( 'plcarticle_singleview_rewrite_active', false ); ?>
            <input name="plcarticle_singleview_rewrite_active" type="checkbox" <?=($bSingleViewRewriteActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span>Enable Article Single View Slug Rewrite</span>
    </div>
    <!-- Enable Article Single View Rewrite -->

    <!-- Article Single View Base Page -->
    <div class="plc-admin-settings-field">
        <select name="plcarticle_singleview_base_page" class="plc-settings-value">
            <option selected="selected" disabled="disabled" value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option>
            <?php
            $selected_page = get_option( 'plcarticle_singleview_base_page' );
            $pages = get_pages();
            foreach ( $pages as $page ) {
                $option = '<option value="' . $page->ID . '" ';
                $option .= ( $page->ID == $selected_page ) ? 'selected="selected"' : '';
                $option .= '>';
                $option .= $page->post_title;
                $option .= '</option>';
                echo $option;
            }
            ?>
        </select>
        <span>Article Single View Base Page</span>
    </div>
    <!-- Article Single View Base Page -->

    <hr/>
    <button class="plc-admin-settings-save plc-admin-btn plc-admin-btn-primary" plc-admin-page="page-singleview">Save Single View Settings</button>
    <!-- Save Button -->
</article>