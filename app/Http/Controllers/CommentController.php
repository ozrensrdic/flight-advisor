<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'city_id' => 'required',
            'user_id' => 'required',
            'comment' => 'required',
        ]);

        Comment::create($request->all());

        return redirect()->route('cities.show', $request['city_id'])
            ->with('success', 'Comment added successfully.');
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return RedirectResponse
     */
    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $request->validate([
            'city_id' => 'required',
            'user_id' => 'required',
            'comment' => 'required',
        ]);

        if (auth()->user()->id !== $comment->user->id) {
            return redirect()->route('cities.show', $request['city_id'])
                ->with('error', 'Not authorized to delete this comment');
        }


        $comment->update($request->all());

        return redirect()->route('cities.show', $request['city_id'])
            ->with('success', 'Comment updated successfully');
    }

    /**
     * @param Comment $comment
     * @return RedirectResponse
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $cityId = $comment->city_id;

        if (auth()->user()->id !== $comment->user->id) {
            return redirect()->route('cities.show', $cityId)
                ->with('error', 'Not authorized to delete this comment');
        }

        try {
            $comment->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('cities.show', $cityId)
                ->with('error', 'Comment is not deleted');
        }

        return redirect()->route('cities.show', $cityId)
            ->with('success', 'Comment deleted successfully');
    }

}
