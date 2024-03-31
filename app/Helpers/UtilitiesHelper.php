<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;

class UtilitiesHelper
{
    public static function filterParameters($params, $parametrosPermitidos) {
        $array = array_filter($params, function ($key) use ($parametrosPermitidos) {
            return in_array($key, $parametrosPermitidos);
        }, ARRAY_FILTER_USE_KEY);

        foreach($array as $column => $value) is_string($value) && $array[$column] = strtolower($value);
        return $array;
    }

    public static function objectToArray($object)
    {
        $array = json_encode($object);
        return json_decode($array, true);
    }

    public static function validateFields($fields)
    {
        $errors = [];
        $count = 0;

        foreach ($fields as $field=>$value){
            if(is_null($value)){
                $errors[$field][] = "The {$field} is required.";
                unset($fields[$field]);
            } else{
                $count++;
            }
        }
        
        $flag = $count > 0;

        return [$errors, $flag, $fields];
    }
}