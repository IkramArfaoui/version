<?php

namespace Alteis\Bundle\VersionBundle\Command;

use Alteis\Bundle\VersionBundle\Entity\VersionApp;
use Alteis\Bundle\VersionBundle\Exception\VersionAlreadyExists;
use Alteis\Bundle\VersionBundle\Exception\VersionDoesntExist;
use Alteis\Bundle\VersionBundle\Repository\VersionAppRepository;
use Alteis\Bundle\VersionBundle\Writer\VersionWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MEPCommand extends Command implements ContainerAwareInterface {

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

        $this->setName('version:mep')
            ->setDescription('Permet de mettre à jour la date de mise en production de la version courante')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Permet d\'éventuellement spécifier un numéro de version');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Mise à jour de la date de livrasion');

        $nameVersion = null;

        if($input->getOption('name')){
            $nameVersion = $input->getOption('name');
        }

        /** @var VersionAppRepository $repository */
        $repository = $this->container->get('doctrine')->getRepository(VersionApp::class);

        if(!(is_null($nameVersion))){
            $versionDB = $repository->findOneBy(array('name'=>$nameVersion));
        }
        else {
            $versionDB = $repository->getLastVersion();
        }

        if(!($versionDB instanceof VersionApp)){
            throw new VersionDoesntExist("Aucune version n'est disponible en base de données");
        }

        /** @var VersionApp $versionDB */
        $versionDB->setDateMiseEnProduction(new \Datetime());
        $this->container->get('doctrine')->getManager()->flush();

        $io->success('Date de mise en production ajoutée');
    }
}
