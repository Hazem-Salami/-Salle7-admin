<?php

namespace App\Http\Controllers\category;

use App\Http\Controllers\Controller;
use App\Http\Requests\category\CreateCategoryRequest;
use App\Http\Requests\category\UpdateCategoryRequest;
use App\Services\Category\CategoryService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     *
     * @var CategoryService
     */
    protected CategoryService $categoryService;

    // singleton pattern, service container
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function createCategory(CreateCategoryRequest $request): Response
    {
        return $this->categoryService->createCategory($request);
    }

    public function updateCategory(UpdateCategoryRequest $request): Response
    {
        return $this->categoryService->updateCategory($request);
    }

    public function deleteCategory(Request $request): Response
    {
        return $this->categoryService->deleteCategory($request);
    }

    public function getCategories(Request $request): Response
    {
        return $this->categoryService->getCategories($request);
    }
}
