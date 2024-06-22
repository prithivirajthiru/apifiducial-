<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BusinessManagerService;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
class BusinessManagerController extends AbstractController
{

    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/business/manager", name="business_manager")
     */
    public function index()
    {
        return $this->render('business_manager/index.html.twig', [
            'controller_name' => 'BusinessManagerController',
        ]);
    }

     /**
     * @Route("/api/superviseactivity/requestcountwithstatuscode/period/{period}", name="getActivityRequestByStatusWithPeriod",methods={"GET"})
     */
    public function getActivityRequestByStatusWithPeriod($period,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getRequestByStatusWithPeriod($period);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

     /**
     * @Route("/api/superviseactivity/requestcountwithstatuscode/date/{date}", name="getActivityRequestByStatusWithDate",methods={"GET"})
     */
    public function getActivityRequestByStatusWithDate($date,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getRequestByStatusWithDate($date);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

     /**
     * @Route("/api/superviseactivity/requestcountwithorigin/period/{period}", name="getActivityRequestByOriginWithPeriod",methods={"GET"})
     */
    public function getActivityRequestByOriginWithPeriod($period,BusinessManagerService $businessManagerService)
    {
        $OriginWithCount = $businessManagerService->_getRequestByOriginWithPeriod($period);
        return new ApiResponse($OriginWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

     /**
     * @Route("/api/superviseactivity/requestcountwithorigin/date/{date}", name="getActivityRequestByOriginWithDate",methods={"GET"})
     */
    public function getActivityRequestByOriginWithDate($date,BusinessManagerService $businessManagerService)
    {
        $OriginWithCount = $businessManagerService->_getRequestByOriginWithDate($date);
        return new ApiResponse($OriginWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

     /**
     * @Route("/api/supervisebusiness/requestcountusingperiod/{type}/{period}", name="getBusinessRequestByStatusWithPeriod",methods={"GET"})
     */
    public function getBusinessRequestByPeriod($period,$type,BusinessManagerService $businessManagerService)
    {
        $Count = $businessManagerService->_getRequestByPeriod($period,$type);
        return new ApiResponse($Count, 200,["Content-Type"=>"application/json"],'json','success');
    }

     /**
     * @Route("/api/supervisebusiness/requestcountforstatus", name="getBusinessRequestByStatusWithPeriod",methods={"POST"})
     */
    public function getBusinessRequestforStatus(Request $request,BusinessManagerService $businessManagerService)
    {
        $Count = $businessManagerService->_getRequestforStatus($request);
        return new ApiResponse($Count, 200,["Content-Type"=>"application/json"],'json','success',['timezone']);
    }

     /**
     * @Route("/api/supervisebusiness/requestcountusingdate/{type}/{date}", name="getBusinessRequestByStatusWithDate",methods={"GET"})
     */
    public function getBusinessRequestByDate($date,$type,BusinessManagerService $businessManagerService)
    {
        $Count = $businessManagerService->_getRequestByDate($date,$type);
        return new ApiResponse($Count, 200,["Content-Type"=>"application/json"],'json','success');
    }

      /**
     * @Route("/api/supervisebusiness/getRequestByTypeWithData/{period}", name="getRequestByTypeWithData",methods={"GET"})
     */
    public function getRequestByTypeWithData($period,BusinessManagerService $businessManagerService)
    {
        $Count = $businessManagerService->_getRequestByTypeWithData($period);
        return new ApiResponse($Count, 200,["Content-Type"=>"application/json"],'json','success');
    }

      /**
     * @Route("/api/supervisebusiness/getRequestByTypeWithData/usingdateorall/{date}", name="getRequestByTypeWithDataDate",methods={"GET"})
     */
    public function getRequestByTypeWithDataDate($date,BusinessManagerService $businessManagerService)
    {
        $Count = $businessManagerService->_getRequestByTypeWithDataDate($date);
        return new ApiResponse($Count, 200,["Content-Type"=>"application/json"],'json','success');
    }

     /**
     * @Route("/api/superviseactivity/requestcountwithstatuscode/all/withyearbased", name="getActivityRequestByStatusallwithyearbased",methods={"GET"})
     */
    public function getActivityRequestByStatusWithYearBased(BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getActivityRequestByStatusWithYearBased();
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

      /**
     * @Route("/api/superviseactivity/requestcountwithstatuscode/withperiod/{period}", name="getActivityRequestByStatusallwithperiodbased",methods={"GET"})
     */
    public function getActivityRequestByStatusWithperiodBased($period,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getActivityRequestByStatusWithPeriodBased($period);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

     /**
     * @Route("/api/getaveragetime", name="getActivityRequestAverageTime",methods={"POST"})
     */
    public function getActivityRequestAverageTime(Request $request,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getActivityRequestAverageTime($request);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

     /**
     * @Route("/api/getCountOfRequeststatus", name="getCountOfRequeststatus",methods={"POST"})
     */
    public function getCountOfRequeststatus(Request $request,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getCountOfRequeststatus($request);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

    /**
     * @Route("/api/getCountOfRequeststatus/chart", name="getCountOfRequeststatusChart",methods={"POST"})
     */
    public function getCountOfRequeststatusChart(Request $request,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getCountOfRequeststatusChart($request);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

    /**
     * @Route("/api/superviseactivity/requestcountwithstatuscode/withperiodv1/{period}", name="getActivityRequestByStatusallwithperiodbasedv1",methods={"GET"})
     */
    public function getActivityRequestByStatusWithperiodBasedv1($period,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getActivityRequestByStatusWithPeriodBasedv1($period);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

    /**
     * @Route("/api/forall/getCountOfRequeststatus/{period}", name="getCountOfRequeststatusForAll",methods={"GET"})
     */
    public function getCountOfRequeststatusForAll($period,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getCountOfRequeststatusForAll($period);
		//$statusWithCount = $businessManagerService->_tempsMoyen(0,0,0);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

    /**
     * @Route("/api/forall/getCountOfRequeststatus/givendate", name="getgivendateCountOfRequeststatusForAll",methods={"POST"})
     */
    public function getGivendateCountOfRequeststatusForAll(Request $request,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getCountOfRequeststatusGivenDateForAll($request);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

    /**
     * @Route("/api/forall/getCountOfRequeststatus/uptoGivendate", name="getgivenUptodateCountOfRequeststatusForAll",methods={"POST"})
     */
    public function getGivenUptodateCountOfRequeststatusForAll(Request $request,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getCountOfRequeststatusGivenUptoDateForAll($request);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

    /**
     * @Route("/api/get/refuses", name="getRefuses",methods={"POST"})
     */
    public function getRefuses(Request $request,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getRefuses($request);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }

    /**
     * @Route("/api/get/nextstatuscount", name="getNextstatusCount",methods={"POST"})
     */
    public function getNextstatusCount(Request $request,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->_getNextstatusCount($request);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }
	
	/**
     * @Route("/api/get/tempsmoyen", name="getTempsMoyen",methods={"POST"})
     */
    public function getTempsMoyen(Request $request,BusinessManagerService $businessManagerService)
    {
        $statusWithCount = $businessManagerService->forAllRequestMoyenneDetail($request);
        return new ApiResponse($statusWithCount, 200,["Content-Type"=>"application/json"],'json','success');
    }
}
