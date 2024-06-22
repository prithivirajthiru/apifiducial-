<?php

namespace App\Service;

use App\Entity\RefDirect;
use App\UtilsV3\Direct;
use App\Repository\RefVariableRepository;
use Doctrine\ORM\EntityManagerInterface;
use \Swift_SmtpTransport,\Swift_Mailer,\Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Console\Output\OutputInterface;

use App\Entity\RefVariable;
use App\Entity\DataUserspace;
use App\Entity\EmailAction;
use App\Entity\RefRequeststatus;
use App\Entity\DataRequest;
use App\Entity\DataTemplate;



class AutomaticMailService{
    public $EM;
    private $mailer;
    private $templating;
    public function __construct(EntityManagerInterface $EM,\Twig\Environment $templating )
    {
        $this->EM = $EM;
        $this->templating = $templating;

    }

    public function SingleMail($varrepo,$datatemprepo,$param,$actionId,$to_email){
        $text=$datatemprepo->findOneBy(['action_id'=>$actionId,'status'=>'Active']);
        $data=array();
        $lang='fr';
        $data=$this->getValue($varrepo,$param,$lang);
        $et=$text->getHtml();
        if($text->getSignature()){
            if($text->getSignature()->getType()=='text'){
                $et=$et.$text->getSignature()->getData();
            }
            if($text->getSignature()->getType()=='img'){
                $et=$et.'<img src="http://apikeprevos.keprevos.com/api/filedownloadwithMIME/'.$text->getSignature()->getData().'" style="width:150px;height:50px;"/>';
            }
        }
       
       
        $edittext=$this->addVariableWithJS($et,$data);
        $transport =(new Swift_SmtpTransport($text->getFromTemplate()->getSmtpIp(),$text->getFromTemplate()->getSmptPort(),'ssl'))
        ->setUsername($text->getFromTemplate()->getEmailId())
        ->setPassword($text->getFromTemplate()->getPassword());
        $mailer = new Swift_Mailer($transport);
        $env = new \Twig_Environment(new \Twig_Loader_Array(['my_template_name' => $edittext]));
        $html = $env->render('my_template_name', array());
         $message = (new Swift_Message($text->getSubjectTemplate()))
         ->setFrom($text->getFromTemplate()->getEmailId())
         ->setTo($to_email)
         ->setBody(
            $html         
         ,'text/html')
         ;

         $result = $mailer->send($message);
         
    }

    public function sendMail(){
        // $entityManager = $this->EM->getManager();

        $dr=array();
        $dr1=array();
        $varrepo      =  $this->EM->getRepository(RefVariable::class);
        $userspacererpo      =  $this->EM->getRepository(DataUserspace::class);
        $emailactionrepo  =  $this->EM->getRepository(EmailAction::class);
        $refrequestRepo    =  $this->EM->getRepository(RefRequeststatus::class);
        $datarequestRepo    =  $this->EM->getRepository(DataRequest::class);
        $datatemprepo    =  $this->EM->getRepository(DataTemplate::class);
        $actions=$emailactionrepo->findBy(['is_automatic'=>'A']);
        foreach($actions as $action){
            //  return $actions;
           $status=$action->getFromStatus();
           if($status==0){
               $status='000';
           }
           $requeststatus=$refrequestRepo->findOneBy(['status_requeststatus'=>$status]);
           $datarequests=$datarequestRepo->findBy(['requeststatus'=>$requeststatus->getId()]);
    //    return $datarequests;
        $continue=array();
        foreach($datarequests as $datarequest){
            $clientdetail=$userspacererpo->findoneBy(['id_request'=>$datarequest->getId()]);
            array_push($dr,$datarequest->getId());
            $client=$datarequest->getClient()->getId();

            if(!$clientdetail){
                array_push($continue,$datarequest->getId());
               continue;
            }
            $to_email=$clientdetail->getEmailUs();
            $requestdate= $datarequest  ->  getDateupdRequest();
            $currentdate= new \DateTime();
            $diffcalc=$requestdate->diff($currentdate);
            $datediff=$diffcalc->format('%d');
            $no_of_days=$action->getNoOfDays();
          
            if($datediff>=$no_of_days){
                $actionId=$action->getId();
                $chkreqstatus= $refrequestRepo->findOneBy(['status_requeststatus'=>$action->getToStatus()]);
                // return $chkreqstatus;
                $mail=$this->SingleMail($varrepo,$datatemprepo,$client,$actionId,$to_email);
                $datarequest  ->  setDateupdRequest(new \DateTime());
                $datarequest  ->  setRequeststatus($chkreqstatus);
                $this->EM -> persist($datarequest);
                $this->EM -> flush();
                array_push($dr1,$datarequest->getId());
            }
           
        }
    
        }
        return ["dr1"=>$dr,"dr2"=>$dr1,"continue"=>$continue];
       
    

    }

    public function getValue(RefVariableRepository $varrepo,$id,$lang){
        $fields=$varrepo->findAll();
        $conn = $this->EM->getConnection();

        foreach($fields as $field){

            if($field->getPreparedvalue()=='two'){
                $stmt = $conn->prepare($field->getQuery());
                $stmt->bindParam(':lang', $lang);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $result=$stmt->fetch();
                $field->setValue($result['value']);
            }

            if($field->getPreparedvalue()=='one'){
                $stmt = $conn->prepare($field->getQuery());
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $result=$stmt->fetch();
                $field->setValue($result['value']);
            }
            if($field->getPreparedvalue()=='three'){
                $stmt = $conn->prepare($field->getQuery());
                $stmt->execute();
                $result=$stmt->fetch();
                $field->setValue($result['value']);
            }
        
    }
    return $fields;
        
    }

    public function addVariableWithJS(&$text,$data){
        // $text1=$text;
        $JS="";
        foreach($data as $datum){
            if($datum->getValue()!=null){
                if($datum->getScript()){
                   
                }
                $pattern="%".$datum->getVariablename()."%";
                 $correctiontext = preg_replace("/$pattern/",htmlEntities($datum->getValue(), ENT_NOQUOTES, "UTF-8"),$text);
                //$correctiontext = preg_replace("/$pattern/"," <div id=`id1`>solomon</div> ",$text);
                $JS=$JS.$datum->getScript().";";
                $text= $correctiontext;
            }
           
           
        }

       // $text=$text.'<script  type="text/javascript">'.$JS.'</script>';
       
        return $text;
       
       
    }

    public function Single(OutputInterface $output,$message){

        $output->writeln($message['message']);
    }
}