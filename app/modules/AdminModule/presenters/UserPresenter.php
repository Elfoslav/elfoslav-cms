<?php

namespace AdminModule;

use Nette\Application\BadRequestException,
	Nette\Application\UI\Form,
	Models\Authenticator;
/**
 * User presenter
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
class UserPresenter extends SecuredPresenter
{
	public function renderDefault() {
		$this->template->users = $this->userRepository->findAll();
	}

	public function actionCreate() {
		$this['userForm']->onSuccess[] = callback($this, 'createUserSubmitted');
	}

	public function actionDelete($id) {
		$user = $this->userRepository->find($id);
		if(!$user) {
			throw new BadRequestException('User not found');
		}
		$this->em->remove($user);
		$this->em->flush();
		$this->flashMessage($this->translator->translate('Uživateľ zmazaný'));
		$this->redirect('default');
	}

	public function createUserSubmitted($form) {
		$values = $form->getValues(TRUE);
		dump($values);
		$existingUser = $this->userRepository->findOneBy(array('username' => $values['username']));
		if($existingUser) {
			$this->flashMessage($this->translator->translate(sprintf('Užívateľ s menom %s už existuje', $values['username'])));
			return;
		}
		$user = new \Entities\User;
		$user->fromArray($values);
		//hash password
		$user->setPassword(Authenticator::calculateHash($values['password']));
		$this->em->persist($user);
		try {
			$this->em->flush();
		} catch (\Exception $e) {
			//TODO: log exception/send email
			throw $e;
		}
		$this->flashMessage($this->translator->translate('Užívateľ bol vytvorený'));
		$this->redirect('default');
	}

	public function actionUpdate($id = 0) {
		$user = $this->userRepository->find($id);
		if(!$user) {
			throw new BadRequestException('user does not exist');
		}
		$this['userForm']->addHidden('id');
		$this['userForm']->setDefaults($user->toArray());

		$oldPassword = new \Nette\Forms\Controls\TextInput('Staré heslo');
		$oldPassword->setType('password');
		//insert component "oldPassword" before "password"
		$this['userForm']->addComponent($oldPassword, 'oldPassword', 'password');
		$this['userForm']->onSuccess[] = callback($this, 'updateUserSubmitted');
	}

	public function updateUserSubmitted($form) {
		$values = $form->getValues(TRUE);
		$id = $values['id'];
		$newPassword = $values['password'];
		$oldPassword = $values['oldPassword'];
		unset($values['id'], $values['oldPassword'], $values['password']);
		dump($values);

		$user = $this->userRepository->find($id);
		if(!$user) {
			throw new BadRequestException('user does not exist');
		}
		//if user filled in old password and new password
		if($newPassword && $oldPassword) {
			$oldPassword = Authenticator::calculateHash($oldPassword, $user->getPassword());
			if($user->getPassword() !== $oldPassword) {
				$form->addError('Nesprávne staré heslo');
				return;
			}
			$user->setPassword(Authenticator::calculateHash($newPassword));
			$this->flashMessage($this->translator->translate('Heslo bolo zmenené'));
		}

		$user->fromArray($values);
		try {
			$this->em->flush();
		} catch (\Exception $e) {
			//TODO: log exception/send mail
			throw $e;
		}
		$this->flashMessage($this->translator->translate('Užívateľ uložený'));
		$this->redirect('this');
	}

	public function createComponentUserForm() {
		$form = new Form;

		$form->addText('username', 'Užív. meno')
			->setRequired('Meno je povinné');
		$form->addPassword('password', 'Heslo');
		$form->addText('email', 'Email')
			->setRequired('Email je povinný');

		$form->addSubmit('submit', 'Uložiť');

		return $form;
	}

	/** @var \Repositories\UserRepository */
	protected $userRepository;

	public function injectUserRepository(\Repositories\UserRepository $userRepo) {
		if ($this->userRepository) {
			throw new \Nette\InvalidStateException('UserRepository has already been set');
		}
		$this->userRepository = $userRepo;
	}
}
