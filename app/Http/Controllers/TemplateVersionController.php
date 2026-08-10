<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemplateVersion;

class TemplateVersionController extends Controller
{
    public function edit($id){
        $type = 'templateVersion';
        $template = TemplateVersion::findOrFail($id);
        return view('email-templates.edit', compact('template', 'type'));
    }

    public function update(Request $request, $id){

       $validated =  $this->validate($request,[
            'name' =>'required|string',
            'content' => 'required|string'
        ]);

        TemplateVersion::findOrFail($id)->update([
            'user_id' => auth()->user()->id,
            'name' => $validated['name'],
            'content' => $validated['content']
        ]);
     
        return redirect()->back()->withToast('Template updated successfully.');
    }

}
