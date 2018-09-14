<?php

namespace App\Admin\Controllers;

use App\Models\Speaker;
use Illuminate\Support\Facades\DB;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SpeakersController extends Controller
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

            
            // $data = $this->speeches_by_speaker_graph();
            // $content->body(view('admin.charts.speeches_by_speaker_line', compact('data')));

            // Show data on grid
            $content->body($this->grid()->render());
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
        return Admin::grid(Speaker::class, function(Grid $grid) {
                
            // The first column displays the id field and sets the column as a sortable column
            $grid->id('speaker_id')->sortable();
            $grid->english_name()->editable();
            $grid->greek_name()->editable();
            $grid->image()->editable();
            $grid->email()->editable();
            $grid->wiki_el()->editable();
            $grid->wiki_en()->editable();
            $grid->twitter()->editable();
            $grid->website()->editable();
            $grid->filter(function($filter){
                $filter->like('english_name', 'english_name');
                $filter->like('greek_name', 'greek_name');
                $filter->like('image', 'image');
                $filter->like('email', 'email');
                $filter->like('wiki_el', 'wiki_el');
                $filter->like('wiki_en', 'wiki_en');
                $filter->like('twitter', 'twitter');
                $filter->like('website', 'website');
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
        return Admin::form(Speaker::class, function(Form $form){
            $form->display('speaker_id', 'ID');
            $form->text('english_name', trans('admin.english_name'));
            $form->text('greek_name', trans('admin.greek_name'))->rules('required');
            $form->image('image', trans('admin.image'));
            $form->text('wiki_el', trans('admin.wiki_el'));
            $form->text('wiki_en', trans('admin.wiki_en'));
            $form->text('twitter', trans('admin.twitter'));
            $form->text('website', trans('admin.website'));
        });
    }

    public function chart()
    {
        return Admin::content(function (Content $content) {

            $content->header('chart');
            $content->description('.....');

            $content->body(view('admin.charts.bar'));
        });
    }

    protected function speeches_by_speaker_graph()
    {
        return DB::table('speeches')->select('speaker_id', DB::raw('count(*) as total'))->groupBy('speaker_id')->get();
    }
}
