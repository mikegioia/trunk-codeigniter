<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

//----------------------------------------------------------------------------- 
// Shortcuts and utility
//
function config( $key )
{
    $CI =& get_instance();

    return $CI->config->item( $key );    
}

function now()
{
    return date( DATE_MYSQL );
}

function mysql_date( $date = FALSE )
{
    if ( $date === FALSE )
    {
        return date( DATE_MYSQL, now() );
    }
    elseif ( is_int( $date ) )
    {
        return date( DATE_MYSQL, $date );
    }
    else
    {
        return date( DATE_MYSQL, strtotime( $date ) );
    }
}

function map( $object, $index, $default = FALSE, $check_index_exists = FALSE )
{
    if ( is_array( $object ) )
    {
        if ( isset( $object[ $index ] ) )
        {
            return ( $check_index_exists )
                ? TRUE
                : $object[ $index ];
        }
    }
    else
    {
        if ( isset( $object->$index ) )
        {
            return ( $check_index_exists )
                ? TRUE
                : $object->$index;
        }
    }

    return $default;
}

function asset_url( $path )
{
    return site_url( 'public/'. $path );
}

//----------------------------------------------------------------------------- 
// Strings and numbers
//
function clean( $string, $nl2br = FALSE )
{
    if ( $nl2br )
    {
        return nl2br(
            htmlentities( $string, ENT_COMPAT, 'UTF-8' ));
    }
    
    return htmlentities( $string, ENT_COMPAT, 'UTF-8' );
}

function clean_line_breaks( $html )
{
    // convert all new lines to line breaks (nl2br) then remove any line breaks in excess
    // of two.
    //
    $html = preg_replace( '/<br[^>]*>+/', "\n", $html );
    $html = preg_replace( '/(?:(?:\r\n|\r|\n)\s*){2}/s', "\n\n", $html );
    $html = trim( $html );
    $html = nl2br( $html );
    
    return $html;
}

function format_mobile( $mobile_number = "" )
{
    if ( strlen( $mobile_number ) == 10 )
    {
        return substr( $mobile_number, 0, 3 ) ."-". 
            substr( $mobile_number, 3, 3 ) ."-". 
            substr( $mobile_number, 6, 4 );
    }
    elseif ( strlen( $mobile_number ) == 11 )
    {
        return "+". substr( $mobile_number, 0, 1 ) ." ". 
            substr( $mobile_number, 1, 3 ) ."-". 
            substr( $mobile_number, 4, 3 ) ."-". 
            substr( $mobile_number, 7, 4 );
    }
    else
    {
        return $mobile_number;
    }
}

function valid( $mixed, $expected_type = INT )
{
    if ( is_numeric( $mixed ) || $expected_type === INT )
    {
        return is_numeric( $mixed )
            && strlen( $mixed )
            && intval( $mixed ) > 0;
    }
    elseif ( is_string( $mixed ) || $expected_type === STRING )
    {
        return is_string( $mixed )
            && strlen( $mixed ) > 0;
    }

    return FALSE;
}

function to_string( $mixed )
{
    if ( is_string( $mixed ) )
    {
        return $mixed;
    }
    elseif ( is_array( $mixed ) )
    {
        trim_array( $mixed );
        return implode( ", ", $mixed );
    }
    elseif ( is_numeric( $mixed ) )
    {
        return (string) $mixed;
    }
    elseif ( is_object( $mixed ) )
    {
        return serialize( $mixed );
    }
    else
    {
        return serialize( $mixed );
    }
}

function get_percent( $count, $total )
{
    $count = intval( $count );
    $total = intval( $total );

    if ( $count <= 0 || $total <= 0 )
    {
        return 0;
    }

    return round( $count / $total * 100, 0 );
}

function ordinal( $num )
{ 
    if ( ! in_array( ( $num % 100 ), array( 11, 12, 13 ) ) )
    {
        switch ( $num % 10 ) 
        {
            case 1:  
                return $num .'st';
            case 2:  
                return $num .'nd';
            case 3:  
                return $num .'rd';
        }
    }

    return $num .'th';
}

//----------------------------------------------------------------------------- 
// Comparisons
//
function int_eq( $arg_1, $arg_2 )
{
    // two integers are equal
    //
    return intval( $arg_1 ) === intval( $arg_2 );
}

function str_eq( $arg_1, $arg_2 )
{
    // two strings are equal
    //
    return "". $arg_1 === "". $arg_2;
}