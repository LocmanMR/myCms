<?php
declare(strict_types=1);


namespace App\Helpers;


use App\Exceptions\ProbabilityException;

class ProbabilityHelper
{
    /**
     * Returns an event based on its probability
     * @param array $data
     * @param string $column
     * @return int
     * @throws ProbabilityException
     */
    public static function getRandomIndex(array $data, string $column = 'probability'): int
    {
        $rand = mt_rand(1, array_sum(array_column($data, $column)));
        $current = $prev = 0;
        for ($index = 0, $count = count($data); $index < $count; ++$index) {
            $prev += $index !== 0 ? $data[$index - 1][$column] : 0;
            $current += $data[$index][$column];
            if ($rand > $prev && $rand <= $current) {
                return $index;
            }
        }

        throw new ProbabilityException('Probability helper failed');
    }
}