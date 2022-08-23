<?php

namespace Alteis\Bundle\VersionBundle\Command;
use Alteis\Bundle\VersionBundle\Helper\ReleaseNote;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ReleaseNoteCommand extends Command implements ContainerAwareInterface {

    /** @var ContainerInterface $container */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function configure()
    {
        $this->setName('version:note-service:create')
            ->setDescription('Permet de créer une note de version pour les issues fermées en format (md)')
            ->addArgument('version', InputArgument::OPTIONAL, 'La version du projet.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $releaseNote = new ReleaseNote();

        $path = $this->container->getParameter('note_de_version_path'); //from config.xml
        $baseUrl = $this->container->getParameter('gitlab_url');
        $token = $this->container->getParameter('gitlab_token'); //from parametter.yml
        $projectId = $this->container->getParameter('project_id'); //from parametter.yml
        $dir = $this->container->get('kernel')->getRootDir();

        $params = $releaseNote->getParams($path, $baseUrl, $token, $projectId, $dir);

        try {
            $version = $this->getVersion($input, $output);
            $content = $releaseNote->getApiContent($params, $version);

            if(count($content) == 0){
                $io->warning("Aucune demande trouvée pour la note de version");
            }
            else{
                $releaseNote->generateFile($content, $params->getPath(), $version);
                $io->success('Le fichier a été crée avec succès');
            }

        } catch(\Exception $e) {
            $io->error('Erreur lors de la création du ficher : ' . $e->getMessage());
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function getVersion(InputInterface $input, OutputInterface $output) : string
    {
        $version = $input->getArgument('version');
        if (!$version) {
            $io = new SymfonyStyle($input, $output);
            $version = $io->ask('Veuillez enter la version du projet : ', '');
        }

        return $version;
    }
}
