<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/stat")
 */
class StatisticController extends Controller
{
	/**
	 * @Route("/{id}", name="url_statistics")
	 * @Method({"GET"})
	 */
	public function statAction(Request $request, $id)
	{
		try {
			$statistics = $this->getDoctrine()
				->getRepository('AppBundle:Minified')
				->getStatisticForUrl($id);
			return new JsonResponse($statistics);
		} catch (\Exception $e) {
			return new JsonResponse(['err' => $e->getMessage()]);
		}
	}
}