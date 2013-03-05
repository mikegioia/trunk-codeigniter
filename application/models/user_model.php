<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model_Mongo {

    function __construct()
    {
        parent::__construct();
    }

    function get_by_id( $user_id )
    {
        if ( ! valid( $user_id ) )
        {
            return NULL;
        }

        return $this->db
            ->limit( 1 )
            ->get_where(
                'users',
                array(
                    'id' => $user_id
                ))
            ->row();
    }

    function get_by_username( $username )
    {
        if ( ! valid( $username, STRING ) )
        {
            return NULL;
        }

        return $this->db
            ->limit( 1 )
            ->get_where(
                'users',
                array(
                    'username' => $username
                ))
            ->row();
    }

    function save( $user = NULL, $id = 0 )
    {
        if ( ! $user )
        {
            return FALSE;
        }

        if ( valid( $id ) && $id > 0 )
        {
            $success = $this->db
                ->where( 'id', $id )
                ->update( 'users', $user );
        }
        else
        {
            $success = $this->db
                ->insert( 'users', $user );
            $id = $this->db->insert_id();
        }

        return $success
            && $this->get_by_id( $id );
    }

}