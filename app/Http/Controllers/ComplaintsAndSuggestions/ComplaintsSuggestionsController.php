<?php

namespace App\Http\Controllers\ComplaintsAndSuggestions;

use App\Http\Controllers\Controller;
use App\Services\ComplaintsAndSuggestions\ComplaintsSuggestionsService;
use Illuminate\Http\Response;

class ComplaintsSuggestionsController extends Controller
{
    /**
     *
     * @var ComplaintsSuggestionsService
     */
    protected ComplaintsSuggestionsService $complaintsSuggestionsService;

    // singleton pattern, service container
    public function __construct(ComplaintsSuggestionsService $complaintsSuggestionsService)
    {
        $this->complaintsSuggestionsService = $complaintsSuggestionsService;
    }

    public function getComplaints(): Response
    {
        return $this->complaintsSuggestionsService->getComplaints();
    }

    public function getSuggestions(): Response
    {
        return $this->complaintsSuggestionsService->getSuggestions();
    }
}
