<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Admin;
use AppBundle\Form\Type\AdminType;
use AppBundle\Utils\Tools;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/adm/admin", name="adm_admin")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $data = [];

        $data['subset'] = $this->getDoctrine()->getRepository('AppBundle:Admin')->paginate($request);
        $data['pagination'] = Tools::pagination($data['subset']);
        $data['keyword'] = $request->query->get('keyword');

        return $this->render('admin/admin/index.html.twig', $data);
    }

    /**
     * @Route("/adm/admin/create", name="adm_admin_create")
     * @Method({"GET","POST"})
     */
    public function createAction(Request $request)
    {
        $admin = new Admin;
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $admin->getUser()->encodeNewPassword($this->get('security.password_encoder'));
            $user->setRoles(['ROLE_ADMIN']);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($admin);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Data has been saved');

            return $this->redirectToRoute('adm_admin');
        }

        return $this->render('admin/admin/form.html.twig', [
            'form'=>$form->createView(),
            'title'=>'New Item',
            'menu_active'=>'adm_admin',
        ]);
    }

    /**
     * @Route("/adm/admin/{id}/update", name="adm_admin_update")
     * @Method({"GET","POST"})
     * @ParamConverter("admin", class="AppBundle:Admin")
     */
    public function updateAction(Request $request, Admin $admin)
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($admin);
            $em->persist($admin->getUser()->encodeNewPassword($this->get('security.password_encoder')));
            $em->flush();

            $this->addFlash('success', 'Data has been saved');

            return $this->redirectToRoute('adm_admin');
        }

        return $this->render('admin/admin/form.html.twig', [
            'form'=>$form->createView(),
            'title'=>'Edit #'.$admin->getName(),
            'menu_active'=>'adm_admin',
        ]);
    }

    /**
     * @Route("/adm/admin/{id}/delete", name="adm_admin_delete")
     * @Method({"DELETE"})
     * @ParamConverter("admin", class="AppBundle:Admin")
     */
    public function deleteAction(Admin $admin)
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
