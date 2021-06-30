<?php
// global $path;
// require_once($path. "tools/vendor/autoload.php");
namespace Utilities\QRCode;

class Generator {
    private $options;
    private $path, $file, $data;

    public function __construct(string $data, string $path= null, string $file = null)
    {
        $this->path = $path ? $path : FILESTORE_PATH . "qrcodes/";
        $this->file = $file ? $file : uniqid() . '.png';
        $this->data = $data;
        $this->options = new \chillerlan\QRCode\QROptions([
            'version'      => 10,
            'outputType'   => \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'     => \chillerlan\QRCode\QRCode::ECC_H,
            'scale'        => 5,
            'imageBase64'  => false
            // 'moduleValues' => [
            //     // finder
            //     1536 => [0, 63, 255], // dark (true)
            //     6    => [255, 255, 255], // light (false), white is the transparency color and is enabled by default
            //     5632 => [241, 28, 163], // finder dot, dark (true)
            //     // alignment
            //     2560 => [255, 0, 255],
            //     10   => [255, 255, 255],
            //     // timing
            //     3072 => [255, 0, 0],
            //     12   => [255, 255, 255],
            //     // format
            //     3584 => [67, 99, 84],
            //     14   => [255, 255, 255],
            //     // version
            //     4096 => [62, 174, 190],
            //     16   => [255, 255, 255],
            //     // data
            //     1024 => [0, 0, 0],
            //     4    => [255, 255, 255],
            //     // darkmodule
            //     512  => [0, 0, 0],
            //     // separator
            //     8    => [255, 255, 255],
            //     // quietzone
            //     18   => [255, 255, 255],
            //     // logo (requires a call to QRMatrix::setLogoSpace())
            //     20    => [255, 255, 255]
            // ]
        ]);
            return $this;
    }

    public function render() {
        $pathToFile = $this->path . $this->file;
        (new \chillerlan\QRCode\QRCode($this->options))->render($this->data, $pathToFile);
        return $pathToFile;
    }

}
