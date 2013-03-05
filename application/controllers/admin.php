<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends App_Controller {

    function __construct()
    {
        parent::__construct();

        if ( ! config( 'admin_enabled' ) )
        {
            show_404();
        }
    }

    public function index()
    {
        // set the page
        //
        $this->html[] = 'admin';
        $this->data[ 'page_class' ] = 'admin';
        
        return $this->render();
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */