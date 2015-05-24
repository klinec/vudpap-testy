<?php

namespace AppBundle\Controller;

use DM\AjaxCom\Handler as AjaxCom;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @package Everlution\ApplicationBundle
 * @author Miroslav Demovic <miroslav.demovic@everlution.com>
 */
class Controller extends SymfonyController
{
    const PAGE_SIZE = 20;

    const DEFAULT_TITLE = 'Testy';

    const CONTAINER_MAIN = '#main_content';

    public function getPageContainer($name = false)
    {
        if (!$name) {
            return self::CONTAINER_MAIN;
        }

        if (empty($this->pageContainer[$name])) {
            throw new \Exception('Trying to access a non-existing container');
        }

        return $this->pageContainer[$name];
    }

    public function setPageContainer($name, $value)
    {
        if (empty($name) || empty($value)) {
            throw new \Exception('Cannot set a empty name or value container');
        }

        $this->pageContainer[$name] = $value;

        return $this;
    }

    /**
     * Generate Json Response object
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     * @param Response $response A response instance
     *
     * @return JsonResponse A Json Response instance
     */
    public function renderAjax(
        $view,
        array $parameters = array(),
        Response $response = null
    ) {
        $ajax = false;
        $isModal = false;

        $request = $this->getRequest();
        if (!$request->server->get('HTTP_X_AJAXCOM')) {
            return false;
        }

        $ajaxClass = $this->getAjaxClass();
        if (!empty($parameters['ajax_custom'])
            && $parameters['ajax_custom'] instanceof $ajaxClass
            && !$parameters['ajax_custom']->isEmpty()
        ) {
            $ajax = $parameters['ajax_custom'];
        }

        if (!empty($parameters['is_modal'])) {
            $isModal = $parameters['is_modal'];
        }

        if (!$ajax) {
            $ajax = $this->getAjax();

            if (!$request->server->get('HTTP_X_AJAXCOM_BLOCKS')) {
                if (!empty($parameters['modal_close'])) {
                    $ajax->modal('.modal')->close();
                }

                if ($isModal) {
                    $parameters['extend_layout']
                        = 'AppBundle::layout_modal.html.twig';
                    $parameters['is_modal'] = true;
                    $ajax->modal(
                        $this->renderView($view, $parameters),
                        'twitterbootstrap3'
                    );
                } else {
                    if (!empty($parameters['ajax_url'])) {
                        $ajax->changeUrl($parameters['ajax_url']);
                    }

                    $parameters['extend_layout']
                        = 'AppBundle::layout_empty.html.twig';
                    $ajax->container('#main_content')->animate(false)->html(
                        $this->renderView($view, $parameters)
                    );
                }
            }
        }

        if ($request->server->get('HTTP_X_AJAXCOM_BLOCKS')) {
            $blocks = json_decode(
                $request->server->get('HTTP_X_AJAXCOM_BLOCKS'),
                true
            );

            if ($isModal) {
                $parameters['extend_layout'] = 'AppBundle::layout_modal.html.twig';
            } else {
                $parameters['extend_layout'] = 'AppBundle::layout_main.html.twig';
            }

            if (!empty($parameters['ajax_url'])) {
                $ajax->changeUrl($parameters['ajax_url']);
            }

            if (is_string($blocks)) {
                $html = $this->get('twig')
                    ->loadTemplate($view)
                    ->renderBlock($blocks, $parameters);
                $ajax->container('#' . $blocks)->html($html);
            } elseif (is_array($blocks)) {
                foreach ($blocks as $index => $block) {
                    if (is_string($block)) {
                        $html = $this->get('twig')
                            ->loadTemplate($view)
                            ->renderBlock($block, $parameters);
                        $ajax->container('#' . $block)->html($html);
                    } else {
                        if (!isset($block['selector'])) {
                            throw $this->createNotFoundException(
                                'Invalid X-AjaxCom-Blocks header: \
                                key "selector" not found for block "' . $index . '"'
                            );
                        }
                        if (!isset($block['method'])) {
                            throw $this->createNotFoundException(
                                'Invalid X-AjaxCom-Blocks header: \
                                key "method" not found for block "' . $index . '"'
                            );
                        }

                        $html = $this->get('twig')
                            ->loadTemplate($view)
                            ->renderBlock($index, $parameters);

                        switch ($block['method']) {
                            case 'html':
                                $ajax->container($block['selector'])->html($html);
                                break;
                            case 'replaceWith':
                                $ajax->container($block['selector'])->replaceWith($html);
                                break;
                            default:
                                throw $this->createNotFoundException(
                                    "Invalid X-AjaxCom-Blocks header: \
                                Method '{$block['method']}' not found.
                                Allowed methods are 'html', 'replaceWith'"
                                );
                        }
                    }
                }
            } else {
                throw $this
                    ->createNotFoundException('Invalid X-AjaxCom-Blocks header');
            }
        }

        if (!empty($parameters['callback'])) {
            if (is_array($parameters['callback'])) {
                foreach ($parameters['callback'] as $callback) {
                    $ajax->callback($callback);
                }
            } else {
                $ajax->callback($parameters['callback']);
            }
        }

        $class = str_replace('route_', '', $request->get('_route'));
        $ajax->callback('setBodyClass', array('class' => $class));
        $ajax->container('title')->html(self::DEFAULT_TITLE);

        $response = new JsonResponse();
        $response->headers->set(
            "Cache-Control",
            'no-cache,max-age=0,must-revalidate,no-store'
        );
        $response->setData($ajax->respond());

        return $response;
    }

    /**
     * Overrides standard render, based on request renders
     * either ajax or regular page
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     * @param Response $response A response instance
     *
     * @return Response A Response instance
     */
    public function render(
        $view,
        array $parameters = array(),
        Response $response = null
    ) {

        if (!$render = $this->renderAjax($view, $parameters, $response)) {
            $render = parent::render($view, $parameters, $response);
        }

        return $render;
    }

    public function assertAccess($permission, $entity = false)
    {
        if ($entity === null) {
            throw $this->createNotFoundException('Unable to find entity');
        }

        $securityContext = $this->get('security.context');
        if (false === $securityContext->isGranted($permission, $entity)) {
            throw new AccessDeniedException();
        }
    }

    /**
     * Add flash message to display in convenient moment
     *
     * @param  string $message
     * @param  string $level
     * @return void
     */
    public function addFlash($message, $level = 'info')
    {
        $this->get('session')->getFlashBag()->add($level, $message);
    }

    /**
     * Add flash message to AjaxCom object
     *
     * @param  \DM\AjaxCom\Handler $ajax
     * @param  string $message
     * @param  string $level
     * @param  string $type
     * @return void
     */
    public function addAjaxFlash(
        AjaxCom &$ajax,
        $message,
        $level = 'success',
        $type = 'replace'
    ) {
        $this->get('session')->getFlashBag()->add($level, $message);

        if ($this->get('request')->server->get('HTTP_X_AJAXCOM')) {
            $html = $this->renderView(
                '@App/Common/flash_messenger.html.twig'
            );

            $ajax->flashMessage($html, $level, $type);
        }
    }

    public function hasRight($requiredRoles = null)
    {
        if (!empty($requiredRoles)
            && false === $this->get('security.context')->isGranted($requiredRoles)
        ) {
            return false;
        }

        return true;
    }
}
