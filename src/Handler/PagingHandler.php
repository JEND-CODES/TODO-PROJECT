<?php

namespace App\Handler;

use Symfony\Component\HttpFoundation\Request;

class PagingHandler
{

    public function handle(Request $request)
    {
        $startParam = $request->query->get('start');

        $limitParam = $request->query->get('limit');

        if($startParam > 100 || $limitParam > 100)
        {
            throw new \Exception('Too many items requested. 100 max.');
        }

        return array($startParam, $limitParam);

    }


}
