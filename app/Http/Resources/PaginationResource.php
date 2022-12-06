<?php

namespace App\Http\Resources;

class PaginationResource
{

    /**
     * @param DocsTrustResource $param
     */
    public function __construct(\App\Http\Resources\DocsTrustResource $param)
    {

    }


    public function toArray($request): array
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage()
        ];
    }
}
