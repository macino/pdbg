<?php

require 'pdbg.php';
pdbg()->testEnv = true;
var_dump(pdbg()->testEnv);

use PHPUnit\Framework\TestCase;

class pdbgTest extends TestCase
{
  public function testInstance()
  {
    $this->assertInstanceOf(pdbg::class, pdbg(), 'Class instance');
  }
  public function testLogFlush()
  {
    pdbg()->restart();
    $this->assertEquals(
      "[11:22:33.123456] test\n"
    , pdbg()->log('test')->flush()
    , 'Log and flush'
    );
  }
  public function testLogFlushShort()
  {
    pdbg()->restart();
    $this->assertEquals(
      "[11:22:33.123456] test\n"
      , pdbg()->l('test')->fl()
      , 'Log and flush short'
    );
  }
  public function testPointFlush()
  {
    pdbg()->restart();
    $this->assertEquals(
      "[11:22:33.123456] CHPT #1 test\n"
    , pdbg()->point('test')->flush()
    , 'Point and flush'
    );
  }
  public function testPointFlushShort()
  {
    pdbg()->restart();
    $this->assertEquals(
      "[11:22:33.123456] CHPT #1 test\n"
      , pdbg()->p('test')->fl()
      , 'Point and flush short'
    );
  }
  public function testDumpHflush()
  {
    pdbg()->restart();
    $this->assertEquals(
      "<pre>[11:22:33.123456] \n'<p>test</p>'\n</pre>"
      , pdbg()->dump('<p>test</p>')->hflush()
      , 'Dump and hflush'
    );
  }
  public function testDumpHflushShort()
  {
    pdbg()->restart();
    $this->assertEquals(
      "<pre>[11:22:33.123456] \n'<p>test</p>'\n</pre>"
      , pdbg()->d('<p>test</p>')->hfl()
      , 'Dump and hflush short'
    );
  }
  public function testHdumpHflush()
  {
    pdbg()->restart();
    $this->assertEquals(
      "<pre>[11:22:33.123456] \n<code><span style=\"color: #000000\">\n&lt;?&nbsp;'&lt;p&gt;test&lt;/p&gt;'</span>\n</code>\n</pre>"
    , pdbg()->hdump('<p>test</p>')->hflush()
    , 'Hdump and hflush'
    );
  }
  public function testHdumpHflushShort()
  {
    pdbg()->restart();
    $this->assertEquals(
      "<pre>[11:22:33.123456] \n<code><span style=\"color: #000000\">\n&lt;?&nbsp;'&lt;p&gt;test&lt;/p&gt;'</span>\n</code>\n</pre>"
    , pdbg()->hd('<p>test</p>')->hfl()
    , 'Hdump and hflush short'
    );
  }
  public function testLogFflush()
  {
    pdbg()->restart();
    $logFile = 'test.log';
    pdbg()->setLogFile($logFile);
    pdbg()->log('test')->fflush();
    $this->assertFileExists($logFile);
    $this->assertEquals(
      "[11:22:33.123456] test\n"
      , file_get_contents($logFile)
      , 'Log and fflush'
    );
    unlink($logFile);
  }
  public function testLogFflushShort()
  {
    pdbg()->restart();
    $logFile = 'test.log';
    pdbg()->setLogFile($logFile);
    pdbg()->l('test')->ffl();
    $this->assertFileExists($logFile);
    $this->assertEquals(
      "[11:22:33.123456] test\n"
      , file_get_contents($logFile)
      , 'Log and fflush short'
    );
    unlink($logFile);
  }
  public function testBenchFlush()
  {
    pdbg()->restart();
    pdbg()->bstart('test');
    $this->assertEquals(
      "[11:22:33.123456] BNCH (test) STRT\n[11:22:33.123456] BNCH (test) 0.000000 ms\n"
      , pdbg()->bench()->flush()
      , 'Bench and flush'
    );
  }
  public function testBenchFlushShort()
  {
    pdbg()->restart();
    pdbg()->bs('test');
    $this->assertEquals(
      "[11:22:33.123456] BNCH (test) STRT\n[11:22:33.123456] BNCH (test) 0.000000 ms\n"
      , pdbg()->b()->fl()
      , 'Bench and flush short'
    );
  }
}
