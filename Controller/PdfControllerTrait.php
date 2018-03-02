<?php

namespace Ang3\Bundle\PdfBundle\Controller;

use Exception;
use SplFileInfo;
use Ang3\Bundle\PdfBundle\Response\PdfResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\File;

/**
 * PDF controller trait.
 *
 * @author Joanis ROUANET
 */
trait PdfControllerTrait
{
    /**
     * Creates a PDF response from a file.
     *
     * @param File|string $pdfFile
     * @param string|null $fileName
     * @param string      $contentDisposition
     * @param int         $status
     * @param array       $headers
     *
     * @return PdfResponse
     */
    public function createPdfResponseFromFile($pdfFile, $fileName = null, $contentDisposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT, $status = 200, $headers = [])
    {
        // Si le fichier n'est pas une instance de fichier Symfony
        if ($pdfFile instanceof File) {
            // Construction d'un fichier symfony
            $pdfFile = new File((string) $pdfFile);
        }

        // Si le fichier n'est pas lisible
        if (!$pdfFile->isReadable()) {
            throw new Exception('Unable to create a PDF response because the file "%s" is not readable.');
        }

        return $this->createPdfResponseFromBinaries(
            file_get_contents($pdfFile instanceof SplFileInfo ? $pdfFile->getRealPath() : (string) $pdfFile),
            $fileName,
            $contentDisposition,
            $status,
            $headers
        );
    }

    /**
     * Creates a PDF response from PDF data.
     *
     * @param string      $data
     * @param string|null $fileName
     * @param string      $contentDisposition
     * @param int         $status
     * @param array       $headers
     *
     * @return PdfResponse
     */
    public function createPdfResponseFromBinaries($data, $fileName = null, $contentDisposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT, $status = 200, $headers = [])
    {
        return new PdfResponse((string) $data, $fileName, $contentDisposition, $status, $headers);
    }
}
