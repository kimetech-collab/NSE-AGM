<?php

namespace App\Services;

use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TicketPdfService
{
    public function __construct(
        protected QRService $qr
    ) {
    }

    public function render(Registration $registration): string
    {
        $token = $this->qr->generateToken($registration);
        $qrImage = $this->renderQrImage($token);

        return Pdf::loadView('pdf.ticket', [
            'registration' => $registration,
            'qrImage' => $qrImage,
        ])->output();
    }

    protected function renderQrImage(string $payload): ?string
    {
        $brandGreen = new Rgb(43, 107, 43); // NSE Green 700
        $white = new Rgb(255, 255, 255);
        $fill = Fill::uniformColor($white, $brandGreen);

        try {
            $renderer = new GDLibRenderer(320, 4, 'png', 9, $fill);
            $writer = new Writer($renderer);
            $png = $writer->writeString($payload, Encoder::DEFAULT_BYTE_MODE_ENCODING, ErrorCorrectionLevel::M());

            return 'data:image/png;base64,' . base64_encode($png);
        } catch (\Throwable $e) {
            try {
                $renderer = new ImageRenderer(
                    new RendererStyle(320, 4, null, null, $fill),
                    new SvgImageBackEnd()
                );
                $writer = new Writer($renderer);
                $svg = $writer->writeString($payload, Encoder::DEFAULT_BYTE_MODE_ENCODING, ErrorCorrectionLevel::M());

                return 'data:image/svg+xml;base64,' . base64_encode($svg);
            } catch (\Throwable $e) {
                return null;
            }
        }
    }
}
