<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Util {

    public $messages;
    public $redirect;

    function Util()
    {
        $this->messages = array();
        $this->redirect = NULL;
    }
    
    function error( $message = "", $overwrite = FALSE )
    {
        $this->message( 'error', $message, $overwrite );
    }
    
    function success( $message = "", $overwrite = FALSE )
    {
        $this->message( 'success', $message, $overwrite );
    }
    
    function info( $message = "", $overwrite = FALSE )
    {
        $this->message( 'info', $message, $overwrite );
    }
    
    function message( $type = 'error', $message = '', $overwrite = FALSE )
    {
        if ( ! isset( $this->messages[ $type ] ) || $overwrite == TRUE )
        {
            $this->messages[ $type ] = array();
        }
        
        if ( ! is_array( $message ) )
        {
            $message = array( $message );
        }

        foreach ( $message as $m )
        {
            $this->messages[ $type ][] = $m;
        }
    }

    function redirect( $url )
    {
        $this->redirect = $url;
    }

    function get_messages()
    {
        if ( is_array( $this->messages ) )
        {
            return $this->messages;
        }
        else
        {
            return array();
        }
    }

}