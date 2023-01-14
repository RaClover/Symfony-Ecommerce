<?php

namespace App\Controller\Api;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api' , name: 'product_api')]
class ProductController extends AbstractController
{






    #[Route('/products' , name: 'products' , methods: ['GET'])]
    public function getProducts(Request $request , ProductsRepository $productsRepository):Response
    {
        $products = $productsRepository->findAll();

        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'status' => 200,
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price'  => $product->getPrice(),
                'color' => $product->getColor(),
                'size' => $product->getSize(),
                'image' => $product->getImage()
            ];
       }
        return $this->response($data);
    }


    #[Route('/products' , name: 'products_add' , methods: ['POST'])]
    public function addPost(Request $request , EntityManagerInterface $entityManager , ProductsRepository $productsRepository)
    {
        try{
            $request = $this->transformJsonBody($request);


            if (!$request || !$request->get('name') || !$request->request->get('description')){
                throw new \Exception();
            }

            $product = new Products();
            $product->setName($request->get('name'));
            $product->setDescription($request->get('description'));
            $product->setPrice($request->get('price'));
            $product->setColor($request->get('color'));
            $product->setSize($request->get('size'));
            $product->setImage($request->get('image'));
            $entityManager->persist($product);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'message' => "Product added successfully",
            ];
            return $this->response($data);

        }catch (\Exception $e){
            $data = [
                'status' => 422,
                'errors' => "Data not valid",
            ];
            return $this->response($data, 422);
    }
    }





    #[Route('/products/{id}' , name: 'products_get' , methods: ['GET'])]
    public function getPost(ProductsRepository $productsRepository, $id){
        $product = $productsRepository->find($id);
        $data = [];
        if (!$product){
            $data = [
                'status' => 404,
                'errors' => "Product not found",
            ];
        }else{
            $data = [
                'status' => 200,
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price'  => $product->getPrice(),
                'color' => $product->getColor(),
                'size' => $product->getSize(),
                'image' => $product->getImage()
            ];
        }
        return $this->response($data );
    }




    #[Route('/products/{id}' , name: 'products_put' , methods: ['PUT'])]
    public function updatePost(Request $request, EntityManagerInterface $entityManager, ProductsRepository $productsRepository
        , $id){

//        try{
            $product = $productsRepository->find($id);
            if (!$product){
                $data = [
                    'status' => 404,
                    'errors' => "Product not found",
                ];
                return $this->response($data, 404);
            }

            $request = $this->transformJsonBody($request);

//            if (!$request || !$request->get('name') || !$request->request->get('description')){
//                throw new \Exception();
//            }

            $product->setName($request->get('name'));
//            $product->setDescription($request->get('description'));
//            $product->setPrice($request->get('price'));
//            $product->setColor($request->get('color'));
//            $product->setSize($request->get('size'));
//            $product->setImage($request->get('image'));
            $entityManager->flush();



            $data = [
                'status' => 200,
                'message' => "Product updated successfully",
            ];
            return $this->response($data);

//        }catch (\Exception $e){
//            $data = [
//                'status' => 422,
//                'errors' => "Data no valid",
//            ];
//            return $this->response($data, 422);
//        }

    }




    #[Route('/products/{id}' , name: 'products_delete' , methods: ['DELETE'])]
    public function deletePost(EntityManagerInterface $entityManager, ProductsRepository $productsRepository, $id){
        $product = $productsRepository->find($id);

        if (!$product){
            $data = [
                'status' => 404,
                'errors' => "Product not found",
            ];
            return $this->response($data, 404);
        }

        $entityManager->remove($product);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "Product deleted successfully",
        ];
        return $this->response($data);
    }



    public function response($data, $status = 200, $headers = [])
    {
        return $this->json($data, $status, $headers);
    }

    protected function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }





}