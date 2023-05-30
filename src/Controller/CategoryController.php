<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryForm;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{

    #[Route('/', name: 'read')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', ['categories' => $categories]);
    }

    #[Route('/create', name: 'create')]
    public function createGet(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryForm::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            
            // сохранение категории в базу данных
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('read');
        }

        return $this->render('category/form.html.twig', [
            'form' => $form,
        ]);
    }

    // #[Route('/', name: 'create', methods: ['POST'])]
    // public function create(EntityManagerInterface $entityManager): Response
    // {

    //     // $categories = new Category();
    //     // $categories->setTitle('title')
    //     //     ->setDescription('description')
    //     //     ->setImg('Img');
    //     //     // ->setCategory();
    //     // $categories1 = new Category();
    //     // $categories1->setTitle('title1')
    //     //     ->setDescription('description1')
    //     //     ->setImg('Img1')
    //     //     ->setCategory($categories);

    //     // $entityManager->persist($categories);
    //     // $entityManager->flush();
    //     // $entityManager->persist($categories1);
    //     // $entityManager->flush();
    //     // $request = Request::createFromGlobals();
    //     // dd($request->getMethod());

    //     return $this->render('category/index.html.twig');
    // }
}
