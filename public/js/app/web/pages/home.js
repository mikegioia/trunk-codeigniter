/**
 * Home page
 */

var HomePage = new PageClass;
HomePage.extend({

    // set up page event handlers
    //
    load: function() {
        this.base();

        // defer DOM handling until after callstack is finished
        //
        var self = this;
        _.defer( function() {
            self.references();
        });
        
        App.Log.debug( 'HomePage load()', 'sys' );
    },

    // set up reference links for <sup> tags
    //
    references: function() {
        $( 'sup' ).each( function ( i ) {
            var $this = $( this ),
                number = $this.text();
            var $a = jQuery( '<a>', {
                href: "javascript:;",
                rel: "footnote",
                text: number
            });
            var $target = $( '#appendix' )
                .find( 'li' )
                .eq( parseInt( number ) - 1 );

            $a.data( 'id', number );
            $a.on( 'click', function () {
                App.Page.scrollTo(
                    $target, {
                        highlightCount: 2,
                        elementToHighlight: $target,
                        topPadding: -40,
                        scrollTime: 500
                    });
            });

            $this.html( '' ).append( $a );
        });
    }
    
});




// End of file
