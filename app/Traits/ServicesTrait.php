<?php

namespace App\Traits;

trait ServicesTrait
{
    public function fieldsFiles($parameters)
    {
        $data =    [];
        foreach ($parameters as $key => $value) {
            if ($key != '_token' && $key != '_method') {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}
