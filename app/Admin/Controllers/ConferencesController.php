<?php

namespace App\Admin\Controllers;

use App\Conference;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ConferencesController extends Controller
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
            $grid = Admin::grid(Conference::class, function(Grid $grid){
                
                // The first column displays the id field and sets the column as a sortable column
                $grid->id('id')->sortable();
                $grid->conference_date();
                $grid->conference_indicator();
                $grid->doc_location();
                $grid->doc_name();
                $grid->video_link();
                $grid->session();
                $grid->date_of_crawl();
                $grid->pdf_loc();
                $grid->pdf_name();
                $grid->time_period();
                $grid->downloaded();

                $grid->filter(function($filter){
                    $filter->between('conference_date', 'conference_date')->datetime();
                    $filter->like('conference_indicator', 'conference_indicator');
                    $filter->like('time_period', 'time_period');
                    $filter->like('session', 'session');
                    $filter->between('date_of_crawl', 'date_of_crawl')->datetime();
                });
            });
            
            $content->body($grid);
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
        return Admin::grid(Conference::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Conference::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
