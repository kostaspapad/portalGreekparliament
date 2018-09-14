<?php

namespace App\Admin\Controllers;

use App\Models\Speech;
use Illuminate\Support\Facades\DB;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SpeechesController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');
            
            // Show speeches count by conference date graph
            $data = $this->speeches_by_conference_graph();
            $content->body(view('admin.charts.speeches_by_conf_line', compact('data')));
            
            // Show data on grid
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Speech::class, function(Grid $grid) {
            // The first column displays the id field and sets the column as a sortable column
            $grid->id('speech_id')->sortable();
            $grid->speech_conference_date();
            $grid->speaker_id();
            $grid->speech()->editable();
            $grid->f_name()->editable();
            $grid->created_at();
            $grid->updated_at();
            $grid->md5();
            
            $grid->filter(function($filter){
                $filter->like('speech', 'speech');
                $filter->like('f_name', 'f_name');
                $filter->like('created_at', 'created_at');
                $filter->like('updated_at', 'updated_at');
                $filter->like('md5', 'md5');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Speech::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    protected function speeches_by_conference_graph()
    {
        return DB::table('speeches')->select('speech_conference_date', DB::raw('count(*) as total'))->groupBy('speech_conference_date')->get();
    }

    protected function graph()
    {
        $data = $this->speeches_by_conference_graph();
        $content->body(view('admin.charts.line', compact('data')));
    }
}
