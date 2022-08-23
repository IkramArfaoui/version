<?php

namespace Alteis\Bundle\VersionBundle\Command;

use Alteis\Bundle\VersionBundle\Entity\VersionApp;
use Alteis\Bundle\VersionBundle\Repository\VersionAppRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CheckCommand extends Command implements ContainerAwareInterface {

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

        $this->setName('version:check')
            ->setDescription('Permet de comparer les versions applicative et base de données')
            ->addOption('symfony', null, InputOption::VALUE_NONE, 'Complète le check avec la version de Symfony');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        /** @var VersionAppRepository $repository */
        $repository = $this->container->get('doctrine')->getRepository(VersionApp::class);
        $versionDC = $this->container->get('alteis.version.data_collector');

        $appVersion = $versionDC->getVersion();
        $lastVersionBDD = $repository->getLastVersion();

        $bddVersion = '';
        if($lastVersionBDD instanceof VersionApp) {
            $bddVersion = $lastVersionBDD->getName();
        }
        
        $io->title('Check des versions');
        $io->note('Version applicative : ' . $appVersion);
        $io->note('Version de la base de données : ' . $bddVersion);

        if($input->getOption('symfony'))
        {
            $io->note('Version de symfony : ' . $versionDC->getSymfony());
        }

        if ($appVersion == $bddVersion)
        {
            $io->success('Les versions applicative et base de données coïncident');
        }
        else
        {
            $io->error('Les versions ne coïncident pas.');
        }
    }
}
