<?php

declare(strict_types=1);

use Rector\Configuration\RectorConfigBuilder;

/** @var RectorConfigBuilder $rectorConfig */
$rectorConfig = require 'vendor/justbetter/magento2-coding-standard/rector.php';

/** Define additional rules here
 * @see: https://getrector.com/find-rule?activeRectorSetGroup=php
 * @see: https://getrector.com/find-rule?activeRectorSetGroup=core
 */
/** @see: https://getrector.com/documentation/levels */
$rectorConfig->withTypeCoverageLevel(0);         // 1 is least intrusive changes, higher is more intrusive
$rectorConfig->withCodeQualityLevel(0);          // 1 is least intrusive changes, higher is more intrusive
$rectorConfig->withDeadCodeLevel(0);             // 1 is least intrusive changes, higher is more intrusive

$rectorConfig->withPreparedSets(
    // Only enable these when the levels above are completed and their config is removed
    // It will automatically set their level to the highest possible.
    // typeDeclarations: true,  // https://getrector.com/find-rule?activeRectorSetGroup=core&rectorSet=core-type-declarations
    // codeQuality: true,       // https://getrector.com/find-rule?activeRectorSetGroup=core&rectorSet=core-code-quality
    // deadCode: true,          // https://getrector.com/find-rule?activeRectorSetGroup=core&rectorSet=core-dead-code
    instanceOf: false,       // https://getrector.com/find-rule?rectorSet=core-instanceof&activeRectorSetGroup=core
    earlyReturn: false,      // https://getrector.com/find-rule?rectorSet=core-early-return&activeRectorSetGroup=core
);

return $rectorConfig;
