<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utility 
{
    function load_config()
    {
        $CI =& get_instance();
        $CI->load->config( 'config.local' );
    }

    function fix_output()
    {
        $CI =& get_instance();
        $buffer = $CI->output->get_output();
        $log = $CI->util->print_log();
        $buffer = str_replace( "{admin_log}", $log, $buffer );
        
        if ( $CI->config->item( 'output_compression' ) )
        {
            ini_set( "pcre.recursion_limit", "16777" );  // 8MB stack. *nix

            $buffer = $this->process_data_jmr1( $buffer );
        }
        
        $CI->output->set_output( $buffer );
        $CI->output->_display();  
    }

    function process_data_jmr1( $text )
    {
        $re = '%# Collapse whitespace everywhere but in blacklisted elements.
            (?>             # Match all whitespans other than single space.
              [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
            | \s{2,}        # or two or more consecutive-any-whitespace.
            ) # Note: The remaining regex consumes no text at all...
            (?=             # Ensure we are not in a blacklist tag.
              [^<]*+        # Either zero or more non-"<" {normal*}
              (?:           # Begin {(special normal*)*} construct
                <           # or a < starting a non-blacklist tag.
                (?!/?(?:textarea|pre|script)\b)
                [^<]*+      # more non-"<" {normal*}
              )*+           # Finish "unrolling-the-loop"
              (?:           # Begin alternation group.
                <           # Either a blacklist start tag.
                (?>textarea|pre|script)\b
              | \z          # or end of file.
              )             # End alternation group.
            )  # If we made it here, we are not in a blacklist tag.
            %Six';

        $text = preg_replace( $re, " ", $text );
        
        if ( $text === NULL ) 
        {
            exit( "PCRE Error! File too big.\n" );
        }

        return $text;
    }
}
