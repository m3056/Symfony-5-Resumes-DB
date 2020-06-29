<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Resume;
use App\Form\CompaniesType;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * @Route("/companies")
 */
class CompaniesController extends AbstractController
{
    /**
     * @Route("/", name="companies_index", methods={"GET"})
     */
    public function index(CompanyRepository $companyRepository): Response
    {
        return $this->render('companies/index.html.twig', [
            'companies' => $companyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="companies_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $company = new Companies();
        $form = $this->createForm(CompaniesType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('companies_index');
        }

        return $this->render('companies/new.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }


   

    /**
     * @Route("/{id}", name="companies_show", methods={"GET"})
     */
    public function show(Companies $company): Response
    {
        return $this->render('companies/show.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="companies_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Companies $company): Response
    {
        $form = $this->createForm(CompaniesType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('companies_index');
        }

        return $this->render('companies/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="companies_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Companies $company): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($company);
            $entityManager->flush();
        }

        return $this->redirectToRoute('companies_index');
    }
}
