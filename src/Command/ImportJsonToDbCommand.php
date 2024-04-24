<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Document\Product;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Input\InputArgument;

class ImportJsonToDbCommand extends Command
{
    private const DEFAULT_JSON_URL  = 'https://file.notion.so/f/f/5f4961f1-3ce8-4cbb-9a67-ec3baf081ee7/1ed10d1f-fd07-4cfd-9c23-35d3d765ffd5/amazon.json?id=6b00230f-b9c5-4cf0-97ee-3a954baa361b&table=block&spaceId=5f4961f1-3ce8-4cbb-9a67-ec3baf081ee7&expirationTimestamp=1713952800000&signature=GpKzbCEqwVyJZ5dkN-zhLmvB9D6FVUkRhYCPY-LTMLM&downloadName=amazon.json';

    public function __construct(private DocumentManager $documentManager)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:import-json-to-db')
            ->setDescription('Import json and stores it in DB')
            ->addArgument('url', InputArgument::OPTIONAL, 'URL of the JSON file', self::DEFAULT_JSON_URL);
    }

    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $jsonUrl = $input->getArgument('url');

        try {
            $data = $this->fetchAndDecodeJson($jsonUrl);
            $this->processProducts($data);
            $this->documentManager->flush();
            $output->writeln('Data successfully saved in MongoDB.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error importing data: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }

    private function fetchAndDecodeJson(string $url): array
    {
        $json = file_get_contents($url);
        if ($json === false) {
            throw new \Exception('Failed to fetch JSON from URL');
        }

        $data = json_decode($json, true);
        if ($data === null) {
            throw new \Exception('Error decoding JSON');
        }

        return $data;
    }

    private function processProducts(array $data): void
    {
        foreach ($data['SearchResult']['Items'] as $item) {
            $asin = $item['ASIN'];
            if (!$this->productExists($asin)) {
                $this->importProduct($item);
            }
        }
    }

    private function productExists(string $asin): bool
    {
        return $this->documentManager->getRepository(Product::class)->findOneBy(['asin' => $asin]) !== null;
    }

    private function importProduct(array $item): void
    {
        $product = new Product();
        $product->setAsin($item['ASIN']);
        $product->setTypeId($item['BrowseNodeInfo']['BrowseNodes'][0]['Id']);
        $product->setImageUrl($item['Images']['Primary']['Large']['URL']);
        $product->setBrand($item['ItemInfo']['ByLineInfo']['Brand']['DisplayValue']);
        $product->setFeatures($item['ItemInfo']['Features']['DisplayValues']);
        $product->setTitle($item['ItemInfo']['Title']['DisplayValue']);
        $product->setIsFreeShippingEligible($item['Offers']['Listings'][0]['DeliveryInfo']['IsFreeShippingEligible']);
        $product->setDiscount($item['Offers']['Listings'][0]['Price']['Savings']['Percentage']);
        $product->setSalesRank($item['BrowseNodeInfo']['BrowseNodes'][0]['SalesRank']);

        $this->documentManager->persist($product);
    }
}