<?php    if (!defined('BASEPATH')) exit('No direct script access allowed');

class Local_api_model extends CI_Model {

    function Local_api_model()
    {
        parent::__construct();
    }

    // check all of the tasks and make sure none have been running for too long. 
    //
    function health_check()
    {
        $zombies = $this->scheduled_task_model->get_zombies();

        if ( $zombies )
        {
            foreach ( $zombies as $zombie )
            {
                $data = array(
                    'type' => config( 'emergency_types' )->scheduled_task,
                    'info' => "Task: ". $zombie->name,
                    'message' => "Scheduled task has been running for too long. Check the server ".
                        "and cron log for any errors. It's been moved to active for now." );

                log_message(
                    'error',
                    "Health Check Failed: ". print_r( $data, TRUE ) );

                $data = array(
                    'state' => 'active' );

                $this->scheduled_task_model->save( $data, $zombie->id );
            }
        }

        return TRUE;
    }
}