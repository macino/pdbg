<?php

class pdbg
{
  private $buff = "";
  private $point = 1;
  private static $inst;
  public $testEnv = false;
  public $logFile = 'pdbg.log';

  private function __construct() {}
  static function getInst()
  {
    if (!self::$inst) {
      self::$inst = new pdbg();
    }
    return self::$inst;
  }
  private function stopOb()
  {
    if (ob_get_level() > 0) {
      ob_end_clean();
    }
    return $this;
  }
  private function mtime()
  {
  	if ($this->testEnv) {
  		return 0.123456;
	  }
  	return microtime(true);
  }
  private function date()
  {
  	if ($this->testEnv) {
  		return '11:22:33';
	  }
  	return date('H:i:s');
  }
  function restart()
  {
  	if ($this->testEnv) {
  		$this->buff = "";
  		$this->point = 1;
		}
  }
  function setLogFile($logFile = 'pdbg.log')
  {
  	$this->logFile = $logFile;
  }
  function flush()
  {
  	if ($this->testEnv) {
    	return $this->buff;
		}
    $this->stopOb();
    echo $this->buff;
    die('--pdbg--');
  }
  function fl(){
  	return $this->flush();
  }
  function hflush()
  {
    $out = sprintf(
      "<pre>%s</pre>"
    , $this->buff
    );
    if ($this->testEnv) {
    	return $out;
		}
    $this->stopOb();
    echo $out;
    die ('--pdbg--');
  }
  function hfl()
  {
  	return $this->hflush();
  }
  function fflush($append = true)
  {
    $flag = $append ? FILE_APPEND : 0;
    file_put_contents($this->logFile, $this->buff, $flag);
  }
  function ffl($append = true)
  {
  	return $this->fflush($append);
  }
  function log($msg)
  {
    $mtime = $this->mtime();
    $mtime = floor(($mtime - floor($mtime)) * pow(10,6));
    $this->buff .=
      "[" . $this->date() . "." . $mtime . "] "
    . $msg . "\n"
    ;
    return $this;
  }
  function l($msg)
  {
  	return $this->log($msg);
  }
  function point($desc = '') {
    if ($desc) {
      $desc = ' ' . $desc;
    }
    $this->log("CHPT #" . $this->point++ . $desc);
    return $this;
  }
  function p($desc = '')
  {
  	return $this->point($desc);
  }
  function dump($var, $desc = '')
  {
    $desc .= "\n";
    $this->log($desc . var_export($var, true));
    return $this;
  }
  function d($var, $desc = '')
  {
  	return $this->dump($var, $desc);
  }
  function hdump($var, $desc = '')
  {
    $desc .= "\n";
    $this->log(
      $desc
    . highlight_string(
      "<? "
      . var_export($var, true)
      , true
      )
    );
    return $this;
  }
  function hd($var, $desc = '')
  {
  	return $this->hdump($var, $desc);
  }
  private $bench = array();
  function bstart($desc = '-')
  {
    array_push(
      $this->bench
    , array(
        'desc' => $desc,
        'time' => $this->mtime(),
      )
    );
    $this->log(sprintf(
      "BNCH (%s) STRT"
    , $desc
    ));
    return $this;
  }
  function bs($desc = '-')
  {
		return $this->bstart($desc);
  }
  function bench()
  {
    $b = array_shift($this->bench);
    $this->log(sprintf(
      "BNCH (%s) %f ms"
    , $b['desc']
    , ($this->mtime() - $b['time']) * 1000
    ));
    return $this;
  }
  function b()
  {
  	return $this->bench();
  }
  function hdigh() //how do I get here
  {
    $bt = debug_backtrace(~DEBUG_BACKTRACE_PROVIDE_OBJECT&~DEBUG_BACKTRACE_IGNORE_ARGS);
    $log = '';
    foreach($bt as $i => $line) {
      $log .= sprintf(
        "  (%d) %s#%s : %s%s%s(%s)\n"
      , $i
      , $line['file']
      , $line['line']
      , $line['class']
      , $line['type']
      , $line['function']
      , implode(', ', $line['args'])
      );
    }
    $this->log("BCK/TRC\n" . $log);
    return $this;
  }
}

function pdbg()
{
  return pdbg::getInst();
}

function _p()
{
	return pdbg();
}
