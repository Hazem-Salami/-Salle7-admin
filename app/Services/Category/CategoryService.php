<?php

namespace App\Services\Category;

use App\Http\Requests\category\CreateCategoryRequest;
use App\Http\Requests\category\UpdateCategoryRequest;
use App\Http\Traits\Base64Trait;
use App\Http\Traits\FilesTrait;
use App\Jobs\category\CreateCategoryJob;
use App\Jobs\category\DeleteCategoryJob;
use App\Jobs\category\UpdateCategoryJob;
use App\Models\Category;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CategoryService extends BaseService
{
    use Base64Trait, FilesTrait;
    /**
     * @param CreateCategoryRequest
     * @return Response
     */
    public function createCategory($request): Response
    {
        DB::beginTransaction();

        $file = $request->file('category_photo');

        if ($file != null) {
            $category_photo = [$this->base64Encode($file), $file->getClientOriginalExtension()];

            $path = $this->storeFile($file, 'category_photo');
        }

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image_path' => $path,
        ]);

        $category->category_photo = $category_photo;
        $parent = $category->category;
        $category->parent_name = $parent? $parent->name : null;

        try {

            CreateCategoryJob::dispatch($category->toArray())->onQueue('store');
            CreateCategoryJob::dispatch($category->toArray())->onQueue('main');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->customResponse(false, 'Bad Internet', null, 504);
        }
        DB::commit();

        return $this->customResponse(true, 'Create Category Success',
            $category->only(['id','name','description','image_path','category_id','created_at','updated_at']));
    }

    /**
     * @param UpdateCategoryRequest
     * @return Response
     */
    public function updateCategory($request): Response
    {
        DB::beginTransaction();

        $category = $request->get('category');

        if($category->id != $request->category_id) {

            $old_name = $category->name;

            $category_photo = null;

            if ($request->has('category_photo')) {

                $file = $request->file('category_photo');

                if ($file != null) {
                    $category_photo = [$this->base64Encode($file), $file->getClientOriginalExtension()];

                    $image_path = $this->storeFile($file, 'category_photo');

                    $this->destoryFile($category->image_path);

                    $category->image_path = $image_path;
                }
            }
            $category->update($request->all());

            $category->old_name = $old_name;
            $category->category_photo = $category_photo;
            $parent = $category->category;
            $category->parent_name = $parent? $parent->name : null;

            try {

                UpdateCategoryJob::dispatch($category->toArray())->onQueue('store');
                UpdateCategoryJob::dispatch($category->toArray())->onQueue('main');

            } catch (\Exception $e) {
                DB::rollBack();
                return $this->customResponse(false, 'Bad Internet', null, 504);
            }
            DB::commit();

            return $this->customResponse(true, 'update Category Success',
                $category->only(['id','name','description','image_path','category_id','created_at','updated_at']));

        } else{
            return $this->customResponse(false, 'parent category and category are the same', null, 400);
        }
    }

    /**
     * @param Request
     * @return Response
     */
    public function deleteCategory($request): Response
    {
        DB::beginTransaction();

        $category = $request->get('category');

        $this->destoryFile($category->image_path);

        $category->delete();

        try {

            DeleteCategoryJob::dispatch($category->toArray())->onQueue('store');
            DeleteCategoryJob::dispatch($category->toArray())->onQueue('main');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->customResponse(false, 'Bad Internet', null, 504);
        }
        DB::commit();

        return $this->customResponse(true, 'delete Category Success');
    }

    /**
     * @param Request
     * @return Response
     */
    public function getCategories($request): Response
    {
        $categories = Category::where('category_id', null)->paginate(\request('size'));
        $this->getChildren($categories,$request->children_id,$request->load_more);

        return $this->customResponse(true, 'get Categories Success', $categories);
    }

    private function getChildren($categories, $children_id = null, $load_more = 1)
    {
        foreach ($categories as $key => $category){

            $category->key = $category->id;

            $category->data = [
                "name" => $category->name,
                "description" => $category->description,
                "category_id" => $category->category_id,
                "image_path" => $category->image_path,
                "created_at" => $category->created_at,
                "updated_at" => $category->updated_at,
            ];

            if($children_id && $category->id == $children_id){

                $category->children = Category::where('category_id', $category->id)->limit($load_more * \request('capacity'))->get();

            }else{

                $category->children = Category::where('category_id', $category->id)->limit(\request('capacity'))->get();
            }

            $this->getChildren($category->children,$children_id,$load_more);

            $categories[$key] = $category->only(['key','data','children']);
        }
        return $categories;
    }
}
