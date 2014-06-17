===========================================================================
                            PDBG - Php Debugger
===========================================================================

:Auhtor: Tomas Macik
:Contact: tomas.macik@gmail.com

.. contents::

References
==========

Instantination
--------------

pdbg(), Pdbg::getInst()
~~~~~~~~~~~~~~~~~~~~~~~

Returns singleton instance of ``pdbg`` class.

Logging and dumping
-------------------

log(msg)
~~~~~~~~

Append a message from ``msg`` to log buffer with timestamp prefix.

point([desc])
~~~~~~~~~~~~~

Appends a check point message to log buffer. Description of check point
will be added if present in ``desc``.

dump(var[, desc])
~~~~~~~~~~~~~~~~~

Append an exported variable to log buffer. ``var_export`` fce is used to get
the exported content of variable. Description of dump will be added if
present in ``desc``.

hdump(var[, desc])
~~~~~~~~~~~~~~~~~~

Same as ``dump``, but with syntax highlight in html. Improves readability
in browsers but it worsen it in console output.

hdigh()
~~~~~~~

As `How Do I Get Here`, prints a formatted backtrace to actual position
including function itself. It does not dump the objects, only arguments of
each function in trace.

Benchmarking
------------

Pdbg can provide multiple benchmarks. Bench marks have their own buffer, so
by ending the last started benchmark will be ended. Benchmarsk are
messaured in microsecond precission.

bstart([desc])
~~~~~~~~~~~~~~

Starts a new bechmark and mark it to log buffer with description
(default='-').

bench()
~~~~~~~

Ends the last started benchmark and mark it to log buffer with message
containing the duration of the benchmark in miliseconds units.

Flushing the buffer
-------------------

flush()
~~~~~~~

Stops outputbuffer if present. Flushes the content of log buffer to
stdout without any surrounding tags and dies. This is good for console
debugging.

hflush()
~~~~~~~~

Same as ``flush()``, but it uses ``htmlentities`` and ``<pre>`` tags to format
output, so it would be more readable in html output.

fflush([append])
~~~~~~~~~~~~~~

Flushes the content of log buffer to file ``pdbg.log`` without any
formatting. If ``append`` is ``true``, the content will be appended to the
file. Otherwise the file will be overwritten with new content.
