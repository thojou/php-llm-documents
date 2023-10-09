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

use Google\Client;
use Thojou\LLMDocuments\Crawler\Spatie\SpatieCrawlerConfig;
use Thojou\LLMDocuments\Crawler\Spatie\SpatieCrawlerFactory;
use Thojou\LLMDocuments\Document\Document;
use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Embedding\OpenAi\OpenAiEmbeddings;
use Thojou\LLMDocuments\Loader\WebSearchLoader;
use Thojou\LLMDocuments\Parser\Unstructured\Api\UnstructuredAPI;
use Thojou\LLMDocuments\Parser\Unstructured\UnstructuredParserFactory;
use Thojou\LLMDocuments\Retriever\SimilarityRetriever;
use Thojou\LLMDocuments\Search\Google\GoogleSearchEngineFactory;
use Thojou\LLMDocuments\Storage\VectorStore\LocalVectorStore;
use Thojou\LLMDocuments\Transformation\DocumentTransformationBuilder;
use Thojou\LLMDocuments\ValueObjects\DoctranConfig;
use Thojou\OpenAi\OpenAi;
use Thojou\SimpleApiClient\Adapter\GuzzleClientFactory;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/credentials.php';


// INPUT
$query = $argv[1] ?? "Wer hat die Basketball WM 2023 gewonnen?";
$threshold = isset($argv[3]) ? (float)$argv[3] : 0.85;

// Define the OpenAI API Interface
$openAI = new OpenAi(
    OPENAI_KEY,
);

// Define the SimilarityRetriever to store and find similar documents
$retriever = new SimilarityRetriever(
    new LocalVectorStore(
        '/tmp/test.json',
        new OpenAiEmbeddings($openAI, 'text-embedding-ada-002'),
    ),
    $threshold
);

/**
 * @param array<DocumentInterface> $contextDocuments
 *
 * @return DocumentInterface
 */
function combineDocuments(array $contextDocuments): DocumentInterface
{
    $text = "";
    foreach($contextDocuments as $key => $document) {
        $text .= "DOCUMENT $key\n";
        $text .= $document->getPageContent() . "\n";
        $text .= "METADATA\n";
        $text .= json_encode($document->getMetadata(), JSON_PRETTY_PRINT) . "\n";
        $text .= "\n";
    }

    return new Document($text);
}

echo "Start retrieving relevant documents\n";
$contextDocuments = $retriever->getRelevantDocuments($query);

echo "Start combining documents\n";
$document = combineDocuments($contextDocuments);

echo "Start chat with OpenAI\n";
$context = $document->getPageContent();

$messages = [
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'system', 'content' => "Respond to the user's query using only the information provided in the given context. If you lack the necessary information to answer the question, reply with 'I don't know'."],
        ['role' => 'user',  'content' => "CONTEXT INFORMATION:\n===\n$context\n===\nUSER QUERY: $query\n===\nRESPONSE:"],
    ]
];

$response = $openAI->chat()->completion($messages);

echo "User QUERY: $query\n";
echo "RESPONSE:\n";
echo $response['choices'][0]['message']['content'] . "\n";
