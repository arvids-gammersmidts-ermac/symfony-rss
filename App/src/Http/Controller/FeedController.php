<?php

namespace App\Http\Controller;

use App\Application\Command\FetchFeed;
use App\Application\Service\FeedService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * Class BinanceController
 * @package App\Controller
 */
class FeedController extends Controller
{
    // TODO to auth root
    // TODO to api controller
    /**
     * @Route("/feed/", name="rss_feed")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function feed(Request $request, FeedService $feedService): JsonResponse
    {
        $fetchFeed = new FetchFeed();
        $feed = $feedService->getFeed($fetchFeed);

        return new JsonResponse(
            [
                "feed" => $feed
            ],
            JsonResponse::HTTP_OK
        );
    }
}
