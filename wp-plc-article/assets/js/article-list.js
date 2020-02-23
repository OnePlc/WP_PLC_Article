function WPPLCLOADLIST(oTargetEl,oSettings,iPage) {
    jQuery.post(plcArticleList.ajaxUrl,{action:'wpplc_article_listview',page_id:iPage,widget_settings:oSettings},function(retHTML) {
        oTargetEl.html(retHTML);
    });
}