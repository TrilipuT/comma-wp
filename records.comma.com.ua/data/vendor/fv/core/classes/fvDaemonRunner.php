<?php
/**
 * Created by cah4a.
 * Time: 15:15
 * Date: 23.09.13
 */

final class fvDaemonRunner {

    private $script = null;
    private $folder = null;

    function __construct( $script ){
        return $this->setScript( $script );
    }

    public function getFolder(){
        if( empty($this->folder) )
            return rtrim( SCRIPTS_PATH, "/" ) . "/";

        return $this->folder;
    }

    public function setScript( $script ){
        $this->script = $script;
        return $this;
    }

    public function getScript(){
        return $this->getFolder() . $this->script . ".php";
    }

    public function getLogFile(){
        return $this->getFolder() . "logs/" . $this->script . ".log";
    }

    public function getPidFile(){
        return $this->getFolder() . "pids/" . $this->script . ".pid";
    }

    public function getPid(){
        if( ! file_exists( $this->getPidFile() ) )
            return null;

        return trim( file_get_contents( $this->getPidFile() ) );
    }

    public function getPhpExecutable(){
        return PHP_BINDIR . "/php";
    }

    public function start(){
        $script = $this->getScript();
        $log = $this->getLogFile();
        $options = '-d memory_limit=50M';
        passthru("{$this->getPhpExecutable()} {$options} {$script} {$this->getPidFile()} >> {$log} 2>&1 &");
        sleep(1);

        return $this->status();
    }

    public function status(){
        if( ! file_exists( $this->getPidFile() ) )
            return false;

        return fvDaemon::isRunning( $this->getPid() );
    }

    public function stop(){
        if( ! $this->getPid() ){
            return false;
        }
        passthru( "kill {$this->getPid()}", $a );
        if( $a == 0 ){

            $maxTime = time() + 10;
            while( time() < $maxTime ){
                sleep(1);
                if( ! $this->status() )
                    return true;
            }

            throw new Exception("sigterm sent, but daemon is still runnning");
        } else {
            throw new Exception("kill command unsuccessful");
        }
    }

}