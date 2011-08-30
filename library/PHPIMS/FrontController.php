<?php
/**
 * PHPIMS
 *
 * Copyright (c) 2011 Christer Edvartsen <cogo@starzinger.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * * The above copyright notice and this permission notice shall be included in
 *   all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @package PHPIMS
 * @subpackage Server
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/phpims
 */

namespace PHPIMS;

use PHPIMS\Response\Response;
use PHPIMS\Request\RequestInterface;
use PHPIMS\Image\Image;

/**
 * Client that interacts with the server part of PHPIMS
 *
 * This client includes methods that can be used to easily interact with a PHPIMS server
 *
 * @package PHPIMS
 * @subpackage Server
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/phpims
 */
class FrontController {
    /**
     * Configuration
     *
     * @var array
     */
    private $config;

    /**
     * Class constructor
     *
     * @param array $config Configuration array
     */
    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Create a resource object based on the request
     *
     * @param PHPIMS\Request\RequestInterface $request A request instance
     * @return PHPIMS\Resource\ResourceInterface
     * @throws PHPIMS\Exception
     */
    private function resolveResource(RequestInterface $request) {
        if ($request->isImageRequest()) {
            $resource = new Resource\Image();
        } else if ($request->isImagesRequest()) {
            $resource = new Resource\Images();
        } else if ($request->isMetadataRequest()) {
            $resource = new Resource\Metadata();
        } else {
            throw new Exception('Invalid request', 400);
        }

        return $resource;
    }

    /**
     * Handle a request
     *
     * @param PHPIMS\Request\RequestInterface $request The request object
     * @return PHPIMS\Response\ResponseInterface
     * @throws PHPIMS\Exception
     */
    public function handle(RequestInterface $request) {
        if ($request->getMethod() === RequestInterface::METHOD_BREW) {
            throw new Exception('I\'m a teapot!', 418);
        }

        $response   = new Response();
        $database   = $this->config['database'];
        $storage    = $this->config['storage'];
        $httpMethod = $request->getMethod();
        $methodName = strtolower($httpMethod);

        // Fetch the resource instance
        $resource = $this->resolveResource($request);

        // Execute pre-exec plugins
        foreach ($resource->getPreExecPlugins($httpMethod) as $plugin) {
            $plugin->exec($request, $response, $database, $storage);
        }

        $resource->$methodName($request, $response, $database, $storage);

        // Execute post-exec plugins
        foreach ($resource->getPostExecPlugins($httpMethod) as $plugin) {
            $plugin->exec($request, $response, $database, $storage);
        }

        return $response;
    }
}
