<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\ImportType;
use AdminBundle\Import\Importer;
use AdminBundle\Import\Reader\TableImportReader;
use AdminBundle\Import\Storage\TableImportStorage;
use AppBundle\Service\FileUploader;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class TableAdminController extends Controller
{
    /**
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
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
                    $importReader  = new TableImportReader();
                    $importStorage = new TableImportStorage($this->admin);
                    $importer      = new Importer($importReader, $importStorage);
                    $result        = $importer->import($fileName);

                    $this->addFlash('sonata_flash_success', sprintf('(%s) Registros importados', $result->getCount()));
                    if ($result->hasErrors()) {
                        $this->addFlash('sonata_flash_error', sprintf(
                            'No se ha podido importar: %s',
                            $result->formattedErrors($this->get('twig'), '@Admin/partials/import-errors.html.twig')
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

        return $this->renderWithExtraParams('@Admin/table/import.html.twig', [
            'action' => 'import',
            'form' => $form->createView()
        ]);
    }
}