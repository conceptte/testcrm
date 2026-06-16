<?php
namespace Mtr\MiniCRM\API\V1\Presentation\Customers;

use Mtr\MiniCRM\API\V1\Exception\ApiExceptionInterface;
use Mtr\MiniCRM\API\V1\Exception\NotFoundException;
use Mtr\MiniCRM\API\V1\Presentation\ApiPresenter;
use Mtr\MiniCRM\API\V1\Resource\CustomerResource;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Throwable;

class DetailsPresenter extends ApiPresenter
{
    public function __construct(
        private CustomersRepositoryInterface $customerRepository
    )
    {}

    /**
     * Get customer details
     * 
     * @param string $id Customer public ID
     * 
     * @return void
     */
    public function actionIndex(string $id): void
    {
        try {
            if (!$customer = $this->customerRepository->byPublicId($id)) {
                throw new NotFoundException('Customer not found');
            }

            $apiData = [
                'success' => true,
                'data' => CustomerResource::fromRow($customer),
            ];

        } catch (ApiExceptionInterface $e) {

            $apiData = $this->errorData('Customer not found');

        } catch (Throwable $e) {

            $apiData = $this->errorData();
        }

        $this->sendJson($apiData);
    }

    
}