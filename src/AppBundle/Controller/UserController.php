<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Profile;
use AppBundle\Form\Type\ProfileType;
use AppBundle\Utils\Tools;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/admin-area/user", name="crd_user")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $data = [];

        $data['subset'] = $this->getDoctrine()->getRepository('AppBundle:Profile')->paginate($request, $this->getUser()->getProfile());
        $data['pagination'] = Tools::pagination($data['subset']);
        $data['keyword'] = $request->query->get('keyword');

        return $this->render('user/index.html.twig', $data);
    }

    /**
     * @Route("/admin-area/user/create", name="crd_user_create")
     * @Method({"GET","POST"})
     */
    public function createAction(Request $request)
    {
        $admin = new Profile;
        $form = $this->createForm(ProfileType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $admin->getUser()->encodeNewPassword($this->get('security.password_encoder'));
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($admin);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Data has been saved');

            return $this->redirectToRoute('crd_user');
        }

        return $this->render('user/form.html.twig', [
            'form'=>$form->createView(),
            'title'=>'New Item',
            'menu_active'=>'crd_user',
        ]);
    }

    /**
     * @Route("/admin-area/user/{id}/update", name="crd_user_update")
     * @Method({"GET","POST"})
     * @ParamConverter("admin", class="AppBundle:Profile")
     */
    public function updateAction(Request $request, Profile $admin)
    {
        $form = $this->createForm(ProfileType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($admin);
            $em->persist($admin->getUser()->encodeNewPassword($this->get('security.password_encoder')));
            $em->flush();

            $this->addFlash('success', 'Data has been saved');

            return $this->redirectToRoute('crd_user');
        }

        return $this->render('user/form.html.twig', [
            'form'=>$form->createView(),
            'title'=>'Edit #'.$admin->getName(),
            'menu_active'=>'crd_user',
        ]);
    }

    /**
     * @Route("/admin-area/user/{id}/delete", name="crd_user_delete")
     * @Method({"DELETE"})
     * @ParamConverter("admin", class="AppBundle:Profile")
     */
    public function deleteAction(Profile $admin)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($admin);
        $em->remove($admin->getUser());
        $em->flush();

        return $this->json([
            'success'=>true,
            'message'=>'Data has been deleted',
        ]);
    }
}
