<?php

namespace Alteis\Bundle\VersionBundle\Twig\Extension;

use Alteis\Bundle\VersionBundle\Helper\VersionHelper;

class VersionExtension extends \Twig_Extension
{

    const FILE_NAME = 'version.txt';

    /**
     * @var VersionHelper
     */
    protected $helper;

    /**
     * @var string
     */
    protected $kernelRootDir;

    /**
     * VersionExtension constructor.
     * @param VersionHelper $helper
     * @param $kernelRootDir
     */
    public function __construct(VersionHelper $helper, $kernelRootDir)
    {
        $this->helper = $helper;
        $this->kernelRootDir = $kernelRootDir;
    }

    public function getName()
    {
        return 'version';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('version', array($this, 'version'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('symfony', array($this, 'symfony'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('moisMEP', array($this, 'moisMEP'), array('is_safe' => array('html'))),
        );
    }

    public function version()
    {
        return $this->helper->getVersion();
    }

    public function symfony()
    {
        return $this->helper->getSymfony();
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function moisMEP($fileName = self::FILE_NAME)
    {
        $filemtime = $this->fileModificationTime($fileName);
        $date = $this->strFormatTime($filemtime);
        return utf8_encode($date);
    }

    private function fileModificationTime($fileName)
    {
        $filemtime = filemtime($this->kernelRootDir . DIRECTORY_SEPARATOR . $fileName);
        return $filemtime;
    }

    private function strFormatTime($filemtime)
    {
        setlocale(LC_TIME, 'french');
        $date = strftime('%B %Y', $filemtime);
        return $date;
    }
}
