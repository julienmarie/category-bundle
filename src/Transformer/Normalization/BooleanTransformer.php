<?php

declare(strict_types=1);

namespace Flagbit\Bundle\CategoryBundle\Transformer\Normalization;

use Flagbit\Bundle\CategoryBundle\Transformer\NormalizationTransformer;

/**
 * Transformer that handles proper normalization of boolean values.
 */
class BooleanTransformer implements NormalizationTransformer
{
    /**
     * Convert passed data to a int representing the boolean value of the data (0 / 1).
     *
     * @param mixed $data
     */
    public function transform($data): int
    {
        return (int) ((bool) $data);
    }
}
