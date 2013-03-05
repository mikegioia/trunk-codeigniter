<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

    var $CI;
    var $logged_in;
    var $user;
    var $user_id;
    
    public function __construct()
    {
        $this->CI =& get_instance();
        
        $this->user_id = $this->CI->session->userdata( 'user_id' );
        $this->logged_in = $this->CI->session->userdata( 'logged_in' );

        // check and load databases
        //
        if ( config( 'load_mysql' ) )
        {
            $this->load->library( 'database' );
            $this->load->model( 'user_model' );
        }

        if ( config( 'load_mongo' ) )
        {
            $this->load->library( 'mongo_db' );
        }

        log_message( 'debug', "Auth Class Initialized" );
    }

    public function initialize( $user = NULL )
    {
        if ( ! config( 'load_mysql' ) )
        {
            return TRUE;
        }

        $this->user = ( $user )
            ? $user
            : $this->CI->user_model->get_by_id( $this->user_id );

        return TRUE;
    }

    public function validate_login( $username, $password )
    {
        if ( ! config( 'load_mysql' ) )
        {
            return FALSE;
        }

        $user = $this->user_mongo_model->get_by_username( $username );

        if ( ! $user )
        {
            return FALSE;
        }

        $full_salt = substr( $user->password, 0, 29 );
        $crypt_password = crypt( $password, $full_salt );

        return str_eq( $user->password, $crypt_password );
    }

    public function register( $email, $password, $username = "" )
    {
        if ( ! config( 'load_mysql' ) )
        {
            return FALSE;
        }

        if ( ! valid( $email, STRING ) || ! valid( $password, STRING ) )
        {
            return FALSE;
        }

        $salt = substr( sha1( $email ), 0, 22 );
        $crypt_password = crypt( $password, '$2a$10$' . $salt );
        
        return $this->CI->user_model->save(
            array(
                'username' => $username,
                'email' => $email,
                'password' => $crypt_password,
                'salt' => $salt,
                'created_on' => $this->CI->mongo_db->date(),
                'roles' => array( ROLE_READER )
            ));
    }
    
    public function authorize()
    {
        $this->CI->session->set_userdata( 'logged_in', TRUE );
        $this->CI->session->set_userdata( 'user_id', $this->user_id );
        $this->logged_in = TRUE;
        
        return TRUE;
    }
    
    public function deauthorize()
    {
        $this->CI->session->set_userdata( 'logged_in', FALSE );
        $this->CI->session->set_userdata( 'user_id', NULL );
        $this->logged_in = FALSE;

        $this->CI->session->sess_destroy();
        
        return TRUE;
    }
    
    public function authorized()
    {
        return $this->logged_in === TRUE && valid( $this->user_id );
    }
    
    public function get_first_name( $name = NULL )
    {
        if ( ! $name )
        {
            $name = $this->user->name;
        }

        $pieces = explode( " ", $name );

        if ( count( $pieces ) <= 1 )
        {
            return $name;
        }

        return $pieces[ 0 ];
    }
    
}

// END Auth class

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */