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

namespace Thojou\LLMDocuments\Storage\VectorStore;

use Exception;
use Symfony\Component\Uid\Uuid;
use Thojou\LLMDocuments\Document\Document;
use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Embedding\EmbeddingInterface;
use Thojou\LLMDocuments\Similarity\CosineSimilarity;
use Thojou\LLMDocuments\Similarity\SimilarityInterface;
use Thojou\LLMDocuments\Storage\LocalStoreTrait;

class LocalVectorStore implements VectorStoreInterface
{
    use LocalStoreTrait;

    /**
     * @var array<string, array{id: string, text: string, vector: array<int, float>, metadata: array<string, mixed>}>
     */
    protected array $storage;

    public function __construct(
        protected readonly string $filename,
        private readonly EmbeddingInterface $embedding,
        private readonly SimilarityInterface $similarity = new CosineSimilarity()
    ) {
        $this->load();
    }

    /**
     * @param array<int, DocumentInterface> $documents
     *
     * @return void
     * @throws Exception
     */
    public function addDocuments(array $documents): void
    {
        $texts = array_map(fn (DocumentInterface $document) => $document->getPageContent(), $documents);
        $metadata = array_map(fn (DocumentInterface $document) => $document->getMetadata(), $documents);

        $this->addTexts($texts, $metadata);
    }

    /**
     * @param array<int, string> $texts
     * @param array<int, array<string, mixed>> $metadata
     *
     * @return void
     * @throws Exception
     */
    public function addTexts(array $texts, array $metadata): void
    {
        $vectors = $this->embedding->embedList($texts);

        $data = array_map(
            function (string $text, array $vector, array $meta): array {
                $id = $meta[DocumentInterface::IDENTIFIER] ?? null;
                if (!is_null($id) && !is_string($id)) {
                    throw new Exception("Metadata key document_id must be a string or null");
                }

                $id ??= Uuid::v4()->toRfc4122();

                return [
                    'id' => (string)$id,
                    'text' => $text,
                    'vector' => $vector,
                    'metadata' => $meta + [DocumentInterface::IDENTIFIER => $id]
                ];
            },
            $texts,
            $vectors,
            $metadata
        );

        $data = array_combine(array_column($data, 'id'), $data);

        $this->storage = $this->storage + $data;

        $this->save();
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
     * @param string $query
     * @param float $similarityThreshold
     * @param int $limit
     * @param array<string, mixed> $filter
     *
     * @return array<int, DocumentInterface>
     */
    public function search(string $query, float $similarityThreshold = 0.75, int $limit = 4, array $filter = []): array
    {
        $queryVector = $this->embedding->embedString($query);

        $results = array_filter(
            $this->storage,
            fn (array $data) => $this->similarity->compare($queryVector, $data['vector']) >= $similarityThreshold
        );

        $results = array_map(
            fn (array $data) => [
                'id' => $data['id'],
                'score' => $this->similarity->compare($queryVector, $data['vector']),
                'metadata' => $data['metadata'],
                'text' => $data['text']
            ],
            $results
        );

        usort(
            $results,
            fn (array $a, array $b) => $b['score'] <=> $a['score']
        );

        $results = array_slice($results, 0, $limit);

        return array_values(
            array_map(
                fn (array $data) => new Document(
                    $data['text'],
                    $data['metadata']
                ),
                $results
            )
        );
    }
}
