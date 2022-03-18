#!/usr/bin/php -q
<?php
    ini_set('display_errors', 0);
    error_reporting(0);
    
    include __DIR__ . '/../../vendor/autoload.php';
    require __DIR__ . '/../../config/config.php';
    require __DIR__ . '/../../config/helpers.php';

    use app\shared\ProcessOS;
    use app\models\TSBOT;

/*
     * Para rodar como um processo normal é preciso passar "N" como primeiro parametro.
     */
    $notDaemon = isset($argv[2]) && (strtoupper($argv[2]) === 'N');

    $kill = isset($argv[1]) && (strtoupper($argv[1]) === 'KILL');
    $process = new ProcessOS();
    $process->path(CONF_PATH_BOT);
    $service = array(
        array("service" => "tsbot.php", "pid" => 2, "idbot" => 14),
    );

    if ($kill == 'KILL') {
        foreach ($service as $proc) {
            $process->task($proc['service']);
            $process->columns($proc['pid'], $proc['idbot']);
            $tasks = $process->find();
            foreach ($tasks as $task) {
                $t = explode('|', $task);
                $process->kill($t[0]);
            }
        }
        exit();
    }

    /*
     * Inicializa o daemom quando N não for informado.
     */
    GetDaemon($notDaemon);
    try {
        declare(ticks=1);
        $statusSignal = 0;
        pcntl_signal(SIGTERM, "sig_handler");
        pcntl_signal(SIGHUP, "sig_handler");
        pcntl_signal(SIGUSR1, "sig_handler");
        pcntl_signal(SIGINT, "sig_handler");

        while (true) {
            foreach ($service as $k => $v) {
                $tsbot = new TSBOT();
                $process->task($v['service']);
                $process->columns($v['pid'], $v['idbot']);
                $tasks = $process->find();
                $bots = $tsbot->findAll();

                foreach ($bots as $bot) {
                    $found = false;
                    foreach ($tasks as $task) {
                        $t = explode('|', $task);

                        /** Processo Encontrado e OK */
                        if (trim($t[1]) == $bot->id_tsbot && $bot->active_tsbot) {
                            $found = true;
                            break;
                        }

                        /** Processo encontrado mas inativo, sera finalizado */
                        if (trim($t[1]) == $bot->id_tsbot && !$bot->active_tsbot) {
                            $process->kill($t[0]);
                            $found = true;
                            break;
                        }
                    }

                    if (!$found && $bot->active_tsbot) {
                        $process->execute($bot->id_tsbot);
                    }
                }
                sleep(1);
            }

            sleep(1);
            if (sig_status()) {
                break;
            }
        }
    } catch (Exception $ex) {
        echo "Error Finish:" . $ex->getMessage() . "\n\n";
    }

    function GetDaemon($notDaemon) {
        /*
         * Se o script não for chamado com daemon sai sem executar nada.
         */
        if ($notDaemon) {
            return 0;
        }

        $pid = pcntl_fork();
        if ($pid) {
            exit(0); //success
        }
    }

    function sig_handler($signo) {
        global $statusSignal;
        $statusSignal = 1;
    }

    function sig_status() {
        global $statusSignal;
        pcntl_signal_dispatch();
        return $statusSignal;
    }
    
