<?php declare(strict_types=1);
namespace Imbo\Image\OutputConverter;

use Imagick;
use ImagickException;
use Imbo\Exception\OutputConverterException;
use Imbo\Http\Response\Response;
use Imbo\Model\Image;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Imbo\Image\OutputConverter\Basic
 */
class BasicTest extends TestCase
{
    private Basic $converter;

    public function setUp(): void
    {
        $this->converter = new Basic();
    }

    /**
     * @covers ::getSupportedMimeTypes
     */
    public function testReturnsSupportedMimeTypes(): void
    {
        $types = $this->converter->getSupportedMimeTypes();

        $this->assertIsArray($types);

        $this->assertContains('image/png', array_keys($types));
        $this->assertContains('image/jpeg', array_keys($types));
        $this->assertContains('image/gif', array_keys($types));
    }

    /**
     * @covers ::convert
     */
    public function testCanConvertImage(): void
    {
        $extension = 'png';
        $mimeType = 'image/png';

        $imagick = $this->createMock(Imagick::class);
        $imagick
            ->expects($this->once())
            ->method('setImageFormat')
            ->with($extension);

        $image = $this->createMock(Image::class);
        $image
            ->expects($this->once())
            ->method('setHasBeenTransformed')
            ->with(true);

        $this->assertNull($this->converter->convert($imagick, $image, $extension, $mimeType));
    }

    /**
     * @covers ::convert
     */
    public function testThrowsExceptionOnImagickFailure(): void
    {
        $extension = 'png';

        $imagick = $this->createMock(Imagick::class);
        $imagick
            ->expects($this->once())
            ->method('setImageFormat')
            ->with($extension)
            ->willThrowException(new ImagickException('some error'));

        $this->expectExceptionObject(new OutputConverterException('some error', Response::HTTP_BAD_REQUEST));
        $this->converter->convert(
            $imagick,
            $this->createMock(Image::class),
            $extension,
            'image/png',
        );
    }
}
