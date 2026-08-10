<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\TemplateVersion;

class TemplateController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(){
      
        $templates = Template::get();

        $templateVersions = TemplateVersion::where('user_id',auth()->user()->id)->get();

        return view('email-templates.index',compact('templates', 'templateVersions'));
    }

    public function create(){

    }

    public function store(){

    }
    
    public function edit($id){
        $type = 'template';
        $template = Template::findOrFail($id);
        return view('email-templates.edit', compact('template','type'));
    }

    public function update(Request $request, $id){

       $validated =  $this->validate($request,[
            'name' =>'required|string',
            'content' => 'required|string'
        ]);

        $template = Template::findOrFail($id);
        
        // Create a new version
        $template->templateVersions()->create([
            'user_id' => auth()->user()->id,
            'name' => $validated['name'],
            'content' => $validated['content']
        ]);
     
        return redirect()->back()->withToast('Template updated successfully.');
    }

    public function delete(){

    }
}
