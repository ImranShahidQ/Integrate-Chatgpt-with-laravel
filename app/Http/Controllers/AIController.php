<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Spatie\PdfToText\Pdf;
use OpenAI;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AIController extends Controller
{

    public function analyze()
    {
        return view('analyze');
    }

    public function generateSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xml,txt,pdf|max:2048',
            'what_is_your_relationship' => 'required|string',
            'time_since_you_know' => 'required|string',
        ], [
            'file.required' => 'Please upload a file.',
            'file.mimes' => 'The file must be either XML, TXT, or PDF format.',
            'file.max' => 'The file size must not exceed 2MB.',
            'what_is_your_relationship.required' => 'Please provide your relationship.',
            'time_since_you_know.required' => 'Please provide the time since you know the person.',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
    
        $file = $request->file('file');
        $filePath = $file->store('uploads');

        $relationship = $request->input('what_is_your_relationship');
        $timeKnown = $request->input('time_since_you_know');

        $summary = $this->analyzeFileAndGenerateSummary($filePath, $relationship, $timeKnown);

        return view('result', ['summary' => $summary]);
    }

    private function analyzeFileAndGenerateSummary($filePath, $relationship, $timeKnown)
    {
        // get content from uploaded file
        $fileContent = $this->getFileContent($filePath);
        // get content from prompt and dropdown fields
        $filePath = storage_path('personalities\persona_default.txt');
        if (File::exists($filePath)) {
            $promptContent = File::get($filePath);
        } else {
            throw new \Exception('File not found: ' . $filePath);
        }
        // replace prompt from other file
        $replaceContent = ["{RELATIONSHIP}", "{TIME_KNOWN}"];
        $replaceWith = [$relationship, $timeKnown];
        $prompt = str_replace($replaceContent, $replaceWith, $promptContent);
        $prompt .= "\n\nChat Content:\n" . $fileContent . "";
        // configure and get response from open ai
        $apiKey = config('services.openai.api_key');
        $client = OpenAI::client($apiKey);
                        
            $result = $client->chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                ['role' => 'user', 'content' => $prompt],
                            ],
            ]);
                        
        return $result->choices[0]->message->content;
    }
    // private functions for check file extensions
    private function getFileContent($filePath) 
    {
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        switch ($fileExtension) 
        {
            case 'xml':
                $fileContent = $this->readXmlFile($filePath);
                break;
            case 'txt':
                $fileContent = $this->readTxtFile($filePath);
                break;
            case 'pdf':
                $fileContent = $this->readPdfFile($filePath);
                break;
            default:
                return 'Unsupported file format';
        }
        return $fileContent;
    }
    // private function for get content from Xml file
    private function readXmlFile($filePath)
    {
        $fileContent = file_get_contents(storage_path('app/' . $filePath));
        return $fileContent;
    }
    // private function for get content from Txt file
    private function readTxtFile($filePath)
    {
        $fileContent = file_get_contents(storage_path('app/' . $filePath));
        return $fileContent;
    }
    // private function for get content from Pdf file
    private function readPdfFile($filePath)
    {
        $fileContent = (new \Spatie\PdfToText\Pdf('Path\to\pdftotext.exe'))->setPdf(storage_path('app/' . $filePath))->text();
        return $fileContent;
    }
}