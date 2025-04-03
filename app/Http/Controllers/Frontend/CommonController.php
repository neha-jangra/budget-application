<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\CommonRepository;

use App\Repositories\UserRepository;
use App\Models\{SubProjectData};

class CommonController extends Controller
{

    /** @var  CommonRepository */
    private $commonRepository;

    /** @var  UserRepository */
    private $userRepository;

    public function __construct(CommonRepository $commonRepository, UserRepository $userRepository)
    {
        $this->commonRepository = $commonRepository;

        $this->userRepository   = $userRepository;
    }

    public function Currentprojectuserdata(Request $request)
    {
        $recordId = $request->record;

        if ($recordId) {
            $getRecord = SubProjectData::find($recordId);
            if ($getRecord && is_null($getRecord->employee_id) && !is_null($getRecord->unit_costs)) {
                return false;
            }
        }

        $user = $this->userRepository->with('userprofile')->find($request->id);

        if (!$user) {
            return 0;
        }

        return $request->has('year')
            ? getDailyRateForYear($user->id, $request->year, $user)
            : calculateAverageDailyRate($user->id, optional($user->userProfile)->rate);
    }


    public function CurrentprojectestimateBudget(Request $request)
    {
        $_data = $request->all();

        $_estimate_budget = $this->commonRepository->updateEstimateBudget($_data['data']);

        return $this->Response($_estimate_budget['message'], $_estimate_budget['statusCode'], $_estimate_budget['data']);
    }
}
