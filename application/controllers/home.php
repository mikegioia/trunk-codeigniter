<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends App_Controller {

    function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $this->html[] = 'home';
        $this->data[ 'page_class' ] = 'home';

        return $this->render();
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */