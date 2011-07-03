<?php

namespace Mastop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Mandango\Cache\FilesystemCache;
use Mandango\Connection;
use Mandango\Mandango;
use Model\Mapping\MetadataFactory;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="_admin_render")
     * @Template()
     */
    public function renderAdminAction($model, $title , array $attr)
    {
        $mandango = $this->get('mandango');
        $modelDom = new $model($mandango);
        $allReg = $modelDom->getRepository()->createQuery();
        
        $ratoLab = new \Model\User($mandango);
        
        //var_dump($attr);
        $arrayDom = array();
        $headerTable = array();
        $i = 0;
        foreach($allReg as $k => $v){
            foreach($attr as $key => $value){
                if ($i == 0){
                    $headerTable[] = $key;
                }
                $getFunc = 'get'.ucfirst($value['value']);
                $arrayDom[$k][strtolower($value['value'])] = $v->$getFunc();
            }
            $i++;
        }
        
        return array(
            "title" => $title,
            "header" => $headerTable,
            "doms" => $arrayDom,
            );
    }
    
    /**
     * @Route("/insert", name="_user_insert")
     * @Template()
     */
    public function insertAction()
    {

        /*if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }*/
        
        $factory = $this->get('form.factory');
        $usr = new \Model\User($this->get("mandango"));
        $form = $factory->create(new InsertForm());
        
        $form->setData($usr);

        return $this->render('MastopUserBundle:User:insert.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/edit/{id}", name="_user_edit")
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editAction($id){
        $mandango = $this->get('mandango');
        $user = $mandango->getRepository('Model\User')->findOneById($id);
        
        $factory = $this->get('form.factory');
        $form = $factory->create(new InsertForm());

        $form->setData($user);
        return $this->render('MastopUserBundle:User:edit.html.twig', array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }
    
    /**
     * @Route("/pre-delete/{id}", name="_user_pre_delete")
     * @Template()
     */
    public function preDeleteAction($id){
        $mandango = $this->get('mandango');
        $usrDOM = new \Model\User($mandango);
        $user = $usrDOM->getRepository()->findOneById($id);
        return array('user' => $user,
            'id' => $id);
    }
    
    /**
     * @Route("/delete/{id}", name="_user_delete")
     * @Template()
     */
    public function deleteAction($id){
        $mandango = $this->get('mandango');
        $usr = new \Model\User($mandango);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $allUsr = $usr->getRepository()->findOneById($id);
            if ($allUsr->delete()){
                $msg = 'Usuário deletado com sucesso!';
            }else{
                $msg = 'Erro ao deletar usuário!';
            }
            $this->get('session')->setFlash('notice', $msg);
            return $this->redirect($this->generateUrl('_user'));
        }
    }
    
    /**
     * @Route("/emailTeste", name="_user_email_teste")
     * @Template()
     */
    public function emailTesteAction(){
        $mailer = $this->get('mailer');

        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('afsdede92@gmail.com')
            ->setTo('afsdede92@gmail.com')
            ->setBody("André Enviou");
        /*for ($i = 0; $i < 1000; $i++){
            $mailer->send($message);
        }*/
            var_dump($mailer->send($message));
            if ($mailer->send($message)){
                return new \Symfony\Component\HttpFoundation\Response("Foi!");
            }else {
                return "Erro!";
            }
            
            $this->get('session')->setFlash('notice', 'E-mail enviado com sucesso!');
            return $this->redirect($this->generateUrl('_user'));
            //$this->renderView('HelloBundle:Hello:email.txt.twig', array('name' => $name))
    }
    
    /**
     * @Route("/email/{id}", name="_user_email")
     * @Template()
     */
    public function emailAction($id){
        $usr = new \Model\User();
        $allUsr = $usr->getRepository()->findOneById($id);
        $contactRequest = new ContactRequest($this->get('mailer'));
        
        $factory = $this->get('form.factory');
        $form = $factory->create(new ContatoForm());

        $form->bindRequest($this->get('request'));
        if ($form->isValid()) {
            $contactRequest->send();
        }

        // Display the form with the values in $contactRequest
        return $this->render('MastopUserBundle:User:email.html.twig', array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }
    
    /**
     * @Route("/save", name="_user_save")
     * @Template()
     */
    public function saveAction(){
        $request = $this->get('request');
        $session = $this->get('session');
        $msgRetOk = "adicionado";
        $msgRetErr = "adicionar";
        if ($request->getMethod() == 'POST') {
            $formRequest = $request->request->get('userform');
            $factory = $this->get('form.factory');
            $form = $factory->create(new InsertForm());
            $mandango = $this->get('mandango');
            $userPersist = new \Model\User($mandango);
            $form->setData($userPersist);
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $idUsr = $request->request->get('idUsr');
                if ($idUsr){
                    $msgRetErr = "editar";
                    $msgRetOk = "editado";
                    $usr = new \Model\User($mandango);
                    $allUsr = $usr->getRepository()->findOneById($idUsr);
                    if ($allUsr){
                        $userPersist->setId($allUsr->getId());
                    }else {
                        $msgRetErr = "Usuário não encontrado";
                    }
                }
                if ($userPersist->save()){
                    var_dump($userPersist->getUpdatedAt());
                    $this->get('session')->setFlash("notice", "Usuário ".$msgRetOk." com sucesso!");
                }else{
                    $this->get('session')->setFlash("notice", "Erro ao ".$msgRetErr." usuário!");
                }
            }else {
                $this->get('session')->setFlash('notice', 'Erro ao salvar no banco de dados');
            }
        }else{
            $this->get('session')->setFlash('notice', 'A requisição precisa ser feita via POST!');
        }
        return $this->redirect($this->generateUrl('_user'));
    } 
    
    
}
