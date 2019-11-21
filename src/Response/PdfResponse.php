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
    public function __construct(string $content, string $fileName = null, string $contentDisposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT, int $status = 200, array $headers = [])
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
        $fileName = $fileName ?: uniqid('pdf_').date('Ymd_His').'.pdf';

        // Enregistrement des entêtes
        $this->headers->add(['Content-Type' => 'application/pdf']);
        $this->headers->add(['Content-Disposition' => $this->headers->makeDisposition($contentDisposition, $fileName)]);
    }
}
