<?php
/*
 * File name: TownshipAPIController.php
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Repositories\TokenRepository;
use App\Http\Requests\CreateTokenRequest;
/**
 * Class TownshipAPIController
 * @package App\Http\Controllers\API
 */
class TokenAPIController extends Controller
{
    /** @var  OptionRepository */
    private $tokenRepository;

    public function __construct(TokenRepository $tokenRepo)
    {
        parent::__construct();
        $this->tokenRepository = $tokenRepo;
    }

    public function create_token(CreateTokenRequest $request): JsonResponse
    {
        $input = $request->all();

        try {
           $token = $this->tokenRepository->create($input);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($token->toArray(), __('lang.saved_successfully', ['operator' => __('lang.option')]));
    }
}