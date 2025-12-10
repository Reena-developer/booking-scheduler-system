<?php

namespace App\Traits;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

trait ApiResponseTrait
{
    /**
     * Success response
     */
    public function success($data = [], string $message = 'Success', int $status = 200)
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * Error response
     */
    public function error(string $message = 'Error', int $status = 400, array $errors = [])
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    /**
     * Paginated response
     */
    public function paginated(AbstractPaginator $paginator, string $message = 'Data fetched successfully', $resource = null) {
        $items = $resource ? $resource::collection($paginator->items()) : $paginator->items();

        return $this->success([
            'items' => $items,
            'pagination'  => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
        ], $message);
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error(
            'Validation failed',
            422,
            $validator->errors()->toArray()
        );

        throw new ValidationException($validator, $response);
    }

}
