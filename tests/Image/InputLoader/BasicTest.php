<?php declare(strict_types=1);
namespace Imbo\Image\InputLoader;

use Imagick;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Imbo\Image\InputLoader\Basic
 */
class BasicTest extends TestCase
{
    private Basic $loader;

    public function setUp(): void
    {
        $this->loader = new Basic();
    }

    /**
     * @covers ::getSupportedMimeTypes
     */
    public function testReturnsSupportedMimeTypes(): void
    {
        $types = $this->loader->getSupportedMimeTypes();

        $this->assertIsArray($types);

        $this->assertContains('image/png', array_keys($types));
        $this->assertContains('image/jpeg', array_keys($types));
        $this->assertContains('image/gif', array_keys($types));
        $this->assertContains('image/tiff', array_keys($types));
    }

    /**
     * @covers ::load
     */
    public function testLoadsImage(): void
    {
        $blob = file_get_contents(FIXTURES_DIR . '/1024x256.png');

        $imagick = $this->createMock(Imagick::class);
        $imagick
            ->expects($this->once())
            ->method('readImageBlob')
            ->with($blob);

        $this->assertNull($this->loader->load($imagick, $blob, 'image/png'));
    }
}
