<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel {
	public function registerBundles() {
		$bundles = array(
				new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
				new Symfony\Bundle\SecurityBundle\SecurityBundle(),
				new Symfony\Bundle\TwigBundle\TwigBundle(),
				new Symfony\Bundle\MonologBundle\MonologBundle(),
				new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
				new Symfony\Bundle\AsseticBundle\AsseticBundle(),
				new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
				new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
				// FOSUserBundle
				new FOS\UserBundle\FOSUserBundle(),
				new Application\UserBundle\ApplicationUserBundle(),
				// StofDoctrineExtensionsBundle
				new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
				// FOSMessageBundle
				new FOS\MessageBundle\FOSMessageBundle(),
				new Application\MessageBundle\ApplicationMessageBundle(),
				//JMSTranslationBundle
				new JMS\TranslationBundle\JMSTranslationBundle(),
				//AdmingeneratorGeneratorBundle
				new Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle(),
				new Knp\Bundle\MenuBundle\KnpMenuBundle(),
				new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
				// HWIOAuthBundle
				new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
		);

		if (in_array($this->getEnvironment(), array('dev', 'test'))) {
			$bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
			$bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
			$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
		}

		return $bundles;
	}

	public function registerContainerConfiguration(LoaderInterface $loader) {
		$loader->load(__DIR__ . '/config/config_' . $this->getEnvironment()
						. '.yml');
	}
}
