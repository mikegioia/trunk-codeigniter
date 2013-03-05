<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends Task_Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        show_404();
    }

    public function mongoindex()
    {
        // example mongo index
        //
        // $this->mongo_db->add_index( 'users', array( 'username' => 1 ) );

        $this->message = "Successfully added all indexes";
        $this->redirect = site_url();

        return $this->render();
    }

}

/* End of file tasks.php */
/* Location: ./application/controllers/tasks.php */