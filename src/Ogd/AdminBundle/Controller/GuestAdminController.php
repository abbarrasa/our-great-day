<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\ImportType;
use AdminBundle\Import\Importer;
use AppBundle\Service\Uploader\FileUploader;
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
                $fileName = $fileUploader->upload($file, false);
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
                    $headers     = reset($rows);
                    $rows        = array_slice($rows, 1, null, true);
                    //Filter repeated data                    
                    $rows        = array_unique($rows, SORT_REGULAR);
                    $result      = Importer::import($this->admin, $rows, $headers);

                    $this->addFlash('sonata_flash_success', sprintf('(%s) Registros importados', $result->getCount()));
                    if ($result->hasErrors()) {
                        $this->addFlash('sonata_flash_error', sprintf(
                            'No se ha podido importar: %s', $result->formattedErrors($this->get('twig'))
                        ));
                        
                        return new RedirectResponse($this->admin->generateUrl('import'));
                    }

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
}
