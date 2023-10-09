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
use Thojou\LLMDocuments\Loader\FilesystemLoader;
use Thojou\LLMDocuments\Loader\WebSearchLoader;
use Thojou\LLMDocuments\Parser\Unstructured\Api\UnstructuredAPI;
use Thojou\LLMDocuments\Parser\Unstructured\UnstructuredParserFactory;
use Thojou\LLMDocuments\Retriever\ParentDocumentRetriever;
use Thojou\LLMDocuments\Retriever\SimilarityRetriever;
use Thojou\LLMDocuments\Search\Google\GoogleSearchEngineFactory;
use Thojou\LLMDocuments\Splitter\RecursiveTextSplitter;
use Thojou\LLMDocuments\Storage\DocumentStore\LocalDocumentStore;
use Thojou\LLMDocuments\Storage\VectorStore\LocalVectorStore;
use Thojou\LLMDocuments\Transformation\DocumentTransformationBuilder;
use Thojou\LLMDocuments\ValueObjects\DoctranConfig;
use Thojou\LLMDocuments\ValueObjects\ExtractProperty;
use Thojou\LLMDocuments\ValueObjects\FunctionParameters;
use Thojou\OpenAi\OpenAi;
use Thojou\SimpleApiClient\Adapter\GuzzleClientFactory;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/credentials.php';


// INPUT
$filepath = $argv[1];
$task = $argv[2] ?? "Create a PHPUnit TestCase Class and implement tests for the given code.";

// Define the OpenAI API Interface
$openAI = new OpenAi(
    OPENAI_KEY,
);

$loader = new FilesystemLoader('/.*.php/');
$documents = $loader->load($filepath);

if(!is_array($documents)) {
    $documents = [$documents];
}


foreach($documents as $document) {
    $content = $document->getPageContent();

    $messages = [
        'model' => 'gpt-4',
        'messages' => [
            ['role' => 'system', 'content' => "As a coding assistant, your role is to assist the developer in achieving their goals by completing tasks based on the developer's instructions and the provided file content."],
            ['role' => 'user',  'content' => "FILE CONTENT:\n===\n$content\n===\nTASK: $task\n===\nRESULT:"],
        ]
    ];

    $response = $openAI->chat()->completion($messages);

    echo "User QUERY: $task\n";
    echo "RESPONSE:\n";
    echo $response['choices'][0]['message']['content'] . "\n";
}
