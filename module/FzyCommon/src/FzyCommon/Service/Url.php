<?php
namespace FzyCommon\Service;

/**
 * Class Url
 * @package FzyCommon\Service
 * Service Key: FzyCommon\Url
 */
class Url extends Base
{
    /**
     * @param $routeName
     * @param  array  $routeParams
     * @param  array  $routeOptions
     * @return string
     */
    public function fromRoute($routeName, $routeParams = array(), $routeOptions = array())
    {
        /* @var $router \Laminas\Mvc\Router\Http\TreeRouteStack */
        $router = $this->getServiceLocator()->get('router');

        return $router->assemble($routeParams, array_merge($routeOptions, array('name' => $routeName)));
    }

    /**
     * Returns the S3 URL for the file at the specified key.
     * expiration determines how long the URL will work
     * downlaodAs specifies what the file name should start as when downloading
     * @param $key
     * @param  null   $expiration
     * @param  null   $downloadAs
     * @return string
     */
    public function fromS3($key, $expiration = null, $downloadAs = null)
    {
        /** @var \Aws\S3\S3Client $s3 */
        $s3 = $this->getServiceLocator()->get('FzyCommon\Service\Aws\S3');
        $bucket = $this->getServiceLocator()->get('FzyCommon\Service\Aws\S3\Config')->get('bucket');

        if (empty($expiration)) {
            return $s3->getObjectUrl($bucket, $key);
        }

        $cmdArgs = array(
            'Bucket' => $bucket,
            'Key' => $key,
        );
        if (!empty($downloadAs)) {
            $cmdArgs['ResponseContentDisposition'] = 'attachment;filename=' . $downloadAs;
        }

        $cmd = $s3->getCommand('GetObject', $cmdArgs);
        $request = $s3->createPresignedRequest($cmd, $expiration);

        return (string) $request->getUri();
    }
}
