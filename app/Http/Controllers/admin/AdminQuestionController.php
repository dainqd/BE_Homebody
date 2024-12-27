<?php

namespace App\Http\Controllers\admin;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Http\Controllers\Controller;
use App\Models\Answers;
use App\Models\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminQuestionController extends Controller
{
    public function list(Request $request)
    {
        try {
            $size = $request->input('size') ?? 10;
            $size = intval($size);
            $questions = Questions::where('status', '!=', QuestionStatus::DELETED)
                ->orderByDesc('id')
                ->paginate($size);
            return view('admin.questions.list', compact('questions'));
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return back();
        }
    }

    public function detail(Request $request, $id)
    {
        try {
            $question = Questions::find($id);

            if (!$question || $question->status == QuestionStatus::DELETED) {
                return redirect(route('error.not.found'));
            }

            $size = $request->input('size') ?? 10;
            $size = intval($size);

            $answers = Answers::where('question_id', $id)
                ->where('status', '!=', AnswerStatus::DELETED)
                ->orderBy('id', 'ASC')
                ->paginate($size);

            return view('admin.questions.detail', compact('question', 'answers'));
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return back();
        }
    }


    public function create(Request $request)
    {
        try {
            return view('admin.questions.create');
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return back();
        }
    }

    public function store(Request $request)
    {
        try {
            $question = new Questions();

            $title = $request->input('title');
            $content = $request->input('content');
            $status = $request->input('status');

            $question->title = $title;
            $question->content = $content;
            $question->status = $status;

            $question->user_id = Auth::user()->id;

            $question->save();

            alert()->success('Create successfully!');
            return redirect(route('admin.qna.questions.list'));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $question = Questions::find($id);

            if (!$question || $question->status == QuestionStatus::DELETED) {
                return redirect(route('error.not.found'));
            }

            $title = $request->input('title');
            $content = $request->input('content');
            $status = $request->input('status');

            $question->title = $title;
            $question->content = $content;
            $question->status = $status;

            $question->save();

            alert()->success('Update successfully!');
            return redirect(route('admin.qna.questions.list'));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $question = Questions::find($id);

            if (!$question || $question->status == QuestionStatus::DELETED) {
                return redirect(route('error.not.found'));
            }

            $question->status = QuestionStatus::DELETED;
            $question->save();

            alert()->success('Delete successfully!');
            return redirect(route('admin.qna.questions.list'));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }
}
