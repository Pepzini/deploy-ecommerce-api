<?php

class Logger {

    private $db;
    private $ss;

    /*constructor*/
	function __construct() {
        require_once 'dbHandler.php';
        require_once 'sessionHandlr.php';
        $this->db = new DbHandler();
        $this->ss = new SessionHandlr();
    }

	/**
	* function logs an admin action
	* @return true or error
	*/
    public function logAction($session_type, $action, $log_type="user") {
        // get session
        $session = $this->ss->getSession($session_type);
        if($session[$session_type]) {
            // insert into db
            $log_id = $this->db->insertToTable(
                [ $session[$session_type]['user_fullname'], $session[$session_type]['user_id'], $action, date("Y-m-d H:i:s") ], /*values - array*/
                [ 'log_actor_name', 'log_actor_id', 'log_action','log_time' ], /*column names - array*/
                "user_log" /*table name - string*/
            );

            if($log_id) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function logManagerAction($session_type, $action, $log_type="manager") {
        // get session
        $session = $this->ss->getSession($session_type);
        if($session[$session_type]) {
            // insert into db
            $log_id = $this->db->insertToTable(
                [ $session[$session_type]['sm_name'], $session[$session_type]['sm_id'], $action, date("Y-m-d H:i:s") ], /*values - array*/
                [ 'log_actor_name', 'log_actor_id', 'log_action','log_time' ], /*column names - array*/
                "service_manager_log" /*table name - string*/
            );

            if($log_id) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function logToFile($type, $line) {
        $line = date("Y-m-d H:i:s") . ' : ' . $line;
        return file_put_contents('logs/' . $type . '.txt', $line.PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}