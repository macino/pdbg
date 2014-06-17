<?php

class pdbg
{
  private $buff = "";
  private function __construct() {}
  private static $inst;
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
  function flush()
  {
    $this->stopOb();
    echo $this->buff;
    die('--pdbg--');
  }
  function hflush()
  {
    $this->stopOb();
    echo sprintf(
      "<pre>%s</pre>"
    , $this->buff
    );
    die('--pdbg--');
  }
  function fflush($append = true)
  {
    $flag = $append ? FILE_APPEND : 0;
    file_put_contents("pdbg.log", $this->buff, $flag);
  }
  function log($msg)
  {
    $mtime = microtime(true);
    $mtime = floor(($mtime - floor($mtime)) * pow(10,6));
    $this->buff .=
      "[" . date("H:i:s") . "." . $mtime . "] "
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
        'time' => microtime(true),
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
    , (microtime(true) - $b['time']) * 1000
    ));
    return $this;
  }
  function hdigh($x) //how do I get here
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

