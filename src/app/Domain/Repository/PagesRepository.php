<?php
/**
 * Created by PhpStorm.
 * User: andreafrittella
 * Date: 23/02/18
 * Time: 23:31
 */

namespace Afrittella\LaravelPages\Domain\Repository;


use Afrittella\LaravelPages\Domain\Model\Page;
use Afrittella\LaravelRepository\Repositories\BaseRepository;

class PagesRepository extends BaseRepository
{
    public function model()
    {
        return Page::class;
    }

    public function all($columns = ['*']): Page
    {
        return $this->model->withDepth()
            ->defaultOrder()
            ->whereIsRoot()
            ->select($columns);
    }

    public function children($id): Page
    {
        return $this->model->withDepth()
            ->defaultOrder()
            ->descendantsOf($id)
            ->where('parent_id', '=', $id);
    }

    public function find($id, $columns = array('*')): Page
    {
        $this->applyCriteria();
        return $this->model->withDepth()->find($id, $columns);
    }

    public function create(array $data)
    {
        $data['slug'] = $data['title'];

        return parent::create($data);
    }

    public function moveUp($id)
    {
        $node = $this->model->find($id);
        return $node->up();
    }

    public function moveDown($id)
    {
        $node = $this->model->find($id);
        return $node->down();
    }

    public function tree($name)
    {
        $root = $this->findBy('title', $name);
        $tree = false;

        if (!empty($root)) {
            $tree = $this->model->withDepth()->defaultOrder()->descendantsOf($root->id)->toTree();
        }

        return $tree;
    }
}