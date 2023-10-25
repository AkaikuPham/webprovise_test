<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

class CompanyService implements CompanyServiceInterface
{
    /**
     * get Company Tree
     *
     * @return array
     */
    public function getCompanyTree()
    {
        // Get data of companies
        $companies = $this->getDataByUrl(config('const.api_company_url'));

        // Get data of travels
        $travels = $this->getDataByUrl(config('const.api_travel_url'));

        // Build company Tree
        $companyTrees = $this->buildTree($companies);

        // calculate Cost of Company
        foreach ($companyTrees as &$parent) {
            $this->calculateCostRecursively($parent, $travels);
        }

        return [Response::HTTP_OK, $companyTrees];
    }

    /**
     * get Data By Url
     *
     * @param string $url
     * @return array
     */
    private function getDataByUrl($url)
    {
        $client = new \GuzzleHttp\Client();
        $data = $client->get($url);

        return json_decode($data->getBody()->getContents(), true);
    }

    /**
     * build Tree
     *
     * @param array $data
     * @param string $parentId
     * @return array
     */
    function buildTree($data, $parentId = '0') {
        $tree = array();

        foreach ($data as $node) {
            $node['cost'] = 0;
            $node['children'] = [];
            if ($node['parentId'] == $parentId) {
                $children = $this->buildTree($data, $node['id']);
                if ($children) {
                    $node['children'] = $children;
                }
                $tree[] = $node;
            }
        }

        return $tree;
    }

    /**
     * get Cost By CompanyId
     *
     * @param uuid $companyId
     * @param array $parentId
     * @return double
     */
    private function getCostByCompanyId($companyId, $travels)
    {
        $data = \collect($travels)->groupBy('companyId');
        return array_sum(array_column($data[$companyId]->toArray(), 'price'));
    }

    /**
     * calculate Cost Recursively
     *
     * @param array &$node
     * @param array $travels
     * @return array
     */
    function calculateCostRecursively(&$node, $travels) {
        $travelCost = $this->getCostByCompanyId($node['id'], $travels);
        $childCost = 0;
    
        if (isset($node['children'])) {
            foreach ($node['children'] as &$child) {
                $childCost += $this->calculateCostRecursively($child, $travels);
            }
        }

        $node['cost'] = $travelCost + $childCost;
        return $node['cost'];
    }
}
