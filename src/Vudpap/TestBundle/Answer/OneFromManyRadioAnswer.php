<?php

namespace Vudpap\TestBundle\Answer;


use Symfony\Component\Validator\Constraints\NotBlank;
use Vudpap\TestBundle\Provider\Answer\AnswerProviderAbstract;

class OneFromManyRadioAnswer extends AnswerProviderAbstract
{
    /** @var \Symfony\Component\Form\Form */
    private $form;

    public function __construct($structure)
    {
        $this->setStructure($structure);
    }

    public function initForm($translationDomain = 'messages')
    {
        $this->form = $this->container->get('form.factory')->createBuilder()
            ->add(
                'answer',
                'choice',
                [
                    'choices' => $this->getStructure(),
                    'translation_domain' => $translationDomain,
                    'constraints' => new NotBlank(),
                    'required' => true
                ]
            )
            ->getForm();
    }

    /**
     * Process answer data
     *
     * @param $question
     * @param $data
     * @return bool
     */
    public function handleAnswer($question, $data)
    {
        $this->form->handleRequest($data);

        if ($this->form->isValid()) {
            $this->addAnswered($question, $this->form->get('answer')->getData());

            return true;
        }

        return false;
    }

    public function render($template, $question)
    {
        if (!$this->form->isSubmitted() and $this->isAnswered($question)) {
            $this->form->get('answer')->setData(
                $this->getAnswered($question)
            );
        }

        return $this->container->get('templating')->render(
            $template,
            [
                'form' => $this->form->createView()
            ]
        );
    }
}
