<?php

if (!function_exists('RandomSecurePassword')) {
    function RandomSecurePassword($lower = 5, $upper = 2, $digits = 2, $special_characters = 1): string
    {
        $lower_case = "abcdefghijklmnopqrstuvwxyz";
        $upper_case = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numbers = "1234567890";
        $symbols = "!@#$%^&*";

        $lower_case = str_shuffle($lower_case);
        $upper_case = str_shuffle($upper_case);
        $numbers = str_shuffle($numbers);
        $symbols = str_shuffle($symbols);

        $random_password = substr($lower_case, 0, $lower);
        $random_password .= substr($upper_case, 0, $upper);
        $random_password .= substr($numbers, 0, $digits);
        $random_password .= substr($symbols, 0, $special_characters);

        return str_shuffle($random_password);
    }
}
