<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\CompanyServiceInterface;

class CompanyController extends BaseController
{
    protected $companyService;

    /**
     * Create a new instance
     *
     * @param CompanyServiceInterface $companyService
     */
    public function __construct(CompanyServiceInterface $companyService)
    {
        $this->companyService = $companyService;
    }


    /**
     * get Company Tree
     *
     * @return json
     */
    public function getCompanyTree()
    {
        list($statusCode, $data) = $this->companyService->getCompanyTree();

        return $this->response($data, $statusCode);
    }
}
