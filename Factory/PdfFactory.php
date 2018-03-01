<?php

namespace Ang3\Bundle\PdfBundle\Factory;

use Ang3\Component\Filesystem\File\TemporaryFile;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Joanis ROUANET
 */
class PdfFactory
{
    /**
     * Sumfony kernel.
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     * Constructor of the PDF.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Generates a PDF from an URL and returns data as binaries.
     *
     * @param string      $url
     * @param string|null $pdfPath
     *
     * @return string
     */
    public function createFromUrl($url, $pdfPath = null)
    {
        // Lancement de l'application en mode console
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        // Récupération du chemin cible du pdf
        $target = $pdfPath;

        // Si pas de chemin pour le PDF
        if (!$target) {
            // Création d'un fichier temporaire
            $tmpFile = new TemporaryFile();

            // Définition du chemin du pdf selon le chemin du fichier temporaire
            $target = $tmpFile->getRealPath();
        }

        // Configuration de la commande à lancer
        $input = new ArrayInput(array(
           'command' => 'ang3:pdf:generate',
           'url' => (string) $url,
           'target' => (string) $target,
           '-vvv',
        ));

        // Lancement de la commande
        $output = new NullOutput();
        $application->run($input, $output);

        // Récupération des données binaires du PDF
        $binaries = file_get_contents($target);

        // Retour des données du fichier
        return $binaries;
    }

    /**
     * Creates a PDF from html content.
     *
     * @param string      $html
     * @param string|null $pdfPath
     *
     * @return string
     */
    public function createFromHtml($html, $pdfPath = null)
    {
        // Création d'un fichier temporaire
        $tmpFile = new TemporaryFile($html);

        // Retour des données binaires
        return $this->generatePdfFromUrl(sprintf('file://%s', $tmpFile->getRealPath()), $pdfPath);
    }
}
