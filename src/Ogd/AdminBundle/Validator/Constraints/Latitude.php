<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Latitude extends Constraint
{
    public $message = 'The string "{{ string }}" should adhere to ISO 6709 ex. +40.20361.';
}