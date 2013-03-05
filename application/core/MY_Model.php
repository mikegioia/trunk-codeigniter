<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {}

/**
 * Mongo class
 *
 * Wrapper for performing mongo actions
 *
 */
class CI_Model_Mongo extends CI_Model {

    const WHERE_IN = 'where_in';
    const WHERE_NOT_IN = 'where_not_in';
    const WHERE_GTE = 'where_gte';
    const WHERE_LTE = 'where_lte';
    const WHERE_GT = 'where_gt';
    const WHERE_LT = 'where_lt';
    const WHERE_NE = 'where_ne';

    const INT_VAL = 'int_val';
    const STRING_VAL = 'string_val';

    function CI_Model_Mongo() {}
    
    // performs an upsert given a collection, data, and any parameters
    //
    function upsert( $collection, $data, $params = array(), $options = array() )
    {
        $defaults = array(
            'upsert' => TRUE );
        $options = array_merge( $defaults, $options );

        return $this->mongo_db
            ->where( $params )
            ->set( (array) $data )
            ->update( $collection, $options );
    }

    // gets something from the specified collection
    //
    function get( $collection, $params = array() )
    {
        if ( $params )
        {
            $this->mongo_db->where( $params );
        }

        return $this->mongo_db->get( $collection );
    }

    // return list of distinct fields for a key
    //
    function get_distinct( $collection, $key, $params = array() )
    {
        if ( $params )
        {
            $this->mongo_db->where( $params );
        }

        //$this->mongo_db->where_ne( $key, '' );

        $distinct = $this->mongo_db->command(
            array(
                'distinct' => $collection,
                'key' => $key,
                'query' => $this->mongo_db->wheres ),
            TRUE );

        $ret = array();

        foreach ( $distinct[ 'values' ] as $value )
        {
            if ( $value )
            {
                $ret[] = $value;
            }
        }

        return $ret;
    }
    
    // set the limit and offset if they exist in the parameters
    //
    function set_constraints( $params )
    {
        if ( array_key_exists( 'limit', $params ) )
        {
            $this->mongo_db->limit( $params[ 'limit' ] );
        }

        if ( array_key_exists( 'offset', $params ) )
        {
            $this->mongo_db->offset( $params[ 'offset' ] );
        }
    }

    // set a where field
    //
    function set_where( $params, $key, $mongo_field = FALSE, $options = array() )
    {
        if ( ! $mongo_field )
        {
            $mongo_field = $key;
        }

        // check if key exists
        //
        if ( ! array_key_exists( $key, $params ) )
        {
            return;
        }

        // prepare value options
        //
        foreach ( $options as $option )
        {
            switch ( $option )
            {
                case self::INT_VAL:
                    if ( is_array( $params[ $key ] ) )
                    {
                        foreach ( $params[ $key ] as &$val )
                        {
                            $val = intval( $val );
                        }
                    }
                    else
                    {
                        $params[ $key ] = intval( $params[ $key ] );
                    }
                    break;
                case self::STRING_VAL:
                    if ( is_array( $params[ $key ] ) )
                    {
                        foreach ( $params[ $key ] as &$val )
                        {
                            $val = (string) $val;
                        }
                    }
                    else
                    {
                        $params[ $key ] = (string) $params[ $key ];
                    }
                    break;
            }
        }

        $options = array_diff( 
            $options, 
            array(
                self::INT_VAL,
                self::STRING_VAL
            ));

        // if there are no flags, set the where
        //
        if ( ! $options && ! count( $options ) )
        {
            $this->mongo_db->where( $mongo_field, $params[ $key ] );
        }

        // handle any flags
        //
        foreach ( $options as $option )
        {
            switch ( $option )
            {
                case self::WHERE_IN:
                    $this->mongo_db->where_in( $mongo_field, $params[ $key ] );
                    break;

                case self::WHERE_NOT_IN:
                    $this->mongo_db->where_not_in( $mongo_field, $params[ $key ] );
                    break;

                case self::WHERE_GT:
                    $this->mongo_db->where_gt( $mongo_field, $params[ $key ] );
                    break;

                case self::WHERE_GTE:
                    $this->mongo_db->where_gte( $mongo_field, $params[ $key ] );
                    break;

                case self::WHERE_LT:
                    $this->mongo_db->where_lt( $mongo_field, $params[ $key ] );
                    break;

                case self::WHERE_LTE:
                    $this->mongo_db->where_lte( $mongo_field, $params[ $key ] );
                    break;

                case self::WHERE_NE:
                    $this->mongo_db->where_ne( $mongo_field, $params[ $key ] );
                    break;
            }
        }

        return TRUE;
    }

}
