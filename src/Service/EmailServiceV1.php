<?php

namespace App\Service;

use Pikirasa\RSA;
use Dompdf\Dompdf;
use App\UtilsV3\Direct;
use App\Utils\RequestContent;
use App\Entity\RefField;
use App\Entity\RefDirect;
use App\Entity\DataClient;
use App\Entity\DataRelance;
use App\Entity\DataRequest;
use App\Entity\EmailAction;
use App\Entity\RefVariable;
use App\Entity\DataAttorney;
use App\Entity\DataTemplate;
use App\Entity\EmailContent;
use App\Entity\DataClientSab;
use App\Entity\OptionsTabel;
use App\Entity\RefTypeClient;
use Spipu\Html2Pdf\Html2Pdf;
use App\Entity\DataUserspace;
use App\Entity\DataFieldIssue;
use App\Entity\DataRequestFile;
use App\Entity\RefRequeststatus;
use App\Entity\RefEmailAutomatic;
use App\Entity\DataRequestRequeststatus;
use App\Repository\DataRequestRequeststatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RefVariableRepository;
use Symfony\Component\Serializer\Serializer;
use App\UtilsV3\Scenario2\DataRequestRequststatus;
use \Swift_SmtpTransport,\Swift_Mailer,\Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\DBAL\Connection;
use Twig\Environment;
use Twig\Loader\ArrayLoader;


class EmailServiceV1 {
    private $EM;
    private $mailer;
    private $templating;
    private $params;


    public function __construct(EntityManagerInterface $EM,\Twig\Environment $templating,ParameterBagInterface $params)
    {
        $this->EM = $EM;
        $this->templating = $templating;
        $this->params = $params;
      
    }

    public function getValue(RefVariableRepository $varrepo,$id,$lang){
        $fields=$varrepo->findAll();
        $conn = $this->EM->getConnection();
        $attorneyrepo = $this->EM->getRepository(DataAttorney::class);
        $clientrepo = $this->EM->getRepository(DataClient::class);
        $requestrepo = $this->EM->getRepository(DataRequest::class);
        $reffieldrepo = $this->EM->getRepository(RefField::class);
        $attorney = $attorneyrepo->findOneBy(['client'=>$id,'ismandatory_attorney'=>true]);
        $list_of_attorneys = $attorneyrepo->findBy(['client'=>$id]);
        $client = $clientrepo->findOneBy(['id'=>$id]);
        foreach($fields as $field){

            if($field->getPreparedvalue()=='two'){

                $query = $field->getQuery();
                $resultSet = $conn->executeQuery($query, ['id' => $id,'lang' => $lang,]);
                $result = $resultSet->fetchAssociative();
                if($result!=false){
                    $field->setValue($result['value']);
                    if($field->getVariablename()=='ape'){
                        $value = $result['value'];
                        $field->setValue($value);
                        if($client->getEpa()){
                            $epaid = $client->getEpa()->getEpaCode();
                            $value = $epaid." ".$result['value'];
                            $field->setValue($value);
                        }    
                    }
                }
            }

            if($field->getPreparedvalue()=='one'){
                if($field->getVariableName()=='Representemail'||$field->getVariableName()=='Clientcréation'){
                    $request = $requestrepo->findOneBy(['client'=>$id]);
                    $id = $request->getId();
                    $query = $field->getQuery();
                    $resultSet = $conn->executeQuery($query, ['id' => $id]);
                    $result = $resultSet->fetchAssociative();
                    if($result!=false){
                        $field->setValue($result['value']);
                    }
                }
                if($field->getVariableName() == 'IBAN'){
                    $request = $requestrepo->findOneBy(['client'=>$id]);
                    $id = $request->getId();
                    $query = $field->getQuery();
                    $resultSet = $conn->executeQuery($query, ['id' => $id]);
                    $result = $resultSet->fetchAssociative();
                    if($result!=false){
                        $string = wordwrap($result['value'], 4, "\n", true);
                    }
                    $field->setValue($string); 
                }
                else{
                    if($field->getVariableName() == 'issues'){
                        $version = $this->version($id);
                        
                        $query = $field->getQuery();
                        $resultSet = $conn->executeQuery($query, ['id' => $id,'version'=>$version]);
                        $results = $resultSet->fetchAllAssociative();
                        $string = "";
                        if($results){
                            foreach($results as $result){
                                $field_id = $result['field_id'];
                                $issuefield = $reffieldrepo->findOneBy(["id"=>$field_id]);
                                $string = $string."<br>".$issuefield->getLabel()."<br>";
                            }
                        }
                        
                        // return $string;
                        $field->setValue($string);   
                    }     
                    else{
                        $query = $field->getQuery();
                        $resultSet = $conn->executeQuery($query, ['id' => $id]);
                        $result = $resultSet->fetchAssociative();
                        if($result!=false){
                            $field->setValue($result['value']);
                        }
                    }
                }
                
            }
            if($field->getPreparedvalue()=='three'){
                $query = $field->getQuery();
                $resultSet = $conn->executeQuery($query, ['id' => $id]);
                $result = $resultSet->fetchAssociative();
                if($result!=false){
                    $field->setValue($result['value']);
                }
                if($field->getVariablename()=='Datedujour'){
                    $date=date_create($result['value']);
                    $value=date_format($date,"d/m/Y");
                    $field->setValue($value);
                }
                
            }
            if($attorney){
                if($field->getPreparedvalue()=='four'){
                    $sid = $attorney->getId();
                    $query = $field->getQuery();
                    $resultSet = $conn->executeQuery($query, ['id' => $id,'sid' => $sid]);
                    $result = $resultSet->fetchAssociative();
                    if($result!=false){
                        $field->setValue($result['value']);
                    }
                    if($field->getVariablename()=='Clientdesignation'){
                       $name = $client->getCompanynameClient(); 
                       $field->setValue($name);
                    }
                    if($field->getVariablename()=='Clientprenom'){
                        $field->setValue(" ");
                    }
                    if($field->getVariablename()=='Representdatenaissance'){
                        $date=date_create($result['value']);
                        $value=date_format($date,"d/m/Y");
                        $field->setValue($value);
                    }
                }
                if($field->getPreparedvalue()=='five'){
                    if($field->getVariablename()=='shareholeder_detail'){
                        $string = "";
                        $head = "";
                        foreach($list_of_attorneys as $attorney){
                            $nom = $attorney->getNameAttorney();
                            $prenom = $attorney->getSurnameAttorney();
                            $amount = $attorney->getAmountAttorney();
                            $percentage = $attorney->getPercentageAttorney();
                            $head = '<tr> <th>Nom & PreNom</th> <th>Percentege</th> <th>Amount</th> </tr>';
                            $string =$string.'<tr> <td>'.$nom.$prenom.'</td> <td>'.$percentage.'</td> <td>'.$amount.'</td> </tr>';
                        }
                        $result = '<table style="width:100%">'. $head.$string.'</table>';
                        $field->setValue($result);
                    }
                    elseif($field->getVariablename()=='shareholeder_company_detail'){
                        $fullstring = "";
                        foreach($list_of_attorneys as $attorney){
                            $dob = "";
                            if ($attorney->getIsshareholderAttorney() == 1) {
                                $nom = $attorney->getNameAttorney();
                                $prenom = $attorney->getSurnameAttorney();
                                $amount = $attorney->getAmountAttorney();
                                $percentage = $attorney->getPercentageAttorney();
                                $companyname = $attorney->getCompanyName();
                                $siren = $attorney->getRegisterNo();
                                if($attorney->getDatebirthAttorney()){
                                    $dob = $attorney->getDatebirthAttorney();
                                    $dob = date_format($dob,"d/m/Y");                          
                                    //$serializer = new Serializer(array(new DateTimeNormalizer()));
                                    //$dob = $serializer->normalize($dob);
                                }
                                
                                if($attorney->getCountrybirthAttorney()){
                                    $birthcountry = $attorney->getPlacebirthAttorney()." (".$attorney->getCountrybirthAttorney()->getDescCountry().")";
                                }
                                else{
                                    $birthcountry = "";
                                }
                                if($attorney->getIscompany() == 1){
                                    if($attorney->getNationalityAttorney()){
                                        $birthcountry = $attorney->getNationalityAttorney()->getDescCountry();
                                    }
                                    else{
                                        $birthcountry = "";
                                    }
                                    $string =  '<tr><td>-</td><td>'.$companyname.'</td><td></td><td>Siren : '.$siren.'</td><td>('.$birthcountry.')</td></tr>';
                                }
                                else{
                                    $string = '<tr ><td>-</td><td>'.$nom.'</td><td>'.$prenom.'</td><td>né(e) le '.$dob.'</td><td>à '.$birthcountry.'</td></tr>';
                                }
                                $fullstring = $fullstring.$string;
                            }                            
                        }
                        $result = $fullstring;
                        $field->setValue($result);
                    }
                    else{
                    $sid = $attorney->getId();
                    $query = $field->getQuery();
                    $resultSet = $conn->executeQuery($query, ['id' => $id,'sid' => $sid,'lang'=>$lang]);
                    $result = $resultSet->fetchAssociative();
                    if($result!=false){
                        $field->setValue($result['value']);
                    }
                    }
                }    
            }
    }
    return $fields;  
    }
    public function version($client_id){
      
        $q = "SELECT MAX(version) FROM data_field_issue WHERE client_id = :clientId AND attorney_id IS NULL";
        $version = 0;
        if($q){
            $conn =  $this->EM->getConnection();
            $resultSet = $conn->executeQuery($q, ['clientId'=>$client_id]);
            $result = $resultSet->fetchAllAssociative();
            $version = $result[0]["MAX(version)"];    
            }      
        return $version; 
    }

    public function addVariable($text,$data){
        $text1=$text;
        foreach($data as $datum){
            if($datum->getValue()!=null){
                $pattern="%".$datum->getVariablename()."%";
                $correctiontext = preg_replace("/$pattern/"," ".$datum->getValue()." ",$text1);
                $text1= $correctiontext;
            }
           
           
        }
       
        return $text1;
       
       
    }


    public function addVariableWithJS($text,$data){
        foreach($data as $datum){
            $variablename = $datum->getVariablename();
            $value = $datum->getValue();
            if($datum->getValue()!=null){
                if($datum->getVariablename()=='Clientadresse'||$datum->getVariablename()=='Representadresse'){
                    $value = str_replace("|", " ",$value);
                }
                if($datum->getVariablename()=='Representdatenaissance'){
                    $value = substr($value,0,10);
                }
                $checkvalue = substr($value,0,1);
                if($checkvalue==" "){
                    $value = substr($value,1);
                }
                if($datum->getVariablename()=='Clientadresse1'){
                    $data = explode("|",$value);
                    if($data){
                        $value = $data[0];
                    }
                }
                if($datum->getVariablename()=='Clientadresse2'){
                    $data = explode("|",$value);
                    if(count($data)==2){
                        $value = $data[1];
                    }
                    else{
                        $value = "";
                    }
                }
                if($datum->getVariablename()=='loginurl'){

                    $pub_key = file_get_contents('../pub.crt');
                    $pri_key = file_get_contents('../pri.crt');
                    $rsa     = new RSA($pub_key, $pri_key);
                    $data    = $datum->getValue();
                    $encrypted = $rsa->base64Encrypt($data);

                    $value = '<table><tbody>
                    <tr>
                    <td align="center" bgcolor="#A32035" role="presentation" style="border:none;cursor:auto;mso-padding-alt:10px 25px;background:#A32035;" valign="middle">
                        <a href="'.$this->params->get('app.ser1').'/otp?euid='.base64_encode($encrypted).'" target="_blank" title="" data-targettype="webpage" data-targetname="" style="display:inline-block;background:#A32035;color:#FFFFFF;font-family:Arial, Helvetica, sans-serif;font-size:13px;font-weight:normal;line-height:120%;margin:0;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;"><span style="color:#ffffff">J\'ouvre mon compte</span></a>
                    </td>
                    </tr>             
                    </tbody></table>';
                    // $value = '<button id="link_to_otp" style="width: 16.25rem; height: 3.125rem; margin: 0 !important; margin-left: 1.65rem !important; font-weight: 600; font-size: 0.875rem;background: #b51f39;box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12); padding: .84rem 2.14rem; font-size: .81rem; margin: .375rem;border: 0;border-radius: .125rem;cursor: pointer;text-transform: uppercase;white-space: normal;color: #fff;line-height: 1.5;text-align: center;vertical-align: middle;user-select: none;">J'.'ouvre mon compte<span class="process_btn_ic"></span></button>';
                    $pattern="#".$datum->getVariablename()."#";
                    $correctiontext = preg_replace("/$pattern/",$value,$text);
                    // $JS=$JS.$datum->getScript().";";
                    $text= $correctiontext;
                }
                $pattern="#".$variablename."#";
                $correctiontext = preg_replace("/$pattern/","<b>".mb_strtoupper($value, 'UTF-8')."</b>",$text);
                $text= $correctiontext;
            }
            else{
                $pattern="#".$variablename."#";
                $correctiontext = preg_replace("/$pattern/","<b>".mb_strtoupper("", 'UTF-8')."</b>",$text);
                $text= $correctiontext;
            }
        }
        return $text;  
    }


    public function mailSend($mailer,$subject,$from,$to,$edittext,$templating){
        
        
        $this->templating = $templating;
        $message = (new \Swift_Message($subject))
        ->setFrom($from)
        ->setTo($to)
        ->setBody($edittext,'text/html');
        $mailer->send($message);
       return $from;
       
    }

    public function mailSendv1($mailer,$subject,$from,$to,$edittext,$templating,$transport){
      
     
        // $this->transport = \Swift_SmtpTransport::newInstance('smtp.example.org', 25)
        //             ->setUsername("vijayarethinam.info@gmail.com")
        //             ->setPassword("9500662417");
        // $mailer = new Swift_Mailer($transport);

        // Create a message
        $this->templating = $templating;
        $message = (new \Swift_Message($subject))
        ->setFrom($from)
        ->setTo($to)
        ->setBody($edittext,'text/html');
        $mailer->send($message);
       return $from;

        // // Send the message
        // $result = $mailer->send($message);
       
    }

    public function sendMail(){
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
            $status=$action->getFromStatus();
            if($status==0){
               $status='000';
            }
            $requeststatus=$refrequestRepo->findOneBy(['status_requeststatus'=>$status]);
            $datarequests=$datarequestRepo->findBy(['requeststatus'=>$requeststatus->getId()]);
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
                    $mail=$this->sendEmail($varrepo,$datatemprepo,$client,$actionId,$to_email, true);
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


    public function sendEmail($varrepo,$datatemprepo,$param,$actionId,$to_email, $norib, $ignore=false){
       
        $text = $datatemprepo->findOneBy(['action_id'=>$actionId,'status'=>'Active']);
        $data = array();
        $lang = 'fr';
        $subject = "";
        $data = $this->getValue($varrepo,$param,$lang);
        if ($text != null) {
            $et = $text->getHtml();
            if($text->getSignature()){
                // if($text->getSignature()->getType() == 'text'){
                //     $et = $et.$text->getSignature()->getData();
                // }
                // if($text->getSignature()->getType() == 'img'){
                //     $et = $et.'<img src="http://apikeprevos.keprevos.com/api/filedownloadwithMIME/'.$text->getSignature()->getData().'" style=""/>';
                // }
                if($text->getSignature()->getType() == 'img'){
                    // $imgurl = $this->params->get('app.dir').$text->getSignature()->getData();
                    // $str = str_replace("/","\\",$imgurl);
                    // $url =  base64_encode(file_get_contents($str));
                    // $url = "data:image/png;base64,".$url;
                    // $imgurl = '<img src="'.$url.'" style=""/>';
                    $uuid = $text->getSignature()->getUuid();
                    $imgurl = '<img src="http://127.0.0.1:8000/api/signaturedownload_withuuid/'.$uuid.'" style=""/>';
                    $et = $et.$imgurl;
                }
            }
            $edittext = $this->addVariableWithJS($et,$data);
            $requestrepo = $this->EM->getRepository(DataRequest::class); 
            $loader = new ArrayLoader(['my_template_name' => $edittext]);
            $env = new Environment($loader);
            $html = $env->render('my_template_name', []);
            // $env = new \Twig_Environment(new \Twig_Loader_Array(['my_template_name' => $edittext]));
            // $html = $env->render('my_template_name', array());
            $html = '<body style="font-family: Arial, Helvetica, sans-serif;font-size: 12px;">'.$html.'</body>';
            $datarequest = $requestrepo->findOneBy(['client'=>$param]);
            $from = null;
            
            if ($text->getFromTemplate() !== null) {
                $from = $text->getFromTemplate()->getExpediteur();
            }
            if($text->getSubjectTemplate()!=null){
                $subject = $text->getSubjectTemplate();
            }
         
            $email = new EmailContent;
            $email->setToEmail($to_email);
            $email->setDataRequest($datarequest);
            $email->setContent($html);
            $email->setFromEmail($from);
            $email->setSubject($subject);
            $email->setStatus("pending");
            $email->setAttachementDoc($text->getAttachmentTemplate());
            $sended = false;

            if (($norib == false &&  $text->getAttachmentTemplate() == "RIB") || ($norib == true && $text->getAttachmentTemplate() != "RIB")) {
                $this->EM->persist($email);
                $this->EM->flush();                
                $sended = true;
            }

            if ($sended == false && $ignore == true) {
                $this->EM->persist($email);
                $this->EM->flush();
                $sended = true;
            }
        }  

    }

    public function sendReqdetailEmail($varrepo,$datatemprepo,$actionId,$datareqs){
       
        $text = $datatemprepo->findOneBy(['action_id'=>$actionId,'status'=>'Active']);
        $data = array();
        $lang = 'fr';
        $subject = "";
        $content = $text->getHtml();
        $finalContent = $this->getFinalContent($content,$datareqs);
        $env = new \Twig_Environment(new \Twig_Loader_Array(['my_template_name' => $finalContent]));
        $html = $env->render('my_template_name', array());
        $html = '<html><style>
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }
        </style><body style="font-family: Arial, Helvetica, sans-serif;font-size: 12px;">'.$html.'</body></html>';
        $from = null;    
        if ($text->getFromTemplate() !== null) {
            $from = $text->getFromTemplate()->getExpediteur();
        }
        if($text->getSubjectTemplate()!=null){
            $subject = $text->getSubjectTemplate();
        }
        $email = new EmailContent;
        $email->setToEmail("compteveille@themisbanque.com");
        $email->setContent($html);
        $email->setFromEmail($from);
        $email->setSubject($subject);
        $email->setStatus("pending");
        $email->setAttachementDoc($text->getAttachmentTemplate());
        $this->EM->persist($email);
        $this->EM->flush();
        return "ok";
        
    }

    public function getRequestContent($datareqs){
        $clientrepo = $this->EM->getRepository(DataClient::class); 
        $clientsabrepo = $this->EM->getRepository(DataClientSab::class); 
        $optionrepo = $this->EM->getRepository(OptionsTabel::class);
        $typeclientrepo = $this->EM->getRepository(RefTypeClient::class);
        $string = "";
        $arrRequestContent = array();
        foreach($datareqs as $datareq){
            $objsab = $clientsabrepo->findOneBy(['request'=>$datareq->getId()]);
            $sabid = "";
            $companyname = "";
            $typeclient = "";
            $product = "";
            $createdate = "";
            if($objsab){
                $sabid = $objsab->getNumero();
            }
			
			$datareqstarepo = $this->EM->getRepository(DataRequestRequeststatus::class); 
            $datareqsta = $datareqstarepo->findOneBy(['id_request'=>$datareq->getId(), 'id_requeststatus' => 4]);

            $companyname = $datareq->getClient()->getCompanynameClient();
            if($datareqsta){
                $createdate=$datareqsta->getDateRequestRequeststatus();
			    $createdate=date("d/m/Y",$createdate->getTimeStamp());
            }
            $objtypeclient = $datareq->getClient()->getTypeclient();
            if($objtypeclient){
                $typeclient = $objtypeclient->getDescTypeclient();
            }
            $objoption = $optionrepo->findOneBy(['client'=>$datareq->getClient()]);
            if($objoption){
                if($objoption->getProduct()){
                    $product = $objoption->getProduct()->getName();
                }
            }
            // $string =$string.'<tr> <td>'.$sabid.'</td> <td>'.$companyname.'</td> <td>'.$createdate.'</td> <td>'.$typeclient.'</td> <td>'.$product.'</td></tr>';
            $objRequestContent = new RequestContent;
            $objRequestContent->setSabId($sabid);
            $objRequestContent->setCompanyName($companyname);
            $objRequestContent->setCreateDate($createdate);
            $objRequestContent->setTypeClient($typeclient);
            $objRequestContent->setProduct($product);
            array_push($arrRequestContent,$objRequestContent);

        }
        return $arrRequestContent; 
    }

    public function getForRequestDetail($datareqs){
        $clientrepo = $this->EM->getRepository(DataClient::class); 
        $clientsabrepo = $this->EM->getRepository(DataClientSab::class); 
        $optionrepo = $this->EM->getRepository(OptionsTabel::class);
        $typeclientrepo = $this->EM->getRepository(RefTypeClient::class);
        $arrData = array();
        foreach($datareqs as $datareq){
            $objsab = $clientsabrepo->findOneBy(['request'=>$datareq->getId()]);
            $sabid = "";
            $companyname = "";
            $typeclient = "";
            $product = "";
            if($objsab){
                $sabid = $objsab->getNumero();
            }
            $companyname = $datareq->getClient()->getCompanynameClient();
            $createdate = $datareq->getDateRequest();
            $createdate=date("d/m/Y",$createdate->getTimeStamp());
            $objtypeclient = $datareq->getClient()->getTypeclient();
            if($objtypeclient){
                $typeclient = $objtypeclient->getDescTypeclient();
            }
            $objoption = $optionrepo->findOneBy(['client'=>$datareq->getClient()]);
            if($objoption){
                if($objoption->getProduct()){
                    $product = $objoption->getProduct()->getName();
                }
            }
            array_push($arrData,[$sabid,$companyname,$createdate,$typeclient,$product]);
        }
        return $arrData; 
    }


    public function sendEmailWIthExtras($varrepo,$datatemprepo,$param,$actionId,$to_email,$extras){
        $text=$datatemprepo->findOneBy(['action_id'=>$actionId,'status'=>'Active']);
        $data=array();
        $lang='fr';
        $data=$this->getValue($varrepo,$param,$lang);
        $et=$text->getHtml();
        if($text->getSignature()){
            // if($text->getSignature()->getType() == 'text'){
            //     $et = $et.$text->getSignature()->getData();
            // }
            if($text->getSignature()->getType() == 'img'){
                // $imgurl = $this->params->get('app.dir').$text->getSignature()->getData();
                // $str = str_replace("/","\\",$imgurl);
                // $url =  base64_encode(file_get_contents($str));
                // $url = "data:image/png;base64,".$url;
                $uuid = $text->getSignature()->getUuid();
                $imgurl = '<img src="http://127.0.0.1:8000/api/signaturedownload_withuuid/'.$uuid.'" style=""/>';
                $et = $et.$imgurl;
            }
        }
        $edittext=$this->addVariableWithJSOveride($et,$data,$extras);
        $loader = new ArrayLoader(['my_template_name' => $edittext]);
        $env = new Environment($loader);
        $html = $env->render('my_template_name', []);
        // $env = new \Twig_Environment(new \Twig_Loader_Array(['my_template_name' => $edittext]));
        // $html = $env->render('my_template_name', array());
        $html = '<body style="font-family: Arial, Helvetica, sans-serif;font-size: 12px;">'.$html.'</body>';
        $requestrepo = $this->EM->getRepository(DataRequest::class); 
        $datarequest = $requestrepo->findOneBy(['client'=>$param]);
        $from = null;
        $subject = null;
        if ($text->getFromTemplate() !== null) {
            $from = $text->getFromTemplate()->getExpediteur();
        }
        if($text->getSubjectTemplate()!=null){
           $subject = $text->getSubjectTemplate();
        }
        $email = new EmailContent;
        $email->setToEmail($to_email);
        $email->setDataRequest($datarequest);
        $email->setContent($html);
        $email->setFromEmail($from);
        $email->setSubject($subject);
        $email->setStatus("pending");
        $email->setAttachementDoc($text->getAttachmentTemplate());
        $this->EM->persist($email);
        $this->EM->flush();
        return "ok";
        }  

    public function addVariableWithJSOveride(&$text,$data,$extras){
        // $text1=$text;
        $JS="";
        foreach($data as $datum){
            if($datum->getValue()!=null){
                if($datum->getScript()){
                   
                }
                if($datum->getVariablename()=='loginurl'){
                    $pattern="#".$datum->getVariablename()."#";

                    $pub_key = file_get_contents('../pub.crt');
                    $pri_key = file_get_contents('../pri.crt');
                    $rsa     = new RSA($pub_key, $pri_key);
                    $data    = $datum->getValue();
                    $encrypted = $rsa->base64Encrypt($data);
                    $link = '<table><tbody>
                    <tr>
                    <td align="center" bgcolor="#A32035" role="presentation" style="border:none;cursor:auto;mso-padding-alt:10px 25px;background:#A32035;" valign="middle">
                        <a href="'.$this->params->get('app.ser1').'/otp?euid='.base64_encode($encrypted).'" target="_blank" title="" data-targettype="webpage" data-targetname="" style="display:inline-block;background:#A32035;color:#FFFFFF;font-family:Arial, Helvetica, sans-serif;font-size:13px;font-weight:normal;line-height:120%;margin:0;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;"><span style="color:#ffffff">J\'ouvre mon compte</span></a>
                    </td>
                    </tr>
                    </tbody></table>';
                    $correctiontext = preg_replace("/$pattern/",$link,$text);
                   //$correctiontext = preg_replace("/$pattern/"," <div id=`id1`>solomon</div> ",$text);
                   $JS=$JS.$datum->getScript().";";
                   $text= $correctiontext;
                }
                else{
                $pattern="#".$datum->getVariablename()."#";
                 $correctiontext = preg_replace("/$pattern/",$datum->getValue(),$text);
                $JS=$JS.$datum->getScript().";";
                $text= $correctiontext;
                }
            }
           
           
        }

        foreach($extras as $extra){
            if($extra){               
                $pattern="#".$extra->getVariablename()."#";
                 $correctiontext = preg_replace("/$pattern/",$extra->getValue(),$text);
                $JS=$JS.$extra->getScript().";";
                $text= $correctiontext;
            }
        }
       // $text=$text.'<script  type="text/javascript">'.$JS.'</script>';
        return $text;
    }

    public function previewContent($previewdata,$data){
        $text1=$previewdata;
        foreach($data as $datum){
            if($datum->getVariablename()){
                $pattern="%".$datum->getVariablename()."%";
                $correctiontext = preg_replace("/$pattern/"," ".$datum->getSampleValue()." ",$text1);
                $text1= $correctiontext;
            }
           
           
        }
       
        return $text1;
       
       
    }

    public function documentEdit($text,$clientid){
        $data = array();
        $lang = 'fr';
        $varrepo=$this->EM->getRepository(RefVariable::class);
        $data = $this->getValue($varrepo,$clientid,$lang);
        // return $data;
        $edittext = $this->addVariableWithJS($text,$data);
        return $edittext;
    }


    public function sendMailSamp($clientid,$actionId,$to_email){
        $varrepo=$this->EM->getRepository(RefVariable::class);
        $datatemprepo=$this->EM->getRepository(DataTemplate::class);
        $text=$datatemprepo->findOneBy(['action_id'=>$actionId,'status'=>'Active']);
        $data=array();
        $lang='fr';
        $data=$this->getValue($varrepo,$clientid,$lang);
        $et=$text->getHtml();
        if($text->getSignature()){
            if($text->getSignature()->getType()=='text'){
                $et=$et.$text->getSignature()->getData();
            }
            if($text->getSignature()->getType()=='img'){
                $et=$et.'<img src="http://apikeprevos.keprevos.com/api/filedownloadwithMIME/'.$text->getSignature()->getData().'" style=""/>';
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

//     public function samplemail(){

        
//         $edittext = '<html><body style="font-family: Arial, Helvetica, sans-serif;font-size: 12px;"><p>Bonjour,</p><p>Nous vous remercions  de compte.</p><p>Vous pourrez à tout moment vous connecter à votre espace au lien suivant :</p><p><a href=https://dev.eer-front.dom01.fr/otp?euid=NWpmT0l3Sm5JWk5raFNHQUZpS1U4bzlCWEF2SE92WjIvT0c5eTIwcnFUZnF2SlVFTDV6VW5ZT1R0YnFsUEdHUDV6MFA3dll6aUFJWXdiYTlFNE9SOWRGRkFzT1dya3JCWjBGRGlnK3U5aloyQjlLTDJHKzR1enNPZ0o4cExpNWRJV2YwQ2ozZlpnbjNaeUU4K0NiRW1oU2pOeUlhMzhoTFRkQ0VVZkxISnlFT1FtVWUrREdMSlJ3TUN6bHNRWTdObGRlYWVxemQyWmkvQ0ZuaExWZEFNcVNNRlNCMmYwY3RVZVZWdjB3bzFZYWRoNUthTXZ3TEV6SEJ3THdMRjJRemhrL0F1Mk1LU2NkTUtLd0U4QUZCUmVoRnZNYktOK3hrdFVQSWV3cklGcmx6S1FtbXNPcldPRXVMUG50dURRWmtCWE1TWjVVZjNFOWk3RmRKMzUzbkFnPT0="style="background-color: #b51f39;border: none;color: white;padding: 15px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;">vijay</a></p><p><br></p><img src="http://127.0.0.1:8000/api/signaturedownload_withuuid/608d147a38e996eef7d97bf956b740fcc9b4f8b5" style=""/></body></html>';
       
//         $smtp_host_ip = 'smtp.gmail.com';

//         $transport = new Swift_SmtpTransport($smtp_host_ip, 465, 'ssl');
//         $transport->setUsername('vijayarethinam.info@gmail.com')->setPassword('500662417@vijay');
//         $mailer = new Swift_Mailer($transport);
//         $message =new Swift_Message();  
//         $message ->setFrom("vijayarethinam.info@gmail.com")
//         ->setTo("vijayarethinam.info@gmail.com");
//         $message->setBody($edittext, 'text/html','utf-8');
//         $mailer->send($message);
//         return "success";
     
// }
public function makesampleDocument($client_id){
    $url = "societe/fidcon.html";
    $handle  = fopen("societe/fidcon.html", "r+");
    $content = fread($handle, filesize($url));
    fclose($handle);
    $finalcontent = $this->documentEdit($content, $client_id);
    $html2pdf = new HTML2PDF('P', 'A4', 'fr');
    $html2pdf->writeHTML($finalcontent, 0);
    // $html2pdf->pdf->Output('C:/wamp64/www/apikeprevos/public/'.$folder.$pdfname, 'F');  
    $html2pdf->pdf->Output('C:\KEPREVOS Projects\FIDUCIAL\Repositories\apikeprevosv1\public\vasi1.pdf', 'F');

}

public function RefEmailAutomatic($datareqreq, $eloquaservice, $integration, $api, $clientrepo, $attorneyrepo, $userspacerepo,  $datacontrepo, $prodrepo, $sabrepo, $contralia, $ds){
    $max_action_ids = $this->maxId();
    
    // Get all actions in automatic
    $all_action_ids = $this->allId();    // For each action, get the status where it has to be launched
    foreach ($all_action_ids as $action_id) {
        $emailautodatas = $this->getEmailAutomaticIds($action_id);		
        foreach ($emailautodatas as $emailauto) {
            $refstatusrepo      = $this->EM->getRepository(RefRequeststatus::class);
            $refstatus 			= $refstatusrepo->findOneBy(["status_requeststatus" => $emailauto["statuscode"]]);			

            $requestrepo = $this->EM->getRepository(DataRequest::class);			
			$myreq = $requestrepo->findBy(["requeststatus" => $refstatus->getId()]);        
			
			// Check for the request, which are in the status, if the mail for the action id is done, if not check the number of day.			
			foreach ($myreq as $req) {

                $datareqreqrepo = $this->EM->getRepository(DataRequestRequeststatus::class);			
                $mydatareqreq = $datareqreqrepo->findOneBy(["request_id" => $req->getId()], ['id' => "DESC"]);
                
                $relancerepo = $this->EM->getRepository(DataRelance::class);			
                $relance = $relancerepo->findOneBy(["dataRequest" => $req->getId(), "emailAction" => $action_id], ['id' => "DESC"]);

                $drrsrepo = $this->EM->getRepository(DataRequestRequeststatus::class);			
                $drrs = $drrsrepo->findOneBy(["id_request" => $req->getId()], ["date_request_requeststatus" => "DESC"]);                

                $emailactionrepo = $this->EM->getRepository(EmailAction::class);
                $emailaction = $emailactionrepo->findOneBy(['id'=>$action_id]);
				
				if (!$relance || $relance->getDateRelance() < $mydatareqreq->getDateRequestRequeststatus()) {								
                    
					$currentdate = new \DateTime();
					$emailactiondate =  $drrs->getDateRequestRequeststatus();
					$diffcalc =$emailactiondate->diff($currentdate);					
					
					$datediff = $diffcalc->format('%a');					
					$no_of_days = $emailaction->getNoOfDays();
										
					if ($datediff >= $no_of_days && $no_of_days !== null) {
						
						$varrepo = $this->EM->getRepository(RefVariable::class);
						$datatemprepo = $this->EM->getRepository(DataTemplate::class);
						
						$userspacerepo  = $this->EM->getRepository(DataUserspace::class);
						$clientdetail	= $userspacerepo->findoneBy(['id_request'=> $req->getId()]);
						$to_email		= $clientdetail->getEmailUs();						
						
						$data = $this->sendEmail($varrepo,$datatemprepo,$req->getId(),$action_id,$to_email, false, true);
                        
                        $relance = new DataRelance;
                        $relance->setDataRequest($req);
                        $relance->setEmailAction($emailaction);
                        $relance->setDateRelance(new \DateTime());    
                        $this->EM->persist($relance);
                        $this->EM->flush();					
					}
				} else {
					
					$idtoclose = ['19' => 1, '28' => 1, '32' => 1, '37' => 1];
					
					if (isset($idtoclose[$action_id])) {
						$currentdate = new \DateTime();
						$emailactiondate = $relance[0]->getDateRelance();
						$diffcalc =$emailactiondate->diff($currentdate);					
						
						$datediff = $diffcalc->format('%d');											
						
						if ($datediff > 10) {
																					
                            $datareqreqstatus = new DataRequestRequststatus;
                            $datareqreqstatus->setIdRequest($req->getId());
                            $datareqreqstatus->setCode("999");
                            $datareqreqstatus->setLogin("");

							$datareqreq->createWithCode($eloquaservice, $datareqreqstatus, $this->EM, $integration, $api, $clientrepo, $attorneyrepo, $userspacerepo,  $datacontrepo, $prodrepo, $sabrepo, $contralia, $this, $ds);							
						}
					}
					
				}
            }

        }
    }
    return "ok";   
   
}

public function getEmailAutomaticIds($id){
    $q = "select * from ref_email_automatic where email_action_id =:id order by email_action_id DESC";
    $conn = $this->EM->getconnection();
    $emailautodatas = $conn->executeQuery($q, ['id' => $id])->fetchAll();
    return $emailautodatas;
}

public function allId(){
    $q = "SELECT id FROM email_action WHERE is_automatic = 'A' ORDER BY root Asc,title";
    $conn = $this->EM->getconnection();
    $ids = $conn->executeQuery($q)->fetchAll();
    $allactionids = array();
    foreach($ids as $id){
        array_push($allactionids,(int)$id['id']);
    }
    return $allactionids;
}

public function maxId(){

    $q = "select l.id FROM email_action l
    inner join (
      select 
        root, max(id) as laststatus 
      from email_action where is_automatic = 'A'
      group by root
    ) r
      on l.id = r.laststatus and l.root = r.root
    order by id asc";
    $conn = $this->EM->getconnection();
    $ids = $conn->executeQuery($q)->fetchAll();
    $maxids = array();
    foreach($ids as $id){
        array_push($maxids,(int)$id['id']);
    }
    return $maxids;

}

public function ResendMail($varrepo,$datatemprepo,$email) {
        $pub_key = file_get_contents('../pub.crt');
        $pri_key = file_get_contents('../pri.crt');
        $userspacerepo  = $this->EM->getRepository(DataUserspace::class);
        $reqstatusrepo  = $this->EM->getRepository(RefRequeststatus::class);
        $datareqrepo    = $this->EM->getRepository(DataRequest::class);
        $rsa            = new RSA($pub_key, $pri_key);

        $email          = $rsa->base64Decrypt(base64_decode($email));
        $chkemail       = $userspacerepo->findOneBy(['email_us'=>$email,'active_us'=>'Active'], ['id' => 'DESC']);
        $datarequest    = $datareqrepo->findOneBy(['id'=>$chkemail->getIdRequest()]);
        $clientdata     = $datarequest->getClient();
        $clientid       = $clientdata->getId();
        $actionid = 14;
        
        $emailchk = $userspacerepo->findOneBy(['email_us'=>$email,'active_us'=>'Active'], ['id' => 'DESC']);
        if ($emailchk) {
            if ($emailchk->getIdRequest()->getRequeststatus() !== null) {
                $clientstatusid = $emailchk->getIdRequest()->getRequeststatus()->getId();
                $count = 0;
                $chkstatuses = ["190", "191", "170", "180", "171", "172", "999"];
                foreach($chkstatuses as $chkstatus) {
                    $requeststatus = $reqstatusrepo->findOneBy(['status_requeststatus'=>$chkstatus]);
                    if ($requeststatus) {
                        if ($requeststatus->getId() == $clientstatusid) {  
                            $count = $count + 1;
                            break;
                        }
                    }
                }
            }

            if($count != 0) {
                return 'ko';
            } else {
                $data = $this->sendEmail($varrepo,$datatemprepo,$clientid,$actionid,$email,true);
                return "ok";
            }
        }  
        return 'ko';


}

public function RefEmailAutomaticTest($datareqreq, $eloquaservice, $integration, $api, $clientrepo, $attorneyrepo, $userspacerepo,  $datacontrepo, $prodrepo, $sabrepo, $contralia, $ds){
    $max_action_ids = $this->maxId();
    
    // Get all actions in automatic
    $all_action_ids = $this->allId();    // For each action, get the status where it has to be launched
    foreach ($all_action_ids as $action_id) {
        $emailautodatas = $this->getEmailAutomaticIds($action_id);		
        foreach ($emailautodatas as $emailauto) {
            $refstatusrepo      = $this->EM->getRepository(RefRequeststatus::class);
            $refstatus 			= $refstatusrepo->findOneBy(["status_requeststatus" => $emailauto["statuscode"]]);			

            $requestrepo = $this->EM->getRepository(DataRequest::class);			
			$myreq = $requestrepo->findBy(["requeststatus" => $refstatus->getId()]);
			
			// Check for the request, which are in the status, if the mail for the action id is done, if not check the number of day.			
			foreach ($myreq as $req) {
                
                $datareqreqrepo = $this->EM->getRepository(DataRequestRequeststatus::class);			
                $mydatareqreq = $datareqreqrepo->findOneBy(["request_id" => $req->getId()], ['id' => "DESC"]);
                
                $relancerepo = $this->EM->getRepository(DataRelance::class);			
                $relance = $relancerepo->findOneBy(["dataRequest" => $req->getId(), "emailAction" => $action_id], ['id' => "DESC"]);

                $drrsrepo = $this->EM->getRepository(DataRequestRequeststatus::class);			
                $drrs = $drrsrepo->findOneBy(["id_request" => $req->getId()], ["date_request_requeststatus" => "DESC"]);                

                $emailactionrepo = $this->EM->getRepository(EmailAction::class);
                $emailaction = $emailactionrepo->findOneBy(['id'=>$action_id]);
				
				if (!$relance || $relance->getDateRelance() < $mydatareqreq->getDateRequestRequeststatus()) {										

					$currentdate = new \DateTime();
					$emailactiondate =  $drrs->getDateRequestRequeststatus();
					$diffcalc =$emailactiondate->diff($currentdate);					
					
					$datediff = $diffcalc->format('%a');					
					$no_of_days = $emailaction->getNoOfDays();
						                    
					if ($datediff >= $no_of_days && $no_of_days !== null) {
						
						$varrepo = $this->EM->getRepository(RefVariable::class);
						$datatemprepo = $this->EM->getRepository(DataTemplate::class);
						
						$userspacerepo  = $this->EM->getRepository(DataUserspace::class);
						$clientdetail	= $userspacerepo->findoneBy(['id_request'=> $req->getId()]);
						$to_email		= $clientdetail->getEmailUs();						
						/*
						$data = $this->sendEmail($varrepo,$datatemprepo,$req->getId(),$action_id,$to_email, false, true);
                        
                        $relance = new DataRelance;
                        $relance->setDataRequest($req);
                        $relance->setEmailAction($emailaction);
                        $relance->setDateRelance(new \DateTime());    
                        $this->EM->persist($relance);
                        $this->EM->flush();		*/			
					}
				} else {
					
					/*$idtoclose = ['19' => 1, '28' => 1, '32' => 1, '37' => 1];
					
					if (isset($idtoclose[$action_id])) {
						$currentdate = new \DateTime();
						$emailactiondate = $relance[0]->getDateRelance();
						$diffcalc =$emailactiondate->diff($currentdate);					
						
						$datediff = $diffcalc->format('%d');											
						
						if ($datediff > 10) {
																					
                            $datareqreqstatus = new DataRequestRequststatus;
                            $datareqreqstatus->setIdRequest($req->getId());
                            $datareqreqstatus->setCode("999");
                            $datareqreqstatus->setLogin("");

							$datareqreq->createWithCode($eloquaservice, $datareqreqstatus, $this->EM, $integration, $api, $clientrepo, $attorneyrepo, $userspacerepo,  $datacontrepo, $prodrepo, $sabrepo, $contralia, $this, $ds);							
						}
					}*/
					
				}
            }

        }
    }
    return "ok";   
   
}

}
