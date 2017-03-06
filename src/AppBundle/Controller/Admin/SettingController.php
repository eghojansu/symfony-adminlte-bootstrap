<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Setting;
use AppBundle\Utils\Config;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class SettingController extends Controller
{
    /**
     * @Route("/adm/setting", name="adm_setting")
     * @Method({"GET", "POST"})
     */
    public function settingAction(Request $request)
    {
        $configs = $this->get('app.utils.config')->getAll();
        $form = $this->createFormBuilder($configs)
            ->add('app_name', TextType::class, [
                'label'=>'Nama',
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>50]),
                ],
            ])
            ->add('app_alias', TextType::class, [
                'label'=>'Alias',
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>10]),
                ],
            ])
            ->add('app_description', TextareaType::class, [
                'label'=>'Deskripsi',
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>100]),
                ],
            ])
            ->add('app_owner', TextType::class, [
                'label'=>'Owner',
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>50]),
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $repo = $em->getRepository('AppBundle:Setting');

            $data = $form->getData();
            foreach ($configs as $name => $value) {
                $config = $repo->findOneByName($name)?:new Setting();
                $config->setName($name);
                $config->setContent($data[$name]);

                $em->persist($config);
            }

            $em->flush();

            $this->addFlash('success', 'Configuration has been saved');

            return $this->redirectToRoute('adm_setting');
        }

        return $this->render('admin/setting/form.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}
