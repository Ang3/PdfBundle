<?php

namespace Ang3\Bundle\PdfBundle\Controller;

use Ang3\Bundle\PdfBundle\Response\PdfResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * PDF controller trait.
 *
 * @author Joanis ROUANET
 */
trait PdfControllerTrait
{
    /**
     * Creates a PDF response from HTML.
     *
     * @param string      $html
     * @param string|null $fileName
     * @param string      $contentDisposition
     * @param int         $status
     * @param array       $headers
     *
     * @return PdfResponse
     */
    public function createPdfResponseFromHtml($html, $fileName = null, $contentDisposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT, $status = 200, $headers = [])
    {
        // Création du fichier PDF et récupération du chemin local
        $pdfFile = $this->get('ang3_pdf.factory')->createFromHtml($html);

        // Création de la réponse
        $response = $this->createPdfResponseFromBinaries(
            file_get_contents($pdfFile),
            $fileName,
            $contentDisposition,
            $status,
            $headers
        );

        // Suppression du fichier PDF
        $this->get('filesystem')->remove($pdfFile);

        // Retour de la réponse
        return $response;
    }

    /**
     * Creates a PDF response from PDF data.
     *
     * @param string      $binaries
     * @param string|null $fileName
     * @param string      $contentDisposition
     * @param int         $status
     * @param array       $headers
     *
     * @return PdfResponse
     */
    public function createPdfResponseFromBinaries($binaries, $fileName = null, $contentDisposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT, $status = 200, $headers = [])
    {
        return new PdfResponse(
            $binaries,
            $fileName,
            $contentDisposition,
            $status,
            $headers
        );
    }
}
