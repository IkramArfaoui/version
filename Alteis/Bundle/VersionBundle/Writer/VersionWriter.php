<?php

namespace Alteis\Bundle\VersionBundle\Writer;

use Symfony\Component\Filesystem\Filesystem;

class VersionWriter
{

    /** @var Filesystem */
    private $fileSystem;

    /** @var string $file */
    private $file;

    /**
     * VersionWriter constructor.
     * @param $file
     */
    public function __construct($file)
    {
        $this->fileSystem = new Filesystem();
        $this->file = $file;
    }

    /**
     * Permet d'inscrire le nouveau nom de la version dans le fichier applicatif
     * @param string $name
     */
    public function write($name){
        $this->fileSystem->dumpFile($this->file, $name);
    }
}