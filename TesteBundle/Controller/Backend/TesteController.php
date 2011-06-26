<?php

namespace Mastop\TesteBundle\Controller\Backend;

/*use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Mastop\UserBundle\Form\InsertForm;
use Mastop\UserBundle\Contact\ContactRequest;
use Mastop\UserBundle\Form\ContatoForm;
use Mastop\UserBundle\Document\User;*/

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Mastop\TesteBundle\Form\TesteFormType;
use Mastop\TesteBundle\Document\Teste;


class TesteController extends Controller
{
    /**
     * @Route("/", name="_backend")
     * @Template()
     */
    public function indexAction()
    {
        /* Criação do Document Manager, que é responsável por manusear o banco
         * de dados, que nesse caso irá listar todos os registros existentes
         */
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        
        /* A função getRepository, é utilizada para selecionar o doumento que 
         * será utilizado, é passado no seguinte formato:
         * Empresa+Bundle:Documento
         * 
         * Após escolher o documento que será utilizado, a função findAll traz
         * todos os registros.
         * 
         * Ela é um alias para a função 'findBy(array())'
         * 
         */
        $dom = $dm->getRepository("MastopTesteBundle:Teste")->findAll();
        
        // Array, que irá guardar todos os registros retornados pelo findAll()
        $users = array();
        
        /* Foreach é utilizado para passar por todos os registros retornados
         * pela função findAll().
         * 
         * $k -> Tem o MongoId de cada um dos registros;
         * $v -> Tem um objeto (registro), com suas respectivas informações
         */
        foreach ($dom as $k => $v) {
            
            // Tratamento dos registros do banco de ados
            $dateRet=" - ";
            
            // Verifica se está setada a data de criação, para fazer o tratamento
            if (!is_null($v->getCreatedAt())){ 
                
                /* Mesmo sendo um objeto guardado dentro de outro, quando é 
                 * resgatado, ele continua tendo todas suas propriedades.
                 */
                $dateRet = date('d/m/Y',$v->getCreatedAt()->getTimestamp()); 
            }
            
            /* É possível passar o objeto como um todo, como também é possível
             * tratar cada uma de suas propriedades.
             */
            $users[$k]["title"] = strtoupper($v->getTitle());
            $users[$k]["description"] = substr($v->getDescription(), 0, 40)."...";
            $users[$k]["order"] = $v->getOrder();
            $users[$k]["createdAt"] = $dateRet;
        }
        
        /* É passado um array, com uma série de informações, para que o Twig
         * possa escolher o melhor meio para manipular as informações
         */
        return array(
            "users" => $users,
        );
    }
    
    /**
     * @Route("/novo", name="_teste_insert")
     * @Template()
     */
    public function insertAction()
    {

        /* Instancia a factory de formulários.
         * Passa o objeto TesteFormType como parâmetro, que contém todas as 
         * configurações e informações do objeto.
         */
        
        $factory = $this->get('form.factory');
        $form = $factory->create(new TesteFormType());
        
        /* Passa para o twig o form com createView(), que faz o trabalho para
         * adaptar para view
         */
        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/editar/{id}", name="_teste_edit")
     * @Template()
     */
    public function editAction($id){
        
        /* Instancia a factory dos formulários, que faz com que eles possam ser
         * criados.
         */
        $factory = $this->get('form.factory');
        
        /* Cria um novo formulário baseado no objeto TesteFormType, que tem toda a
         * configuração do formulário:
         * Arquivo do objeto: /form/TesteFormType
         */
        $form = $factory->create(new TesteFormType());

        // Seta o Document Manager para manipulação do banco de dados
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        
        /* Utiliza o Document Manager, criado anteriormente, para
         * trazer o registro no banco de dados
         */
        
        $testeObj = $dm->find('MastopTesteBundle:Teste', $id);
        
        /* Outra forma para trazer um objeto do banco de dados
         * $testeObj = $dm->getRepository('MastopTesteBundle:Teste')->findOneById($id);
         */
        
        /* Abaixo o debug, do objeto retornado pelo find.
         * O objeto, tem as funções de get e set, criadas no 
         * documento (a classe que mapeia o banco de dados)
         */
                    
        /*
        echo "<pre>";
        var_dump($testeObj);
        echo "</pre>";
         */
                    
        /* Exemplo de manipulação do objeto retornado.
         * Como citado anteriormente, é possível pegar os dados retornados com
         * as funções get e setar dados com as funções set, criadas no documento
         */
        
        /* Antes de fazer a alteração:
         * echo $testeObj->getTitle();
         * 
         * Depois de fazer alteração:
         * $testeObj->setTitle("Novo título para o objeto");
         * 
         * echo $testeObj->getTitle();
         */
        

        /* Pega o objeto retornado do banco de dados, e coloca os seus respectivos
         * valores nos campos corretos
         */
        $form->setData($testeObj);
        
        return array(
            'form' => $form->createView(),
            'id' => $id
        );
    }
    
    /**
     * @Route("/deletar-confirmacao/{id}", name="_teste_pre_delete")
     * @Template()
     */
    public function preDeleteAction($id){
        
        // Instancia o Document Manager para realizer queries no banco de dados
        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        /* A consulta a seguir, não é obrigatória.
         * A consulta foi feita, baseada no id passado, para montar o texto de 
         * confirmação antes da exclusão do objeto do banco de dados.
         * 
         * O único trecho obrigatório é a passar o ID do objeto, que será o meio
         * que possibilitará que o objeto seja deletado.
         */
        $testeObj = $dm->find('MastopTesteBundle:Teste', $id);

        return array('teste' => $testeObj,
            'id' => $id);
    }
    
    /**
     * @Route("/deletar/{id}", name="_teste_delete")
     * @Template()
     */
    public function deleteAction($id){
        
        // Instancia o Document Manager para manipulação do banco de dados
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        
        /* Realiza um consulta no banco de dados para trazer o registro correto,
         * baseado no $id (integer), passado como parâmetro da action.
         */
        $testeObj = $dm->getRepository("MastopTesteBundle:Teste")->findOneById($id);
        
        /* Na função remove, é necessário passar um objeto(um documento) como 
         * parâmetro, para conseguir deletar o mesmo.
         * 
         * OBS: Qualquer função ou método utilizado, que traga um objeto - documento -
         * do banco de dados, pode ser passado como parâmetro desta função
         */
        $dm->remove($testeObj);
        $dm->flush();
        
        // Realiza o redirecionamento para a listagem dos registros
        return $this->redirect($this->generateUrl('_backend'));
    }

    /**
     * @Route("/salvar", name="_teste_save")
     * @Template()
     */
    public function saveAction(){
        
        /* Pega os dados enviados pelo envio do formulário, é utilizado nesse
         * caso para verificar o tipo de requisição, porém, pode ser utilizado,
         * por exemplo, para pegar cada um dos dados enviados pelo formulário
         */
        $request = $this->get('request');
        
        
        /* Cria um novo formulário baseado no objeto TesteFormType, que tem toda a
         * configuração do formulário:
         * Arquivo do objeto: /form/TesteFormType
         */
        $factory = $this->get('form.factory');
        $form = $factory->create(new TesteFormType());
        
        /* Para pegar os dados do formulário como um array, basta fazer algo
         * parecido com o seguinte código:
         * $dadosPost = $request->request->get($form->getName());
         */
        
        // Seta o Document Manager que faz a manipulação do banco de dados
        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        // Verifica se a requisição feita é POST
        if ($request->getMethod() == 'POST') {
            
            /* Pega os dados retornados pelo formulário e insere no form, baseado
             * no 'data_class' que foi passado na classe TesteFormType(setado, na
             * criação da variavel $form
             */
            $form->bindRequest($request);
            
            /* Verifica se o formulário é válido, beaseado nas configurações 
             * feitas na classe que controla o formulário.
             */
            if ($form->isValid()) {
                
                // Pega o ID passado pelo formulário, para controle de edição
                $idTeste = $request->request->get('idTeste');
                
                /* Após o bindRequest, o formulário, tem o data, com a classe 
                 * setada no 'data_class' da classe
                 */
                $testePersist = $form->getData();
                
                /* Verifica se existe o 'id' no formulário, para saber se é um
                 * formulário de edição ou inserção
                 */
                if ($idTeste){
                    
                    /* Após verificar se o id está setado, caso esteja, é edição
                     * de um dos registros, seta o ID, no objeto(que é baseado
                     * no 'data_class' passado na classe de formulário), para 
                     * que o sistema atualize o registro
                     */
                    $testePersist->setId($idTeste);
                    $dm->merge($testePersist);
                    
                }else{
                    
                    /* É possivel manipular o objeto já existente, no exemplo a
                     * seguir, foi setada a data de criação, que só é setada na
                     * inserção de um registro no banco de dados.
                     */
                    $testePersist->setCreatedAt(new \DateTime());
                    
                    /* Após a verificação se o id está setado, caso não esteja,
                     * é por que é inserção de um novo registro. Então pega o 
                     * objeto obtido, baseado nos dados retornados pelo formulário
                     * e no 'data_class' do formulário e insere no banco de dados
                     */
                    $dm->persist($testePersist);
                }
                
                // Finaliza a inserção ou atualização de um registro
                /* As seguintes mensagens foram craidas, para da um feedback 
                 * correto para o usuário.
                 * 
                 * Obs.: Elas não são fundamentais para o perfeito funcionamento do
                 * sistema, porém são muito importantes para a utilização do mesmo
                */
                if (is_null($dm->flush())){
                    $this->get('session')->setFlash("notice", "Usuário ".(($idTeste) ? "editado" : "adicionado")." com sucesso!");
                }else{
                    $this->get('session')->setFlash("notice", "Erro ao ".(($idTeste) ? "editar" : "adicionar")." usuário!");
                }
            }else {
                $this->get('session')->setFlash('notice', 'O formulário não foi preenchido corretamente');
            }
        }else{
            $this->get('session')->setFlash('notice', 'A requisição não é válida!');
        }

        // Realiza o redirecionamento para a listagem dos registros
        return $this->redirect($this->generateUrl('_backend'));
        
    } 
    
    /**
     * @Route("/administracao", name="_user_admin")
     * @Template()
     */
    
    public function adminUserAction(){
        
        /**/
        $configuration = array();
        
        // Configuração do nome da listagem
        
        $configuration['name'] = 'Users';
        $configuration['dataClass'] = 'Model\User';
        
        // Configuração dos campos
        
        $campos = array();
        $campos['nome']['label']     = 'Nome';
        $campos['nome']['type']      = 'string';
        
        $campos['idade']['type']     = 'integer';
        
        $campos['email']['label']    = 'E-mail';
        $campos['email']['type']     = 'string';
        
        $campos['username']['type']  = 'string';
        
        $campos['password']['label'] = 'Senha';
        $campos['password']['type']  = 'string';
        $campos['password']['show']  = '$dataType->getData()';
        
        $campos['createdAt']['type'] = 'date';
        
        $configuration['fields'] = $campos;
        
        // /Configuração dos campos
        
        // Configuração das actions
        
        $action = array();
        
        $action['edit']['label']   = 'Editar';
        $action['edit']['path']    = '_user_edit'; // Seta a URL para edição, opção para todas as actions
        
        $action['delete']['label'] = 'Deletar'; // Seta a URL para exclusão, opção para todas as actions
        $action['delete']['path']  = '_user_pre_delete'; // Seta a URL para exclusão, opção para todas as actions
        
        $configuration['action'] = $action;
        // /Configuração das actions
        
        $admin = $this->get('mastop.admin.mandango');
        $admin->configure($configuration);
        
        $admin->setBaseTemplate('MastopAdminBundle:Admin:default.html.twig');
        
        $adminFormat = $admin->prepareAdministration();
        
        //$admin = new AdminGen();
        
        return $this->render($admin->getBaseTemplate(), array(
            'title' => $adminFormat['title'],
            'header'=> $adminFormat['header'],
            'doms'  => $adminFormat['doms']
        ));
        
        
    }
    
}