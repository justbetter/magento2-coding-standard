<?php

declare(strict_types=1);

use Magento2\Rector\Src\ReplaceNewDateTimeNull;
use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodingStyle\Rector\ClassConst\RemoveFinalFromConstRector;
use Rector\Config\RectorConfig;
use Rector\Configuration\RectorConfigBuilder;
use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector;
use Rector\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeSplFixedArrayRector;

// Work around RectorConfigBuilder being private
class ConfigBuilderWrapper {
    private RectorConfigBuilder $configBuilder;
    public function __construct()
    {
        $this->configBuilder = RectorConfig::configure();
    }

    public function __invoke(RectorConfig $rectorConfig): void
    {
        $magentoRector = require 'vendor/magento/magento-coding-standard/rector.php';
        $magentoRector($rectorConfig);

        // Remove duplicate rules
        $ref = new ReflectionObject($this->configBuilder);
        $prop = $ref->getProperty('rules');
        $prop->setAccessible(true);
        $rules = $prop->getValue($this->configBuilder);
        $rules = array_values(array_unique($rules));
        $prop->setValue($this->configBuilder, $rules);

        $this->configBuilder->__invoke($rectorConfig);
    }

    public function __call($method, $parameters)
    {
        return $this->configBuilder->{$method}(...$parameters);
    }
}

/** @var RectorConfigBuilder $rectorConfigUniq */
$rectorConfigUniq = new ConfigBuilderWrapper();

$rectorConfigUniq
    ->withPaths([
        __DIR__ . '/../../../app',
    ])
    ->withSkip([
        __DIR__ . '/../../../app/bootstrap.php',
        __DIR__ . '/../../../app/functions.php',
        __DIR__ . '/../../../app/autoload.php',
        __DIR__ . '/../../../app/etc/NonComposerComponentRegistration.php',
        __DIR__ . '/../../../app/etc/registration_globlist.php',
        __DIR__ . '/../../../app/etc/vendor_path.php',
    ])
    ->withPhpSets()
    ->withRules([
        InlineConstructorDefaultToPropertyRector::class,
        RemoveFinalFromConstRector::class,
        RemoveEmptyClassMethodRector::class,
        CompleteDynamicPropertiesRector::class,
        RemoveNonExistingVarAnnotationRector::class,
        RemoveUselessReadOnlyTagRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class,
        AddParamArrayDocblockBasedOnCallableNativeFuncCallRector::class,
        AddReturnArrayDocblockBasedOnArrayMapRector::class,
        AddParamTypeSplFixedArrayRector::class,
        ExplicitNullableParamTypeRector::class,
    ])
    ->withSkip([
        ReadOnlyPropertyRector::class, // Do not make constructors readonly as this is incompatible with parents
        ReplaceNewDateTimeNull::class, // Broken magento added rule (https://github.com/magento/magento-coding-standard/issues/392)
        // Mostly aimed at preventing issues for API interfaces
        RemoveUselessReturnTagRector::class, // Prevent removing @return docblocks, even if its the same type
        RemoveUselessParamTagRector::class, // Prevent removing @param docblocks, even if its the same type
        RemoveNullTagValueNodeRector::class, // Prevent removing @param docblocks, even if its the same type
    ]);

return $rectorConfigUniq;
