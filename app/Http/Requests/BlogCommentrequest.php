<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogCommentrequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'blog_id'    => ['required', 'exists:blogs,id'],
            'comment_id' => ['required', 'sometimes', 'exists:comments,id'],
            'username'   => 'required',
            'comment'    => 'required',
        ];
    }
}
