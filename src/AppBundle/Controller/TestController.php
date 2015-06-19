<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function basicAction(Request $request, $testName, $testUrlId = '', $previous = '')
    {
        $testProvider = $this->get('test.loader')->getTest($testName);

        if (!$testProvider) {
            throw $this->createNotFoundException('Test not found');
        }

        if (empty($testUrlId)) {
            $testUrlId = $testProvider->init();
        } elseif (!$testProvider->load($testUrlId)) {
            throw $this->createNotFoundException('Undefined test');
        }

        if (!empty($previous)) {
            $testProvider->goToPrevious();
        }

        $testProvider->process($request);

        return new Response($testProvider->render());
    }
}
