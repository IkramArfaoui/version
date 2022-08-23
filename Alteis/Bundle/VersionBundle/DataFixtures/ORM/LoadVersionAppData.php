<?php

namespace Alteis\Bundle\VersionBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Alteis\Bundle\VersionBundle\Entity\VersionApp;

class LoadVersionAppData extends AbstractFixture implements ContainerAwareInterface
{
    private $container;
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager)
    {
        $filePath = $this->container->getParameter('kernel.root_dir').'/version.txt';
        if (file_exists($filePath)) {
            $name = trim(file_get_contents($filePath));
            $versionApp = new VersionApp();
            $versionApp->setName($name);
            $manager->persist($versionApp);
            $manager->flush();
        }
        else {
            throw new \Exception ('Pour gérer la version application, il faut gérer un fichier version.txt dans ' . $this->container->getParameter('kernel.root_dir'));
        }
    }
}
