<?php

declare(strict_types=1);

namespace Flagbit\Bundle\CategoryBundle\Transformer\Denormalization;

use Flagbit\Bundle\CategoryBundle\Transformer\DenormalizationTransformer;

/**
 * Default denormalization transformer that applies no changes.
 */
class DefaultTransformer implements DenormalizationTransformer
{
    /**
     * @phpstan-param mixed $data
     *
     * @return mixed
     */
    public function transform($data)
    {
        return $data;
    }
}
