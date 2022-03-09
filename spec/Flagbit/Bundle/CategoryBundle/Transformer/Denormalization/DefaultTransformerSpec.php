<?php

declare(strict_types=1);

namespace spec\Flagbit\Bundle\CategoryBundle\Transformer\Denormalization;

use Flagbit\Bundle\CategoryBundle\Transformer\Denormalization\DefaultTransformer;
use PhpSpec\ObjectBehavior;

/**
 * @method transform(mixed $data)
 */
class DefaultTransformerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(DefaultTransformer::class);
    }

    public function it_returns_unmodified_value_with_string_data_argument(): void
    {
        $this->transform('some test')->shouldReturn('some test');
    }

    public function it_returns_unmodified_value_with_int_data_argument(): void
    {
        $this->transform(5)->shouldReturn(5);
    }

    public function it_returns_unmodified_value_with_boolean_data_argument(): void
    {
        $this->transform(true)->shouldReturn(true);
    }

    public function it_returns_unmodified_value_with_array_data_argument(): void
    {
        $this->transform(['test' => 5])->shouldReturn(['test' => 5]);
    }
}
