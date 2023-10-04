<?php

declare(strict_types=1);

/*
 * This file is part of PHP LLM Documents.
 *
 * (c) Thomas Joußen <tjoussen91@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Thojou\LLMDocuments\ValueObjects;

final class FunctionParameters
{
    private readonly string $type;

    /**
     * @var array<string, mixed>
     */
    private array $properties = [];

    /**
     * @var array<int, string>
     */
    private array $required = [];

    public function __construct()
    {
        $this->type = 'object';
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'properties' => $this->properties,
            'required' => $this->required,
        ];
    }

    public function add(ExtractProperty $property): self
    {
        $this->properties[$property->getName()] = $property->toArray();

        if ($property->isRequired()) {
            $this->required[] = $property->getName();
        }

        return $this;
    }
}
