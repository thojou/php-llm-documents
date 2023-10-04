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

namespace Thojou\LLMDocuments\Storage\DocumentStore;

use Exception;
use Symfony\Component\Uid\Uuid;
use Thojou\LLMDocuments\Document\Document;
use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Storage\LocalStoreTrait;

class LocalDocumentStore implements DocumentStoreInterface
{
    use LocalStoreTrait;

    public function __construct(
        protected readonly string $filename,
    ) {
    }

    /**
     * @throws Exception
     */
    public function get(string $id): DocumentInterface
    {
        return isset($this->storage[$id]) ?
            $this->fromArray($this->storage[$id]) :
            throw new Exception("Document with id $id not found");
    }

    /**
     * @param DocumentInterface|array<DocumentInterface> $documents
     *
     * @return void
     */
    public function add(DocumentInterface|array $documents): void
    {
        if(!is_array($documents)) {
            $documents = [$documents];
        }

        foreach ($documents as $document) {
            $id = $this->ensureDocumentId($document);
            $this->storage[$id] = $this->toArray($document);
        }

        $this->save();
    }

    /**
     * @throws Exception
     */
    public function collect(array $ids): array
    {
        return array_map(
            fn (string $id): DocumentInterface => $this->get($id),
            $ids
        );
    }

    /**
     * @param array<int, string> $ids
     *
     * @return void
     */
    public function delete(array $ids): void
    {
        foreach ($ids as $id) {
            unset($this->storage[$id]);
        }

        $this->save();
    }

    /**
     * @param DocumentInterface $document
     *
     * @return string
     */
    private function ensureDocumentId(DocumentInterface $document): string
    {
        $id = $document->getId() ?? Uuid::v4()->toRfc4122();
        $document->setId($id);

        return (string)$id;
    }

    /**
     * @param DocumentInterface $document
     *
     * @return array{pageContent: string, metadata: array<string, mixed>}
     */
    private function toArray(DocumentInterface $document): array
    {
        return [
            'pageContent' => $document->getPageContent(),
            'metadata' => $document->getMetadata()
        ];
    }

    /**
     * @param array{pageContent: string, metadata: array<string, mixed>} $data
     *
     * @return DocumentInterface
     */
    private function fromArray(array $data): DocumentInterface
    {
        return new Document(
            $data['pageContent'],
            $data['metadata']
        );
    }
}
