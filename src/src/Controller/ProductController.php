<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\SearchProduct;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/{_locale}/product", defaults={"_locale": "en"}, requirements={"_locale": "en|fa"})
 */
#[Route('/product')]
class ProductController extends AbstractController
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function mapCategoryList($p)
    {
        $arr = new \stdClass();
        foreach ($p as $key => $value) {
            $arr->{$value->getName()} = $key;
        }
        return $arr;
    }

    #[Route('/product_lists', name: 'product_lists', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
            "categories" => json_decode(json_encode($this->mapCategoryList($this->categoryRepository->findAll())), true),
        ]);
    }

    #[Route('/search', name: 'app_product_search', methods: ['GET'])]
    public function search(ProductRepository $productRepository, Request $request, SearchProduct $searchProduct): Response
    {
        $q = $request->query->get('q');
        $c = $request->query->get('c');
        if ($q && $c == -1) {
            $products = $searchProduct->search($q);
        }
        if ($c !== -1 && !$q) {
            $products = $searchProduct->searchByBrand($c);
        }
        if ($q && $c !== "-1") {
            $products = $searchProduct->search($q);
            $isFinded = false;
            $productsByBrand = $searchProduct->searchByBrand($c);
            for ($x = 0; $x < count($productsByBrand); $x++) {
                for ($y = 0; $y < count($products); $y++) {
                    if ($productsByBrand[$x] == $products[$y]) {
                        $productsByBrand[$x] = $products[$y];
                        $isFinded = true;
                    }
                }
            }
            if ($isFinded)
                $products = $productsByBrand;
            else
                $products = [];
        }
        if ($c == -1 && !$q)
            $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'query' => $q,
            'queryByBrand' => $c,
            "categories" => json_decode(json_encode($this->mapCategoryList($this->categoryRepository->findAll())), true),
        ]);
    }

    #[Route('/new_product', name: 'new_product', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $product->setUrl($newFilename);
            }
            $productRepository->add($product, true);
            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        $isExistProduct = false;
        if ($product->getId() !== null) {
            $isExistProduct = true;
        }
        $isLoggedIn = false;
        if ($this->getUser()) {
            $isLoggedIn = true;
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
            "isLoggedIn" => $isLoggedIn,
            "isExistProduct" => $isExistProduct,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product, true);

            return $this->redirectToRoute('product_lists', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }
        return $this->redirectToRoute('product_lists', [], Response::HTTP_SEE_OTHER);
    }
}
