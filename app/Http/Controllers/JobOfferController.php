<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobOfferController extends Controller
{
    /**
     * Wyświetla listę ofert pracy z możliwością filtrowania.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['job_title', 'description', 'url', 'work_mode', 'location', 'work_type']);
        $query = JobOffer::query();

        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                if (in_array($key, ['job_title', 'description', 'work_mode', 'location', 'work_type'])) {
                    $query->whereHas('versions', function ($q) use ($key, $value) {
                        $q->where($key, 'like', "%$value%");
                    });
                }
            }
        }

        $query->whereHas('versions', function ($q) {
            $q->where('is_active', true);
        });

        $jobOffers = $query->get();
        $jobOffersWithVersions = [];

        foreach ($jobOffers as $jobOffer) {
            $latestVersion = $jobOffer->versions()->where('is_active', true)->latest()->first();
            if ($latestVersion) {
                $jobOffersWithVersions[] = [
                    'jobOffer' => $jobOffer,
                    'version' => $latestVersion
                ];
            }
        }

        return view('jobOffers.index', [
            'jobOffers' => $jobOffersWithVersions,
            'filters' => $filters,
        ]);
    }

    /**
     * Wyświetla historię zmian oferty pracy.
     *
     * @param int $jobOfferId
     * @return View
     */
    public function history(int $jobOfferId): View
    {
        $jobOffer = JobOffer::findOrFail($jobOfferId);
        $versionList = $jobOffer->versions()->get();
        
        return view('jobOffers.history', [
            'versionList' => $versionList,
        ]);
    }
}
