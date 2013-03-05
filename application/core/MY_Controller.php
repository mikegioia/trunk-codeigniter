<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Controller extends CI_Controller
{
    var $title;

    var $status;
    var $message;
    var $html;
    var $script;
    var $data;
    var $pager;
    var $redirect;

    var $response;
    var $ajax = FALSE;
    var $requires_textarea = FALSE;

    function __construct()  
    {
        parent::__construct();
    
        $this->ajax = ( $this->input->post( 'ajax' ) !== FALSE )
            ? $this->input->post( 'ajax' )
            : FALSE;

        $this->status = "success";
        $this->message = "";
        $this->script = "";
        $this->html = array();
        $this->data = array();

        $this->auth->initialize();

        // preload any template data
        //
        $this->load_template_data();
    }

    function quit( $type = 'error', $message = '', $location = NULL )
    {
        $this->status = $type;
        $this->message = $message;
                
        if ( $location )
        {
            $this->redirect = site_url( $location );    
        }        

        $this->render();
    }
    
    // loads a tour by key and adds it to the html template
    //
    function display_tour( $tour_key )
    {
        $this->data[ 'display_tour' ] = TRUE;
        $this->data[ 'tour_key' ] = $tour_key;

        $this->script .= <<<STR
_.defer( function() {
    App.Tour.start( '#$tour_key' );
});
STR;
        // set this property to be seen
        //
        //$this->prop->set( $tour_key, 1 );
    }

    // load any data to be prepared in the template. these variables get sent to
    // the default template view.
    //
    function load_template_data()
    {
        //$this->data[ 'example' ] = $example;
    }

    function render()
    {
        $output = array(
            'status' =>     $this->status,
            'message' =>    $this->message,
            'html' =>       $this->html,
            'script' =>     $this->script,
            'data' =>       $this->data,
            'pager' =>      $this->pager,
            'redirect' =>   $this->redirect );
        
        if ( $this->ajax )
        {
            $this->load->vars( $this->data );
            $temp = array();

            foreach ( $this->html as $key => $view )
            {
                $temp[ $key ] = $this->load->view( $view, '', TRUE );
            }

            $output[ 'html' ] = $temp;
            unset( $temp );

            if ( $this->requires_textarea )
            {
                echo "<textarea>" . json_encode( $output ) . "</textarea>";
            }
            else
            {
                echo json_encode( $output );    
            }
            
            exit;
        }
        else
        {
            $this->session->set_flashdata( 
                $this->status, $this->message );
        
            foreach ( $this->util->get_messages() as $type => $message )
            {
                $this->session->set_flashdata( $type, $message );
            }

            if ( strlen( $this->util->redirect ) )
            {
                $this->redirect = $this->util->redirect;
            }

            if ( $this->redirect )
            {
                redirect( $this->redirect );
            }
            else
            {
                $this->load->vars( $this->data );

                // compile the body html
                //
                $body = "";

                foreach ( $this->html as $key => $view )
                {
                    $body .= $this->load->view( 
                        $view,
                        array(),
                        TRUE );
                }

                $this->load->view( 
                    'template', 
                    array(
                        'body' => $body 
                    ));
            }            
        }
    }
}

class Task_Controller extends App_Controller
{

    function __construct()  
    {
        parent::__construct();

        // optional check if its a POST request
    }

}
