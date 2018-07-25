<?php

namespace AdminBundle\Import;

class ImportResult
{
    /** @var  integer */
    private $count;

    /** @var  array */
    private $errors;

    /**
     * ImportResult constructor.
     * @param null $count
     * @param array|null $errors
     */
    public function __construct($count = null, array $errors = null)
    {
        $this->setCount($count);
        $this->setErrors($errors);
    }

    /**
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param integer $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Checks if it has errors
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->getErrors()) > 0;
    }

    /**
     * Gets formatted errors.
     * @param \Twig_Environment $environment
     * @param $view
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function formattedErrors(\Twig_Environment $environment, $view)
    {
        return $environment->render($view, ['errors' => $this->getErrors()]);
    }
}