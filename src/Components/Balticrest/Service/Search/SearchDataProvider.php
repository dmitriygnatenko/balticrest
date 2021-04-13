<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Search;

use App\Components\Balticrest\Service\Mapper\SearchRecordDTOMapper;
use App\Repository\SearchDataRepository;
use App\Repository\SearchLogRepository;

class SearchDataProvider implements SearchDataProviderInterface
{
    /** @var int */
    private const MIN_CHARS_IN_WORD = 3;

    /** @var int */
    private const MAX_RESULTS_COUNT = 30;

    private SearchDataRepository $searchDataRepository;

    private SearchLogRepository $searchLogRepository;

    private SearchRecordDTOMapper $searchRecordDTOMapper;

    /**
     * @param SearchDataRepository $searchDataRepository
     * @param SearchLogRepository $searchLogRepository
     * @param SearchRecordDTOMapper $searchRecordDTOMapper
     */
    public function __construct(
        SearchDataRepository $searchDataRepository,
        SearchLogRepository $searchLogRepository,
        SearchRecordDTOMapper $searchRecordDTOMapper
    ) {
        $this->searchDataRepository = $searchDataRepository;
        $this->searchLogRepository = $searchLogRepository;
        $this->searchRecordDTOMapper = $searchRecordDTOMapper;
    }

    /**
     * @param string $searchText
     *
     * @return array
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function search(string $searchText): array
    {
        $searchText = $this->prepare($searchText);

        if ($searchText === '') {
            return [];
        }

        $results = $this->searchDataRepository->search($searchText, self::MAX_RESULTS_COUNT);

        $this->searchLogRepository->saveLog($searchText, count($results));

        return $this->searchRecordDTOMapper->fillAll($results);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function prepare(string $text): string
    {
        if (mb_strlen($text) > 255) {
            $text = mb_substr($text, 0, 255);
        }

        $wordsArray = explode(' ', trim($text));

        $wordsArray = array_filter($wordsArray, static function ($word) {
            $word = preg_replace("/[^a-zA-ZА-Яа-я0-9\s]/u", '', $word);

            return mb_strlen($word) >= self::MIN_CHARS_IN_WORD;
        });

        $wordsArray = array_map(static function ($word) {
            return '+' . $word;
        }, $wordsArray);

        $text = implode(' ', $wordsArray);

        return mb_strlen($text) >= self::MIN_CHARS_IN_WORD ? $text : '';
    }
}