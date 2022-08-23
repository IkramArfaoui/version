<?php

namespace Alteis\Bundle\VersionBundle\Command;

use Alteis\Bundle\VersionBundle\Entity\VersionApp;
use Alteis\Bundle\VersionBundle\Exception\VersionAlreadyExists;
use Alteis\Bundle\VersionBundle\Repository\VersionAppRepository;
use Alteis\Bundle\VersionBundle\Writer\VersionWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NewVersionCommand extends Command implements ContainerAwareInterface {

    /** @var ContainerInterface $container */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function configure() {

        $this->setName('version:new')
            ->setDescription('Permet de d\'initialiser une nouvelle version de l\'applcation')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Indique le numéro de la version à créer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Création d\'une nouvelle version');
        
        if($input->getOption('name')){
            $nameVersion = $input->getOption('name');
        }
        else {
            $question = new Question('Quel est le numéro de la version ?');
            $nameVersion = $io->askQuestion($question);
        }

        /** @var VersionAppRepository $repository */
        $repository = $this->container->get('doctrine')->getRepository(VersionApp::class);
        
        $versionDB = $repository->findOneBy(array('name'=>$nameVersion));

        if($versionDB instanceof VersionApp){
            throw new VersionAlreadyExists("La version $nameVersion existe déjà en base de données");
        }

        /** @var VersionWriter $writer */
        $writer = $this->container->get('alteis.version.writer');
        $writer->write($nameVersion);

        $io->note('Fichier mis à jour');

        $date = new \Datetime();

        // On met à jour la version locale
        $versionApp = new VersionApp();
        $versionApp->setName($nameVersion);
        $versionApp->setDateInitialisation($date);

        $io->note('Base de données locale mise à jour');

        $this->container->get('doctrine')->getManager()->persist($versionApp);
        $this->container->get('doctrine')->getManager()->flush();

        // On récupère le contenu pour y intégrer notre requête
        $dateFormatted = $date->format('Y-m-d');
        $request = '$this->addSql("INSERT INTO version_app (name, date_initialisation) VALUES (\''.$nameVersion.'\', \''.$dateFormatted.'\')");';

        $outputSubCommand = new BufferedOutput();
        $inputSubCommand = new ArrayInput(array('command' => 'doctrine:migrations:generate'));
        $command = $this->getApplication()->find('doctrine:migrations:generate');
        $command->run($inputSubCommand, $outputSubCommand);

        $content = $outputSubCommand->fetch();
        $outputParts = explode('to ', $content);
        $file = $outputParts[1];
        $file = str_replace('"', '', $file);
        $file = str_replace("\\", "/", $file);
        $file = trim($file);

        // On récupère le contenu
        $content = file_get_contents($file);
        $contents = explode( '// this up() migration is auto-generated, please modify it to your needs', $content);
        $end = $request . $contents[1];
        $contentFinal = $contents[0] . $end;

        file_put_contents($file, $contentFinal);
        $io->note('Migration correspondante créée ' . $file);

        $io->success('Nouvelle version créée : ' .$nameVersion);
    }
}
