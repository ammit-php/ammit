<?php

use \mageekguy\atoum;

$report = $script->addDefaultReport();

// This will add a green or red logo after each run depending on its status.
$report->addField(new atoum\report\fields\runner\result\logo());

// This will ad the default CLI report
$script->addDefaultReport();

$cloverWriter = new atoum\writers\file('atoum.coverage.xml');

// Generate a clover XML report.
$cloverReport = new atoum\reports\asynchronous\clover();
$cloverReport->addWriter($cloverWriter);

$runner->addReport($cloverReport);

$runner->addTestsFromDirectory(__DIR__.'/tests/units');
