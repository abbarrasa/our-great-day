<?php

namespace AppBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class GuestAdminController extends Controller
{
    /**
     * View https://phpspreadsheet.readthedocs.io/en/develop/topics/reading-files/
     *
     * @param Request $request
     */
    public function importAction(Request $request)
    {
        $inputFileName = './sampleData/example1.xls';
        try {
            /**  Identify the type of $inputFileName  **/
            $inputFileType = IOFactory::identify($inputFileName);
            /**  Create a new Reader of the type that has been identified  **/
            $reader = IOFactory::createReader($inputFileType);
            /**  Advise the Reader that we only want to load cell data  **/
            $reader->setReadDataOnly(true);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($inputFileName);

            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
        } catch(Exception $e) {
            die('Error loading file: '.$e->getMessage());
        }
    }
}