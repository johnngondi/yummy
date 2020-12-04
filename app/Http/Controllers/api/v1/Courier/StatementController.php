<?php

namespace App\Http\Controllers\api\v1\Courier;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\UserAccount;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    public function index()
    {
        $statement = UserAccount::where('user_id', auth()->user()->id)->latest()->paginate(10);
        return new DataResource([
            'statements' => $statement->items(),
            'next' => $statement->nextPageUrl()
        ]);
    }
}
