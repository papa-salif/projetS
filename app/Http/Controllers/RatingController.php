<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use app\Models\Incident;
class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRatingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show(Rating $rating)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        //
    }

    public function showEvaluationForm(Incident $incident)
    {
        return view('ratings.evaluate', compact('incident'));
    }

    public function submitEvaluation(Request $request, Incident $incident)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
        ]);

        Rating::create([
            'incident_id' => $incident->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comments' => $request->comments,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Merci pour votre évaluation.');
    }

    public function show()
{
    $user = auth()->user();
    $canRate = $this->canUserRate($user);
    $usageCount = $this->getUserUsageCount($user);

    return view('app_rating.show', compact('canRate', 'usageCount'));
}

public function askForRating()
{
    $user = auth()->user();
    if ($this->canUserRate($user)) {
        return response()->json(['showRatingPrompt' => true]);
    }
    return response()->json(['showRatingPrompt' => false]);
}

// private function getUserUsageCount($user)
// {
//     return Facture::where('user_id', $user->id)->count() + 
//            Achat::where('user_id', $user->id)->count();
// }

private function canUserRate($user)
{
    if ($user->hasRole('agent') || $user->hasRole('admin')) {
        return false;
    }

    $usageCount = $this->getUserUsageCount($user);
    return $usageCount >= 5 && ! Rating::where('user_id', $user->id)->exists();
}
}
