<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo ( isset( $html_title ) ) ? $html_title .' | ' : ''; ?><?php echo config( 'html_title' ); ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">

        <!-- Plugins go first -->
        <link rel="stylesheet" href="<?php echo site_url( 'public/css/trunk.css' ); ?>">
        <link rel="stylesheet" href="<?php echo site_url( 'public/css/font-awesome.min.css' ); ?>">
        <link rel="stylesheet" href="<?php echo site_url( 'public/css/prettify.css' ); ?>">

        <!-- App overwrites -->
        <link rel="stylesheet" href="<?php echo site_url( 'public/css/skeleton.css' ); ?>">
        <link rel="stylesheet" href="<?php echo site_url( 'public/css/app.css' ); ?>">

        <script src="<?php echo site_url( 'public/js/app.js' ); ?>"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- Container -->
        <div id="container" class="clearfix block">

            <!-- Header -->
            <div id="header" class="margin-down-20">
                <div class="liner">
                    <div class="name"><?php echo config( 'html_title' ); ?></div>
                    <div class="buttons">
                        
                    </div>
                    <div class="tagline"></div>
                </div>
            </div><!-- /header -->

            <!-- Content -->
            <div id="content" class="clearfix block">
                <div id="page" class="round-3 <?php echo ( isset( $page_class ) ) ? $page_class : ''; ?>">
                    <?php echo ( isset( $body ) ) ? $body : ''; ?>
                </div><!-- /page -->
            </div><!-- /content -->

            <!-- Footer -->
            <div id="footer">
                <p>
                    Footer
                </p>
            </div><!-- /footer -->
        
        </div><!-- /container -->

        <!-- Load Trunk -->
        <script>
            App.extend({
                rootPath: '<?php echo site_url( "" ); ?>',
                jsPath: '<?php echo site_url( "public/js" ); ?>/',
                env: '<?php echo config( "js_environment" ); ?>',
                pages: [ 
                    'home.js' ],
                libraries: [ 
                    'rainbow-custom.min.js' ],
                version: '<?php echo config( "asset_version" ); ?>'
            });
            
            App.init( function() {
                HomePage.load();
<?php   if ( isset( $js_pages ) && count( $js_pages ) ):
            foreach ( (array) $js_pages as $js_page ): ?>
                <?php echo $js_page; ?>.load();
<?php       endforeach;
        endif; 
        if ( isset( $tour ) && strlen( $tour ) ): ?>

<?php   endif;

        // check status messages
        //
        $statuses = array( ERROR, SUCCESS, INFO );
        $messages = array(); 
        foreach ( $statuses as $status ):
            $status_messages = $this->session->flashdata( $status );
            if ( is_array( $status_messages ) ):
                foreach ( $status_messages as $message ): ?>
            App.Message.notify( '<?php echo urlencode( $message ); ?>', App.Const.status_<?php echo $status; ?>, true );
<?php            endforeach;
            elseif ( $status_messages ): ?> 
            App.Message.notify( '<?php echo urlencode( $status_messages ); ?>', App.Const.status_<?php echo $status; ?>, true );
<?php        endif;
        endforeach; ?>
            });
        </script>
        
<?php   if ( ENVIRONMENT === 'production' && strlen( config( 'google_analytics_key' ) ) > 0 ): ?>
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '<?php echo config( "google_analytics_key" ); ?>']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
<?php   endif; ?>

    </body>
</html>