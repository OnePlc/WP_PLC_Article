/**
 * Close Overlay Function
 */
function closePlcArtOverlay() {
    jQuery('#myOverlay').css({display:'none'});
}

jQuery(function() {

    /**
     * Add Overlay to Body
     */
    jQuery('body').append('<div id="myOverlay" class="overlay">\n' +
        '  <span class="closebtn" onclick="closePlcArtOverlay()" title="Close Overlay">x</span>\n' +
        '  <div class="overlay-content">\n' +
        '    <form action="/gebrauchtmaschinen">\n' +
        '      <input type="text" placeholder="Maschine suchen.." name="q" class="plcArtSearchTerm">\n' +
        '      <button type="submit" class="plcPopopSearchStart"><i class="fa fa-search"></i></button>\n' +
        '    </form>\n' +
        '  </div>\n' +
        '</div>');

    /**
     * Overlay Search Listeners
     */
    jQuery('.plcArtSearchOverlay').on('click',function() {
        jQuery('#myOverlay').css({display:'block'});
        jQuery('.plcArtSearchTerm').focus();
        return false;
    });

    jQuery('.plcPopopSearchStart').on('click',function() {
        var q = jQuery('.plcArtSearchTerm').val();
        window.location = plcSettings.pageURL+'/gebrauchtmaschinen/?q='+q;
        return false;
    });
});