<?php namespace Gdoo\Product\Controllers;

use DB;
use Request;
use Validator;

use Gdoo\Model\Grid;
use Gdoo\Model\Form;
use Gdoo\Product\Models\ProductUnit;

use Gdoo\Index\Controllers\DefaultController;

class UnitController extends DefaultController
{
    public $permission = ['dialog'];

    public function index()
    {
        $header = Grid::header([
            'code' => 'product_unit',
            'referer' => 1,
            'search' => ['by' => ''],
        ]);

        $cols = $header['cols'];

        $cols['actions']['options'] = [[
            'name' => '编辑',
            'action' => 'edit',
            'display' => $this->access['edit'],
        ]];

        $header['buttons'] = [
            ['name' => '删除', 'icon' => 'fa-remove', 'action' => 'delete', 'display' => $this->access['delete']],
            ['name' => '导出', 'icon' => 'fa-share', 'action' => 'export', 'display' => 1],
        ];
        $header['cols'] = $cols;
        $header['tabs'] = ProductUnit::$tabs;

        $search = $header['search_form'];
        $query = $search['query'];

        if (Request::method() == 'POST') {
            $model = DB::table($header['table'])->setBy($header);
            foreach ($header['join'] as $join) {
                $model->leftJoin($join[0], $join[1], $join[2], $join[3]);
            }
            $model->orderBy('code', 'asc');

            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }

            $model->select($header['select']);
            $rows = $model->paginate($query['limit'])->appends($query);
            return Grid::dataFilters($rows, $header);
        }

        return $this->display([
            'header' => $header,
        ]);
    }

    public function create()
    {
        $id = (int)Request::get('id');
        $form = Form::make(['code' => 'product_unit', 'id' => $id]);
        return $this->render([
            'form' => $form,
        ], 'create');
    }

    public function edit()
    {
        return $this->create();
    }

   public function dialog()
    {
        $search = search_form([], [
            ['text','product_unit.name','名称'],
            ['text','product_unit.id','ID'],
        ]);

        if (Request::method() == 'POST') {
            $model = ProductUnit::orderBy('sort', 'asc');
            foreach ($search['where'] as $where) {
                if ($where['active']) {
                    $model->search($where);
                }
            }
            $rows = $model->get(['*', 'name as text']);
            return ['data' => $rows];
        }
        return $this->render([
            'search' => $search,
        ]);
    }

    public function delete()
    {
        if (Request::method() == 'POST') {
            $ids = Request::get('id');
            return Form::remove(['code' => 'product_unit', 'ids' => $ids]);
        }
    }
}
