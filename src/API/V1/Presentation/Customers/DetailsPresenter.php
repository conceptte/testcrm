<?php
namespace Mtr\MiniCRM\API\V1\Presentation\Customers;

use Mtr\MiniCRM\API\V1\Exception\ApiExceptionInterface;
use Mtr\MiniCRM\API\V1\Exception\NotFoundException;
use Mtr\MiniCRM\API\V1\Presentation\ApiPresenter;
use Mtr\MiniCRM\API\V1\Resource\Customers\CustomerResourceFactory;
use Mtr\MiniCRM\API\V1\Resource\ResourceInterface;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Throwable;

class DetailsPresenter extends ApiPresenter
{
    public function __construct(
        private CustomersRepositoryInterface $customerRepository,
        private CustomerResourceFactory $customerResourceFactory
    )
    {}

    /**
     * Get customer details
     * 
     * @param string $id Customer public ID
     * 
     * @return void
     */
    public function actionDefault(string $id): void
    {
        try {
            $apiData = [
                "success" => true,
                "data" => $this->getCusomerData($id)
            ];

        } catch (ApiExceptionInterface $e) {

            $apiData = $this->errorData('Customer not found');

        } catch (Throwable $e) {

            $apiData = $this->errorData($e->getMessage());
        }

        $this->sendJson($apiData);
    }

    /**
     * @param string $id
     * 
     * @return ResourceInterface
     * @throws NotFoundException
     */
    private function getCusomerData(string $id): ResourceInterface
    {
        if (!$customer = $this->customerRepository->byPublicId($id)) {
                throw new NotFoundException('Customer not found');
        }

        return $this->customerResourceFactory->createResource($customer);
    }

    
}