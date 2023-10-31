<?php

namespace App\Services\ComplaintsAndSuggestions;

use App\Models\Complaint;
use App\Models\Suggestion;
use App\Services\BaseService;
use Illuminate\Http\Response;

class ComplaintsSuggestionsService extends BaseService
{
    public function getSuggestions(): Response
    {
        $suggestions = Suggestion::paginate(\request('size'));

        return $this->customResponse(true, 'تم الحصول على المقترحات', $suggestions);
    }

    public function getComplaints(): Response
    {
        $complaints = Complaint::paginate(\request('size'));

        return $this->customResponse(true, 'تم الحصول على الشكاوي', $complaints);
    }

}
