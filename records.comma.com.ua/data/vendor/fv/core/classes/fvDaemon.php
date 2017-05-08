<?php
/**
 * Created by cah4a.
 * Time: 15:15
 * Date: 23.09.13
 */

abstract class fvDaemon {

    protected $fork = false;
    protected $tickSleepTime = .1;
    protected $lifetime = 86400; // one day
    protected $forkEnabled = true;
    protected $signalsEnabled = true;
    protected $maxMemory = 50;
    protected $autoRestart = false;

    private $unexpectedlyExit = true;
    private $runTime;
    private $pidFile = "";

    final public function __construct( $pidFile = null ){
        ini_set('memory_limit', $this->maxMemory . "M");
        $this->pidFile = $pidFile;

        if( empty($pidFile) )
            $this->pidFile = preg_replace( "/\\/([^\\/]+)\\.php$/", "/pids/$1.pid", $_SERVER['SCRIPT_NAME']);

        $this->lockOrDie();
        $this->attachSignals();
        $this->run();
        $this->unlock();
    }

    protected function onSIGTERM(){
        $this->unexpectedlyExit = false;
        print date('c') . " :: SIGTERM received. Bye!\n";

        if( file_exists( $this->pidFile ) ){
            $pid = file_get_contents( $this->pidFile );
            if( $pid == getmypid() ){
                $this->unlock();
            }
        }

        die;
    }

    /**
     * @return $this
     */
    private function run(){
        $this->runTime = microtime(true);

        while( true ){
            $this->runTick();

            $this->sleep( $this->tickSleepTime );

            if( $this->hasFork() ){
                pcntl_signal_dispatch();
            }

            if( $this->getMemoryUsed() > $this->maxMemory ){
                print date('c') . " :: Max memory used reached\n";
                $this->unexpectedlyExit = false;
                break;
            }

            if( $this->getExecutionTime() > $this->lifetime ){
                $this->unexpectedlyExit = false;
                break;
            }
        }

        return $this;
    }

    private function getExecutionTime(){
        return microtime(true) - $this->runTime;
    }

    abstract protected function tick();

    private function attachSignals(){
        if( ! $this->hasSignals() )
            return;

        foreach( get_class_methods($this) as $method ){
            if( strpos($method, "onSIG") === 0 ){
                $signal = substr($method, 2);
                if( ! defined($signal) )
                    throw new Exception( "Signal {$signal} is undefined" );

                pcntl_signal( constant($signal), array( $this, $method ) );
            }
        }
    }

    private function sleep( $seconds ){
        usleep( $seconds * 1000000 );
    }

    private function runTick(){
        if( $this->hasFork() ){
            $pid = pcntl_fork();
            if ($pid == -1) {
                die('could not fork');
            } else if ($pid) {
                pcntl_wait( $status ); // Ждём пока форк сделает свои делишки
            } else {
                $this->fork = true;
                $this->tick();
                $this->unexpectedlyExit = false;
                die; // Форк сделал своё дело, форк может умереть
            }
        } else {
            $this->tick();
        }
    }

    private function lockOrDie(){
        if( file_exists( $this->pidFile ) ){
            $pid = file_get_contents( $this->pidFile );

            if( $pid ){
                if( $this->isRunning( $pid ) ){
                    #print "Already Run (PID {$pid})";
                    $this->unexpectedlyExit = false;
                    die;
                }
            }
        }

        file_put_contents( $this->pidFile, getmypid() );

        if( ! file_exists( $this->pidFile ) ){
            print date('c') . " :: Can't lock via pid file {$this->pidFile}\n";
            die;
        }

        if( getmypid() != file_get_contents( $this->pidFile ) ){
            print date('c') . " :: Can't lock via pid file\n";
            die;
        }

        print date('c') . " :: Pid " . getmypid() . " started.\n";

        return $this;
    }

    private function unlock(){
        unlink( $this->pidFile );
        return $this;
    }

    static function isRunning( $pid ){
        return intval(`ps -p {$pid} | grep {$pid} | wc -l` ) > 0;
    }

    /**
     * @return bool
     */
    private function hasFork(){
        return $this->forkEnabled && extension_loaded( 'pcntl' );
    }

    /**
     * @return bool
     */
    private function hasSignals(){
        return $this->signalsEnabled && extension_loaded( 'pcntl' );
    }

    private function getMemoryUsed(){
        return memory_get_usage()/1024/1024;
    }

    function __destruct(){
        if( $this->unexpectedlyExit ){
            if( $this->fork )
                print date('c') . " :: Fork dropped unexpectedly\n";
            else
                print date('c') . " :: Deamon dropped unexpectedly\n";
        }
    }


}