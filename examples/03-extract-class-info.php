<?php

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

// Define the OpenAI API Interface
$openAI = new OpenAi(
    OPENAI_KEY,
);


$loader = new FilesystemLoader('/.*.php/');
$documents = $loader->load($filepath);

$extractor = (new DocumentTransformationBuilder(new DoctranConfig($openAI, 'gpt-4', 4000)))
    ->extract([
        new ExtractProperty(
            'className',
            'string',
            'The class\'s FQN',
        ),
        new ExtractProperty(
            'type',
            'string',
            'The type of the class (interface, class, trait)',
        ),
        new ExtractProperty(
            'extends',
            'string',
            'The name of the extended class.',
        ),
        new ExtractProperty(
            'docBlock',
            'string',
            'The docblock above the class definition',
        ),
        new ExtractProperty(
            'interfaces',
            'array',
            'A list of FQN names of all implemented interfaces.',
            items: [
                'type' => 'string',
                'description' => 'The FQN of the implemented interface',
            ]
        ),
        new ExtractProperty(
            'imports',
            'array',
            'List of all imports inside the file',
            items: [
                'type' => 'string',
                'description' => 'The FQN of the imported file',
            ]
        ),
        new ExtractProperty(
            'methods',
            'array',
            'A List of all method signatures',
            items: (new FunctionParameters())
                ->add(new ExtractProperty(
                    'name',
                    'string',
                    'The name of the method',
                ))
                ->add(new ExtractProperty(
                    'signature',
                    'string',
                    'The method signature',
                ))
                ->add(new ExtractProperty(
                    'docBlock',
                    'string',
                    'The docblock describing the method',
                ))
                ->toArray()
        )
    ]);

var_dump($extractor->execute($documents));exit;








