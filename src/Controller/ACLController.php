<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Utils\ApiResponse;
use App\UtilsV3\ACLV1\Rule;
use App\UtilsV3\ACLV1\Role;
use App\Entity\RefPage;
use App\Entity\DataRole;
use App\Entity\DataRule;
use App\Repository\RefPageRepository;
use App\Repository\RefActionRepository;
use App\Repository\DataRoleRepository;
use App\Repository\DataRuleRepository;
use Doctrine\ORM\EntityManagerInterface;

class ACLController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }
    
     /**
     * @Route("/api/acl/insert/role", name="insertRoleV1",methods={"POST"})
     */
    public function insertRoleV1(Request $request,RefActionRepository $actionrepo,DataRoleRepository $datarolerepo,RefPageRepository $pagerepo)
    {

        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $role = $serializer->deserialize($content, Role::class, 'json');
        
        $datarole = new DataRole();
        $datarole->setName($role->getName());
        $datarole->setDescRole($role->getDescRole());
        $datarole->setVersion('V1');
        $datarole->setStatus('Active');
        $entityManager->persist($datarole);
        $entityManager->flush();
        foreach($role->getRule() as $rule){
            $rule = $serializer->deserialize( json_encode($rule,JSON_NUMERIC_CHECK ), Rule::class, 'json');
            $action = $actionrepo->findOneBy(['id'=>$rule->getAction()]);
            $role = $datarolerepo->findOneBy(['id'=>$rule->getRole()]);
            $page = $pagerepo->findOneBy(['id'=>$rule->getPage()]);
            if(!$action){
                return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"action id is invalid!!!!");
            }
            if(!$page){
                return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"page id is invalid!!!!");
            }
            $datarule = new DataRule();
            $datarule->setAction($action);
            $datarule->setRole($datarole);
            $datarule->setPage($page);
            $datarule->setDescRule($rule->getDescRule());        
            $datarule->setStatus('Active');
            $datarule->setVesion('V1');
            $datarule->setVisibility($rule->getVisibility());
            $entityManager->persist($datarule);
            $entityManager->flush();

        }
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"success!!!!");
    }

    /**
     * @Route("/api/acl/update/role", name="updateRoleV1",methods={"PUT"})
     */
    public function updateRoleV1_(Request $request,RefActionRepository $actionrepo,DataRoleRepository $datarolerepo,RefPageRepository $pagerepo,DataRuleRepository $datarulerepo)
    {

        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $role = $serializer->deserialize($content, Role::class, 'json');
        $datarole = $datarolerepo->findOneBy(['id'=>$role->getId()]);
        if(!$datarole){
             return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"invalid_roleid!!!!");
        }
        $datarole->setName($role->getName());
        $datarole->setDescRole($role->getDescRole());
        $datarole->setVersion($role->getVersion());
        $datarole->setStatus($role->getStatus());
        $entityManager->persist($datarole);
        $entityManager->flush();
       
        foreach($role->getRule() as $rule){
            $rule = $serializer->deserialize( json_encode($rule,JSON_NUMERIC_CHECK ), Rule::class, 'json');
            $action = $actionrepo->findOneBy(['id'=>$rule->getAction()]);
            $role = $datarolerepo->findOneBy(['id'=>$rule->getRole()]);
            $page = $pagerepo->findOneBy(['id'=>$rule->getPage()]);
            if(!$action){
                return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"action id is invalid!!!!");
            }
            if(!$page){
                return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"page id is invalid!!!!");
            }
            if(!$rule->getId()){
                $datarule = new DataRule();
            }
            else{
                $datarule = $datarulerepo->findOneBy(['id'=>$rule->getId()]);
            }
            
            $datarule->setAction($action);
            $datarule->setRole($datarole);
            $datarule->setPage($page);
            $datarule->setDescRule($rule->getDescRule());        
            $datarule->setStatus($rule->getStatus());
            $datarule->setVesion($rule->getVesion());
            $datarule->setVisibility($rule->getVisibility());
            $entityManager->persist($datarule);
            $entityManager->flush();
        }
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"success!!!!");
    }

    /**
     * @Route("/api/acl/dashboard/{process}/{role_id}", name="getDashboardNormalRule",methods={"GET"})
     */
    public function getDashboardNormalRule(Request $request,DataRuleRepository $datarulerepo,RefPageRepository $pagerepo,$role_id,DataRoleRepository $datarolerepo,$process)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $pages = $pagerepo->findBy(['type'=>"Dashboard"]);
        $data=[];
        if($process=="normal"){
            $role = $datarolerepo->findOneBy(['id'=>$role_id]);
            foreach($pages as $page){
                $value=$datarulerepo->findOneBy(['role'=>$role_id,'page'=>$page->getId()]);
               array_push($data,$value);
            }
            $role->setRule($data);
             return new ApiResponse($role,200,["Content-Type"=>"application/json"],'json',"success!!!!");
        }
        if($process=="map"){
            foreach($pages as $page){
                $value=$datarulerepo->findOneBy(['role'=>$role_id,'page'=>$page->getId()]);
                $data[$page->getId()]=$value;
            }
            return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"success!!!!");
        }     
    }

    /**
     * @Route("/api/acl/get/single_role/{process}/{role_id}", name="singleProcessRole",methods={"GET"})
     */
    public function singleProcessRole_($role_id,Request $request,DataRoleRepository $datarolerepo,DataRuleRepository $datarulerepo,$process)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = [];
        $rules = array();
        $singlerole = $datarolerepo->findOneBy(['id' => $role_id]);
        $roles=$datarulerepo->findBy(['role'=> $role_id]);
        if($process == 'normal'){
     
        $singlerole->setRule($rolearray);
        return new ApiResponse($singlerole,200,["Content-Type"=>"application/json"],'json',"success!!!!");
        }
        if($process == 'map'){
            foreach($roles as $role){
                $data[$role->getPage()->getRefId()]=$role;
               
            }
            $singlerole->setRule($data);
            return new ApiResponse($singlerole,200,["Content-Type"=>"application/json"],'json',"success!!!!");
        }
    }

    /**
     * @Route("/api/role/enabled/{id}", name="roleEnabled",methods={"PUT"})
     */
    public function roleEnabled(DataRoleRepository $datarolerepo,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $role = $datarolerepo->findOneBy(['id'=>$id]);
        if(!$role){
            return new ApiResponse($role,400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $role->setStatus('Active');
        $entityManager->persist($role);
        $entityManager->flush();
        return new ApiResponse($role,200,["Content-Type"=>"application/json"],'json','updated success');          
    }
   
    /**
     * @Route("/api/role/disabled/{id}", name="roleDisabled",methods={"PUT"})
     */
    public function roleDisabled(DataRoleRepository $datarolerepo,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $role = $datarolerepo->findOneBy(['id'=>$id]);
        if(!$role){
            return new ApiResponse($role,400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $role->setStatus('Disabled');
        $entityManager->persist($role);
        $entityManager->flush();
        return new ApiResponse($role,200,["Content-Type"=>"application/json"],'json','updated success');    
    }
    
}
