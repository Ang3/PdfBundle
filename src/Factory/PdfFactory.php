<?php

namespace Ang3\Bundle\PdfBundle\Factory;

use InvalidArgumentException;
use RuntimeException;
use Neutron\TemporaryFilesystem\TemporaryFilesystem;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
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
     * Symfony filesystem component.
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Sumfony kernel.
     *
     * @var TemporaryFilesystem
     */
    private $temporaryFilesystem;

    /**
     * Constructor of the PDF.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->filesystem = new Filesystem();
        $this->temporaryFilesystem = TemporaryFilesystem::create();
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

        // Définition de la cible
        $target = $pdfPath ?: $this->temporaryFilesystem->createTemporaryFile('pdf_', null, 'pdf');

        // Configuration de la commande à lancer
        $input = new ArrayInput(array(
           'command' => 'ang3:pdf:generate',
           'url' => (string) $url,
           'target' => (string) $target,
           '-vvv',
        ));

        // Lancement de la commande
        $output = new NullOutput();
        $result = $application->run($input, $output);

        // Si la commande a échoué
        if($result != 0) {
            throw new RuntimeException(sprintf('Unable to create PDF - Error code: %d', $result));
        }

        // Retour du chemin local du fichier PDF
        return $target;
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
        // Création du fichier html temporaire
        $htmlFile = $this->temporaryFilesystem->createTemporaryFile('html_', null, 'html');

        // Enregistrement du contenu du fichier HTML
        $this->filesystem->dumpFile($htmlFile, $html);

        // Création du fichier PDF selon l'URL du fichier HTML
        $pdfFile = $this->createFromUrl(sprintf('file://%s', $htmlFile), $pdfPath);

        // Supression du fichier HTML
        $this->filesystem->remove($htmlFile);

        // Retour du chemin du fichier PDF
        return $pdfFile;
    }

    /**
     * Merges all pdf files and creates an unique PDF to target URL.
     * 
     * @param  array  $pdfFiles
     * @param  string $target
     *
     * @throws InvalidArgumentException When PDF file(s) was not found.
     * 
     * @return string
     */
    public function merge(array $pdfFiles, $target)
    {
        // Initialisation des fichiers introuvables éventuels
        $filesNotFound = [];

        // Pour chaque fichier PDF
        foreach($pdfFiles as $key => $pdfFile) {
            // Retrait de tous les espaces en trop dans le chemin
            $pdfFile = trim($pdfFile);

            // Si le fichier n'existe pas
            if(!$this->filesystem->exists($pdfFile)) {
                // Enregistrement de l'URL du fichier introuvable
                $filesNotFound[] = sprintf('"$pdfFile"');
            }
        }

        // Si on a un/des fichier(s) non trouvé(s)
        if(count($filesNotFound) > 0) {
            throw new InvalidArgumentException(sprintf('Unable to find PDF file(s) %s', implode(',', $filesNotFound)));
        }

        // Lancement de l'application en mode console
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        // Définition de la cible
        $target = $target ?: $this->temporaryFilesystem->createTemporaryFile('pdf_', null, 'pdf');

        // Configuration de la commande à lancer
        $input = new ArrayInput(array(
           'command' => 'ang3:pdf:merge',
           'files' => explode(',', $pdfFiles),
           'target' => (string) $target,
           '-vvv',
        ));

        // Lancement de la commande
        $output = new NullOutput();
        $application->run($input, $output);

        // Retour du chemin du fichier PDF
        return $target;
    }
}
