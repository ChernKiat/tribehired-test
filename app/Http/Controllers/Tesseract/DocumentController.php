<?php

namespace App\Http\Controllers\Tesseract;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tesseract\DocumentRequest;
use App\Models\Tesseract\Document;
use App\Models\Tesseract\Page;
use App\Skins\FileSkin;
use App\Skins\GitHub\PDFMinerSixSkin;
use App\Skins\GitHub\PDFToImageSkin;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::query();

        if ($request->search) {
            $query->where('name', "like", "%{$request->search}%");
        }

        $data = $query->orderBy('id', 'desc')->paginate(10);

        return view('tesseract.index', compact('data'));
    }

    public function create()
    {
        return view('tesseract.create');
    }

    public function store(DocumentRequest $request)
    {
        try {
            $document = new Document();
            $document->name         = $request->name;
            $document->description  = $request->description;
            $document->save();
            
            // if ($request->hasFile('file')) {
                $folder = "documents\\{$document->id}";
                $file = new FileSkin($request->file('file'));
                $fileName = $file->save($folder);

                $document->filename   = "{$file->name_set['name']}-{$file->name_set['random']}";
                $document->extension  = $file->file->getClientOriginalExtension();

                dd('double', 'check');
                // PDFMinerSixSkin
                $pdfMinerSix = new PDFMinerSixSkin();
                $data = $pdfMinerSix->findText($file->full_path, $fileName, false);
                $document->quick_extract = "";

                // PDFToImageSkin
                if ($document->extension == 'pdf') {
                    $pdfToImage = new PDFToImageSkin();
                    $pdfToImage->savePDFToImage($file->full_path, $document->filename, $document->extension);

                    $document->page         = $file->page;
                }

                $document->save();
                dd('double', 'check');
            // }

            for ($i = 1; $i < $document->page + 1; $i++) {
                $page = new Page();
                $page->document_id  = $document->id;
                $page->number       = $i;
                $page->copy         = "a:0:{}";
                $page->save();
            }

                // PDFMinerSkin
                $file = new PDFMinerSkin($file);
                $data = $file->setOutputFilename("output.txt");
                // $file->encoding_format = PDFMinerSkin::LANGUAGE_JAPANESE_ENCODING;
                // $file->vertical_writing_detection = true;
                $data = $file->convertPDF($folder);
        } catch (Exception $e) {
            throw $e;
        }

        return redirect()->route('documents.index')->withSuccess('Document ' . trans('tesseract.created_successfully_dot'));
    }

    public function edit($id)
    {
        $document = Document::find($id);

        return view('create', [
            'edit' => true,
            'data' => $document
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $document = Document::find($id);
            $document->name         = $request->name;
            $document->description  = $request->content;
            $document->save();
        } catch (Exception $e) {
            throw $e;
        }

        return redirect()->route('documents.index')->withSuccess('Document ' . trans('default.updated_successfully_dot'));
    }

    public function crop($id)
    {
        $pages = Page::with('document')->where('document_id', $id)->paginate(1);

        return view('crop', [
            'data' => $pages
        ]);
    }

    public function export($id)
    {
        $pages = Page::with('document')->where('document_id', $id)->paginate(1);

        return view('crop', [
            'data' => $pages
        ]);
    }

    public function destroy($id)
    {
        Document::where('id', $id)->delete();

        return redirect()->route('documents.index')->withSuccess('Document ' . trans('default.deleted_successfully_dot'));
    }
}
