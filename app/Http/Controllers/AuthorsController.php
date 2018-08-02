<?php

namespace App\Http\Controllers;

use App\Authors;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Session;

class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index(Request $request, Builder $builder)
    {
        if ($request->ajax()) {
            $authors = Authors::select(['id', 'name']);
            return Datatables::of($authors)
                    ->addColumn('action', function ($authors) {
                        return view('datatable._action', [
                        'model'=> $authors,
                        'form_url'=> route('authors.destroy', $authors->id),
                        'edit_url' => route('authors.edit', $authors->id),
                        'confirm_message' => 'Yakin mau menghapus ' . $authors->name . '?'
                    ]);
                    })->make(true);
        }
        $html = $builder
            ->addColumn(['data' => 'name', 'name'=>'name', 'title'=>'Nama'])
            ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false,
                         'searchable'=>false]);
        return view('authors.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
{
    $this->validate($request, ['name' => 'required|unique:authors']);
    $authors = Authors::create($request->only('name'));
    Session::flash("flash_notification", [
    "level"=>"success",
    "message"=>"Berhasil menyimpan $authors->name"
]);
    return redirect()->route('authors.index');
}
    public function edit($id)
{
    $authors = Authors::find($id);
    return view('authors.edit')->with(compact('authors'));

}
    public function update(Request $request, $id)
{
    $this->validate($request, ['name' => 'required|unique:authors,name,'. $id]);
    $authors = Authors::find($id);
    $authors->update($request->only('name'));
    Session::flash("flash_notification", [
    "level"=>"success",
    "message"=>"Berhasil menyimpan $authors->name"
]);
    return redirect()->route('authors.index');

}
    public function destroy($id)
    {
        if(!Authors::destroy($id)) return redirect()->back();
        Session::flash("flash_notification", [
        "level"=>"success",
        "message"=>"Penulis berhasil dihapus"
        ]);
        return redirect()->route('authors.index');
    }
}