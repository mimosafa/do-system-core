<?php

namespace DoSystem\Core\Domain\Car\Service;

use DoSystem\Core\Domain\Car\CarRepositoryInterface;
use DoSystem\Core\Domain\Car\CarValueVin;

class VinService
{
    /**
     * @var CarRepositoryInterface
     */
    private $repository;

    /**
     * @var string[]
     */
    private static $search = [
        '-', // 半角ハイフン
        'ー', // 全角ハイフン

        /**
         * @see https://qiita.com/ryounagaoka/items/4cf5191d1a2763667add
         */
        '-', //	U+002D ASCIIのハイフン
        'ー', // U+30FC 全角の長音
        '‐', // U+2010 別のハイフン
        '‑', // U+2011 改行しないハイフン
        '–', // U+2013 ENダッシュ
        '—', // U+2014 EMダッシュ
        '―', // U+2015 全角のダッシュ
        '−', // U+2212 全角のマイナス
        'ｰ', // U+FF70 半角カナの長音

        /**
         * @see http://tomute.hateblo.jp/entry/20130130/1359556272
         */
        '･', // U+FF65 半角中黒
        '・', // U+30FB 全角中黒
        '·', // U+00B7 ラテン文字の中黒
        '•', // U+2022 ビュレット
    ];

    /**
     * Constructor
     *
     * @param CarRepositoryInterface $repository
     */
    public function __construct(CarRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $string
     * @return bool
     */
    public function exists(string $string): bool
    {
        $list = $this->repository->query([
            'vin' => $string,
        ]);
        return !$list->isEmpty();
    }

    /**
     * Format passed string to available vin string
     * If invalid string passed, return empty string
     *
     * @param string $string
     * @return string
     */
    public static function format(string $string): string
    {
        $string = \mb_convert_kana($string, 'as');
        $string = \preg_replace('/( |　)/', '', $string);
        $string = \str_replace(self::$search, '', $string);

        return CarValueVin::isValid($string) ? $string : '';
    }
}
