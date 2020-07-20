<?php

namespace DoSystem\Domain\Car\Model;

use DoSystem\Module\Domain\Model\ValueObjectStringInterface;
use DoSystem\Module\Domain\Model\ValueObjectTrait;

class CarValueVin implements ValueObjectStringInterface
{
    use ValueObjectTrait;

    /**
     * @var string
     */
    private $transportBureau;
    private $classCode;
    private $character;
    private $serialNumber;

    /**
     * Acceptable string pattern
     *
     * @var string
     */
    private static $pattern;

    /**
     * Transport bureaus list e.g. '品川'
     *
     * @see https://www.airia.or.jp/info/number/02.html
     * @var string[]
     */
    private static $transportBureauList = [
        // 北海道
        '札幌', '函館', '室蘭', '帯広', '釧路',
        '北見', '旭川',
        // 東北
        '宮城', '福島', 'いわき', '岩手',
        '青森', '八戸', '山形', '庄内', '秋田',
        // 関東
        '品川', '足立', '練馬', '多摩', '八王子',
        '横浜', '相模', '川崎', '湘南', '千葉',
        '習志野', '袖ヶ浦', '野田', '大宮', '熊谷',
        '所沢', '春日部', '水戸', '土浦', '群馬',
        '栃木', '佐野', '山梨',
        // 北陸信越
        '新潟', '長岡', '長野', '松本', '石川', '富山',
        // 中部
        '名古屋', '西三河', '小牧', '豊橋', '静岡',
        '浜松', '沼津', '岐阜', '飛騨', '三重', '福井',
        // 近畿
        '大阪', '和泉', 'なにわ', '京都', '神戸',
        '姫路', '滋賀', '奈良', '和歌山',
        // 中国
        '広島', '福山', '鳥取', '島根', '岡山', '山口',
        // 四国
        '香川', '徳島', '愛媛', '高知',
        // 九州
        '福岡', '北九州', '久留米', '筑豊', '佐賀',
        '長崎', '佐世保', '厳原', '熊本', '大分',
        '宮崎', '鹿児島', '大島',
        // 沖縄
        '沖縄', '宮古', '八重山',

        // ご当地ナンバー
        '盛岡', '平泉', '仙台', '会津', '郡山', 'つくば',
        '那須', '高崎', '前橋', '成田', '柏', '川口',
        '越谷', '川越', '杉並', '世田谷', '富士山', '金沢',
        '諏訪', '伊豆', '岡崎', '豊田', '一宮', '春日井',
        '鈴鹿', '堺', '倉敷', '下関', '奄美',
    ];

    /**
     * Class code pattern e.g. '88'
     */
    private static $classCodePattern = '[0-9]{1,3}';

    /**
     * Character list e.g. 'そ'
     *
     * @see https://www.airia.or.jp/info/number/01.html
     * @var string[]
     */
    private static $characterList = [
        'あ', 'い', 'う', 'え', 'か', 'き', 'く', 'け', 'こ', 'を',
        'さ', 'す', 'せ', 'そ', 'た', 'ち', 'つ', 'て', 'と', 'な',
        'に', 'ぬ', 'ね', 'の', 'は', 'ひ', 'ふ', 'ほ', 'ま', 'み',
        'む', 'め', 'も', 'や', 'ゆ', 'ら', 'り', 'る', 'ろ',
        'わ', 'れ',
        'E', 'H', 'K', 'M', 'T', 'Y', 'よ',
    ];

    /**
     * Serial number pattern e.g. '5555'
     */
    private static $serialNumberPattern = '0|[1-9][0-9]{0,3}';

    /**
     * Constructor
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $pattern = self::getRegexPattern();
        if (!\preg_match("/{$pattern}/", $value, $matches)) {
            throw new \Exception();
        }
        $this->transportBureau = $matches[1];
        $this->classCode = $matches[2];
        $this->character = $matches[3];
        $this->serialNumber = $matches[4];
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->getTransportBureau() . $this->getClassCode() . $this->getCharacter() . $this->getSerialNumber();
    }

    /**
     * @return string
     */
    public function getTransportBureau(): string
    {
        return $this->transportBureau;
    }

    /**
     * @return string
     */
    public function getClassCode(): string
    {
        return $this->classCode;
    }

    /**
     * @return string
     */
    public function getCharacter(): string
    {
        return $this->character;
    }

    /**
     * @return string
     */
    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    /**
     * @param mixed $valueObject
     * @return bool
     */
    public function equals($valueObject): bool
    {
        return $valueObject instanceof static
            && $this->getTransportBureau() === $valueObject->getTransportBureau()
            && $this->getClassCode() === $valueObject->getClassCode()
            && $this->getCharacter() === $valueObject->getCharacter()
            && $this->getSerialNumber() === $valueObject->getSerialNumber()
            && \get_called_class() === \get_class($valueObject);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isValid($value): bool
    {
        if (!\is_string($value)) {
            return false;
        }
        if (!$value) {
            return false;
        }
        $pattern = self::getRegexPattern();
        return (bool) \preg_match("/{$pattern}/", $value);
    }

    /**
     * @static
     *
     * @return string Regex pattern
     */
    public static function getRegexPattern(): string
    {
        if (!self::$pattern) {
            self::$pattern  = '^';
            self::$pattern .= '(' . \implode('|', self::$transportBureauList) . ')';
            self::$pattern .= '(' . self::$classCodePattern . ')';
            self::$pattern .= '(' . \implode('|', self::$characterList) . ')';
            self::$pattern .= '(' . self::$serialNumberPattern . ')';
            self::$pattern .= '$';
        }
        return self::$pattern;
    }
}
