<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

class CompanyService implements CompanyServiceInterface
{
    public function getCompanyTree()
    {
        // To do
        return [Response::HTTP_OK, []];
    }
}
