<?php

class SheeplaLog {
    
    protected $writters = array();
    
    const LEVEL_SERVICE = 'service';
    const LEVEL_NOTICE = 'notice';
    const LEVEL_ERROR = 'error';
    const LEVEL_CRITICAL = 'critical';
    
    
    public function __construct() {
        
        $this->writters[] = new SheeplaLogMail();
        $this->writters[] = new SheeplaLogFile();
    }
    
    
    public function log( $level , $method , $msg , $target ) {
        
        if( in_array( $level , array( self::LEVEL_NOTICE , self::LEVEL_ERROR , self::LEVEL_CRITICAL ) ) ) {
            
            switch( $level ) {
                case self::LEVEL_NOTICE:
                    return $this->notice( $method , $msg , $target );
                    
                case self::LEVEL_ERROR:
                    return $this->error( $method , $msg , $target );
                    
                case self::LEVEL_CRITICAL:
                    return $this->critical( $method , $msg , $target );
            }
        }
    }
    
    public function service( $method , $msg , $target ) {
        
        $this->write( self::LEVEL_SERVICE , $method , $msg , $target );
    }
    
    public function notice( $method , $msg , $target ) {
        
        $this->write( self::LEVEL_NOTICE , $method , $msg , $target );
    }
    
    
    public function error( $method , $msg , $target ) {
        
        $this->write( self::LEVEL_ERROR , $method , $msg , $target );
    }
    
    
    public function critical( $method , $msg , $target ) {
        
        $this->write( self::LEVEL_CRITICAL ,$method , $msg , $target );
    }
    
    
    protected function write( $level , $method , $msg , $target ) {
        $msg = $this->formatMessage( $level, $method, $msg );
        
        foreach( $this->writters as $writter ) {
            
            $writter->log( $level , $msg , $target );
        }
    }
    
    
    protected function formatMessage( $level , $method , $msg ) {
        
        return date( 'Y-m-d H:i:s' ) . "; $level; $method; $msg\n";
    }
}

class SheeplaLogMail {
    
    protected $recipitent = 'logs@sheepla.com';
    
    public function log( $level , $message , $target ) {
        
        if( preg_match( '/Invalid config/', $message ) &&
            null == $target->config->get( 'sheepla_' ) &&
            null == $target->config->get( 'sheepla_' ) ) {
            
            return false;
        }
        
        
        if( SheeplaLog::LEVEL_CRITICAL == $level ||
            SheeplaLog::LEVEL_SERVICE == $level ) {
            
            $sheeplaVersion = defined( 'SHEEPLA_VERSION' ) ? SHEEPLA_VERSION : 'unknown';
                    
            $msg = "Message from {$_SERVER['HTTP_HOST']} ( Opencart )" . "
                opencart version: " . VERSION . "
                sheepla version: " . $sheeplaVersion . "
                http host: {$_SERVER['HTTP_HOST']}
                server name: {$_SERVER['SERVER_NAME']}
                timestamp: " . date("Y-m-d H:i:s") . "
                message: 
                   $message"
            ;
            
            @mail( $this->recipitent , "Message from {$_SERVER['HTTP_HOST']} ( Opencart )" , $msg );
        }
    }
}

class SheeplaLogFile {
    
    protected $logFile;
    
    public function __construct() {
        
        $this->logFile = DIR_LOGS . "sheepla.log";
    }
    
    public function log( $level , $message , $target ) {
        
        if( !is_writeable( $this->logFile ) &&
            !touch( $this->logFile ) ) {
            
            return;
        }
        
        file_put_contents( $this->logFile , $message , FILE_APPEND );
    }
}