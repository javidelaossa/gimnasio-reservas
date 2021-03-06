<?php

namespace App\Controller;

use App\Entity\Actividad;
use App\Entity\Actividades;
use App\Entity\Reserva;
use App\Entity\Sala;
use App\Entity\TipoAct;
use App\Entity\Usuario;
use App\Form\ActividadType;
use App\Form\SalasType;
use App\Form\TipoActType;
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
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $mensaje = $request->query->get('mensaje');
            $entityManager = $this->getDoctrine()->getManager();
            $actividades = $entityManager->getRepository(Actividad::class)->findAll();
            return $this->render('gimnasio.html.twig', [
                'controller_name' => 'GimnasioController',
                'actividades' => $actividades,
                'mensaje' => $mensaje
            ]);
        } else {
            return $this->redirectToRoute('app_login', [
                'error' => 'Necesitas ser admin para entrar',
            ]);
        }

    }

    /**
     * @Route("/asistencia", name="app_asistencia")
     */

    public function asistencia(Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MONITOR')) {
            $entityManager = $this->getDoctrine()->getManager();
            $dia = date('Y-m-d');
            $hora = date('H:00:00');
            $horafin = date('H:00:00', strtotime('1 hour'));
            $qb = $entityManager->createQueryBuilder();
            $query = $qb->select('u')
                ->from(Reserva::class, 'u')
                ->where('u.dia = :dia')
//            ->innerJoin(Actividad::class, 'a', 'WITH', 'a.hinic = :hinic')
//            ->innerJoin(Usuario::class, 'p', 'WITH', 'p.id = :monitor')
//            ->innerJoin(Actividad::class, 'a', 'WITH', 'a.monitor = :monitor')
//            ->innerJoin(Actividad::class, 'a1', 'WITH', 'a1.hinic = :hinic ')
//            ->innerJoin(Actividad::class, 'a2', 'WITH', 'a2.hfin = :hfin')
//            ->setParameter('hinic', $hora)
//            ->setParameter('hfin', $horafin)
//            ->setParameter('monitor', $this->getUser()->getId())
                ->setParameter('dia', $dia)
                ->getQuery();

            return $this->render('asistencia.html.twig', [
                'controller_name' => 'Asistencia',
                'asistencia' => $query->getResult(),
            ]);
        } else {
            return $this->redirectToRoute('app_gimnasio', [
                'mensaje' => 'Necesitas ser admin para entrar',
            ]);
        }

    }

    /**
     * @Route("/asistencia/{id}", name="app_confasistencia")
     */

    public function confirmarAsistencia(Request $request, $id): Response
    {
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MONITOR')) {
            $entityManager = $this->getDoctrine()->getManager();
            $reservas = $entityManager->getRepository(Reserva::class)->find($id);
            $reservas->setAsistencia(true);
            $entityManager->persist($reservas);
            $entityManager->flush();
            return $this->redirectToRoute('app_asistencia');
        } else {
            return $this->redirectToRoute('app_gimnasio', [
                'mensaje' => 'Necesitas ser admin para entrar',
            ]);
        }
    }


    /**
     * @Route("/actividades", name="app_actividades")
     */

    public function actividades(Request $request): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $entityManager = $this->getDoctrine()->getManager();
            $actividades = $entityManager->getRepository(TipoAct::class)->findAll();
            return $this->render('actividadeslista.html.twig', [
                'actividades' => $actividades,
            ]);

        } else {
            return $this->redirectToRoute('app_login', [
                'error' => 'Necesitas ser admin para entrar',
            ]);
        }


    }

    /**
     * @Route("/crearActividadesInd", name="app_crearActividadesInd")
     */

    public function crearActividadesInd(Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MONITOR')) {
            $entityManager = $this->getDoctrine()->getManager();
            $actividades = $entityManager->getRepository(Actividades::class)->findAll();
            return $this->render('actividadeslista.html.twig', [
                'actividades' => $actividades,
            ]);
        } else {
            return $this->redirectToRoute('app_gimnasio', [
                'mensaje' => 'Necesitas ser admin para entrar',
            ]);
        }
    }

    /**
     * @Route("/crearActividadesIndForm", name="app_crearActividadesIndForm")
     */

    public function crearActividadesIndForm(Request $request, SluggerInterface $slugger): Response
    {
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MONITOR')) {
            $tipoAct = new TipoAct();
            $form = $this->createForm(TipoActType::class, $tipoAct);
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
                        throw new \Exception('Ocurri?? un error');
                    }
                    $tipoAct->setImagen($newFilename);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $tipoAct = $form->getData();
                $entityManager->persist($tipoAct);
                $entityManager->flush();
                return $this->redirectToRoute('app_crearActividadesIndForm');
            }
            return $this->render('actividadeslistaform.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('app_gimnasio', [
                'mensaje' => 'Necesitas ser admin para entrar',
            ]);
        }
    }

    /**
     * @Route("/reservasActdirect/{id}", name="app_reservasActdirect")
     */

    public function reservaActividaddirect(Request $request, $id): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $entityManager = $this->getDoctrine()->getManager();
            $reservas = new Reserva();
            $actividad = $entityManager->getRepository(Actividad::class)->find($id);

            $reservas->setAsistencia(false);
            $reservas->setUsuario($this->getUser());
            $reservas->setActividad($actividad);
            $reservas->setDia(\DateTime::createFromFormat('U', time()));
            $entityManager->persist($reservas);
            $entityManager->flush();
            return $this->redirectToRoute('app_gimnasio');
        } else {
            return $this->redirectToRoute('app_login', [
                'error' => 'Necesitas ser admin para entrar',
            ]);
        }

    }

    /**
     * @Route("/actividad/{id}", name="app_actividadesind")
     */

    public function actividadesLista(Request $request, $id): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {

            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $query = $qb->select('u')
                ->from(Actividad::class, 'u')
                ->where('u.nombre = :id')
                ->setParameter('id', $id)
                ->getQuery();

            return $this->render('gimnasio.html.twig', ['controller_name' => 'GimnasioController',
                'actividades' => $query->getResult()]);

        } else {
            return $this->redirectToRoute('app_login', [
                'error' => 'Necesitas ser admin para entrar',
            ]);
        }
    }


    /**
     * @Route("/reservasAct/{id}", name="app_reservasAct")
     */

    public function reservaActividad(Request $request, $id): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {

            $entityManager = $this->getDoctrine()->getManager();
            $reservas = new Reserva();
            $actividad = $entityManager->getRepository(Actividad::class)->find($id);
            $form = $this->createFormBuilder($reservas)
                ->add('Reservar', SubmitType::class,
                    array('attr' => array('class' => 'btn btn-primary')))
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $dia = date('Y-m-d');
                $entityManager = $this->getDoctrine()->getManager();
                $reservas->setUsuario($this->getUser());
                $reservas->setActividad($actividad);
                $reservas->setAsistencia(false);
                $reservas->setDia(\DateTime::createFromFormat('U', time()));
                $entityManager->persist($reservas);
                $entityManager->flush();
                return $this->redirectToRoute('app_gimnasio');
            }
            return $this->render('reservas.html.twig', [
                'controller_name' => $id,
                'actividad' => $actividad,
                'form' => $form->createView()
            ]);

        } else {
            return $this->redirectToRoute('app_login', [
                'error' => 'Necesitas ser admin para entrar',
            ]);
        }
    }

    /**
     * @Route("/salas", name="app_salas")
     */
    public function salas(Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
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
     * @Route("/actividadesCrear", name="app_actividadescrear")
     */
    public
    function actividadesCrear(Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MONITOR')) {
            $actividades = new Actividad();
            $form = $this->createForm(ActividadType::class, $actividades);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $actividades = $form->getData();
                $entityManager->persist($actividades);
                $entityManager->flush();
                return $this->redirectToRoute('app_actividadescrear');
            }
            return $this->render('actividades.html.twig', [
                'controller_name' => 'actividades Controller',
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('app_gimnasio', [
                'mensaje' => 'Necesitas ser admin para entrar',
            ]);
        }
    }

}
