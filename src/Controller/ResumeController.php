<?php

namespace App\Controller;

use App\Entity\Resume;
use App\Entity\Companies;
use App\Entity\SendedResumes;
use App\Form\ResumeType;
use App\Form\SendResumeType;
use App\Repository\ResumeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * @Route("/resumes")
 */
class ResumeController extends AbstractController
{
    /**
     * @Route("/", name="resume_index", methods={"GET"})
     */
    public function index(ResumeRepository $resumeRepository): Response
    {
        return $this->render('resume/index.html.twig', [
            'resumes' => $resumeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="resume_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $resume = new Resume();
        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);
        $resume->setCreateDate(date('j-m-Y'));
        $resume->setChangeDate("");

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $resume->getText();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('created_resumes_directory'),
                $fileName
            );

            $resume->setText($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($resume);
            $entityManager->flush();

            

            return $this->redirectToRoute('resume_index');
        }
        
        return $this->render('resume/new.html.twig', [
            'resume' => $resume,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/new/{id}", name="send_resume", methods={"GET","POST"})
     */
    public function sendResume(Request $request, Companies $company): Response
    {
        $sendedResume = new SendedResumes();
        $form = $this->createForm(SendResumeType::class, $sendedResume);
        $form->handleRequest($request);
    
        $sendedResume->setDate(date('F j, Y, g:i a'));
        $random = random_int(0, 1);
        $sendedResume->setReaction($random);
        $sendedResume->setCompanyName($company->getName());
        $groupResumes = $this->getDoctrine()
                    ->getRepository(SendedResumes::class)
                    ->groupResumeByNameAndReaction();


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $sendedResume->getPath();
             
            /* $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension(); */
            $fileName = $file->getClientOriginalName();

            
            $file->move(
                $this->getParameter('sended_resumes_directory'),
                $fileName
            );

          
            $sendedResume->setPath($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sendedResume);
            $entityManager->flush();

            return //$this->redirectToRoute('companies_index'); 
            $this->render('resume/group_resume.html.twig', [
                'groupResumes' => $groupResumes
            ] );
        } 

         return $this->render('resume/send_resume.html.twig' , [
            //'company' => $company,
            'form' => $form->createView()
        ] ); 
    }

     /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * @Route("/{id}", name="resume_show", methods={"GET"})
     */
    public function show(Resume $resume): Response
    {
        
        return $this->render('resume/show.html.twig', [
            'resume' => $resume,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="resume_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Resume $resume): Response
    {
        $resume->setText(
            new File($this->getParameter('brochures_directory').'/'.$resume->getText())
        );
        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);
        $resume->setChangeDate(date('j-m-Y'));
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $resume->getText();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );

            $resume->setText($fileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('resume_index');
        }

        return $this->render('resume/edit.html.twig', [
            'resume' => $resume,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="resume_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Resume $resume): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resume->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resume);
            $entityManager->flush();
        }

        return $this->redirectToRoute('resume_index');
    }
}
