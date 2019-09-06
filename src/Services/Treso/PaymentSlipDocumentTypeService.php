<?php

namespace Keros\Services\Treso;

use Keros\DataServices\Treso\PaymentSlipDocumentTypeDataService;
use Keros\Entities\Treso\PaymentSlipDocumentType;
use Keros\Error\KerosException;
use Psr\Container\ContainerInterface;

class PaymentSlipDocumentTypeService
{

    /** @var PaymentSlipDocumentTypeDataService */
    private $paymentSlipDocumentTypeDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->paymentSlipDocumentTypeDataService = $container->get(PaymentSlipDocumentTypeDataService::class);
    }

    /**
     * Return the only one PaymentSlipDocumentType.
     * Can change in future.
     * @return PaymentSlipDocumentType
     * @throws KerosException
     */
    public function get(): PaymentSlipDocumentType
    {
        $documentType = $this->paymentSlipDocumentTypeDataService->getOne(1);
        if (!$documentType) {
            throw new KerosException("The payment slip document type could not be found", 404);
        }
        return $documentType;
    }
}