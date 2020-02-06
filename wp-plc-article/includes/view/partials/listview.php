<article class="plc-admin-page-listview plc-admin-page" style="padding: 10px 40px 40px 40px;">
    <h1><?=__('Article List View','wp-plc-article')?></h1>
    <p>Here you find the Article List View settings</p>

    <h3>Basic Settings</h3>

    <!-- List View Slug -->
    <div class="plc-admin-settings-field">
        <input type="text" class="plc-settings-value" name="plcarticle_listview_slug" value="<?=(!empty(get_option('plcarticle_listview_slug'))) ? get_option('plcarticle_listview_slug') : 'article'?>" />
        <span>List View Slug</span>
    </div>
    <!-- List View Slug -->

    <!-- Enable Article List View Rewrite -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bListViewRewriteActive = get_option( 'plcarticle_listview_rewrite_active', false ); ?>
            <input name="plcarticle_listview_rewrite_active" type="checkbox" <?=($bListViewRewriteActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span>Enable Article List View Slug Rewrite</span>
    </div>
    <!-- Enable Article List View Rewrite -->

    <!-- Article List View Base Page -->
    <div class="plc-admin-settings-field">
        <select name="plcarticle_listview_base_page" class="plc-settings-value">
            <option selected="selected" disabled="disabled" value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option>
            <?php
            $selected_page = get_option( 'plcarticle_listview_base_page' );
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
        <span>Article List View Base Page</span>
    </div>
    <!-- Article List View Base Page -->

    <hr/>
    <button class="plc-admin-settings-save plc-admin-btn plc-admin-btn-primary" plc-admin-page="page-listview">Save List View Settings</button>
    <!-- Save Button -->
</article>