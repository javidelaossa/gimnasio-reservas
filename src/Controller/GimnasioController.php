<?php

namespace App\Controller;

use App\Entity\Actividad;
use App\Entity\Actividades;
use App\Entity\Reserva;
use App\Entity\Reservas;
use App\Entity\Sala;
use App\Entity\Usuario;
use App\Form\ActividadesType;
use App\Form\ActividadType;
use App\Form\ReservasType;
use App\Form\SalasType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class GimnasioController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */

    public function home(): Response
    {
        return $this->render('home.html.twig', [
            'controller_name' => 'GimnasioController'
        ]);
    }

    /**
     * @Route("/gimnasio", name="app_gimnasio")
     */

    public function index(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
//        $request = Request::createFromGlobals();
        $mensaje = $request->query->get('mensaje');
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $actividades = $entityManager->getRepository(Actividad::class)->findAll();
        return $this->render('gimnasio.html.twig', [
            'controller_name' => 'GimnasioController',
            'actividades' => $actividades,
            'mensaje' => $mensaje
        ]);

    }

    /**
     * @Route("/reservasActdirect/{id}", name="app_reservasActdirect")
     */

    public function reservaActividaddirect(Request $request, $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $reservas = new Reserva();
        $actividad = $entityManager->getRepository(Actividad::class)->find($id);


        $reservas->setAsistencia(false);
        $reservas->setUsuario($this->getUser());
        $reservas->setActividad($actividad);
        $entityManager->persist($reservas);
        $entityManager->flush();
        return $this->redirectToRoute('app_gimnasio');

    }

    /**
     * @Route("/reservasAct/{id}", name="app_reservasAct")
     */

    public function reservaActividad(Request $request, $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $reservas = new Reserva();
        $actividad = $entityManager->getRepository(Actividad::class)->find($id);
        $form = $this->createFormBuilder($reservas)
//            ->add('usuario', EntityType::class, [
//                'class' => Usuario::class
//            ])
            ->add('save', SubmitType::class, array('label' => 'Crear Task'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
//            $reservas = $form->getData();
            $reservas->setUsuario($this->getUser());
            $reservas->setActividad($actividad);
            $reservas->setAsistencia(false);
            $entityManager->persist($reservas);
            $entityManager->flush();
            return $this->redirectToRoute('app_gimnasio');
        }
        return $this->render('reservas.html.twig', [
            'controller_name' => $id,
            'actividad' => $actividad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/salas", name="app_salas")
     */
    public function salas(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $roles = $this->getUser()->getRoles();
        if (in_array("ROLE_ADMIN", $roles) || in_array("ROLE_MONITOR", $roles)) {
            $sala = new Sala();
            $form = $this->createForm(SalasType::class, $sala);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $sala = $form->getData();
                $entityManager->persist($sala);
                $entityManager->flush();
                return $this->redirectToRoute('app_salas');
            }
            return $this->render('sala.html.twig', [
                'controller_name' => 'Sala Controller',
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('app_gimnasio', [
                'mensaje' => 'Necesitas ser admin para entrar',
            ]);
        }
    }

    /**
     * @Route("/actividades", name="app_actividades")
     */
    public function actividades(Request $request, SluggerInterface $slugger): Response
    {
        $roles = $this->getUser()->getRoles();
        if (in_array("ROLE_ADMIN", $roles)) {
            $actividades = new Actividad();
            $form = $this->createForm(ActividadType::class, $actividades);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $imagen = $form->get('imagen')->getData();
                if ($imagen) {
                    $originalFilename = pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $imagen->guessExtension();
                    try {
                        $imagen->move(
                            $this->getParameter('imagen_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw new \Exception('Ocurrió un error');
                    }
                    $actividades->setImagen($newFilename);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $actividades = $form->getData();
                $entityManager->persist($actividades);
                $entityManager->flush();
                return $this->redirectToRoute('app_actividades');
            }
            return $this->render('actividades.html.twig', [
                'controller_name' => 'actividades Controller',
                'rol' => $roles,
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('app_gimnasio', [
                'mensaje' => 'Necesitas ser admin para entrar',
            ]);
        }

    }

}
