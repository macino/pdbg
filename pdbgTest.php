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
    $this->assertEquals("[11:22:33.123456] test\n", pdbg()->log('test')->flush(), 'Log and flush');
	}
}
