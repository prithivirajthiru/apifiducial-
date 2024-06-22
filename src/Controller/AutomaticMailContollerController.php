<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use App\Service\EmailServiceV1;
use App\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
class AutomaticMailContollerController extends AbstractController
{

    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/automatic/mail/contoller", name="automatic_mail_contoller")
     */
    public function index()
    {
        return $this->render('automatic_mail_contoller/index.html.twig', [
            'controller_name' => 'AutomaticMailContollerController',
        ]);
    }

     /**
     * @Route("/automatic/mail", name="automatic_mail")
     */
    public function automaticMail(EmailServiceV1 $emailservice)
    {
        $data = $emailservice->sendMail();
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"successfully Send");

    }
}
