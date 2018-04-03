<?php

namespace AdminBundle\Import\Strategy;


interface ImportStrategy
{
    public function validateData(array $headers, array $rows);

    public function import(array $headers, array $rows);

}
