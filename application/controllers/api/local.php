<?php    if (!defined('BASEPATH')) exit('No direct script access allowed');

class Local extends App_Controller {
    
    function Local()
    {
        parent::__construct();

        $this->load->model( 'scheduled_task_model' );
        $this->load->model( 'local_api_model' );
        $this->load->model( 'user_mongo_model' );
        $this->load->model( 'tweet_mongo_model' );
        $this->load->model( 'stats_mongo_model' );
        $this->load->model( 'group_model' );
        $this->load->library( 'twitter' );

        // if the request isn't from command line, throw them a 401
        //
        if ( ! $this->input->is_cli_request() )
        {
            header( 'HTTP/1.0 401 Unauthorized' );
            
            $this->_quit( FALSE, $error = "You are not authorized to make requests" );
        }

        error_reporting( E_ALL );
        ini_set( 'display_errors', 1 );
    }

    function index()
    {
        header( 'HTTP/1.0 404 Not Found' );
            
        $this->_quit( FALSE, $error = "Page not found" );
    }

    /**
     * Called every minute via CodeIgniter CLI from the cron. This heartbeat is responsible
     * for checking the scheduled tasks list for what to execute. Anything that is currently
     * scheduled will be forked, set to active, ran, and the process itself will then set
     * its status back to sleeping.
     */
    function heartbeat( $task_name = "" )
    {
        // set the time limit to 3 minutes to compensate for long processes
        //
        set_time_limit( 180 );

        // fetch the tasks that are ready to go and non-active. if a task was
        // specified, use that instead.
        //
        if ( $task_name )
        {
            $task = $this->scheduled_task_model->get_by_name( $task_name );
            $tasks = ( $task )
                ? array( $task )
                : array();
        }
        else
        {
            $tasks = $this->scheduled_task_model->get_active();
        }

        // for each one, run the appropriate task
        //
        foreach ( $tasks as $task )
        {
            $this->scheduled_task_model->start_task( $task );
            $success = FALSE;

            switch ( $task->name )
            {
                
            }

            $status = ( $success )
                ? 'success'
                : 'error';

            $this->scheduled_task_model->complete_task( 
                $task, $status, "", $task->state );
        }
    }
    
    function _quit( $return = FALSE, $error = "", $success = "", $send = NULL )
    {
        if ( $return )
        {
            $data = array(
                'status' => "success",
                'message' => $success );
        }
        else
        {
            $data = array(
                'status' => "error",
                'message' => $error );
        }
        
        if ( $send !== NULL && count( $send ) )
        {
            $data = array_merge( $data, $send );
        }
        
        echo json_encode( $data );
        exit;
    }

}
