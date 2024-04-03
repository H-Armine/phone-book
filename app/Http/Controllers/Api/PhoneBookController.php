<?php

namespace App\Http\Controllers\Api;

use App\Models\PhoneBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PhoneBookRequest;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PhoneBookController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $perPage = $request->query('perPage', 10);
        $page = $request->query('page', 1);

        $phoneBooks = PhoneBook::paginate($perPage, ['*'], 'page', $page);

        $data = [
            "success" => true,
            "data" => $phoneBooks->items(),
            "total" => $phoneBooks->total(),
            "perPage" => $phoneBooks->perPage(),
            "currentPage" => $phoneBooks->currentPage(),
            "lastPage" => $phoneBooks->lastPage()
        ];

        return response()->json($data, ResponseAlias::HTTP_OK);
    }

    /**
     * @param PhoneBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PhoneBookRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $phoneBook = PhoneBook::create($request->validated());

            return response()->json([
                "message" => "Phone book created successfully!",
                "success" => true,
                "data" => $phoneBook
            ], ResponseAlias::HTTP_CREATED);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                "success" => false,
                "message" => 'An unexpected error occurred.'
            ])->setStatusCode(ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function retrieve(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $phoneBook = PhoneBook::find($id);

            if (!$phoneBook) {
                return response()->json([
                    "success" => false,
                    "message" => "Phone book entry not found."
                ], ResponseAlias::HTTP_NOT_FOUND);
            }

            return response()->json([
                "success" => true,
                "data" => $phoneBook
            ], ResponseAlias::HTTP_OK);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                "success" => false,
                "message" => 'An unexpected error occurred.'
            ])->setStatusCode(ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param PhoneBookRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PhoneBookRequest $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $phoneBook = PhoneBook::find($id);

            if (!$phoneBook) {
                return response()->json([
                    "success" => false,
                    "message" => "Phone book entry not found."
                ], ResponseAlias::HTTP_NOT_FOUND);
            }

            $phoneBook->update($request->validated());

            return response()->json([
                "message" => "Phone book entry updated successfully!",
                "success" => true,
                "data" => $phoneBook
            ], ResponseAlias::HTTP_OK);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                "success" => false,
                "message" => 'An unexpected error occurred.'
            ])->setStatusCode(ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $phoneBook = PhoneBook::find($id);

            if (!$phoneBook) {
                return response()->json([
                    "success" => false,
                    "message" => "Phone book entry not found."
                ], ResponseAlias::HTTP_NOT_FOUND);
            }

            $phoneBook->delete();

            return response()->json([
                "message" => "Phone book entry deleted successfully!",
                "success" => true
            ], ResponseAlias::HTTP_OK);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                "success" => false,
                "message" => 'An unexpected error occurred.'
            ])->setStatusCode(ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
