<?php


namespace frontend\helper;


/**
 * Class Isbn
 * @package frontend\helper
 */
class Isbn
{
    /**
     * @param string $isbn
     * @return bool
     */
    public static function isValid(string $isbn): bool
    {
        $isValid = mb_ereg_match('(\d+-*)+', $isbn);

        return $isValid && (self::isValidIsbn10($isbn) || self::isValidIsbn13($isbn));
    }

    /**
     * @param string $isbn
     * @return bool
     */
    private static function isValidIsbn10(string $isbn)
    {
        $isbn = mb_ereg_replace('[^\d]', '', $isbn);
        $checksumIsbn = mb_substr($isbn, -1, 1);
        $digits = mb_str_split(
            mb_substr($isbn, 0, 9)
        );

        $sum = 0;
        foreach ($digits as $index => $digit) {
            $sum += (10 - $index) * $digit;
        }

        $checksum = 11 - ($sum % 11);

        switch ($checksum) {
            case 10: $checksum = 'X';
                break;
            case 11: $checksum = '0';
                break;
        }

        return $checksum == mb_strtoupper($checksumIsbn);
    }

    /**
     * @param string $isbn
     * @return bool
     */
    private static function isValidIsbn13(string $isbn)
    {
        $isbn = mb_ereg_replace('[^\d]', '', $isbn);
        $checksumIsbn = mb_substr($isbn, -1, 1);
        $digits = mb_str_split(
            mb_substr($isbn, 0, 12)
        );

        $sum = 0;
        foreach($digits as $index => $digit) {
            $sum += ($index % 2) ? $digit * 3 : $digit;
        }

        $checksum = (10 - $sum % 10);

        if ($checksum == 10) {
            $checksum = 0;
        }
        return $checksum == $checksumIsbn;
    }
}