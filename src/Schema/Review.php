<?php
declare(strict_types=1);


namespace Setono\SyliusTrustpilotPlugin\Schema;


use DateTimeImmutable;

final class Review
{
    /**
     * This is the Trustpilot id for the review, i.e. 630f5d327f7a8621ee4e8396
     */
    public string $id;

    /**
     * The name of the review author
     */
    public string $author;

    public DateTimeImmutable $publishedAt;

    public string $headline;

    public string $body;

    /**
     * A number from 1-5
     */
    public int $rating;

    /**
     * Examples: da, en, de
     */
    public string $language;

    public function __construct(
        string $id,
        string $author,
        DateTimeImmutable $publishedAt,
        string $headline,
        string $body,
        int $rating,
        string $language
    ) {
        $this->id = $id;
        $this->author = $author;
        $this->publishedAt = $publishedAt;
        $this->headline = $headline;
        $this->body = $body;
        $this->rating = $rating;
        $this->language = $language;
    }


    public static function fromSchema(array $data): self
    {
        $id = substr(strrchr($data['@id'], '/'), 1);
        $publishedAt = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.vT', $data['datePublished']);

        return new self(
            $id,
            $data['author']['name'],
            $publishedAt,
            $data['headline'],
            $data['reviewBody'],
            (int) $data['reviewRating']['ratingValue'],
            $data['inLanguage']
        );
    }
}
