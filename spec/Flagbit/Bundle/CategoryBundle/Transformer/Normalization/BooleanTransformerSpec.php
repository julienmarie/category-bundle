<?php

declare(strict_types=1);

namespace spec\Flagbit\Bundle\CategoryBundle\Transformer\Normalization;

use Flagbit\Bundle\CategoryBundle\Transformer\Normalization\BooleanTransformer;
use PhpSpec\ObjectBehavior;

/**
 * @method transform(mixed $data)
 */
class BooleanTransformerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(BooleanTransformer::class);
    }

    public function it_returns_one_with_boolean_true_data_argument(): void
    {
        $this->transform(true)->shouldReturn(1);
    }

    public function it_returns_zero_with_boolean_false_data_argument(): void
    {
        $this->transform(false)->shouldReturn(0);
    }

    public function it_returns_one_with_int_one_data_argument(): void
    {
        $this->transform(1)->shouldReturn(1);
    }

    public function it_returns_zero_with_int_zero_data_argument(): void
    {
        $this->transform(0)->shouldReturn(0);
    }

    public function it_returns_one_with_int_five_data_argument(): void
    {
        $this->transform(5)->shouldReturn(1);
    }

    public function it_returns_one_with_int_minus_one_data_argument(): void
    {
        $this->transform(-1)->shouldReturn(1);
    }

    public function it_returns_one_with_string_one_data_argument(): void
    {
        $this->transform('1')->shouldReturn(1);
    }

    public function it_returns_zero_with_string_zero_data_argument(): void
    {
        $this->transform('0')->shouldReturn(0);
    }

    public function it_returns_one_with_string_data_argument(): void
    {
        $this->transform('test')->shouldReturn(1);
    }

    public function it_returns_one_with_string_space_data_argument(): void
    {
        $this->transform(' ')->shouldReturn(1);
    }

    public function it_returns_zero_with_string_empty_data_argument(): void
    {
        $this->transform('')->shouldReturn(0);
    }

    public function it_returns_one_with_array_with_elements_data_argument(): void
    {
        $this->transform(['test'])->shouldReturn(1);
    }

    public function it_returns_one_with_array_empty_data_argument(): void
    {
        $this->transform([])->shouldReturn(0);
    }
}
