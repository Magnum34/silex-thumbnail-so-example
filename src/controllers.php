<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ThumbnailSo\ThumbnailSo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})
->bind('homepage');

$app->post('/send', function (Request $request) use ($app) {
    try {
        $params = $request->request->all();
        $file = $request->files->get('file');
        if(array_key_exists("storage", $params) && $file){
            $thumbnail = new ThumbnailSo($file->getPathName());
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $thumbnail->resizeToMaxSide(150);
            switch($params['storage']){
                case 'local':
                    $thumbnail->save('local', 'example', $filename);
                    break;
                case 's3':
                    $thumbnail->save('s3', 'example', $filename );
                    break;
                case 'dropbox':
                    $thumbnail->save('dropbox', 'example', $filename);
                    break;

            }
            $app['session']->getFlashBag()->add('success', 'Saved');
        }else{
            $app['session']->getFlashBag()->add('error', "Not found file");
        }
    }catch(Exception $exc){
        $app['session']->getFlashBag()->add('error', $exc->getMessage());
    }

    return $app->redirect('/');
})
->bind('send');

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
