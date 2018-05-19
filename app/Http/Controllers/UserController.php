<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class UserController extends BaseController
{

    public static $model = User::class;

    public function post(Request $request)
    {
        // Validate role if set, and replace it's uuid with id
        if ($request->request->has('primary_role')) {
            $primaryRole = $request->request->get('primary_role');

            if (in_array($primaryRole, Role::FRONTEND_ALLOWED_TO_CREATE)) {
                $role = Role::where('name', '=', $primaryRole)->first();
                $request->request->set('primary_role', $role->getKey());
            } else {
                throw new UnauthorizedHttpException('Invalid Role');
            }
        }

        return $this->response->item(parent::post($request), $this->getTransformer())->setStatusCode(201);
    }

}
