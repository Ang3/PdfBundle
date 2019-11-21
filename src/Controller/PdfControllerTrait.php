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
     * Creates a PDF response from content.
     *
     * @return PdfResponse
     */
    public function createPdfResponseFromHtml(string $content, string $fileName = null, string $contentDisposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT, int $status = 200, array $headers = [])
    {
        // Création du fichier PDF et récupération du chemin local
        $pdfFile = $this->get('ang3_pdf.factory')->createFromHtml($content);

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
     * Creates a PDF response from PDF binary data.
     *
     * @return PdfResponse
     */
    public function createPdfResponseFromBinaries(string $binaries, string $fileName = null, string $contentDisposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT, int $status = 200, array $headers = [])
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
