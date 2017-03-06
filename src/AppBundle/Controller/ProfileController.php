<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     * @Method({"GET", "POST"})
     */
    public function updateAction(Request $request)
    {
        $user = $this->getUser();
        $oldAvatar = $user->getAvatar();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->encodeNewPassword($this->get('security.password_encoder'));

            $avatar = $user->getAvatar();
            if ($avatar) {
                $uploadDir = $this->get('kernel')->getUploadDir();
                $fileName = $this->get('app.utils.string')->randomString(10).'.'.$avatar->guessExtension();
                $avatar->move($uploadDir, $fileName);

                $user->setAvatar($fileName);

                // delete old
                @unlink($uploadDir.'/'.$oldAvatar);
            } else {
                $user->setAvatar($oldAvatar);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Profile has been updated');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}
