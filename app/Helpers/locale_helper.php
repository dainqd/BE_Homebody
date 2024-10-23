<?php

if (!function_exists('locale_helper')) {
    function locale_helper($model, $property, $lang): string
    {
        if (property_exists($model, $property . '_' . $lang)) {
            return $model->{$property . '_' . $lang};
        }
        return $model->$property;
    }
}
