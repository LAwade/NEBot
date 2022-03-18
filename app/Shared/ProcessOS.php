<?php

    namespace App\Shared;

    /**
     * Class Process to Execute Commands Linux and Find Process
     * 
     * @author: Lucas Awade
     * @version: 1.0.0
     * 
     */
    class ProcessOS {

        private $path;
        private $command;
        private $process;
        private $found = array();

        ########################################################################
        ########                     FUNCTIONS PUBLIC                   ########
        ########################################################################

        /**
         * Search by columns
         * method shell awk
         */
        public function columns($args) {
            $columns = func_get_args($args);
            if ($columns) {
                $find = "";
                foreach ($columns as $key => $value) {
                    if ($key) {
                        $find .= " \"|\" $$value";
                    } else {
                        $find .= "$$value";
                    }
                }
                $this->command = " | awk {'print $find, \"\"'}";
            }
        }

        /**
         * Get to command for execute in function -> exec()
         * @return string
         */
        public function find() {
            return $this->process($this->findProcess());
        }

        /**
         * Show results founded
         * @return array
         */
        public function found() {
            return $this->found;
        }

        public function command() {
            return "ps aux | grep {$this->process} | grep -v grep " . $this->command;
        }

        /**
         * Execute programs|scripts
         * @param string $args
         * @return boolean
         */
        public function execute($args) {
            if ($args) {
                $args = func_get_args($args);
            }
            if ($this->path) {
                shell_exec($this->path . $this->process . " " . ($args ? implode(" ", $args) : '') . " > /dev/null &");
                return true;
            } else {
                return false;
            }
        }

        /**
         * Killer process
         */
        public function kill($pid) {
            shell_exec("kill -9 " . trim($pid));
        }

        /**
         * Killer process
         */
        public function killAll() {
            shell_exec("killall -9 " . $this->process);
        }

        /**
         * Patch to execute program|script
         * @param string $path
         */
        public function path($path) {
            $this->path = $path;
        }

        /**
         * Define the process
         * @param string $process
         */
        public function task($process) {
            $this->process = $process;
        }

        ########################################################################
        ########                   FUNCTIONS IMPORTANT                  ########
        ########################################################################

        /**
         * Execute command in shell
         * @return string $process
         */
        private function findProcess() {
            return shell_exec($this->command());
        }

        /**
         * Shearch coluns 
         * @param string $process
         * @return array|string
         */
        private function process($process) {
            if ($this->command) {
                $this->found = explode(" ", $process);
                foreach ($this->found as $key => $value) {
                    if (!trim($value)) {
                        unset($this->found[$key]);
                    }
                    $string = explode('|', $value);
                    foreach ($string as $process) {
                        if (!$process || strpos('aux', $process) !== false) {
                            unset($this->found[$key]);
                        }
                    }
                }
                return $this->found;
            }
            return $this->process = $process;
        }

    }

?>