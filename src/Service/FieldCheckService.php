<?php
namespace App\Service;
use App\Entity\FieldCheck;
use App\Utils\FieldChecks;
use App\Entity\DataClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use \Swift_SmtpTransport,\Swift_Mailer,\Swift_Message;
class FieldCheckService{
    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }
    // public function insert($request, $entityManager){
    //     $encoders       =  [new JsonEncoder()];
    //     $normalizer     =  new ObjectNormalizer();
    //     $normalizers   =  array($normalizer,new DateTimeNormalizer());
    //     $serializer    =  new Serializer($normalizers ,$encoders);
    //     $content       =  $request->getContent();
    //     $data = $serializer->deserialize($content, FieldChecks::class, 'json');
        
    //     $dataClientrepo=$this->EM->getRepository(DataClient::class);
    //     $dataClient=$dataClientrepo->findOneBy(['id'=>$data->getClientId()]);
    //     if(!$dataClient){
    //         return "client";
    //     }
    //     // return $FieldCheck;
    //     $fields=$data->getFields();
    //     foreach($fields as $field){
    //         $field      = $serializer->deserialize( json_encode($field), FieldCheck::class, 'json');
    //         // return $field->getRerurnCheck();
    //         $fieldCheck = new FieldCheck();
    //         $fieldCheck->setClient( $dataClient);
    //         $fieldCheck->setFieldName($field->getFieldName());
    //         $fieldCheck->setRerurnCheck($field->getRerurnCheck());
    //         $fieldCheck->setResumeCheck($field->getResumeCheck());
    //         $fieldCheck->setValue($field->getValue());
    //         $entityManager->persist($fieldCheck);
    //         $entityManager->flush();
    //     }
    //     return "success";
    // }
    // public function update($request, $entityManager){
    //     $encoders       =  [new JsonEncoder()];
    //     $normalizer     =  new ObjectNormalizer();
    //     $normalizers   =  array($normalizer,new DateTimeNormalizer());
    //     $serializer    =  new Serializer($normalizers ,$encoders);
    //     $content       =  $request->getContent();
    //     $datafields = $serializer->deserialize($content, FieldChecks::class, 'json');
    //     $fieldrepo=$this->EM->getRepository(FieldCheck::class);

    //     $dataClientrepo=$this->EM->getRepository(DataClient::class);
    //     $dataClient=$dataClientrepo->findOneBy(['id'=>$datafields->getClientId()]);
    //     if(!$dataClient){
    //         return "client";
    //     }
    //     $dataClients=$fieldrepo->findBy(['client'=>$datafields->getClientId()]);
    //     if($dataClients){
    //         foreach($dataClients as $data){
    //         $entityManager->remove($data);
    //         $entityManager->flush();
    //         }
    //     }
     
    //     // return $FieldCheck;
    //     $fields=$datafields->getFields();
    //     foreach($fields as $field){
    //         $field      = $serializer->deserialize( json_encode($field), FieldCheck::class, 'json');
    //         $fieldCheck = new FieldCheck();
    //         $fieldCheck->setClient( $dataClient);
    //         $fieldCheck->setFieldName($field->getFieldName());
    //         $fieldCheck->setRerurnCheck($field->getRerurnCheck());
    //         $fieldCheck->setResumeCheck($field->getResumeCheck());
    //         $fieldCheck->setValue($field->getValue());
    //         $entityManager->persist($fieldCheck);
    //         $entityManager->flush();
    //     }
    //     return $fieldCheck;
    // }
   

    }