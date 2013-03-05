<?php    if (!defined('BASEPATH')) exit('No direct script access allowed');

class Scheduled_task_model extends CI_Model {

    function Scheduled_task_model()
    {
        parent::__construct();
    }
    
    // retrieve all active scheduled tasks. Tasks set to active state and have 
    // not been run in the frequency seconds of time.
    //
    function get_active()
    {
        $time = date( "G:i:s" );

        $this->db->select( "*" );
        $this->db->from( "scheduled_tasks as st" );
        $this->db->where( "date_add( st.last_run, interval st.frequency second ) <= '". mysql_date() ."'" );
        $this->db->where( "st.state", "active" );
        $this->db->where( "st.start_time <= '{$time}' && st.end_time >= '{$time}'" );
        
        return $this->db->get()->result();
    }

    function get_by_name( $name )
    {
        $this->db->select( "*" );
        $this->db->from( "scheduled_tasks as st" );
        $this->db->where( "st.name", $name );

        return $this->db->get()->row();
    }

    // fetch all tasks that are running for too long (i.e. 10 minutes or more)
    //
    function get_zombies()
    {
        $time = mysql_date();

        $this->db->select( "*" );
        $this->db->from( "scheduled_tasks as st" );
        $this->db->where( "st.state = 'running'" );
        //$this->db->where( "st.name <> 'health_check'" );
        $this->db->where( "st.run_start_time is not null" );
        $this->db->where( "( date_add( st.run_start_time, interval 10 minute ) < '{$time}' )" );

        return $this->db->get()->result();
    }

    function get_all()
    {
        $this->db->select( "*" );
        $this->db->from( "scheduled_tasks as st" );
        
        return $this->db->get()->result();
    }

    // set task to be 'running'
    //
    function start_task( $task )
    {
        $data = array(
            'state' => 'running',
            'run_start_time' => mysql_date());

        return $this->save( $data, $task->id );
    }

    // update task run time and set the return info
    //
    function complete_task( $task, $status = 'success', $message = '', $state = 'active' )
    {
        $data = array(
            'return_status' => $status,
            'return_message' => $message,
            'last_run' => mysql_date(),
            'state' => $state );

        return $this->save( $data, $task->id );
    }

    function deactivate_all()
    {
        return $this->db->update( 
            'scheduled_tasks', 
            array( 
                'state' => 'inactive' ), 
            array( 
                'state' => 'active' ));
    }

    // save a scheduled task
    //
    function save( $data, $id = NULL )
    {
        // if there's an id, perform an update, if not we'll do an insert
        //
        if ( $id )
        {
            return $this->db->update( 
                'scheduled_tasks', 
                $data, 
                array( 
                    'id' => $id 
                ));
        }
        else
        {
            return $this->db->insert( 'scheduled_tasks', $data );
        }
    }

}