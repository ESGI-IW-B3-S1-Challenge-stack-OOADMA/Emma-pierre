<?php

namespace App\HttpFoundationWish;

final class InputBag extends ParameterBag
{
    /**
     * Returns a scalar input value by name.
     *
     * @param string|int|float|bool|null $default The default value if the input key does not exist
     */
    public function get(string $key, mixed $default = null): string|int|float|bool|null
    {
        if (null !== $default && !\is_scalar($default) && !$default instanceof \Stringable) {
            throw new \InvalidArgumentException(sprintf('Expected a scalar value as a 2nd argument to "%s()", "%s" given.', __METHOD__, get_debug_type($default)));
        }

        $value = parent::get($key, $this);

        if (null !== $value && $this !== $value && !\is_scalar($value) && !$value instanceof \Stringable) {
            throw new \Exception(sprintf('Input value "%s" contains a non-scalar value.', $key));
        }

        return $this === $value ? $default : $value;
    }

    /**
     * Replaces the current input values by a new set.
     */
    public function replace(array $inputs = []): void
    {
        $this->parameters = [];
        $this->add($inputs);
    }

    /**
     * Adds input values.
     */
    public function add(array $inputs = []): void
    {
        foreach ($inputs as $input => $value) {
            $this->set($input, $value);
        }
    }

    /**
     * Sets an input by name.
     *
     * @param string|int|float|bool|array|null $value
     */
    public function set(string $key, mixed $value): void
    {
        if (null !== $value && !\is_scalar($value) && !\is_array($value) && !$value instanceof \Stringable) {
            throw new \InvalidArgumentException(sprintf('Expected a scalar, or an array as a 2nd argument to "%s()", "%s" given.', __METHOD__, get_debug_type($value)));
        }

        $this->parameters[$key] = $value;
    }
}