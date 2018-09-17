<?php

namespace Ang3\Bundle\PdfBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * @author Joanis ROUANET
 */
class MergeCommand extends ContainerAwareCommand
{
    /**
     * Configuration de la commande.
     */
    protected function configure()
    {
        $this
            ->setName('ang3:pdf:merge')
            ->setDescription('Generation of pdf file - Usage : ang3:pdf:merge <files> [--target|-t pdf_file]')
            ->setHelp('This command merges PDF files to an unique PDF file.')
            ->addOption('pdfunite-path', 'p', InputOption::VALUE_OPTIONAL, 'Path of pdfunite executable (default: /usr/bin/pdfunite).')
            ->addArgument('files', InputArgument::REQUIRED, 'URL\'s of PDF files')
            ->addArgument('target', InputArgument::REQUIRED, 'Location of merged PDF file')
        ;
    }

    /**
     * Execution de la commande.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Récupération du style symfony
        $io = new SymfonyStyle($input, $output);

        // Récupération du chemin du fichier HTML
        $files = $input->getArgument('files');

        // Récupération du chemin du PDF
        $target = $input->getArgument('target');

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $io->text("Generating merged PDF in $target...");
        }

        // Définition du chemin de google chrome
        $pdfunitePath = $input->getOption('pdfunite-path') ?: $this->getContainer()->getParameter('ang3_pdf.parameters')['pdfunite_path'];

        // Génération de la commande
        $command = sprintf('%s %s %s', $pdfunitePath, $files, $target);

        // Génération du processus
        $process = new Process($command);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            $io->error('pdfunite error : '.$e->getMessage());

            return 1;
        }

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $io->success('PDF merged successfully');
        }
    }
}
