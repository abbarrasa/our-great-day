<?php

namespace AdminBundle\Import\Strategy;


interface ImportStrategy
{
    public function validateData(array $headers, array $data);

    public function import(array $headers, array $data);

}
