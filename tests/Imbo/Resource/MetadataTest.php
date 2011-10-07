<?php
/**
 * Imbo
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
 * @package Imbo
 * @subpackage Unittests
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/imbo
 */

namespace Imbo\Resource;

/**
 * @package Imbo
 * @subpackage Unittests
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/imbo
 */
class MetadataTest extends ResourceTests {
    protected function getNewResource() {
        return new Metadata();
    }

    public function testDelete() {
        $imageIdentifier = md5(microtime());
        $publicKey = md5(microtime());

        $writer = $this->getMock('Imbo\Http\Response\ResponseWriterInterface');
        $writer->expects($this->once())->method('write')->with($this->isType('array'), $this->request, $this->response);

        $resource = $this->getNewResource();
        $resource->setResponseWriter($writer);

        $this->request->expects($this->once())->method('getImageIdentifier')->will($this->returnValue($imageIdentifier));
        $this->request->expects($this->once())->method('getPublicKey')->will($this->returnValue($publicKey));
        $this->database->expects($this->once())->method('deleteMetadata')->with($publicKey, $imageIdentifier);

        $resource->delete($this->request, $this->response, $this->database, $this->storage);
    }
}
