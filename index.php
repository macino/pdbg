<?php

include_once "pdbg.php";
pdbg()->bstart();
pdbg()->point();
pdbg()->bstart('test');
$var = array(1,2,3, 'xx');
pdbg()->dump($var);
pdbg()->log('test');
pdbg()->dump($var, 'var');
pdbg()->hdump($var, 'var');
pdbg()->bench();
pdbg()->point('x');
pdbg()->bench();
pdbg()->hdigh(10, 123);
pdbg()->hflush();

