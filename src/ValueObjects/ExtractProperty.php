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

final class ExtractProperty
{
    /**
     * @param string                    $name
     * @param string                    $type
     * @param string                    $description
     * @param bool                      $required
     * @param array<string, mixed>|null $items
     * @param array<int, string>|null   $enum
     */
    public function __construct(
        private readonly string $name,
        private readonly string $type,
        private readonly string $description,
        private readonly bool $required = true,
        private readonly ?array $items = null,
        private readonly ?array $enum = null
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = [
            'type' => $this->type,
            'description' => $this->description,
        ];

        if ($this->items) {
            $array['items'] = $this->items;
        }

        if ($this->enum) {
            $array['enum'] = $this->enum;
        }

        return $array;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }
}
