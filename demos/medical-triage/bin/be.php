<?php

declare(strict_types=1);

namespace Be\Pattern\MedicalTriage;

require dirname(__DIR__) . '/vendor/autoload.php';

use Be\Framework\BecomingInterface;
use Be\Pattern\MedicalTriage\Input\PatientInput;
use Be\Pattern\MedicalTriage\Module\DevModule;
use Ray\Di\Injector;

use function dirname;
use function escapeshellarg;
use function passthru;
use function printf;

$injector = new Injector(new DevModule());
$becoming = $injector->getInstance(BecomingInterface::class);

$final = $becoming(new PatientInput(
    patientId: 'PT-10001',
    chiefComplaint: 'severe chest pain',
    consciousnessLevel: 0,
));
printf("triage: %s\n", $final::class);

echo "\n--- stree ---\n";
passthru('vendor/bin/stree ' . escapeshellarg(dirname(__DIR__) . '/var/log/medical-triage.json'));
