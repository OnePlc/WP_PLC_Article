jQuery(function() {
    var plcmultiselectItemView = elementor.modules.controls.BaseData.extend({
        onReady: function () {
            //console.log(this.ui);
            this.ui.select.select2();

            var origSelect = this.ui.select;
            var select2Select = this.ui.select.parent().find("ul.select2-selection__rendered");

            var oSetter = this;

            select2Select.sortable({
                attribute: 'data-select2-id',
                containment: 'parent',
                update: function() {
                    /**
                    var sortedIDs = select2Select.sortable( "toArray" );
                    console.log(sortedIDs);
                    oSetter.setValue(['model_idfs']);
                     **/
                    var postData = select2Select.sortable('toArray', {
                        attribute: 'data-select2-id',//this will look up this attribute
                        key: 'order',//this manually sets the key
                        expression: /(.+)/ //expression is a RegExp allowing to determine how to split the data in key-value. In your case it's just the value
                    });
                    var aFinalVals = [];
                    for(var i = 0, l = postData.length; i < l; i++ ) {
                        var iSelect2ID =  parseInt(postData[i]);
                        var mySelect2LI = select2Select.find('li.select2-selection__choice[data-select2-id="'+iSelect2ID+'"]');
                        var myVal = mySelect2LI.attr('title');
                        if(iSelect2ID > 0) {
                            var myOpt = false;
                            origSelect.find('option').each(function(e) {
                                if(jQuery(this).html() == myVal) {
                                    myOpt = jQuery(this);
                                }
                            });
                            if(myOpt[0]) {
                                var myVal = myOpt[0].value;
                                aFinalVals.push(myVal);
                                console.log('val:'+myVal);
                            }
                            //console.log(myOpt);
                        }
                        //var iSelect2ID = parseInt(jQuery(this).attr('data-select2-id'))-1;
                        //
                    }
                    console.log('set net val:');
                    console.log(aFinalVals);
                    oSetter.setValue(aFinalVals);
                }
            });

            console.log('done');
            //this.ui.textarea.emojioneArea(options);
        },

        saveValue: function () {
            //this.setValue(this.ui.textarea[0].emojioneArea.getText());
        },

        onBeforeDestroy: function () {
            this.saveValue();
            //this.ui.textarea[0].emojioneArea.off();
        }
    });

    elementor.addControlView('plc-multi-sortable', plcmultiselectItemView);
});

function updateSelect2IDs(select2Select,origSelect) {
    select2Select.find('li.select2-selection__choice').each(function(e) {
        console.log('update item');

        if(myOpt[0]) {
            var myVal = myOpt[0].value;
            jQuery(this).attr('id', 'plc-sortsel-' + myVal);
        }
    });
}