<?php

namespace Ang3\Bundle\PdfBundle\Response;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * A PDF response.
 *
 * @author Joanis ROUANET
 */
class PdfResponse extends Response
{
    /**
     * Constructor of the response.
     *
     * @param string $content
     * @param string $fileName
     * @param string $contentDisposition
     * @param int    $status
     * @param array  $headers
     */
    public function __construct($content, $fileName = null, $contentDisposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT, $status = 200, $headers = [])
    {
        // Hydratation des directives possibles
        $contentDispositionDirectives = [
            ResponseHeaderBag::DISPOSITION_INLINE,
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        ];

        // Si la disposition n'est pas autorisée
        if (!in_array($contentDisposition, $contentDispositionDirectives)) {
            throw new InvalidArgumentException(sprintf('Expected one of the following directives: "%s", but "%s" given.', implode('", "', $contentDispositionDirectives), $contentDisposition));
        }

        // Construction de la réponse de base
        parent::__construct($content, $status, $headers);

        // Définition du nom du fichier PDF
        $fileName = $fileName ?: uniqid('pdf_').time().'.pdf';

        // Enregistrement des entêtes
        $this->headers->add(['Content-Type' => 'application/pdf']);
        $this->headers->add(['Content-Disposition' => $this->headers->makeDisposition($contentDisposition, $fileName)]);
    }
}
