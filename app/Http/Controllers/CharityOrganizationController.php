<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCharityOrganizationRequest;
use App\Models\CharityOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use \Illuminate\Validation\ValidationException;
class CharityOrganizationController extends Controller
{
  
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCharityOrganizationRequest $request)
    {
        DB::beginTransaction();
        try {
            $charityOrganization = new CharityOrganization;
            $charityOrganization->fill($request->all());
            $charityOrganization->isAddedByUser = 1;
            $charityOrganization->save();
            DB::commit();
            return response([
              'success' => true,
              'data'=>['id'=>$charityOrganization->id,'name'=>$charityOrganization->name]
          ], 200);
        } catch (ValidationException $e) {
          return response([
                'success' => false,
                'message' => $this->transformValidationErrors($exception->errors()),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
          dd($e);
            DB::rollback();
            return response([
              'success' => false,
              'message' => 'Error occurred.',
          ], 500);
        }
    }

    /**
     * Parse validation errors.
     *
     * @param array $errors
     * @return JsonResponse
     */
    public function transformValidationErrors(array $errors)
    {
        $transformedErrors = [];

        foreach ($errors as $field => $message) {
            $transformedErrors[] = [
                'field' => $field,
                'message' => $message[0],
            ];
        }
        return count($transformedErrors) > 0 ? $transformedErrors[0]['message'] : 'Error occurred.';
    }
}
