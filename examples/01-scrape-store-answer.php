<?php

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
use Thojou\LLMDocuments\Splitter\RecursiveTextSplitter;
use Thojou\LLMDocuments\Storage\VectorStore\LocalVectorStore;
use Thojou\LLMDocuments\Transformation\DocumentTransformationBuilder;
use Thojou\LLMDocuments\ValueObjects\DoctranConfig;
use Thojou\OpenAi\OpenAi;
use Thojou\SimpleApiClient\Adapter\GuzzleClientFactory;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/credentials.php';


// INPUT
$query = $argv[1];
$num = isset($argv[2]) ? (int)$argv[2] : 2;
$threshold = isset($argv[3]) ? (float)$argv[3] : 0.75;


// Define the OpenAI API Interface
$openAI = new OpenAi(
    OPENAI_KEY,
);

// Define the WebSearchLoader
$webSearchLoader = new WebSearchLoader(
    // Use Google API for WebSearch
    new GoogleSearchEngineFactory(
        new Client([
            'application_name' => 'BrAin/1.0',
            'developer_key' => GOOGLE_DEVELOPER_KEY,
        ]),
        SEARCH_ENGINE_ID
    ),
    // Use SpatieCrawler for WebCrawling
    new SpatieCrawlerFactory(
        (new SpatieCrawlerConfig())
            ->setRemoteInstance(gethostbyname('chromium'), 9222)
            ->setEnableJavascript(true)
            ->setNodeBinary('/root/.nvm/versions/node/v18.17.0/bin/node')
            ->setNpmBinary('/root/.nvm/versions/node/v18.17.0/bin/npm')
    ),
    // Use UnstructuredParser for parsing the crawled websites into raw text
    new UnstructuredParserFactory(
        new UnstructuredAPI(
            new GuzzleClientFactory('http://unstructured:8000', 'DemoAI')
        )
    ),
    $num
);

// Define the SimilarityRetriever to store and find similar documents
$retriever = new SimilarityRetriever(
    new LocalVectorStore(
        '/tmp/test.json',
        new OpenAiEmbeddings($openAI, 'text-embedding-ada-002'),
    ),
    $threshold,
    4,
    new RecursiveTextSplitter(
        chunkSize: 512,
        chunkOverlap: 128
    )
);

// Define the DocumentTransformationBuilder to summarize the final context documents
$summarizer = (new DocumentTransformationBuilder(
    new DoctranConfig($openAI, 'gpt-3.5-turbo', 4000)
))->summarize(2000);

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


// Search the Web for the query
echo "Start searching the web for '$query'\n";
$documents = $webSearchLoader->load($query);

foreach($documents as $key => $document) {
    echo "Start summarizing the context\n";
    $documents[$key] = $summarizer->execute($document);
}

echo "Found " . count($documents) . " documents\n";
echo "Start adding documents to the retriever\n";
$retriever->addDocuments($documents);

echo "Start retrieving relevant documents\n";
$contextDocuments = $retriever->getRelevantDocuments($query);

echo "Start combining documents\n";
$document = combineDocuments($contextDocuments);

echo "Start chat with OpenAI\n";
$context = $document->getPageContent();

$messages = [
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'system', 'content' => "Respond to the user's query using only the information provided in the given context. If you lack the necessary information to answer the question, reply with 'I don't know'. Please include at least one source as link in your response."],
        ['role' => 'user',  'content' => "CONTEXT INFORMATION:\n===\n$context\n===\nUSER QUERY: $query\n===\nRESPONSE:"],
    ]
];

echo json_encode($messages, JSON_PRETTY_PRINT) . "\n";

$response = $openAI->chat()->completion($messages);

echo "User QUERY: $query\n";
echo "RESPONSE:\n";
echo $response['choices'][0]['message']['content'] . "\n";






