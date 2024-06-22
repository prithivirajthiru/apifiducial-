<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;


use App\Entity\RefTable;
use Doctrine\ORM\EntityManagerInterface;
class RefTableController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/ref/table", name="ref_table")
     */
    public function index()
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $repo=$this->EM->getRepository(RefTable::class);
        $entityManager = $this->EM;
        $refTable= $repo->next('requeststatus');
        $jsonContent = $serializer->serialize($refTable, 'json');
        return new Response( $jsonContent);
    }


  
}
