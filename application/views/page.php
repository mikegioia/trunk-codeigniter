
<div id="page" class="round-5 clearfix">

</div><!-- /#page -->


<div id="tour" style="display:none;">
<?php   if ( isset( $tour_view ) ):
            $this->load->view( 'tours/'. $tour_view );
        endif; ?>
</div><!-- /#tour -->

<?php   if ( isset( $tour_view ) ): ?>
<script>
    App.ready( function() {
        App.Tour.start( 
            $( '#tour .aj-tour' )
        );
    });
</script>
<?php   endif; ?>
