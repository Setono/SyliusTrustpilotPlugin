<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Command;

use Setono\SyliusTrustpilotPlugin\Schema\Review;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;

class FetchReviewsCommand extends Command
{
    protected static $defaultName = 'setono:sylius-trustpilot:fetch-reviews';

    /** @var string|null */
    protected static $defaultDescription = 'Will fetch reviews and save them locally';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = Client::createChromeClient();
        $crawler = $client->request('GET', 'https://dk.trustpilot.com/review/www.2trendy.dk');
        $crawler = new Crawler($crawler->html());

        /** @var \DOMElement $element */
        foreach ($crawler->filterXPath('//script[@type="application/ld+json"]') as $element) {
            $data = json_decode($element->textContent, true, 512, JSON_THROW_ON_ERROR);
            $reviews = $this->extractReviews($data);

            dump($reviews);
        }

        return 0;
    }

    /**
     * @return list<Review>
     */
    private function extractReviews(array $data): array
    {
        if(!isset($data['@graph']) || !is_array($data['@graph'])) {
            return [];
        }

        $reviews = [];

        foreach ($data['@graph'] as $idx => $object) {
            if(!is_int($idx) || !is_array($object)) {
                return [];
            }

            if(!isset($object['@type']) || $object['@type'] !== 'Review') {
                continue;
            }

            $reviews[] = Review::fromSchema($object);
        }

        return $reviews;
    }
}
