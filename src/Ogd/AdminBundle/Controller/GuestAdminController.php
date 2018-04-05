<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Guest;
use AdminBundle\Form\ImportType;
use AppBundle\Import\Importer;
use AppBundle\Service\FileUploader;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class GuestAdminController extends Controller
{
    /**
     * View https://phpspreadsheet.readthedocs.io/en/develop/topics/reading-files/
     *
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function importAction(Request $request, FileUploader $fileUploader)
    {
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $file     = $form->get('file')->getData();
                $fileName = $this->path($fileUploader->getTargetDirectory()) . $fileUploader->upload($file);
                try {
                    /* Identify the type of $fileName  */
                    $fileType = IOFactory::identify($fileName);
                    /* Create a new Reader of the type that has been identified  */
                    $reader = IOFactory::createReader($fileType);
                    /* Advise the Reader that we only want to load cell data  */
                    $reader->setReadDataOnly(true);
                    /* Load $fileName to a Spreadsheet Object  */
                    $spreadsheet = $reader->load($fileName);
                    $worksheet   = $spreadsheet->getActiveSheet();
                    $rows        = $worksheet->toArray();
                    $headers     = array_shift($rows);
                    //Filter repeated data                    
                    $rows        = array_unique($rows, SORT_REGULAR);
                    $count       = Importer::import($this->admin, $rows, $headers);

                    $this->addFlash('sonata_flash_success', sprintf('(%s) Registros importados', $count));

                    return new RedirectResponse($this->admin->generateUrl('list'));
                } catch(Exception $e) {
                    die('Error loading file: '.$e->getMessage());
                }
            } else {
                $this->addFlash('sonata_flash_error', 'Se ha producido un error durante la importación de invitados');
            }
        }

        return $this->renderWithExtraParams('@Admin/guest/import.html.twig', [
            'action' => 'import',
            'form' => $form->createView()
        ]);
    }

    private function path($path)
    {
        if (is_dir($path)) {
            if (substr($path, -1) !== DIRECTORY_SEPARATOR) {
                return $path . DIRECTORY_SEPARATOR;
            }
        }

        return $path;
    }
}
