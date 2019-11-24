<?php

namespace App\Http\Controller;

use App\Application\Command\FetchFeed;
use App\Application\Service\FeedService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    const TOPIC_COUNT = 10;
    /**
     * @Route("/", name="homepage")
     */
    public function index(FeedService $feedService)
    {
        if(empty($this->getUser())) {
            return $this->redirectToRoute('user_login');
        }

        $fetchFeed = new FetchFeed();
        $fetchFeed->topicCount = self::TOPIC_COUNT;
        $feed = $feedService->getFeed($fetchFeed);

        return $this->render('Page/Index/index.twig', [
            'user_info' => 'IndexController',
            'news' => $feed
        ]);
    }
}
