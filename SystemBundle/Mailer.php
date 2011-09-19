<?php

/**
 * Mastop/SystemBundle/Mailer.php
 *
 * Serviço "mastop.mailer" para envio de emails
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



namespace Mastop\SystemBundle;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Mailer
{
    private $mastop;
    private $theme;
    private $mailer;
    private $message;
    private $templatingEngine;
    private $template;
    private $data = array();
    
    /**
     * Seta o serviço mastop
     * @param Mastop $mastop 
     */
    public function setMastop(Mastop $mastop){
        $this->mastop = $mastop;
    }
    
    /**
     * Seta o mailer
     * @param \Swift_Mailer $mailer 
     */
    public function setMailer(\Swift_Mailer $mailer){
        $this->mailer = $mailer;
    }
    
    /**
     * Cria uma nova mensagem em $message
     */
    public function newMessage(){
        $this->message = \Swift_Message::newInstance();
        $this->message->setFrom($this->mastop->param('system.site.adminmail'), $this->mastop->param('system.site.name'));
    }
    
    /**
     * Pega a mensagem
     * @return \Swift_Message $message
     */
    public function getMessage(){
        return $this->message;
    }
    
    /**
     * Seta o template engine
     * @param EngineInterface $engine 
     */
    public function setTemplatingEngine(EngineInterface $engine){
        $this->templatingEngine = $engine;
    }
    
    /**
     * Seta o tema
     * @param Themes $theme 
     */
    public function setTheme(Themes $theme){
        $this->theme = $theme;
    }
    
    /**
     * Seta os dados para passar para o template
     * @param array $data
     * @return Mailer $mailer
     */
    public function data(array $data){
        $this->data = $data;
        return $this;
    }
    
    /**
     * Seta o assunto
     * @param string $subject
     * @return Mailer $mailer
     */
    public function subject($subject){
        $this->message->setSubject($subject);
        return $this;
    }
    
    /**
     * Adiciona um destinatário
     * @param UserInterface $to
     * @return Mailer $mailer
     */
    public function to($to){
        if(is_object($to) && $to instanceof UserInterface){
            $this->message->addTo(array($to->getEmail() => $to->getName()));
        }else{
            $this->message->addTo($to);
        }
        return $this;
    }
    
    /**
     * Adiciona um destinatário como cópia
     * @param UserInterface $to
     * @return Mailer $mailer
     */
    public function cc($cc){
        if(is_object($cc) && $cc instanceof UserInterface){
            $this->message->addCc(array($cc->getEmail() => $cc->getName()));
        }else{
            $this->message->addCc($cc);
        }
        return $this;
    }
    
    /**
     * Adiciona um destinatário como cópia oculta
     * @param UserInterface $to
     * @return Mailer $mailer
     */
    public function bcc($bcc){
        if(is_object($bcc) && $bcc instanceof UserInterface){
            $this->message->addBcc(array($bcc->getEmail() => $bcc->getName()));
        }else{
            $this->message->addBcc($bcc);
        }
        return $this;
    }
    
    /**
     * Função que seta o template
     * @param string $template Nome do arquivo de template
     * @param array $data Dados a passar para o template. Veja função data()
     */
    public function template($template, $data = null){
        if(!file_exists($this->theme->getDir().'/'.$this->theme->getName().'/Mail/'.$template.'.html.twig')){
            throw new \RuntimeException('Template de e-mail "'.$this->theme->getDir().'/'.$this->theme->getName().'/Mail/'.$template.'.html.twig" não existe.');
        }
        $this->template = $template.'.html.twig';
        if($data && is_array($data)){
            $this->data = $data;
        }
        return $this;
    }
    
    /**
     * Seta o conteúdo da mensagem (quando não se quer usar um template)
     * @param string $content
     * @param string $type
     * @return Mailer 
     */
    public function content($content, $type = 'text/html'){
        $this->message->setBody($content, $type);
        return $this;
    }
    
    public function send(){
        if($this->template){
            $this->message->setBody($this->templatingEngine->render('::Mail/'.$this->template, $this->data), 'text/html');
        }
        $sent = $this->mailer->send($this->message);
        $this->newMessage();
        return $sent;
    }

    /**
     * Envia uma notificação simples. Se o "email" for nulo, o email vai para o admin
     * @param string $subject Assunto
     * @param string $message Mensagem - Se for nulo, envia o mesmo conteúdo do subject
     * @param string $email E-mail para enviar a notificação
     */
    public function notify($subject, $message = null, $email = null){
        $msg = \Swift_Message::newInstance();
        $msg->setSubject($subject);
        $msg->setFrom($this->mastop->param('system.site.adminmail'), $this->mastop->param('system.site.name'));
        if(!$email){
            $msg->setTo(array($this->mastop->param('system.site.adminmail') => $this->mastop->param('system.site.name')));
        }else{
            $msg->setTo($email);
        }
        if(!$message){
            $msg->setBody($subject);
        }else{
            $msg->setBody($message, 'text/html');
        }
        return $this->mailer->send($msg);
    }
    
    
    
}

