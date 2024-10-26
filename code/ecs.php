<?php

declare(strict_types=1);

use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\StandaloneLinePromotedPropertyFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([__DIR__ . '/src', __DIR__.'/tests']);

    $ecsConfig->import(SetList::COMMON);
    $ecsConfig->import(SetList::CLEAN_CODE);
    $ecsConfig->import(SetList::SYMPLIFY);
    $ecsConfig->import(SetList::PSR_12);
    $ecsConfig->import(SetList::DOCTRINE_ANNOTATIONS);

    $ecsConfig->ruleWithConfiguration(LineLengthFixer::class, [LineLengthFixer::LINE_LENGTH => 140]);
    $ecsConfig->rule(StandaloneLinePromotedPropertyFixer::class);
};
