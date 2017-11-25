<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/url")
 */
class MinifyController extends Controller
{
	/**
     * @Route("/all", name="get_all_minified")
     * @Method({"GET"})
     */
    public function minifiedAction()
    {
        try {
            $urls = $this->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:Minified')
                ->getAllMinifiedUrls();
            return new JsonResponse($urls);
        } catch (\Exception $e) {
            return new JsonResponse([]);
        }
    }

    /**
     * @Route("/minify", name="post_minify_url")
     * @Method({"POST"})
     */
    public function minifyAction(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = ['shortCode' => null];
        if (isset($content['url'])) {
            $response = $this->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:Minified')
                ->addNewUrl($content['url']);
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/update", name="post_update_url")
     * @Method({"POST"})
     */
    public function updateAction(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        if (isset($content['shortCode']) && isset($content['id'])) {
            $response = $this->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:Minified')
                ->updateUrlShortCode($content['id'], $content['shortCode']);
        } else {
            $response = ['status' => 'fail', 'message' => 'Bad request'];
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/delete", name="post_delete_url")
     * @Method({"POST"})
     */
    public function deleteAction(Request $request)
    {
    	$content = json_decode($request->getContent(), true);
    	$response = ['status' => 'fail', 'message' => 'Bad ID'];
    	if (isset($content['id']) && is_numeric($content['id'])) {
    		$response = $this->getDoctrine()
    			->getManager()
    			->getRepository('AppBundle:Minified')
    			->deleteUrl($content['id']);
    	}
    	return new JsonResponse($response);
    }
}
