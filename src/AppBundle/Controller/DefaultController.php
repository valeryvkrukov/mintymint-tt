<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/{shortCode}", name="redirect", requirements={"shortCode":"^[A-Za-z0-9]+$"})
     * @Method({"GET"})
     */
    public function redirectAction(Request $request, $shortCode)
    {
        $url = $this->getDoctrine()
			->getRepository('AppBundle:Minified')
			->findOneBy(['shortCode' => $shortCode]);
	        if ($url) {
		        $geodata = $this->container
				->get('bazinga_geocoder.geocoder')
				->using('geo_plugin')
				->geocode($request->server->get('REMOTE_ADDR'));
			$targetUrl = $this->getDoctrine()
	        		->getRepository('AppBundle:Statistic')
				->registerHitForUrl($url, $geodata->first());
		} else {
			$targetUrl = $this->generateUrl('homepage');
		}
		$response = new RedirectResponse($targetUrl);
		$response->setPrivate();
		$response->setMaxAge(0);
		$response->setSharedMaxAge(0);
		$response->headers->addCacheControlDirective('must-revalidate', true);
		$response->headers->addCacheControlDirective('no-store', true);
		return $response;
    }
}
