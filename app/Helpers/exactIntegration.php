<?php

use App\Models\{ExactSubProjectSchema, ExactToken, ExactSubProject, ProjectYearLinkingWithExact, SubProject, SubProjectData, User, Project, ExactExpenses, OtherDirectExpense, SubProjectSyncedStatus};
use Carbon\Carbon;
use Illuminate\Support\Facades\{Log, DB};

function getAuthToken()
{
      $auth = ExactToken::first();
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://start.exactonline.nl/api/oauth2/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'refresh_token=' . $auth->refresh_token . '&grant_type=refresh_token' . '&client_id=' . config('env.client_id') . '&client_secret=' . config('env.client_secret'),
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/x-www-form-urlencoded',
        )
      ));

      $response = curl_exec($curl);
      if (curl_errno($curl)) {
        Log::error('cURL error: ' . curl_error($curl));
        curl_close($curl);
        return;
      }
      curl_close($curl);
      $response = json_decode($response);

      if (isset($response->access_token)) {
        $input['refresh_token'] = $response->refresh_token ?? $auth->refresh_token;
        $input['access_token'] = $response->access_token;
        $input['token_type'] = $response->token_type;
        ExactToken::where('id', $auth->id)->update($input);
        return $input['access_token'];
      } else {
        Log::error('Failed to get auth token', ['response' => $response]);
      }

}

function commonCurl($url, $method, $arr, $maxRetries = 5)
{
    $auth = ExactToken::first();
    if (Carbon::parse($auth->updated_at)->addMinutes(9)->isPast()) {
      Log::info("Refreshing Auth Token...");
      getAuthToken();
      $auth = ExactToken::first();
    }

    $retryCount = 0;
    $waitTime = 2; // Initial wait time in seconds

    do {
      $checkLine = curl_init();
      curl_setopt_array($checkLine, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS  => json_encode($arr),
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer " . $auth->access_token,
          'Content-Type: application/json',
          'Accept: application/json',
        ),
      ));

      $save = curl_exec($checkLine);
      $httpCode = curl_getinfo($checkLine, CURLINFO_HTTP_CODE);
      $curlError = curl_error($checkLine);
      curl_close($checkLine);

      if ($curlError) {
        Log::error("cURL Error: " . $curlError);
        return null;
      }

      if ($httpCode == 429) {
        $retryCount++;
        $waitTime *= 2; // Exponential backoff (2, 4, 8, 16, etc.)
        Log::warning("Received 429 Too Many Requests. Retrying in {$waitTime} seconds... (Attempt $retryCount of $maxRetries)");
        Log::info($url);
        sleep($waitTime);
      } else {
        return json_decode($save);
      }
    } while ($httpCode == 429 && $retryCount < $maxRetries);

    Log::error("Max retries reached. API still returning 429.");
    return null;
}

function getExactSubProjects($id)
{
    return ExactSubProject::where('exact_project_id', $id)->get();
}

function checkIfDataSyncedOrNot($projectId, $year)
{
    // Define required category IDs (1 to 6)
    $requiredCategories = range(1, 6);

    // Get all sub-projects under the given project
    $subProjectIds = SubProject::where('project_id', $projectId)->where('year', $year)->pluck('id')->toArray();
    if (empty($subProjectIds)) {
      return false;
    }

    // Get existing categories for all sub-projects of the project
    $existingCategories = SubProjectSyncedStatus::whereIn('sub_project_id', $subProjectIds)
      ->whereIn('look_up_id', $requiredCategories)
      ->pluck('look_up_id')
      ->unique()
      ->toArray();

    // Find missing categories
    $missingCategories = array_diff($requiredCategories, $existingCategories);

    return empty($missingCategories);
}


function getExactLinkedProjectPerYear($id, $year)
{
    return ProjectYearLinkingWithExact::where('project_id', $id)->where('year', $year)->first();
}

function getCategoryMappings()
{
    return [
      'staff'         => ['staff', 'staf', 'SALARIES AND FRINGE BENEFITS'],
      'consultants'   => ['consultants', 'consultancy', 'CONSULTANCIES and SUB-GRANTS', 'CONSULTANTS'],
      'sub-grantees'  => ['subgrantee', 'sub-grantees', 'Sub Grants', 'LOCAL SUB-GRANTS'],
      'travel'        => ['travel', 'TRAVEL (DIRECT TRAVEL COSTS)'],
      'meeting'       => ['meeting', 'meetings', 'MEETINGS'],
      'other_expense' => ['other direct expense', 'miscellaneous', 'Other Project Costs', 'Other Direct COsts', 'TOTAL OTHER DIRECT COSTS'],
    ];
}

function getProjectHierarchyId($category)
{
    $hierarchyMappings = [
      'staff'         => 1,
      'consultants'   => 2,
      'sub-grantees'  => 3,
      'travel'        => 4,
      'meeting'       => 5,
      'other_expense' => 6,
    ];
    return $hierarchyMappings[$category] ?? null;
}

function getAllWBSItems($exactProjectId)
{
    static $cachedWBS = []; // Store cached responses

    if (isset($cachedWBS[$exactProjectId])) {
      return $cachedWBS[$exactProjectId];
    }

    $apiUrl = config('env.exact_url') . "/read/project/ProjectWBSByProject?projectId=guid'{$exactProjectId}'";
    $method = 'GET';
    $projectWBS = commonCurl($apiUrl, $method, []);

    $cachedWBS[$exactProjectId] = $projectWBS->d->results ?? [];
    return $cachedWBS[$exactProjectId];
}
function saveExactLineItemsInBudgetApp($id, $year)
{
    $getExactLinkedId = getExactLinkedProjectPerYear($id, $year);
    if (!$getExactLinkedId) {
        return;
    }
    $hasSubProject = $getExactLinkedId->has_subproject;
    if ($hasSubProject) {
        $getSubProjects = SubProject::where('project_id', $id)->where('year', $year)->get();
        if ($getSubProjects->isNotEmpty()) {
            foreach ($getSubProjects as $subProject) {
                processCategories($getExactLinkedId->exact_project_id, $subProject, $year, true);
            }
        }
    } else {
        $project = Project::where('id', $id)->first();
        if ($project) {
            processCategories($getExactLinkedId->exact_project_id, $project, $year, false);
        }
    }
    return true;
}

function processCategories($exactProjectId, $subProject, $year, $isSubProject)
{
    $categoryMappings = getCategoryMappings();
    foreach ($categoryMappings as $category => $keywords) {
        $parentId = getParentIdByCategory($exactProjectId, $category, $subProject);
        if ($parentId) {
            $wbsItems = getWBSItemsByParent($exactProjectId, $parentId);
            if (!empty($wbsItems)) {
                processWBSItems($wbsItems, $subProject, $year, $isSubProject, $category);
            }
        }
    }
}

function getParentIdByCategory($exactProjectId, $category, $subProject)
{
  $allWBSItems = getAllWBSItems($exactProjectId);
  $categoryMappings = getCategoryMappings();
  $keywords = $categoryMappings[$category] ?? [];

  $isSynced = false;
  $filteredWBS = [];

  foreach ($allWBSItems as $wbs) {
    if ($wbs->Parent != $subProject->exact_id) {
        continue;
    }

    foreach ($keywords as $keyword) {
        if (!empty($wbs->Description) && stripos($wbs->Description, $keyword) !== false) {
            $filteredWBS[] = $wbs;
            $isSynced = true;
            break; // No need to check more keywords once matched
        }
    }
  }
  return $isSynced ? reset($filteredWBS) : null;
}

function getWBSItemsByParent($exactProjectId, $parentId)
{
    $allWBSItems = getAllWBSItems($exactProjectId);
    return array_filter($allWBSItems, function ($wbs) use ($parentId) {
        return isset($wbs->Parent) && $wbs->Parent == $parentId->ID;
    });
}

function getTimeTransactionsForWBSItems($wbsItems)
{
    static $cachedTimeTransactions = [];

    if (empty($wbsItems)) {
      return [];
    }

    $activityIds = array_map(fn($wbs) => "guid'{$wbs->ID}'", $wbsItems);
    $filter = '$filter=' . implode(' or ', array_map(fn($id) => "Activity eq {$id}", $activityIds));

    $timeTransactionsUrl = config('env.exact_url') . "/project/TimeTransactions?" . http_build_query([$filter]);
    $method = 'GET';

    if (isset($cachedTimeTransactions[$timeTransactionsUrl])) {
      return $cachedTimeTransactions[$timeTransactionsUrl];
    }

    $timeTransactions = commonCurl($timeTransactionsUrl, $method, []);
    $cachedTimeTransactions[$timeTransactionsUrl] = $timeTransactions->d->results ?? [];
    return $cachedTimeTransactions[$timeTransactionsUrl];
}

function processWBSItems($wbsItems, $subProject, $year, $isSubProject, $category)
{
    static $hasStaffEntries = false;
    $method = 'GET';

    // Fetch all time transactions in one request
    $timeTransactions = getTimeTransactionsForWBSItems($wbsItems);

    foreach ($wbsItems as $wbsItem) {
      $activityId = $wbsItem->ID;

      // Find related transaction
      $employee = null;
      foreach ($timeTransactions as $transaction) {
        if ($transaction->Activity == $activityId && $transaction->HourStatus == 20) {
          $employee = $transaction->Employee ?? null;
          break;
        }
      }

      if ($category !== 'staff' && !$hasStaffEntries) {
        return;
      }

      // Fetch exact record
      $exactUrl = config('env.exact_url') . "/project/" . ($category === 'staff' ? "WBSActivities" : "WBSExpenses") . "?" . http_build_query([
        '$filter' => "ID eq guid'{$wbsItem->ID}'"
      ]);

      $exactRecord = commonCurl($exactUrl, $method, []);

      if (!empty($exactRecord->d->results)) {
        if ($category === 'staff') {
          $hasStaffEntries = true;
        }
        saveCategoryData($employee, $subProject, $wbsItem, $year, $isSubProject, $category);
      }
    }
}


function saveCategoryData($employee, $subProject, $wbsItem, $year, $isSubProject, $category)
{
    $user = $employee ? User::where('exact_id', $employee)->first() : null;
    $exactWbsDescription = $wbsItem->Description;
    $totalApprovalBudget = $wbsItem->Cost ?? 0;
    $workHoursPerDay = config('env.work_hours_per_day');
    $units = ($workHoursPerDay > 0) ? ($wbsItem->Hours / $workHoursPerDay) : 0;
    $unitCosts = ($units > 0) ? ($wbsItem->Cost / $units) : 0;
    $actualExpensesToDate = $wbsItem->Revenue ?? 0;
    $remainingBalance = $totalApprovalBudget - $actualExpensesToDate;
    $projectHierarchyId = getProjectHierarchyId($category);

    SubProjectData::updateOrCreate(
      [
        'year'                 => $year,
        'exact_wbs_id'         => $wbsItem->ID
      ],
      [
        'sub_project_id'        => (!$isSubProject) ? null : $subProject->id,
        'note'                  => 'per_day',
        'units'                 => $units,
        'unit_costs'            => $unitCosts,
        'actual_expenses_to_date' => $actualExpensesToDate,
        'remaining_balance'     => $remainingBalance,
        'project_hierarchy_id'  => $projectHierarchyId,
        'project_id'            => (!$isSubProject) ? $subProject->id : $subProject->project_id,
        'total_approval_budget' => $totalApprovalBudget,
        'percentage'            => 0,
        'exact_wbs_description' => $exactWbsDescription,
        'exact_wbs_id'          => $wbsItem->ID,
        'employee_id'           => $user->id ?? null,
    ]
    );

    SubProjectSyncedStatus::updateOrCreate(
      [
        'look_up_id' => $projectHierarchyId,
        'project_id' => $subProject->project_id,
        'sub_project_id' => $subProject->id,
      ],
      [
        'is_synced' => 1,
      ]
    );
}

function getLookupSyncStatus($subProject, $lookupId){
    return SubProjectSyncedStatus::where([
        'look_up_id' => $lookupId,
        'sub_project_id' => $subProject,
    ])->first();
}

function createExactSubProjectSchema($projectId, $subProjectId)
{
    $descriptions = [
      "SALARIES AND FRINGE BENEFITS",
      "CONSULTANTS",
      "LOCAL SUB-GRANTS",
      "TRAVEL (DIRECT TRAVEL COSTS)",
      "MEETINGS",
      "TOTAL OTHER DIRECT COSTS",
      "INDIRECT COSTS"
    ];
    
    $url = config('env.exact_url') . "/project/WBSDeliverables";
    foreach ($descriptions as $key => $description) {
        $payload = [
          "Description" => $description,
          "Project" => $projectId,
          "PartOf" => $subProjectId
        ];
        $response = commonCurl($url, "POST", $payload);
        // Extract exact_id from response
        $exactId = $response->d->ID ?? null;

        // Set look_up_id: If it's "INDIRECT COSTS", store 8 instead of 7
        $lookUpId = ($description === "INDIRECT COSTS") ? 8 : ($key + 1);

        // Store in the database
        ExactSubProjectSchema::create([
          'project_id' => $projectId,
          'sub_project_id' => $subProjectId,
          'description' => $description,
          'exact_id' => $exactId,
          'look_up_id' => $lookUpId
        ]);
    }
}

function createAutoSyncedValues($subProjectId, $projectId){
    for ($i=1; $i <=6 ; $i++) {
        SubProjectSyncedStatus::updateOrCreate(
          [
            'look_up_id' => $i,
            'project_id' => $projectId,
            'sub_project_id' => $subProjectId,
          ],
          [
            'is_synced' => 1,
          ]
        );
    }
}

function saveActivitiesExpensesInExact($recordDetail)
{
    $workHoursPerDay = config('env.work_hours_per_day');
    $description = ($recordDetail->project_hierarchy_id == 6)
    ? OtherDirectExpense::where('id', $recordDetail->employee_id)->value('name')
    : ($recordDetail->user->name ?? $recordDetail->employee_id);

  // Determine API type and budget key
  $isActivity = $recordDetail->project_hierarchy_id == 1;
    $apiPath = $isActivity ? "WBSActivities" : "WBSExpenses";
    $budgetKey = $isActivity ? "BudgetedHours" : "Quantity";
    $budgetValue = $isActivity ? $recordDetail->units * $workHoursPerDay : $recordDetail->units;

    // Get 'PartOf' ID from ExactSubProjectSchema
    $getPartOf = ExactSubProjectSchema::where([
        'project_id' => $recordDetail->project->exact_id,
        'sub_project_id' => $recordDetail->subProject->exact_id,
        'look_up_id' => $recordDetail->project_hierarchy_id
    ])->value('exact_id');
    
    $payload = [
        "Description" => $description,
        "Project" => $recordDetail->project->exact_id,
        "PartOf" => $getPartOf,
        "BudgetedRevenue" => $recordDetail->actual_expenses_to_date,
        "BudgetedCost" => $recordDetail->total_approval_budget,
        $budgetKey => $budgetValue
    ];

    // Set URL and method based on exact_wbs_id existence
    $method = $recordDetail->exact_wbs_id ? "PUT" : "POST";
    $url = config('env.exact_url') . "/project/{$apiPath}" . ($method === "PUT" ? "(guid'{$recordDetail->exact_wbs_id}')" : "");

    $response = commonCurl($url, $method, $payload);

    // Store exact_id only if created via POST
    if ($method === "POST" && isset($response->d->ID)) {
        SubProjectData::where('id', $recordDetail->id)->update(['exact_wbs_id' => $response->d->ID]);
    }
}

function saveIndirectCostInExact($recordDetail)
{
    $subProject = SubProject::where('id', $recordDetail['sub_project_id'])->first();
    $getPartOf = ExactSubProjectSchema::where([
      'project_id' => $subProject->project->exact_id,
      'sub_project_id' => $subProject->exact_id,
      'look_up_id' => 8
    ])->value('exact_id');

    $payload = [
        "Description" => 'Indirect Cost',
        "Project" => $subProject->project->exact_id,
        "PartOf" => $getPartOf,
        "BudgetedRevenue" => $recordDetail['actual_expenses'],
        "BudgetedCost" => $recordDetail['indirect_cost_approval'],
    ];

    // Set URL and method based on exact_wbs_id existence
    $method = $subProject->exact_indirect_cost_id ? "PUT" : "POST";
    $url = config('env.exact_url') . "/project/WBSExpenses" . ($method === "PUT" ? "(guid'{$subProject->exact_indirect_cost_id}')" : "");
    $response = commonCurl($url, $method, $payload);

    // Store exact_id only if created via POST
    if ($method === "POST" && isset($response->d->ID)) {
        SubProject::where('id', $recordDetail['sub_project_id'])->update(['exact_indirect_cost_id' => $response->d->ID]);
    }
}

function deleteExactActivityOrExpense($isActivity, $id){
  $apiPath = $isActivity ? "WBSActivities" : "WBSExpenses";
  $url = config('env.exact_url') . "/project/{$apiPath}" . "(guid'{$id}')";
  commonCurl($url, "DELETE", []);
}
