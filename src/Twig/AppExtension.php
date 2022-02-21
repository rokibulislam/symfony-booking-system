<?php

namespace App\Twig;

use App\Service\UploaderHelper;
use Twig\Extension\AbstractExtension;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper) {
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getFunctions() : array
    {
        return [
            new TwigFunction('uploaded_asset', [$this, 'getUploadedAssetPath']),
        ];
    }

    public function getUploadedAssetPath($path)
    {
        return $this->uploaderHelper->getPublicPath($path);
    }
}