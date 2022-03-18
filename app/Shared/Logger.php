<?php

namespace App\Shared;

class Logger {
    /*
     * GENERATE LOG
     */
    private $active;
    private $log;
    private $type;
    private $text;

    /*
     * CONF. FILE LOG
     */
    private $file;
    private $path;
    private $name;

    /*
     * CONST. MESSAGE TYPE
     */
    const LOG_SUCCESS = "SUCCESS";
    const LOG_DEBUG = "DEBUG";
    const LOG_INFO = "INFO";
    const LOG_WARNING = "WARNING";
    const LOG_ERROR = "ERROR";

    public function __construct($nameLog, $path, $active = true) {
        $this->name = $nameLog;
        $this->path = $path;
        $this->active = $active;
        $this->config($nameLog);
    }

    ########################################################################
    ##                                TYPES                               ##
    ########################################################################

    public function success($log, $debug_trace = null) {
        $this->type = self::LOG_SUCCESS;
        $this->text = $log;
        $this->header($log, $debug_trace ? $debug_trace : debug_backtrace());
        $this->write();
    }

    public function debug($log, $debug_trace = null) {
        $this->type = self::LOG_DEBUG;
        $this->text = $log;
        $this->header($log, $debug_trace ? $debug_trace : $this->name);
        $this->write();
    }

    public function info($log, $debug_trace = null) {
        $this->type = self::LOG_INFO;
        $this->text = $log;
        $this->header($log, $debug_trace ? $debug_trace : $this->name);
        $this->write();
    }

    public function error($log, $debug_trace = null) {
        $this->type = self::LOG_ERROR;
        $this->text = $log;
        $this->header($log, $debug_trace ? $debug_trace : $this->name);
        $this->write();
    }

    public function warning($log, $debug_trace = null) {
        $this->type = self::LOG_WARNING;
        $this->text = $log;
        $this->header($log, $debug_trace ? $debug_trace : $this->name);
        $this->write();
    }

    ########################################################################
    ##                              IMPORTANT                             ##
    ########################################################################

    private function write() {
        if ($this->active) {
            file_put_contents($this->file, $this->log, FILE_APPEND);
        }
    }

    private function header($log, $debug_trace) {
        $x = 0;
        $this->log = "________________________________________________________________________________________\n";
        if(is_array($debug_trace)){
            $method = $debug_trace[0]['class'] ? "{$debug_trace[0]['class']}::{$debug_trace[0]['function']}" : $debug_trace[0]['function'];
            $args = null;
            
            if(count($debug_trace[0]['args']) > 0){
                foreach($debug_trace[0]['args'] as $key => $arg){
                    $x++;
                    $args .= $arg;
                    if(count($debug_trace[0]['args']) != $x){
                        $args .= ",";
                    }
                }
            }
            $this->log .= sprintf("\n[ %s ][ LINE %s ][ %s ][ ARGS ($args) ][ %s ]\n\n", date('d/m/Y H:i:s'), $debug_trace[0]['line'], $method, $this->type);
        } else {
            $this->log .= sprintf("\n[ %s ][ %s ][ %s ]\n\n", date('d/m/Y H:i:s'), $debug_trace, $this->type);
        }
        $this->log .= "> " . $log;
        $this->log .= "\n\n ---------------------------------- [ FINISH LOGGER ] ----------------------------------\n\n";
    }

    public function locateLog() {
        echo "\n\n {$this->file} \n\n";
    }

    ########################################################################
    ##                               CONFIGS                              ##
    ########################################################################

    public function config($name, $exten = ".log", $prefix = null) {
        $contents = null;
        if (file_exists($this->file)) {
            $contents = file_get_contents($this->file);
        }

        $this->file = trim($this->path . $prefix . ($name ? $name : $this->name) . $exten);
        file_put_contents($this->file, $contents ? $contents : "", FILE_APPEND);
    }

    public function getType() {
        return $this->type;
    }
    
    public function getText(){
        return $this->text;
    }

    public function setLogger($active) {
        if ($this->active === true) {
            $this->active = $active;
        } else if ($active === false) {
            $this->active = $active;
        } else {
            $this->active = true;
        }
    }
}
