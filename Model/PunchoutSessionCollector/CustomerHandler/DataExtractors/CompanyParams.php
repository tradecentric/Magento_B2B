<?php
declare(strict_types=1);

namespace TradeCentric\PunchoutB2b\Model\PunchoutSessionCollector\CustomerHandler\DataExtractors;

use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Punchout2Go\Punchout\Model\DataExtractorInterface;

/**
 * Class CompanyParams
 * @package Tradecentric\PunchoutB2b\Model\PunchoutSessionCollector\CustomerHandler\DataExtractors
 */
class CompanyParams implements DataExtractorInterface
{
    /**
     * @var CompanyRepositoryInterface
     */
    protected $companyRepository;

    /**
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param array $params
     * @return array
     */
    public function extract(array $params): array
    {
        $result = [];
        if (!isset($params['custom']['default_company'])) {
            return $result;
        }
        $companyId = $params['custom']['default_company'];
        try {
            $company = $this->companyRepository->get((int) $companyId);
        } catch (NoSuchEntityException $e) {
            return $result;
        }
        $result['extension_attributes'] = [
            'company_attributes' => [
                'company_id' => $company->getId()
            ]
        ];
        return $result;
    }
}
