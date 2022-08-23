<?php

namespace Alteis\Bundle\VersionBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Style\SymfonyStyle;

class CommonCommand extends ContainerAwareCommand {
    
    protected function configure() {
      
        $this->setName('alteis:version:help');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) 
    {            
       
        $io = new SymfonyStyle($input, $output);

        $io->title('Commandes disponibles dans le bundle version :');

        $headers = array (
            'Commandes', 'Rôle', 'Options', 'Arguments'
        );
        $rows = array(
            array (
                'alteis:version:check', 
                'Cette commande de vérifier les version',
                '--symfony',
                '-'
            )
        );
        $io->table($headers, $rows);
    }
    
    public function constructParamsArray (InputInterface $input)
    {
        $container = $this->getContainer();
        $params = array ();
        
        $configs = array (
            "version_suffix"
        );
      
        foreach ($configs as $config)
        {
             if($container->hasParameter($config))
            {
                $params[$config] = $container->getParameter($config);
            }
            else 
            {
                $params[$config] = "";
            }
        }
      
        $params = $this->loadOptions($input, $params);
        
        return $params;
    }
    
    public function loadOptions(InputInterface $input, Array $params)
    {
        $rOptions = $input->getOptions();
        $container = $this->getContainer();
        foreach ($rOptions as $rOption => $rvalue)
        {
            if($container->hasParameter('version_'.$rOption))
            {
                $$rOption = $container->getParameter('version_'.$rOption);
            }
            else 
            {
                $$rOption = true;
            }
            // On vérifie si une valeur a été transmise en option. Si c'est le cas on surcharge le parameters
            if (!is_null($rvalue))
            {
                $$rOption = $rvalue;
            }
            $params[$rOption] = $$rOption;
        }
        return $params;
    }
    
}
