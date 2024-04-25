<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Document\Product;
use App\Exception\DecodeJsonException;
use App\Exception\FetchJsonException;
use App\Service\ProductService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Input\InputArgument;

class ImportJsonToDbCommand extends Command
{
    private const JSON_FILE_PATH = __DIR__ . '/../Data/amazon.json';

    public function __construct(private ProductService $productService)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:import-json-to-db')
            ->setDescription('Import json and stores it in DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        try {
            $data = $this->fetchAndDecodeJson(self::JSON_FILE_PATH);
            $this->processProducts($data);
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
            throw new FetchJsonException();
        }

        $data = json_decode($json, true);
        if ($data === null) {
            throw new DecodeJsonException();
        }

        return $data;
    }

    private function processProducts(array $data): void
    {
        foreach ($data['SearchResult']['Items'] as $item) {
            $asin = $item['ASIN'];
            if (!$this->productService->productExists($asin)) {
                $this->importProduct($item);
            }
        }
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

        $this->productService->saveProduct($product);
    }
}