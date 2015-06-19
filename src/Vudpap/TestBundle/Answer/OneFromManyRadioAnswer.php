<?php

namespace Vudpap\TestBundle\Answer;


use Symfony\Component\Validator\Constraints\NotBlank;
use Vudpap\TestBundle\Provider\Answer\AnswerProviderAbstract;

class OneFromManyRadioAnswer extends AnswerProviderAbstract
{
    const FORM_FIELD = 'answer';

    /** @var \Symfony\Component\Form\Form */
    private $form;

    public function initForm($translationDomain = 'messages')
    {
        $this->form = $this->container->get('form.factory')->createBuilder()
            ->add(
                self::FORM_FIELD,
                'choice',
                [
                    'choices' => $this->getStructure(),
                    'expanded' => true,
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
     * @param $data
     * @return bool
     */
    public function process($data)
    {
        $this->form->handleRequest($data);

        if ($this->form->isValid()) {
            $this->setAnswer($this->form->get(self::FORM_FIELD)->getData());

            return true;
        }

        return false;
    }

    public function render($params = [])
    {
        if ($this->form->isSubmitted() and $this->form->isValid()) {
            // clear submitted data in form from previous answer to not show in the next answer
            $this->initForm();
        }

        if ($this->hasAnswer()) {
            $this->form->get(self::FORM_FIELD)->setData(
                $this->getAnswer()
            );
        }

        return $this->container->get('templating')->render(
            $this->template,
            array_merge(
                $params,
                ['form' => $this->form->createView()]
            )
        );
    }
}
