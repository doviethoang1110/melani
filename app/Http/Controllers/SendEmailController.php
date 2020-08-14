<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Mail;

class SendEmailController extends Controller
{
    public function contact(){
        return view('user/contact');
    }
    public function send_email(Request $request){
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ];
        $messages = [
            'name.required' => 'Tên không được rỗng',
            'email.required' => 'Email không được rỗng',
            'email.email' => 'Email không đúng định dạng',
            'subject.required' => 'Tiêu đề không được rỗng',
            'message.required' => 'Tin nhắn không được rỗng' 
        ];
        $data = [
            'name' =>$request->name,
            'email' =>$request->email,
            'subject' =>$request->subject,
            'message' =>$request->message
        ];
        $errors = Validator::make($request->all(),$rules,$messages);
        if($errors->fails()){
            return response()->json(['errors'=>$errors->errors()->all()]);
        }else{
            Mail::send('user.mail-contact', [
                'message' =>$request->message,
                'name' =>$request->name
            ], function ($mail) use($request){
                $mail->to('melanishopdemo@gmail.com',$request->name);
                $mail->from($request->email);
                $mail->subject($request->subject);
            });
            return response()->json(['success'=>'Gửi thành công']);
        }
    }
}