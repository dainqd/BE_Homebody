<?php

namespace App\Http\Controllers\admin;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Http\Controllers\Controller;
use App\Models\Answers;
use App\Models\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAnswerController extends Controller
{
    public function detail(Request $request, $id)
    {
        try {
            $answer = Answers::where('id', $id)
                ->where('status', '!=', AnswerStatus::DELETED)
                ->first();

            if (!$answer) {
                return redirect(route('error.not.found'));
            }

            $question = Questions::where('id', $answer->question_id)
                ->where('status', '!=', QuestionStatus::DELETED)
                ->first();

            return view('admin.answers.detail', compact('answer', 'question'));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }


    public function create(Request $request)
    {
        try {
            $question_id = $request->input('question');

            $question = Questions::where('id', $question_id)
                ->where('status', '!=', QuestionStatus::DELETED)
                ->first();

            if (!$question) {
                return redirect(route('error.not.found'));
            }

            return view('admin.answers.create', compact('question'));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function store(Request $request)
    {
        try {
            $answer = new Answers();

            $title = $request->input('title');
            $content = $request->input('content');
            $question_id = $request->input('question_id');
            $status = $request->input('status');

            $question = Questions::find($question_id);

            if (!$question || $question->status == QuestionStatus::DELETED) {
                return redirect(route('error.not.found'));
            }

            $answer->title = $title;
            $answer->status = $status;
            $answer->content = $content;
            $answer->question_id = $question_id;
            $answer->user_id = Auth::user()->id;

            $answer->save();

            alert()->success('Create successfully!');
            return redirect(route('admin.qna.questions.detail', $question_id));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $answer = Answers::where('id', $id)
                ->where('status', '!=', AnswerStatus::DELETED)
                ->first();

            if (!$answer) {
                return redirect(route('error.not.found'));
            }

            $title = $request->input('title');
            $content = $request->input('content');
            $status = $request->input('status');

            $answer->title = $title;
            $answer->status = $status;
            $answer->content = $content;

            $answer->save();

            alert()->success('Update successfully!');
            return redirect(route('admin.qna.questions.detail', $answer->question_id));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            alert()->error('Error, Please try again!');
            return back();
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $answer = Answers::where('id', $id)
                ->where('status', '!=', AnswerStatus::DELETED)
                ->first();

            if (!$answer) {
                $data = returnMessage(-1, 400, '', 'Answer not found!');
                return response($data, 400);
            }

            $answer->status = AnswerStatus::DELETED();
            $answer->save();

            $data = returnMessage(1, 200, '', 'Success');
            return response($data, 200);
        } catch (\Exception $exception) {
            $data = returnMessage(-1, 400, '', $exception->getMessage());
            return response($data, 400);
        }
    }
}
