<?php

class pdbg
{
  private $buff = "";
  private static $inst;
  public $testEnv = false;

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
		}
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
    die('--pdbg--');
  }
  function fflush($append = true)
  {
    $flag = $append ? FILE_APPEND : 0;
    file_put_contents("pdbg.log", $this->buff, $flag);
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
  private $point = 1;
  function point($desc = '') {
    if ($desc) {
      $desc = ' ' . $desc;
    }
    $this->log("CHPT #" . $this->point++ . $desc);
    return $this;
  }
  function dump($var, $desc = '')
  {
    $desc .= "\n";
    $this->log($desc . var_export($var, true));
    return $this;
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

