<?php

namespace App\Action\Helpers;

class RegexIDInArrayAction
{
    /*
     * This action checks whether a given string exists in an array of string patterns,
     * with support for dynamic placeholders such as {id}.
     *
     * Instead of storing every possible string explicitly, you can use {id} in your
     * patterns to represent a variable numeric ID (or, optionally, alphanumeric IDs).
     *
     * The action will automatically convert the {id} placeholder into a regex pattern
     * to match the corresponding segment in the input string.
     *
     * Example:
     *   Pattern in array: '/api/download/competition/{id}/entrants'
     *   Input string:     '/api/download/competition/999/entrants'
     *   Result:           true
     *
     * This allows you to efficiently check against multiple dynamic routes or URLs
     * without needing to hardcode every possible variation.
     */

    public function handle(array $haystack, string $needle): bool
    {
        foreach ($haystack as $pattern) {
            // Escape regex special chars, then replace {id} with \d+ for numbers
            $regex = '#^' . str_replace('\{id\}', '\d+', preg_quote($pattern, '#')) . '$#';

            if (preg_match($regex, $needle)) {
                return true;
            }
        }

        return false;
    }
}
