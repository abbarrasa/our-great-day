<?php

namespace AdminBundle\Import\Strategy;


interface StrategyInterface
{
    public function validateData(array $headers, array $data);
}