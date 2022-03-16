<?php

declare(strict_types=1);

namespace Flagbit\Bundle\CategoryBundle\Transformer\Denormalization;

use Flagbit\Bundle\CategoryBundle\Transformer\DenormalizationTransformer;

/**
 * Transformer that handles proper normalization of boolean values.
 */
class BooleanTransformer implements DenormalizationTransformer
{
    /**
     * Convert passed data to an int representing the boolean value of the data (0 / 1).
     *
     * @phpstan-param string $data
     */
    public function transform($data): bool
    {
        return (bool) $data;
    }
}
