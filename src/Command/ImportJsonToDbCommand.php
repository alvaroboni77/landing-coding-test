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
    public function __construct(private DocumentManager $documentManager)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:import-json-to-db')
            ->setDescription('Import json and stores it in DB')
            ->addArgument('url', InputArgument::REQUIRED, 'URL of the JSON file');
    }

    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $jsonUrl = $input->getArgument('url');

        try {
            $data = $this->fetchAndDecodeJson($jsonUrl);
            $this->processProducts($data);
            $this->documentManager->flush();
            $output->writeln('Data successfully saved.');

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