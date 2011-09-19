<?php

/**
 * Mastop/SystemBundle/Command/InstallThemesCommand.php
 *
 * Comando para instalar os temas do sistema.
 *  
 * 
 * @copyright 2011 Mastop Internet Development.
 * @link http://www.mastop.com.br
 * @author Fernando Santos <o@fernan.do>
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */



namespace Mastop\SystemBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class InstallThemesCommand extends ContainerAwareCommand {

    private $mt;

    protected function configure() {
        $this
                ->setDefinition(array(
                    new InputArgument('target', InputArgument::REQUIRED, 'A pasta os arquivos dos temas serão copiados (geralmente "web")'),
                ))
                ->addOption('symlink', null, InputOption::VALUE_NONE, 'Criar links ao invés de copiar')
                ->setHelp(<<<EOT
O comando <info>mastop:installthemes</info> instala os arquivos estáticos dos temas em uma determinada
pasta (ex.: a pasta web).

<info>./app/console mastop:installthemes web [--symlink]</info>

Uma pasta "themes" será criada dentro da pasta alvo, e o sistema varrerá
todos os temas, pegando os arquivos estáticos e copiando para a pasta criada.

Para criar links ao invés de copiar os arquivos, use a opção <info>--symlink</info>.

EOT
                )
                ->setName('mastop:installthemes')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output) {
        parent::initialize($input, $output);

        $this->mt = $this->getContainer()->get('mastop.themes');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        if (!is_dir($input->getArgument('target'))) {
            throw new \InvalidArgumentException(sprintf('O diretório alvo "%s" não existe.', $input->getArgument('target')));
        }

        if (!function_exists('symlink') && $input->getOption('symlink')) {
            throw new \InvalidArgumentException('A função symlink() não está disponível em seu sistema. Você deve instalar os temas sem a opção --symlink.');
        }

        $filesystem = $this->getContainer()->get('filesystem');
        $origem = $this->mt->getDir();
        $temas = $this->mt->getAllowedThemes();
        if(empty ($origem) || empty($temas)){
            throw new \RuntimeException('O sistema de temas não está configurado.');
        }

        // Cria o diretório de temas
        $filesystem->mkdir($input->getArgument('target') . '/themes/', 0777);
        foreach ($temas as $tema) {
            $originDir = $origem . '/'.$tema.'/Frontend';
            $finder = new Finder();
            $finder->in($originDir);
            $finder->files()->notName('*.twig');
            if (is_dir($originDir)) {
                $targetDir = $input->getArgument('target') . '/themes/' . $tema;

                $output->writeln(sprintf('Instalando arquivos do tema <comment>%s</comment> em <comment>%s</comment>', $tema, $targetDir));

                $filesystem->remove($targetDir);

                if ($input->getOption('symlink')) {
                    $filesystem->symlink($originDir, $targetDir);
                } else {
                    $filesystem->mkdir($targetDir, 0777);
                    $filesystem->mirror($originDir, $targetDir, $finder);
                }
            }
            $originDirAdmin = $origem . '/'.$tema.'/Backend';
            $finder = new Finder();
            $finder->in($originDirAdmin);
            $finder->files()->notName('*.twig');
            if (is_dir($originDirAdmin)) {
                $targetDir = $input->getArgument('target') . '/themes/' . $tema . '/admin';

                $output->writeln(sprintf('Instalando arquivos da administração do tema <comment>%s</comment> em <comment>%s</comment>', $tema, $targetDir));

                $filesystem->remove($targetDir);

                if ($input->getOption('symlink')) {
                    $filesystem->symlink($originDirAdmin, $targetDir);
                } else {
                    $filesystem->mkdir($targetDir, 0777);
                    $filesystem->mirror($originDirAdmin, $targetDir, $finder);
                }
            }
            $originDirMail = $origem . '/'.$tema.'/Mail';
            $finder = new Finder();
            $finder->in($originDirMail);
            $finder->files()->notName('*.twig');
            if (is_dir($originDirMail)) {
                $targetDir = $input->getArgument('target') . '/themes/' . $tema . '/mail';

                $output->writeln(sprintf('Instalando arquivos de email do tema <comment>%s</comment> em <comment>%s</comment>', $tema, $targetDir));

                $filesystem->remove($targetDir);

                if ($input->getOption('symlink')) {
                    $filesystem->symlink($originDirMail, $targetDir);
                } else {
                    $filesystem->mkdir($targetDir, 0777);
                    $filesystem->mirror($originDirMail, $targetDir, $finder);
                }
            }
        }
    }

}
